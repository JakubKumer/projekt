<?php
    session_start();
    include_once "connect.php";  
    if (isset($_POST['categoryName'])) {
        $categoryName = $_POST['categoryName'];
        $errors = [];
    
        // Check if the category already exists
        $stmt = $conn->prepare("SELECT id_category FROM categories WHERE category_name = :categoryName");
        $stmt->bindParam(':categoryName', $categoryName);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {
            $errors[] = "Nie można dodać drugi raz tej samej kategorii";
        } else {
            // Insert the new category
            $stmt = $conn->prepare("INSERT INTO categories (category_name) VALUES (:categoryName)");
            $stmt->bindParam(':categoryName', $categoryName);
            if ($stmt->execute()) {
                $_SESSION['success'] = "Pomyślnie utworzono kategorię";
            } else {
                $errors[] = "Wystąpił błąd podczas dodawania kategorii";
            }
        }
    
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
        }
    
        header('Location: ../dist/category_admin.php');
        exit();
    } else {
        header('Location: ../dist/category_admin.php');
        exit();
    }
?>