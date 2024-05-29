<?php 
    if (isset($_GET["id"])) {
    include_once "connect.php";    
    $id = $_GET["id"];
    // Pobieranie użytkownika o podanym identyfikatorze
    $sql = "SELECT * FROM users WHERE id_user = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() == 1) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $firstName = $row["firstName"];
        $lastName = $row["lastName"];
        $email = $row["email"];
        include_once "update.php";
    } else {
        echo "Użytkownik o podanym identyfikatorze nie istnieje.";
        exit();
        }    
}
 else {
        echo "Nieprawidłowe żądanie.";
        exit();
    }

?>