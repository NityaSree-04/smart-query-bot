# OpenRouter API Setup Guide

## Why OpenRouter for Your Ideathon Project?

OpenRouter provides a unified API to access multiple AI models through a single API key, making it perfect for hackathons and demos:

### ✅ Key Benefits:
1. **Free Models Available** - No cost for demo/testing
2. **Multiple Providers** - Access to OpenAI, Anthropic, Google, Meta, and more
3. **Zero Upfront Cost** - Only pay for what you use
4. **Quota Monitoring** - Built-in usage tracking
5. **OpenAI-Compatible** - Minimal code changes needed
6. **Easy Model Switching** - Change models without architecture changes

### 🆓 Recommended Free Models:
- `meta-llama/llama-3.2-3b-instruct:free` - Fast, good for SQL generation
- `google/gemini-flash-1.5:free` - Excellent for general tasks
- `google/gemini-2.0-flash-exp:free` - Latest Gemini model
- `qwen/qwen-2-7b-instruct:free` - Good alternative

## 🚀 Quick Start

### Step 1: Get Your OpenRouter API Key

1. Visit [OpenRouter](https://openrouter.ai/)
2. Click **"Sign In"** (top right)
3. Sign in with Google, GitHub, or email
4. Go to **"Keys"** section in your dashboard
5. Click **"Create Key"**
6. Give it a name (e.g., "Ideathon Project")
7. Copy your API key (starts with `sk-or-v1-...`)

> **Important**: Keep your API key secure! Never commit it to version control.

### Step 2: Configure Your Project

#### For Go Backend:

1. Open your `.env` file (or create one from `.env.example`)

2. Replace the OpenAI API key with your OpenRouter key:
   ```env
   OPENAI_API_KEY=sk-or-v1-your-openrouter-key-here
   OPENROUTER_BASE_URL=https://openrouter.ai/api/v1
   OPENROUTER_MODEL=meta-llama/llama-3.2-3b-instruct:free
   ```

3. The Go code will be updated to use OpenRouter's endpoint

#### For PHP Backend:

1. Open `api/config.php`

2. Update the configuration:
   ```php
   define('OPENAI_API_KEY', 'sk-or-v1-your-openrouter-key-here');
   define('OPENAI_BASE_URL', 'https://openrouter.ai/api/v1');
   define('OPENAI_MODEL', 'meta-llama/llama-3.2-3b-instruct:free');
   ```

### Step 3: Test Your Setup

Run your application and try a simple query like:
- "How many users are in the database?"
- "Show me all products"

## 📊 Available Free Models

| Model | Provider | Best For | Speed |
|-------|----------|----------|-------|
| `meta-llama/llama-3.2-3b-instruct:free` | Meta | SQL generation, fast responses | ⚡⚡⚡ |
| `google/gemini-flash-1.5:free` | Google | General tasks, good quality | ⚡⚡ |
| `google/gemini-2.0-flash-exp:free` | Google | Latest features, experimental | ⚡⚡ |
| `qwen/qwen-2-7b-instruct:free` | Alibaba | Alternative option | ⚡⚡ |

## 🔧 Advanced Configuration

### Model Selection Strategy

For your database chat application, I recommend:

1. **SQL Generation**: Use `meta-llama/llama-3.2-3b-instruct:free`
   - Fast and efficient for structured queries
   - Good understanding of SQL syntax

2. **Response Formatting**: Use `google/gemini-flash-1.5:free`
   - Better at natural language responses
   - More conversational

### Custom Headers (Optional)

OpenRouter supports custom headers for better tracking:

```javascript
headers: {
  "HTTP-Referer": "https://your-app-url.com",
  "X-Title": "Ideathon Database Chat"
}
```

## 💰 Cost Monitoring

1. Visit your [OpenRouter Dashboard](https://openrouter.ai/activity)
2. View real-time usage statistics
3. Set spending limits (optional)
4. Monitor per-model costs

### Free Tier Limits:
- Free models have rate limits but no cost
- Typically: 20-200 requests per minute
- Perfect for demos and development

## 🔄 Switching Models

To switch models, simply update the model name in your config:

```env
# Fast and free
OPENROUTER_MODEL=meta-llama/llama-3.2-3b-instruct:free

# Better quality (still free)
OPENROUTER_MODEL=google/gemini-flash-1.5:free

# Latest experimental (free)
OPENROUTER_MODEL=google/gemini-2.0-flash-exp:free
```

No code changes needed!

## 🛠️ Troubleshooting

### "Invalid API key"
- Verify your key starts with `sk-or-v1-`
- Check for extra spaces or quotes
- Regenerate key if needed

### "Model not found"
- Check model name spelling
- Verify model is available: [OpenRouter Models](https://openrouter.ai/models)
- Try a different free model

### "Rate limit exceeded"
- Free models have rate limits
- Wait a few seconds between requests
- Consider upgrading to paid tier for production

### "No response from API"
- Check internet connection
- Verify base URL is correct: `https://openrouter.ai/api/v1`
- Check OpenRouter status page

## 📚 Resources

- [OpenRouter Documentation](https://openrouter.ai/docs)
- [Available Models](https://openrouter.ai/models)
- [API Reference](https://openrouter.ai/docs/api-reference)
- [Pricing](https://openrouter.ai/docs/pricing)

## 🎯 Best Practices for Ideathon

1. **Start with free models** - Perfect for demos
2. **Monitor usage** - Keep track in dashboard
3. **Test different models** - Find the best fit
4. **Set up fallbacks** - Have backup model options
5. **Document your choice** - Explain why you chose OpenRouter

## 🔐 Security Tips

1. ✅ Use environment variables for API keys
2. ✅ Never commit `.env` file to git
3. ✅ Add `.env` to `.gitignore`
4. ✅ Rotate keys after the event
5. ✅ Set spending limits in dashboard

---

**Ready to go!** Your Ideathon project now has access to multiple AI models with zero upfront cost. 🚀
