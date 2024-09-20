<?php
session_start();
include_once "../scripts/connect.php";

if (isset($_SESSION['id_user']) && isset($_SESSION['email'])) {

    // Pobieranie kategorii z bazy danych
    $sql = "SELECT id_category, category_name FROM categories";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Pobieranie wartości wyszukiwania i kategorii z URL
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $categoryId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    // Nazwa aktualnej kategorii
    $currentCategoryName = '';

    // Podstawowe zapytanie SQL do pobrania aukcji
    $sql2 = "SELECT auctions.*, categories.category_name 
             FROM auctions 
             JOIN categories ON auctions.id_category = categories.id_category";

    // Warunki do zapytania SQL
    $conditions = [];
    $params = [];

    // Dodanie warunku wyszukiwania, jeśli podano tekst do wyszukiwania
    if (!empty($search)) {
        $conditions[] = "auctions.title LIKE :search";
        $params[':search'] = '%' . $search . '%';
    }

    // Dodanie warunku dla kategorii, jeśli wybrano kategorię
    if ($categoryId > 0) {
        $conditions[] = "auctions.id_category = :categoryId";
        $params[':categoryId'] = $categoryId;

        // Pobranie nazwy kategorii
        $categorySql = "SELECT category_name FROM categories WHERE id_category = :categoryId";
        $categoryStmt = $conn->prepare($categorySql);
        $categoryStmt->execute([':categoryId' => $categoryId]);
        $currentCategory = $categoryStmt->fetch(PDO::FETCH_ASSOC);
        $currentCategoryName = $currentCategory ? $currentCategory['category_name'] : '';
    }

    // Dodanie warunków do zapytania SQL, jeśli istnieją
    if (!empty($conditions)) {
        $sql2 .= ' WHERE ' . implode(' AND ', $conditions);
    }

    $stmt2 = $conn->prepare($sql2);
    $stmt2->execute($params);
    $auctions = $stmt2->fetchAll(PDO::FETCH_ASSOC);
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

        <div id="dropdownButton" class="text-black font-bold">
            <div onclick="myDropdown()" class="block w-12 h-12 rounded-full overflow-hidden border-2 border-blue-300 focus:outline-none focus:border-white">
                <img class="h-full w-full object-cover" src="https://cdn.pixabay.com/photo/2017/03/04/20/50/pale-2116960_640.jpg" alt="">
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
</aside>

    <div class="w-3/4 mt-20">
        <ul>
            <?php if (!empty($auctions)): ?>
                <?php foreach ($auctions as $auction): ?>
                    <li class="bg-gray-200 m-1 h-auto rounded-lg hover:shadow-blue-900 hover:shadow-lg hover:bg-slate-300">
                        <a href="#">
                            <div class="flex justify-between p-2">
                                <img class="w-20 h-20 object-cover rounded-lg" src="<?php echo htmlspecialchars($auction['image']); ?>" alt="">
                                <p class="w-1/3 ml-5"><?php echo htmlspecialchars($auction['title']); ?></p>
                                <p class="w-1/3">cena: <?php echo htmlspecialchars($auction['start_price']); ?> zł</p>
                            </div>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Brak wyników wyszukiwania dla: "<?php echo !empty($search) ? htmlspecialchars($search) : htmlspecialchars($currentCategoryName); ?>"</p>
            <?php endif; ?>
        </ul>
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
