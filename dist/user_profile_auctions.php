<?php
session_start();
include_once "../scripts/connect.php";
$id_user = $_SESSION['id_user'];

$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$successMessage = isset($_SESSION['success']) ? $_SESSION['success'] : "";
unset($_SESSION['errors']);
unset($_SESSION['success']);
$sql = "
    SELECT a.*, c.category_name 
    FROM auctions a 
    JOIN categories c ON a.id_category = c.id_category 
    WHERE a.id_user = :id_user
";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
$stmt->execute();
$auctions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/user_profile2.css">
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
        </div>
    </header>
   <div class="kontener ">
    <div class="flex justify-evenly m-3">
    <h2>Twoje aukcje</h2>
    <button type="button" class="ml-3 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"><a href="add_auction_user.php">Dodaj Aukcje</a></button>
    </div>
        <table>
            <tr>
                <th>Numer aukcji</th>
                <th>Tytuł</th>
                <th>Opis</th>
                <th>Zdjęcie</th>
                <th>kategoria</th>
                <th>Cena początkowa</th>
                <th>Akcje</th>
            </tr>
            <?php foreach ($auctions as $auction): ?>
                <tr>
                <td><?php echo htmlspecialchars($auction['id_auction']); ?></td>
                    <td><?php echo htmlspecialchars($auction['title']); ?></td>
                    <td><?php echo htmlspecialchars($auction['description']); ?></td>
                    <td><img src="<?php echo htmlspecialchars($auction['image']); ?>" alt="Zdjęcie produktu" width="100"></td>
                    <td><?php echo htmlspecialchars($auction['category_name']); ?></td>
                    <td><?php echo htmlspecialchars($auction['start_price']); ?></td>
                    <td>                        
                        <span class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20"><a href="edit_auction.php?id=<?php echo htmlspecialchars($auction['id_auction']); ?>">Edytuj</a></span>
                        <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10"><a href="../scripts/delete_auctions.php?id=<?php echo htmlspecialchars($auction['id_auction']); ?>">Usuń</a></span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
   </div>
</body>
</html>