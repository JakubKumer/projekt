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
        // Optional: Log the error message for debugging
        // error_log($e->getMessage());
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: ../dist/register.php');
        exit();
    }
}

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
                <div class="g-recaptcha" data-sitekey="6LcFomomAAAAAJhwaC5dkkcCNcaBDahjIbH-w5-s"></div>
                <input type="submit" name="submit" id="submit" value="Zarejestruj">
            </form>    
        </div>
    </div>
</body>
</html>