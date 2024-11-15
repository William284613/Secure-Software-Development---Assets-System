<?php
 $errors = array();

 /*--------------------------------------------------------------*/
 /* Function for Remove escapes special
 /* characters in a string for use in an SQL statement
 /*--------------------------------------------------------------*/
function real_escape($str){
  global $con;
  $escape = mysqli_real_escape_string($con,$str);
  return $escape;
}
/*--------------------------------------------------------------*/
/* Function for Remove html characters
/*--------------------------------------------------------------*/
function remove_junk($str){
  $str = nl2br($str);
  $str = htmlspecialchars(strip_tags($str, ENT_QUOTES));
  return $str;
}
/*--------------------------------------------------------------*/
/* Function for Uppercase first character
/*--------------------------------------------------------------*/
function first_character($str){
  $val = str_replace('-'," ",$str);
  $val = ucfirst($val);
  return $val;
}
/*--------------------------------------------------------------*/
/* Function for Checking input fields not empty
/*--------------------------------------------------------------*/
function validate_fields($var){
  global $errors;
  foreach ($var as $field) {
    $val = remove_junk($_POST[$field]);
    if(isset($val) && $val==''){
      $errors = $field ." can't be blank.";
      return $errors;
    }
  }
}
/*--------------------------------------------------------------*/
/* Function for Display Session Message
   Ex echo displayt_msg($message);
/*--------------------------------------------------------------*/
function display_msg($msg =''){
   $output = array();
   if(!empty($msg)) {
      foreach ($msg as $key => $value) {
         $output  = "<div class=\"alert alert-{$key}\">";
         $output .= "<a href=\"#\" class=\"close\" data-dismiss=\"alert\">&times;</a>";
         $output .= remove_junk(first_character($value));
         $output .= "</div>";
      }
      return $output;
   } else {
     return "" ;
   }
}
/*--------------------------------------------------------------*/
/* Function for redirect
/*--------------------------------------------------------------*/
function redirect($url, $permanent = false)
{
    if (headers_sent() === false)
    {
      header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
    }

    exit();
}
/*--------------------------------------------------------------*/
/* Function for find out total saleing price, buying price and profit
/*--------------------------------------------------------------*/
function total_price($totals){
   $sum = 0;
   $sub = 0;
   foreach($totals as $total ){
     $sum += $total['total_saleing_price'];
     $sub += $total['total_buying_price'];
     $profit = $sum - $sub;
   }
   return array($sum,$profit);
}
/*--------------------------------------------------------------*/
/* Function for Readable date time
/*--------------------------------------------------------------*/
function read_date_local($str, $user_timezone = 'Asia/Kuala_Lumpur') {
  if ($str) {
      $server_timezone = 'UTC'; // Change this to the server's time zone

      $server_dt = new DateTime($str, new DateTimeZone($server_timezone));
      $server_dt->setTimezone(new DateTimeZone('UTC'));

      $user_dt = new DateTime($str, new DateTimeZone($user_timezone));

      // Check if the user timezone is currently observing DST
      $user_is_dst = $user_dt->format('I');

      // If the user timezone is observing DST, adjust the time
      if ($user_is_dst) {
          $offset = $user_is_dst ? 1 : 0;
          $user_dt->modify("$offset hour");
      }

      return $user_dt->format('F j, Y, g:i:s a');
  } else {
      return null;
  }
}


/*--------------------------------------------------------------*/
/* Function for  Readable Make date time
/*--------------------------------------------------------------*/
function make_date(){
  return strftime("%Y-%m-%d %H:%M:%S", time());
}
/*--------------------------------------------------------------*/
/* Function for  Readable date time
/*--------------------------------------------------------------*/
function count_id(){
  static $count = 1;
  return $count++;
}
/*--------------------------------------------------------------*/
/* Function for Creting random string
/*--------------------------------------------------------------*/
function randString($length = 5)
{
  $str='';
  $cha = "0123456789abcdefghijklmnopqrstuvwxyz";

  for($x=0; $x<$length; $x++)
   $str .= $cha[mt_rand(0,strlen($cha))];
  return $str;
}



/*--------------------------------------------------------------*/
/* // Validate password rules
/*--------------------------------------------------------------*/
function validate_password($password) {
  $errors = array();

  // Minimum length
  if (strlen($password) < 8) {
      $errors[] = "Password must be at least 8 characters long.";
  }

  // At least one uppercase letter
  if (!preg_match('/[A-Z]/', $password)) {
      $errors[] = "Password must contain at least one uppercase letter.";
  }

  // At least one lowercase letter
  if (!preg_match('/[a-z]/', $password)) {
      $errors[] = "Password must contain at least one lowercase letter.";
  }

  // At least one digit
  if (!preg_match('/[0-9]/', $password)) {
      $errors[] = "Password must contain at least one digit.";
  }

  return $errors;
}


/*--------------------------------------------------------------*/
/* // Find User
/*--------------------------------------------------------------*/
function find_by_column($table, $column, $value) {
  global $db;
  $value = $db->escape($value);
  $sql = "SELECT * FROM {$table} WHERE {$column} = '{$value}' LIMIT 1";
  $result = $db->query($sql);
  return $db->fetch_assoc($result);
}





?>


