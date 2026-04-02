# Get New Groq API Key

## ❌ Current Issue
Your Groq API key is invalid or expired.

**Error:** `Groq API error (HTTP 401): Invalid API Key`

---

## ✅ Get New Free API Key

### Step 1: Go to Groq Console
**URL:** https://console.groq.com/keys

### Step 2: Sign In
- Use Google/GitHub account
- Or create new account (free)

### Step 3: Create API Key
1. Click "Create API Key"
2. Give it a name (e.g., "EAPCET Chatbot")
3. Copy the key (starts with `gsk_`)

### Step 4: Update Config
Open: `C:\Users\nitya\OneDrive\Desktop\Ideathon\api\config.php`

Replace this line:
```php
define('GROQ_API_KEY', 'YOUR_API_KEY_HERE');
```

With your new key:
```php
define('GROQ_API_KEY', 'gsk_YOUR_NEW_KEY_HERE');
```

### Step 5: Deploy
```powershell
Copy-Item "C:\Users\nitya\OneDrive\Desktop\Ideathon\api\config.php" "C:\xampp\htdocs\Ideathon\api\config.php" -Force
```

---

## 🔄 Alternative: Switch Back to Ollama

If you don't want to get a new Groq key, I can switch you back to Ollama (slower but works offline).

**Let me know:**
1. Get new Groq key (recommended - fast responses)
2. Switch back to Ollama (slower but free & unlimited)
