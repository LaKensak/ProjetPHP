<?php
if (isset($_POST['chrono']) && isset($_POST['userId']) && isset($_POST['cubeId'])) {
    $chrono = $_POST['chrono'];
    $userId = $_POST['userId'];
    $cubeId = $_POST['cubeId'];

    $sql = "INSERT INTO chronos (user_id, cube_id, chrono_time) VALUES (:userId, :cubeId, :chrono)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['userId' => $userId, 'cubeId' => $cubeId, 'chrono' => $chrono]);

    echo json_encode(['success' => true, 'user_id' => $userId, 'cube_id' => $cubeId]);
    exit;
}
?>