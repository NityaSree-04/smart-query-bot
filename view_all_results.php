<?php
session_start();

// Check if we have query results
$hasResults = isset($_SESSION['last_query_results']) && !empty($_SESSION['last_query_results']);
$results = $_SESSION['last_query_results'] ?? [];
$sql = $_SESSION['last_sql'] ?? 'N/A';
$question = $_SESSION['last_question'] ?? 'N/A';
$rowCount = count($results);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All EAPCET Results - View Complete Data</title>
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        
        .header {
            margin-bottom: 30px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 20px;
        }
        
        h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .info {
            color: #666;
            font-size: 14px;
            margin-top: 10px;
        }
        
        .query-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }
        
        .query-info strong {
            color: #667eea;
        }
        
        .query-info code {
            background: #e9ecef;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 13px;
        }
        
        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
            transition: background 0.3s;
        }
        
        .back-btn:hover {
            background: #5568d3;
        }
        
        table.dataTable {
            width: 100% !important;
            font-size: 13px;
        }
        
        table.dataTable thead th {
            background: #667eea;
            color: white;
            font-weight: 600;
            padding: 12px 8px;
        }
        
        table.dataTable tbody td {
            padding: 10px 8px;
        }
        
        table.dataTable tbody tr:hover {
            background-color: #f1f3f5;
        }
        
        .dataTables_wrapper .dataTables_length select {
            padding: 5px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        
        .dataTables_wrapper .dataTables_filter input {
            padding: 5px 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
            margin-left: 5px;
        }
        
        .dt-buttons {
            margin-bottom: 15px;
        }
        
        .dt-button {
            background: #667eea !important;
            color: white !important;
            border: none !important;
            padding: 8px 15px !important;
            border-radius: 4px !important;
            margin-right: 5px !important;
            cursor: pointer !important;
        }
        
        .dt-button:hover {
            background: #5568d3 !important;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        .no-data a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 Complete EAPCET Results</h1>
            <p class="info">Interactive table with search, sort, and export capabilities</p>
        </div>
        
        <a href="chatbot.html" class="back-btn">← Back to Chatbot</a>
        
        <?php
        // No authentication required - direct access allowed
        
        // Check if we have query results
        if (!$hasResults) {
            echo '<div class="no-data">';
            echo '<h2>No Results Available</h2>';
            echo '<p>Please run a query in the <a href="chatbot.html">chatbot</a> first.</p>';
            echo '</div>';
            echo '</div></body></html>';
            exit();
        }
        
        // Display query info if we have results
        if ($hasResults) {
            echo '<div class="query-info">';
            echo '<strong>Your Question:</strong> ' . htmlspecialchars($question) . '<br>';
            echo '<strong>Results Found:</strong> ' . $rowCount . ' rows<br>';
            echo '<strong>SQL Query:</strong> <code>' . htmlspecialchars($sql) . '</code>';
            echo '</div>';
        }
        ?>
        <?php
        if (!$hasResults) {
            echo '<div class="no-data">';
            echo '<h2>No Results Available</h2>';
            echo '<p>Please run a query in the <a href="chatbot.html">chatbot</a> first.</p>';
            echo '</div>';
        } elseif ($rowCount > 0) {
            // Column name mappings for EAPCET table
            $columnMappings = [
                'COL 1' => 'S.No',
                'COL 2' => 'Inst Code',
                'COL 3' => 'College Name',
                'COL 4' => 'Type',
                'COL 5' => 'Inst Region',
                'COL 6' => 'District',
                'COL 7' => 'Location',
                'COL 8' => 'Co-Ed',
                'COL 9' => 'Affiliation',
                'COL 10' => 'Established',
                'COL 11' => 'Autonomous',
                'COL 12' => 'Branch',
                'COL 13' => 'OC Boys',
                'COL 14' => 'OC Girls',
                'COL 15' => 'SC Boys',
                'COL 16' => 'SC Girls',
                'COL 17' => 'ST Boys',
                'COL 18' => 'ST Girls',
                'COL 19' => 'BC-A Boys',
                'COL 20' => 'BC-A Girls',
                'COL 21' => 'BC-B Boys',
                'COL 22' => 'BC-B Girls',
                'COL 23' => 'BC-C Boys',
                'COL 24' => 'BC-C Girls',
                'COL 25' => 'BC-D Boys',
                'COL 26' => 'BC-D Girls',
                'COL 27' => 'BC-E Boys',
                'COL 28' => 'BC-E Girls',
                'COL 29' => 'EWS Boys',
                'COL 30' => 'EWS Girls',
                'COL 31' => 'Fee (₹)',
                'COL 32' => 'Extra'
            ];
            
            // Get column names from first row
            $columns = array_keys($results[0]);
            
            echo '<table id="resultsTable" class="display" style="width:100%">';
            echo '<thead><tr>';
            foreach ($columns as $col) {
                // Use mapped name if available, otherwise use original
                $displayName = $columnMappings[$col] ?? $col;
                echo '<th>' . htmlspecialchars($displayName) . '</th>';
            }
            echo '</tr></thead>';
            echo '<tbody>';
            
            foreach ($results as $row) {
                echo '<tr>';
                foreach ($columns as $col) {
                    $value = $row[$col] ?? '';
                    // Format empty or dash values
                    if ($value === '' || $value === '-' || $value === 'NA') {
                        $value = '-';
                    }
                    echo '<td>' . htmlspecialchars($value) . '</td>';
                }
                echo '</tr>';
            }
            
            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<div class="no-data">';
            echo '<h2>No Results Found</h2>';
            echo '<p>The query returned no results.</p>';
            echo '</div>';
        }
        ?>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#resultsTable').DataTables({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                order: [[0, 'asc']],
                responsive: true,
                language: {
                    search: "Search in results:",
                    lengthMenu: "Show _MENU_ entries per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ results",
                    infoEmpty: "No results available",
                    infoFiltered: "(filtered from _MAX_ total results)"
                }
            });
        });
    </script>
</body>
</html>
