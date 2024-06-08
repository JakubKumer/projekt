<?php
include_once "connect.php";
$sortByID = isset($_GET['sortByID']) ? $_GET['sortByID'] : '';
$sortByFirstName = isset($_GET['sortByFirstName']) ? $_GET['sortByFirstName'] : '';
$sortByLastName = isset($_GET['sortByLastName']) ? $_GET['sortByLastName'] : '';
$sortByEmail = isset($_GET['sortByEmail']) ? $_GET['sortByEmail'] : '';

function sortByColumn(&$arr, $col, $asc) {
    $sort_dir = $asc === 'asc' ? SORT_ASC : SORT_DESC;
    array_multisort(array_column($arr, $col), $sort_dir, $arr);
}

$stmt = $conn->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($sortByID) {
    sortByColumn($users, 'id_user', $sortByID);
}
if ($sortByFirstName) {
    sortByColumn($users, 'firstName', $sortByFirstName);
}
if ($sortByLastName) {
    sortByColumn($users, 'lastName', $sortByLastName);
}
if ($sortByEmail) {
    sortByColumn($users, 'email', $sortByEmail);
}
?>