<?php
session_start();


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projektinz";
$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

$user_id = $_SESSION['id_user'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Aktualizacja danych użytkownika
    $email = $_POST['email'];
    $city = $_POST['city'];
    $street = $_POST['street'];
    $house_number = $_POST['house_number'];
    $postal_code = $_POST['postal_code'];
    $phone_number = $_POST['phone_number'];

    $sql = "UPDATE users SET email=?, city=?, street=?, house_number=?, postal_code=?, phone_number=? WHERE id_user=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $email, $city, $street, $house_number, $postal_code, $phone_number, $user_id);
    $stmt->execute();
}

// Pobieranie danych użytkownika
$sql = "SELECT * FROM users WHERE id_user=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Pobieranie aukcji użytkownika
$sql = "SELECT * FROM auctions WHERE id_user=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$auctions = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
    <title>Profil użytkownika</title>
    <link rel="stylesheet" href="../src/user_profile2.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-blue-950">       
        <div class=" container w-4/5 m-auto bg-blue-950 flex justify-around  p-8">
            <div class="text-white ">logo</div>
            <div class="text-white"><a href="">Moje Dane</a></div>
            <div class="text-white"><a href="">Twoje Aukcje</a></div>
        </div>
    </header>
   <div class="kontener">
    <h1 class="font-bold text-lg">Profil użytkownika</h1>
        <h2>Twoje dane</h2>
        <form class="w-3/4 m-auto" method="POST" action="user_profile.php">
           <div class="block w-1/2">
                <label for="firstName">Imię:</label>
                <input type="text" name="firstName" id="firstName" value="<?php echo $user['firstName']; ?>" readonly><br>
           </div>
            <div class="block w-1/2">
                <label for="lastName">Nazwisko:</label>
                <input type="text" name="lastName" id="lastName" value="<?php echo $user['lastName']; ?>" readonly><br>
            </div>                       
            <div class="block w-1/2">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo $user['email']; ?>" readonly><br>
            </div>

            <div class="block w-1/2">
                <label for="city">Miasto:</label>
                <input type="text" name="city" id="city" value="<?php echo $user['city']; ?>"><br>
            </div>

<<<<<<< HEAD
            <div class="block w-1/2">
                <label for="street">Ulica:</label>
                <input type="text" name="street" id="street" value="<?php echo $user['street']; ?>"><br>
            </div>
=======
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?php echo $user['email']; ?>"><br>
>>>>>>> e14a70c4c2b6d7b1d069d543fdd2b6da668bf48f

            <div class="block w-1/2">
                <label for="house_number">Nr domu:</label>
                <input type="text" name="house_number" id="house_number" value="<?php echo $user['house_number']; ?>"><br>
            </div>

            <div class="block w-1/2">
                <label for="postal_code">Kod pocztowy:</label>
                <input type="text" name="postal_code" id="postal_code" value="<?php echo $user['postal_code']; ?>"><br>
            </div>

<<<<<<< HEAD
            <div class="block w-1/2">
                <label for="phone_number">Nr telefonu:</label>
                <input type="text" name="phone_number" id="phone_number" value="<?php echo $user['phone_number']; ?>"><br>
            </div>
=======
        <label for="house_number">Nr domu / mieszkania:</label>
        <input type="text" name="house_number" id="house_number" value="<?php echo $user['house_number']; ?>"><br>
>>>>>>> e14a70c4c2b6d7b1d069d543fdd2b6da668bf48f

            <input type="submit" value="Zaktualizuj dane">
        </form>

<<<<<<< HEAD
        <h2>Twoje aukcje</h2>
        <table>
            <tr>
                <th>ID aukcji</th>
                <th>Tytuł</th>
                <th>Opis</th>
                <th>Cena początkowa</th>
=======
        <label for="phone_number">Nr telefonu:</label>
        <input type="text" name="phone_number" id="phone_number" value="<?php echo $user['phone_number']; ?>"><br>

        <input type="submit" value="Zaktualizuj dane">
    </form>

    <h2>Twoje aukcje</h2>
        <table>
            <tr>
                <th>Numer aukcji</th>
                <th>Nazwa</th>
                <th>Kategoria</th>
                <th>Data zakończenia</th>
>>>>>>> e14a70c4c2b6d7b1d069d543fdd2b6da668bf48f
                <th>Akcje</th>
            </tr>
            <?php while ($auction = $auctions->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $auction['id_auction']; ?></td>
                    <td><?php echo $auction['title']; ?></td>
<<<<<<< HEAD
                    <td><?php echo $auction['description']; ?></td>
                    <td><?php echo $auction['start_price']; ?></td>
                    <td>
=======
                    <td><?php echo $auction['category']; ?></td>
                    <td><?php echo $auction['end_time']; ?></td>
                    <td>
                        <a href="aukcja.php?id=<?php echo $auction['id_auction']; ?>">Sprawdź</a>
>>>>>>> e14a70c4c2b6d7b1d069d543fdd2b6da668bf48f
                        <a href="edit_auction.php?id=<?php echo $auction['id_auction']; ?>">Edytuj</a> | 
                        <a href="delete_auction.php?id=<?php echo $auction['id_auction']; ?>">Usuń</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
   </div>
</body>
</html>
