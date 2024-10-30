<?php
session_start();
include_once "../scripts/connect.php";

// Check if id_auction is set in GET parameters
if (!isset($_GET['id_auction'])) {
    echo "Invalid product!";
    exit;
}

$id_auction = (int)$_GET['id_auction'];

// Fetch auction details and related information
$sql = "SELECT a.*, c.category_name, 
        u.firstName AS owner_first_name, u.lastName AS owner_last_name,
        hb.firstName AS highest_bidder_first_name, hb.lastName AS highest_bidder_last_name
        FROM auctions a
        JOIN categories c ON a.id_category = c.id_category
        JOIN users u ON a.id_user = u.id_user
        LEFT JOIN users hb ON a.highest_bidder_id = hb.id_user
        WHERE a.id_auction = :id_auction";

$stmt = $conn->prepare($sql);
$stmt->execute(['id_auction' => $id_auction]);
$auction = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$auction) {
    echo "Product not found!";
    exit;
}

// Check if user has already favorited this auction
$currentUserId = $_SESSION['id_user'];
$favSql = "SELECT * FROM favorites WHERE id_user = :id_user AND id_auction = :id_auction";
$favStmt = $conn->prepare($favSql);
$favStmt->execute(['id_user' => $currentUserId, 'id_auction' => $id_auction]);
$isFavorited = $favStmt->fetch(PDO::FETCH_ASSOC) ? true : false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['favorite_action'])) {
    if ($_POST['favorite_action'] === 'add') {
        // Add auction to favorites
        $favInsertSql = "INSERT INTO favorites (id_user, id_auction) VALUES (:id_user, :id_auction)";
        $favInsertStmt = $conn->prepare($favInsertSql);
        try {
            $favInsertStmt->execute(['id_user' => $currentUserId, 'id_auction' => $id_auction]);
            $isFavorited = true;
            $successMessage = "Aukcja została dodana do obserwowanych.";
        } catch (Exception $e) {
            $errors[] = "Wystąpił błąd podczas dodawania do obserwowanych: " . $e->getMessage();
        }
    } elseif ($_POST['favorite_action'] === 'remove') {
        // Remove auction from favorites
        $favDeleteSql = "DELETE FROM favorites WHERE id_user = :id_user AND id_auction = :id_auction";
        $favDeleteStmt = $conn->prepare($favDeleteSql);
        try {
            $favDeleteStmt->execute(['id_user' => $currentUserId, 'id_auction' => $id_auction]);
            $isFavorited = false;
            $successMessage = "Aukcja została usunięta z obserwowanych.";
        } catch (Exception $e) {
            $errors[] = "Wystąpił błąd podczas usuwania z obserwowanych: " . $e->getMessage();
        }
    }
}




$errors = [];
$successMessage = '';

// Handle bid submission with date check
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bid_amount'])) {
    $currentDate = new DateTime();
    $auctionEndDate = new DateTime($auction['end_time']);

    // Check if the auction has ended
    if ($currentDate > $auctionEndDate) {
        $errors[] = "Aukcja jest zakończona. Nie można podbijać cen";
    } else {
        $newBid = floatval($_POST['bid_amount']);

        if ($newBid > $auction['start_price']) {
            // Update the price and highest bidder in the database
            $updateSql = "UPDATE auctions 
                          SET start_price = :newBid, 
                              highest_bidder_id = :userId 
                          WHERE id_auction = :id_auction";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->execute([
                ':newBid' => $newBid,
                ':userId' => $currentUserId,
                ':id_auction' => $id_auction
            ]);

            // Update local auction details
            $auction['start_price'] = $newBid;
            $auction['highest_bidder_id'] = $currentUserId;
            $auction['highest_bidder_first_name'] = $_SESSION['firstName'];
            $auction['highest_bidder_last_name'] = $_SESSION['lastName'];

            $successMessage = "Twoja oferta jest aktualnie najwyższa: {$newBid} zł";
        } else {
            $errors[] = "Nowa oferta musi być wyższa niż aktualna cena!";
        }
    }
    if (isset($_SESSION['id_user'])) {
        // Pobierz dane użytkownika
        $userStmt = $conn->prepare("SELECT firstName, lastName FROM users WHERE id_user = :id_user");
        $userStmt->execute(['id_user' => $_SESSION['id_user']]);
        $userData = $userStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($userData) {
            $_SESSION['firstName'] = $userData['firstName'];
            $_SESSION['lastName'] = $userData['lastName'];
        }
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
        <p class="text-base text-gray-600">
        <?php
        if ($auction['highest_bidder_id']) {
            echo "Aktualnie najwyższą ofertę złożył(a): " . 
                 htmlspecialchars($auction['highest_bidder_first_name'] . ' ' . $auction['highest_bidder_last_name']);
        } else {
            echo "Jeszcze nikt nie złożył oferty.";
        }
        ?>
    </p>
        <!-- Bid Form -->
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
              <li class="text-gray-400"><span class="text-gray-600">Koniec aukcji: <?php echo htmlspecialchars($auction['end_time']); ?></span></li>
              <li class="text-gray-400"><span class="text-gray-600">Wystawione przez: <?php echo htmlspecialchars($auction['owner_first_name'] . ' ' . $auction['owner_last_name']); ?></span></li>
              <div class="mt-4 lg:row-span-3 lg:mt-0">
        <!-- Add/Remove favorite form -->
                  <form method="POST" action="">
                      <button type="submit" name="favorite_action" value="<?php echo $isFavorited ? 'remove' : 'add'; ?>" class="mt-4 flex w-1/2 items-center justify-center rounded-md border border-transparent bg-indigo-600 px-8 py-3 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                          <?php echo $isFavorited ? 'Usuń z obserwowanych' : 'Dodaj do obserwowanych'; ?>
                      </button>
                  </form>
              </div>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="../js/dropdown.js"></script>
</body>
</html>