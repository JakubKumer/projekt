<?php
    session_start();
    include_once "../scripts/connect.php";
    if (!isset($_SESSION['id_user'])) {
        // Jeśli użytkownik nie jest zalogowany, przekieruj do formularza logowania
        header("Location: login.php");
        exit(); // Zatrzymanie dalszego ładowania strony
    }
    
    // Pobieranie id_user z sesji
    $id_user = $_SESSION['id_user'];
    
    // Sprawdzenie, czy użytkownik ma przypisaną rolę administratora (id_role == 3)
    $query = "SELECT id_role FROM users WHERE id_user = :id_user";
    $stmt = $conn->prepare($query); // Przygotowanie zapytania SQL
    $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT); // Użycie bindValue zamiast bind_param
    $stmt->execute(); // Wykonanie zapytania
    
    // Sprawdzenie wyniku zapytania
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $id_role = $user['id_role'];
    
        // Sprawdzenie, czy użytkownik jest administratorem (id_role == 3)
        if ($id_role != 3) {
            // Jeśli użytkownik nie jest administratorem, wyświetlamy komunikat o braku uprawnień
            echo "Brak uprawnień do przeglądania tej strony.";
            exit(); // Zatrzymanie dalszego ładowania strony
        }
    } else {
        // Jeśli użytkownik nie został znaleziony w bazie
        header("Location: login.php");
        exit(); // Zatrzymanie dalszego ładowania strony
    }
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
    <h1 class="font-bold text-lg">Dodaj Aukcje</h1>
    <h2>Nowa Aukcja</h2>

    <!-- Obsługa błędów -->
    <?php if(!empty($errors)){ ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 w-auto m-auto lg:w-2/3" role="alert">
            <p class="font-bold">Błąd</p>
            <ul><?php foreach($errors as $error){ ?>
                <li><?php echo $error; ?></li>
            <?php } ?></ul>
        </div>  
    <?php } ?>

    <!-- Obsługa wiadomości sukcesu -->
    <?php if (!empty($successMessage)) { ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 w-auto m-auto lg:w-2/3" role="alert">
            <p class="font-bold">Sukces</p>
            <p><?php echo $successMessage; ?></p>
        </div>
    <?php } ?>

    <form class="m-auto w-1/2 flex-wrap h-45" method="POST" action="../scripts/add_auction_admin.php" enctype="multipart/form-data">
        <div class="block w-1/2 m-auto">
            <label for="productTitle">Nazwa produktu:</label>
            <input type="text" name="productTitle" id="productTitle" value="" class="block p-2.5 w-full bg-gray-50 border border-gray-300 rounded-lg"><br>
        </div>
        <div class="block w-1/2">
            <label for="price">Cena:</label>
            <input type="text" name="price" id="price" value="" class="block p-2.5 w-full bg-gray-50 border border-gray-300 rounded-lg"><br>
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