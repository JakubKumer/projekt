<?php
if (isset($_GET["id"])) {
    // Tworzenie połączenia z bazą danych    
   include_once "connect.php";
   $id = $_GET["id"];

   try {
       // Usuwanie użytkownika o podanym identyfikatorze
       $sql = "DELETE FROM users WHERE id_user = :id";
       $stmt = $conn->prepare($sql);
       $stmt->bindParam(':id', $id, PDO::PARAM_INT);

       if ($stmt->execute()) {
           header("Location: ../dist/admin.php");
           exit();
       } else {
           echo "Błąd podczas usuwania użytkownika.";
       }
   } catch (PDOException $e) {
       echo "Błąd podczas usuwania użytkownika: " . $e->getMessage();
   }
} 
else {
   echo "Nieprawidłowy identyfikator użytkownika.";
}

?>
