<?php
session_start();
include_once "../scripts/connect.php";

// Sprawdź, czy użytkownik jest zalogowany i czy przesłano ID aukcji
if (!isset($_SESSION['id_user'])) {
    header("location: login.php");
    exit;
}

if (!isset($_GET['id_auction'])) {
    header("location: user_profile_auctions.php");
    exit;
}

$id_auction = (int)$_GET['id_auction'];
$id_user = $_SESSION['id_user'];
$errors = [];
$successMessage = '';

// Pobierz szczegóły aukcji
$sql = "SELECT * FROM auctions WHERE id_auction = :id_auction AND id_user = :id_user";
$stmt = $conn->prepare($sql);
$stmt->execute(['id_auction' => $id_auction, 'id_user' => $id_user]);
$auction = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$auction) {
    echo "Nie znaleziono aukcji lub nie masz uprawnień do jej edycji.";
    exit;
}

// Sprawdź, czy formularz został przesłany, aby zaktualizować dane
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pobierz dane z formularza
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = (int)$_POST['category'];
    
    // Weryfikacja danych
    if (empty($title) || empty($description)) {
        $errors[] = "Tytuł i opis są wymagane.";
    }

    // Obsługa pliku zdjęcia
    if (!empty($_FILES['image']['name'])) {
        $imagePath = '../uploads/' . basename($_FILES['image']['name']);
        
        // Check if the temporary file exists
        if (file_exists($_FILES['image']['tmp_name'])) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/' . $imagePath)) {
                // File moved successfully
            } else {
                $errors[] = "Błąd przesyłania pliku. Spróbuj ponownie.";
            }
        } else {
            $errors[] = "Plik tymczasowy nie istnieje.";
        }
    } else {
        $imagePath = $auction['image'];
    }

    // Aktualizuj aukcję w bazie
    if (empty($errors)) {
        $updateSql = "UPDATE auctions 
                      SET title = :title, description = :description, id_category = :category, image = :image 
                      WHERE id_auction = :id_auction AND id_user = :id_user";
        $stmt = $conn->prepare($updateSql);
        $stmt->execute([
            'title' => $title,
            'description' => $description,
            'category' => $category,
            'image' => $imagePath,
            'id_auction' => $id_auction,
            'id_user' => $id_user
        ]);

        $successMessage = "Aukcja została pomyślnie zaktualizowana.";
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Edytuj Aukcję</title>
    <link rel="stylesheet" href="../src/user_profile2.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
<header class="bg-blue-950">       
    <div class="container w-4/5 m-auto bg-blue-950 flex justify-around p-8">
        <div><a href="loggin.php"><img src="/projekt/projekt/img/BidHub_logo_removebg_minimalized.png" alt="Błąd załadowania zdjęcia" width="150" height="150"></a></div>
        <div class="text-white"><a href="user_profile.php">Moje Dane</a></div>
            <div class="text-white"><a href="user_profile_auctions.php">Twoje Aukcje</a></div>
            <div class="text-white"><a href="my_reviews.php">Twoje opinie</a></div>
            <div class="text-white"><a href="user_profile_fav.php">Obserwowane aukcje</a></div>
            <div class="text-white"><a href="user_win_auction_profile.php">Wygrane aukcje</a></div> 
            <div class="text-white"><a href="user_sold_list.php">Sprzedane</a></div>    
    </div>
</header>

<div class="kontener">
    <h1 class="font-bold text-lg">Edytuj Aukcję</h1>
    <?php if (!empty($errors)) { ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
            <p class="font-bold">Błąd</p>
            <ul>
                <?php foreach ($errors as $error) { ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>

    <?php if (!empty($successMessage)) { ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
            <p class="font-bold">Sukces</p>
            <p><?php echo htmlspecialchars($successMessage); ?></p>
        </div>
    <?php } ?>

    <form class="m-auto w-full max-w-md flex flex-col h-auto" enctype="multipart/form-data" method="POST" action="">
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Tytuł:</label>
            <input type="text" name="title" id="title" class="block w-full p-2 border border-gray-300 rounded-md" value="<?php echo htmlspecialchars($auction['title']); ?>">
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Opis:</label>
            <textarea name="description" id="description" class="block w-full box-border h-52 p-2 border border-gray-300 rounded-md"><?php echo htmlspecialchars($auction['description']); ?></textarea>
        </div>
        <div class="mb-4">
            <label for="category" class="block text-sm font-medium text-gray-700">Wybierz kategorię</label>
            <select id="category" name="category" class="block w-full p-2 border border-gray-300 rounded-md">
                <option value="">Wybierz kategorię</option>
                <?php
                    $stmt = $conn->prepare("SELECT * FROM categories");
                    $stmt->execute();
                    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($categories as $row) {
                        $selected = $auction['id_category'] == $row['id_category'] ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($row['id_category']) . '" ' . $selected . '>' . htmlspecialchars($row['category_name']) . '</option>';
                    }
                ?>
            </select>
        </div>
        <div class="mb-4">
            <img src="<?php echo $auction['image']; ?>" alt="Obraz aukcji" class="mt-4 w-32 h-32">
            <label for="image" class="block text-sm font-medium text-gray-700">Zdjęcie</label>
            <input type="file" name="image" id="image" class="block w-full p-2 border border-gray-300 rounded-md">
        </div>
        <input type="submit" value="Zaktualizuj dane" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 cursor-pointer">
    </form>      
</div>
</body>
</html>