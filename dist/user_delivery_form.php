<?php
session_start();
include_once "../scripts/connect.php";

// Sprawdzenie, czy `auction_id` jest ustawione w URL
if (!isset($_GET['auction_id'])) {
    echo "Brak dostępu. Musisz podać prawidłowe ID aukcji.";
    exit;
}

// Pobranie `auction_id` z URL
$auction_id = (int)$_GET['auction_id'];

// Zapytanie SQL: pobranie `id_user` z tabeli `completed_auctions` dla konkretnego `auction_id`
$queryAuction = "
    SELECT highest_bidder_id
    FROM completed_auctions
    WHERE id = :auction_id
";
$stmtAuction = $conn->prepare($queryAuction);
$stmtAuction->bindParam(':auction_id', $auction_id, PDO::PARAM_INT);
$stmtAuction->execute();

// Sprawdzenie, czy znaleziono rekord dla podanego `auction_id`
$auction = $stmtAuction->fetch(PDO::FETCH_ASSOC);
if (!$auction) {
    echo "Brak wyników dla tej aukcji.";
    exit;
}

// Pobranie danych użytkownika na podstawie `id_user` z wyniku powyższego zapytania
$id_user = (int)$auction['highest_bidder_id'];
$queryUser = "
    SELECT firstName, lastName, email, city, street, house_number, postal_code, phone_number
    FROM users
    WHERE id_user = :id_user
";
$stmtUser = $conn->prepare($queryUser);
$stmtUser->bindParam(':id_user', $id_user, PDO::PARAM_INT);
$stmtUser->execute();

// Sprawdzenie, czy dane użytkownika zostały znalezione
$userData = $stmtUser->fetch(PDO::FETCH_ASSOC);
if (!$userData) {
    echo "Brak danych użytkownika.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/user_profile.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Dane do wysyłki</title>
</head>
<body class="bg-gray-100">
<header class="bg-blue-950">       
        <div class=" container w-4/5 m-auto bg-blue-950 flex justify-around  p-8">
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
        <h3 class="text-xl font-semibold leading-7 text-gray-900">Dane do wysyłki</h3>
        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">Szczegóły adresowe kupującego.</p>
    </div>
    <div class="mt-6 border-t border-gray-300">
        <dl class="divide-y divide-gray-300">
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Imię i Nazwisko</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                    <?php echo htmlspecialchars($userData['firstName'] . ' ' . $userData['lastName']); ?>
                </dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Email</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                    <?php echo htmlspecialchars($userData['email']); ?>
                </dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Adres</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                    <?php echo htmlspecialchars($userData['street'] . ' ' . $userData['house_number'] . ', ' . $userData['postal_code'] . ' ' . $userData['city']); ?>
                </dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Numer telefonu</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                    <?php echo htmlspecialchars($userData['phone_number']); ?>
                </dd>
            </div>
        </dl>
    </div>
</main>
</body>
</html>