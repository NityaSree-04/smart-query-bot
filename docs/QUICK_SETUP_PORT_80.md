# Quick Setup - 3 Steps to Run on Port 80

## ✅ Step 1: Add Your OpenRouter API Key

**Notepad is now open** with the file: `C:\xampp\htdocs\Ideathon\api\config.php`

Find this line (around line 12):
```php
define('OPENAI_API_KEY', 'sk-or-v1-your-openrouter-key-here');
```

Replace `sk-or-v1-your-openrouter-key-here` with your actual OpenRouter API key.

**Save the file** (Ctrl+S) and close Notepad.

---

## ✅ Step 2: Start XAMPP

1. Open **XAMPP Control Panel**
2. Click **Start** next to **Apache** (for web server)
3. Click **Start** next to **MySQL** (for database)

Both should turn green and say "Running".

---

## ✅ Step 3: Import Database

1. Open browser and go to: **http://localhost/phpmyadmin**
2. Click **"Import"** tab at the top
3. Click **"Choose File"** button
4. Navigate to: `C:\xampp\htdocs\Ideathon\database.sql`
5. Click **"Go"** at the bottom

You should see: "Import has been successfully finished"

---

## 🎉 Open Your Application

Go to this URL in your browser:

```
http://localhost/Ideathon/
```

You should see the **AI Database Chat** interface!

---

## 🧪 Test It

Try asking:
- "How many users are in the database?"
- "Show me all products"
- "What is the total revenue?"

---

## 🆘 Quick Troubleshooting

**Problem: "Port 80 already in use"**
- Close Skype or other programs using port 80
- Or change Apache to port 8080 in XAMPP config

**Problem: "Database connection failed"**
- Make sure MySQL is running (green in XAMPP)
- Import the database.sql file

**Problem: "OpenRouter API error"**
- Check your API key in config.php
- Make sure it starts with `sk-or-v1-`

---

## 📍 Your Application Path

**Local Files**: `C:\xampp\htdocs\Ideathon\`
**Web URL**: `http://localhost/Ideathon/`
**Config File**: `C:\xampp\htdocs\Ideathon\api\config.php`

---

**That's it! You're ready to demo!** 🚀
