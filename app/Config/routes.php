<?php
declare(strict_types=1);

use App\Controller\AnalyzerController;

return [
    [
        'method' => 'GET',
        'path' => '/',
        'handler' => function () {
            include __DIR__ . '/../Views/sql_analyzer.php';
            return '';
        }
    ],
    [
        'method' => 'POST',
        'path' => '/analyze',
        'handler' => [AnalyzerController::class, 'analyze']
    ],
    // Add more routes here following the same structure
];