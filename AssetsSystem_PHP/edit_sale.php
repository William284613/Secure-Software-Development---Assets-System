<?php
  $page_title = 'View Asset';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>
<?php
$product = find_by_id('products',(int)$_GET['id']);
$all_categories = find_all('categories');
$sql = "SELECT * FROM products WHERE id=" . (int)$product['id'];
$result = $db->query($sql);
?>
<?php
 if(isset($_POST['product'])){
    $req_fields = array('product-title','product-categorie','product-quantity','buying-price', 'saleing-price','product-date','product-number', 'product-owner'
    ,'product-detail','product-address','product-postcode','product-city','product-state','product-measure','product-status' );
    validate_fields($req_fields);

   if(empty($errors)){
       $p_name  = remove_junk($db->escape($_POST['product-title']));
       $p_cat   = (int)$_POST['product-categorie'];
       $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
       $p_buy   = remove_junk($db->escape($_POST['buying-price']));
       $p_sale  = remove_junk($db->escape($_POST['saleing-price']));
       $date    = remove_junk($db->escape($_POST['product-date']));
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
       $query   = "UPDATE products SET";
       $query  .=" name ='{$p_name}', quantity ='{$p_qty}',";
       $query  .=" buy_price ='{$p_buy}', sale_price ='{$p_sale}', categorie_id ='{$p_cat}', date='{$date}',
       number='$p_number', owner='$p_owner', detail='$p_detail', address='$p_address',postcode='$p_postcode', city='$p_city', state='$p_state', measure='$p_state', moredetail='$p_moredetail',status='$p_status'";
       $query  .=" WHERE id ='{$product['id']}'";
       $result = $db->query($query);
               if($result && $db->affected_rows() === 1){
                 $session->msg('s',"Product updated ");
                 redirect('product.php', false);
               } else {
                 $session->msg('d',' Sorry failed to updated!');
                 redirect('edit_product.php?id='.$product['id'], false);
               }

   } else{
       $session->msg("d", $errors);
       redirect('edit_product.php?id='.$product['id'], false);
   }

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
            <span>View Asset</span>
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
                  <input type="text" class="form-control" name="product-title" value="<?php echo remove_junk($product['name']);?>" readonly>
               </div>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-user"></i>
                  </span>
                  <input type="text" class="form-control" name="product-owner" value="<?php echo remove_junk($product['owner']);?>" readonly>
               </div>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-list"></i>
                  </span>
                  <input type="text" class="form-control" name="product-detail" value="<?php echo remove_junk($product['detail']);?>" readonly>
               </div>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-home"></i>
                  </span>
                  <input type="text" class="form-control" name="product-address" value="<?php echo remove_junk($product['address']);?>" readonly>
               </div>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-home"></i>
                  </span>
                  <input type="text" class="form-control" name="product-postcode" value="<?php echo remove_junk($product['postcode']);?>" readonly>
               </div>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-home"></i>
                  </span>
                  <input type="text" class="form-control" name="product-city" value="<?php echo remove_junk($product['city']);?>" readonly>
               </div>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-home"></i>
                  </span>
                  <input type="text" class="form-control" name="product-state" value="<?php echo remove_junk($product['state']);?>" readonly>
               </div>
              </div>



              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <select class="form-control" name="product-categorie">
                    <option value=""> Select a categorie</option>
                   <?php  foreach ($all_categories as $cat): ?>
                     <option value="<?php echo (int)$cat['id']; ?>" <?php if($product['categorie_id'] === $cat['id']): echo "selected"; endif; ?> readonly disabled>
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
                  <input type="text" class="form-control" name="product-number" value="<?php echo remove_junk($product['number']);?>" readonly>
               </div>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-calendar"></i>
                  </span>
                  <input type="text" class="form-control datepicker" name="product-date" value="<?php echo remove_junk($product['date']);?>" readonly disabled>
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
                      <input type="number" class="form-control" name="product-quantity" value="<?php echo remove_junk($product['quantity']); ?>" readonly>
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
                      <input type="number" class="form-control" name="product-measure" value="<?php echo remove_junk($product['measure']); ?>" readonly>
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
                      <input type="number" class="form-control" name="buying-price" value="<?php echo remove_junk($product['buy_price']);?>" readonly>
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
                       <input type="number" class="form-control" name="saleing-price" value="<?php echo remove_junk($product['sale_price']);?>" readonly>
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
                  <input type="text" class="form-control" name="product-moredetail" value="<?php echo remove_junk($product['moredetail']);?>" readonly>
               </div>

               <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-menu-right"></i>
                  </span>
                  <input type="text" class="form-control" name="product-status" value="<?php echo remove_junk($product['status']);?>" readonly>
               </div>

              
          </form>
         </div>
        </div>
      </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
