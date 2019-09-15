<div class="wrap">
<h2>List Inventory</h2>
<?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
<?php if (isset($error_message)): ?><div class="error"><p><?php echo $error_message; ?></p></div><?php endif; ?>
<table class='striped' id="datatable_list">
  <thead>
    <tr>
      <td>#</td>
      <td>SKU</td>
      <td>Item</td>
      <td>Quantity</td>
      <td>Status</td>
      <td>Created By</td>
      <td>Time</td>
      <td>Modified</td>
      <td>Time</td>
      <td>Action</td>
    </tr>
  </thead>
  <tbody>
    <?php $count = 1;
    for ($i = 0;  $i < count($list); $i++) { ?>
    <tr>
      <td><?php echo $count; ?></td>
      <td><?php echo $list[$i]['sku']; ?></td>
      <td><?php echo $list[$i]['title']; ?>&nbsp;<a href="<?php echo admin_url('admin.php?page=lh-add-inventory&edit&id='.$list[$i]['ref']); ?>" title="Edit"><i class="fas fa-edit"></i></a></td>
      <td><?php echo number_format( self::getBalance( $list[$i]['ref'] ) ); ?>&nbsp;<a href="<?php echo admin_url('admin.php?page=lh-add-inventory-stock&add&id='.$list[$i]['ref']); ?>" title="Add to Stock"><i class="fas fa-plus-square" style="color:green"></i></a>&nbsp;<a href="<?php echo admin_url('admin.php?page=lh-add-inventory-stock&remove&id='.$list[$i]['ref']); ?>" title="Remove from Stock"><i class="fas fa-minus-square" style="color:red"></i></a>&nbsp;<i class="fas fa-search" title="Search History"></i></td>
      <td><?php echo $list[$i]['status']; ?>&nbsp;<?php echo self::getLink($list[$i]['status']); ?></td>
      <td><?php echo self::getuser( $list[$i]['created_by'] ); ?></td>
      <td><?php echo $list[$i]['create_time']; ?></td>
      <td><?php echo self::getuser( $list[$i]['last_modified_by'] ); ?></td>
      <td><?php echo $list[$i]['modify_time']; ?></td>
      <td><i class="fas fa-trash-alt"></i></td>
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
<script>
jQuery(function ($) {
    $('#datatable_list').dataTable({
	  "pageLength": 25
	});
} );
</script>