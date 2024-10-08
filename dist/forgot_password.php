<?php
session_start();
$errors = [];
$successMessage = "";

require_once "../scripts/connect.php"; // Połączenie z bazą danych PDO
require '../vendor/autoload.php'; // Załaduj Composer autoloader

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
                $resetLink = "http://localhost/projekt/projekt/dist/reset_password.php?token=" . $token;

                // Zapis tokenu i jego daty wygaśnięcia w bazie danych (ważny np. 1 godzinę)
                $query = $conn->prepare("UPDATE users SET reset_token = :token, token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = :email");
                $query->bindParam(":token", $token, PDO::PARAM_STR);
                $query->bindParam(":email", $email, PDO::PARAM_STR);
                $query->execute();

                // Skonfiguruj PHPMailer
                $mail = new PHPMailer(true);
                try {
                    // Ustawienia serwera SMTP
                    $mail->isSMTP();
                    $mail->Host = 'smtp.wp.pl'; // Ustaw swój serwer SMTP
                    $mail->SMTPAuth = true;
                    $mail->Username = 'BidHub@wp.pl'; // Twój adres e-mail
                    $mail->Password = 'Licytacja123'; // Hasło do skrzynki e-mail
                    $mail->SMTPSecure = 'tls'; // Zabezpieczenie (SSL/TLS)
                    $mail->Port = 587; // Port dla TLS

                    // Odbiorca
                    $mail->setFrom('BidHub@wp.pl', 'BidHub'); // Nadawca
                    $mail->addAddress($email); // Odbiorca (adres e-mail z formularza)

                    // Treść wiadomości
                    $mail->isHTML(true);
                    $mail->Subject = 'Resetowanie hasla';
                    $mail->Body    = "Kliknij w poniższy link, aby zresetować swoje hasło:<br><a href='$resetLink'>$resetLink</a><br>Link jest ważny przez 1 godzinę.";
                    $mail->AltBody = "Kliknij w poniższy link, aby zresetować swoje hasło:\n$resetLink\nLink jest ważny przez 1 godzinę.";

                    $mail->send();
                    $successMessage = "Na podany adres e-mail wysłaliśmy link do resetowania hasła.";
                } catch (Exception $e) {
                    $errors[] = "Nie udało się wysłać e-maila. Spróbuj ponownie później. Błąd: {$mail->ErrorInfo}";
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
                <div class="links">
                    <a href="login.php">Logowanie</a>                   
                </div>
            </form>
        </div>
    </div>
</body>
</html>
