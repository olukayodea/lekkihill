<?php
$list = self::$list;
$data = self::$viewData;
?>
<div class="wrap">
<h2>List Inventory</h2>
<form class="search-form wp-clearfix" method="get" autocomplete="off">
  <p class="search-box">
    <label class="screen-reader-text" for="tag-search-input">Search Inventory:</label>
    <input type="search" id="tag-search-input" name="s" value="" />
    <input type="submit" id="search-submit" class="button" value="Search Inventory"  />
  </p>
</form>
<?php if (isset(self::$message)): ?><div class="updated"><p><?php echo self::$message; ?></p></div><?php endif; ?>
<?php if (isset(self::$error_message)): ?><div class="error"><p><?php echo self::$error_message; ?></p></div><?php endif; ?>
<table class='widefat striped fixed' id="datatable_list">
  <thead>
    <tr>
      <th class="manage-column column-cb check-column"></th>
      <th class="manage-column column-columnname" scope="col">SKU</th>
      <th class="manage-column column-columnname" scope="col">Item</th>
      <th class="manage-column column-columnname" scope="col">Desc</th>
      <th class="manage-column column-columnname" scope="col">Quantity</th>
      <th class="manage-column column-columnname" scope="col">Cost</th>
      <th class="manage-column column-columnname" scope="col">Discount</th>
      <th class="manage-column column-columnname" scope="col">Status</th>
      <th class="manage-column column-columnname" scope="col">Created By</th>
      <th class="manage-column column-columnname" scope="col">Time</th>
      <th class="manage-column column-columnname" scope="col">Modified</th>
      <th class="manage-column column-columnname" scope="col">Time</th>
      <th class="manage-column column-columnname" scope="col">Action</th>
    </tr>
  </thead>
  <tfoot>
    <tr>
      <th class="manage-column column-cb check-column"></th>
      <th class="manage-column column-columnname" scope="col">SKU</th>
      <th class="manage-column column-columnname" scope="col">Item</th>
      <th class="manage-column column-columnname" scope="col">Desc</th>
      <th class="manage-column column-columnname" scope="col">Quantity</th>
      <th class="manage-column column-columnname" scope="col">Cost</th>
      <th class="manage-column column-columnname" scope="col">Discount</th>
      <th class="manage-column column-columnname" scope="col">Status</th>
      <th class="manage-column column-columnname" scope="col">Created By</th>
      <th class="manage-column column-columnname" scope="col">Time</th>
      <th class="manage-column column-columnname" scope="col">Modified</th>
      <th class="manage-column column-columnname" scope="col">Time</th>
      <th class="manage-column column-columnname" scope="col">Action</th>
    </tr>
  </tfoot>
  <tbody>
    <?php $count = 1;
    for ($i = 0;  $i < count($list); $i++) {
      if ($list[$i]['status'] == "INACTIVE") {
        $tag = "This action will make this item visible to all users";
      } else if ($list[$i]['status'] == "ACTIVE") {
        $tag = "This action will make this item invisible to all users";
      } ?>
    <tr>
      <th class="check-column" scope="row"><?php echo $count; ?></th>
      <td class="column-columnname"><?php echo $list[$i]['sku']; ?></td>
      <td class="column-columnname"><a href="<?php echo admin_url('admin.php?page=lh-add-inventory-view&id='.$list[$i]['ref']); ?>" title="View More"><?php echo $list[$i]['title']; ?></a>&nbsp;<a href="<?php echo admin_url('admin.php?page=lh-add-inventory&return='.$_REQUEST['page'].'&edit&id='.$list[$i]['ref']); ?>" title="Edit"><i class="fas fa-edit"></i></a></td>
      <td class="column-columnname"><?php echo $list[$i]['qty_desc']; ?></td>
      <td><?php echo number_format( self::getBalance( $list[$i]['ref'] ) ); ?>&nbsp;<a href="<?php echo admin_url('admin.php?page=lh-add-inventory-stock&return='.$_REQUEST['page'].'&add&id='.$list[$i]['ref']); ?>" title="Add to Stock"><i class="fas fa-plus-square" style="color:green"></i></a>&nbsp;<a href="<?php echo admin_url('admin.php?page=lh-add-inventory-stock&return='.$_REQUEST['page'].'&remove&id='.$list[$i]['ref']); ?>" title="Remove from Stock"><i class="fas fa-minus-square" style="color:red"></i></a></td>
      <td class="column-columnname"><?php echo "&#8358; ".number_format($list[$i]['cost'], 2); ?></td>
      <td class="column-columnname"><?php echo intval($list[$i]['discount']); ?>&nbsp;&nbsp;<a href="Javascript:void(0)" class="discount" data-id="<?php echo $list[$i]['ref']; ?>" data-value="<?php echo intval($list[$i]['discount']); ?>" data-toggle="modal" data-target="#poatPayment"><i class="fas fa-edit"></i></a>
      </td>
      <td class="column-columnname"><?php echo $list[$i]['status']; ?>&nbsp;<a href="<?php echo admin_url('admin.php?page=lh-inventory&return='.$_REQUEST['page'].'&changeStatus='.$list[$i]['status'].'&id='.$list[$i]['ref']); ?>" onClick="return confirm('<?php echo $tag; ?>. are you sure you want to continue ?')"><?php echo self::getLink($list[$i]['status']); ?></a></td>
      <td class="column-columnname"><?php echo self::getuser( $list[$i]['created_by'] ); ?></td>
      <td class="column-columnname"><?php echo $list[$i]['create_time']; ?></td>
      <td class="column-columnname"><?php echo self::getuser( $list[$i]['last_modified_by'] ); ?></td>
      <td class="column-columnname"><?php echo $list[$i]['modify_time']; ?></td>
      <td class="column-columnname">
        <a href="<?php echo admin_url('admin.php?page=lh-inventory&return='.$_REQUEST['page'].'&remove&id='.$list[$i]['ref']); ?>" onClick="return confirm('this action will remove this item. This action can not be undone. are you sure you want to continue ?')"><i class="fas fa-trash-alt"style="color:red"></i></a></td>
    </tr>
    <?php $count++;
    } ?>
  </tbody>
</table>

<h3>Legend</h3>
<i class="fas fa-plus-square" style="color:green" title="Add to Stock"></i>&nbsp;&nbsp;Add to Inventory Stock<br>
<i class="fas fa-minus-square" style="color:red" title="Remove from Stock"></i>&nbsp;&nbsp;Remove from Inventory Stock<br>
<i class="fas fa-play" title="Activate" style="color:green"></i>&nbsp;&nbsp;Activate Inventory Item<br>
<i class="fas fa-stop" title="Deactivate" style="color:red"></i>&nbsp;&nbsp;Deactivate Inventory Item<br>
<i class="fas fa-edit" title="Edit"></i>&nbsp;&nbsp;Edit Inventory Item<br>
<i class="fas fa-trash-alt"></i>&nbsp;&nbsp;Remove Inventory Item
</div>

<!-- post payment invoice modal --->
<div class="modal fade" id="poatPayment" tabindex="-1" role="dialog" aria-labelledby="poatPaymentLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="POST" action="">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="poatPaymentLabel">Edit Discount</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group row">
            <label for="comp_qty_0" class="col-sm-3 col-form-label">Discount</label>
            <div class="col-sm-9">
              <input type="number" class="form-control-plaintext" name="discountValue" id="discountValue" value="" placeholder="Discount" required />
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="inventory_id" id="inventory_id" value="">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" name="updateDiscount" class="btn btn-primary">Update Discount</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script>
jQuery(function ($) {
    $('.discount').click(function () {
      // $( ".discount" ).data( "id" );
      $('#inventory_id').val($( this ).data( "id" ));
      $('#discountValue').val($( this ).data( "value" ));
    });
    $('#datatable_list').DataTable();
} );
</script>
