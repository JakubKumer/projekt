<?php
include_once "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["id"])) {
        $id = $_GET["id"];

        // Usunięcie aukcji z bazy danych
        $sql = "DELETE FROM auctions WHERE id_auction = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: ../dist/auctions_admin.php");
            exit();
        } else {
            echo "Błąd podczas usuwania aukcji.";
        }
    } else {
        echo "Nieprawidłowe żądanie.";

    }

}
?>
