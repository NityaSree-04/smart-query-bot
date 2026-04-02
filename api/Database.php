<?php
/**
 * Database Class
 * Handles MySQL database connections, queries, and schema introspection
 */

class Database {
    private $conn;
    private $host;
    private $user;
    private $pass;
    private $dbname;
    
    public function __construct($host, $user, $pass, $dbname) {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->dbname = $dbname;
        $this->connect();
    }
    
    /**
     * Establish database connection
     */
    private function connect() {
        try {
            $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
            
            if ($this->conn->connect_error) {
                throw new Exception("Connection failed: " . $this->conn->connect_error);
            }
            
            $this->conn->set_charset("utf8mb4");
        } catch (Exception $e) {
            throw new Exception("Database connection error: " . $e->getMessage());
        }
    }
    
    /**
     * Execute a SQL query and return results
     */
    public function query($sql) {
        try {
            $result = $this->conn->query($sql);
            
            if ($result === false) {
                throw new Exception("Query error: " . $this->conn->error);
            }
            
            if ($result === true) {
                return ['success' => true, 'affected_rows' => $this->conn->affected_rows];
            }
            
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            
            return [
                'success' => true,
                'data' => $data,
                'count' => count($data)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Get database schema (tables and columns)
     */
    public function getSchema() {
        try {
            $schema = [];
            
            // Get all tables
            $tablesQuery = "SHOW TABLES";
            $tablesResult = $this->conn->query($tablesQuery);
            
            if (!$tablesResult) {
                throw new Exception("Failed to get tables: " . $this->conn->error);
            }
            
            while ($tableRow = $tablesResult->fetch_array()) {
                $tableName = $tableRow[0];
                
                // Get columns for each table
                $columnsQuery = "DESCRIBE `$tableName`";
                $columnsResult = $this->conn->query($columnsQuery);
                
                $columns = [];
                while ($columnRow = $columnsResult->fetch_assoc()) {
                    $columns[] = [
                        'name' => $columnRow['Field'],
                        'type' => $columnRow['Type'],
                        'null' => $columnRow['Null'] === 'YES',
                        'key' => $columnRow['Key'],
                        'default' => $columnRow['Default'],
                        'extra' => $columnRow['Extra']
                    ];
                }
                
                $schema[] = [
                    'table' => $tableName,
                    'columns' => $columns
                ];
            }
            
            return [
                'success' => true,
                'schema' => $schema
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Build schema description for AI prompts
     */
    public function getSchemaDescription() {
        $schemaResult = $this->getSchema();
        
        if (!$schemaResult['success']) {
            return "Unable to retrieve schema";
        }
        
        // Special column mappings for EAPCET table
        $eapcetColumnMappings = [
            'COL 1' => 'Serial Number (SNO) - IMPORTANT: Use `COL 1` with backticks and space',
            'COL 2' => 'Institution Code (INSTCODE) - Use `COL 2` with backticks and space',
            'COL 3' => 'College Name (NAME OF THE INSTITUTION) - Use `COL 3` with backticks and space',
            'COL 4' => 'Institution Type (TYPE) - PVT/GOVT - Use `COL 4` with backticks and space',
            'COL 5' => 'Institution Region (INST_REG) - AU/SVU/OU - Use `COL 5` with backticks and space',
            'COL 6' => 'District Code (DIST) - Use `COL 6` with backticks and space',
            'COL 7' => 'Place/Location (PLACE) - Use `COL 7` with backticks and space',
            'COL 8' => 'Co-education Status (COED) - Use `COL 8` with backticks and space',
            'COL 9' => 'University Affiliation (AFFL.) - JNTUK/JNTUA/JNTUH - Use `COL 9` with backticks and space',
            'COL 10' => 'Established Year (ESTD) - Use `COL 10` with backticks and space',
            'COL 11' => 'Admission Region (A_REG) - Use `COL 11` with backticks and space',
            'COL 12' => 'Branch/Course Code (branch_code) - CSE/ECE/EEE/MECH/CIVIL/etc - CRITICAL: Use `COL 12` with backticks and space, NOT COL_12',
            'COL 13' => 'OC Boys Closing Rank (OC_BOYS) - Use `COL 13` with backticks and space',
            'COL 14' => 'OC Girls Closing Rank (OC_GIRLS) - Use `COL 14` with backticks and space',
            'COL 15' => 'SC Boys Closing Rank (SC_BOYS) - Use `COL 15` with backticks and space',
            'COL 16' => 'SC Girls Closing Rank (SC_GIRLS) - Use `COL 16` with backticks and space',
            'COL 17' => 'ST Boys Closing Rank (ST_BOYS) - Use `COL 17` with backticks and space',
            'COL 18' => 'ST Girls Closing Rank (ST_GIRLS) - Use `COL 18` with backticks and space',
            'COL 19' => 'BC-A Boys Closing Rank (BCA_BOYS) - Use `COL 19` with backticks and space',
            'COL 20' => 'BC-A Girls Closing Rank (BCA_GIRLS) - Use `COL 20` with backticks and space',
            'COL 21' => 'BC-B Boys Closing Rank (BCB_BOYS) - Use `COL 21` with backticks and space',
            'COL 22' => 'BC-B Girls Closing Rank (BCB_GIRLS) - Use `COL 22` with backticks and space',
            'COL 23' => 'BC-C Boys Closing Rank (BCC_BOYS) - Use `COL 23` with backticks and space',
            'COL 24' => 'BC-C Girls Closing Rank (BCC_GIRLS) - Use `COL 24` with backticks and space',
            'COL 25' => 'BC-D Boys Closing Rank (BCD_BOYS) - Use `COL 25` with backticks and space',
            'COL 26' => 'BC-D Girls Closing Rank (BCD_GIRLS) - Use `COL 26` with backticks and space',
            'COL 27' => 'BC-E Boys Closing Rank (BCE_BOYS) - Use `COL 27` with backticks and space',
            'COL 28' => 'BC-E Girls Closing Rank (BCE_GIRLS) - Use `COL 28` with backticks and space',
            'COL 29' => 'OC-EWS Boys Closing Rank (OC_EWS_BOYS) - Use `COL 29` with backticks and space',
            'COL 30' => 'OC-EWS Girls Closing Rank (OC_EWS_GIRLS) - Use `COL 30` with backticks and space',
            'COL 31' => 'College Fee Amount (COLLFEE) - Use `COL 31` with backticks and space',
            'COL 32' => 'Reserved/Extra Column - Use `COL 32` with backticks and space'
        ];
        
        $description = "";
        foreach ($schemaResult['schema'] as $table) {
            $description .= "\nTable: {$table['table']}\n";
            
            // Add special description for EAPCET table
            if ($table['table'] === 'apeamcet2024') {
                $description .= "Description: AP EAPCET 2024 Engineering College Admission Last Rank Details\n";
                $description .= "This table contains closing ranks for different categories and genders for ~1565 engineering colleges and branches.\n";
                $description .= "\nIMPORTANT NOTES:\n";
                $description .= "- Total colleges: ~1565 records\n";
                $description .= "- Categories: OC (Open Category), SC (Scheduled Caste), ST (Scheduled Tribe), BC-A/B/C/D/E (Backward Classes), OC-EWS (Economically Weaker Section)\n";
                $description .= "- Gender: BOYS or GIRLS columns\n";
                $description .= "- Lower rank number = Better rank (e.g., rank 1000 is better than rank 5000)\n";
                $description .= "- Empty or very high ranks may indicate no admissions in that category\n";
                $description .= "- Skip the first 2 rows as they contain headers\n\n";
            }
            
            $description .= "Columns:\n";
            
            foreach ($table['columns'] as $column) {
                $nullable = $column['null'] ? 'NULL' : 'NOT NULL';
                $columnName = $column['name'];
                
                // Use mapped name if available
                if (isset($eapcetColumnMappings[$columnName])) {
                    $description .= "  - `{$columnName}` = {$eapcetColumnMappings[$columnName]} ({$column['type']}, {$nullable})";
                } else {
                    $description .= "  - {$columnName} ({$column['type']}, {$nullable})";
                }
                
                if ($column['key'] === 'PRI') {
                    $description .= " [PRIMARY KEY]";
                }
                
                $description .= "\n";
            }
        }
        
        return $description;
    }
    
    /**
     * Validate that query is a SELECT statement
     */
    public function isSelectQuery($sql) {
        $sql = trim(strtoupper($sql));
        return strpos($sql, 'SELECT') === 0;
    }
    
    /**
     * Close database connection
     */
    public function close() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
    
    /**
     * Get connection object
     */
    public function getConnection() {
        return $this->conn;
    }
}
?>
