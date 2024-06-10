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
        </div>
    </header>
    <div class="kontener">
    <h1 class="font-bold text-lg">Dodaj Aukcje</h1>
        <h2>Nowa Aukcja</h2>
        <?php if(!empty($errors)){?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 w-auto m-auto lg:w-1/3" role="alert">
                <p class="font-bold">Błąd</p>
                <ul><?php foreach($errors as $error){?>
                   <li> <?php echo $error; ?> <?php } ?></li>
                </ul>
            </div>  
       <?php }
        ?>
        <?php if (!empty($successMessage)) { ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 w-auto m-auto lg:w-1/3" role="alert">
                <p class="font-bold">Sukces</p>
                <p><?php echo $successMessage; ?></p>
            </div>
        <?php } ?>
        <form class="m-auto w-1/2 flex-wrap h-45" method="POST" action="../scripts/add_auction.php" enctype="multipart/form-data">
           <div class="block w-1/2 m-auto">
                <label for="productTitle">Nazwa produktu:</label>
                <input type="text" name="productTitle" id="productTitle" value="" ><br>
           </div>
            <div class="block w-1/2">
                <label for="price">Cena:</label>
                <input type="text" name="price" id="price" value="" ><br>
            </div> 
            <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Opis</label>
            <textarea id="description" name="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Opisz swój produkt tutaj"></textarea> 
            
            <label for="category" class="block mb-2 mt-2 text-sm font-medium text-gray-900 dark:text-white w-1/2 ">Select an option</label>
            <select id="category" name="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
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
            <div class="block w-1/2 mt-2">
                <label for="">Zdjęcie</label>
                <input type="file" name="image" value="" ><br>
            </div> 
            <input class="mt-2" type="submit" name="addAuction" value="Dodaj Aukcje">           
        </form>       
   </div>
   <div class="kontener">
   <h2>Twoje aukcje</h2>
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
                        <a href="edit_auction.php?id=<?php echo htmlspecialchars($auction['id_auction']); ?>">Edytuj</a> | 
                        <a href="delete_auction.php?id=<?php echo htmlspecialchars($auction['id_auction']); ?>">Usuń</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
   </div>
</body>
</html>