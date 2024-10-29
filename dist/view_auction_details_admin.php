<?php
session_start();
include_once "../scripts/connect.php";

// Sprawdzenie uprawnień administratora
if (!isset($_SESSION['id_user']) || $_SESSION['id_role'] != 3) {
    header("Location: ../admin.php?error=Brak+uprawnień");
    exit();
}

// Sprawdzenie, czy przekazano ID aukcji
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: finished_auction_admin.php?error=Nieprawidłowy+ID+aukcji");
    exit();
}

$auction_id = (int)$_GET['id'];

try {
    // Pobieranie id_user na podstawie id aukcji
    $stmtAuction = $conn->prepare("SELECT id_user FROM completed_auctions WHERE id = :id");
    $stmtAuction->bindValue(':id', $auction_id, PDO::PARAM_INT);
    $stmtAuction->execute();
    $auction = $stmtAuction->fetch(PDO::FETCH_ASSOC);

    if (!$auction) {
        header("Location: finished_auction_admin.php?error=Aukcja+nie+znaleziona");
        exit();
    }

    // Pobranie danych użytkownika na podstawie id_user
    $user_id = $auction['id_user'];
    $stmtUser = $conn->prepare("SELECT * FROM users WHERE id_user = :id_user");
    $stmtUser->bindValue(':id_user', $user_id, PDO::PARAM_INT);
    $stmtUser->execute();
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        header("Location: finished_auction_admin.php?error=Użytkownik+nie+znaleziony");
        exit();
    }
} catch (Exception $e) {
    header("Location: finished_auction_admin.php?error=Wystąpił+błąd+" . urlencode($e->getMessage()));
    exit();
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
        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Dane do przelewu</h1>
            <div class="mt-6 border-t border-gray-300">
                <dl class="divide-y divide-gray-300">
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">ID Użytkownika</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"><?php echo htmlspecialchars($user['id_user']); ?></dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Imię</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"><?php echo htmlspecialchars($user['firstName']); ?></dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Nazwisko</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"><?php echo htmlspecialchars($user['lastName']); ?></dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Email</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"><?php echo htmlspecialchars($user['email']); ?></dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Telefon</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"><?php echo htmlspecialchars($user['phone_number']); ?></dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Nr Konta Bankowego</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"><?php echo htmlspecialchars($user['bank_account_number']); ?></dd>
                    </div>
                </dl>
            </div>
            <div class="flex justify-end">
                <a href="finished_auction_admin.php" class="mt-4 inline-block bg-blue-500 text-white font-medium py-2 px-4 rounded hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 ">Powrót do zakończonych aukcji</a>
            </div>
            
        </div>
    </div>
</body>
</html>