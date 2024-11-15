<?php
  $page_title = 'Edit categorie';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
?>
<?php
  // Display all categories.
  $categorie = find_by_id('categories', (int)$_GET['id']);
  if (!$categorie) {
    $session->msg("d", "Missing category id.");
    redirect('categorie.php');
  }
?>

<?php
try {
  if (isset($_POST['edit_cat'])) {
    $req_field = array('categorie-name');
    validate_fields($req_field);
    $cat_name = remove_junk($db->escape($_POST['categorie-name']));

    if (empty($errors)) {
      $sql = "UPDATE categories SET name='{$cat_name}'";
      $sql .= " WHERE id='{$categorie['id']}'";
      $result = $db->query($sql);

      if ($result && $db->affected_rows() === 1) {
        $session->msg("s", "Successfully updated Category");
        redirect('categorie.php', false);
      } else {
        throw new Exception("No CHANGES, Sorry! Failed to Update!");
      }
    } else {
      $error_message = is_array($errors) ? implode(" ", $errors) : $errors;
      throw new Exception($error_message);       
     }
  }
} catch (Exception $e) {
  $session->msg("d", $e->getMessage());
  redirect('categorie.php', false);
}
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
   <div class="col-md-12">
     <?php echo display_msg($msg); ?>
   </div>
   <div class="col-md-5">
     <div class="panel panel-default">
       <div class="panel-heading">
         <strong>
           <span class="glyphicon glyphicon-th"></span>
           <span>Editing <?php echo remove_junk(ucfirst($categorie['name']));?></span>
        </strong>
       </div>
       <div class="panel-body">
         <form method="post" action="edit_categorie.php?id=<?php echo (int)$categorie['id'];?>">
           <div class="form-group">
               <input type="text" class="form-control" name="categorie-name" value="<?php echo remove_junk(ucfirst($categorie['name']));?>">
           </div>
           <button type="submit" name="edit_cat" class="btn btn-primary">Update categorie</button>
       </form>
       </div>
     </div>
   </div>
</div>



<?php include_once('layouts/footer.php'); ?>
