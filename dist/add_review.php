<?php
session_start();
include_once "../scripts/connect.php";

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['id_user'])) {
    header("Location: loggin.php");
    exit();
}

// Sprawdzenie, czy ID aukcji zostało przekazane
if (!isset($_GET['id_auction'])) {
    die("Brak ID aukcji.");
}

$auctionId = intval($_GET['id_auction']);
$currentUserId = $_SESSION['id_user'];

// Pobranie szczegółów zakończonej aukcji
$completedAuctionQuery = "SELECT title, id_user AS seller_id, highest_bidder_id 
                          FROM completed_auctions 
                          WHERE id = :id_auction";
$completedAuctionStmt = $conn->prepare($completedAuctionQuery);
$completedAuctionStmt->bindParam(':id_auction', $auctionId, PDO::PARAM_INT);
$completedAuctionStmt->execute();
$completedAuction = $completedAuctionStmt->fetch(PDO::FETCH_ASSOC);

// Sprawdzenie, czy aukcja istnieje i czy użytkownik jest jej zwycięzcą
if (!$completedAuction || $completedAuction['highest_bidder_id'] != $currentUserId) {
    die("Nie masz uprawnień do wystawienia opinii dla tej aukcji.");
}

$auctionTitle = $completedAuction['title'];
$sellerId = $completedAuction['seller_id'];

// Sprawdzenie, czy użytkownik już dodał opinię do tej zakończonej aukcji
$checkQuery = "SELECT COUNT(*) 
               FROM reviews 
               WHERE reviewer_id = :reviewer_id AND completed_auction_id = :completed_auction_id";
$checkStmt = $conn->prepare($checkQuery);
$checkStmt->bindParam(':reviewer_id', $currentUserId, PDO::PARAM_INT);
$checkStmt->bindParam(':completed_auction_id', $auctionId, PDO::PARAM_INT);
$checkStmt->execute();
$reviewCount = $checkStmt->fetchColumn();

if ($reviewCount > 0) {
    die("<p class='text-red-500'>Już dodałeś opinię do tej aukcji.</p>");
}

// Obsługa formularza
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rating = intval($_POST['rating']);
    $comment = $_POST['comment'];
    $date = date('Y-m-d H:i:s');

    // Wstawianie recenzji do bazy danych
$insertQuery = "INSERT INTO reviews (completed_auction_id, reviewer_id, reviewed_user_id, rating, comment, review_date) 
VALUES (:completed_auction_id, :reviewer_id, :reviewed_user_id, :rating, :comment, :review_date)";
$insertStmt = $conn->prepare($insertQuery);
$insertStmt->bindParam(':completed_auction_id', $auctionId, PDO::PARAM_INT);
$insertStmt->bindParam(':reviewer_id', $currentUserId, PDO::PARAM_INT);
$insertStmt->bindParam(':reviewed_user_id', $sellerId, PDO::PARAM_INT);
$insertStmt->bindParam(':rating', $rating, PDO::PARAM_INT);
$insertStmt->bindParam(':comment', $comment, PDO::PARAM_STR);
$insertStmt->bindParam(':review_date', $date);

if ($insertStmt->execute()) {
// Przekierowanie do user_pack_delivery.php po pomyślnym dodaniu opinii
header("Location: user_pack_delivery.php");
exit(); // Zatrzymaj dalsze wykonywanie skryptu
} else {
echo "<p class='text-red-500'>Wystąpił błąd podczas dodawania opinii.</p>";
}

}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj opinię</title>
    <link rel="stylesheet" href="../src/user_profile.css">
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
        <div class="px-4 sm:px-0">
            <h3 class="text-base font-semibold leading-7 text-gray-900">Dodaj opinię</h3>
            <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">Wystaw swoją opinię do aukcji <strong><?php echo htmlspecialchars($auctionTitle); ?></strong></p>
        </div>
        
        <form method="POST" class="mt-8">
            <div class="mb-4">
                <label for="rating" class="block text-sm font-medium text-gray-700">Ocena:</label>
                <select name="rating" id="rating" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="" disabled selected>Wybierz ocenę</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="comment" class="block text-sm font-medium text-gray-700">Komentarz:</label>
                <textarea name="comment" id="comment" rows="4" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>
            <div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Dodaj opinię
            </button>
            </div>
        </form>
    </main>
</body>
</html>
