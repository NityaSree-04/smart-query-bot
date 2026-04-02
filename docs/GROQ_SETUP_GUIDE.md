# Groq API Setup Guide

Complete guide to integrate Groq API into your Natural Language to SQL Chat Application.

## 🚀 Quick Start

### Step 1: Get Your Groq API Key

1. Visit [Groq Console](https://console.groq.com)
2. Sign up for a free account (no credit card required)
3. Navigate to **API Keys** in the left sidebar
4. Click **Create API Key**
5. Give it a name (e.g., "DatabaseChatDemo")
6. Copy the key immediately (starts with `gsk_...`)
7. **Important**: Store it securely - you won't see it again!

### Step 2: Configure Your API Key

Open `api/config.php` and replace the placeholder with your actual API key:

```php
define('GROQ_API_KEY', 'gsk_your_actual_key_here');
```

### Step 3: Test the Integration

Run the test script to verify everything works:

```bash
php test_groq.php
```

You should see:
- ✓ API Key is set
- ✓ API Connection successful!
- ✓ SQL generation successful!
- ✓ Response formatting successful!
- === All Tests Passed! ===

### Step 4: Start Using the Chat Interface

1. Start your local PHP server:
   ```bash
   php -S localhost:8080
   ```

2. Open your browser and navigate to:
   ```
   http://localhost:8080/index.html
   ```

3. Try asking questions like:
   - "How many users are there?"
   - "Show me the 5 most recent orders"
   - "What are the names of all products?"

## 🎯 Available Models

The default model is `llama-3.3-70b-versatile` (best for SQL generation).

You can change the model in `api/config.php`:

```php
// Best for SQL generation (default)
define('GROQ_MODEL', 'llama-3.3-70b-versatile');

// Alternative options:
// define('GROQ_MODEL', 'llama3-70b-8192');        // Excellent reasoning
// define('GROQ_MODEL', 'llama3-8b-8192');         // Faster, still good
// define('GROQ_MODEL', 'mixtral-8x7b-32768');     // Good alternative
```

## 📊 Rate Limits (Free Tier)

Groq's free tier is very generous:
- **Requests per minute**: 30
- **Requests per day**: 14,400
- **Tokens per minute**: 6,000

Perfect for development and demo purposes!

Check your limits at: https://console.groq.com/settings/limits

## 🔧 Troubleshooting

### Error: "API Key is not set"
- Make sure you've replaced `'your-groq-api-key-here'` in `api/config.php`
- Verify the key starts with `gsk_`

### Error: "HTTP 401 Unauthorized"
- Your API key is invalid or expired
- Generate a new key at https://console.groq.com

### Error: "HTTP 429 Rate Limit"
- You've exceeded the free tier limits
- Wait a minute and try again
- Consider upgrading if you need higher limits

### Error: "cURL error"
- Check your internet connection
- Verify PHP cURL extension is enabled: `php -m | grep curl`

### SQL Queries Not Working
- Check your database connection in `api/config.php`
- Verify your database schema is correct
- Make sure MySQL is running

## 🎨 How It Works

1. **User asks a question** → Frontend sends to `api/chat.php`
2. **GroqAI generates SQL** → Converts natural language to SQL query
3. **Database executes query** → Runs the SQL and gets results
4. **GroqAI formats response** → Converts results to natural language
5. **User sees answer** → Displayed in the chat interface

## 📁 File Structure

```
Ideathon/
├── api/
│   ├── config.php          # Configuration (API key, database)
│   ├── GroqAI.php          # Groq API integration
│   ├── Database.php        # Database operations
│   ├── chat.php            # Main chat endpoint
│   └── schema.php          # Database schema endpoint
├── index.html              # Chat interface
├── test_groq.php           # Test script
└── GROQ_SETUP_GUIDE.md     # This file
```

## 🌟 Why Groq?

- **Lightning Fast**: Responses in < 1 second
- **Free Tier**: Generous limits for development
- **No Credit Card**: Sign up without payment info
- **OpenAI Compatible**: Easy to integrate
- **Great for SQL**: Excellent reasoning capabilities

## 🎓 For Your Technical Event

### Demo Tips:
1. **Show the speed**: Groq is incredibly fast - highlight this!
2. **Live queries**: Ask complex questions in real-time
3. **Explain the flow**: Show how natural language → SQL → results
4. **Error handling**: Demonstrate how it handles invalid queries
5. **Multiple tables**: Show JOIN queries working

### Sample Questions for Demo:
- "How many users registered last month?"
- "Show me the top 10 products by sales"
- "Which customers have placed more than 5 orders?"
- "What's the average order value?"
- "List all products that are out of stock"

## 📞 Support

- **Groq Documentation**: https://console.groq.com/docs
- **Groq Discord**: https://discord.gg/groq
- **API Status**: https://status.groq.com

## 🎉 You're All Set!

Your Natural Language to SQL Chat is now powered by Groq's lightning-fast inference. Enjoy building your demo! 🚀
