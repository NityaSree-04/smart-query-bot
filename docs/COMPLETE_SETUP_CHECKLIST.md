# ✅ COMPLETE SETUP CHECKLIST

## Step 1: Add Your OpenRouter API Key ✏️

1. Open this file: `C:\xampp\htdocs\Ideathon\api\config.php`
2. Find line 12:
   ```php
   define('OPENAI_API_KEY', 'sk-or-v1-your-openrouter-key-here');
   ```
3. Replace `sk-or-v1-your-openrouter-key-here` with your actual API key
4. Save the file

## Step 2: Start XAMPP Services 🚀

1. Open **XAMPP Control Panel**
2. Click **Start** next to **Apache**
3. Click **Start** next to **MySQL**
4. Both should show green "Running" status

## Step 3: Import Database 💾

1. Open: http://localhost/phpmyadmin
2. Click **"New"** in left sidebar
3. Database name: `ai_chat_db`
4. Click **"Create"**
5. Click **"Import"** tab
6. Choose file: `C:\xampp\htdocs\Ideathon\database.sql`
7. Click **"Go"**

## Step 4: Test OpenRouter Connection 🧪

Open this URL to test if your API key works:
```
http://localhost/Ideathon/api/test_openrouter.php
```

You should see:
```json
{
    "success": true,
    "message": "OpenRouter API is working correctly!",
    ...
}
```

If you see an error, check:
- ✅ API key is correct in config.php
- ✅ API key starts with `sk-or-v1-`
- ✅ You have internet connection

## Step 5: Open Your Application 🎉

Go to:
```
http://localhost/Ideathon/
```

## Step 6: Test with Questions 💬

Try these example questions:
1. "How many users are in the database?"
2. "Show me all products"
3. "What is the total revenue from orders?"

---

## 🆘 Troubleshooting

### Error: "Failed to communicate with server"
**Fix**: Make sure Apache is running in XAMPP

### Error: "Database connection failed"
**Fix**: 
1. Make sure MySQL is running
2. Import database.sql file
3. Check database name is `ai_chat_db`

### Error: "OpenRouter API error"
**Fix**:
1. Verify API key in `C:\xampp\htdocs\Ideathon\api\config.php`
2. Test at: http://localhost/Ideathon/api/test_openrouter.php

### Error: "Unexpected token '<'"
**Fix**: This means PHP has an error. Check:
1. All files are copied to `C:\xampp\htdocs\Ideathon\`
2. Apache is running
3. Check Apache error log in XAMPP

---

## ✅ Quick Verification

1. ✅ XAMPP Apache: Running (green)
2. ✅ XAMPP MySQL: Running (green)
3. ✅ Database imported: ai_chat_db exists
4. ✅ API key added: in config.php
5. ✅ Test passes: http://localhost/Ideathon/api/test_openrouter.php
6. ✅ App loads: http://localhost/Ideathon/

---

**All green? You're ready to demo!** 🎊
