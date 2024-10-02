<?php
session_start();
$errors = [];
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "../config/db.php"; // Połączenie z bazą danych PDO

    // Filtracja i walidacja e-maila
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Podaj poprawny adres e-mail.";
    } else {
        try {
            // Sprawdzenie, czy użytkownik istnieje w bazie danych
            $query = $conn->prepare("SELECT id_user FROM users WHERE email = :email LIMIT 1");
            $query->bindParam(":email", $email, PDO::PARAM_STR);
            $query->execute();
            $user = $query->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Generowanie unikalnego tokenu resetującego hasło
                $token = bin2hex(random_bytes(50));
                $resetLink = "http://yourdomain.com/reset_password.php?token=" . $token;

                // Zapis tokenu i jego daty wygaśnięcia w bazie danych (ważny np. 1 godzinę)
                $query = $conn->prepare("UPDATE users SET reset_token = :token, token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = :email");
                $query->bindParam(":token", $token, PDO::PARAM_STR);
                $query->bindParam(":email", $email, PDO::PARAM_STR);
                $query->execute();

                // Wyślij e-mail do użytkownika z linkiem do resetowania hasła
                $subject = "Resetowanie hasła";
                $message = "Kliknij w poniższy link, aby zresetować swoje hasło:\n\n$resetLink\n\nLink jest ważny przez 1 godzinę.";
                $headers = "From: no-reply@yourdomain.com\r\n";
                $headers .= "Reply-To: no-reply@yourdomain.com\r\n";
                $headers .= "Content-Type: text/plain; charset=utf-8\r\n";

                if (mail($email, $subject, $message, $headers)) {
                    $successMessage = "Na podany adres e-mail wysłaliśmy link do resetowania hasła.";
                } else {
                    $errors[] = "Nie udało się wysłać e-maila. Spróbuj ponownie później.";
                }
            } else {
                // Komunikat, jeśli użytkownik nie istnieje
                $errors[] = "Nie ma użytkownika z takim adresem e-mail.";
            }
        } catch (PDOException $e) {
            // Obsługa błędów PDO
            $errors[] = "Wystąpił błąd. Spróbuj ponownie później.";
        }
    }
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
    <title>Zapomniane hasło</title>
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
            <form method="POST" action="forgot_password.php" autocomplete="off">
                <h2>Odzyskaj hasło</h2>
                <div class="inputBox">
                    <input type="text" name="email" id="email" required>
                    <span>E-mail</span>
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <input type="submit" value="Wyślij link resetujący" name="submit" id="submit">
            </form>
        </div>
    </div>
</body>
</html>
