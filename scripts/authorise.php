<?php
include_once 'connect.php';
if(isset($_POST['email'])&&isset($_POST['password'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    if(empty($_POST['email'])){
        header("Location: ../dist/login.php?error=Podaj email");
    }
    else if(empty($_POST['password'])){
        header("Location: ../dist/login.php?error=Podaj Hasło");
    }
    else{
        $stmt = $conn -> prepare("SELECT * FROM users WHERE email = $email");
        $stmt -> execute([$email]);
        if($stmt -> rowCount()===1){
            
        }else{
            header("Location: ../dist/login.php? error= nieprawidłowy email lub hasło");
        }
    }
}
?>