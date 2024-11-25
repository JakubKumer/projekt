<?php
session_start();
include_once "../scripts/connect.php";
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$successMessage = isset($_SESSION['success']) ? $_SESSION['success'] : "";
unset($_SESSION['errors']);
unset($_SESSION['success']);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/register.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <title>Rejestracja</title>
</head>
<body>
    <div class="container">
        <h1>BidHub</h1>
        <?php if(!empty($errors)){?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 w-auto m-auto lg:w-1/3" role="alert">
                <p class="font-bold">Błąd</p>
                <ul><?php foreach($errors as $error){?>
                   <li> <?php echo $error; ?> <?php } ?></li>
                </ul>
            </div>  
       <?php }
        ?>
        <?php if (!empty($successMessage)) { ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 w-auto m-auto lg:w-1/3" role="alert">
                <p class="font-bold">Sukces</p>
                <p><?php echo $successMessage; ?></p>
            </div>
        <?php } ?>
        <div class="box m-auto ">
            <span class="borderLine"></span>
            <form method="POST" action="../scripts/register.php" autocomplete="off">
                <h1>Rejestracja</h1>
                <div class="inputBox">
                    <input type="text" name="firstName" id="firstName">
                    <span>Imię</span>
                    <i class="fa-solid fa-user"></i>                    
                </div>
                <div class="inputBox">                    
                    <input type="text" name="lastName" id="lastName">
                    <span>Nazwisko</span>
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="inputBox">                   
                    <input type="text" name="email" id="email">
                    <span>Email</span>
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <div class="inputBox">                    
                    <input type="password" name="password1" id="password1">
                    <span>Hasło</span>
                    <i class="fa-solid fa-lock"></i>
                </div>
                <div class="inputBox">       
                    <input type="password" name="password2" id="password2">
                    <span>Powtórz hasło</span>
                    <i class="fa-solid fa-lock"></i>   
                </div>
                <div class="checkbox-container">
                    <input type="checkbox" name="checkbox" id="checkbox">
                    <span>Akceptuję regulamin</span>
                </div>    
                <div class="g-recaptcha" data-sitekey="6LexgokqAAAAAF7MHc0tpyye-i8B2TkqiAD4pXfD"></div>
                <input type="submit" name="submit" id="submit" value="Zarejestruj">
                <div class="links">
                    <a href="login.php">Logowanie</a>                   
                </div>
            </form>    
        </div>
    </div>
</body>
</html>