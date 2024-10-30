<?php
session_start();
include_once "../scripts/connect.php";

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit(); // Zatrzymanie dalszego ładowania strony
}

// Pobieranie id_user z sesji
$id_user = $_SESSION['id_user'];

// Sprawdzenie, czy użytkownik ma przypisaną rolę administratora (id_role == 3)
$query = "SELECT id_role FROM users WHERE id_user = :id_user";
$stmt = $conn->prepare($query);
$stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);
if ($user) {
    $id_role = $user['id_role'];

    if ($id_role != 3) {
        echo "Brak uprawnień do przeglądania tej strony.";
        exit(); // Zatrzymanie dalszego ładowania strony
    }
} else {
    header("Location: login.php");
    exit(); // Zatrzymanie dalszego ładowania strony
}

// Zapytanie do bazy danych, aby pobrać wszystkie opinie
$query = "SELECT r.id_review, r.rating, r.comment, r.review_date, a.title, 
                 u.firstName AS reviewed_firstName, u.lastName AS reviewed_lastName, 
                 ur.firstName AS reviewer_firstName, ur.lastName AS reviewer_lastName
          FROM reviews r 
          JOIN completed_auctions a ON r.completed_auction_id = a.id
          JOIN users u ON u.id_user = r.reviewed_user_id 
          JOIN users ur ON ur.id_user = r.reviewer_id 
          ORDER BY r.review_date DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once "../scripts/admin_filtr.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opinie Administratora</title>
    <link rel="stylesheet" href="../src/admin.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
<div class="sidebar">
        <a href="admin.php"><i class="fa fa-fw fa-user"></i> Użytkownicy</a>
        <a href="auctions_admin.php"><i class="fa fa-clipboard"></i> Aukcje</a>
        <a href="category_admin.php"><i class="fa fa-clipboard"></i> Kategorie</a>
        <a href="finished_auction_admin.php"><i class="fa fa-clipboard"></i> Zakończone aukcje</a>
        <a href="admin_reviews.php"><i class="fa fa-clipboard"></i> Opinie</a>
        <a href="../scripts/logout.php"><i class="fa fa-clipboard"></i> Wyloguj</a>
</div>
<div class="main">
<h1 class="text-5xl">Panel Administratora</h1>
    <h2>Opinie</h2>
    <table>
        <tr>
            <th>ID Opinii</th>
            <th>Ocena</th>
            <th>Komentarz</th>
            <th>Data</th>
            <th>Aukcja</th>
            <th>Imię Wystawiającego</th>
            <th>Nazwisko Wystawiającego</th>
            <th>Imię Recenzowanego</th>
            <th>Nazwisko Recenzowanego</th>
            <th>Akcje</th>
        </tr>
        <?php
        if (count($reviews) > 0) {
            foreach ($reviews as $row) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["id_review"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["rating"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["comment"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["review_date"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["title"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["reviewer_firstName"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["reviewer_lastName"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["reviewed_firstName"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["reviewed_lastName"]) . "</td>";
                echo "<td><a href='../scripts/delete_review.php?id=" . htmlspecialchars($row["id_review"]) . "'>Usuń</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='10'>Brak opinii.</td></tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
