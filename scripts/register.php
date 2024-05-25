<?php
session_start();
$errors = [];
$successMessage = "";

if (isset($_POST["email"])) {
    $fine = true;
    $name = $_POST['firstName'];
    $surname = $_POST['lastName'];
    $email = $_POST['email'];
    $emailS = filter_var($email, FILTER_SANITIZE_EMAIL);
    $pass1 = $_POST['password1'];
    $pass2 = $_POST['password2'];
    $passhash = password_hash($pass1, PASSWORD_DEFAULT);
    $secretkey = "6LcFomomAAAAAHfg3GSrse9LrfPD5h91WEIAgPx6";
    $check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretkey . '&response=' . $_POST['g-recaptcha-response']);
    $replay = json_decode($check, true);

    if (strlen($name) < 3 || strlen($name) > 20) {
        $fine = false;
        $errors[] = "Imię musi zawierać od 3 do 20 znaków.";
    }

    if (!ctype_alnum($name)) {
        $fine = false;
        $errors[] = "Imię musi zawierać tylko litery (bez polskich znaków).";
    }

    if (!ctype_alnum($surname)) {
        $fine = false;
        $errors[] = "Nazwisko musi składać się z liter (bez polskich znaków).";
    }

    if (!filter_var($emailS, FILTER_VALIDATE_EMAIL) || $emailS != $email) {
        $fine = false;
        $errors[] = "Podaj poprawny email.";
    }

    if (strlen($pass1) < 8) {
        $fine = false;
        $errors[] = "Hasło musi zawierać przynajmniej 8 znaków.";
    }

    if ($pass1 != $pass2) {
        $fine = false;
        $errors[] = "Podane hasła nie są identyczne.";
    }

    if (!isset($_POST['checkbox'])) {
        $fine = false;
        $errors[] = "Musisz zaakceptować regulamin.";
    }

    if (!$replay['success']) {
        $fine = false;
        $errors[] = "Potwierdź, że nie jesteś botem.";
    }

    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);
    try {
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        } else {
            $result = $conn->query("SELECT id_user FROM users WHERE email='$email'");
            if (!$result) throw new Exception($conn->error);
            $emailcount = $result->num_rows;
            if ($emailcount > 0) {
                $fine = false;
                $errors[] = "Podany email jest już zajęty.";
            }

            if ($fine) {
                if ($conn->query("INSERT INTO `users` (`id_user`, `firstName`, `lastName`, `email`, `pass`, `passwordrepeat`) VALUES (NULL, '$name', '$surname', '$email', '$passhash', '$passhash');")) {
                    $_SESSION['registersucced'] = true;
                    header('Location:login.php');
                } else {
                    throw new Exception($conn->error);
                }
            }

            $conn->close();
        }
    } catch (Exception $e) {
        $errors[] = 'Ups, coś poszło nie tak!';
    }
}
?>
