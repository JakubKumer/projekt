<?php
session_start();
require_once '../scripts/connect.php';
require_once '../vendor/autoload.php';
require_once '../scripts/secrets.php';

\Stripe\Stripe::setApiKey($stripeSecretKey);

$errors = [];
$successMessage = '';

// Check if the user is logged in
if (!isset($_SESSION['id_user'])) {
    $errors[] = "Musisz być zalogowany .";
}

// Get the session_id from the URL
$session_id = $_GET['session_id'] ?? null;

if (!$session_id) {
    $errors[] = "Brak ID sesji.";
}

if (empty($errors)) {
    try {
        // Retrieve the session from Stripe to confirm the payment status
        $session = \Stripe\Checkout\Session::retrieve($session_id);

        if ($session->payment_status == 'paid') {
            // Payment was successful, update the database
            $stmt = $conn->prepare("
                UPDATE completed_auctions 
                SET payment_status = 'zaplacone' 
                WHERE id = :auction_id AND highest_bidder_id = :user_id
            ");
            
            $stmt->bindParam(':auction_id', $session->metadata->auction_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $_SESSION['id_user'], PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                $successMessage = "Płatność udana.";
            } else {
                $errors[] = "Nieudało się zaaktualizowac statusu płatności.";
            }
        } else {
            $errors[] = "Płatność nieudana.";
        }
    } catch (\Stripe\Exception\ApiErrorException $e) {
        $errors[] = "Stripe API Error: " . $e->getMessage();
    } catch (PDOException $e) {
        $errors[] = "Database Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p class="font-bold">Error</p>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p class="font-bold">Success</p>
                <p><?php echo htmlspecialchars($successMessage); ?></p>
            </div>
        <?php endif; ?>

        <h1 class="text-2xl font-bold mb-4">Payment Status</h1>
        <p class="mb-4">
            <?php
            if (!empty($successMessage)) {
                echo "Your payment has been processed successfully.";
            } else {
                echo "There was an issue processing your payment. Please contact support if you believe this is an error.";
            }
            ?>
        </p>
        <a href="user_profile.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Return to Profile
        </a>
    </div>
</body>
</html>