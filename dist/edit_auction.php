<?php 
session_start();
include_once "../scripts/connect.php";

$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$successMessage = isset($_SESSION['success']) ? $_SESSION['success'] : "";
unset($_SESSION['errors']);
unset($_SESSION['success']);

// Sprawdź, czy użytkownik jest zalogowany oraz czy ma uprawnienia administratora
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];

// Sprawdzenie uprawnień administratora
$query = "SELECT id_role FROM users WHERE id_user = :id_user";
$stmt = $conn->prepare($query);
$stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if ($user && $user['id_role'] != 3) {
    echo "Brak uprawnień do przeglądania tej strony.";
    exit();
}

$id_auction = isset($_GET['id_auction']) ? (int)$_GET['id_auction'] : 0;
$errors = [];
$successMessage = '';

try {
    // Pobierz dane aukcji
    $sql = "SELECT * FROM auctions WHERE id_auction = :id_auction";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_auction', $id_auction, PDO::PARAM_INT);
    $stmt->execute();
    $auction = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$auction) {
        echo "Aukcja o podanym ID nie istnieje.";
        exit;
    }

    // Pobierz listę kategorii
    $categoriesQuery = "SELECT id_category, category_name FROM categories";
    $categoriesStmt = $conn->prepare($categoriesQuery);
    $categoriesStmt->execute();
    $categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Błąd bazy danych: " . $e->getMessage();
    exit;
}

// Obsługa formularza aktualizacji aukcji
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $category = (int)$_POST['category'] ?? 0;

    if (empty($title) || empty($description)) {
        $errors[] = "Tytuł i opis są wymagane.";
    }

    if (!empty($_FILES['image']['name'])) {
        $imagePath = '../uploads/' . basename($_FILES['image']['name']);
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            $errors[] = "Błąd przesyłania pliku.";
        }
    } else {
        $imagePath = $auction['image'];
    }

    // Aktualizacja aukcji, jeśli brak błędów
    if (empty($errors)) {
        try {
            $updateSql = "UPDATE auctions 
                          SET title = :title, description = :description, id_category = :category, image = :image 
                          WHERE id_auction = :id_auction";
            $stmt = $conn->prepare($updateSql);
            $stmt->execute([
                ':title' => $title,
                ':description' => $description,
                ':category' => $category,
                ':image' => $imagePath,
                ':id_auction' => $id_auction
            ]);
            $successMessage = "Aukcja została pomyślnie zaktualizowana.";
        } catch (PDOException $e) {
            $errors[] = "Błąd przy aktualizacji aukcji: " . $e->getMessage();
        }
    }
}
    
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
        <a href="finished_auction_admin.php"><i class="fa fa-clipboard"></i> Zakończone aukcje</a>
        <a href="admin_reviews.php"><i class="fa fa-clipboard"></i> Opinie</a>
        <a href="../scripts/logout.php"><i class="fa fa-clipboard"></i> Wyloguj</a>
    </div>
    <div class="main">
    <h1 class="text-3xl mb-5"> Aukcji</h1>
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
    <form class="m-auto w-full max-w-md flex flex-col h-auto" action="" method="post" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-lg">
        <div class="mb-4">
            <label class="block text-gray-700">Tytuł:</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($auction['title']); ?>" class="border border-gray-300 p-2 w-full rounded-lg">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Opis:</label>
            <textarea name="description" class="border border-gray-300 p-2 w-full rounded-lg"><?php echo htmlspecialchars($auction['description']); ?></textarea>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Kategoria:</label>
            <select name="category" class="border border-gray-300 p-2 w-full rounded-lg">
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id_category']; ?>" <?php if ($auction['id_category'] == $cat['id_category']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($cat['category_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Zdjęcie:</label>
            <input type="file" name="image" class="border border-gray-300 p-2 w-full rounded-lg">
            <img src="<?php echo $auction['image']; ?>" alt="Obraz aukcji" class="mt-4 w-32 h-32">
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Zapisz zmiany</button>
    </form>
       
    </div>
</body>
</html>