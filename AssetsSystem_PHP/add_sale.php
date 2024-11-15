<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$page_title = 'Add Asset';
require_once('includes/load.php');

// Check what level user has permission to view this page
page_require_level(3);
$all_categories = find_all('categories');
$all_photo = find_all('media');

try {
    if (isset($_POST['add_sale'])) {
        $req_fields = array(
            'product-title', 'product-categorie', 'product-quantity', 'buying-price',
            'saleing-price', 'product-date', 'product-number', 'product-owner',
            'product-detail', 'product-address', 'product-postcode', 'product-city',
            'product-state', 'product-measure'
        );
        validate_fields($req_fields);

        if (empty($errors)) {
            $p_name = remove_junk($db->escape($_POST['product-title']));
            $p_cat = remove_junk($db->escape($_POST['product-categorie']));
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
            $p_status = "Pending";

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

            $query = "INSERT INTO products (";
            $query .= " name,quantity,buy_price,sale_price,categorie_id,date,number,owner,detail,address,postcode,city,state,measure,moredetail,status,filename,folder_path";
            $query .= ") VALUES (";
            $query .= " '{$p_name}', '{$p_qty}', '{$p_buy}', '{$p_sale}', '{$p_cat}', '{$date}', '{$p_number}','{$p_owner}','{$p_detail}','{$p_address}'
         ,'{$p_postcode}','{$p_city}','{$p_state}','{$p_measure}','{$p_moredetail}','{$p_status}', '{$filename}', '{$folder_path}' ";
            $query .= ")";
            $query .= " ON DUPLICATE KEY UPDATE name='{$p_name}'";

            if ($db->query($query)) {
                $session->msg('s', "Asset added to system !");
                redirect('add_sale.php', false);
            } else {
                throw new Exception('Sorry failed to added!');
            }
        } else {
          $error_message = is_array($errors) ? implode(" ", $errors) : $errors;
          throw new Exception($error_message);          
         }
    }
} catch (Exception $e) {
    $session->msg("d", $e->getMessage());
    redirect('add_sale.php', false);
}
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
  <div class="row">
  <div class="col-md-8">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Add New Asset</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="add_sale.php" class="clearfix" enctype="multipart/form-data">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-title" placeholder="Asset Title">
               </div>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-user"></i>
                  </span>
                  <input type="text" class="form-control" name="product-owner" value="<?php echo isset($user['name']) ? htmlspecialchars($user['name']) : ''; ?>" placeholder="Asset Owner" readonly>
               </div>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-list"></i>
                  </span>
                  <input type="text" class="form-control" name="product-detail" placeholder="Asset Detail">
               </div>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-home"></i>
                  </span>
                  <input type="text" class="form-control" name="product-address" placeholder="Address">
               </div>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-home"></i>
                  </span>
                  <input type="text" class="form-control" name="product-postcode" placeholder="Postcode">
               </div>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-home"></i>
                  </span>
                  <input type="text" class="form-control" name="product-city" placeholder="City">
               </div>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-home"></i>
                  </span>
                  <input type="text" class="form-control" name="product-state" placeholder="State">
               </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <select class="form-control" name="product-categorie">
                      <option value="">Select Asset Category</option>
                    <?php  foreach ($all_categories as $cat): ?>
                      <option value="<?php echo (int)$cat['id'] ?>">
                        <?php echo $cat['name'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="col-md-6">
                      <div class = "form-group">
                        <label for="pdfFile" style="color: #646464; font-size: 13px; font-weight: lighter;">Select PDF File:</label>
                        <input type="file" name="pdfFile" class="form-control-file" id="pdfFile">
                      </div>
                    
                  </div>


                </div>
              </div>
              

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-barcode"></i>
                  </span>
                  <input type="text" class="form-control" name="product-number" placeholder="No.Sijil Pendaftaran">
               </div>
              </div>

              <div class="form-group">
               <div class="input-group date">
                 <span class="input-group-addon">
                  <i class="glyphicon glyphicon-calendar"></i>
                 </span>
                 <input type="text" class="form-control datepicker" name="product-date" placeholder="Ownership Date">
               </div>
              </div>
              


              <div class="form-group">
               <div class="row">
                 <div class="col-md-4">
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                     </span>
                     <input type="number" class="form-control" name="product-quantity" placeholder="Total Quantity">
                  </div>
                 </div>

                 <div class="col-md-6">
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                     </span>
                     <input type="number" class="form-control" name="product-measure" placeholder="Quantity Measurement">
                  </div>
                 </div>

                 <div class="col-md-8">
                   <div class="input-group">
                     <span class="input-group-addon">
                       <i class="glyphicon glyphicon-usd"></i>
                     </span>
                     <input type="number" class="form-control" name="buying-price" placeholder="Acquisition Value">
                     <span class="input-group-addon">.00</span>
                  </div>
                 </div>

                  <div class="col-md-8">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-usd"></i>
                      </span>
                      <input type="number" class="form-control" name="saleing-price" placeholder="Current Estimated Value">
                      <span class="input-group-addon">.00</span>
                   </div>
                  </div>
               </div>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-moredetail" placeholder="Further Explaination">
               </div>
              </div>


              <div>
                <p><strong>Please ensure that all information is correct. No changes is allow after submit.</strong></p><br>
                <p><strong>The verification process is expected to take approximately 2 to 3 days to complete.</strong></p>
              </div>


              <button type="submit" name="add_sale" class="btn btn-danger">Add Asset</button>
          </form>
         </div>
        </div>
      </div>
    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
