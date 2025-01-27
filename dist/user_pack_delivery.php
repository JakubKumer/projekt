<?php
// Inicjalizacja sesji, pobranie połączenia z bazą danych, itd.
session_start();
include_once "../scripts/connect.php";
$currentUserId = $_SESSION['id_user'];

// Obsługa formularza do oznaczania aukcji jako odebrane
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $updateQuery = "UPDATE completed_auctions 
                    SET delivery_status = 'odebrane' 
                    WHERE highest_bidder_id = :userId AND delivery_status = 'nieodebrane'";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bindParam(':userId', $currentUserId, PDO::PARAM_INT);
    $updateStmt->execute();
}

// Pobranie zakończonych aukcji dla zalogowanego użytkownika
$query = "SELECT * FROM completed_auctions WHERE highest_bidder_id = :userId";
$stmt = $conn->prepare($query);
$stmt->bindParam(':userId', $currentUserId, PDO::PARAM_INT);
$stmt->execute();
$win = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/user_profile.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Wygrane aukcje</title>
</head>
<body>
<header class="bg-blue-950">       
    <div class="container w-4/5 m-auto bg-blue-950 flex justify-around p-8">
        <div class=""><a href="loggin.php"><img src="/projekt/projekt/img/BidHub_logo_removebg_minimalized.png" alt="Błąd załadowania zdjęcia" width="150" height="150"></a></div>
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
        <h3 class="text-base font-semibold leading-7 text-gray-900">Wygrane aukcje</h3>
        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">Szczegóły wygranych aukcji.</p>
    </div>
    <div class="mt-6 border-t border-gray-300">
        <?php foreach ($win as $auction): ?>
            <dl class="divide-y divide-gray-300">
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Tytuł aukcji</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"><?php echo htmlspecialchars($auction['title']); ?></dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Cena</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"><?php echo htmlspecialchars($auction['price']); ?> zł</dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Data zakończenia</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"><?php echo htmlspecialchars($auction['end_time']); ?></dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Status płatności</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"><?php echo htmlspecialchars($auction['payment_status']); ?></dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Status dostawy</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"><?php echo htmlspecialchars($auction['delivery_status']); ?></dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Czy wysłano?</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"><?php echo htmlspecialchars($auction['is_send']); ?></dd>
                </div>

                <?php
                // Sprawdzenie, czy użytkownik już dodał opinię do danej aukcji
                $reviewCheckQuery = "SELECT COUNT(*) 
                                     FROM reviews 
                                     WHERE reviewer_id = :reviewer_id AND completed_auction_id = :completed_auction_id";
                $reviewCheckStmt = $conn->prepare($reviewCheckQuery);
                $reviewCheckStmt->bindParam(':reviewer_id', $currentUserId, PDO::PARAM_INT);
                $reviewCheckStmt->bindParam(':completed_auction_id', $auction['id'], PDO::PARAM_INT);
                $reviewCheckStmt->execute();
                $hasReviewed = $reviewCheckStmt->fetchColumn() > 0;

                // Sprawdzenie statusu dostawy
                $isDelivered = $auction['delivery_status'] === 'odebrane';
                ?>
                
                <!-- Formularz do oznaczenia jako odebrane -->
                <form method="POST" class="mt-8 h-1/2 flex justify-end opacity-100">
                    <button type="submit" name="update_status" class="<?php echo $isDelivered ? 'bg-green-500 text-white font-bold py-2 px-4 rounded' : 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded'; ?>">
                        <?php echo $isDelivered ? 'Odebrane' : 'Oznacz jako odebrane'; ?>
                    </button>
                </form>

                <!-- Formularz do dodania opinii -->
                <form method="GET" action="add_review.php" class="mt-8 h-1/2 flex justify-end opacity-100">
                    <input type="hidden" name="id_auction" value="<?php echo $auction['id']; ?>">

                    <?php if ($hasReviewed): ?>
                        <button type="button" class="bg-gray-500 text-white font-bold py-2 px-4 rounded cursor-not-allowed" disabled>
                            Już dodałeś opinię
                        </button>
                    <?php else: ?>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Dodaj opinię
                        </button>
                    <?php endif; ?>
                </form>
            </dl>
        <?php endforeach; ?>
    </div>
</main>
</body>
</html>
