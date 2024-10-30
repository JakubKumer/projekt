<?php
session_start();
include_once "../scripts/connect.php";
$errors = [];
$successMessage = "";

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

if ($user) {
    $id_role = $user['id_role'];
    if ($id_role != 3) {
        echo "Brak uprawnień do przeglądania tej strony.";
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}


// Proste zapytanie SQL bez JOIN z tabelą categories
$sql = "SELECT * FROM completed_auctions";
$stmt = $conn->prepare($sql);
$stmt->execute();
$completed_auctions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/auctions_admin.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Zakończone Aukcje</title>
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
         <!-- Wyświetlanie alertów sukcesu lub błędu -->
         <?php if(isset($_GET['success'])): ?>
         <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 w-auto m-auto lg:w-1/3" role="alert">
            <p class="font-bold">Sukces</p>
            <p><?php echo htmlspecialchars($_GET['success']); ?></p>
        </div>
        <?php endif; ?>

        <?php if(isset($_GET['error'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 w-auto m-auto lg:w-1/3" role="alert">
                <p class="font-bold">Błąd</p>
                <p><?php echo htmlspecialchars($_GET['error']); ?></p>
            </div>
        <?php endif; ?>
        <h2 class="font-bold text-xl">Zakończone Aukcje</h2>
        <div class="flex justify-end mr-5">
            <form action="../scripts/update_completed_auctions.php" method="">
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Aktualizuj zakończone aukcje</button>
            </form>
        </div>
        <!-- Tabela zakończonych aukcji -->
        <table>
            <tr>
                <th>ID aukcji</th>
                <th>Tytuł</th>
                <th>Opis</th>
                <th>Zdjęcie</th>
                <th>Data zakończenia</th>
                <th>Cena</th>
                <th>Status płatności</th>
                <th>Status dostawy</th>
                <th>Wysłane</th>
                <th>Akcje</th>
            </tr>
            <?php foreach ($completed_auctions as $auction): ?>
                <tr>
                    <td><?php echo htmlspecialchars($auction['id']); ?></td>
                    <td><?php echo htmlspecialchars($auction['title']); ?></td>
                    <td><?php echo htmlspecialchars($auction['description']); ?></td>
                    <td><img src="<?php echo htmlspecialchars($auction['image']); ?>" alt="Zdjęcie produktu" width="100"></td>
                    <td><?php echo htmlspecialchars($auction['end_time']); ?></td>
                    <td><?php echo htmlspecialchars($auction['price']); ?></td>
                    <td><?php echo htmlspecialchars($auction['payment_status']); ?></td>
                    <td><?php echo htmlspecialchars($auction['delivery_status']); ?></td>
                    <td><?php echo htmlspecialchars($auction['is_send']); ?></td>
                    <td>
                        <span class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20"><a href="view_auction_details_admin.php?id=<?php echo htmlspecialchars($auction['id']); ?>">Szczegóły</a></span>
                        <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10"><a href="../scripts/delete_completed_auction.php?id=<?php echo htmlspecialchars($auction['id']); ?>">Usuń</a></span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>