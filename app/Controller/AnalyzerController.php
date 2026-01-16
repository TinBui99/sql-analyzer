<?php
// app/Controller/AnalyzerController.php
declare(strict_types=1);

namespace App\Controller;

use App\Analyzer\SqlAnalyzer;

class AnalyzerController
{
    private SqlAnalyzer $analyzer;

    public function __construct()
    {
        $this->analyzer = new SqlAnalyzer();
    }

    public function analyze(): string
    {
        header('Content-Type: application/json');

        try {
            // Get data from POST or GET
            $input = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;
            $sql = $input['sql'] ?? null;
            $selectedRules = $input['rules'] ?? [];

            if (empty($sql)) {
                throw new \InvalidArgumentException('SQL query is required');
            }

            // If rules are provided, pass them to the analyzer
            if (!empty($selectedRules)) {
                $this->analyzer->setEnabledRules($selectedRules);
            }

            $result = $this->analyzer->analyze($sql);
//            var_dump($result);die();

            return json_encode([
                'success' => true,
                'issue' => $result['issue'] ?? [],
                'warning' => $result['warning'] ?? [],
                'error' => $result['error'] ?? []
            ]);

        } catch (\Throwable $e) {
            http_response_code(400);
            return json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}