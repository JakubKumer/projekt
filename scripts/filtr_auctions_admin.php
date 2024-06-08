<?php
    include_once "../scripts/connect.php";

    // Pobranie danych z URL, jeśli istnieją
    $sortByAuctionID = isset($_GET['sortByAuctionID']) ? $_GET['sortByAuctionID'] : '';
    $sortByUserID = isset($_GET['sortByUserID']) ? $_GET['sortByUserID'] : '';
    $sortByTitle = isset($_GET['sortByTitle']) ? $_GET['sortByTitle'] : '';
    $sortByStartPrice = isset($_GET['sortByStartPrice']) ? $_GET['sortByStartPrice'] : '';

    // Funkcja sortująca dla tablicy aukcji
    function sortByColumn(&$arr, $col, $asc) {
        $sort_dir = $asc === 'asc' ? SORT_ASC : SORT_DESC;
        array_multisort(array_column($arr, $col), $sort_dir, $arr);
    }

    // Pobieranie aukcji z bazy danych
    $stmt = $conn->prepare("SELECT * FROM auctions");
    $stmt->execute();
    $auctions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Sortowanie danych, jeśli zdefiniowane są parametry sortowania
    if ($sortByAuctionID) {
        sortByColumn($auctions, 'id_auction', $sortByAuctionID);
    }
    if ($sortByUserID) {
        sortByColumn($auctions, 'id_user', $sortByUserID);
    }
    if ($sortByTitle) {
        sortByColumn($auctions, 'title', $sortByTitle);
    }
    if ($sortByStartPrice) {
        sortByColumn($auctions, 'start_price', $sortByStartPrice);
    }
?>