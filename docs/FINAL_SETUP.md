# 🎯 FINAL SETUP INSTRUCTIONS

## ⚠️ IMPORTANT: Do These Steps IN ORDER

### ✅ Step 1: Add Your OpenRouter API Key

Open Notepad and edit this file:
```
C:\xampp\htdocs\Ideathon\api\config.php
```

Find line 12 and replace the API key:
```php
define('OPENAI_API_KEY', 'sk-or-v1-PUT-YOUR-ACTUAL-KEY-HERE');
```

**Save the file!**

---

### ✅ Step 2: Start XAMPP

1. Open **XAMPP Control Panel**
2. Click **Start** next to **Apache** → Should turn green
3. Click **Start** next to **MySQL** → Should turn green

---

### ✅ Step 3: Create and Import Database

#### 3a. Create Database
1. Open: http://localhost/phpmyadmin
2. Click **"New"** in the left sidebar
3. Database name: `ai_chat_db`
4. Collation: `utf8mb4_general_ci`
5. Click **"Create"**

#### 3b. Import Data
1. Click on `ai_chat_db` in the left sidebar
2. Click **"Import"** tab at the top
3. Click **"Choose File"**
4. Select: `C:\xampp\htdocs\Ideathon\database.sql`
5. Click **"Go"** at the bottom
6. Wait for "Import has been successfully finished"

---

### ✅ Step 4: Test API Connection

Open this URL in your browser:
```
http://localhost/Ideathon/api/test_openrouter.php
```

**Expected Result:**
```json
{
    "success": true,
    "message": "OpenRouter API is working correctly!",
    "model": "meta-llama/llama-3.2-3b-instruct:free",
    "response": "Hello! ..."
}
```

**If you see an error:**
- Check API key in config.php
- Make sure it starts with `sk-or-v1-`
- Verify you have internet connection

---

### ✅ Step 5: Open Your Application

Go to:
```
http://localhost/Ideathon/
```

You should see the **AI Database Chat** interface!

---

### ✅ Step 6: Test with Questions

Try these:
1. Type: `How many users are in the database?`
2. Type: `Show me all products`
3. Type: `What is the total revenue?`

---

## 🆘 Common Errors and Fixes

### Error: "Failed to communicate with server: Unexpected token '<'"
**Cause**: PHP error or Apache not running
**Fix**:
1. Make sure Apache is running (green in XAMPP)
2. Check: http://localhost/Ideathon/api/test_openrouter.php
3. If still error, check Apache error log in XAMPP

### Error: "Database connection failed"
**Fix**:
1. MySQL must be running (green in XAMPP)
2. Database must be named exactly: `ai_chat_db`
3. Re-import database.sql if needed

### Error: "OpenRouter API error (HTTP 401)"
**Fix**: API key is wrong
1. Get new key from: https://openrouter.ai/keys
2. Update in: `C:\xampp\htdocs\Ideathon\api\config.php`

### Error: "OpenRouter API error (HTTP 429)"
**Fix**: Rate limit (rare with free models)
- Wait 10 seconds and try again

---

## ✅ Verification Checklist

Before your demo, verify:

- [ ] XAMPP Apache: **Running** (green)
- [ ] XAMPP MySQL: **Running** (green)
- [ ] Database exists: `ai_chat_db` in phpMyAdmin
- [ ] API test passes: http://localhost/Ideathon/api/test_openrouter.php
- [ ] App loads: http://localhost/Ideathon/
- [ ] Can ask questions and get responses

---

## 📍 Important URLs

| Purpose | URL |
|---------|-----|
| **Your App** | http://localhost/Ideathon/ |
| **API Test** | http://localhost/Ideathon/api/test_openrouter.php |
| **phpMyAdmin** | http://localhost/phpmyadmin |
| **Get API Key** | https://openrouter.ai/keys |

---

## 📁 Important Files

| File | Purpose |
|------|---------|
| `C:\xampp\htdocs\Ideathon\api\config.php` | **Add your API key here** |
| `C:\xampp\htdocs\Ideathon\database.sql` | Database to import |
| `C:\xampp\htdocs\Ideathon\index.html` | Main application |

---

## 🎉 You're Ready!

Once all checkboxes are ✅, your demo is ready to go!

**Demo URL**: http://localhost/Ideathon/

---

**Need help?** Check the error messages and use the fixes above.
