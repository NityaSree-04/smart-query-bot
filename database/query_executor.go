package database

import (
	"encoding/json"
	"fmt"
)

type QueryResult struct {
	Columns []string        `json:"columns"`
	Rows    [][]interface{} `json:"rows"`
	Count   int             `json:"count"`
}

func ExecuteQuery(db *DBConnection, query string, args ...interface{}) (*QueryResult, error) {
	rows, err := db.Query(query, args...)
	if err != nil {
		return nil, fmt.Errorf("query execution failed: %w", err)
	}
	defer rows.Close()

	// Get column names
	columns, err := rows.Columns()
	if err != nil {
		return nil, fmt.Errorf("failed to get columns: %w", err)
	}

	// Prepare result
	result := &QueryResult{
		Columns: columns,
		Rows:    make([][]interface{}, 0),
	}

	// Scan rows
	for rows.Next() {
		// Create a slice of interface{} to hold each column value
		values := make([]interface{}, len(columns))
		valuePtrs := make([]interface{}, len(columns))
		for i := range values {
			valuePtrs[i] = &values[i]
		}

		if err := rows.Scan(valuePtrs...); err != nil {
			return nil, fmt.Errorf("failed to scan row: %w", err)
		}

		// Convert byte slices to strings
		for i, v := range values {
			if b, ok := v.([]byte); ok {
				values[i] = string(b)
			}
		}

		result.Rows = append(result.Rows, values)
	}

	result.Count = len(result.Rows)

	if err := rows.Err(); err != nil {
		return nil, fmt.Errorf("row iteration error: %w", err)
	}

	return result, nil
}

func (qr *QueryResult) ToJSON() (string, error) {
	data, err := json.MarshalIndent(qr, "", "  ")
	if err != nil {
		return "", err
	}
	return string(data), nil
}

func (qr *QueryResult) ToString() string {
	if qr.Count == 0 {
		return "No results found."
	}

	result := fmt.Sprintf("Found %d row(s):\n\n", qr.Count)
	
	// Add column headers
	for i, col := range qr.Columns {
		if i > 0 {
			result += " | "
		}
		result += col
	}
	result += "\n"
	
	// Add separator
	for i := range qr.Columns {
		if i > 0 {
			result += "-+-"
		}
		result += "----------"
	}
	result += "\n"

	// Add rows
	for _, row := range qr.Rows {
		for i, val := range row {
			if i > 0 {
				result += " | "
			}
			result += fmt.Sprintf("%v", val)
		}
		result += "\n"
	}

	return result
}
