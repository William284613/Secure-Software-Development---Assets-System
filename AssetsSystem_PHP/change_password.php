<?php
  $page_title = 'Change Password';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
?>
<?php $user = current_user(); ?>
<?php
  try {
    if(isset($_POST['update'])){

      $req_fields = array('new-password','old-password','id');
      validate_fields($req_fields);

  if(empty($errors)){

    // Check if the old password matches the current user's password
    
      if(password_verify($_POST['old-password'], current_user()['password'])) {
        $id = (int)$_POST['id'];
        $new_password = remove_junk($db->escape($_POST['new-password']));
  
        // Validate the new password
        $password_errors = validate_password($new_password);
        if (!empty($password_errors)) {
          throw new Exception(implode(" ", $password_errors));
        }
  
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
  
        // Update the user's password
        $sql = "UPDATE users SET password ='{$hashed_password}' WHERE id='{$db->escape($id)}'";
        //$sql = "UPDATE users SET password ='{$hashed_password}' WHERE id='nonexistent_id'";

        $result = $db->query($sql);
  
        if($result && $db->affected_rows() === 1) {
          $session->msg('s', "Successfully changed! Logout and Login with your new password.");
          redirect('change_password.php', false);
        } else {
          throw new Exception('Failed to update password.');
        }
        } else{
          $error_message = is_array($errors) ? implode(" ", $errors) : $errors;
          throw new Exception($error_message.'Old Password Not Matched !');  
          redirect('change_password.php', false);
        }
        }else {
          $error_message = is_array($errors) ? implode(" ", $errors) : $errors;
          throw new Exception($error_message);            
        }     
        } 
        }catch (Exception $e) {
          $session->msg('d', $e->getMessage());
          redirect('change_password.php', false);
    }
?>

<?php include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
       <h3>Change your password</h3>
     </div>
     <?php echo display_msg($msg); ?>
      <form method="post" action="change_password.php" class="clearfix">
        <div class="form-group">
              <label for="newPassword" class="control-label">New password</label>
              <input type="password" class="form-control" name="new-password" placeholder="New password">
        </div>
        <div class="form-group">
              <label for="oldPassword" class="control-label">Old password</label>
              <input type="password" class="form-control" name="old-password" placeholder="Old password">
        </div>
        <div class="form-group clearfix">
               <input type="hidden" name="id" value="<?php echo (int)$user['id'];?>">
                <button type="submit" name="update" class="btn btn-info">Change</button>
        </div>
    </form>
</div>
<?php include_once('layouts/footer.php'); ?>
