<?php
session_start();
include_once "connect.php";

// Sprawdzenie, czy użytkownik jest administratorem
if (!isset($_SESSION['id_user']) || $_SESSION['id_role'] != 3) {
    header("Location: ../admin.php?error=Brak+uprawnień");
    exit();
}

// Sprawdzenie, czy przekazano parametr ID aukcji
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../dist/finished_auction_admin.php?error=Nieprawidłowy+ID+aukcji");
    exit();
}

$id = (int)$_GET['id'];

try {
    // Przygotowanie zapytania SQL do usunięcia aukcji
    $stmt = $conn->prepare("DELETE FROM completed_auctions WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    
    // Wykonanie zapytania
    if ($stmt->execute()) {
        header("Location: ../dist/finished_auction_admin.php?success=Aukcja+została+usunięta");
    } else {
        header("Location: ../dist/finished_auction_admin.php?error=Nie+udało+się+usunąć+aukcji");
    }
} catch (Exception $e) {
    header("Location: ../dist/finished_auction_admin.php?error=Wystąpił+błąd+" . urlencode("Błąd: " . $e->getMessage()));
    exit();
}
?>