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

$currentUserId = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : null;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($auction['title']); ?></title>
    <link rel="stylesheet" href="../src/output.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
<header class="bg-blue-950">
    <div class="container w-4/5 m-auto bg-blue-950 flex justify-around p-8">
        <div><a href="index.php"><img src="/projekt/projekt/img/BidHub_logo_removebg_minimalized.png" alt="Logo" width="150" height="150"></a></div>
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
</header>
<div class="bg-white">
    <div class="pt-6">
        <div class="mx-auto mt-6 max-w-2xl sm:px-6 lg:grid lg:max-w-7xl lg:grid-cols-3 lg:gap-x-8 lg:px-8">
            <div class="hidden lg:grid lg:grid-cols-1 lg:gap-y-8">
                <div class="aspect-h-2 aspect-w-3 overflow-hidden rounded-lg">
                    <img src="<?php echo htmlspecialchars($auction['image']); ?>" alt="<?php echo htmlspecialchars($auction['title']); ?>" class="h-full w-full object-cover object-center">
                </div>
            </div>
        </div>
        <div class="mx-auto max-w-2xl px-4 pb-16 pt-10 sm:px-6 lg:grid lg:max-w-7xl lg:grid-cols-3 lg:gap-x-8 lg:px-8 lg:pb-24 lg:pt-16">
            <div class="lg:col-span-2 lg:border-r lg:border-gray-200 lg:pr-8">
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl"><?php echo htmlspecialchars($auction['title']); ?></h1>
            </div>
            <div class="mt-4 lg:row-span-3 lg:mt-0">
                <h2 class="text-xl font-bold">Aktualna cena</h2>
                <p class="text-3xl tracking-tight text-gray-900"><?php echo htmlspecialchars($auction['start_price']); ?> zł</p>
                <p class="text-base text-gray-600">
                    <?php
                    if ($auction['highest_bidder_id']) {
                        echo "Aktualnie najwyższą ofertę złożył(a): " . htmlspecialchars($auction['highest_bidder_first_name'] . ' ' . $auction['highest_bidder_last_name']);
                    } else {
                        echo "Jeszcze nikt nie złożył oferty.";
                    }
                    ?>
                </p>

                <?php if ($currentUserId): ?>
                    <form method="POST" action="">
                        <label for="bid_amount" class="block text-sm font-medium text-gray-700 mt-2">Podaj swoją cenę</label>
                        <input type="number" id="bid_amount" name="bid_amount" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" step="1.00" required>
                        <button type="submit" class="mt-4 flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-8 py-3 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Licytuj</button>
                    </form>
                <?php else: ?>
                    <p class="mt-4 text-red-500 font-bold">Musisz się zalogować, aby brać udział w licytacji i zobaczyć więcej szczegółów dotyczących aukcji.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script src="../js/dropdown.js"></script>
</body>
</html>