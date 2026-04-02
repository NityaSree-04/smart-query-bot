package ai

import (
	"db-chat-ai/database"
	"fmt"
	"strings"
)

type Prompt struct {
	System string
	User   string
}

func BuildSQLPrompt(schema []database.TableInfo, userQuestion string) Prompt {
	schemaDescription := buildSchemaDescription(schema)

	systemPrompt := fmt.Sprintf(`You are a SQL expert. Your task is to convert natural language questions into SQL queries.

Database Schema:
%s

Rules:
1. Generate ONLY the SQL query, no explanations or markdown formatting
2. Use proper SQL syntax for the database type
3. Always use proper JOIN conditions when querying multiple tables
4. Limit results to a reasonable number (e.g., LIMIT 100) unless specifically asked for all
5. Use appropriate WHERE clauses to filter data
6. Return only SELECT queries for safety
7. Do not use DELETE, UPDATE, INSERT, DROP, or other modifying statements

Examples:
Question: "How many users are there?"
SQL: SELECT COUNT(*) as total_users FROM users;

Question: "Show me the 5 most recent orders"
SQL: SELECT * FROM orders ORDER BY created_at DESC LIMIT 5;

Question: "What are the names of all products?"
SQL: SELECT name FROM products;`, schemaDescription)

	userPrompt := fmt.Sprintf("Convert this question to SQL: %s", userQuestion)

	return Prompt{
		System: systemPrompt,
		User:   userPrompt,
	}
}

func buildSchemaDescription(schema []database.TableInfo) string {
	var sb strings.Builder

	for _, table := range schema {
		sb.WriteString(fmt.Sprintf("\nTable: %s\n", table.Name))
		sb.WriteString("Columns:\n")
		for _, col := range table.Columns {
			nullable := "NOT NULL"
			if col.Nullable {
				nullable = "NULL"
			}
			sb.WriteString(fmt.Sprintf("  - %s (%s, %s)\n", col.Name, col.Type, nullable))
		}
	}

	return sb.String()
}
