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
    $secretkey = "6LexgokqAAAAAFhdt8_dRiMFDCgpFSOcYOh2TZzy";
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
        if (isset($replay['error-codes'])) {
            $errors[] = "Błąd reCAPTCHA: " . implode(', ', $replay['error-codes']);
        }
    }

    require_once "connect.php";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT id_user FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $emailcount = $stmt->rowCount();
        if ($emailcount > 0) {
            $fine = false;
            $errors[] = "Podany email jest już zajęty.";
        }

        if ($fine) {
            $defaultImagePath = '../uploads/user-3331256_1280.png';
            
            $stmt = $conn->prepare("INSERT INTO users (firstName, lastName, email, pass, passwordrepeat, profile_image) VALUES (:name, :surname, :email, :passhash, :passhash, :profile_image)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':surname', $surname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':passhash', $passhash);
            $stmt->bindParam(':profile_image', $defaultImagePath); 

            if ($stmt->execute()) {
                $_SESSION['registersucced'] = true;
                $_SESSION['success'] = "Rejestracja zakończona sukcesem. Możesz się zalogować.";
                header('Location: ../dist/register.php');
                exit();
            } else {
                throw new Exception("Insert query failed");
            }
        }
    } catch (PDOException $e) {
        $errors[] = 'Ups, coś poszło nie tak!';

    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: ../dist/register.php');
        exit();
    }
}
?>