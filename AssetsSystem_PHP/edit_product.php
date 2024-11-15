<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$page_title = 'Edit product';
require_once('includes/load.php');

// Checkin What level user has permission to view this page
page_require_level(2);

try {
    $product = find_by_id('products', (int)$_GET['id']);
    $all_categories = find_all('categories');
    $sql = "SELECT * FROM products WHERE id=" . (int)$product['id'];
    $result = $db->query($sql);
    if (isset($_POST['product'])) {
        $req_fields = array(
            'product-title', 'product-categorie', 'product-quantity', 'buying-price',
            'saleing-price', 'product-date', 'product-number', 'product-owner',
            'product-detail', 'product-address', 'product-postcode', 'product-city',
            'product-state', 'product-measure', 'product-status'
        );
        validate_fields($req_fields);

        if (empty($errors)) {
            $p_name = remove_junk($db->escape($_POST['product-title']));
            $p_cat = (int)$_POST['product-categorie'];
            $p_qty = remove_junk($db->escape($_POST['product-quantity']));
            $p_buy = remove_junk($db->escape($_POST['buying-price']));
            $p_sale = remove_junk($db->escape($_POST['saleing-price']));
            $date = remove_junk($db->escape($_POST['product-date']));
            $p_number = remove_junk($db->escape($_POST['product-number']));
            $p_owner = remove_junk($db->escape($_POST['product-owner']));
            $p_detail = remove_junk($db->escape($_POST['product-detail']));
            $p_address = remove_junk($db->escape($_POST['product-address']));
            $p_postcode = remove_junk($db->escape($_POST['product-postcode']));
            $p_city = remove_junk($db->escape($_POST['product-city']));
            $p_state = remove_junk($db->escape($_POST['product-state']));
            $p_measure = remove_junk($db->escape($_POST['product-measure']));
            $p_moredetail = remove_junk($db->escape($_POST['product-moredetail']));
            $p_status = remove_junk($db->escape($_POST['product-status']));

            // Check if product-owner exists in the database
            $owner_exists = find_by_column('users', 'username', $p_owner);
            if (!$owner_exists) {
                throw new Exception('Assets owner "Username" does not exist in the database. Please check!');
            }

            $targetDir = "pdf/";
            $targetFile = $targetDir . basename($_FILES["pdfFile"]["name"]);
            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            if ($fileType != "pdf" || $_FILES["pdfFile"]["size"] > 2000000) {
                echo("ERROR: Only PDF Format files and less than 2MB are allowed to upload. Please Check!");
            } else {
                if (move_uploaded_file($_FILES["pdfFile"]["tmp_name"], $targetFile)) {
                    $filename = $_FILES["pdfFile"]["name"];
                    $folder_path = $targetDir;
                }
            }

            $query = "UPDATE products SET";
            $query .= " name ='{$p_name}', quantity ='{$p_qty}',";
            $query .= " buy_price ='{$p_buy}', sale_price ='{$p_sale}', categorie_id ='{$p_cat}', date='{$date}',
                number='$p_number', owner='$p_owner', detail='$p_detail', address='$p_address',postcode='$p_postcode', city='$p_city', state='$p_state', measure='$p_state', moredetail='$p_moredetail',status='$p_status',filename='$filename',folder_path='$folder_path'";
            $query .= " WHERE id ='{$product['id']}'";

            $result = $db->query($query);
            if ($result && $db->affected_rows() === 1) {
                $session->msg('s', "Assets updated ");
                redirect('product.php', false);
            } else {
                throw new Exception('Sorry failed to updated!');
            }
        } else {
          $error_message = is_array($errors) ? implode(" ", $errors) : $errors;
          throw new Exception($error_message);                
        }
    }
} catch (Exception $e) {
    $session->msg("d", $e->getMessage());
    redirect('edit_product.php?id=' . $product['id'], false);
}
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
  <div class="row">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Edit Asset</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-7">

           <form method="post" action="edit_product.php?id=<?php echo (int)$product['id'] ?>">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-title" value="<?php echo remove_junk($product['name']);?>">
               </div>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-user"></i>
                  </span>
                  <input type="text" class="form-control" name="product-owner" value="<?php echo remove_junk($product['owner']);?>">
               </div>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-list"></i>
                  </span>
                  <input type="text" class="form-control" name="product-detail" value="<?php echo remove_junk($product['detail']);?>">
               </div>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-home"></i>
                  </span>
                  <input type="text" class="form-control" name="product-address" value="<?php echo remove_junk($product['address']);?>">
               </div>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-home"></i>
                  </span>
                  <input type="text" class="form-control" name="product-postcode" value="<?php echo remove_junk($product['postcode']);?>">
               </div>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-home"></i>
                  </span>
                  <input type="text" class="form-control" name="product-city" value="<?php echo remove_junk($product['city']);?>">
               </div>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-home"></i>
                  </span>
                  <input type="text" class="form-control" name="product-state" value="<?php echo remove_junk($product['state']);?>">
               </div>
              </div>



              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <select class="form-control" name="product-categorie">
                    <option value=""> Select a categorie</option>
                   <?php  foreach ($all_categories as $cat): ?>
                     <option value="<?php echo (int)$cat['id']; ?>" <?php if($product['categorie_id'] === $cat['id']): echo "selected"; endif; ?> >
                       <?php echo remove_junk($cat['name']); ?></option>
                   <?php endforeach; ?>
                 </select>
                  </div>

                  <div class="col-md-6">
                    <table class = "table table-bordered table-striped table-hover">
                      <thead>
                        <tr>
                          <th>File Name</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                    
                      <tbody>
                        <?php
                        $count = 1;
                        if ($result->num_rows > 0){
                          while ($row = $result->fetch_assoc()){
                            echo "<tr>";
                            echo "<td>".$row['filename']."</td>";
                            echo '<td><a href="pdf/'.$row['filename'].'" class="btn btn-info" download>Download</a></td>';
                            $count++;
                          }
                        } else{
                          echo "<tr><td colspan='3'>No records found.</td></tr>";
                        }
                        ?>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-barcode"></i>
                  </span>
                  <input type="text" class="form-control" name="product-number" value="<?php echo remove_junk($product['number']);?>">
               </div>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-calendar"></i>
                  </span>
                  <input type="text" class="form-control datepicker" name="product-date" value="<?php echo remove_junk($product['date']);?>">
               </div>
              </div>


            
              <div class="form-group">
               <div class="row">

                 <div class="col-md-4">
                  <div class="form-group">
                    <label for="qty">Total Quantity</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                       <i class="glyphicon glyphicon-shopping-cart"></i>
                      </span>
                      <input type="number" class="form-control" name="product-quantity" value="<?php echo remove_junk($product['quantity']); ?>">
                   </div>
                  </div>
                 </div>

                 <div class="col-md-6">
                  <div class="form-group">
                    <label for="qty">Quantity Measurement</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                       <i class="glyphicon glyphicon-shopping-cart"></i>
                      </span>
                      <input type="number" class="form-control" name="product-measure" value="<?php echo remove_junk($product['measure']); ?>">
                   </div>
                  </div>
                 </div>

                 <div class="col-md-8">
                  <div class="form-group">
                    <label for="qty">Acquisition Value</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-usd"></i>
                      </span>
                      <input type="number" class="form-control" name="buying-price" value="<?php echo remove_junk($product['buy_price']);?>">
                      <span class="input-group-addon">.00</span>
                   </div>
                  </div>
                 </div>

                  <div class="col-md-8">
                   <div class="form-group">
                     <label for="qty">Current Estimated Value</label>
                     <div class="input-group">
                       <span class="input-group-addon">
                         <i class="glyphicon glyphicon-usd"></i>
                       </span>
                       <input type="number" class="form-control" name="saleing-price" value="<?php echo remove_junk($product['sale_price']);?>">
                       <span class="input-group-addon">.00</span>
                    </div>
                   </div>
                  </div>

               </div>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-moredetail" value="<?php echo remove_junk($product['moredetail']);?>">
               </div>
              </div>

               <br>

              <div class="col-md-20 ">
               <label class="radio-inline">
                <input type="radio" name="product-status" value="Pending"> <strong style = "font-size: 16px;">Pending</strong>
               </label>
               <label class="radio-inline">
                <input type="radio" name="product-status" value="Approve"> <strong style = "font-size: 16px;">Approve</strong>
               </label>
               <label class="radio-inline">
                <input type="radio" name="product-status" value="Reject"> <strong style = "font-size: 16px;">Reject</strong>
               </label>
              </div>

              <br>

              <button type="submit" name="product" class="btn btn-danger">Update</button>
          </form>
         </div>
        </div>
      </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
