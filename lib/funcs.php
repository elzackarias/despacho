<?php
function verify(string $uid): bool{
   include 'lib/config.php';
   //Verificamos si ya tiene una direccion registrada
   $query = mysqli_query($connect,"SELECT * FROM addresses WHERE user_id = '$uid'");
   return mysqli_num_rows($query) >= 1 ? true : false;
}

function getData(string $uid): array{
   include 'lib/config.php';
   $query = mysqli_query($connect,"SELECT firstname, lastname, user, role FROM users WHERE id = '$uid'");
   while($data = mysqli_fetch_assoc($query)){
      $arr = array(
         "firstname" => $data['firstname'],
         "lastname" => $data['lastname'],
         "user" => $data['user'],
         "role" => $data['role']
      );
   }  
   return $arr;
}
function getAppointments(){
   include 'lib/config.php';
   $query = mysqli_query($connect,"SELECT id,date_start,mode FROM appointments");
   $arr=[];
   $colors = ["a0D6EF6","a454655","aA0A6DC","aFF617D"];
   while($data = mysqli_fetch_assoc($query)){
       array_push($arr,array("id" => $data['id'], "start" => $data['date_start'],"title" => 'Cita #'.$data['id'], "classNames" => $colors[array_rand($colors)],"color"=>'white'));
   }
   return json_encode($arr);
}
function getHour($date){
   $date = strtotime($date);
   return date('H', $date);
}
function getMyAppointments($uid){
   include 'lib/config.php';
   $dia = date('Y-m-d');
   $query = mysqli_query($connect, "SELECT id,date_start,mode,subject FROM appointments WHERE DATE(date_start) >= DATE('$dia') AND user_id = '$uid'");
   $arr = [];
   while ($data = mysqli_fetch_assoc($query)) {
       array_push($arr, array("id" => $data['id'], "start" => $data['date_start'], "mode" => $data['mode'], "subject" => $data['subject']));
   }
   return $arr;
}
function getUser($id){
   include 'lib/config.php';
   $q = mysqli_query($connect,"SELECT firstname,lastname FROM users WHERE id = '$id'");
   $data = mysqli_fetch_assoc($q);
   return $data['firstname'].' '.$data['lastname'];
}

function getAddress($uid){
   include 'lib/config.php';
   $query = mysqli_query($connect,"SELECT * FROM addresses WHERE user_id = '$uid'");
   while($data = mysqli_fetch_assoc($query)){
      $arr = array(
         "street" => $data['street'],
         "colony" => $data['colony'],
         "city" => $data['city'],
         "zipcode" => $data['zipcode']
      );
   }  
   return $arr;
}
?>