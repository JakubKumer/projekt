<?php
session_start();
include_once "../scripts/connect.php";

// Pobieranie kategorii z bazy danych
$sql = "SELECT id_category, category_name FROM categories";
$stmt = $conn->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Pobranie wartości wyszukiwania i kategorii z URL
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$categoryId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Liczba wyników na stronę
$itemsPerPage = 8;

// Uzyskaj bieżącą stronę z parametru GET (jeśli jest ustawiona), w przeciwnym razie ustaw na 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

// Oblicz offset (początek dla SQL LIMIT)
$offset = ($page - 1) * $itemsPerPage;

// Podstawowe zapytanie SQL do pobrania aukcji
$sql2 = "SELECT SQL_CALC_FOUND_ROWS auctions.*, categories.category_name 
         FROM auctions 
         JOIN categories ON auctions.id_category = categories.id_category";

// Warunki do zapytania SQL
$conditions = ["auctions.status = 'active'", "auctions.end_time > NOW()"];
$params = [];

// Dodanie warunku wyszukiwania
if (!empty($search)) {
    $conditions[] = "auctions.title LIKE :search";
    $params[':search'] = '%' . $search . '%';
}

// Dodanie warunku dla kategorii
if ($categoryId > 0) {
    $conditions[] = "auctions.id_category = :categoryId";
    $params[':categoryId'] = $categoryId;
}

// Dodanie warunków do zapytania SQL
$sql2 .= ' WHERE ' . implode(' AND ', $conditions);

// Dodanie limitu i offsetu dla paginacji
$sql2 .= " LIMIT :limit OFFSET :offset";

// Przygotowanie i wykonanie zapytania
$stmt2 = $conn->prepare($sql2);
$stmt2->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
$stmt2->bindValue(':offset', $offset, PDO::PARAM_INT);
foreach ($params as $key => &$val) {
    $stmt2->bindParam($key, $val);
}
$stmt2->execute();
$auctions = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// Pobranie łącznej liczby wyników (dla paginacji)
$totalAuctionsStmt = $conn->query("SELECT FOUND_ROWS() as total");
$totalAuctions = $totalAuctionsStmt->fetch(PDO::FETCH_ASSOC)['total'];

// Oblicz łączną liczbę stron
$totalPages = ceil($totalAuctions / $itemsPerPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produkty</title>
    <link rel="stylesheet" href="../src/output.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
<header class="bg-blue-950">
    <div class="container w-4/5 m-auto bg-blue-950 flex justify-between p-8">
        <div><a href="index.php"><img src="/projekt/projekt/img/BidHub_logo_removebg_minimalized.png" alt="Logo" width="150" height="150"></a></div>

        <!-- Formularz wyszukiwania -->
            <form method="GET" action="index.php" class="flex">
                <input type="text" name="search" class="bg-slate-400 text-black rounded-md mr-2" placeholder="Wyszukaj przedmiot..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded-md">Szukaj</button>
            </form>
            <div id="dropdownButton" class=" text-black font-bold">
                <div onclick="myDropdown()" class="block w-12 h-12 rounded-full overflow-hidden border-2 border-blue-300 focus:outline-none focus:border-white">
                    <img class="h-full w-full object-cover" src="https://cdn.pixabay.com/photo/2017/03/04/20/50/pale-2116960_640.jpg" alt="">
                </div>
                <div id="dropdown" class="absolute bg-blue-300 rounded-lg p-2 mt-1 hidden w-40">
                    <a class="block px-4 py-2 text-gray-800 hover:bg-indigo-500 hover:text-white" href="login.php">Zaloguj się</a>
                    <a class="block px-4 py-2 text-gray-800 hover:bg-indigo-500 hover:text-white" href="register.php">Utwórz konto</a>          
                </div>
            </div>
        </div>
    </div>
</header>
<main class="container w-4/5 m-auto flex">
    <aside class="bg-gray-200 w-1/4 mt-20 mr-5 h-auto">
        <h2 class="font-bold text-center">Kategorie</h2>
        <ul class="text-center p-3">
            <li class="m-1 hover:underline hover:text-blue-900 <?php echo !isset($categoryId) ? 'font-bold text-blue-600' : ''; ?>">
                <a href="index.php">Wszystko</a>
            </li>
            <?php foreach ($categories as $category): ?>
                <li class="m-1 hover:underline hover:text-blue-900 <?php echo ($category['id_category'] == $categoryId) ? 'font-bold text-blue-600' : ''; ?>">
                    <a href="index.php?id=<?php echo htmlspecialchars($category['id_category']); ?>&search=<?php echo htmlspecialchars($search); ?>">
                        <?php echo htmlspecialchars($category['category_name']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </aside>

    <div class="w-3/4 mt-20">
        <ul>
            <?php if (!empty($auctions)): ?>
                <?php foreach ($auctions as $auction): ?>
                    <li class="bg-gray-200 m-1 h-auto rounded-lg hover:shadow-lg hover:bg-slate-300">
                        <a href="product_page_unverify.php?id_auction=<?php echo htmlspecialchars($auction['id_auction']); ?>">
                            <div class="flex justify-between p-2">
                                <img class="w-20 h-20 object-cover rounded-lg" src="<?php echo htmlspecialchars($auction['image']); ?>" alt="<?php echo htmlspecialchars($auction['title']); ?>">
                                <p class="w-1/3 ml-5"><?php echo htmlspecialchars($auction['title']); ?></p>
                                <p class="w-1/3">Cena: <?php echo htmlspecialchars($auction['start_price']); ?> zł</p>
                                <p class="w-1/4">Zakończenie: <?php echo htmlspecialchars(date('d-m-Y H:i', strtotime($auction['end_time']))); ?></p>
                            </div>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Brak wyników wyszukiwania dla tej kategorii</p>
            <?php endif; ?>
        </ul>

        <!-- Paginacja -->
        <?php if ($totalPages > 1): ?>
        <nav class="flex justify-center mt-8">
            <ul class="inline-flex items-center -space-x-px">
                <!-- Przycisk poprzedniej strony -->
                <?php if ($page > 1): ?>
                    <li>
                        <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&id=<?php echo $categoryId; ?>" 
                        class="block px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700">Poprzednia</a>
                    </li>
                <?php else: ?>
                    <li>
                        <span class="block px-3 py-2 ml-0 leading-tight text-gray-400 bg-gray-200 border border-gray-300 rounded-l-lg">Poprzednia</span>
                    </li>
                <?php endif; ?>

                <!-- Numery stron -->
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li>
                        <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&id=<?php echo $categoryId; ?>" 
                        class="block px-3 py-2 leading-tight <?php echo $i == $page ? 'bg-indigo-600 text-white' : 'text-gray-500 bg-white'; ?> border border-gray-300 hover:bg-gray-100 hover:text-gray-700">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <!-- Przycisk następnej strony -->
                <?php if ($page < $totalPages): ?>
                    <li>
                        <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&id=<?php echo $categoryId; ?>" 
                        class="block px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700">Następna</a>
                    </li>
                <?php else: ?>
                    <li>
                        <span class="block px-3 py-2 leading-tight text-gray-400 bg-gray-200 border border-gray-300 rounded-r-lg">Następna</span>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</main>
<script src="../js/dropdown.js"></script>
</body>
</html>