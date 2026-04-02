# 🚀 Groq API Integration - Quick Start

## ⚡ What Changed?

Your app now uses **Groq API** instead of OpenRouter/Gemini for **10x faster** SQL generation!

## 📝 Next Steps (3 minutes)

### 1️⃣ Add Your API Key

Open: `api/config.php` (line 12)

Replace:
```php
define('GROQ_API_KEY', 'your-groq-api-key-here');
```

With your actual key from: https://console.groq.com

---

### 2️⃣ Test It

```bash
php test_groq.php
```

Should see: `✓ All Tests Passed!`

---

### 3️⃣ Run Your App

```bash
php -S localhost:8080
```

Open: http://localhost:8080/index.html

---

## 📚 Files Created

- ✅ `api/GroqAI.php` - Main integration class
- ✅ `test_groq.php` - Test script
- ✅ `GROQ_SETUP_GUIDE.md` - Complete guide

## 📝 Files Updated

- ✅ `api/config.php` - Groq settings
- ✅ `api/chat.php` - Uses GroqAI class
- ✅ `.env` - Environment variables

---

## 🎯 Try These Questions

- "How many users are there?"
- "Show me the 5 most recent orders"
- "What's the average order value?"

---

## 🆘 Troubleshooting

**Error: API Key not set**
→ Edit `api/config.php` line 12

**Error: HTTP 401**
→ Invalid API key, get new one from console.groq.com

**Error: HTTP 429**
→ Rate limit, wait 1 minute

---

## 📖 Full Documentation

See: `GROQ_SETUP_GUIDE.md` for complete setup guide

---

**Model Used:** `llama-3.3-70b-versatile` (best for SQL)

**Free Tier:** 30 requests/min, 14,400/day - Perfect for demos! 🎉
