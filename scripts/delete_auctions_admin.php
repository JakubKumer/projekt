<?php
session_start();
include_once "../scripts/connect.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../dist/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
        $id = $_GET["id"];

        try {
            // Przygotowanie zapytania SQL do usunięcia aukcji
            $sql = "DELETE FROM auctions WHERE id_auction = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                // Jeśli usunięcie się powiodło
                header("Location: ../dist/auctions_admin.php?success=Aukcja została pomyślnie usunięta.");
                exit();
            } else {
                // Jeśli usunięcie się nie powiodło
                header("Location: ../dist/auctions_admin.php?error=Błąd podczas usuwania aukcji.");
                exit();
            }
        } catch (PDOException $e) {
            // Obsługa błędów związanych z ograniczeniami integralności
            if ($e->getCode() == "23000") {
                $error_message = "Nie można usunąć aukcji, ponieważ istnieją powiązane dane.";
            } else {
                $error_message = "Błąd podczas usuwania aukcji: " . $e->getMessage();
            }
            header("Location: ../dist/auctions_admin.php?error=" . urlencode($error_message));
            exit();
        }
    } else {
        header("Location: ../dist/auctions_admin.php?error=Nieprawidłowe ID aukcji.");
        exit();
    }
}
?>