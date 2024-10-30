<?php
session_start();
include_once "../scripts/connect.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$query = "SELECT id_role FROM users WHERE id_user = :id_user";
$stmt = $conn->prepare($query);
$stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);
if ($user && $user['id_role'] != 3) {
    echo "Brak uprawnień do przeglądania tej strony.";
    exit();
} elseif (!$user) {
    header("Location: login.php");
    exit();
}

$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$successMessage = isset($_SESSION['success']) ? $_SESSION['success'] : "";
unset($_SESSION['errors']);
unset($_SESSION['success']);

// Fetch categories from the database
try {
    $categoryQuery = "SELECT * FROM categories";
    $categoryStmt = $conn->prepare($categoryQuery);
    $categoryStmt->execute();
    $categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errors[] = "Błąd podczas pobierania kategorii: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj Aukcje</title>
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
    <h1 class="font-bold text-lg">Dodaj Aukcje</h1>

    <?php if(!empty($errors)){ ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 w-auto m-auto lg:w-2/3" role="alert">
            <p class="font-bold">Błąd</p>
            <ul><?php foreach($errors as $error){ ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php } ?></ul>
        </div>  
    <?php } ?>

    <?php if (!empty($successMessage)) { ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 w-auto m-auto lg:w-2/3" role="alert">
            <p class="font-bold">Sukces</p>
            <p><?php echo htmlspecialchars($successMessage); ?></p>
        </div>
    <?php } ?>

    <form class="m-auto w-1/2 flex-wrap h-45" method="POST" action="../scripts/add_auction_admin.php" enctype="multipart/form-data">
        <div class="block w-1/2 m-auto">
            <label for="productTitle">Nazwa produktu:</label>
            <input type="text" name="productTitle" id="productTitle" class="block p-2.5 w-full bg-gray-50 border border-gray-300 rounded-lg"><br>
        </div>
        <div class="block w-1/2">
            <label for="price">Cena:</label>
            <input type="text" name="price" id="price" class="block p-2.5 w-full bg-gray-50 border border-gray-300 rounded-lg"><br>
        </div> 
        <div class="block w-full">
            <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Opis</label>
            <textarea id="description" name="description" rows="4" class="block p-2.5 w-full bg-gray-50 rounded-lg border border-gray-300" placeholder="Opisz swój produkt tutaj"></textarea>
        </div>

        <div class="block w-1/2 mt-4">
            <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Wybierz kategorię</label>
            <select id="category" name="category" class="bg-gray-50 border border-gray-300 rounded-lg block w-full">
                <option selected>Wybierz kategorię</option>
                <?php foreach ($categories as $row) { ?>
                    <option value="<?php echo htmlspecialchars($row['id_category']); ?>">
                        <?php echo htmlspecialchars($row['category_name']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="block w-1/2 mt-4">
            <label for="end_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Data zakończenia aukcji</label>
            <input type="datetime-local" id="end_time" name="end_time" class="block p-2.5 w-full bg-gray-50 rounded-lg border border-gray-300" required>
        </div>

        <div class="block w-1/2 mt-4">
            <label for="image" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Zdjęcie</label>
            <input type="file" name="image" id="image" class="block p-2.5 w-full bg-gray-50 rounded-lg border border-gray-300"><br>
        </div>

        <div class="block w-full mt-4">
            <input type="submit" name="addAuction" value="Dodaj Aukcje" class="bg-blue-500 text-white p-2.5 rounded-lg">
        </div>
    </form>       
</div>
</body>
</html>