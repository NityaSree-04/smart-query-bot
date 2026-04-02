# 🔧 JSON Error Fix Guide

## Error Message
```
Failed to communicate with server: Unexpected token '<', "<br /><b>"... is not valid JSON
```

## What This Means
The server is returning an HTML error page instead of JSON. This happens when:
1. **Apache is not running**
2. **PHP has a fatal error**
3. **Required files are missing**

## ✅ Quick Fix Steps

### Step 1: Start Apache & MySQL
1. Open **XAMPP Control Panel**
2. Click **Start** next to **Apache** (if not green)
3. Click **Start** next to **MySQL** (if not green)
4. Wait for both to show green "Running" status

### Step 2: Test the API
Open in browser: **http://localhost/Ideathon/api/test_api.php**

You should see JSON output like:
```json
{
    "success": true,
    "message": "API Test Endpoint",
    "tests": {
        "php_version": "8.2.12",
        "config_loaded": true,
        "database_connection": "SUCCESS",
        "total_colleges": "1568",
        "ollama_server": "RUNNING"
    }
}
```

### Step 3: Check for Errors
If you see HTML errors instead of JSON:

#### Error: "Fatal error: Class 'OllamaAI' not found"
**Solution:**
```powershell
Copy-Item "C:\Users\nitya\OneDrive\Desktop\Ideathon\api\*" "C:\xampp\htdocs\Ideathon\api\" -Force -Recurse
```

#### Error: "Database connection failed"
**Solution:**
1. Make sure MySQL is running in XAMPP
2. Check database exists: http://localhost/phpmyadmin
3. Database name should be: `ai_chat_db`
4. Table should be: `apeamcet2024lastrankdetailsnonsw`

#### Error: "Ollama server not responding"
**Solution:**
```powershell
# Check if Ollama is running
Invoke-RestMethod -Uri "http://localhost:11434/api/tags" -Method Get
```

### Step 4: Refresh and Test Chatbot
1. **Clear browser cache:** Ctrl+Shift+Delete → Clear cache
2. **Refresh chatbot:** Ctrl+F5
3. **Try question:** "How many colleges are there?"

---

## 🔍 Detailed Diagnostics

### Check 1: Is Apache Running?
```powershell
netstat -ano | findstr :80
```
Should show Apache listening on port 80.

### Check 2: Test PHP Directly
```powershell
php C:\xampp\htdocs\Ideathon\api\test_api.php
```
Should output JSON without errors.

### Check 3: Check Apache Error Log
Location: `C:\xampp\apache\logs\error.log`

Look for recent errors related to Ideathon.

### Check 4: Test Database Connection
```powershell
php C:\Users\nitya\OneDrive\Desktop\Ideathon\test_db_query.php
```
Should show: "Total colleges: 1568"

---

## 🚀 Alternative: Use Groq Instead of Ollama

If Ollama keeps timing out or causing issues, switch to Groq for instant responses:

### Update `C:\xampp\htdocs\Ideathon\api\config.php`:
```php
// Comment out Ollama
// define('OLLAMA_BASE_URL', 'http://localhost:11434');
// define('OLLAMA_MODEL', 'qwen2.5-coder:7b');

// Use Groq instead
define('GROQ_API_KEY', 'YOUR_API_KEY_HERE');
define('GROQ_MODEL', 'llama-3.3-70b-versatile');
```

### Update `C:\xampp\htdocs\Ideathon\api\chat.php`:
Change line 15:
```php
require_once 'GroqAI.php';  // Instead of OllamaAI.php
```

Change lines 43-44:
```php
$ai = new GroqAI(GROQ_API_KEY, GROQ_MODEL);  // Instead of OllamaAI
```

**Groq Benefits:**
- ⚡ 1-2 second responses (vs 60+ seconds for Ollama)
- 🎯 Perfect for demos
- 🌐 Cloud-based, very reliable

---

## 📋 Checklist

Before testing chatbot, ensure:

- [ ] Apache is running (green in XAMPP)
- [ ] MySQL is running (green in XAMPP)
- [ ] Test API works: http://localhost/Ideathon/api/test_api.php
- [ ] Database has data (1568 colleges)
- [ ] Ollama is running (or switch to Groq)
- [ ] Browser cache cleared (Ctrl+Shift+Delete)

---

## 🎯 Quick Test Commands

```powershell
# 1. Check Apache
netstat -ano | findstr :80

# 2. Copy all files to XAMPP
Copy-Item "C:\Users\nitya\OneDrive\Desktop\Ideathon\api\*" "C:\xampp\htdocs\Ideathon\api\" -Force -Recurse

# 3. Test API
Start-Process "http://localhost/Ideathon/api/test_api.php"

# 4. Test chatbot
Start-Process "http://localhost/Ideathon/chatbot.html"
```

---

## 💡 Most Common Fix

**90% of the time, this fixes it:**

1. **Restart XAMPP:**
   - Stop Apache & MySQL
   - Start Apache & MySQL
   
2. **Clear browser cache:** Ctrl+Shift+Delete

3. **Refresh chatbot:** Ctrl+F5

4. **Try again!**

---

## 🆘 Still Not Working?

### Option A: Switch to Groq (Recommended for Demo)
- Fast, reliable, perfect for presentations
- See instructions above

### Option B: Debug Further
1. Open browser console (F12)
2. Go to Network tab
3. Ask a question
4. Click on the failed request
5. Check the "Response" tab
6. Share the error message

---

**Test API First:** http://localhost/Ideathon/api/test_api.php

If that works, the chatbot should work too!
