<?php
session_start();
include_once "../scripts/connect.php";

// Sprawdzenie, czy `seller_id` jest przekazane w parametrze GET
if (!isset($_GET['seller_id'])) {
    echo "Sprzedający nie został znaleziony.";
    exit;
}

$sellerId = (int)$_GET['seller_id'];

// Pobranie informacji o sprzedającym
$sellerInfoQuery = "SELECT firstName, lastName FROM users WHERE id_user = :seller_id";
$sellerInfoStmt = $conn->prepare($sellerInfoQuery);
$sellerInfoStmt->execute(['seller_id' => $sellerId]);
$sellerInfo = $sellerInfoStmt->fetch(PDO::FETCH_ASSOC);

if (!$sellerInfo) {
    echo "Sprzedający nie został znaleziony.";
    exit;
}

// Pobranie wszystkich opinii o sprzedającym
$reviewsSql = "SELECT r.rating, r.comment, r.review_date, u.firstName AS reviewer_first_name, u.profile_image, ca.title AS auction_title 
               FROM reviews r
               JOIN users u ON r.reviewer_id = u.id_user
               JOIN completed_auctions ca ON r.completed_auction_id = ca.id
               WHERE r.reviewed_user_id = :seller_id
               ORDER BY r.review_date DESC";

$reviewsStmt = $conn->prepare($reviewsSql);
$reviewsStmt->execute(['seller_id' => $sellerId]);
$sellerReviews = $reviewsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opinie o sprzedającym: <?php echo htmlspecialchars($sellerInfo['firstName'] . ' ' . $sellerInfo['lastName']); ?></title>
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

<main class="container mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-4">Opinie o sprzedającym: <?php echo htmlspecialchars($sellerInfo['firstName'] . ' ' . $sellerInfo['lastName']); ?></h2>
    
    <ul>
        <?php if (!empty($sellerReviews)): ?>
            <?php foreach ($sellerReviews as $review): ?>
                <li class="bg-gray-200 m-1 p-4 rounded-lg">
                    <div class="flex items-center">
                        <img class="w-10 h-10 rounded-full mr-3" src="<?php echo htmlspecialchars($review['profile_image']); ?>" alt="Zdjęcie profilu">
                        <div>
                            <p class="font-bold"><?php echo htmlspecialchars($review['reviewer_first_name']); ?></p>
                            <p class="text-sm text-gray-500"><?php echo htmlspecialchars(date('d-m-Y', strtotime($review['review_date']))); ?></p>
                            <p class="font-semibold">Aukcja: <?php echo htmlspecialchars($review['auction_title']); ?></p> <!-- Tytuł aukcji -->
                        </div>
                    </div>
                    <div class="mt-2">
                        <p class="text-sm"><?php echo htmlspecialchars($review['comment']); ?></p>
                        <p class="font-bold">Ocena: <?php echo htmlspecialchars($review['rating']); ?>/5</p>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Sprzedający nie ma jeszcze żadnych opinii.</p>
        <?php endif; ?>
    </ul>
</main>

<script src="../js/dropdown.js"></script>
</body>
</html>
