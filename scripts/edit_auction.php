<?php
include_once "connect.php";
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["id"])) {
        $id = $_GET["id"];

        // Pobranie danych aukcji do edycji
        $sql = "SELECT * FROM auctions WHERE id_auction = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $id_user = $row["id_user"];
            $title = $row["title"];
            $description = $row["description"];
            $start_price = $row["start_price"];
        } else {
            echo "Nie znaleziono aukcji.";
            exit();
        }
    } else {
        echo "Nieprawidłowe żądanie.";
        exit();
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["id"]) && isset($_POST["title"]) && isset($_POST["description"]) && isset($_POST["start_price"])) {
        $id = $_POST["id"];
        $title = $_POST["title"];
        $description = $_POST["description"];
        $start_price = $_POST["start_price"];

        // Aktualizacja danych aukcji
        $sql = "UPDATE auctions SET title = :title, description = :description, start_price = :start_price WHERE id_auction = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':start_price', $start_price, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: ../dist/auctions_admin.php");
            exit();
        } else {
            echo "Błąd podczas aktualizacji danych aukcji.";
        }
    } else {
        echo "Wszystkie pola formularza muszą być wypełnione.";
        exit();
    }
    exit();
}
?>
