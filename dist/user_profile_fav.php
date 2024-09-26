<?php
    session_start();
    include_once "../scripts/connect.php";
    $currentUserId = $_SESSION['id_user'];
    $sql = "SELECT a.*, c.category_name
            FROM auctions a
            JOIN categories c ON a.id_category = c.id_category
            JOIN favorites f ON a.id_auction = f.id_auction
            WHERE f.id_user = :id_user";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id_user' => $currentUserId]);
    $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
    <title>Profil użytkownika</title>
    <link rel="stylesheet" href="../src/user_profile.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-blue-950">       
        <div class=" container w-4/5 m-auto bg-blue-950 flex justify-around  p-8">
        <div class=""><a href="loggin.php"><img src="/projekt/projekt/img/BidHub_logo_removebg_minimalized.png" alt="Błąd załadowania zdjęcia" width="150" height="150"></a></div>
            <div class="text-white"><a href="user_profile.php">Moje Dane</a></div>
            <div class="text-white"><a href="user_profile_auctions.php">Twoje Aukcje</a></div>
            <div class="text-white"><a href="user_profile_fav.php">Obserwowane aukcje</a></div>
                
        </div>
    </header>
    <div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold">Obserwowane aukcje</h1>
    <ul class="mt-4">
        <?php if (!empty($favorites)): ?>
            <?php foreach ($favorites as $fav): ?>
                <li class="bg-gray-200 m-1 h-auto rounded-lg hover:shadow-blue-900 hover:shadow-lg hover:bg-slate-300">
                    <a href="product_page.php?id_auction=<?php echo htmlspecialchars($fav['id_auction']); ?>">  
                        <div class="flex justify-between p-2">
                            <img class="w-20 h-20 object-cover rounded-lg" src="<?php echo htmlspecialchars($fav['image']); ?>" alt="<?php echo htmlspecialchars($fav['title']); ?>">
                            <p class="w-1/3 ml-5"><?php echo htmlspecialchars($fav['title']); ?></p>
                            <p class="w-1/3">Cena: <?php echo htmlspecialchars($fav['start_price']); ?> zł</p>
                            <p class="w-1/4">Zakończenie: <?php echo htmlspecialchars(date('d-m-Y H:i', strtotime($fav['end_time']))); ?></p>
                        </div>
                    </a>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nie masz żadnych obserwowanych aukcji.</p>
        <?php endif; ?>
    </ul>
</div>
   
</body>
</html>
