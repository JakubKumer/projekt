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
    $sql = "SELECT auctions.*, categories.category_name 
    FROM auctions 
    JOIN categories ON auctions.id_category = categories.id_category";
    $stmt = $conn->prepare($sql); // Corrected line
    $stmt->execute(); // Corrected line
    $auctions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/auctions_admin.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Document</title>
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
    <h1 class="text-5xl">Panel Administratora</h1>
        <h2 class="font-bold text-xl">Aukcje</h2>
        <?php if (isset($_GET['success'])): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 w-auto m-auto lg:w-1/3" role="alert">
            <p class="font-bold">Sukces</p>
            <p><?php echo htmlspecialchars($_GET['success']); ?></p>
        </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 w-auto m-auto lg:w-1/3" role="alert">
            <p class="font-bold">Błąd</p>
            <p><?php echo htmlspecialchars($_GET['error']); ?></p>
        </div>
        <?php endif; ?>
        <div class="flex justify-end mr-5"><button type="button" class="ml-3 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"><a href="add_admin_auction.php">Dodaj Aukcje</a></button></div>

        <!-- Tabela aukcji z ikonami strzałek do sortowania -->
        <table>
            <tr>
                <th><a href="?sortByAuctionID=<?php echo $sortByAuctionID === 'asc' ? 'desc' : 'asc'; ?>">ID aukcji <?php if ($sortByAuctionID === 'asc') echo "&#8593;"; else echo "&#8595;"; ?></a></th>
                <th><a href="?sortByUserID=<?php echo $sortByUserID === 'asc' ? 'desc' : 'asc'; ?>">ID użytkownika <?php if ($sortByUserID === 'asc') echo "&#8593;"; else echo "&#8595;"; ?></a></th>
                <th>Kategoria</th>
                <th><a href="?sortByTitle=<?php echo $sortByTitle === 'asc' ? 'desc' : 'asc'; ?>">Tytuł <?php if ($sortByTitle === 'asc') echo "&#8593;"; else echo "&#8595;"; ?></a></th>
                <th>Opis</th>
                <th>Zdjęcie</th>
                <th><a href="?sortByStartPrice=<?php echo $sortByStartPrice === 'asc' ? 'desc' : 'asc'; ?>">Cena początkowa <?php if ($sortByStartPrice === 'asc') echo "&#8593;"; else echo "&#8595;"; ?></a></th>
                <th>Kończy się</th>
                <th>Akcje</th>

            </tr>
             <?php foreach ($auctions as $auction): ?>
                <tr>
                <td><?php echo htmlspecialchars($auction['id_auction']); ?></td>
                <td><?php echo htmlspecialchars($auction['id_user']); ?></td>
                <td><?php echo htmlspecialchars($auction['category_name']); ?></td>
                    <td><?php echo htmlspecialchars($auction['title']); ?></td>
                    <td><?php echo htmlspecialchars($auction['description']); ?></td>
                    <td><img src="<?php echo htmlspecialchars($auction['image']); ?>" alt="Zdjęcie produktu" width="100"></td>
                    <td><?php echo htmlspecialchars($auction['start_price']); ?></td>
                    <td><?php echo htmlspecialchars($auction['end_time']); ?></td>
                    <td>                        
                        <span class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20"><a href="edit_auction.php?id_auction=<?php echo htmlspecialchars($auction['id_auction']); ?>">Edytuj</a></span>
                        <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10"><a href="../scripts/delete_auctions_admin.php?id=<?php echo htmlspecialchars($auction['id_auction']); ?>">Usuń</a></span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>