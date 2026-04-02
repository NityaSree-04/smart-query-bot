# Quick Start Script for OpenRouter Integration (PowerShell)
# Save this file with UTF-8 BOM encoding for emoji support

Write-Host "OpenRouter Quick Start for Ideathon" -ForegroundColor Cyan
Write-Host "====================================" -ForegroundColor Cyan
Write-Host ""

# Check if .env exists
if (-not (Test-Path .env)) {
    Write-Host "[*] Creating .env file from .env.example..." -ForegroundColor Yellow
    Copy-Item .env.example .env
    Write-Host "[OK] .env file created!" -ForegroundColor Green
    Write-Host ""
    Write-Host "[!] IMPORTANT: Please edit .env and add your OpenRouter API key" -ForegroundColor Yellow
    Write-Host "    Get your key from: https://openrouter.ai/keys" -ForegroundColor White
    Write-Host ""
    Write-Host "    Replace this line:" -ForegroundColor White
    Write-Host "    OPENAI_API_KEY=sk-or-v1-your-openrouter-key-here" -ForegroundColor Gray
    Write-Host ""
    Write-Host "    With your actual key:" -ForegroundColor White
    Write-Host "    OPENAI_API_KEY=sk-or-v1-xxxxxxxxxxxxxxxx" -ForegroundColor Gray
    Write-Host ""
} else {
    Write-Host "[OK] .env file already exists" -ForegroundColor Green
}

Write-Host ""
Write-Host "Current Configuration:" -ForegroundColor Cyan
Write-Host "======================" -ForegroundColor Cyan

if (Test-Path .env) {
    $baseUrl = (Get-Content .env | Select-String "OPENROUTER_BASE_URL" | ForEach-Object { $_.ToString().Split('=')[1] })
    $model = (Get-Content .env | Select-String "OPENROUTER_MODEL" | ForEach-Object { $_.ToString().Split('=')[1] })
    
    Write-Host "Base URL: $baseUrl" -ForegroundColor White
    Write-Host "Model: $model" -ForegroundColor White
    
    # Check if API key is set
    $apiKey = (Get-Content .env | Select-String "^OPENAI_API_KEY=" | ForEach-Object { $_.ToString().Split('=')[1] })
    if ($apiKey -like "sk-or-v1-*" -and $apiKey -notlike "*your-openrouter-key-here*") {
        Write-Host "API Key: [OK] Configured" -ForegroundColor Green
    } else {
        Write-Host "API Key: [X] Not configured (please add your OpenRouter key)" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "Recommended Free Models:" -ForegroundColor Cyan
Write-Host "========================" -ForegroundColor Cyan
Write-Host "1. meta-llama/llama-3.2-3b-instruct:free (Fast, good for SQL)" -ForegroundColor White
Write-Host "2. google/gemini-flash-1.5:free (Excellent quality)" -ForegroundColor White
Write-Host "3. google/gemini-2.0-flash-exp:free (Latest Gemini)" -ForegroundColor White
Write-Host "4. qwen/qwen-2-7b-instruct:free (Alternative option)" -ForegroundColor White
Write-Host ""

Write-Host "Next Steps:" -ForegroundColor Cyan
Write-Host "===========" -ForegroundColor Cyan
Write-Host "1. Get your OpenRouter API key: https://openrouter.ai/keys" -ForegroundColor White
Write-Host "2. Edit .env and add your API key" -ForegroundColor White
Write-Host "3. Run: go run main.go" -ForegroundColor White
Write-Host "4. Open: http://localhost:8080" -ForegroundColor White
Write-Host ""

Write-Host "Tips:" -ForegroundColor Cyan
Write-Host "=====" -ForegroundColor Cyan
Write-Host "- Free models are perfect for demos and development" -ForegroundColor White
Write-Host "- Monitor usage at: https://openrouter.ai/activity" -ForegroundColor White
Write-Host "- Switch models by changing OPENROUTER_MODEL in .env" -ForegroundColor White
Write-Host ""

Write-Host "Resources:" -ForegroundColor Cyan
Write-Host "==========" -ForegroundColor Cyan
Write-Host "- Setup Guide: OPENROUTER_SETUP_GUIDE.md" -ForegroundColor White
Write-Host "- Available Models: https://openrouter.ai/models" -ForegroundColor White
Write-Host "- Documentation: https://openrouter.ai/docs" -ForegroundColor White
Write-Host ""

Write-Host "Ready to start!" -ForegroundColor Green
Write-Host ""
Write-Host "To open .env file for editing, run:" -ForegroundColor Yellow
Write-Host "   notepad .env" -ForegroundColor Gray
Write-Host ""
