<?php
    session_start();
    include_once "../scripts/connect.php";
    $currentUserId = $_SESSION['id_user'];
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
    <title>Document</title>
</head>
<body>
    <header class="bg-blue-950">       
        <div class=" container w-4/5 m-auto bg-blue-950 flex justify-around  p-8">
        <div class=""><a href="loggin.php"><img src="/projekt/projekt/img/BidHub_logo_removebg_minimalized.png" alt="Błąd załadowania zdjęcia" width="150" height="150"></a></div>
            <div class="text-white"><a href="user_profile.php">Moje Dane</a></div>
            <div class="text-white"><a href="user_profile_auctions.php">Twoje Aukcje</a></div>
            <div class="text-white"><a href="user_profile_fav.php">Obserwowane aukcje</a></div>   
            <div class="text-white"><a href="user_win_auction_profile.php">Wygrane aukcje</a></div>
            <div class="text-white"><a href="user_sold_list.php">sprzedane</a></div>    
        </div>
    </header>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold">Wygrane aukcje</h1>
        <ul class="mt-4">
            <?php if (!empty($win)): ?>
                <?php foreach ($win as $fav): ?>
                    <li class="bg-gray-200 m-1 h-auto rounded-lg hover:shadow-blue-900 hover:shadow-lg hover:bg-slate-300">
                        <?php
                        // Sprawdź status płatności
                        if ($fav['payment_status'] == 'zaplacone') {
                            // Jeśli zapłacone, przekieruj do strony z potwierdzeniem
                            $redirectUrl = "user_pack_delivery.php?id=" . htmlspecialchars($fav['id']);
                        } else {
                            // Jeśli nie zapłacone, przekieruj do strony płatności
                            $redirectUrl = "user_win_form.php?id=" . htmlspecialchars($fav['id']);
                        }
                        ?>
                        <a href="<?php echo $redirectUrl; ?>">
                            <div class="flex justify-between p-2">
                                <img class="w-20 h-20 object-cover rounded-lg" src="<?php echo htmlspecialchars($fav['image']); ?>" alt="<?php echo htmlspecialchars($fav['title']); ?>">
                                <p class="w-1/3 ml-5"><?php echo htmlspecialchars($fav['title']); ?></p>
                                <p class="w-1/3">Cena końcowa: <?php echo htmlspecialchars($fav['price']); ?> zł</p>
                                <p class="w-1/4">Zakończenie: <?php echo htmlspecialchars(date('d-m-Y H:i', strtotime($fav['end_time']))); ?></p>
                                <p class="w-1/4 font-bold">
                                    Status: <?php echo htmlspecialchars($fav['payment_status'] == 'zaplacone' ? 'Zapłacone' : 'Nie zapłacone'); ?>
                                </p>
                            </div>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nie masz wygranych aukcji.</p>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>