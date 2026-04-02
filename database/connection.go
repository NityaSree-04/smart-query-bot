package database

import (
	"database/sql"
	"fmt"

	_ "github.com/go-sql-driver/mysql"
	_ "github.com/lib/pq"
	_ "github.com/mattn/go-sqlite3"
)

type Database interface {
	Query(query string, args ...interface{}) (*sql.Rows, error)
	GetSchema() ([]TableInfo, error)
	Close() error
}

type TableInfo struct {
	Name    string
	Columns []ColumnInfo
}

type ColumnInfo struct {
	Name     string
	Type     string
	Nullable bool
}

type DBConnection struct {
	db     *sql.DB
	dbType string
}

func NewConnection(dbType, connectionString string) (*DBConnection, error) {
	var db *sql.DB
	var err error

	switch dbType {
	case "postgres":
		db, err = sql.Open("postgres", connectionString)
	case "mysql":
		db, err = sql.Open("mysql", connectionString)
	case "sqlite":
		db, err = sql.Open("sqlite3", connectionString)
	default:
		return nil, fmt.Errorf("unsupported database type: %s", dbType)
	}

	if err != nil {
		return nil, fmt.Errorf("failed to open database: %w", err)
	}

	// Test the connection
	if err := db.Ping(); err != nil {
		return nil, fmt.Errorf("failed to ping database: %w", err)
	}

	return &DBConnection{
		db:     db,
		dbType: dbType,
	}, nil
}

func (d *DBConnection) Query(query string, args ...interface{}) (*sql.Rows, error) {
	return d.db.Query(query, args...)
}

func (d *DBConnection) GetSchema() ([]TableInfo, error) {
	var tables []TableInfo
	var err error

	switch d.dbType {
	case "postgres":
		tables, err = d.getPostgresSchema()
	case "mysql":
		tables, err = d.getMySQLSchema()
	case "sqlite":
		tables, err = d.getSQLiteSchema()
	default:
		return nil, fmt.Errorf("unsupported database type: %s", d.dbType)
	}

	return tables, err
}

func (d *DBConnection) getPostgresSchema() ([]TableInfo, error) {
	query := `
		SELECT table_name 
		FROM information_schema.tables 
		WHERE table_schema = 'public' AND table_type = 'BASE TABLE'
		ORDER BY table_name
	`
	rows, err := d.db.Query(query)
	if err != nil {
		return nil, err
	}
	defer rows.Close()

	var tables []TableInfo
	for rows.Next() {
		var tableName string
		if err := rows.Scan(&tableName); err != nil {
			return nil, err
		}

		columns, err := d.getPostgresColumns(tableName)
		if err != nil {
			return nil, err
		}

		tables = append(tables, TableInfo{
			Name:    tableName,
			Columns: columns,
		})
	}

	return tables, nil
}

func (d *DBConnection) getPostgresColumns(tableName string) ([]ColumnInfo, error) {
	query := `
		SELECT column_name, data_type, is_nullable
		FROM information_schema.columns
		WHERE table_name = $1
		ORDER BY ordinal_position
	`
	rows, err := d.db.Query(query, tableName)
	if err != nil {
		return nil, err
	}
	defer rows.Close()

	var columns []ColumnInfo
	for rows.Next() {
		var col ColumnInfo
		var nullable string
		if err := rows.Scan(&col.Name, &col.Type, &nullable); err != nil {
			return nil, err
		}
		col.Nullable = nullable == "YES"
		columns = append(columns, col)
	}

	return columns, nil
}

func (d *DBConnection) getMySQLSchema() ([]TableInfo, error) {
	query := "SHOW TABLES"
	rows, err := d.db.Query(query)
	if err != nil {
		return nil, err
	}
	defer rows.Close()

	var tables []TableInfo
	for rows.Next() {
		var tableName string
		if err := rows.Scan(&tableName); err != nil {
			return nil, err
		}

		columns, err := d.getMySQLColumns(tableName)
		if err != nil {
			return nil, err
		}

		tables = append(tables, TableInfo{
			Name:    tableName,
			Columns: columns,
		})
	}

	return tables, nil
}

func (d *DBConnection) getMySQLColumns(tableName string) ([]ColumnInfo, error) {
	query := fmt.Sprintf("DESCRIBE %s", tableName)
	rows, err := d.db.Query(query)
	if err != nil {
		return nil, err
	}
	defer rows.Close()

	var columns []ColumnInfo
	for rows.Next() {
		var col ColumnInfo
		var null, key, defaultVal, extra sql.NullString
		if err := rows.Scan(&col.Name, &col.Type, &null, &key, &defaultVal, &extra); err != nil {
			return nil, err
		}
		col.Nullable = null.String == "YES"
		columns = append(columns, col)
	}

	return columns, nil
}

func (d *DBConnection) getSQLiteSchema() ([]TableInfo, error) {
	query := "SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name"
	rows, err := d.db.Query(query)
	if err != nil {
		return nil, err
	}
	defer rows.Close()

	var tables []TableInfo
	for rows.Next() {
		var tableName string
		if err := rows.Scan(&tableName); err != nil {
			return nil, err
		}

		columns, err := d.getSQLiteColumns(tableName)
		if err != nil {
			return nil, err
		}

		tables = append(tables, TableInfo{
			Name:    tableName,
			Columns: columns,
		})
	}

	return tables, nil
}

func (d *DBConnection) getSQLiteColumns(tableName string) ([]ColumnInfo, error) {
	query := fmt.Sprintf("PRAGMA table_info(%s)", tableName)
	rows, err := d.db.Query(query)
	if err != nil {
		return nil, err
	}
	defer rows.Close()

	var columns []ColumnInfo
	for rows.Next() {
		var col ColumnInfo
		var cid int
		var notNull int
		var dfltValue sql.NullString
		var pk int
		if err := rows.Scan(&cid, &col.Name, &col.Type, &notNull, &dfltValue, &pk); err != nil {
			return nil, err
		}
		col.Nullable = notNull == 0
		columns = append(columns, col)
	}

	return columns, nil
}

func (d *DBConnection) Close() error {
	return d.db.Close()
}
