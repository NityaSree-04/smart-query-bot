package config

import (
	"fmt"
	"os"

	"github.com/joho/godotenv"
)

type Config struct {
	Server   ServerConfig
	OpenAI   OpenAIConfig
	Database DatabaseConfig
}

type ServerConfig struct {
	Host string
	Port string
}

type OpenAIConfig struct {
	APIKey  string
	BaseURL string // For OpenRouter: https://openrouter.ai/api/v1
	Model   string // Model to use (e.g., meta-llama/llama-3.2-3b-instruct:free)
}

type DatabaseConfig struct {
	Type     string // postgres, mysql, or sqlite
	Host     string
	Port     string
	User     string
	Password string
	DBName   string
	SQLitePath string
}

func Load() (*Config, error) {
	// Load .env file if it exists
	_ = godotenv.Load()

	config := &Config{
		Server: ServerConfig{
			Host: getEnv("SERVER_HOST", "localhost"),
			Port: getEnv("SERVER_PORT", "8080"),
		},
		OpenAI: OpenAIConfig{
			APIKey:  getEnv("OPENAI_API_KEY", ""),
			BaseURL: getEnv("OPENROUTER_BASE_URL", "https://api.openai.com/v1"),
			Model:   getEnv("OPENROUTER_MODEL", "gpt-4"),
		},
		Database: DatabaseConfig{
			Type:       getEnv("DB_TYPE", "sqlite"),
			Host:       getEnv("POSTGRES_HOST", "localhost"),
			Port:       getEnv("POSTGRES_PORT", "5432"),
			User:       getEnv("POSTGRES_USER", "postgres"),
			Password:   getEnv("POSTGRES_PASSWORD", ""),
			DBName:     getEnv("POSTGRES_DB", ""),
			SQLitePath: getEnv("SQLITE_PATH", "./data.db"),
		},
	}

	// Validate required fields
	if config.OpenAI.APIKey == "" {
		return nil, fmt.Errorf("OPENAI_API_KEY is required")
	}

	return config, nil
}

func getEnv(key, defaultValue string) string {
	if value := os.Getenv(key); value != "" {
		return value
	}
	return defaultValue
}
