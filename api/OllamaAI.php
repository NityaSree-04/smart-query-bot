<?php
/**
 * Ollama AI Class
 * Handles communication with local Ollama API for SQL generation and response formatting
 * Uses OpenAI-compatible API endpoint
 */

class OllamaAI {
    private $model;
    private $apiUrl;
    
    public function __construct($model = null, $baseUrl = null) {
        $this->model = $model ?? (defined('OLLAMA_MODEL_SQL') ? OLLAMA_MODEL_SQL : 'qwen2.5-coder:3b');
        $baseUrl = $baseUrl ?? (defined('OLLAMA_BASE_URL') ? OLLAMA_BASE_URL : 'http://localhost:11434');
        $this->apiUrl = rtrim($baseUrl, '/') . '/v1/chat/completions';
    }

    /**
     * Set the model for the current operation
     */
    public function setModel($model) {
        $this->model = $model;
        return $this;
    }

    /**
     * INTENT CLASSIFIER
     * Instantly classifies a question into one of 4 categories using keyword heuristics.
     * Returns: 'greeting' | 'data_query' | 'conversational' | 'out_of_scope'
     */
    public static function classifyIntent(string $question): string {
        $q = strtolower(trim($question));

        // --- 1. GREETING ---
        $greetingPatterns = [
            '/^(hi|hello|hey|hii|helo|howdy|yo)\b/',
            '/^good\s*(morning|afternoon|evening|night)\b/',
            '/^(who are you|what are you|help me|help|start|begin)\b/',
        ];
        foreach ($greetingPatterns as $pattern) {
            if (preg_match($pattern, $q) && strlen($q) < 30) return 'greeting';
        }

        // --- 2. OUT OF SCOPE (non-EAPCET topics clearly unrelated) ---
        $outOfScopePatterns = [
            '/\b(weather|cricket|football|movie|song|recipe|cook|news|stock|crypto|bitcoin)\b/',
            '/\b(who (is|was) (the )?(prime minister|president|ceo|founder))\b/',
            '/\b(capital of|population of|distance from)\b/',
            '/\b(write (a |an )?(poem|story|essay|code|program))\b/',
            '/\b(translate|meaning of|definition of)\b/',
        ];
        foreach ($outOfScopePatterns as $pattern) {
            if (preg_match($pattern, $q)) return 'out_of_scope';
        }

        // --- 3. DATA QUERY (EAPCET domain keywords) ---
        $dataKeywords = [
            // College / institution terms
            'college', 'colleges', 'institution', 'university', 'instcode',
            // Branch / course terms
            'branch', 'branches', 'cse', 'ece', 'eee', 'mec', 'civ', 'inf', 'aid', 'aim',
            'computer science', 'electronics', 'mechanical', 'civil', 'it ', 'information technology',
            'course', 'courses', 'stream',
            // Admission / rank terms
            'rank', 'cutoff', 'cut off', 'seat', 'admission', 'eapcet', 'eamcet', 'apeapcet',
            'category', 'oc ', 'sc ', 'st ', 'bc-a', 'bc-b', 'bc-c', 'bc-d', 'bc-e', 'ews',
            'boys', 'girls', 'male', 'female',
            // Fee terms
            'fee', 'fees', 'tuition', 'cost',
            // Location terms
            'located', 'location', 'place', 'city', 'district', 'where is',
            // Query verbs specific to data
            'list', 'show', 'find', 'how many', 'which', 'what is the', 'give me', 'tell me',
            // College types
            'govt', 'government', 'private', 'pvt', 'aided',
            // Specific query patterns
            'top ', 'best ', 'low fee', 'affordable', 'average fee', 'total fee',
            // Common abbreviation patterns for college codes
            'lbce', 'lbrce', 'mict', 'srkr', 'acee', 'klef', 'vit',
        ];
        foreach ($dataKeywords as $kw) {
            if (str_contains($q, $kw)) return 'data_query';
        }

        // --- 4. CONVERSATIONAL (general knowledge / math / how-to) ---
        $conversationalPatterns = [
            '/\b(what is|what are|what was|what were)\b/',
            '/\b(how (does|do|is|are|can|to))\b/',
            '/\b(why (is|are|do|does|did))\b/',
            '/\b(explain|describe|define|difference between)\b/',
            '/\b(calculate|compute|\d+\s*[\+\-\*\/]\s*\d+)\b/',
            '/\b(can you|could you|please)\b/',
        ];
        foreach ($conversationalPatterns as $pattern) {
            if (preg_match($pattern, $q)) return 'conversational';
        }

        // Default: treat as a data query (safer fallback for EAPCET context)
        return 'data_query';
    }

    /**
     * Answer a general conversational question (not EAPCET data-related)
     */
    public function answerConversational(string $question): array {
        // Use CHAT model if defined
        $currentModel = $this->model;
        if (defined('OLLAMA_MODEL_CHAT')) {
            $this->model = OLLAMA_MODEL_CHAT;
        }

        $messages = [
            [
                'role' => 'system',
                'content' => "You are a helpful assistant for AP EAPCET college admissions. "
                           . "Answer the user's general question clearly and concisely. "
                           . "If the question is unrelated to engineering admissions in Andhra Pradesh, "
                           . "politely redirect them to ask about colleges, ranks, fees, or branches."
            ],
            [
                'role' => 'user',
                'content' => $question
            ]
        ];
        $result = $this->makeRequest($messages, 0.7, 400);

        // Restore original model
        $this->model = $currentModel;
        return $result;
    }


    public function generateSQL($question, $schemaDescription) {
        $systemPrompt = $this->buildSQLSystemPrompt($schemaDescription);
        
        $messages = [
            [
                'role' => 'system',
                'content' => $systemPrompt
            ],
            [
                'role' => 'user',
                'content' => $question
            ]
        ];
        
        $response = $this->makeRequest($messages, 0.0, 1500); // temp=0 for deterministic SQL; 1500 tokens covers full reasoning + SQL
        
        if ($response['success']) {
            // Clean up the SQL query (remove markdown formatting if present)
            $sql = $response['message'];
            
            // --- DeepSeek-R1 Reasoning Block Removal ---
            // DeepSeek-R1 wraps its chain-of-thought inside <think>...</think> blocks.
            // Strip ALL reasoning content before extracting the final SQL.
            $sql = preg_replace('/<think>[\s\S]*?<\/think>/i', '', $sql); // Closed think blocks
            $sql = preg_replace('/<think>[\s\S]*$/i', '', $sql);           // Unclosed think blocks
            $sql = preg_replace('/<\/think>/i', '', $sql);                 // Stray closing tags
            
            // Remove markdown code blocks
            $sql = preg_replace('/```sql\s*/i', '', $sql);
            $sql = preg_replace('/```\s*$/s', '', $sql);
            $sql = preg_replace('/```/s', '', $sql);
            
            // Remove any leading/trailing text before/after the SQL
            // Look for SELECT and extract from there to semicolon
            if (preg_match('/\b(SELECT\s+.+?;)/is', $sql, $matches)) {
                $sql = $matches[1];
            } elseif (preg_match('/\b(SELECT\s+.+?)(?:\s*$)/is', $sql, $matches)) {
                $sql = $matches[1];
            }
            
            // Remove SQL comments
            $sql = preg_replace('/--[^\n]*/', '', $sql);
            $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
            
            // Clean up whitespace
            $sql = trim($sql);
            
            // Add semicolon if missing
            if (!preg_match('/;\s*$/', $sql)) {
                $sql .= ';';
            }
            
            // POST-PROCESSING: Auto-add null filters for COUNT DISTINCT queries
            // This fixes the issue where AI doesn't always add null checks
            if (preg_match('/COUNT\s*\(\s*DISTINCT\s+`?(\w+\s+\d+)`?\s*\)/i', $sql, $matches)) {
                $column = $matches[1];
                // Check if null filter already exists
                if (stripos($sql, 'IS NOT NULL') === false) {
                    // Add null filter before ORDER BY, GROUP BY, or final semicolon
                    $sql = preg_replace(
                        '/(WHERE\s+.+?)(\s+(ORDER|GROUP|LIMIT|;))/is',
                        '$1 AND `' . $column . '` IS NOT NULL AND `' . $column . '` != \'\' AND `' . $column . '` != \'-\'$2',
                        $sql
                    );
                }
            }
            
            return [
                'success' => true,
                'sql' => $sql
            ];
        }
        
        return $response;
    }
    
    /**
     * Format query results into natural language response (optimized)
     */
    public function formatResponse($question, $queryResult) {
        $systemPrompt = "Format database results into clear, concise answers. "
                      . "List all results completely. Be brief and direct.";
        
        $resultJson = json_encode($queryResult, JSON_PRETTY_PRINT);
        $userPrompt = "Question: \"$question\"\nResults:\n$resultJson\n\nProvide a clear answer listing all results.";
        
        $messages = [
            [
                'role' => 'system',
                'content' => $systemPrompt
            ],
            [
                'role' => 'user',
                'content' => $userPrompt
            ]
        ];
        
        $response = $this->makeRequest($messages, 0.3, 800); // Reduced tokens for speed
        
        return $response;
    }
    
    /**
     * Build system prompt for SQL generation (optimized for speed)
     */
    private function buildSQLSystemPrompt($schemaDescription) {
        return "You are a SQL expert for AP EAPCET (EAMCET) 2024 college admission data.\n\n"
             . "DATABASE RULES (ABSOLUTE):\n"
             . "1. Table: apeamcet2024\n"
             . "2. Use backticks for columns: `COL 3`, `COL 12`, etc.\n"
             . "3. CAST numeric columns for comparison: CAST(`COL 13` AS UNSIGNED) >= 55900\n"
             . "4. EXCLUDE headers: WHERE `COL 1` NOT IN ('APEAPCET-2024[ M P C STREAM] LAST RANK DETAILS  ', 'SNO')\n"
             . "5. NO JOINs (single table only).\n"
             . "6. Branch Mapping: IT seat → `COL 12`='INF' | Mechanical → 'MEC' | Civil → 'CIV'\n"
             . "7. DYNAMIC SEARCH: For college names/codes (LBCE, MIC, etc.), ALWAYS use broad matches: (`COL 2` LIKE '%query%' OR `COL 3` LIKE '%query%').\n"
             . "8. ALWAYS include `COL 3` (College Name) in SELECT for list context.\n\n"
             . "PEOPLE'S LANGUAGE MAPPING:\n"
             . "- 'Branches' or 'Courses' means SELECTing `COL 12` (Branch Code).\n"
             . "- 'LBCE' often refers to 'LBRCE' or 'LAKIREDDY'. Use LIKE '%LBCE%' OR '%LBRCE%' OR '%LAKIREDDY%'.\n\n"
             . "COLUMN MAPPINGS:\n"
             . "- `COL 2` = INSTCODE | `COL 3` = College Name | `COL 4` = Type | `COL 7` = Place\n"
             . "- `COL 12` = Branch Code (CSE, ECE, EEE, INF, MEC, CIV, AID, AIM)\n"
             . "- CATEGORY COLUMNS: SC (`COL 15`), ST (`COL 17`), BC-A (`COL 19`), BC-D (`COL 25`), OC (`COL 13`).\n\n"
             . "REASONING PROCESS:\n"
             . "1. EXTRACT REQUIREMENTS: Analyze the question for any specific data points the user wants (e.g., 'where' -> `COL 7`, 'how much' -> `COL 31`, 'which branch' -> `COL 12`).\n"
             . "2. SELECT TARGETS: Beyond `COL 3`, include EVERY column that directly answers a requirement from Step 1.\n"
             . "3. DYNAMIC SEARCH: Map entities (college names, codes, regions) to broad `LIKE` patterns across `COL 2` and `COL 3`.\n\n"
             . "EXAMPLES:\n"
             . "Q: where is ASR college located and what is the fee?\n"
             . "A: SELECT `COL 3`, `COL 7`, `COL 31` FROM apeamcet2024 WHERE (`COL 2` LIKE '%ASR%' OR `COL 3` LIKE '%ASR%') AND `COL 1` NOT IN ('APEAPCET-2024[ M P C STREAM] LAST RANK DETAILS  ', 'SNO');\n\n"
             . "Q: tell me the rank for oc boys for ECE branch in MICT college.\n"
             . "A: SELECT `COL 3`, `COL 12`, `COL 13` FROM apeamcet2024 WHERE `COL 12`='ECE' AND (`COL 2` LIKE '%MICT%' OR `COL 3` LIKE '%MIC%') AND `COL 1` NOT IN ('APEAPCET-2024[ M P C STREAM] LAST RANK DETAILS  ', 'SNO');\n\n"
             . "Q: what type of college is SRKR?\n"
             . "A: SELECT `COL 3`, `COL 4` FROM apeamcet2024 WHERE (`COL 2` LIKE '%SRKR%' OR `COL 3` LIKE '%SRKR%') AND `COL 1` NOT IN ('APEAPCET-2024[ M P C STREAM] LAST RANK DETAILS  ', 'SNO') LIMIT 1;\n\n"
             . "Q: list all the branches in LBCE college.\n"
             . "Q: list all the branches in LBCE college.\n"
             . "A: SELECT `COL 3`, `COL 12`, `COL 7` FROM apeamcet2024 WHERE (`COL 2` LIKE '%LBCE%' OR `COL 3` LIKE '%LBCE%' OR `COL 3` LIKE '%LBRCE%' OR `COL 3` LIKE '%LAKIREDDY%') AND `COL 1` NOT IN ('APEAPCET-2024[ M P C STREAM] LAST RANK DETAILS  ', 'SNO');\n\n"
             . "Q: iam a boy and my category is SC caste so give me the colleges that i can get IT seat with 55900 rank.\n"
             . "A: SELECT `COL 3`, `COL 7`, `COL 12`, `COL 15` FROM apeamcet2024 WHERE `COL 12`='INF' AND CAST(`COL 15` AS UNSIGNED) >= 55900 AND `COL 1` NOT IN ('APEAPCET-2024[ M P C STREAM] LAST RANK DETAILS  ', 'SNO') ORDER BY CAST(`COL 15` AS UNSIGNED);";
    }
    
    /**
     * Make request to Ollama API using cURL
     */
    private function makeRequest($messages, $temperature = 0.7, $maxTokens = 500) {
        $data = [
            'model' => $this->model,
            'messages' => $messages,
            'temperature' => $temperature,
            'max_tokens' => $maxTokens,
            'stream' => false
        ];
        
        $ch = curl_init($this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // 10 seconds to connect
        curl_setopt($ch, CURLOPT_TIMEOUT, 300); // 5 minutes total timeout (first load can be slow)
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            // Check if it's a timeout
            if (strpos($error, 'timeout') !== false || strpos($error, 'timed out') !== false) {
                return [
                    'success' => false,
                    'error' => 'Ollama is taking too long to respond. This usually happens on first use when the model is loading. Please try again - subsequent requests will be faster.'
                ];
            }
            
            return [
                'success' => false,
                'error' => 'Connection error: ' . $error . '. Make sure Ollama is running.'
            ];
        }
        
        if ($httpCode !== 200) {
            $errorDetails = json_decode($response, true);
            $errorMessage = isset($errorDetails['error']['message']) 
                ? $errorDetails['error']['message'] 
                : $response;
            
            return [
                'success' => false,
                'error' => 'Ollama API error (HTTP ' . $httpCode . '): ' . $errorMessage
            ];
        }
        
        $result = json_decode($response, true);
        
        if (!isset($result['choices'][0]['message']['content'])) {
            return [
                'success' => false,
                'error' => 'Invalid response from Ollama API'
            ];
        }
        
        return [
            'success' => true,
            'message' => $result['choices'][0]['message']['content']
        ];
    }
}
?>
