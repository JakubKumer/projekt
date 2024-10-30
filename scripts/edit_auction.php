<?php
session_start();
    include_once "../scripts/connect.php";
    
    // Sprawdź, czy użytkownik jest zalogowany oraz czy ma uprawnienia administratora
    if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 3) {
        header("location: login.php");
        exit;
    }
    
    // Pobierz ID aukcji do edycji
    $id_auction = isset($_GET['id_auction']) ? (int)$_GET['id_auction'] : 0;
    $errors = [];
    $successMessage = '';
    
    try {
        // Pobierz dane aukcji
        $sql = "SELECT * FROM auctions WHERE id_auction = :id_auction";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_auction', $id_auction, PDO::PARAM_INT);
        $stmt->execute();
        $auction = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$auction) {
            echo "Aukcja o podanym ID nie istnieje.";
            exit;
        }
    } catch (PDOException $e) {
        echo "Błąd bazy danych: " . $e->getMessage();
        exit;
    }
    
    // Obsługa formularza aktualizacji aukcji
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $category = (int)$_POST['category'] ?? 0;
    
        if (empty($title) || empty($description)) {
            $errors[] = "Tytuł i opis są wymagane.";
        }
    
        if (!empty($_FILES['image']['name'])) {
            $imagePath = '../uploads/' . basename($_FILES['image']['name']);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                // Przesyłanie obrazu zakończone sukcesem
            } else {
                $errors[] = "Błąd przesyłania pliku.";
            }
        } else {
            $imagePath = $auction['image'];
        }
    
        // Aktualizacja aukcji, jeśli brak błędów
        if (empty($errors)) {
            try {
                $updateSql = "UPDATE auctions 
                              SET title = :title, description = :description, id_category = :category, image = :image 
                              WHERE id_auction = :id_auction";
                $stmt = $conn->prepare($updateSql);
                $stmt->execute([
                    ':title' => $title,
                    ':description' => $description,
                    ':category' => $category,
                    ':image' => $imagePath,
                    ':id_auction' => $id_auction
                ]);
                $successMessage = "Aukcja została pomyślnie zaktualizowana.";
            } catch (PDOException $e) {
                $errors[] = "Błąd przy aktualizacji aukcji: " . $e->getMessage();
            }
        }
    }