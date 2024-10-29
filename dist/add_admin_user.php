<?php
    session_start();
    include_once "../scripts/connect.php";
    $errors = $_SESSION['errors'] ?? [];
    $successMessage = $_SESSION['successMessage'] ?? '';
    unset($_SESSION['errors'], $_SESSION['successMessage']);
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
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/auctions_admin.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Dane Użytkownika</title>
</head>
<body>
    <div class="sidebar">
        <a href="admin.php"><i class="fa fa-fw fa-user"></i> Użytkownicy</a>
        <a href="auctions_admin.php"><i class="fa fa-clipboard"></i> Aukcje</a>
        <a href="category_admin.php"><i class="fa fa-clipboard"></i> Kategorie</a>
        <a href="finished_auction_admin.php"><i class="fa fa-clipboard"></i> Zakończone Aukcje</a>
        <a href="../scripts/logout.php"><i class="fa fa-clipboard"></i> Wyloguj</a>
    </div>

    <div class="main">
        <h1 class="font-bold text-lg">Nowu użytkownik</h1>
        <?php if (!empty($errors)) { ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 w-1/2 m-auto" role="alert">
                <p class="font-bold">Błąd</p>
                    <ul>
                        <?php foreach ($errors as $error) { echo "<li>" . htmlspecialchars($error) . "</li>"; } ?>
                    </ul>
            </div>
        <?php } ?>
        <?php if (!empty($successMessage)) { ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 w-1/2 m-auto" role="alert">
                <p class="font-bold">Sukces</p>
                <p><?php echo htmlspecialchars($successMessage); ?></p>
            </div>
        <?php } ?>
        <div class="bg-white shadow-lg rounded-lg p-8 max-w-md w-full m-auto mt-10">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Dodaj użytkownika</h2>
            <form action="../scripts/add_user_admin.php" method="POST" class="space-y-4">
                <div>
                    <label for="firstName" class="block text-gray-700 font-medium mb-1">Imię:</label>
                    <input type="text" name="firstName" id="firstName" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="lastName" class="block text-gray-700 font-medium mb-1">Nazwisko:</label>
                    <input type="text" name="lastName" id="lastName" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="email" class="block text-gray-700 font-medium mb-1">Email:</label>
                    <input type="email" name="email" id="email" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="pass" class="block text-gray-700 font-medium mb-1">Hasło:</label>
                    <input type="password" name="password1" id="password1" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="passwordrepeat" class="block text-gray-700 font-medium mb-1">Powtórz hasło:</label>
                    <input type="password" name="password2" id="password2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="pt-4">
                    <input type="submit" value="Dodaj użytkownika" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded-lg transition duration-200">
                </div>
            </form>
        </div>      
    </div>
</body>
</html>