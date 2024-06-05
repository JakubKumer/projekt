<?php
    session_start();
    if(isset($_SESSION['id_user']) && isset($_SESSION['email'])){
        ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../src/output.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
<header class="bg-blue-950">
        <div class=" container w-4/5 m-auto bg-blue-950 flex justify-around  p-8">
            <div class="text-white "><a href="">logo</a></div>
            <div class=""><input class="bg-slate-400 text-black rounded-md" type="text" placeholder="" value="text"></div>
            <div id="dropdownButton" class=" text-black font-bold">
                <div onclick="myDropdown()" class="block w-12 h-12 rounded-full overflow-hidden border-2 border-blue-300 focus:outline-none focus:border-white">
                    <img class="h-full w-full object-cover" src="https://cdn.pixabay.com/photo/2017/03/04/20/50/pale-2116960_640.jpg" alt="">
                </div>
                <div id="dropdown" class="absolute bg-blue-300 rounded-lg p-2 mt-1 hidden w-40">
                    <p class="block px-4 py-2 text-gray-800 hover:bg-indigo-500 hover:text-white" href="">Witaj <?=$_SESSION['firstName']?></p>
                    <a class="block px-4 py-2 text-gray-800 hover:bg-indigo-500 hover:text-white" href="user_profile.php">Mój profil</a>
                    <a class="block px-4 py-2 text-gray-800 hover:bg-indigo-500 hover:text-white" href="../scripts/logout.php">Wyloguj</a>          
                </div>
            </div>
        </div>
    </header>
    <div class="container w-1/4 m-auto ">
        <p><?=$_SESSION['firstName']?></p>
        <p><?=$_SESSION['lastName']?></p>
        <div class="bg-blue-500 w-5"><a href="../scripts/logout.php">Wyloguj</a></div>
    </div>
    <main class="container w-4/5 m-auto flex">
        <aside class="bg-gray-200 w-1/4 mt-20 mr-5 h-48 ">
            <ul class="text-center p-3">
                <li class="m-1 hover:underline  hover:text-blue-900"><a href="#">dadasda</a></li>
                <li class="m-1 hover:underline  hover:text-blue-900"><a href="#">dadasda</a></li>
                <li class="m-1 hover:underline  hover:text-blue-900"><a href="#">dadasda</a></li>
                <li class="m-1 hover:underline  hover:text-blue-900"><a href="#">dadasda</a></li>
                <li class="m-1 hover:underline  hover:text-blue-900"><a href="#">dadasda</a></li>
            </ul>
        </aside>
        <div class="w-3/4 mt-20">
            <ul>
                <li class=" bg-gray-200 m-1 h-auto rounded-lg hover:shadow-blue-900 hover:shadow-lg hover:bg-slate-300">
                    <a href="#">
                        <div class="flex justify-between p-2 ">
                            <img class="w-1/3 h-4/5 rounded-lg" src="https://cdn.pixabay.com/photo/2024/05/15/08/23/bird-8763079_640.jpg" alt="">
                            <p class="w-1/3 ml-5 ">title</p>
                            <p class="w-1/3">cena</p>
                        </div>
                    </a>
                </li>
                <li class=" bg-gray-200 m-1 h-auto rounded-lg hover:shadow-blue-900 hover:shadow-lg hover:bg-slate-300">
                    <a href="#">
                        <div class="flex justify-between p-2 ">
                            <img class="w-1/3 h-4/5 rounded-lg" src="https://cdn.pixabay.com/photo/2024/05/15/08/23/bird-8763079_640.jpg" alt="">
                            <p class="w-1/3 ml-5 ">title</p>
                            <p class="w-1/3">cena</p>
                        </div>
                    </a>
                </li>
                <li class=" bg-gray-200 m-1 h-auto rounded-lg hover:shadow-blue-900 hover:shadow-lg hover:bg-slate-300">
                    <a href="#">
                        <div class="flex justify-between p-2 ">
                            <img class="w-1/3 h-4/5 rounded-lg" src="https://cdn.pixabay.com/photo/2024/05/15/08/23/bird-8763079_640.jpg" alt="">
                            <p class="w-1/3 ml-5 ">title</p>
                            <p class="w-1/3">cena</p>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </main>
    <script src="../js/dropdown.js"></script>
</body>
</html>
<?php
    }else{
        header("Location: loggin.php");
    }
?>