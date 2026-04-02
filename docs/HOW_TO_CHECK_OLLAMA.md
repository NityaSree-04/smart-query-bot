# 🔍 How to Check if Ollama is Running

## Method 1: Quick Check (Recommended)

**Open PowerShell and run**:
```powershell
curl http://localhost:11434/api/tags
```

### **Results**:

✅ **If Ollama is RUNNING**:
```json
{
  "models": [
    {"name": "qwen2.5-coder:7b", ...},
    {"name": "phi3:mini", ...}
  ]
}
```

❌ **If Ollama is NOT running**:
```
curl: (7) Failed to connect to localhost port 11434
```

---

## Method 2: Check Process

**Run in PowerShell**:
```powershell
Get-Process | Where-Object {$_.ProcessName -like "*ollama*"}
```

### **Results**:

✅ **If running**: Shows ollama process
```
ProcessName  Id    CPU
-----------  --    ---
ollama       1234  12.5
```

❌ **If not running**: No output

---

## Method 3: Task Manager

1. Press `Ctrl + Shift + Esc`
2. Go to "Details" tab
3. Look for `ollama.exe`

✅ **Found** = Running  
❌ **Not found** = Not running

---

## How to Start Ollama

### **If Ollama is NOT running**:

**Option 1: Start from Command Line**
```powershell
ollama serve
```
(Keep this window open)

**Option 2: Start as Background Service**
- Ollama usually starts automatically
- Check Windows Services
- Or just run `ollama serve` in a new PowerShell window

---

## How to Stop Ollama

### **Method 1: Task Manager**
1. Press `Ctrl + Shift + Esc`
2. Find `ollama.exe`
3. Right-click → End Task

### **Method 2: Command Line**
```powershell
taskkill /F /IM ollama.exe
```

---

## How to Restart Ollama

```powershell
# Stop
taskkill /F /IM ollama.exe

# Wait 2 seconds
Start-Sleep -Seconds 2

# Start
ollama serve
```

---

## Troubleshooting

### **Problem**: "Port 11434 already in use"
**Solution**: Ollama is already running! No need to start it again.

### **Problem**: "ollama: command not found"
**Solution**: 
1. Ollama might not be installed
2. Or not in PATH
3. Try full path: `C:\Users\<YourName>\AppData\Local\Programs\Ollama\ollama.exe serve`

### **Problem**: Ollama starts but chatbot doesn't work
**Solution**:
1. Check if qwen2.5-coder:7b is installed:
   ```powershell
   ollama list
   ```
2. If not installed:
   ```powershell
   ollama pull qwen2.5-coder:7b
   ```

---

## Quick Diagnostic Script

Save this as `check_ollama.ps1`:
```powershell
Write-Host "=== Ollama Status Check ===" -ForegroundColor Cyan

# Check process
$process = Get-Process | Where-Object {$_.ProcessName -like "*ollama*"}
if ($process) {
    Write-Host "✅ Ollama process is running (PID: $($process.Id))" -ForegroundColor Green
} else {
    Write-Host "❌ Ollama process is NOT running" -ForegroundColor Red
}

# Check API
try {
    $response = curl -UseBasicParsing http://localhost:11434/api/tags 2>&1
    if ($response.StatusCode -eq 200) {
        Write-Host "✅ Ollama API is responding" -ForegroundColor Green
        $models = ($response.Content | ConvertFrom-Json).models
        Write-Host "📦 Installed models: $($models.Count)" -ForegroundColor Cyan
        foreach ($model in $models) {
            Write-Host "   - $($model.name)" -ForegroundColor White
        }
    }
} catch {
    Write-Host "❌ Ollama API is NOT responding" -ForegroundColor Red
}
```

**Run it**:
```powershell
.\check_ollama.ps1
```

---

## Summary

| Command | Purpose |
|---------|---------|
| `curl http://localhost:11434/api/tags` | Check if API responds |
| `Get-Process \| Where {$_.ProcessName -like "*ollama*"}` | Check if process running |
| `ollama serve` | Start Ollama |
| `ollama list` | List installed models |
| `taskkill /F /IM ollama.exe` | Stop Ollama |

---

**Quick test now**: Run `curl http://localhost:11434/api/tags` to see if Ollama is running!
