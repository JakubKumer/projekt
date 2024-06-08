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
        <div class=""><a href="loggin.php"><img src="/projekt/projekt/img/BidHub_logo_removebg_minimalized.png" alt="Błąd załadowania zdjęcia" width="150" height="150"></a></div>
            <div class="text-white"><a href="user_profile.php">Moje Dane</a></div>
            <div class="text-white"><a href="user_profile_auctions.php">Twoje Aukcje</a></div>
        </div>
    </header>
    <div class="kontener">
    <h1 class="font-bold text-lg">Dodaj Aukcje</h1>
        <h2>Nowa Aukcja</h2>
        <form class="m-auto w-1/2 flex-wrap h-45" method="POST" action="user_profile.php">
           <div class="block w-1/2 m-auto">
                <label for="firstName">Nazwa produktu:</label>
                <input type="text" name="firstName" id="firstName" value="" readonly><br>
           </div>
            <div class="block w-1/2">
                <label for="lastName">Cena:</label>
                <input type="text" name="lastName" id="lastName" value="" readonly><br>
            </div> 
            <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Opis</label>
            <textarea id="message" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Opisz swój produkt tutaj"></textarea> 
            <form class="">
                <label for="countries" class="block mb-2 mt-2 text-sm font-medium text-gray-900 dark:text-white w-1/2 ">Select an option</label>
                <select id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option selected>Wybierz kategorie</option>
                    <option value="US">United States</option>
                    <option value="CA">Canada</option>
                    <option value="FR">France</option>
                    <option value="DE">Germany</option>
                </select>
                <input type="submit" value="Zaktualizuj dane">
            </form>                     
            
            
        </form>       
   </div>
   <div class="kontener">
   <h2>Twoje aukcje</h2>
        <table>
            <tr>
                <th>Numer aukcji</th>
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