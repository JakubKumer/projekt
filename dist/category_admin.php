<?php
    session_start();
    include_once "../scripts/connect.php"; 
    $errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
    $successMessage = isset($_SESSION['success']) ? $_SESSION['success'] : "";
    $deleteMessage = isset($_SESSION['Delete']) ? $_SESSION['Delete'] : "";
    unset($_SESSION['errors']);
    unset($_SESSION['success']);  
    unset($_SESSION['Delete']);  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../src/category.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
<div class="sidebar">
        <a href="admin.php"><i class="fa fa-fw fa-user"></i> Użytkownicy</a>
        <a href="auctions_admin.php"><i class="fa fa-clipboard"></i> Aukcje</a>
        <a href="category_admin.php"><i class="fa fa-clipboard"></i> Kategorie</a>
    </div>
    <div class="main">
        <h1 class="text-5xl">Panel Administratora</h1>
        <?php if(!empty($errors)){?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 w-auto m-auto mt-2 lg:w-2/4" role="alert">
                <p class="font-bold">Błąd</p>
                <ul><?php foreach($errors as $error){?>
                   <li> <?php echo $error; ?> <?php } ?></li>
                </ul>
            </div>  
       <?php }
        ?>
        <?php if (!empty($successMessage)) { ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 w-auto m-auto mt-2  lg:w-2/4" role="alert">
                <p class="font-bold">Sukces</p>
                <p><?php echo $successMessage; ?></p>
            </div>
        <?php } ?>
        <?php if (!empty($deleteMessage)) { ?>
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 w-auto m-auto mt-2 lg:w-2/4" role="alert">
                <p class="font-bold">Uwaga</p>
                <p><?php echo $deleteMessage; ?></p>
            </div>
        <?php } ?>
        
        
         <!-- Formularz dodawania użytkownika -->
        <h2 class="mt-2 ">Dodaj Kategorie</h2>
        <form action="../scripts/add_category.php" method="POST">
            <div class="add_wyglad">
                <label for="firstName">Nazwa kategorii:</label>
                <input type="text" name="categoryName" id="categoryName" required><br>
            </div>
            <div class="add_wyglad">
                <input class="add_button" type="submit" value="Dodaj kategorie">
            </div>
        </form>

        <h2 id="uzytkownicy" class="mt-2 ">kategorie</h2>

        <!-- Tabela użytkowników -->
        <table>
            <tr>
                <th>numer kategorii</th>
                <th>Nazwa</th>
                <th>Akcje</th>
            </tr>
        <?php
            // Pobieranie użytkowników z bazy danych
            $stmt = $conn->prepare("SELECT * FROM categories");
            $stmt->execute();
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($categories) > 0) {
                foreach ($categories as $row) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["id_category"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["category_name"]) . "</td>";
                    echo "<td><a href='../scripts/delete_category.php?id=" . htmlspecialchars($row["id_category"]) . "'>Usuń</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Brak kategorii.</td></tr>";
            }
        ?>
        </table>

       
    </div>
</body>
</html>