<?php
require_once __DIR__ . '/config/DatabaseConn.php';

try {
    $pdo = DatabaseConn::getInstance()->getConnection();
    
    // Verificar si la tabla existe
    $checkTable = $pdo->query("SHOW TABLES LIKE 'pattern_scores'");
    $tableExists = $checkTable->rowCount() > 0;
    
    echo "Tabla 'pattern_scores' existe: " . ($tableExists ? 'Sí' : 'No') . "<br>";
    
    if (!$tableExists) {
        echo "<br>Intentando crear tabla...<br>";
        $createTable = <<<SQL
        CREATE TABLE pattern_scores (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) NOT NULL UNIQUE,
            score INT NOT NULL,
            time_seconds INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_score (score DESC),
            INDEX idx_time (time_seconds ASC)
        )
        SQL;
        
        $pdo->exec($createTable);
        echo "Tabla creada exitosamente<br>";
    }
    
    // Obtener datos de la tabla
    $result = $pdo->query("SELECT * FROM pattern_scores ORDER BY score DESC LIMIT 10");
    $rows = $result->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<br>Registros en la tabla: " . count($rows) . "<br>";
    
    if (count($rows) > 0) {
        echo "<pre>";
        print_r($rows);
        echo "</pre>";
    } else {
        echo "No hay registros en la tabla.";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
