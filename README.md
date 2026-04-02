# AI-Powered Database Chat Application

A PHP-based web application that allows you to interact with MySQL databases using natural language queries powered by OpenAI's GPT models.

![AI Database Chat](https://img.shields.io/badge/PHP-7.4+-blue) ![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange) ![OpenAI](https://img.shields.io/badge/OpenAI-GPT--4-green)

## 🌟 Features

- 🤖 **Natural Language Queries**: Ask questions about your data in plain English
- 💾 **MySQL Database**: Full MySQL database support with schema introspection
- 🎨 **Modern Web Interface**: Beautiful, responsive chat UI with real-time updates
- ⚡ **AJAX Communication**: Fast, asynchronous communication with PHP backend
- 🔒 **Safe Query Execution**: Only SELECT queries are allowed for data safety
- 📊 **Schema Awareness**: AI automatically understands your database structure

## 📋 Prerequisites

1. **XAMPP** (or similar LAMP/WAMP stack) - [Download XAMPP](https://www.apachefriends.org/)
   - PHP 7.4 or higher
   - MySQL 5.7 or higher
   - Apache web server

2. **AI API Key** (Choose one):
   - **🆓 OpenRouter** (Recommended for Ideathon) - [Get free API key](https://openrouter.ai/keys)
     - Access to multiple AI models through one key
     - **Completely free models available** (perfect for demos!)
     - Zero upfront cost, quota monitoring included
     - See `OPENROUTER_SETUP_GUIDE.md` for details
   - **💰 OpenAI** - [Get API key](https://platform.openai.com/api-keys)
     - Requires payment/credits
     - GPT-4 and GPT-3.5-turbo models

3. **PHP Extensions** (usually included with XAMPP):
   - mysqli
   - curl
   - json

## 🚀 Installation

### Step 1: Install XAMPP

1. Download and install XAMPP from [apachefriends.org](https://www.apachefriends.org/)
2. Start **Apache** and **MySQL** from XAMPP Control Panel

### Step 2: Set Up the Project

1. Copy the project folder to XAMPP's `htdocs` directory:
   ```
   C:\xampp\htdocs\Ideathon\
   ```

2. Or create a symbolic link (optional):
   ```powershell
   New-Item -ItemType SymbolicLink -Path "C:\xampp\htdocs\Ideathon" -Target "C:\Users\nitya\OneDrive\Desktop\Ideathon"
   ```

### Step 3: Create the Database

1. Open **phpMyAdmin**: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)

2. Click on "Import" tab

3. Choose the `database.sql` file from the project folder

4. Click "Go" to import the database

**OR** use MySQL command line:
```bash
mysql -u root -p < database.sql
```

### Step 4: Configure the Application

#### Option A: Using OpenRouter (Recommended - Free Models!)

**For Go Backend:**

1. Copy `.env.example` to `.env`:
   ```bash
   cp .env.example .env
   ```

2. Edit `.env` and add your OpenRouter API key:
   ```env
   OPENAI_API_KEY=sk-or-v1-your-openrouter-key-here
   OPENROUTER_BASE_URL=https://openrouter.ai/api/v1
   OPENROUTER_MODEL=meta-llama/llama-3.2-3b-instruct:free
   ```

3. Get your free API key from [OpenRouter](https://openrouter.ai/keys)

**Quick Start (Windows):**
```powershell
.\openrouter-quickstart.ps1
```

**For PHP Backend:**

1. Open `api/config.php` in a text editor

2. Update the configuration:
   ```php
   define('OPENAI_API_KEY', 'sk-or-v1-your-openrouter-key-here');
   define('OPENAI_BASE_URL', 'https://openrouter.ai/api/v1');
   define('OPENAI_MODEL', 'meta-llama/llama-3.2-3b-instruct:free');
   ```

#### Option B: Using OpenAI (Paid)

**For Go Backend:**

1. Edit `.env`:
   ```env
   OPENAI_API_KEY=sk-your-openai-key-here
   OPENROUTER_BASE_URL=https://api.openai.com/v1
   OPENROUTER_MODEL=gpt-4
   ```

**For PHP Backend:**

1. Open `api/config.php`:
   ```php
   define('OPENAI_API_KEY', 'sk-your-actual-api-key-here');
   define('OPENAI_BASE_URL', 'https://api.openai.com/v1');
   define('OPENAI_MODEL', 'gpt-4');
   ```

#### Database Configuration (Both Options)

Update database credentials if different from defaults:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // Your MySQL password
define('DB_NAME', 'ai_chat_db');
```

📚 **See `OPENROUTER_SETUP_GUIDE.md` for detailed OpenRouter setup instructions**

### Step 5: Run the Application

1. Make sure Apache and MySQL are running in XAMPP

2. Open your browser and navigate to:
   ```
   http://localhost/Ideathon/
   ```

3. You should see the chat interface!

## 💬 Usage

### Example Questions

Try asking questions like:

- "How many users are in the database?"
- "Show me the top 5 products by price"
- "What is the total revenue from all orders?"
- "List all users and their email addresses"
- "Which products are low in stock?"
- "Show me all pending orders"
- "What are the most popular products?"

### API Endpoints

The application provides REST API endpoints:

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/chat.php` | POST | Process natural language queries |
| `/api/schema.php` | GET | Get database schema |
| `/api/health.php` | GET | Health check |

### API Example

```bash
curl -X POST http://localhost/Ideathon/api/chat.php \
  -H "Content-Type: application/json" \
  -d '{"question": "How many users are there?"}'
```

Response:
```json
{
  "success": true,
  "answer": "There are 10 users in the database.",
  "sql": "SELECT COUNT(*) as total_users FROM users;",
  "data": [{"total_users": "10"}],
  "count": 1
}
```

## 📁 Project Structure

```
Ideathon/
├── index.html              # Main chat interface
├── database.sql            # MySQL database schema
├── api/
│   ├── config.php         # Configuration file
│   ├── Database.php       # Database class
│   ├── OpenAI.php         # OpenAI integration
│   ├── chat.php           # Chat API endpoint
│   ├── schema.php         # Schema API endpoint
│   └── health.php         # Health check endpoint
├── css/
│   └── style.css          # Styling
├── js/
│   └── app.js             # Frontend logic
└── README.md              # This file
```

## 🔧 Configuration

### Database Settings

Edit `api/config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'your_password');
define('DB_NAME', 'ai_chat_db');
```

### OpenAI Settings

```php
define('OPENAI_API_KEY', 'sk-your-key-here');
define('OPENAI_MODEL', 'gpt-4');  // or 'gpt-3.5-turbo'
```

### Debug Mode

```php
define('APP_DEBUG', true);  // Set to false in production
```

## 🛠️ Troubleshooting

### "Database connection failed"
- Check if MySQL is running in XAMPP
- Verify database credentials in `api/config.php`
- Ensure the database `ai_chat_db` exists

### "OpenAI API error"
- Verify your API key is correct in `api/config.php`
- Check your OpenAI account has credits
- Ensure you have internet connectivity

### "404 Not Found"
- Make sure the project is in `C:\xampp\htdocs\Ideathon\`
- Check Apache is running in XAMPP
- Try accessing: `http://localhost/Ideathon/index.html`

### "cURL error"
- Enable cURL extension in PHP
- Check `php.ini` and uncomment: `extension=curl`
- Restart Apache after changes

### CORS Issues
- CORS headers are already set in `api/config.php`
- If issues persist, check browser console for errors

## 🔒 Security Notes

- ✅ Only SELECT queries are allowed (no DELETE, UPDATE, INSERT)
- ✅ SQL injection protection through query validation
- ⚠️ **Never commit your OpenAI API key to version control**
- ⚠️ Set `APP_DEBUG = false` in production
- ⚠️ Consider implementing user authentication for production use
- ⚠️ Use HTTPS in production environments

## 📊 Database Schema

The sample database includes:

- **users**: User accounts (10 sample users)
- **products**: Product catalog (15 sample products)
- **orders**: Customer orders (10 sample orders)
- **order_items**: Order line items

You can modify the schema or add your own tables. The AI will automatically understand the new structure!

## 🎨 Customization

### Change AI Model

Edit `api/config.php`:
```php
define('OPENAI_MODEL', 'gpt-3.5-turbo');  // Faster, cheaper
// or
define('OPENAI_MODEL', 'gpt-4');  // More accurate
```

### Modify UI Colors

Edit `css/style.css` and change the CSS variables:
```css
:root {
    --primary: hsl(250, 84%, 54%);
    --bg-primary: hsl(240, 21%, 15%);
    /* ... */
}
```

## 📝 License

MIT License - Feel free to use this project for learning and development.

## 🙏 Credits

Inspired by the n8n workflow template: [Chat with a database using AI](https://n8n.io/workflows/2090-chat-with-a-database-using-ai/)

## 📧 Support

If you encounter any issues:
1. Check the troubleshooting section above
2. Verify all prerequisites are installed
3. Check browser console for JavaScript errors
4. Check PHP error logs in XAMPP

---

**Made with ❤️ using PHP, MySQL, and OpenAI GPT-4**
