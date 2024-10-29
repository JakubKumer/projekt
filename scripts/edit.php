<?php
include_once "connect.php";

if (!isset($_GET["id"])) {
    echo "Nieprawidłowe żądanie.";
    exit();
}

$id = $_GET["id"];

// Pobieranie danych użytkownika
try {
    $sql = "SELECT * FROM users WHERE id_user = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    if ($stmt->rowCount() != 1) {
        echo "Użytkownik o podanym identyfikatorze nie istnieje.";
        exit();
    }
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "Błąd bazy danych: " . $e->getMessage();
    exit();
}

// Obsługa formularza aktualizacji
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Przygotowanie zapytania aktualizującego
        $updateFields = [];
        $params = [];

        // Sprawdzanie i dodawanie pól do aktualizacji
        $fieldsToUpdate = [
            'email', 'city', 'street', 'house_number', 
            'postal_code', 'phone_number', 'bank_account_number'
        ];

        foreach ($fieldsToUpdate as $field) {
            if (isset($_POST[$field]) && $_POST[$field] !== $user[$field]) {
                $updateFields[] = "$field = :$field";
                $params[$field] = $_POST[$field];
            }
        }

        // Obsługa przesyłania zdjęcia
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['image/jpeg', 'image/png', 'image/jpg'];
            if (in_array($_FILES['image']['type'], $allowed)) {
                $file_name = uniqid() . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $upload_path = '../uploads/' . $file_name;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    $updateFields[] = "profile_image = :profile_image";
                    $params['profile_image'] = $upload_path;
                }
            }
        }

        // Wykonanie aktualizacji jeśli są zmiany
        if (!empty($updateFields)) {
            $sql = "UPDATE users SET " . implode(", ", $updateFields) . " WHERE id_user = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            
            if ($stmt->execute()) {
                // Odświeżenie danych użytkownika
                header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $id);
                exit();
            }
        }
    } catch(PDOException $e) {
        $error = "Błąd podczas aktualizacji: " . $e->getMessage();
    }
}
$firstName = htmlspecialchars($user['firstName']);
$lastName = htmlspecialchars($user['lastName']);
$email = htmlspecialchars($user['email']);
$city = htmlspecialchars($user['city']);
$street = htmlspecialchars($user['street']);
$houseNumber = htmlspecialchars($user['house_number']);
$postalCode = htmlspecialchars($user['postal_code']);
$phoneNumber = htmlspecialchars($user['phone_number']);
$bankAccountNumber = htmlspecialchars($user['bank_account_number']);