<?php
session_start();
include_once "../scripts/connect.php";

// Sprawdzenie, czy użytkownik jest zalogowany
if (isset($_SESSION['id_user']) && isset($_SESSION['email'])) {

    // ID zalogowanego użytkownika
    $userId = $_SESSION['id_user'];

    // Pobieranie recenzji otrzymanych przez zalogowanego użytkownika wraz z tytułem aukcji
    $sql = "SELECT reviews.*, users.firstName, users.profile_image, completed_auctions.title 
            FROM reviews 
            JOIN completed_auctions ON reviews.completed_auction_id = completed_auctions.id
            JOIN users ON reviews.reviewer_id = users.id_user
            WHERE reviews.reviewed_user_id = :id_user
            ORDER BY reviews.review_date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_user', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC); // Pobranie wyników zapytania

    // Informacje o zalogowanym użytkowniku
    $sqlUser = "SELECT firstName, profile_image FROM users WHERE id_user = :id_user";
    $stmtUser = $conn->prepare($sqlUser);
    $stmtUser->bindParam(':id_user', $userId, PDO::PARAM_INT);
    $stmtUser->execute();
    $userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

    $_SESSION['firstName'] = $userData['firstName'];
    $_SESSION['profile_image'] = $userData['profile_image'];
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twoje opinie</title>
    <link rel="stylesheet" href="../src/output.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
<header class="bg-blue-950">       
    <div class="container w-4/5 m-auto bg-blue-950 flex justify-around p-8">
        <div><a href="loggin.php"><img src="/projekt/projekt/img/BidHub_logo_removebg_minimalized.png" alt="Logo" width="150" height="150"></a></div>
        <div class="text-white"><a href="user_profile.php">Moje Dane</a></div>
        <div class="text-white"><a href="user_profile_auctions.php">Twoje Aukcje</a></div>
        <div class="text-white"><a href="my_reviews.php">Twoje opinie</a></div>
        <div class="text-white"><a href="user_profile_fav.php">Obserwowane aukcje</a></div>
        <div class="text-white"><a href="user_win_auction_profile.php">Wygrane aukcje</a></div> 
        <div class="text-white"><a href="user_sold_list.php">Sprzedane</a></div>    
    </div>
</header>

<main class="container w-4/5 m-auto mt-10">
    <h2 class="text-2xl font-bold mb-4">Otrzymane opinie</h2>
    <ul>
        <?php if (!empty($reviews)): ?>
            <?php foreach ($reviews as $review): ?>
                <li class="bg-gray-200 m-1 p-4 rounded-lg">
                    <div class="flex items-center">
                        <img class="w-10 h-10 rounded-full mr-3" src="<?php echo htmlspecialchars($review['profile_image']); ?>" alt="Zdjęcie profilu">
                        <div>
                            <p class="font-bold"><?php echo htmlspecialchars($review['firstName']); ?></p>
                            <p class="text-sm text-gray-500"><?php echo htmlspecialchars(date('d-m-Y', strtotime($review['review_date']))); ?></p>
                            <p class="font-semibold"> <?php echo htmlspecialchars($review['title']); ?></p> <!-- Tytuł aukcji -->
                        </div>
                    </div>
                    <div class="mt-2">
                        <p class="text-sm"><?php echo htmlspecialchars($review['comment']); ?></p>
                        <p class="font-bold">Ocena: <?php echo htmlspecialchars($review['rating']); ?>/5</p>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Brak otrzymanych opinii.</p>
        <?php endif; ?>
    </ul>
</main>

<script src="../js/dropdown.js"></script>
</body>
</html>

<?php
} else {
    header("Location: loggin.php");
    exit();
}
?>
