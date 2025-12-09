<?php
require_once __DIR__ . '/models.php';

function read($db_conn, $userId, $start = 0, $limit = 5) {
    // Wrapper para compatibilidad con el código existente
    return Task::listByUser((int)$userId, (int)$start, (int)$limit);
}

function register( $name) {
    var_dump($name);
}
function getStatusClass($state) {
    return match(strtolower($state)) {
        'todo' => 'status-pending',
        'doing', 'in-progress' => 'status-inprogress',
        'done', 'done' => 'status-completed',
        default => '',
    };
}
