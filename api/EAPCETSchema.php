<?php
/**
 * EAPCET Schema - Optimized Version
 * Balanced training for Qwen without being too large
 */

class EAPCETSchema {
    
    public static function getSchemaDescription() {
        $schema = <<<'SCHEMA'
TABLE: apeamcet2024 (~1,570 records)

COLUMNS (use backticks!):
`COL 1` - SNO | EXCLUDE headers: WHERE `COL 1` NOT IN ('APEAPCET-2024[ M P C STREAM] LAST RANK DETAILS', 'SNO')
`COL 2` - INSTCODE (e.g., 'MICT', 'ACEE', 'LBRCE') | Search: `COL 2` = 'MICT' OR `COL 3` LIKE '%MICT%'
`COL 3` - College Name | Use LIKE: `COL 3` LIKE '%keyword%'
`COL 4` - TYPE ('GOVT', 'PVT', 'AIDED')
`COL 7` - PLACE (City)
`COL 12` - branch_code ('CSE', 'ECE', 'EEE', 'MEC', 'CIV', 'INF', 'AID', 'AIM')
  IMPORTANT: Branch name mappings:
  - Mechanical/MECH → 'MEC' (not 'MECH')
  - Civil → 'CIV' (not 'CIVIL')

CUTOFF RANKS (VARCHAR - use CAST!):
`COL 13`-OC_BOYS, `COL 14`-OC_GIRLS, `COL 15`-SC_BOYS, `COL 16`-SC_GIRLS
`COL 19`-BCA_BOYS, `COL 20`-BCA_GIRLS, `COL 25`-BCD_BOYS, `COL 26`-BCD_GIRLS
Logic: Student rank >= Cutoff = Can get admission

`COL 31` - COLLFEE (VARCHAR - use CAST!)

EXAMPLES:

1. Fee: "CSE under 50000"
SELECT `COL 3`, `COL 7`, `COL 31` FROM apeamcet2024 WHERE `COL 12`='CSE' AND CAST(`COL 31` AS UNSIGNED)<50000 AND `COL 1` NOT IN ('APEAPCET-2024[ M P C STREAM] LAST RANK DETAILS','SNO') ORDER BY CAST(`COL 31` AS UNSIGNED)

2. Rank: "Rank 45000 CSE"
SELECT `COL 3`, `COL 7`, `COL 13` as OC_BOYS, `COL 14` as OC_GIRLS, `COL 31` FROM apeamcet2024 WHERE `COL 12`='CSE' AND (CAST(`COL 13` AS UNSIGNED)>=45000 OR CAST(`COL 14` AS UNSIGNED)>=45000) AND `COL 1` NOT IN ('APEAPCET-2024[ M P C STREAM] LAST RANK DETAILS','SNO') ORDER BY CAST(`COL 31` AS UNSIGNED)

3. Category: "BC-D girl rank 75000 CSE"
SELECT `COL 3`, `COL 7`, `COL 26` as BCD_GIRLS, `COL 31` FROM apeamcet2024 WHERE `COL 12`='CSE' AND CAST(`COL 26` AS UNSIGNED)>=75000 AND `COL 1` NOT IN ('APEAPCET-2024[ M P C STREAM] LAST RANK DETAILS','SNO') ORDER BY CAST(`COL 26` AS UNSIGNED)

4. Government: "Govt CSE"
SELECT `COL 3`, `COL 7`, `COL 31` FROM apeamcet2024 WHERE `COL 4`='GOVT' AND `COL 12`='CSE' AND `COL 1` NOT IN ('APEAPCET-2024[ M P C STREAM] LAST RANK DETAILS','SNO') ORDER BY CAST(`COL 31` AS UNSIGNED)

5. Institution: "List MICT branches"
SELECT `COL 3`, `COL 12`, `COL 31` FROM apeamcet2024 WHERE (`COL 2`='MICT' OR `COL 3` LIKE '%MICT%') AND `COL 1` NOT IN ('APEAPCET-2024[ M P C STREAM] LAST RANK DETAILS','SNO') ORDER BY `COL 12`

5b. Institution: "How many branches in LBCE college"
SELECT COUNT(*) as total FROM apeamcet2024 WHERE (`COL 2`='LBCE' OR `COL 3` LIKE '%LBCE%' OR `COL 3` LIKE '%LAKIREDDY%') AND `COL 1` NOT IN ('APEAPCET-2024[ M P C STREAM] LAST RANK DETAILS','SNO')

6. Count: "How many CSE"
SELECT COUNT(*) as total FROM apeamcet2024 WHERE `COL 12`='CSE' AND `COL 1` NOT IN ('APEAPCET-2024[ M P C STREAM] LAST RANK DETAILS','SNO')

7. Average: "What is the average fee for CSE colleges"
SELECT AVG(CAST(`COL 31` AS UNSIGNED)) as average_fee FROM apeamcet2024 WHERE `COL 12`='CSE' AND `COL 1` NOT IN ('APEAPCET-2024[ M P C STREAM] LAST RANK DETAILS','SNO')

RULES:
1. ALWAYS use backticks: `COL 3`
2. ALWAYS use CAST for numbers: CAST(`COL 31` AS UNSIGNED)
3. ALWAYS exclude headers
4. Return ONLY SQL, no explanations
SCHEMA;
        
        return $schema;
    }
}
?>
