<?php
session_start();
include_once "connect.php";
$errors = [];
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fine = true;

    // Retrieve and sanitize form inputs
    $name = trim($_POST['firstName']);
    $surname = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $emailS = filter_var($email, FILTER_SANITIZE_EMAIL);
    $pass1 = $_POST['password1'];
    $pass2 = $_POST['password2'];
    $passhash = password_hash($pass1, PASSWORD_DEFAULT);
    $defaultImagePath = '../uploads/user-3331256_1280.png';

    // Validations
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
    if ($pass1 !== $pass2) {
        $fine = false;
        $errors[] = "Podane hasła nie są identyczne.";
    }
    $stmt = $conn->prepare("SELECT id_user FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $emailcount = $stmt->rowCount();
    if ($emailcount > 0) {
        $fine = false;
        $errors[] = "Podany email jest już zajęty.";
    }

    // If all validations pass, insert into the database
    if ($fine) {
        try {
            $stmt = $conn->prepare("INSERT INTO users (firstName, lastName, email, pass,passwordrepeat ,profile_image) VALUES (:firstName, :lastName, :email, :passhash, :passhash, :profile_image)");
            $stmt->bindParam(':firstName', $name);
            $stmt->bindParam(':lastName', $surname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':passhash', $passhash);
            $stmt->bindParam(':profile_image', $defaultImagePath);

            $stmt->execute();
            $_SESSION['successMessage'] = "Użytkownik został dodany pomyślnie.";
        } catch (PDOException $e) {
            $errors[] = "Wystąpił błąd podczas dodawania użytkownika: " . $e->getMessage();
        }

    }
      // Store errors in session if there are any
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
    }

    // Redirect back to the form page
    header("Location: ../dist/add_admin_user.php");
    exit();
}
