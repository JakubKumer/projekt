<?php
session_start();
include_once "../scripts/connect.php";
$user_id = $_SESSION['id_user'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $city = $_POST['city'];
    $street = $_POST['street'];
    $house_number = $_POST['house_number'];
    $postal_code = $_POST['postal_code'];
    $phone_number = $_POST['phone_number'];

    $sql = "UPDATE users SET email = :email, city = :city, street = :street, house_number = :house_number, postal_code = :postal_code, phone_number = :phone_number WHERE id_user = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':street', $street);
    $stmt->bindParam(':house_number', $house_number);
    $stmt->bindParam(':postal_code', $postal_code);
    $stmt->bindParam(':phone_number', $phone_number);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
}
$sql = "SELECT * FROM users WHERE id_user = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
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
        <div class=""><a href="loggin.php"><img src="/projekt/projekt/img/BidHub_logo_removebg_minimalized.png" alt="Błąd załadowania zdjęcia" width="150" height="150"></a></div>
            <div class="text-white"><a href="user_profile.php">Moje Dane</a></div>
            <div class="text-white"><a href="user_profile_auctions.php">Twoje Aukcje</a></div>           
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
                <input type="email" name="email" id="email" value="<?php echo $user['email']; ?>" ><br>
            </div>

            <div class="block w-1/2">
                <label for="city">Miasto:</label>
                <input type="text" name="city" id="city" value="<?php echo $user['city']; ?>"><br>
            </div>

            <div class="block w-1/2">
                <label for="street">Ulica:</label>
                <input type="text" name="street" id="street" value="<?php echo $user['street']; ?>"><br>
            </div>

            <div class="block w-1/2">
                <label for="house_number">Nr domu:</label>
                <input type="text" name="house_number" id="house_number" value="<?php echo $user['house_number']; ?>"><br>
            </div>

            <div class="block w-1/2">
                <label for="postal_code">Kod pocztowy:</label>
                <input type="text" name="postal_code" id="postal_code" value="<?php echo $user['postal_code']; ?>"><br>
            </div>

            <div class="block w-1/2">
                <label for="phone_number">Nr telefonu:</label>
                <input type="text" name="phone_number" id="phone_number" value="<?php echo $user['phone_number']; ?>"><br>
            </div>

            <input type="submit" value="Zaktualizuj dane">
        </form>       
   </div>
</body>
</html>
