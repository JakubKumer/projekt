<?php
include_once "../scripts/connect.php";

// Sprawdź, czy użytkownik jest zalogowany
session_start();
if (!isset($_SESSION['id_user'])) {
    echo "Musisz być zalogowany, aby dodać recenzję.";
    exit;
}

// Zbieranie danych z formularza
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_auction = $_POST['id_auction']; // ID aukcji
    $rating = $_POST['rating']; // Ocena
    $comment = $_POST['comment']; // Komentarz
    $id_user = $_SESSION['id_user']; // ID użytkownika

    try {
        // Przygotowanie zapytania SQL
        $stmt = $conn->prepare("INSERT INTO reviews (id_user, rating, comment, date) VALUES (:id_user, :rating, :comment, NOW())");

        // Powiązanie parametrów
        $stmt->bindParam(':id_user', $id_user);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);

        // Wykonanie zapytania
        $stmt->execute();

        echo "Recenzja została dodana pomyślnie!";
    } catch (PDOException $e) {
        echo "Błąd: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Dodaj Recenzję</title>
    <link rel="stylesheet" href="../src/edit.css">
</head>
<body>
    <h1>Dodaj Recenzję</h1>
    <form method="POST" action="">
        <label for="id_auction">ID Aukcji:</label>
        <input type="text" name="id_auction" required><br>

        <label for="rating">Ocena:</label>
        <input type="number" name="rating" min="1" max="5" required><br>

        <label for="comment">Komentarz:</label>
        <textarea name="comment" required></textarea><br>

        <input type="submit" value="Dodaj Recenzję">
    </form>
</body>
</html>
