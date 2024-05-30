<?php 
    include_once "../scripts/edit.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/edit.css">
    <title>Document</title>
</head>
<body>
<div class="container">
    <h2>Edytuj użytkownika</h2>
    <p>ID Użytkownika: <?php echo htmlspecialchars($_GET["id"]); ?></p>
    <form action="../scripts/update.php" method="POST">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($_GET["id"]); ?>">

        <label for="firstName">Imię:</label>
        <input type="text" name="firstName" value="<?php echo htmlspecialchars($firstName); ?>" required><br>

        <label for="lastName">Nazwisko:</label>
        <input type="text" name="lastName" value="<?php echo htmlspecialchars($lastName); ?>" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br>

        <input type="submit" value="Zapisz zmiany">
    </form>
</div>  
</body>
</html>
