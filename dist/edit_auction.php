<?php 
    include_once "../scripts/edit_auction.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/edit_auction.css">
    <title>Document</title>
</head>
<body>
<div class="container">
        <div class="header">
            <h2>Edytuj ogłoszenie</h2>
            <p>ID Ogłoszenia: <?php echo $id; ?></p>
            <p>ID Użytkownika: <?php echo $id_user; ?></p>
        </div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <label for="title">Tytuł:</label>
            <input type="text" name="title" id="title" value="<?php echo $title; ?>" required><br>

            <label for="description">Opis:</label>
            <textarea name="description" id="description" rows="5" required><?php echo $description; ?></textarea><br>

            <label for="start_price">Cena początkowa:</label>
            <input type="number" name="start_price" id="start_price" value="<?php echo $start_price; ?>" required><br>

            <input type="submit" value="Zapisz zmiany">
        </form>
    </div>
</body>
</html>
