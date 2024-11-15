<?php
  $page_title = 'All Asset';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
  $products = join_product_table();
?>
<?php include_once('layouts/header.php'); ?>
  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
         <div class="pull-right">
           <a href="add_sale.php" class="btn btn-primary">Add New</a>
         </div>
        </div>
        <div class="panel-body">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th class="text-center" style="width: 50px;">#</th>
                <th> Owner</th>
                <th> Asset Title </th>
                <th class="text-center" style="width: 10%;"> Categories </th>
                <th class="text-center" style="width: 10%;"> No.Sijil Pendaftaran </th>
                <th class="text-center" style="width: 10%;"> Acquisition Value </th>
                <th class="text-center" style="width: 10%;"> Current Estimated Value </th>
                <th class="text-center" style="width: 10%;"> Ownership Date </th>
                <th class="text-center" style="width: 10%;"> Progress </th>
                <th class="text-center" style="width: 100px;"> Actions </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $product):?>
                <?php
                 if (isset($user['name']) && $product['owner'] === $user['name']):
                ?>
              <tr>
                <td class="text-center"><?php echo count_id();?></td>
                <td class="text-center"> <?php echo remove_junk($product['owner']); ?></td>
                <td> <?php echo remove_junk($product['name']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['categorie']); ?></td>
                <td class="text-center"> <?php echo isset($product['number']) ? remove_junk($product['number']) : ''; ?></td>
                <td class="text-center"> <?php echo remove_junk($product['buy_price']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['sale_price']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['date']); ?></td>
                <td class="text-center"> <?php echo isset($product['status']) ? remove_junk($product['status']) : ''; ?></td>
                <td class="text-center">
                  <div class="btn-group">
                  <a href="edit_sale.php?id=<?php echo (int)$product['id'];?>" class="btn btn-info btn-xs"  title="View" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-zoom-in"></span>
                    </a>
                    <a href="delete_sale.php?id=<?php echo (int)$product['id'];?>" class="btn btn-danger btn-xs"  title="Delete" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
                </td>
              </tr>
              <?php endif; ?>
             <?php endforeach; ?>
            </tbody>
          </tabel>
        </div>
      </div>
    </div>
  </div>
  <?php include_once('layouts/footer.php'); ?>
