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
                <input type="text" name="firstName" id="firstName" value="" ><br>
           </div>
            <div class="block w-1/2">
                <label for="lastName">Cena:</label>
                <input type="text" name="lastName" id="lastName" value="" ><br>
            </div> 
            <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Opis</label>
            <textarea id="message" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Opisz swój produkt tutaj"></textarea> 
            <form class="">
                <label for="countries" class="block mb-2 mt-2 text-sm font-medium text-gray-900 dark:text-white w-1/2 ">Select an option</label>
                <select id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option selected>Wybierz kategorie</option>
                    <?php
                         $stmt = $conn->prepare("SELECT * FROM categories");
                         $stmt->execute();
                         $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                         if (count($categories) > 0) {
                            foreach ($categories as $row) {
                                echo '<option value="' . htmlspecialchars($row['id_category']) . '">' . htmlspecialchars($row['category_name']) . '</option>'; 
                            }
                        } else {
                            echo "<tr><td colspan='5'>Brak kategorii.</td></tr>";
                        }
                    ?>
                </select>
                <div class="flex items-center justify-center w-full">
    <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
        <div class="flex flex-col items-center justify-center pt-5 pb-6">
            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
            </svg>
            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
        </div>
        <input id="dropzone-file" type="file" class="hidden" />
    </label>
</div> 
                <input class="mt-2" type="submit" value="Zaktualizuj dane">
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