<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once "connect.php"; 
    $id = $_POST["id"];
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $email = $_POST["email"];
    try {
        if (empty($id) || empty($firstName) || empty($lastName) || empty($email)) {
            throw new Exception("Wszystkie pola są wymagane.");
        }
        $sql = "UPDATE users SET firstName = :firstName, lastName = :lastName, email = :email WHERE id_user = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR);
        $stmt->bindParam(':lastName', $lastName, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        if ($stmt->execute()) {
            header("Location: ../dist/admin.php");
        } else {
            echo "Wystąpił błąd podczas aktualizacji danych użytkownika.";
        }
    } catch (Exception $e) {
        echo "Błąd: " . $e->getMessage();
    }
} else {
    echo "Nieprawidłowe żądanie.";
}
?>

