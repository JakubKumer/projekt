<?php
session_start();
require_once "connect.php";

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $stmt = $conn->prepare("SELECT id_user, email, pass, id_role FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['pass'])) {
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['id_role'] = $user['id_role'];

            if ($user['id_role'] == 3) {
                header("Location: ../dist/admin.php"); 
            } else {
                header("Location: ../dist/loggin.php"); 
            }
            exit();
        } else {
            header("Location: ../dist/login.php?error=Nieprawidłowy email lub hasło");
            exit();
        }
    } catch (PDOException $e) {
        error_log("Error during login: " . $e->getMessage());
        header("Location: ../dist/login.php?error=Błąd serwera. Spróbuj ponownie później.");
        exit();
    }
}
?>