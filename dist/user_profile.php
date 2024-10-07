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

    // Obsługa uploadu zdjęcia
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "png" => "image/png");
        $filename = $_FILES["image"]["name"];
        $filetype = $_FILES["image"]["type"];
        $filesize = $_FILES["image"]["size"];
    
        // Weryfikuj rozszerzenie pliku
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)) die("Błąd: Proszę wybrać format pliku JPEG lub PNG.");
    
        // Weryfikuj rozmiar pliku - max 5MB
        $maxsize = 5 * 1024 * 1024;
        if($filesize > $maxsize) die("Błąd: Rozmiar pliku jest za duży. Max 5MB");
    
        // Weryfikuj typ MIME pliku
        if(in_array($filetype, $allowed)) {
            // Sprawdź czy plik już istnieje
            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($filename);
            $new_filename = uniqid() . '.' . $ext; // Generuj unikalną nazwę pliku
            $target_file = $target_dir . $new_filename;

            // Przenieś plik do docelowego katalogu
            if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Plik został przesłany, aktualizuj bazę danych
                $profile_image = $target_file;
                $sql = "UPDATE users SET profile_image = :profile_image WHERE id_user = :user_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':profile_image', $profile_image);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();
            } else {
                echo "Przepraszamy, wystąpił błąd podczas przesyłania pliku.";
            }
        } else {
            echo "Błąd: Wystąpił problem z przesłaniem pliku. Spróbuj ponownie."; 
        }
    }

    // Aktualizacja pozostałych danych użytkownika
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
    <link rel="stylesheet" href="../src/user_profile.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-blue-950">       
        <div class=" container w-4/5 m-auto bg-blue-950 flex justify-around  p-8">
        <div class=""><a href="loggin.php"><img src="/projekt/projekt/img/BidHub_logo_removebg_minimalized.png" alt="Błąd załadowania zdjęcia" width="150" height="150"></a></div>
            <div class="text-white"><a href="user_profile.php">Moje Dane</a></div>
            <div class="text-white"><a href="user_profile_auctions.php">Twoje Aukcje</a></div>
            <div class="text-white"><a href="my_reviews.php">Twoje opinie</a></div>
            <div class="text-white"><a href="user_profile_fav.php">Obserwowane aukcje</a></div>
                
        </div>
    </header>
   <div class="kontener">
    <h1 class="font-bold text-lg">Profil użytkownika</h1>
        <h2>Twoje dane</h2>
        <form class="m-auto w-1/2 flex-wrap" enctype="multipart/form-data" method="POST" action="user_profile.php">
           <div class="block w-1/2 m-auto">
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

            <div class="block w-1/2 mt-2">
                <label for="">Ustaw zdjęcie profilowe</label>
                <input type="file" name="image" id="image" accept=".jpg,.jpeg,.png"><br>
            </div> 

            <input type="submit" value="Zaktualizuj dane">
        </form>       
   </div>
</body>
</html>
