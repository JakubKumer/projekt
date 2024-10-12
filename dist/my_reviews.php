<?php
session_start();
include_once "../scripts/connect.php";

if (isset($_SESSION['id_user']) && isset($_SESSION['email'])) {

    // Pobieranie recenzji z bazy danych
    $sql = "SELECT reviews.*, users.firstName, users.profile_image 
            FROM reviews 
            JOIN users ON reviews.id_user = users.id_user 
            ORDER BY reviews.date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Informacje o użytkowniku
    $userId = $_SESSION['id_user'];
    $sqlUser = "SELECT firstName, profile_image FROM users WHERE id_user = :id_user";
    $stmtUser = $conn->prepare($sqlUser);
    $stmtUser->bindParam(':id_user', $userId);
    $stmtUser->execute();
    $userData = $stmtUser->fetch(PDO::FETCH_ASSOC);
    
    $_SESSION['firstName'] = $userData['firstName'];
    $_SESSION['profile_image'] = $userData['profile_image']; 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recenzje</title>
    <link rel="stylesheet" href="../src/output.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
<header class="bg-blue-950">       
        <div class=" container w-4/5 m-auto bg-blue-950 flex justify-around  p-8">
        <div class=""><a href="loggin.php"><img src="/projekt/projekt/img/BidHub_logo_removebg_minimalized.png" alt="Błąd załadowania zdjęcia" width="150" height="150"></a></div>
            <div class="text-white"><a href="user_profile.php">Moje Dane</a></div>
            <div class="text-white"><a href="user_profile_auctions.php">Twoje Aukcje</a></div>
            <div class="text-white"><a href="my_reviews.php">Twoje opinie</a></div>
            <div class="text-white"><a href="user_profile_fav.php">Obserwowane aukcje</a></div>
                
        </div>
    </header>

<main class="container w-4/5 m-auto flex">
    <div class="w-full mt-20">
        <h2 class="text-2xl font-bold mb-4">Recenzje</h2>
        <ul>
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $review): ?>
                    <li class="bg-gray-200 m-1 p-4 rounded-lg">
                        <div class="flex items-center">
                            <img class="w-10 h-10 rounded-full mr-3" src="<?php echo htmlspecialchars($review['profile_image']); ?>" alt="">
                            <div>
                                <p class="font-bold"><?php echo htmlspecialchars($review['firstName']); ?></p>
                                <p class="text-sm text-gray-500"><?php echo htmlspecialchars(date('d-m-Y', strtotime($review['date']))); ?></p>
                            </div>
                        </div>
                        <div class="mt-2">
                            <p class="text-sm"><?php echo htmlspecialchars($review['comment']); ?></p>
                            <p class="font-bold">Ocena: <?php echo htmlspecialchars($review['rating']); ?>/5</p>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Brak recenzji.</p>
            <?php endif; ?>
        </ul>
    </div>
</main>

<script src="../js/dropdown.js"></script>
</body>
</html>
<?php
} else {
    header("Location: loggin.php");
}
?>