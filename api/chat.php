<?php
/**
 * Chat API Endpoint - Clean Working Version
 * Uses qwen2.5-coder:7b model from Ollama
 */

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle OPTIONS for CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only POST allowed
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit();
}

// Increase execution time
set_time_limit(600);
ini_set('max_execution_time', 600);

try {
    // Get input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['question']) || empty(trim($input['question']))) {
        throw new Exception('Question is required');
    }
    
    $question = trim($input['question']);
    
    // Load dependencies
    require_once __DIR__ . '/config.php';
    require_once __DIR__ . '/Database.php';
    require_once __DIR__ . '/OllamaAI.php';
    
    // Initialize
    $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $ai = new OllamaAI();
    
    // --- INTENT CLASSIFICATION ---
    // Classify the question before doing anything (no AI call needed — instant)
    $intent = OllamaAI::classifyIntent($question);

    // 1. GREETING
    if ($intent === 'greeting') {
        echo json_encode([
            'success'   => true,
            'intent'    => 'greeting',
            'response'  => "👋 Hello! I'm your **Smart EAPCET Query BOT**.\n\nI can help you find:\n- 🏫 **Colleges** by rank, fee, branch, or category\n- 📊 **Cutoff ranks** for OC, SC, ST, BC-A/B/C/D/E, EWS\n- 💰 **Fee details** for any college or branch\n- 📍 **Location** of colleges in Andhra Pradesh\n\nTry asking: *\"Show me CSE colleges with OC rank below 10000\"*",
            'sql'       => '',
            'data'      => [],
            'row_count' => 0,
            'model'     => 'System Static',
            'model_id'  => 'static'
        ]);
        exit();
    }

    // 2. OUT OF SCOPE
    if ($intent === 'out_of_scope') {
        echo json_encode([
            'success'   => true,
            'intent'    => 'out_of_scope',
            'response'  => "🎯 I'm specialized in **AP EAPCET 2024** college admissions data.\n\nI can't help with that topic, but I'd love to assist you with:\n- College rankings and cutoffs\n- Branch-wise fees\n- Category-wise seat availability\n\nWhat would you like to know about EAPCET admissions?",
            'sql'       => '',
            'data'      => [],
            'row_count' => 0,
            'model'     => 'System Static',
            'model_id'  => 'static'
        ]);
        exit();
    }

    // 3. CONVERSATIONAL (general question, no DB needed)
    if ($intent === 'conversational') {
        $convResponse = $ai->answerConversational($question);
        if ($convResponse['success']) {
            echo json_encode([
                'success'   => true,
                'intent'    => 'conversational',
                'response'  => $convResponse['message'],
                'sql'       => '',
                'data'      => [],
                'row_count' => 0,
                'model'     => (defined('OLLAMA_MODEL_CHAT') ? OLLAMA_MODEL_CHAT : 'deepseek-r1:1.5b'),
                'model_id'  => (defined('OLLAMA_MODEL_CHAT') ? OLLAMA_MODEL_CHAT : 'deepseek-r1:1.5b')
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'intent'  => 'conversational',
                'error'   => $convResponse['error'] ?? 'AI unavailable. Please try again.'
            ]);
        }
        exit();
    }

    // 4. DATA QUERY — proceed with SQL generation

    $schema = $db->getSchemaDescription();
    
    // Generate SQL
    $sqlResult = $ai->generateSQL($question, $schema);
    
    if (!$sqlResult['success']) {
        // Return 200 but with success=false for specific AI errors
        echo json_encode([
            'success' => false,
            'error' => $sqlResult['error'] ?? 'The AI model is currently busy or warming up. Please try again in a few seconds.'
        ]);
        exit();
    }
    
    $sql = $sqlResult['sql'];
    
    // Validate it's a SELECT query
    if (!preg_match('/^\s*SELECT\s+/i', $sql)) {
        echo json_encode([
            'success' => true,
            'response' => $sql, // If not SQL, treat as AI's direct answer
            'sql' => '-- Direct AI Response',
            'data' => [],
            'row_count' => 0
        ]);
        exit();
    }
    
    // Execute query
    $queryResult = $db->query($sql);
    
    if (!$queryResult['success']) {
        throw new Exception('Query failed: ' . ($queryResult['error'] ?? 'Unknown error'));
    }
    
    $data = $queryResult['data'] ?? [];
    $rowCount = count($data);
    
    // Format response based on results
    if ($rowCount === 0) {
        $response = "I couldn't find any colleges matching your criteria. Try adjusting your search (e.g., higher rank or different fee range).";
    } else {
        // Detect if it's a simple aggregate (COUNT, AVG, etc.)
        $isAggregate = false;
        if ($rowCount === 1 && count($data[0]) === 1) {
            $key = strtolower(key($data[0]));
            if (strpos($key, 'count') !== false || strpos($key, 'avg') !== false || 
                strpos($key, 'sum') !== false || strpos($key, 'min') !== false || 
                strpos($key, 'max') !== false || is_numeric(reset($data[0]))) {
                $isAggregate = true;
            }
        }

        if ($isAggregate) {
            $value = reset($data[0]);
            $label = key($data[0]);
            $cleanLabel = str_replace(['COL ', '_', '(', ')', '*'], ['', ' ', '', '', ''], $label);
            $response = "**" . trim($cleanLabel) . ":** " . (is_numeric($value) ? number_format($value) : $value);
        } else {
            // Smart List Formatting
            $limit = 20;
            $displayData = array_slice($data, 0, $limit);
            
            // Check if all results are for the same college
            $names = array_column($displayData, 'COL 3');
            $uniqueNames = array_values(array_filter(array_unique($names))); // Reset indices for safe access
            $isSameCollege = (count($uniqueNames) === 1 && $rowCount > 1);
            
            if ($isSameCollege) {
                $response = "Found $rowCount items for **" . $uniqueNames[0] . "**:\n\n";
            } else if ($rowCount === 1) {
                $response = "Here is the information you requested:\n\n";
            } else {
                $response = "Found $rowCount matching records. Here are the " . ($rowCount > $limit ? "top $limit" : "results") . ":\n\n";
            }
            
            foreach ($displayData as $idx => $row) {
                if ($isSameCollege || $rowCount <= 5) {
                    // Detailed view for small result sets: Show all relevant columns
                    $details = [];
                    $nameLabel = "";
                    
                    foreach ($row as $k => $v) {
                        if (empty($v) || $v === '-' || $k === 'COL 1') continue;
                        
                        // Comprehensive column mapping for EAPCET database
                        $label = $k;
                        switch($k) {
                            case 'COL 2': $label = "Code"; break;
                            case 'COL 3': $nameLabel = $v; continue 2; // Outer loop continue
                            case 'COL 4': $label = "Type"; break;
                            case 'COL 7': $label = "Location"; break;
                            case 'COL 12': $label = "Branch"; break;
                            case 'COL 13': $label = "OC Boys Rank"; break;
                            case 'COL 14': $label = "OC Girls Rank"; break;
                            case 'COL 15': $label = "SC Boys Rank"; break;
                            case 'COL 16': $label = "SC Girls Rank"; break;
                            case 'COL 17': $label = "ST Boys Rank"; break;
                            case 'COL 18': $label = "ST Girls Rank"; break;
                            case 'COL 19': $label = "BC-A Boys Rank"; break;
                            case 'COL 21': $label = "BC-B Boys Rank"; break;
                            case 'COL 23': $label = "BC-C Boys Rank"; break;
                            case 'COL 25': $label = "BC-D Boys Rank"; break;
                            case 'COL 27': $label = "BC-E Boys Rank"; break;
                            case 'COL 29': $label = "EWS Boys Rank"; break;
                            case 'COL 31': $label = "Total Fee"; break;
                            default: $label = str_replace(['COL ', '_'], ['', ' '], $k);
                        }
                        $details[] = "**$label:** $v";
                    }
                    
                    $prefix = ($rowCount === 1) ? "" : ($idx + 1) . ". ";
                    if ($nameLabel && !$isSameCollege) {
                        $response .= $prefix . "**$nameLabel**\n> " . implode('  |  ', $details) . "\n\n";
                    } else {
                        $response .= $prefix . implode('  |  ', $details) . "\n\n";
                    }
                } else {
                    // Default view for large lists: Show college names
                    $name = $row['COL 3'] ?? $row['COL 12'] ?? $row['COL 2'] ?? 'Record';
                    $response .= ($idx + 1) . ". " . (is_string($name) ? $name : 'Record') . "\n";
                }
            }
            
            if ($rowCount > $limit) {
                $response .= "\n*Click the button below to view all $rowCount results in detail.*";
            }
        }
    }
    
    // Return success
    echo json_encode([
        'success' => true,
        'response' => $response,
        'sql' => $sql,
        'data' => $data,
        'row_count' => $rowCount,
        'has_more' => $rowCount > 50,
        'model' => (defined('OLLAMA_MODEL_SQL') ? OLLAMA_MODEL_SQL : 'Qwen 2.5 Coder 3B'),
        'model_id' => (defined('OLLAMA_MODEL_SQL') ? OLLAMA_MODEL_SQL : 'qwen2.5-coder:3b')
    ]);
    
} catch (Exception $e) {
    // Return 200 even for errors so the frontend can show the message nicely
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
