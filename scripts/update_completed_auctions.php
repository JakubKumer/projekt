<?php
session_start();
include_once "connect.php";

// Sprawdzenie uprawnień administratora
if (!isset($_SESSION['id_user']) || $_SESSION['id_role'] != 3) {
    header("Location: ../admin.php?error=Brak+uprawnień");
    exit();
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
        // Cofnięcie zmian, jeśli nie było wierszy do przeniesienia
        $conn->rollBack();
        header("Location: ../dist/finished_auction_admin.php?error=Brak+aukcji+do+zaktualizowania");
    }
    exit();

} catch (Exception $e) {
    // Cofnięcie zmian w przypadku błędu
    $conn->rollBack();
    header("Location: ../dist/finished_auction_admin.php?error=Wystąpił+błąd+podczas+aktualizacji+aukcji". urlencode("Błąd: " . $e->getMessage()));
    exit();
}
?>