# How to Run Your Application on Port 80 (XAMPP)

## Quick Setup Guide

Your project is using the **PHP backend** with XAMPP, which runs on **port 80** by default.

### ✅ Step 1: Update OpenRouter API Key

1. Open this file in a text editor:
   ```
   c:\Users\nitya\OneDrive\Desktop\Ideathon\api\config.php
   ```

2. Find this line:
   ```php
   define('OPENAI_API_KEY', 'sk-or-v1-your-openrouter-key-here');
   ```

3. Replace `sk-or-v1-your-openrouter-key-here` with your actual OpenRouter API key

4. Save the file

### ✅ Step 2: Copy Project to XAMPP Directory

Your project needs to be in XAMPP's `htdocs` folder. You have two options:

#### Option A: Copy the Folder (Recommended)
```powershell
# Copy entire project to XAMPP
xcopy "C:\Users\nitya\OneDrive\Desktop\Ideathon" "C:\xampp\htdocs\Ideathon" /E /I /Y
```

#### Option B: Create Symbolic Link (Advanced)
```powershell
# Run PowerShell as Administrator, then:
New-Item -ItemType SymbolicLink -Path "C:\xampp\htdocs\Ideathon" -Target "C:\Users\nitya\OneDrive\Desktop\Ideathon"
```

### ✅ Step 3: Start XAMPP Services

1. Open **XAMPP Control Panel**
2. Click **Start** next to **Apache**
3. Click **Start** next to **MySQL**

Both should show green "Running" status.

### ✅ Step 4: Import Database

1. Open phpMyAdmin: **http://localhost/phpmyadmin**
2. Click **"Import"** tab
3. Choose file: `C:\xampp\htdocs\Ideathon\database.sql`
4. Click **"Go"**

### ✅ Step 5: Open Your Application

Open your browser and go to:

```
http://localhost/Ideathon/
```

Or if you want to use the full path:

```
http://localhost/Ideathon/index.html
```

## 🎯 Your Application URLs

| Page | URL |
|------|-----|
| **Main App** | http://localhost/Ideathon/ |
| **API Chat** | http://localhost/Ideathon/api/chat.php |
| **API Schema** | http://localhost/Ideathon/api/schema.php |
| **phpMyAdmin** | http://localhost/phpmyadmin |

## 🔧 Troubleshooting

### Issue: "Port 80 is already in use"
**Solution**: Another program (like Skype or IIS) is using port 80.

1. Open XAMPP Control Panel
2. Click **Config** next to Apache
3. Select **httpd.conf**
4. Find `Listen 80` and change to `Listen 8080`
5. Save and restart Apache
6. Access via: http://localhost:8080/Ideathon/

### Issue: "Database connection failed"
**Solution**: 
1. Make sure MySQL is running in XAMPP
2. Check database credentials in `api/config.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');  // Usually empty for XAMPP
   define('DB_NAME', 'ai_chat_db');
   ```

### Issue: "OpenRouter API error"
**Solution**:
1. Verify your API key in `api/config.php`
2. Make sure it starts with `sk-or-v1-`
3. Check you have internet connection

### Issue: "404 Not Found"
**Solution**:
1. Make sure project is in `C:\xampp\htdocs\Ideathon\`
2. Apache must be running (green in XAMPP)
3. Try: http://localhost/Ideathon/index.html

## 📁 Project Structure for XAMPP

```
C:\xampp\htdocs\Ideathon\
├── index.html          ← Main page
├── api/
│   ├── config.php      ← Your OpenRouter API key goes here
│   ├── chat.php        ← Chat endpoint
│   └── ...
├── css/
│   └── style.css
├── js/
│   └── app.js
└── database.sql        ← Import this to MySQL
```

## ✅ Quick Test

After setup, test your application:

1. Open: http://localhost/Ideathon/
2. You should see "AI Database Chat" interface
3. Try asking: "How many users are in the database?"
4. You should get a response from OpenRouter

## 🎉 You're Ready!

Your application is now running on **port 80** with **OpenRouter API**!

**Access your app at**: http://localhost/Ideathon/

---

**Need help?** Check the main documentation files or ask for assistance.
