#!/bin/bash
# Quick Start Script for OpenRouter Integration

echo "🚀 OpenRouter Quick Start for Ideathon"
echo "========================================"
echo ""

# Check if .env exists
if [ ! -f .env ]; then
    echo "📝 Creating .env file from .env.example..."
    cp .env.example .env
    echo "✅ .env file created!"
    echo ""
    echo "⚠️  IMPORTANT: Please edit .env and add your OpenRouter API key"
    echo "   Get your key from: https://openrouter.ai/keys"
    echo ""
    echo "   Replace this line:"
    echo "   OPENAI_API_KEY=sk-or-v1-your-openrouter-key-here"
    echo ""
    echo "   With your actual key:"
    echo "   OPENAI_API_KEY=sk-or-v1-xxxxxxxxxxxxxxxx"
    echo ""
else
    echo "✅ .env file already exists"
fi

echo ""
echo "📋 Current Configuration:"
echo "========================="
if [ -f .env ]; then
    echo "Base URL: $(grep OPENROUTER_BASE_URL .env | cut -d '=' -f2)"
    echo "Model: $(grep OPENROUTER_MODEL .env | cut -d '=' -f2)"
    
    # Check if API key is set
    API_KEY=$(grep "^OPENAI_API_KEY=" .env | cut -d '=' -f2)
    if [[ $API_KEY == sk-or-v1-* ]] && [[ $API_KEY != *"your-openrouter-key-here"* ]]; then
        echo "API Key: ✅ Configured"
    else
        echo "API Key: ❌ Not configured (please add your OpenRouter key)"
    fi
fi

echo ""
echo "🎯 Recommended Free Models:"
echo "==========================="
echo "1. meta-llama/llama-3.2-3b-instruct:free (Fast, good for SQL)"
echo "2. google/gemini-flash-1.5:free (Excellent quality)"
echo "3. google/gemini-2.0-flash-exp:free (Latest Gemini)"
echo "4. qwen/qwen-2-7b-instruct:free (Alternative option)"
echo ""

echo "📚 Next Steps:"
echo "=============="
echo "1. Get your OpenRouter API key: https://openrouter.ai/keys"
echo "2. Edit .env and add your API key"
echo "3. Run: go run main.go"
echo "4. Open: http://localhost:8080"
echo ""

echo "💡 Tips:"
echo "========"
echo "- Free models are perfect for demos and development"
echo "- Monitor usage at: https://openrouter.ai/activity"
echo "- Switch models by changing OPENROUTER_MODEL in .env"
echo ""

echo "🔗 Resources:"
echo "============="
echo "- Setup Guide: OPENROUTER_SETUP_GUIDE.md"
echo "- Available Models: https://openrouter.ai/models"
echo "- Documentation: https://openrouter.ai/docs"
echo ""
