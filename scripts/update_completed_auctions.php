<?php
session_start();
include_once "connect.php";

// Sprawdzenie uprawnień administratora
if (!isset($_SESSION['id_user']) || $_SESSION['id_role'] != 3) {
    header("Location: ../admin.php?error=Brak+uprawnień");
    exit();
}
$currentDate = new DateTime();

try {
    // Pobierz ID aukcji, które się zakończyły
    $query = "
        SELECT id_auction 
        FROM auctions 
        WHERE end_time < :current_date
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute([':current_date' => $currentDate->format('Y-m-d H:i:s')]);
    
    $expiredAuctions = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Usuń odpowiednie wpisy z tabeli favorites, jeśli istnieją zakończone aukcje
    if (!empty($expiredAuctions)) {
        $deleteQuery = "
            DELETE FROM favorites 
            WHERE id_auction IN (" . implode(',', $expiredAuctions) . ")
        ";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->execute();
        echo "Usunięto wszystkie obserwacje dla zakończonych aukcji.";
    } else {
        echo "Nie znaleziono zakończonych aukcji do usunięcia.";
    }
} catch (Exception $e) {
    echo "Wystąpił błąd: " . $e->getMessage();
}

try {
    // Rozpocznij transakcję
    $conn->beginTransaction();

    // Przeniesienie aukcji z przyszłym end_time do tabeli completed_auctions
    $stmtInsert = $conn->prepare("INSERT INTO completed_auctions (id, title, image, description, end_time, price, status, id_user, highest_bidder_id, payment_status, delivery_status, is_send)
                              SELECT id_auction, title, image, description, end_time, start_price, 'completed', id_user, IFNULL(highest_bidder_id, NULL),'niezaplacone', 'nieodebrane', 'nie'
                              FROM auctions
                              WHERE end_time <= NOW()");
    $stmtInsert->execute();

    // Sprawdzenie, czy jakiekolwiek wiersze zostały przeniesione
    if ($stmtInsert->rowCount() > 0) {
        // Usunięcie przeniesionych aukcji z tabeli auctions
        $stmtDelete = $conn->prepare("DELETE FROM auctions WHERE end_time <= NOW()");
        $stmtDelete->execute();

        // Zatwierdź transakcję
        $conn->commit();
        header("Location: ../dist/finished_auction_admin.php?success=Pomyślnie+zaktualizowano+" . $stmtInsert->rowCount() . "+aukcji");
    } else {
        $conn->rollBack();
        header("Location: ../dist/finished_auction_admin.php?error=Brak+aukcji+do+zaktualizowania");
    }
    exit();

} catch (Exception $e) {
    $conn->rollBack();
    header("Location: ../dist/finished_auction_admin.php?error=Wystąpił+błąd+podczas+aktualizacji+aukcji". urlencode("Błąd: " . $e->getMessage()));
    exit();
}
?>