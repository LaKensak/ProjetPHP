<?php
if (isset($_POST['chrono']) && isset($_POST['cubeId']) && isset($_POST['userId'])) {
    $chrono = $_POST['chrono'];
    $cubeId = $_POST['cubeId'];
    $userId = $_POST['userId'];

    // Update the user's chrono
    $sql = "UPDATE users SET chrono = :chrono WHERE id = :userId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['chrono' => $chrono, 'userId' => $userId]);

    // Handle best scores if needed
    if (isset($_POST['bestscores'])) {
        $bestscores = json_decode($_POST['bestscores'], true);
        // Insert logic to save best scores in a suitable table, e.g.:
        // Example: Save the best score for this cube
        foreach ($bestscores as $score) {
            $sql = "INSERT INTO chronos (user_id, cube_id, chrono_time, meilleurs_temps) VALUES (:userId, :cubeId, :chrono_time, :meilleur_temps)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['userId' => $userId, 'cubeId' => $cubeId, 'score' => $score]);
        }
    }

    // Fetch top scores for this cube (implement this logic based on your database schema)
    $sql = "SELECT * FROM chronos WHERE cube_id = :cubeId ORDER BY score LIMIT 10"; // Adjust based on your table structure
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['cubeId' => $cubeId]);
    $topScores = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'top_times' => $topScores]); // Send back top scores
    exit;
}

?>