# 🎯 Quick Start - Ollama Chatbot

## What's New?
Your chatbot now uses **LOCAL Ollama AI** instead of Groq API!

## Files Created/Updated

### ✅ New Files
1. **`api/OllamaAI.php`** - Handles Ollama API communication
2. **`view_all_data.php`** - View all 1570 EAPCET records with search/sort
3. **`test_ollama.php`** - Test Ollama connection
4. **`OLLAMA_SETUP_GUIDE.md`** - Detailed setup guide

### ✅ Updated Files
1. **`api/config.php`** - Now uses Ollama configuration (no API key!)
2. **`api/chat.php`** - Uses OllamaAI instead of GroqAI

## Quick Test

### 1. Test Ollama Connection
```powershell
cd C:\Users\nitya\OneDrive\Desktop\Ideathon
php test_ollama.php
```

Expected: All tests pass ✅

### 2. Open Chatbot
Navigate to: `http://localhost/Ideathon/chatbot.html`

### 3. View All Data
Navigate to: `http://localhost/Ideathon/view_all_data.php`

## Key Features

### Chatbot (`chatbot.html` → `api/chat.php`)
- Uses local Ollama at `http://localhost:11434`
- Model: `qwen2.5-coder:7b`
- Generates SQL from natural language
- Executes on MySQL database
- Smart response handling:
  - ≤50 rows: Shows HTML table
  - >50 rows: Shows summary + link to full view

### View All Data (`view_all_data.php`)
- Displays all 1570 EAPCET records
- DataTables.js features:
  - 🔍 Search across all columns
  - 📊 Sort by any column
  - 📄 Export to CSV/Excel/PDF
  - 🖨️ Print view
  - 📱 Responsive design
  - 🎯 Column filters for quick filtering

## Database Info

**Table:** `apeamcet2024lastrankdetailsnonsw`
**Rows:** 1570 (excluding 2 header rows)
**Columns:** 32

Key columns:
- `COL 2` - Institution Code
- `COL 3` - College Name
- `COL 12` - Branch Code (CSE, ECE, EEE, etc.)
- `COL 13-30` - Closing ranks for different categories/genders
- `COL 31` - College Fee

## How It Works

```
User Question
    ↓
OllamaAI.php (generates SQL)
    ↓
Database.php (executes SQL)
    ↓
Results
    ↓
├─ ≤50 rows → Show table + AI summary
└─ >50 rows → Show summary + link to view_all_results.php
```

## Example Questions

1. "How many colleges are there?"
2. "Show me all CSE colleges"
3. "I have rank 50000 in OC category. Which colleges can I get?"
4. "Top 10 colleges with lowest fees"
5. "All colleges in Hyderabad"

## Troubleshooting

### Ollama not responding?
```powershell
# Check if running
curl http://localhost:11434

# Start Ollama (if needed)
ollama serve
```

### Model not found?
```powershell
# List models
ollama list

# Pull model (if needed)
ollama pull qwen2.5-coder:7b
```

## Performance Notes

- **SQL Generation:** 2-5 seconds (local inference)
- **Response Formatting:** 3-8 seconds
- **Total:** ~5-15 seconds per query

This is slower than cloud APIs but:
- ✅ Completely free
- ✅ No API limits
- ✅ Full privacy
- ✅ Works offline

## Ready to Use!

1. Make sure Ollama is running
2. Open `chatbot.html`
3. Start asking questions!

For detailed information, see `OLLAMA_SETUP_GUIDE.md`
