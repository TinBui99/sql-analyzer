# SQL Query Analyzer

A PHP-based SQL query analyzer that helps identify potential issues and optimize your SQL queries. The tool checks for common SQL anti-patterns and provides suggestions for improvement.

## Features

- Detects common SQL issues and anti-patterns
- Customizable rule-based analysis
- Simple web interface
- RESTful API endpoint for programmatic access
- Supports multiple SQL query analysis rules

## Prerequisites

- PHP 8.0 or higher
- Composer (Dependency Manager for PHP)
- Web server (Apache/Nginx) or PHP's built-in development server

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/TinBui99/sql-analyzer.git
   cd sql-analyzer
   ```

2. **Docker setup**
   ```bash
   docker compose up -d --build
   ```

3. **Install dependencies**
   ```bash
   docker exec -it {{container_name}} bash
   composer install
   ```


   Then open `http://localhost:8090` in your browser. 

## Usage

### Web Interface

1. Open the application in your web browser
2. Enter your SQL query in the provided text area
3. (Optional) Select specific rules to apply
4. Click "Analyze" to check for issues

### API Endpoint

You can also use the analyzer programmatically via the API:

```bash
# Using cURL
curl -X POST http://your-domain/analyze \
     -d "sql=SELECT * FROM users WHERE id = 1" \
     -d "rules[]=SelectStarRule&rules[]=NoIndexRule"
```

**Response Format:**
```json
{
    "success": true,
    "issue": ["Issue description 1", "Issue description 2"],
    "warning": ["Warning message 1"],
    "error": []
}
```

## Available Rules

The analyzer comes with several built-in rules:

- **SelectStarRule**: Warns against using `SELECT *` in queries
- **MissingWhereClauseRule**: Identifies queries without a WHERE clause on UPDATE/DELETE
- **NoIndexRule**: Checks for missing indexes on filtered columns
- **LimitOffsetRule**: Warns about potential performance issues with large OFFSET values

## Adding Custom Rules

1. Create a new rule class in `app/Rules/` that implements `RuleInterface`
2. Add your rule class to the `$availableRules` array in `SqlAnalyzer.php`
3. The rule will be automatically loaded and available for use

## Support

For support, please open an issue in the GitHub repository.
