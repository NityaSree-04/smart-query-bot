# Quick Start Guide - Multi-Model EAPCET Chatbot

## 🚀 Ready to Use!

Your multi-model chatbot is fully configured. Follow these steps to start using it.

---

## Step 1: Clear Browser Cache (Important!)

Since we updated the JavaScript files, you need to clear your browser cache:

**Method 1: Hard Refresh**
- Press `Ctrl + Shift + R` (Windows/Linux)
- Or `Cmd + Shift + R` (Mac)

**Method 2: Clear Cache**
- Press `Ctrl + Shift + Delete`
- Select "Cached images and files"
- Click "Clear data"

---

## Step 2: Open the Chatbot

Navigate to:
```
http://localhost/Ideathon/chatbot.html
```

---

## Step 3: Select Your AI Model

Choose from the dropdown at the top:

| Model | Speed | Best For |
|-------|-------|----------|
| **Phi-3 Mini** | 3-5s | Accuracy (Default) |
| **Gemma 2B** | 2-3s | Balanced |
| **Qwen Coder** | 1-2s | Fast testing |
| **Groq Llama 70B** | <1s | Demos ⚡ |

---

## Step 4: Ask Questions

Try these examples:

### Example 1: Fee Filter
```
Show CSE colleges under 50000 fee
```
**Expected**: List of CSE colleges with fees < 50,000

### Example 2: Cutoff Chances
```
Rank 60000 BC-D girl CSE chances
```
**Expected**: Colleges where BC-D girls cutoff >= 60,000

### Example 3: Statistics
```
Average fee for government colleges
```
**Expected**: Single number showing average fee

---

## Step 5: View Large Results

When you get >50 results:
1. Click the **"View All Results"** button
2. Opens `view_all.php` with DataTables
3. Search, sort, and export data

---

## 🧪 Test the System

### Quick Test
```
http://localhost/Ideathon/test_multimodel.php
```

This will show:
- ✅ Database connection status
- ✅ Table verification (1,568 records)
- ✅ Model initialization
- ✅ SQL generation test
- ✅ Response times

---

## 📊 Your Database

- **Table**: `apeamcet2024`
- **Records**: 1,568 colleges
- **Columns**: 
  - College info: `NAME_OF_THE_INSTITUTION`, `PLACE`, `TYPE`
  - Branch: `branch_code` (CSE, ECE, EEE, MECH, etc.)
  - Cutoffs: `OC_BOYS`, `OC_GIRLS`, `BCA_BOYS`, `BCA_GIRLS`, etc.
  - Fees: `COLLFEE`

---

## 🎯 Model Selection Tips

**For Accuracy** → Use **Phi-3 Mini**
- Complex queries
- Production use
- Accurate results

**For Speed** → Use **Groq**
- Live demos
- Presentations
- Instant responses

**For Development** → Use **Qwen Coder**
- Quick testing
- Rapid iteration
- Fast feedback

---

## ⚠️ Troubleshooting

### Issue: "An error occurred"
**Solution**: Clear browser cache (Ctrl + Shift + R)

### Issue: Model not responding
**Solution**: Check Ollama is running
```bash
ollama list
```

### Issue: Groq not working
**Solution**: Verify API key in `api/config.php`

### Issue: Database error
**Solution**: Check MySQL is running in XAMPP

---

## ✅ Success Checklist

Before your demo:
- [ ] Clear browser cache
- [ ] Test with Phi-3 Mini
- [ ] Test with Groq (for speed)
- [ ] Try all 3 example queries
- [ ] Test "View All" button
- [ ] Test DataTables export

---

## 🎓 Demo Tips

1. **Start with Groq** - Show instant responses
2. **Switch to Phi-3** - Show accuracy
3. **Use example queries** - They're optimized
4. **Show DataTables** - Impressive export features
5. **Explain model switching** - Unique feature!

---

## 📁 Important Files

| File | Purpose |
|------|---------|
| `chatbot.html` | Main interface |
| `view_all.php` | DataTables viewer |
| `test_multimodel.php` | System test |
| `api/process.php` | Main API endpoint |
| `api/config.php` | Configuration |

---

## 🎉 You're Ready!

Your chatbot has:
- ✅ 4 AI models
- ✅ Smart result handling
- ✅ DataTables with export
- ✅ Model switching
- ✅ 1,568 EAPCET records

**Good luck with your demo!** 🚀

---

**Need help?** Check `MULTI_MODEL_SETUP.md` for detailed documentation.
