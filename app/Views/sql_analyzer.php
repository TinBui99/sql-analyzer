<?php
// app/Views/sql_analyzer.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL Risk Analyzer</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
<div class="container">
    <h1>SQL Risk Analyzer</h1>
    <form id="sqlForm">
        <div class="form-group">
            <label for="sqlQuery">Enter your SQL query:</label>
            <textarea id="sqlQuery" name="sql" placeholder="SELECT * FROM users WHERE id = 1" required></textarea>
        </div>
        
        <div class="rules-panel">
            <h3>SQL Analysis Rules</h3>
            <div class="rules-container">
                <div class="rule-item">
                    <label class="switch">
                        <input type="checkbox" name="rules[]" value="SelectStarRule" checked>
                        <span class="slider round"></span>
                    </label>
                    <span class="rule-name">Select * Detection</span>
                    <span class="rule-desc">Warn when using SELECT * in queries</span>
                </div>
                
                <div class="rule-item">
                    <label class="switch">
                        <input type="checkbox" name="rules[]" value="MissingWhereClauseRule" checked>
                        <span class="slider round"></span>
                    </label>
                    <span class="rule-name">Missing WHERE Clause</span>
                    <span class="rule-desc">Warn when UPDATE/DELETE queries lack a WHERE clause</span>
                </div>
                
                <div class="rule-item">
                    <label class="switch">
                        <input type="checkbox" name="rules[]" value="LimitOffsetRule" checked>
                        <span class="slider round"></span>
                    </label>
                    <span class="rule-name">LIMIT with OFFSET</span>
                    <span class="rule-desc">Warn about potential performance issues with OFFSET</span>
                </div>
                
                <div class="rule-item">
                    <label class="switch">
                        <input type="checkbox" name="rules[]" value="NoIndexRule">
                        <span class="slider round"></span>
                    </label>
                    <span class="rule-name">Index Usage</span>
                    <span class="rule-desc">Warn about potential missing indexes (disabled by default)</span>
                </div>
            </div>
        </div>
        
        <button type="submit" id="analyzeBtn" class="btn">
            <i class="fas fa-search"></i> Analyze SQL
        </button>
    </form>
    <div id="results"></div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Custom JS -->
<script src="/js/app.js"></script>
</body>
</html>