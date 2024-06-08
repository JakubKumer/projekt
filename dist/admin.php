<?php
    include_once "../scripts/connect.php";
    include_once "../scripts/admin_filtr.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../src/admin.css">
</head>
<body>
<div class="sidebar">
        <a href="admin.php"><i class="fa fa-fw fa-user"></i> Użytkownicy</a>
        <a href="auctions_admin.php"><i class="fa fa-clipboard"></i> Aukcje</a>
        <a href="category_admin.php"><i class="fa fa-clipboard"></i> Kategorie</a>
    </div>
    <div class="main">
        <h1>Panel Administratora</h1>
        
         <!-- Formularz dodawania użytkownika -->
         <h2>Dodaj użytkownika</h2>
        <form action="add.php" method="POST">
            <div class="add_wyglad">
                <label for="firstName">Imię:</label>
                <input type="text" name="firstName" id="firstName" required><br>
            </div>
            <div class="add_wyglad">
                <label for="lastName">Nazwisko:</label>
                <input type="text" name="lastName" id="lastName" required><br>
            </div>
            <div class="add_wyglad">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required><br>
            </div>
            <div class="add_wyglad">
                <label for="pass">Hasło:</label>
                <input type="password" name="pass" id="pass" required><br>
            </div>
            <div class="add_wyglad">
                <label for="passwordrepeat">Powtórz hasło:</label>
                <input type="password" name="passwordrepeat" id="passwordrepeat" required><br>
            </div>
            <div class="add_wyglad">
                <input class="add_button" type="submit" value="Dodaj użytkownika">
            </div>
        </form>

        <h2 id="uzytkownicy">Użytkownicy</h2>

        <!-- Tabela użytkowników z ikonami strzałek do sortowania -->
        <table>
            <tr>
                <th><a href="?sortByID=<?php echo $sortByID === 'asc' ? 'desc' : 'asc'; ?>">ID użytkownika <?php if ($sortByID === 'asc') echo "&#8593;"; else echo "&#8595;"; ?></a></th>
                <th><a href="?sortByFirstName=<?php echo $sortByFirstName === 'asc' ? 'desc' : 'asc'; ?>">Imię <?php if ($sortByFirstName === 'asc') echo "&#8593;"; else echo "&#8595;"; ?></a></th>
                <th><a href="?sortByLastName=<?php echo $sortByLastName === 'asc' ? 'desc' : 'asc'; ?>">Nazwisko <?php if ($sortByLastName === 'asc') echo "&#8593;"; else echo "&#8595;"; ?></a></th>
                <th><a href="?sortByEmail=<?php echo $sortByEmail === 'asc' ? 'desc' : 'asc'; ?>">Email <?php if ($sortByEmail === 'asc') echo "&#8593;"; else echo "&#8595;"; ?></a></th>
                <th>Akcje</th>
            </tr>
        <?php
            // Wyświetlanie posortowanych użytkowników
            if (count($users) > 0) {
                foreach ($users as $row) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["id_user"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["firstName"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["lastName"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                    echo "<td><a href='edit.php?id=" . htmlspecialchars($row["id_user"]) . "'>Edytuj</a> | <a href='../scripts/delete.php?id=" . htmlspecialchars($row["id_user"]) . "'>Usuń</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Brak użytkowników.</td></tr>";
            }
        ?>
        </table>

       
    </div>
</body>
</html>