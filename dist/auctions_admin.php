<?php
    include_once "../scripts/connect.php";
    include_once "../scripts/filtr_auctions_admin.php";
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

        <!-- Tabela aukcji z ikonami strzałek do sortowania -->
        <table>
            <tr>
                <th><a href="?sortByAuctionID=<?php echo $sortByAuctionID === 'asc' ? 'desc' : 'asc'; ?>">ID aukcji <?php if ($sortByAuctionID === 'asc') echo "&#8593;"; else echo "&#8595;"; ?></a></th>
                <th><a href="?sortByUserID=<?php echo $sortByUserID === 'asc' ? 'desc' : 'asc'; ?>">ID użytkownika <?php if ($sortByUserID === 'asc') echo "&#8593;"; else echo "&#8595;"; ?></a></th>
                <th><a href="?sortByTitle=<?php echo $sortByTitle === 'asc' ? 'desc' : 'asc'; ?>">Tytuł <?php if ($sortByTitle === 'asc') echo "&#8593;"; else echo "&#8595;"; ?></a></th>
                <th>Opis</th>
                <th><a href="?sortByStartPrice=<?php echo $sortByStartPrice === 'asc' ? 'desc' : 'asc'; ?>">Cena początkowa <?php if ($sortByStartPrice === 'asc') echo "&#8593;"; else echo "&#8595;"; ?></a></th>
                <th>Akcje</th>
            </tr>
            <?php
            // Wyświetlanie posortowanych aukcji
            if (count($auctions) > 0) {
                foreach ($auctions as $row) {
                    echo "<tr>";
                    echo "<td>" . $row["id_auction"] . "</td>";
                    echo "<td>" . $row["id_user"] . "</td>";
                    echo "<td>" . $row["title"] . "</td>";
                    echo "<td>" . $row["descriptions"] . "</td>";
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