<?php
session_start();
$deleteMessage = " ";
if (isset($_GET["id"])) {
    include_once "connect.php";
    $id = $_GET["id"];
    
    try {
        // Deleting the category with the given ID
        $sql = "DELETE FROM categories WHERE id_category = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION["Delete"] = "Kategoria została usunięta";
        } else {
            $_SESSION["Delete"] = "Błąd podczas usuwania kategorii.";
        }
    } catch (PDOException $e) {
        $_SESSION["Delete"] = "Błąd podczas usuwania kategorii: " . $e->getMessage();
    }

    header("Location: ../dist/category_admin.php");
    exit();
} else {
    $_SESSION["Delete"] = "Nieprawidłowy identyfikator kategorii.";
    header("Location: ../dist/category_admin.php");
    exit();
}
?>
