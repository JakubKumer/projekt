<?php
session_start();
include_once 'connect.php';
if(isset($_POST['email'])&&isset($_POST['password'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    if(empty($_POST['email'])){
        header("Location: ../dist/login.php?error=Podaj email");
        exit();
    }
    else if(empty($_POST['password'])){
        header("Location: ../dist/login.php?error=Podaj Hasło");
        exit();
    }
    else{
        $stmt = $conn -> prepare("SELECT * FROM users WHERE email = ?");
        $stmt -> execute([$email]);
        if($stmt -> rowCount()===1){
            
            $user = $stmt->fetch();
            $user_id = $user['id_user'];
            $user_email = $user['email'];
            $user_password = $user['pass'];
            $user_name = $user['firstName'];
            $user_surname = $user['lastName'];
            
            if ($email === $user_email){    
                if(password_verify($password, $user_password)){
                    $_SESSION['id_user'] =  $user_id;
                    $_SESSION['email'] =  $user_email;
                    $_SESSION['pass'] =  $user_password;
                    $_SESSION['firstName'] =  $user_name;
                    $_SESSION['lastName'] =  $user_surname;
                    header("Location: ../dist/loggin.php");
                } else{
                    header("Location: ../dist/login.php? error= nieprawidłowe hasło");
                }     
            }else{
                header("Location: ../dist/login.php? error= nieprawidłowy email");
            }
        }
        else{
            header("Location: ../dist/login.php? error= nieprawidłowy email lub hasło");
            exit();
        }
    }
}
?>