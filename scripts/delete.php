<?php
session_start();
include_once "../scripts/connect.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../dist/login.php");
    exit();
}

// Sprawdź, czy przekazano ID użytkownika do usunięcia
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_to_delete = $_GET['id'];

    try {
        // Przygotowanie zapytania SQL do usunięcia użytkownika
        $query = "DELETE FROM users WHERE id_user = :id_user";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':id_user', $id_to_delete, PDO::PARAM_INT);
        $stmt->execute();

        // Sprawdź, czy udało się usunąć użytkownika
        if ($stmt->rowCount() > 0) {
            header("Location: ../dist/admin.php?success=Użytkownik został pomyślnie usunięty.");
        } else {
            header("Location: ../dist/admin.php?error=Nie znaleziono użytkownika do usunięcia.");
        }
    } catch (PDOException $e) {
        // Obsługa wyjątków związanych z ograniczeniami integralności
        if ($e->getCode() == "23000") {
            $error_message = "Nie można usunąć użytkownika, ponieważ istnieją powiązane dane (np. ulubione).";
        } else {
            $error_message = "Błąd podczas usuwania użytkownika: " . $e->getMessage();
        }
        header("Location: ../dist/admin.php?error=" . urlencode($error_message));
    }
} else {
    header("Location: ../dist/admin.php?error=Nieprawidłowe ID użytkownika.");
}
exit();