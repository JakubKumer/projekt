<?php
session_start();
$errors = [];
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "../scripts/connect.php"; // Połączenie z bazą danych PDO
    require '../vendor/autoload.php'; // Załaduj Composer autoloader

    // Filtracja i walidacja danych
    $token = filter_var($_POST['token'], FILTER_SANITIZE_STRING);
    $newPassword1 = $_POST['newPassword1'];
    $newPassword2 = $_POST['newPassword2'];

    // Sprawdzenie, czy hasła są zgodne
    if ($newPassword1 !== $newPassword2) {
        $errors[] = "Podane hasła nie są zgodne.";
    }

    // Sprawdzenie długości hasła (np. min 8 znaków)
    if (strlen($newPassword1) < 8) {
        $errors[] = "Hasło musi mieć co najmniej 8 znaków.";
    }

    if (empty($errors)) {
        try {
            // Sprawdzenie tokenu i jego ważności
            $query = $conn->prepare("SELECT id_user, token_expiry FROM users WHERE reset_token = :token LIMIT 1");
            $query->bindParam(":token", $token, PDO::PARAM_STR);
            $query->execute();
            $user = $query->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Sprawdzenie, czy token jest jeszcze ważny
                $currentTime = date("Y-m-d H:i:s");
                if ($currentTime > $user['token_expiry']) {
                    $errors[] = "Token wygasł. Proszę spróbować ponownie.";
                } else {
                    // Zaktualizowanie hasła
                    $hashedPassword = password_hash($newPassword1, PASSWORD_DEFAULT);
                    $updateQuery = $conn->prepare("UPDATE users SET pass = :password, reset_token = NULL, token_expiry = NULL WHERE id_user = :id_user");
                    $updateQuery->bindParam(":password", $hashedPassword, PDO::PARAM_STR);
                    $updateQuery->bindParam(":id_user", $user['id_user'], PDO::PARAM_INT);
                    $updateQuery->execute();

                    $successMessage = "Hasło zostało pomyślnie zaktualizowane. Możesz teraz się zalogować.";
                }
            } else {
                $errors[] = "Nieprawidłowy token.";
            }
        } catch (PDOException $e) {
            $errors[] = "Wystąpił błąd podczas aktualizacji hasła: " . $e->getMessage();
        }
    }
} else if (isset($_GET['token'])) {
    // Pobranie tokenu z linku
    $token = $_GET['token'];
} else {
    $errors[] = "Brak tokenu.";
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/login2.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Zresetuj hasło</title>
</head>
<body>
    <div class="container">
        <h1>BidHub</h1>

        <?php if(!empty($errors)){ ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 w-auto m-auto lg:w-1/3" role="alert">
                <p class="font-bold">Błąd</p>
                <ul>
                    <?php foreach($errors as $error){ ?>
                        <li><?php echo $error; ?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>

        <?php if (!empty($successMessage)) { ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 w-auto m-auto lg:w-1/3" role="alert">
                <p class="font-bold">Sukces</p>
                <p><?php echo $successMessage; ?></p>
            </div>
        <?php } ?>

        <div class="box m-auto">
            <span class="borderLine"></span>
            <form method="POST" action="reset_password.php" autocomplete="off">
                <h2>Resetuj hasło</h2>
                <div class="inputBox">
                    <input type="password" name="newPassword1" id="newPassword1" required>
                    <span>Nowe hasło</span>
                    <i class="fa-solid fa-lock"></i>
                </div>
                <div class="inputBox">
                    <input type="password" name="newPassword2" id="newPassword2" required>
                    <span>Potwierdź hasło</span>
                    <i class="fa-solid fa-lock"></i>
                </div>
                <input type="hidden" name="token" value="<?php echo $token; ?>">
                <input type="submit" value="Zresetuj hasło" name="submit" id="submit">
                <div class="links">
                    <a href="login.php">Logowanie</a>                   
                </div>
            </form>
        </div>
    </div>
</body>
</html>
