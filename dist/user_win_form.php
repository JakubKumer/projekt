<?php
session_start();
include_once "../scripts/connect.php";
require '../vendor/autoload.php'; // Include the Stripe PHP library
require_once '../scripts/secrets.php';
\Stripe\Stripe::setApiKey($stripeSecretKey); // Set your Stripe Secret Key

$errors = [];
$successMessage = '';
$user_id = $_SESSION['id_user']; // Assume the user's ID is in the session
$user = [];
$auction = [];

// Get the auction ID from GET
$auction_id = $_GET['id'] ?? null;

if ($auction_id && $user_id) {
    try {
        // Fetch auction and user details
        $stmt = $conn->prepare("
            SELECT ca.*, u.firstName, u.lastName, u.email, u.street, u.city, u.house_number, u.postal_code, u.phone_number 
            FROM completed_auctions ca
            JOIN users u ON ca.highest_bidder_id = u.id_user
            WHERE ca.id = :auction_id AND ca.highest_bidder_id = :user_id
        ");
        $stmt->bindParam(':auction_id', $auction_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            $user = [
                'firstName' => $result['firstName'],
                'lastName' => $result['lastName'],
                'email' => $result['email'],
                'street' => $result['street'],
                'city' => $result['city'],
                'house_number' => $result['house_number'],
                'postal_code' => $result['postal_code'],
                'phone_number' => $result['phone_number']
            ];
            $auction = [
                'title' => $result['title'],
                'price' => $result['price']
            ];
        } else {
            $errors[] = "Auction not found or you don't have permission to buy it.";
        }
    } catch (PDOException $e) {
        $errors[] = "Error fetching data: " . $e->getMessage();
    }
} else {
    $errors[] = "No auction ID or not logged in.";
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['firstName'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $email = $_POST['email'] ?? '';
    $street = $_POST['street'] ?? '';
    $city = $_POST['city'] ?? '';
    $houseNumber = $_POST['house_number'] ?? '';
    $postalCode = $_POST['postal_code'] ?? '';
    $phoneNumber = $_POST['phone_number'] ?? '';

    // Validate fields
    if (empty($firstName)) $errors[] = "Podaj imię.";
    if (empty($lastName)) $errors[] = "Podaj nazwisko.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Niepoprawny adres email.";
    if (empty($street)) $errors[] = "Podaj nazwe ulicy.";
    if (empty($city)) $errors[] = "Podaj nazwe miasta.";
    if (empty($houseNumber)) $errors[] = "Podaj numer domu.";
    if (empty($postalCode)) $errors[] = "Podaj kod pocztowy.";
    if (empty($phoneNumber)) $errors[] = "Podaj numer telefonu.";

    // If no errors
    if (empty($errors)) {
        try {
            // Create Stripe Checkout Session
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'pln',
                        'product_data' => [
                            'name' => $auction['title'],
                        ],
                        'unit_amount' => $auction['price'] * 100, 
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => 'http://localhost/projekt/projekt/dist/success.php?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => 'http://localhost/projekt/projekt/dist/cancel.php',
                'metadata' => [
                    'auction_id' => $auction_id
                ],
            ]);
    
            // Redirect to Stripe Checkout
            header("Location: " . $session->url);
            exit;
        } catch (Exception $e) {
            $errors[] = "Error creating Stripe session: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/user_profile.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Płatność za aukcję</title>
</head>
<body>
    <header class="bg-blue-950">       
        <div class="container w-4/5 m-auto bg-blue-950 flex justify-around p-8">
            <div class=""><a href="loggin.php"><img src="/projekt/projekt/img/BidHub_logo_removebg_minimalized.png" alt="Błąd załadowania zdjęcia" width="150" height="150"></a></div>
            <div class="text-white"><a href="user_profile.php">Moje Dane</a></div>
            <div class="text-white"><a href="user_profile_auctions.php">Twoje Aukcje</a></div>
            <div class="text-white"><a href="user_profile_fav.php">Obserwowane aukcje</a></div>   
            <div class="text-white"><a href="user_win_auction_profile.php">Wygrane aukcje</a></div> 
            <div class="text-white"><a href="user_sold_list.php">sprzedane</a></div>   
        </div>
    </header>
    <div class="container w-4/5 m-auto h-full">
        <?php if(!empty($errors)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 w-auto m-auto lg:w-auto" role="alert">
                <p class="font-bold">Błąd</p>
                <ul>
                <?php foreach($errors as $error): ?>
                   <li><?php echo $error; ?></li>
                <?php endforeach; ?>
                </ul>
            </div>  
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 w-auto m-auto lg:auto" role="alert">
                <p class="font-bold">Sukces</p>
                <p><?php echo $successMessage; ?></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($auction)): ?>
        <form action="" method="POST" class="h-full">
            <div class="border-b border-gray-900/10 pb-12 w-4/5 m-auto h-full ">
                <h2 class="text-base font-semibold leading-7 text-gray-900">Informacje do płatności</h2>
                <p class="mt-1 text-sm leading-6 text-gray-600">Podaj nam swoje dane do płatności</p>

                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-6 flex">
                        <h3 class="text-lg font-semibold">Aukcja: <?php echo htmlspecialchars($auction['title']); ?></h3>
                        <p class="text-xl font-bold ml-5">Cena: <?php echo number_format($auction['price'], 2); ?> PLN</p>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="first-name" class="block text-sm font-medium leading-6 text-gray-900">Imie</label>
                        <div class="mt-2">
                            <input type="text" name="firstName" id="first-name" autocomplete="given-name" value="<?php echo htmlspecialchars($user['firstName']); ?>" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Nazwisko</label>
                        <div class="mt-2">
                            <input type="text" name="lastName" id="last-name" autocomplete="family-name" value="<?php echo htmlspecialchars($user['lastName']); ?>" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-4">
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Adres Email</label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" autocomplete="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="col-span-full">
                        <label for="street" class="block text-sm font-medium leading-6 text-gray-900">Nazwa Ulicy</label>
                        <div class="mt-2">
                            <input type="text" name="street" id="street" autocomplete="street" value="<?php echo htmlspecialchars($user['street']); ?>" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-2 sm:col-start-1">
                        <label for="city" class="block text-sm font-medium leading-6 text-gray-900">Miasto</label>
                        <div class="mt-2">
                            <input type="text" name="city" id="city" autocomplete="address-level2" value="<?php echo htmlspecialchars($user['city']); ?>" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="house_number" class="block text-sm font-medium leading-6 text-gray-900">Numer Domu</label>
                        <div class="mt-2">
                            <input type="text" name="house_number" id="house_number" autocomplete="house_number" value="<?php echo htmlspecialchars($user['house_number']); ?>" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="postal_code" class="block text-sm font-medium leading-6 text-gray-900">Kod Pocztowy</label>
                        <div class="mt-2">
                            <input type="text" name="postal_code" id="postal_code" autocomplete="postal_code" value="<?php echo htmlspecialchars($user['postal_code']); ?>" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="col-span-full">
                        <label for="phone_number" class="block text-sm font-medium leading-6 text-gray-900">Nr Telefonu</label>
                        <div class="mt-2">
                            <input type="text" name="phone_number" id="phone_number" autocomplete="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Anuluj</button>
                    <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Zapłać</button>
                </div>
            </div>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>