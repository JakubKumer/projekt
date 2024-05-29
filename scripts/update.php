<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sprawdzenie, czy wszystkie pola formularza zostały wypełnione
    if (isset($_POST["id"]) && isset($_POST["firstName"]) && isset($_POST["lastName"]) && isset($_POST["email"])) {
       include_once "connect.php";
       $sql = "UPDATE users SET firstName = :firstName, lastName = :lastName, email = :email WHERE id_user = :id";
       $stmt = $conn->prepare($sql);
       $stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR);
       $stmt->bindParam(':lastName', $lastName, PDO::PARAM_STR);
       $stmt->bindParam(':email', $email, PDO::PARAM_STR);
       $stmt->bindParam(':id', $id, PDO::PARAM_INT);

       if ($stmt->execute()) {
           header("Location: admin.php");
           exit();
       } else {
           echo "Błąd podczas aktualizacji danych użytkownika.";
       }   
    } else {
        echo "Wszystkie pola formularza muszą być wypełnione.";
    }
} else {
    echo "Nieprawidłowe żądanie.";
}
?>

