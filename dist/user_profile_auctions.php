<?php
session_start();
include_once "../scripts/connect.php";
$sql = "SELECT * FROM auctions WHERE id_user = :id_user";
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
            <div class="text-white ">logo</div>
            <div class="text-white"><a href="loggin.php">Strona Główna</a></div>
            <div class="text-white"><a href="user_profile.php">Moje Dane</a></div>
            <div class="text-white"><a href="user_profile_auctions.php">Twoje Aukcje</a></div>
        </div>
    </header>
   <div class="kontener">
   <h2>Twoje aukcje</h2>
        <table>
            <tr>
                <th>ID aukcji</th>
                <th>Tytuł</th>
                <th>Opis</th>
                <th>Cena początkowa</th>
                <th>Akcje</th>
            </tr>
            <?php foreach ($auctions as $auction): ?>
                <tr>
                    <td><?php echo htmlspecialchars($auction['id_auction']); ?></td>
                    <td><?php echo htmlspecialchars($auction['title']); ?></td>
                    <td><?php echo htmlspecialchars($auction['description']); ?></td>
                    <td><?php echo htmlspecialchars($auction['start_price']); ?></td>
                    <td>
                        <a href="edit_auction.php?id=<?php echo htmlspecialchars($auction['id_auction']); ?>">Edytuj</a> | 
                        <a href="delete_auction.php?id=<?php echo htmlspecialchars($auction['id_auction']); ?>">Usuń</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
   </div>
</body>
</html>