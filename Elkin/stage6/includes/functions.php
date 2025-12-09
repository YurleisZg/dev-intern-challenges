<?php
function read($db_conn, $userId, $start = 0, $limit = 5) {
    $pdo = $db_conn->getConnection();
    $stmt = $pdo->prepare("SELECT * FROM todos WHERE user_id = :user_id ORDER BY id ASC LIMIT :start, :limit");
    $stmt->bindValue(':user_id', (int)$userId, PDO::PARAM_INT);
    $stmt->bindValue(':start', (int)$start, PDO::PARAM_INT);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
