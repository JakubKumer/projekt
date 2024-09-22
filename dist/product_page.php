<?php
    session_start();
    include_once "../scripts/connect.php";
        // Pobieranie kategorii z bazy danych
        $sql = "SELECT id_category, category_name FROM categories";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Pobieranie wartości wyszukiwania i kategorii z URL
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $categoryId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
        // Pobranie wartości sortowania
        $sort = isset($_GET['sort']) ? $_GET['sort'] : '';
    
        // Podstawowe zapytanie SQL do pobrania aukcji
        $sql2 = "SELECT auctions.*, categories.category_name 
                 FROM auctions 
                 JOIN categories ON auctions.id_category = categories.id_category";
    
        // Warunki do zapytania SQL
        $conditions = [];
        $params = [];
    
        // Dodanie warunku wyszukiwania
        if (!empty($search)) {
            $conditions[] = "auctions.title LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }
    
        // Dodanie warunku dla kategorii
        $currentCategoryName = ''; // Domyślna wartość
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
    
        // Dodanie filtrów po cenie
        if (isset($_GET['price_range'])) {
            $priceRange = $_GET['price_range'];
            if ($priceRange == 'below_25') {
                $conditions[] = "auctions.start_price < 25";
            } elseif ($priceRange == '25_to_50') {
                $conditions[] = "auctions.start_price BETWEEN 25 AND 50";
            } elseif ($priceRange == '50_to_75') {
                $conditions[] = "auctions.start_price BETWEEN 50 AND 75";
            } elseif ($priceRange == '75_to_100') {
                $conditions[] = "auctions.start_price BETWEEN 75 AND 100";
            } elseif ($priceRange == 'above_100') {
                $conditions[] = "auctions.start_price > 100";
            }
        }
    
        // Własny przedział cenowy
        if (!empty($_GET['custom_price_min']) || !empty($_GET['custom_price_max'])) {
            $minPrice = $_GET['custom_price_min'] ?? 0;
            $maxPrice = $_GET['custom_price_max'] ?? PHP_INT_MAX;
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
        if (!empty($conditions)) {
            $sql2 .= ' WHERE ' . implode(' AND ', $conditions);
        }
    
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
    
        // Przygotowanie i wykonanie zapytania
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute($params);
        $auctions = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        if (isset($_GET['id_auction'])) {
            $id_auction = (int)$_GET['id_auction'];
            
        
            // Fetch auction details from the database
            $sql = "SELECT auctions.*, categories.category_name 
                    FROM auctions 
                    JOIN categories ON auctions.id_category = categories.id_category
                    WHERE id_auction = :id_auction";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute(['id_auction' => $id_auction]);
            $auction = $stmt->fetch(PDO::FETCH_ASSOC);
            $image_url = isset($auction['image_url']) ? $auction['image_url'] : 'default_image.jpg';
            if (!$auction) {
                echo "Product not found!";
                exit;
            }
        } else {
            echo "Invalid product!";
            exit;
        }
        $errors = [];
        $successMessage = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newBid = isset($_POST['bid_amount']) ? floatval($_POST['bid_amount']) : 0;

            if ($newBid > $auction['start_price']) {
                // Zaktualizuj cenę w bazie danych
                $updateSql = "UPDATE auctions SET start_price = :newBid WHERE id_auction = :id_auction";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->execute([
                    ':newBid' => $newBid,
                    ':id_auction' => $id_auction
                ]);

                // Wyświetlenie informacji o sukcesie
                $successMessage = "Twoje oferta jest aktualnie najwyższa: {$newBid} zł";

                // Odświeżenie danych aukcji
                $auction['start_price'] = $newBid;
            } else {
                // Dodaj błąd, jeśli oferta jest niższa niż aktualna cena
                $errors[] = "Nowa oferta musi być wyższa niż aktualna cena!";
            }
        }
    
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
<div class="bg-white">
  <div class="pt-6">
    <!-- Image gallery -->
    <div class="mx-auto mt-6 max-w-2xl sm:px-6 lg:grid lg:max-w-7xl lg:grid-cols-3 lg:gap-x-8 lg:px-8">
      <div class="hidden lg:grid lg:grid-cols-1 lg:gap-y-8">
        <div class="aspect-h-2 aspect-w-3 overflow-hidden rounded-lg">
            <img src="<?php echo htmlspecialchars($auction['image']); ?>" alt="<?php echo htmlspecialchars($auction['title']); ?>" class="h-full w-full object-cover object-center">
        </div>
      </div>
    </div>
    <!-- Product info -->
    <div class="mx-auto max-w-2xl px-4 pb-16 pt-10 sm:px-6 lg:grid lg:max-w-7xl lg:grid-cols-3 lg:grid-rows-[auto,auto,1fr] lg:gap-x-8 lg:px-8 lg:pb-24 lg:pt-16">
      <div class="lg:col-span-2 lg:border-r lg:border-gray-200 lg:pr-8">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl"><?php echo htmlspecialchars($auction['title']); ?></h1>
      </div>
      <!-- Options -->
      <div class="mt-4 lg:row-span-3 lg:mt-0">
      <?php if (!empty($errors)) { ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 w-auto m-auto mb-4" role="alert">
                <p class="font-bold">Błąd</p>
                <ul>
                    <?php foreach ($errors as $error) { ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>

        <?php if (!empty($successMessage)) { ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 w-auto m-auto mb-4" role="alert">
                <p class="font-bold">Sukces</p>
                <p><?php echo htmlspecialchars($successMessage); ?></p>
            </div>
        <?php } ?>
  
      <h2 class="">Aktualna cena</h2>
        <p class="text-3xl tracking-tight text-gray-900"><?php echo htmlspecialchars($auction['start_price']); ?> zł</p>

        <!-- Formularz licytacji -->
        <form method="POST" action="">
            <label for="bid_amount" class="block text-sm font-medium text-gray-700 mt-2">Podaj swoją cenę</label>
            <input type="number" id="bid_amount" name="bid_amount" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" step="1.00" required>
            
            <button type="submit" class="mt-4 flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-8 py-3 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Licytuj</button>
        </form>
     </div>
      <div class="py-10 lg:col-span-2 lg:col-start-1 lg:border-r lg:border-gray-200 lg:pb-16 lg:pr-8 lg:pt-6">
        <!-- Description and details -->
        <div>
          <h3 class="sr-only">Description</h3>
          <div class="space-y-6">
            <p class="text-base text-gray-900"><?php echo nl2br(htmlspecialchars($auction['description'])); ?></p>
          </div>
        </div>

        <div class="mt-10">
          <h3 class="text-sm font-medium text-gray-900">Highlights</h3>
          <div class="mt-4">
            <ul role="list" class="list-disc space-y-2 pl-4 text-sm">
              <li class="text-gray-400"><span class="text-gray-600">Kategoria produktu: <?php echo htmlspecialchars($auction['category_name']); ?></span></li>
              <li class="text-gray-400"><span class="text-gray-600">Kon: <?php echo htmlspecialchars($auction['end_time']); ?></span></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>