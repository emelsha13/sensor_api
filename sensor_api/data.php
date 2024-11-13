<?php
require 'config.php';

header("Content-Type: application/json");

try {
    $stmt = $pdo->query("SELECT idx, suhu, humid, kecerahan, timestamp FROM sensor ORDER BY timestamp DESC LIMIT 5");
    $sensors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $summaryStmt = $pdo->query("
        SELECT 
            MAX(suhu) AS suhumax, 
            MIN(suhu) AS suhumin, 
            AVG(suhu) AS suhurata
        FROM sensor
    ");
    $summary = $summaryStmt->fetch(PDO::FETCH_ASSOC);

    $response = [
        "data" => $sensors,
        "summary" => $summary,
        "month_year_max" => date("n-Y")
    ];

    echo json_encode($response, JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
