<?php
session_start();
include_once "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];

    // Sanitize input
    $title = filter_input(INPUT_POST, 'productTitle', FILTER_SANITIZE_STRING);
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $category = filter_input(INPUT_POST, 'category', FILTER_VALIDATE_INT);
    $end_time = filter_input(INPUT_POST, 'end_time', FILTER_SANITIZE_STRING);

    // Validate input
    if (empty($title)) {
        $errors[] = "Nazwa produktu jest wymagana.";
    }
    if (!preg_match('/^\d+(\.\d{1,2})?$/', $_POST['price'])) {
        $errors[] = "Cena musi być liczbą dziesiętną z maksymalnie dwoma miejscami po przecinku.";
    }
    if (empty($description)) {
        $errors[] = "Opis jest wymagany.";
    }
    if ($category === false || $category == 0) {
        $errors[] = "Wybierz poprawną kategorię.";
    }
    if (empty($end_time)) {
        $errors[] = "Data zakończenia aukcji jest wymagana.";
    }

    // Sprawdzenie, czy wybrana data zakończenia aukcji jest późniejsza od bieżącej daty
    $current_time = date('Y-m-d H:i:s');
    if ($end_time < $current_time) {
        $errors[] = "Data zakończenia aukcji musi być późniejsza niż aktualna.";
    }

    // Sprawdzenie, czy plik obrazu został poprawnie przesłany
    if ($_FILES['image']['error'] != UPLOAD_ERR_OK) {
        $errors[] = "Błąd podczas przesyłania obrazu.";
    }

    // Handle image upload
    if (empty($errors)) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is an actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            $errors[] = "Plik nie jest obrazem.";
        }

        // Check file size (5MB max)
        if ($_FILES["image"]["size"] > 5000000) {
            $errors[] = "Przepraszamy, Twój plik jest za duży.";
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $errors[] = "Przepraszamy, dozwolone są tylko pliki JPG, JPEG, PNG i GIF.";
        }

        // Check if there are no errors before moving the file
        if (empty($errors) && move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            try {
                // Wstawienie danych aukcji do bazy, w tym daty zakończenia
                $stmt = $conn->prepare("INSERT INTO auctions (id_user, id_category, title, description, image, start_price, end_time) VALUES (:id_user, :id_category, :title, :description, :image, :start_price, :end_time)");
                $stmt->bindValue(':id_user', $_SESSION['id_user'], PDO::PARAM_INT);
                $stmt->bindValue(':id_category', $category, PDO::PARAM_INT);
                $stmt->bindValue(':title', $title, PDO::PARAM_STR);
                $stmt->bindValue(':description', $description, PDO::PARAM_STR);
                $stmt->bindValue(':image', $target_file, PDO::PARAM_STR);
                $stmt->bindValue(':start_price', $price, PDO::PARAM_STR);
                $stmt->bindValue(':end_time', $end_time, PDO::PARAM_STR);  // Użycie wybranej daty
                $stmt->execute();

                $_SESSION['success'] = "Aukcja została dodana pomyślnie!";
                header("Location: ../dist/user_profile_auctions.php");
                exit();
            } catch (PDOException $e) {
                $errors[] = "Błąd podczas dodawania aukcji: " . $e->getMessage();
            }
        } else {
            $errors[] = "Przepraszamy, wystąpił błąd podczas przesyłania pliku.";
        }
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: ../dist/add_auction_user.php");
        exit();
    }
} else {
    header("Location: ../dist/add_auction_user.php");
    exit();
}
?>