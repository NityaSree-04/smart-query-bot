<?php
// Configuration file for the AI Database Chat application

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ai_chat_db');

// Ollama Settings
define('OLLAMA_BASE_URL', 'http://localhost:11434');
define('OLLAMA_MODEL_SQL', 'qwen2.5-coder:3b'); // SQL specialized model
define('OLLAMA_MODEL_CHAT', 'deepseek-r1:1.5b'); // Reasoning/Chat specialized model

// Set GROQ_API_KEY via environment variable
define('GROQ_API_KEY', getenv('GROQ_API_KEY'));
// Application Settings
define('APP_DEBUG', true);

// CORS Settings (for development)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Error reporting
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Timezone
date_default_timezone_set('Asia/Kolkata');
?>
