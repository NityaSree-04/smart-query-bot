# 🚀 Ollama Local AI Setup Guide

## Overview
Your PHP chatbot has been updated to use **Ollama** running locally on your laptop instead of Groq API. This means:
- ✅ No API key required
- ✅ No internet dependency for AI inference
- ✅ Complete privacy - all data stays on your machine
- ✅ Free unlimited usage

## What Was Changed

### 1. New Files Created
- **`api/OllamaAI.php`** - New AI class for Ollama communication
- **`view_all_data.php`** - View all 1570 EAPCET records with DataTables
- **`test_ollama.php`** - Test script to verify Ollama setup

### 2. Modified Files
- **`api/config.php`** - Updated to use Ollama configuration
- **`api/chat.php`** - Changed from GroqAI to OllamaAI

## Prerequisites

### 1. Ollama Installation
Ollama should already be installed on your laptop. Verify with:
```powershell
ollama --version
```

If not installed, download from: https://ollama.ai/download

### 2. Model Installation
The `qwen2.5-coder:7b` model should already be running. Verify with:
```powershell
ollama list
```

You should see `qwen2.5-coder:7b` in the list.

If not installed:
```powershell
ollama pull qwen2.5-coder:7b
```

### 3. Ollama Server
Make sure Ollama is running:
```powershell
ollama serve
```

**Note:** On Windows, Ollama usually runs automatically as a service. Check if it's accessible at http://localhost:11434

## Configuration

Your `api/config.php` now has:
```php
define('OLLAMA_BASE_URL', 'http://localhost:11434');
define('OLLAMA_MODEL', 'qwen2.5-coder:7b');
```

## Testing the Setup

### Step 1: Test Ollama Connection
Run the test script:
```powershell
php test_ollama.php
```

Expected output:
```
=== Ollama Connection Test ===

Configuration:
- Base URL: http://localhost:11434
- Model: qwen2.5-coder:7b

Test 1: Checking Ollama server...
✅ Ollama server is running!

Available models:
  - qwen2.5-coder:7b

Test 2: Testing chat completion...
✅ Model responded successfully!
Response: Hello from Ollama!

Test 3: Testing SQL generation...
Question: How many colleges are there?
✅ SQL generated successfully!
SQL: SELECT COUNT(*) as total_colleges FROM apeamcet2024lastrankdetailsnonsw WHERE `COL 1` NOT IN ('APEAPCET-2024[ M P C STREAM] LAST RANK DETAILS  ', 'SNO');

=== All Tests Passed! ===
```

### Step 2: Test the Chatbot
1. Open your browser
2. Navigate to: `http://localhost/Ideathon/chatbot.html` (or your XAMPP path)
3. Try asking: "How many colleges are there?"
4. The chatbot should respond using local Ollama AI

### Step 3: View All Data
Navigate to: `http://localhost/Ideathon/view_all_data.php`

This page displays all 1570 EAPCET records with:
- 🔍 Search across all columns
- 📊 Sort by any column
- 📄 Export to CSV, Excel, PDF
- 🖨️ Print-friendly view
- 📱 Responsive design

## How It Works

### 1. User asks a question
```
User: "Show me CSE colleges with rank under 10000"
```

### 2. OllamaAI generates SQL
```php
$ai = new OllamaAI('qwen2.5-coder:7b', 'http://localhost:11434');
$sqlResult = $ai->generateSQL($question, $schemaDescription);
```

Ollama generates:
```sql
SELECT `COL 3`, `COL 12`, `COL 13`, `COL 31`, `COL 7` 
FROM apeamcet2024lastrankdetailsnonsw 
WHERE `COL 12` = 'CSE' AND `COL 13` <= 10000 
ORDER BY `COL 13` ASC;
```

### 3. Execute SQL on MySQL
```php
$db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$queryResult = $db->query($sql);
```

### 4. Format response
- If ≤50 rows: Show HTML table + AI summary
- If >50 rows: Show AI summary + link to `view_all_results.php`

## Database Table

Your EAPCET data is in table: **`apeamcet2024lastrankdetailsnonsw`**

Columns (32 total):
- `COL 1` - Serial Number
- `COL 2` - Institution Code
- `COL 3` - College Name
- `COL 12` - Branch Code (CSE, ECE, EEE, etc.)
- `COL 13` - OC Boys Closing Rank
- `COL 14` - OC Girls Closing Rank
- `COL 15-30` - Other category ranks
- `COL 31` - College Fee
- And more...

## API Endpoint

The chatbot uses: **`http://localhost:11434/v1/chat/completions`**

This is Ollama's OpenAI-compatible API endpoint.

## Troubleshooting

### Issue: "Ollama server is not responding"
**Solution:**
```powershell
# Start Ollama server
ollama serve
```

### Issue: "Model not found (HTTP 404)"
**Solution:**
```powershell
# Pull the model
ollama pull qwen2.5-coder:7b

# Verify it's installed
ollama list
```

### Issue: "Connection timeout"
**Solution:**
- Check if Ollama is running: `http://localhost:11434`
- Restart Ollama service
- Check firewall settings

### Issue: "SQL generation is slow"
**Note:** Local inference is slower than cloud APIs like Groq, but:
- It's completely free
- No API limits
- Complete privacy

For the 7B model, expect:
- SQL generation: 2-5 seconds
- Response formatting: 3-8 seconds

## Performance Tips

### 1. Use a GPU
If you have an NVIDIA GPU, Ollama will automatically use it for faster inference.

### 2. Adjust Model Size
- Faster: `ollama pull qwen2.5-coder:3b` (smaller, faster)
- Better: `qwen2.5-coder:7b` (current, balanced)
- Best: `qwen2.5-coder:14b` (larger, slower, more accurate)

Update `api/config.php`:
```php
define('OLLAMA_MODEL', 'qwen2.5-coder:3b'); // For faster responses
```

### 3. Keep Ollama Running
Don't stop the Ollama server between requests to avoid model loading time.

## Files Summary

### Main Chatbot Files
- `chatbot.html` - Frontend interface
- `api/chat.php` - Main chatbot endpoint (now uses OllamaAI)
- `api/OllamaAI.php` - Ollama AI integration
- `api/Database.php` - MySQL database handler
- `api/config.php` - Configuration (Ollama settings)

### Data Viewing Files
- `view_all_results.php` - View query results (from chatbot)
- `view_all_data.php` - View all 1570 records (direct database view)

### Testing Files
- `test_ollama.php` - Test Ollama connection and functionality

## Next Steps

1. ✅ Run `php test_ollama.php` to verify setup
2. ✅ Open `chatbot.html` in browser
3. ✅ Ask questions about EAPCET data
4. ✅ View all data at `view_all_data.php`

## Example Questions to Try

1. "How many colleges are there?"
2. "Show me all CSE colleges"
3. "I have rank 50000 in OC category as a boy. Which CSE colleges can I get?"
4. "What are the top 10 colleges with lowest fees?"
5. "Compare average cutoffs for all branches"
6. "Show me all colleges in Hyderabad"

## Benefits of Local Ollama

✅ **Privacy**: All data stays on your machine
✅ **Cost**: Completely free, no API costs
✅ **Reliability**: No internet dependency
✅ **Control**: Full control over model and parameters
✅ **Speed**: No network latency (after model loads)

## Support

If you encounter any issues:
1. Check Ollama is running: `http://localhost:11434`
2. Run test script: `php test_ollama.php`
3. Check Ollama logs for errors
4. Verify model is installed: `ollama list`

---

**Ready to use!** Your chatbot now runs 100% locally with Ollama. 🎉
