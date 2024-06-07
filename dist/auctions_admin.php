<?php
    include_once "../scripts/connect.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/auctions_admin.css">
    <title>Document</title>
</head>
<body>
<div class="sidebar">
        <a href="admin.php"><i class="fa fa-fw fa-user"></i> Użytkownicy</a>
        <a href="auctions_admin.php"><i class="fa fa-clipboard"></i> Aukcje</a>
        <a href="category_admin.php"><i class="fa fa-clipboard"></i> Kategorie</a>
    </div>

    <div class="main">
        <h1>Panel Administratora</h1>
        <h2>Aukcje</h2>

        <!-- Tabela aukcji -->
        <table>
            <tr>
                <th>ID aukcji</th>
                <th>ID użytkownika</th>
                <th>Tytuł</th>
                <th>Opis</th>
                <th>Cena początkowa</th>
                <th>Akcje</th>
            </tr>
            <?php
            // Pobieranie aukcji z bazy danych
            $stmt = $conn->prepare("SELECT * FROM auctions");
            $stmt->execute();
            $auctions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($auctions)>0) {
                foreach ($auctions as $row) {
                    echo "<tr>";
                    echo "<td>" . $row["id_auction"] . "</td>";
                    echo "<td>" . $row["id_user"] . "</td>";
                    echo "<td>" . $row["title"] . "</td>";
                    echo "<td>" . $row["description"] . "</td>";
                    echo "<td>" . $row["start_price"] . "</td>";
                    echo "<td><a href='edit_auction.php?id=" . $row["id_auction"] . "'>Edytuj</a> | <a href='../scripts/delete_auctions.php?id=" . $row["id_auction"] . "'>Usuń</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Brak aukcji.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>