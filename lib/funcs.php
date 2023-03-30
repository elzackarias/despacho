<?php
function verify(string $uid): bool{
   include 'lib/config.php';
   //Verificamos si ya tiene una direccion registrada
   $query = mysqli_query($connect,"SELECT * FROM addresses WHERE user_id = '$uid'");
   return mysqli_num_rows($query) >= 1 ? true : false;
}

function getData(string $uid): array{
   include 'lib/config.php';
   $query = mysqli_query($connect,"SELECT firstname, lastname, user FROM users WHERE id = '$uid'");
   while($data = mysqli_fetch_assoc($query)){
      $arr = array(
         "firstname" => $data['firstname'],
         "lastname" => $data['lastname'],
         "user" => $data['user']
      );
   }  
   return $arr;
}
?>