<?php
session_start(); 
include_once "../scripts/connect.php"; 
$query = "
    SELECT u.id_user, u.firstName,  u.lastName, u.email, u.city, u.street , u.house_number, u.postal_code, u.phone_number
    FROM users u
    INNER JOIN completed_auctions ca ON u.id_user = ca.highest_bidder_id
";

$stmt = $conn->prepare($query);
$stmt->execute();

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
            <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">Szczegóły adresowe kupujących.</p>
        </div>
        <div class="mt-6 border-t border-gray-300">
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <dl class="divide-y divide-gray-300">
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Imię i Nazwisko</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            <?php echo htmlspecialchars($row['firstName'] . ' ' . $row['lastName']); ?>
                        </dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Email</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            <?php echo htmlspecialchars($row['email']); ?>
                        </dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Adres</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            <?php echo htmlspecialchars($row['street'] . ' ' . $row['house_number'] . ', ' . $row['postal_code'] . ' ' . $row['city']); ?>
                        </dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Numer telefonu</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            <?php echo htmlspecialchars($row['phone_number']); ?>
                        </dd>
                    </div>
                </dl>
            <?php endwhile; ?>
        </div>
    </main>
</body>
</html>