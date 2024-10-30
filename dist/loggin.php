<?php
session_start();
include_once "../scripts/connect.php";
$currentCategoryName = '';
if (isset($_SESSION['id_user']) && isset($_SESSION['email'])) {

    // Pobieranie kategorii z bazy danych
    $stmt = $conn->prepare("SELECT firstName, profile_image FROM users WHERE id_user = :id_user");
    $stmt->execute(['id_user' => $_SESSION['id_user']]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($userData) {
        $_SESSION['firstName'] = $userData['firstName'];
        $_SESSION['profile_image'] = $userData['profile_image'];
    }

    $sql = "SELECT id_category, category_name FROM categories";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Pobieranie wartości wyszukiwania i kategorii z URL
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $categoryId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    // Pobranie wartości sortowania
    $sort = isset($_GET['sort']) ? $_GET['sort'] : '';

    // Liczba wyników na stronę
    $itemsPerPage = 8;

    // Uzyskaj bieżącą stronę z parametru GET (jeśli jest ustawiona), w przeciwnym razie ustaw na 1
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

    // Oblicz offset (początek dla SQL LIMIT)
    $offset = ($page - 1) * $itemsPerPage;

    // Podstawowe zapytanie SQL do pobrania aukcji i liczenia wyników
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

    // Dodanie filtrów po cenie
    if (isset($_GET['price_range'])) {
        $priceRange = $_GET['price_range'];
        switch ($priceRange) {
            case 'below_25':
                $conditions[] = "auctions.start_price < 25";
                break;
            case '25_to_50':
                $conditions[] = "auctions.start_price BETWEEN 25 AND 50";
                break;
            case '50_to_75':
                $conditions[] = "auctions.start_price BETWEEN 50 AND 75";
                break;
            case '75_to_100':
                $conditions[] = "auctions.start_price BETWEEN 75 AND 100";
                break;
            case 'above_100':
                $conditions[] = "auctions.start_price > 100";
                break;
        }
    }

    // Własny przedział cenowy
    if (!empty($_GET['custom_price_min']) || !empty($_GET['custom_price_max'])) {
        $minPrice = !empty($_GET['custom_price_min']) ? (float)$_GET['custom_price_min'] : 0;
        $maxPrice = !empty($_GET['custom_price_max']) ? (float)$_GET['custom_price_max'] : PHP_INT_MAX;
        $conditions[] = "auctions.start_price BETWEEN :minPrice AND :maxPrice";
        $params[':minPrice'] = $minPrice;
        $params[':maxPrice'] = $maxPrice;
    }

    // Dodanie warunku dla daty zakończenia
    if (!empty($_GET['end_date'])) {
        $endDate = $_GET['end_date'];
        $conditions[] = "auctions.end_time <= :endDate";
        $params[':endDate'] = $endDate;
    }

    // Dodanie warunków do zapytania SQL
    $sql2 .= ' WHERE ' . implode(' AND ', $conditions);

    // Dodanie sortowania
    switch ($sort) {
        case 'price_asc':
            $sql2 .= ' ORDER BY auctions.start_price ASC';
            break;
        case 'price_desc':
            $sql2 .= ' ORDER BY auctions.start_price DESC';
            break;
        case 'end_date_asc':
            $sql2 .= ' ORDER BY auctions.end_time ASC';
            break;
        case 'end_date_desc':
            $sql2 .= ' ORDER BY auctions.end_time DESC';
            break;
        default:
            $sql2 .= ' ORDER BY auctions.end_time ASC';
    }

    // Dodanie limitu i offsetu dla paginacji
    $sql2 .= " LIMIT :limit OFFSET :offset";

    // Przygotowanie i wykonanie zapytania
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
    $stmt2->bindValue(':offset', $offset, PDO::PARAM_INT);
    foreach ($params as $key => &$val) {
        $stmt2->bindParam($key, $val);
    }
    if ($categoryId > 0) {
        $stmt->bindValue(':categoryId', $params[':categoryId'], PDO::PARAM_INT);
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
    <title>Document</title>
    <link rel="stylesheet" href="../src/output.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
<header class="bg-blue-950">
    <div class="container w-4/5 m-auto bg-blue-950 flex justify-around p-8">
        <div><a href="loggin.php"><img src="/projekt/projekt/img/BidHub_logo_removebg_minimalized.png" alt="Błąd załadowania zdjęcia" width="150" height="150"></a></div>

        <!-- Formularz wyszukiwania -->
        <form method="GET" action="loggin.php" class="inline-block">
            <input type="text" name="search" class="bg-slate-400 text-black rounded-md" placeholder="Wyszukaj przedmiot..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded-md">Szukaj</button>
        </form>

        <!-- Formularz do sortowania -->
        <form method="GET" action="loggin.php" class="inline-block ml-4">
            <select name="sort" class="bg-slate-400 text-black rounded-md" onchange="this.form.submit()">
                <option value="">Sortuj według</option>
                <option value="price_asc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'price_asc' ? 'selected' : ''; ?>>Cena rosnąco</option>
                <option value="price_desc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'price_desc' ? 'selected' : ''; ?>>Cena malejąco</option>
                <option value="end_date_asc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'end_date_asc' ? 'selected' : ''; ?>>Data zakończenia (najbliżej)</option>
                <option value="end_date_desc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'end_date_desc' ? 'selected' : ''; ?>>Data zakończenia (najdalej)</option>
            </select>
            <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($categoryId); ?>">
        </form>
        
        <div id="dropdownButton" class="text-black font-bold">
            <div onclick="myDropdown()" class="block w-12 h-12 rounded-full overflow-hidden border-2 border-blue-300 focus:outline-none focus:border-white">
                <img class="h-full w-full object-cover" src="<?php echo !empty($_SESSION['profile_image']) ? htmlspecialchars($_SESSION['profile_image']) : 'uploads/avatar-2388584_1280.png'; ?>" alt="">
            </div>
            <div id="dropdown" class="absolute bg-blue-300 rounded-lg p-2 mt-1 hidden w-40">
                <p class="block px-4 py-2 text-gray-800 hover:bg-indigo-500 hover:text-white">Witaj <?=$_SESSION['firstName']?></p>
                <a class="block px-4 py-2 text-gray-800 hover:bg-indigo-500 hover:text-white" href="user_profile.php">Mój profil</a>
                <a class="block px-4 py-2 text-gray-800 hover:bg-indigo-500 hover:text-white" href="../scripts/logout.php">Wyloguj</a>
            </div>
        </div>
    </div>
</header>
<main class="container w-4/5 m-auto flex">
    
<aside class="bg-gray-200 w-1/4 mt-20 mr-5 h-auto">
    <h2 class="font-bold text-center">Kategorie</h2>
    <ul class="text-center p-3">
        <li class="m-1 hover:underline hover:text-blue-900 <?php echo !isset($categoryId) ? 'font-bold text-blue-600' : ''; ?>">
            <a href="loggin.php">
                Wszystko
            </a>
        </li>
        <?php foreach ($categories as $category): ?>
            <li class="m-1 hover:underline hover:text-blue-900 <?php echo ($category['id_category'] == $categoryId) ? 'font-bold text-blue-600' : ''; ?>">
                <a href="loggin.php?id=<?php echo htmlspecialchars($category['id_category']); ?>&search=<?php echo htmlspecialchars($search); ?>">
                    <?php echo htmlspecialchars($category['category_name']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Filtry -->
    <div class="bg-gray-300 p-4 mt-4 rounded-lg m-auto">
        <h2 class="font-bold">Filtry</h2>
        <form method="GET" action="loggin.php">
            <div class="m-auto">
                <label>Cena (zł)</label><br>
                <label><input class="m-3 " type="radio" name="price_range" value="below_25" <?php echo (isset($_GET['price_range']) && $_GET['price_range'] == 'below_25') ? 'checked' : ''; ?>> poniżej 25 zł</label><br>
                <label><input class="m-3 " type="radio" name="price_range" value="25_to_50" <?php echo (isset($_GET['price_range']) && $_GET['price_range'] == '25_to_50') ? 'checked' : ''; ?>> 25 zł do 50 zł</label><br>
                <label><input class="m-3 " type="radio" name="price_range" value="50_to_75" <?php echo (isset($_GET['price_range']) && $_GET['price_range'] == '50_to_75') ? 'checked' : ''; ?>> 50 zł do 75 zł</label><br>
                <label><input class="m-3 " type="radio" name="price_range" value="75_to_100" <?php echo (isset($_GET['price_range']) && $_GET['price_range'] == '75_to_100') ? 'checked' : ''; ?>> 75 zł do 100 zł</label><br>
                <label><input class="m-3 " type="radio" name="price_range" value="above_100" <?php echo (isset($_GET['price_range']) && $_GET['price_range'] == 'above_100') ? 'checked' : ''; ?>> powyżej 100 zł</label><br>
                <label><input class="m-3 rounded border border-gray-400 hover:border-blue-400 w-3/4" type="number" name="custom_price_min" placeholder="Min" class="border rounded" value="<?php echo isset($_GET['custom_price_min']) ? htmlspecialchars($_GET['custom_price_min']) : ''; ?>" /></label>
                <label><input class="m-3 rounded border border-gray-400 hover:border-blue-400 w-3/4" type="number" name="custom_price_max" placeholder="Max" class="border rounded" value="<?php echo isset($_GET['custom_price_max']) ? htmlspecialchars($_GET['custom_price_max']) : ''; ?>" /></label>
            </div>

            <div class="my-2">
                <label>Data zakończenia do</label>
                <input type="date" name="end_date" class="border rounded" />
            </div>

            <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded-md">Zastosuj filtry</button>
            <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($categoryId); ?>">
            <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>">
        </form>
    </div>
</aside>

<div class="w-3/4 mt-20">
    <ul>
        <?php if (!empty($auctions)): ?>
            <?php foreach ($auctions as $auction): ?>
                <li class="bg-gray-200 m-1 h-auto rounded-lg hover:shadow-blue-900 hover:shadow-lg hover:bg-slate-300">
                    <a href="product_page.php?id_auction=<?php echo htmlspecialchars($auction['id_auction']); ?>">
                        <div class="flex justify-between p-2">
                            <img class="w-20 h-20 object-cover rounded-lg" src="<?php echo htmlspecialchars($auction['image']); ?>" alt="<?php echo htmlspecialchars($auction['title']); ?>">
                            <p class="w-1/3 ml-5"><?php echo htmlspecialchars($auction['title']); ?></p>
                            <p class="w-1/3">cena: <?php echo htmlspecialchars($auction['start_price']); ?> zł</p>
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
                    <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&id=<?php echo $categoryId; ?>&price_range=<?php echo isset($_GET['price_range']) ? htmlspecialchars($_GET['price_range']) : ''; ?>&custom_price_min=<?php echo isset($_GET['custom_price_min']) ? htmlspecialchars($_GET['custom_price_min']) : ''; ?>&custom_price_max=<?php echo isset($_GET['custom_price_max']) ? htmlspecialchars($_GET['custom_price_max']) : ''; ?>&end_date=<?php echo isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : ''; ?>&sort=<?php echo htmlspecialchars($sort); ?>" 
                    class="block px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700">
                        Poprzednia
                    </a>
                </li>
            <?php else: ?>
                <li>
                    <span class="block px-3 py-2 ml-0 leading-tight text-gray-400 bg-gray-200 border border-gray-300 rounded-l-lg">Poprzednia</span>
                </li>
            <?php endif; ?>

            <!-- Numery stron -->
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li>
                    <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&id=<?php echo $categoryId; ?>&price_range=<?php echo isset($_GET['price_range']) ? htmlspecialchars($_GET['price_range']) : ''; ?>&custom_price_min=<?php echo isset($_GET['custom_price_min']) ? htmlspecialchars($_GET['custom_price_min']) : ''; ?>&custom_price_max=<?php echo isset($_GET['custom_price_max']) ? htmlspecialchars($_GET['custom_price_max']) : ''; ?>&end_date=<?php echo isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : ''; ?>&sort=<?php echo htmlspecialchars($sort); ?>" 
                    class="block px-3 py-2 leading-tight <?php echo $i == $page ? 'bg-indigo-600 text-white' : 'text-gray-500 bg-white'; ?> border border-gray-300 hover:bg-gray-100 hover:text-gray-700">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>

            <!-- Przycisk następnej strony -->
            <?php if ($page < $totalPages): ?>
                <li>
                    <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&id=<?php echo $categoryId; ?>&price_range=<?php echo isset($_GET['price_range']) ? htmlspecialchars($_GET['price_range']) : ''; ?>&custom_price_min=<?php echo isset($_GET['custom_price_min']) ? htmlspecialchars($_GET['custom_price_min']) : ''; ?>&custom_price_max=<?php echo isset($_GET['custom_price_max']) ? htmlspecialchars($_GET['custom_price_max']) : ''; ?>&end_date=<?php echo isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : ''; ?>&sort=<?php echo htmlspecialchars($sort); ?>" 
                    class="block px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700">
                        Następna
                    </a>
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
<?php
} else {
    header("Location: loggin.php");
}
?>
