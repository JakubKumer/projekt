<?php
    session_start();
    include_once "../scripts/connect.php";
    include_once "../scripts/filtr_auctions_admin.php";
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
    <title>Document</title>
    <link rel="stylesheet" href="../src/admin.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
<div class="sidebar">
        <a href="admin.php"><i class="fa fa-fw fa-user"></i> Użytkownicy</a>
        <a href="auctions_admin.php"><i class="fa fa-clipboard"></i> Aukcje</a>
        <a href="category_admin.php"><i class="fa fa-clipboard"></i> Kategorie</a>
    </div>
    <div class="kontener">
    <h1 class="font-bold text-4xl">Dodaj Aukcje</h1>
        <h2 class="mt-5 font-bold text-xl">Nowa Aukcja</h2>
        <?php if(!empty($errors)){?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 w-auto m-auto lg:w-2/3" role="alert">
                <p class="font-bold">Błąd</p>
                <ul><?php foreach($errors as $error){?>
                   <li> <?php echo $error; ?> <?php } ?></li>
                </ul>
            </div>  
       <?php }
        ?>
        <?php if (!empty($successMessage)) { ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 w-auto m-auto lg:w-2/3" role="alert">
                <p class="font-bold">Sukces</p>
                <p><?php echo $successMessage; ?></p>
            </div>
        <?php } ?>
        <form class="m-auto mt-5 w-1/2 flex-wrap h-45" method="POST" action="../scripts/add_auction_admin.php" enctype="multipart/form-data">
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
</body>
</html>