<?php
session_start(); 
include_once "../scripts/connect.php"; 

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id_user'];

// Zaktualizowane zapytanie do bazy danych
$query = "
    SELECT ca.id, ca.title, ca.image, ca.description, ca.end_time, ca.price, ca.status, 
           u.firstName, u.lastName, u.email, u.city, u.street, u.house_number, u.postal_code, u.phone_number,
           ca.payment_status, ca.delivery_status, ca.is_send
    FROM completed_auctions ca
    INNER JOIN users u ON ca.highest_bidder_id = u.id_user
    WHERE ca.id_user = :user_id
";

$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

// Obsługa aktualizacji statusu wysyłki
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_delivery'])) {
    $auction_id = $_POST['auction_id'];
    $update_query = "UPDATE completed_auctions SET is_send = 'tak' WHERE id = :auction_id AND id_user = :user_id";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bindParam(':auction_id', $auction_id, PDO::PARAM_INT);
    $update_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $update_stmt->execute();
    
    // Odświeżenie strony po aktualizacji
    header("Location: user_sold_list.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/user_profile.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Sprzedane aukcje</title>
</head>
<body>
    <header class="bg-blue-950">       
        <div class="container w-4/5 m-auto bg-blue-950 flex justify-around p-8">
            <div class=""><a href="loggin.php"><img src="/projekt/projekt/img/BidHub_logo_removebg_minimalized.png" alt="Błąd załadowania zdjęcia" width="150" height="150"></a></div>
            <div class="text-white"><a href="user_profile.php">Moje Dane</a></div>
            <div class="text-white"><a href="user_profile_auctions.php">Twoje Aukcje</a></div>
            <div class="text-white"><a href="user_profile_fav.php">Obserwowane aukcje</a></div>   
            <div class="text-white"><a href="user_win_auction_profile.php">Wygrane aukcje</a></div>
            <div class="text-white"><a href="user_sold_list.php">Sprzedane</a></div>    
        </div>
    </header>
    <main class="container mx-auto mt-8">
        <h1 class="text-2xl font-bold mb-4">Sprzedane aukcje</h1>
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Tytuł</th>
                    <th class="py-2 px-4 border-b">Zdjęcie</th>
                    <th class="py-2 px-4 border-b">Opis</th>
                    <th class="py-2 px-4 border-b">Data zakończenia</th>
                    <th class="py-2 px-4 border-b">Cena</th>
                    <th class="py-2 px-4 border-b">Kupujący</th>
                    <th class="py-2 px-4 border-b">Status płatności</th>
                    <th class="py-2 px-4 border-b">Status wysyłki</th>
                    <th class="py-2 px-4 border-b">Czy wysłane</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['title']) ?></td>
                    <td class="py-2 px-4 border-b"><img src="<?= htmlspecialchars($row['image']) ?>" alt="Zdjęcie aukcji" class="w-20 h-20 object-cover"></td>
                    <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['description']) ?></td>
                    <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['end_time']) ?></td>
                    <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['price']) ?> zł</td>
                    <td class="py-2 px-4 border-b">
                        <a href="user_delivery_form.php?auction_id=<?= urlencode($row['id']) ?>" 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block">
                            Dane do wysyłki
                        </a>
                    </td>
                    <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['payment_status']) ?></td>
                    <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['delivery_status']) ?></td>
                    <td class="py-2 px-4 border-b ">
                        <?php if ($row['is_send'] == 'nie'): ?>
                        <form method="POST" class="h-1/2 ">
                            <input class="opacity-100" type="hidden" name="auction_id" value="<?= $row['id'] ?>">
                            <button type="submit" name="update_delivery" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ">
                                Oznacz jako wysłane
                            </button>
                        </form>
                        <?php else: ?>
                        Wysłane
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
</body>
</html>