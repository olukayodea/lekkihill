<div class="wrap">
<h2>List Inventory</h2>
<form class="search-form wp-clearfix" method="get" autocomplete="off">
  <p class="search-box">
    <label class="screen-reader-text" for="tag-search-input">Search Inventory:</label>
    <input type="search" id="tag-search-input" name="s" value="" />
    <input type="submit" id="search-submit" class="button" value="Search Inventory"  />
  </p>
</form>
<?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
<?php if (isset($error_message)): ?><div class="error"><p><?php echo $error_message; ?></p></div><?php endif; ?>
<table class='widefat striped fixed' id="datatable_list">
  <thead>
    <tr>
      <th class="manage-column column-cb check-column"></th>
      <th class="manage-column column-columnname" scope="col">SKU</th>
      <th class="manage-column column-columnname" scope="col">Item</th>
      <th class="manage-column column-columnname" scope="col">Desc</th>
      <th class="manage-column column-columnname" scope="col">Quantity</th>
      <th class="manage-column column-columnname" scope="col">Cost</th>
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
<script>
jQuery(function ($) {
    $('#datatable_list').DataTable();
} );
</script>
