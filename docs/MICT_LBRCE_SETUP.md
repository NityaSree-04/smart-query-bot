# MICT and LBRCE College Data - Setup Guide

## 📊 New Table Created

**Table Name:** `mict_lbrce_colleges`
**Database:** `ai_chat_db`

**Note:** The existing `apeamcet2024lastrankdetailsnonsw` table is NOT modified.

---

## 🗂️ Table Schema

### Columns:
- **College Info:** code, name, type (MICT/LBRCE), location, district, university
- **Branch Info:** branch_code, branch_name, total_seats
- **Cutoffs:** All categories (OC, SC, ST, BC-A/B/C/D/E, EWS) for boys and girls
- **Fees:** tuition_fee, total_fee, hostel_fee
- **Additional:** placement_percentage, packages, accreditation, NAAC grade, NIRF rank

---

## 🚀 How to Import Data

### Step 1: Copy Files to XAMPP
```powershell
Copy-Item "C:\Users\nitya\OneDrive\Desktop\Ideathon\create_mict_lbrce_table.sql" "C:\xampp\htdocs\Ideathon\" -Force
Copy-Item "C:\Users\nitya\OneDrive\Desktop\Ideathon\import_mict_lbrce.php" "C:\xampp\htdocs\Ideathon\" -Force
```

### Step 2: Run Import Script
Open in browser:
```
http://localhost/Ideathon/import_mict_lbrce.php
```

This will:
1. Create the `mict_lbrce_colleges` table
2. Import sample data for MICT (5 branches)
3. Import sample data for LBRCE (6 branches)

---

## 📝 Sample Data Included

### MICT (Malla Reddy Institute of Technology)
- Location: Hyderabad
- Branches: CSE, ECE, EEE, MECH, CIVIL
- Cutoffs: 25,000 - 45,000

### LBRCE (Lakireddy Bali Reddy College of Engineering)
- Location: Mylavaram, Krishna
- Branches: CSE, ECE, EEE, MECH, IT, CIVIL
- Cutoffs: 22,000 - 42,000

---

## 🎯 Example Queries

After import, you can ask:

1. "Show me MICT college branches"
2. "What are the cutoffs for LBRCE CSE?"
3. "Compare MICT and LBRCE fees"
4. "Show colleges in Hyderabad from the new table"

---

## 📊 To Add More Data

Edit `create_mict_lbrce_table.sql` and add more INSERT statements:

```sql
INSERT INTO `mict_lbrce_colleges` 
(`college_code`, `college_name`, `college_type`, `location`, ...)
VALUES
('CODE', 'College Name', 'MICT', 'Location', ...);
```

Then run the import script again.

---

## ✅ Verification

After import, check:
```sql
SELECT * FROM mict_lbrce_colleges;
```

Should show 11 records (5 MICT + 6 LBRCE).

---

**Your new table is ready!** 🎉
