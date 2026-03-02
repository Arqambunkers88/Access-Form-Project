<?php
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    // The design doc UI included a category, so we capture it (we append it to the description so it fits the ERD strictly)
    $category = $_POST['category'];
    $full_description = "Category: " . $category . "\n" . $description;
    
    $creator_id = $_SESSION['user_id'];

    try {
        // Insert Survey into Database (Default status is 'Draft')
        $sql = "INSERT INTO Survey (title, description, creator_id) VALUES (:title, :description, :creator_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => $title,
            ':description' => $full_description,
            ':creator_id' => $creator_id
        ]);

        // Get the ID of the survey we just created so we can add questions to it
        $survey_id = $pdo->lastInsertId();

        // Redirect to the Form Builder (Add Questions screen)
        header("Location: builder.php?survey_id=" . $survey_id);
        exit();

    } catch (PDOException $e) {
        die("Error creating survey: " . $e->getMessage());
    }
}
?>