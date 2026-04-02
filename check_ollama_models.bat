@echo off
echo ========================================
echo Checking Ollama Model Availability
echo ========================================
echo.

echo [0/4] Testing DeepSeek-R1 (Active Model)...
echo.
echo Testing: deepseek-r1:7b
ollama run deepseek-r1:7b "Reply with just OK" 2>&1 | findstr /C:"error" /C:"not found" /C:"pulling" /C:"OK" || echo Model available
echo.
echo [1/4] Testing qwen2.5 variants...
echo.
echo Testing: qwen2.5
ollama run qwen2.5 "test" --verbose 2>&1 | findstr /C:"error" /C:"not found" /C:"pulling" /C:"success" || echo Model exists
echo.
echo Testing: qwen2.5:7b-instruct
ollama run qwen2.5:7b-instruct "test" --verbose 2>&1 | findstr /C:"error" /C:"not found" /C:"pulling" /C:"success" || echo Model exists
echo.
echo Testing: qwen2.5-coder:7b-instruct
ollama run qwen2.5-coder:7b-instruct "test" --verbose 2>&1 | findstr /C:"error" /C:"not found" /C:"pulling" /C:"success" || echo Model exists
echo.

echo [2/4] Testing gemma3 variants...
echo.
echo Testing: gemma3
ollama run gemma3 "test" --verbose 2>&1 | findstr /C:"error" /C:"not found" /C:"pulling" /C:"success" || echo Model exists
echo.
echo Testing: gemma3-tools
ollama run gemma3-tools "test" --verbose 2>&1 | findstr /C:"error" /C:"not found" /C:"pulling" /C:"success" || echo Model exists
echo.
echo Testing: PetrosStav/gemma3-tools:12b
ollama run PetrosStav/gemma3-tools:12b "test" --verbose 2>&1 | findstr /C:"error" /C:"not found" /C:"pulling" /C:"success" || echo Model exists
echo.

echo [3/4] Testing gemma with 'i' variant...
echo.
echo Testing: gemma:i
ollama run gemma:i "test" --verbose 2>&1 | findstr /C:"error" /C:"not found" /C:"pulling" /C:"success" || echo Model exists
echo.

echo [4/4] Testing llama-3.3-70b-versatile (Groq cloud)...
echo.
echo Testing: llama-3.3-70b-versatile
ollama run llama-3.3-70b-versatile "test" --verbose 2>&1 | findstr /C:"error" /C:"not found" /C:"pulling" /C:"success" || echo Model exists
echo.

echo ========================================
echo Listing all installed Ollama models:
echo ========================================
ollama list
echo.

echo ========================================
echo Test Complete!
echo ========================================
pause
