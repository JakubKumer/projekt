<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/register.css">
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <title>Rejestracja</title>
</head>
<body>
    <div class="container">
        <h1>Sklep internetowy</h1>
        <div class="box">
            <span class="borderLine"></span>
            <form method="POST" action="../skrypt/register.php" autocomplete="off">
                <h1>Rejestracja</h1>
                <div class="inputBox">
                    <input type="text" name="firstName" id="firstName" required="required">
                    <span>Imię</span>
                    <i class="fa-solid fa-user"></i>                    
                </div>
                <div class="inputBox">                    
                    <input type="text" name="lastName" id="lastName" required="required">
                    <span>Nazwisko</span>
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="inputBox">                   
                    <input type="text" name="email" id="email" required="required">
                    <span>Email</span>
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <div class="inputBox">                    
                    <input type="password" name="password1" id="password1" required="required">
                    <span>Hasło</span>
                    <i class="fa-solid fa-lock"></i>
                </div>
                <div class="inputBox">       
                    <input type="password" name="password2" id="password2" required="required">
                    <span>Powtórz hasło</span>
                    <i class="fa-solid fa-lock"></i>   
                </div>
                <div class="checkbox-container">
                    <input type="checkbox" name="checkbox" id="checkbox">
                    <span>Akceptuję regulamin</span>
                </div>    
                <div class="g-recaptcha" data-sitekey="6LcFomomAAAAAJhwaC5dkkcCNcaBDahjIbH-w5-s"></div>
                <input type="submit" name="submit" id="submit" value="Zarejestruj">
            </form>    
        </div>
    </div>
</body>
</html>