<?php
session_start();
include_once "../scripts/connect.php";

if (!isset($_GET["id"])) {
     header("Locations: login.php");
    exit();
}

$id_user = $_GET["id"];
$errors = [];
$successMessage = "";

// Obsługa formularza
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $city = $_POST['city'];
    $street = $_POST['street'];
    $house_number = $_POST['house_number'];
    $postal_code = $_POST['postal_code'];
    $phone_number = $_POST['phone_number'];
    $bank_account_number = $_POST['bank_account_number'];

    // Obsługa uploadu zdjęcia
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ["jpg" => "image/jpg", "jpeg" => "image/jpeg", "png" => "image/png"];
        $filename = $_FILES["image"]["name"];
        $filetype = $_FILES["image"]["type"];
        $filesize = $_FILES["image"]["size"];

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, $allowed)) {
            $errors[] = "Proszę wybrać format pliku JPEG lub PNG.";
        } elseif ($filesize > 5 * 1024 * 1024) {
            $errors[] = "Rozmiar pliku jest za duży. Max 5MB.";
        } elseif (in_array($filetype, $allowed)) {
            $target_dir = "../uploads/";
            $new_filename = uniqid() . '.' . $ext;
            $target_file = $target_dir . $new_filename;

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $profile_image = $target_file;
                $sql = "UPDATE users SET profile_image = :profile_image WHERE id_user = :user_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':profile_image', $profile_image);
                $stmt->bindParam(':user_id', $id_user, PDO::PARAM_INT);
                $stmt->execute();
            } else {
                $errors[] = "Wystąpił błąd podczas przesyłania pliku.";
            }
        }
    }

    // Walidacja danych wejściowych
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Proszę podać poprawny adres email.";
    }
    if (!is_numeric($house_number)) {
        $errors[] = "Numer domu musi być cyfrą.";
    }
    if (!preg_match("/^[0-9]{2}-[0-9]{3}$/", $postal_code)) {
        $errors[] = "Proszę podać kod pocztowy w formacie 00-000.";
    }
    if (!preg_match("/^[0-9]{9}$/", $phone_number)) {
        $errors[] = "Proszę podać poprawny numer telefonu (9 cyfr).";
    }
    if (!preg_match("/^[A-Z]{2}[0-9]{2}[0-9A-Z]{1,30}$/", $bank_account_number)) {
        $errors[] = "Proszę podać poprawny numer konta bankowego w formacie IBAN.";
    }

    if (empty($errors)) {
        try {
            $sql = "UPDATE users SET email = :email, city = :city, street = :street, house_number = :house_number, 
                    postal_code = :postal_code, phone_number = :phone_number, bank_account_number = :bank_account_number 
                    WHERE id_user = :user_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':city', $city);
            $stmt->bindParam(':street', $street);
            $stmt->bindParam(':house_number', $house_number);
            $stmt->bindParam(':postal_code', $postal_code);
            $stmt->bindParam(':phone_number', $phone_number);
            $stmt->bindParam(':bank_account_number', $bank_account_number);
            $stmt->bindParam(':user_id', $id_user, PDO::PARAM_INT);
            $stmt->execute();

            $successMessage = "Dane zostały pomyślnie zaktualizowane!";
        } catch (Exception $e) {
            $errors[] = "Wystąpił błąd podczas aktualizacji danych. Spróbuj ponownie później.";
        }
    }
}

// Pobieranie danych użytkownika
$sql = "SELECT * FROM users WHERE id_user = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $id_user, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edytuj Użytkownika</title>
    <link rel="stylesheet" href="../src/admin.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
<div class="sidebar">
    <a href="admin.php"><i class="fa fa-fw fa-user"></i> Użytkownicy</a>
    <a href="auctions_admin.php"><i class="fa fa-clipboard"></i> Aukcje</a>
    <a href="category_admin.php"><i class="fa fa-clipboard"></i> Kategorie</a>
    <a href="finished_auction_admin.php"><i class="fa fa-clipboard"></i> Zakończone aukcje</a>
    <a href="../scripts/logout.php"><i class="fa fa-clipboard"></i> Wyloguj</a>
</div>
<div class="main">
    <div class="kontener">
        <h1 class="font-bold text-lg">Profil użytkownika</h1>
        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <?php echo $successMessage; ?>
            </div>
        <?php endif; ?>
        <form class="m-auto w-1/2 flex-wrap" enctype="multipart/form-data" method="POST">
        <div class="block w-1/2 m-auto">
            <label for="firstName">Imię:</label>
            <input type="text" name="firstName" id="firstName" value="<?php echo htmlspecialchars($user['firstName']); ?>" readonly><br>
        </div>
        <div class="block w-1/2">
            <label for="lastName">Nazwisko:</label>
            <input type="text" name="lastName" id="lastName" value="<?php echo htmlspecialchars($user['lastName']); ?>" readonly><br>
        </div>
        <div class="block w-1/2">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>"><br>
        </div>
        <div class="block w-1/2">
            <label for="city">Miasto:</label>
            <input type="text" name="city" id="city" value="<?php echo htmlspecialchars($user['city']); ?>"><br>
        </div>
        <div class="block w-1/2">
            <label for="street">Ulica:</label>
            <input type="text" name="street" id="street" value="<?php echo htmlspecialchars($user['street']); ?>"><br>
        </div>
        <div class="block w-1/2">
            <label for="house_number">Nr domu:</label>
            <input type="text" name="house_number" id="house_number" value="<?php echo htmlspecialchars($user['house_number']); ?>"><br>
        </div>
        <div class="block w-1/2">
            <label for="postal_code">Kod pocztowy:</label>
            <input type="text" name="postal_code" id="postal_code" value="<?php echo htmlspecialchars($user['postal_code']); ?>"><br>
        </div>
        <div class="block w-1/2">
            <label for="phone_number">Nr telefonu:</label>
            <input type="text" name="phone_number" id="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>"><br>
        </div>
        <div class="block w-1/2">
            <label for="bank_account_number">Numer konta bankowego:</label>
            <input type="text" name="bank_account_number" id="bank_account_number" value="<?php echo htmlspecialchars($user['bank_account_number']); ?>"><br>
        </div>
        <div class="block w-1/2 mt-2">
            <label for="image">Ustaw zdjęcie profilowe:</label>
            <input type="file" name="image" id="image" accept=".jpg,.jpeg,.png"><br>
        </div>
        <input type="submit" value="Zaktualizuj dane">
    </form>      
    </div>
</div>
</body>
</html>