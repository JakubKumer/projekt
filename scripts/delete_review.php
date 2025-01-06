<?php
session_start();
include_once "connect.php"; // Włączenie połączenia z bazą danych

// Sprawdzenie, czy użytkownik jest zalogowany i ma rolę administratora
if (!isset($_SESSION['id_user'])) {
    header("Location: ../pages/login.php");
    exit();
}

$id_user = $_SESSION['id_user'];

// Sprawdzenie roli użytkownika
$query = "SELECT id_role FROM users WHERE id_user = :id_user";
$stmt = $conn->prepare($query);
$stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user || $user['id_role'] != 3) {
    echo "Brak uprawnień do usuwania opinii.";
    exit();
}

// Sprawdzenie, czy parametr `id` został przekazany w URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Nieprawidłowy identyfikator opinii.";
    exit();
}

$id_review = (int)$_GET['id'];

// Usunięcie opinii z bazy danych
$query = "DELETE FROM reviews WHERE id_review = :id_review";
$stmt = $conn->prepare($query);

try {
    $stmt->execute(['id_review' => $id_review]);
    header("Location: ../dist/admin_reviews.php?success=1"); // Powrót do strony po sukcesie
    exit();
} catch (PDOException $e) {
    echo "Wystąpił błąd podczas usuwania opinii: " . $e->getMessage();
    exit();
}
?>