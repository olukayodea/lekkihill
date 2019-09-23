<?php
if ($data['status'] == "INACTIVE") {
  $tag = "This action will make this item visible to all users";
} else if ($data['status'] == "ACTIVE") {
  $tag = "This action will make this item invisible to all users";
}
?>
<div class="wrap">
<h2><i class="fas fa-capsules"></i>&nbsp;<?php echo $data['title'];  ?></h2>
<?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
<?php if (isset($error_message)): ?><div class="error"><p><?php echo $error_message; ?></p></div><?php endif; ?>
<table width="100%" border="0">
  <tbody>
  <tr class='striped'>
    <td>SKU</td>
    <td><?php echo $data['sku'];  ?></td>
    <td rowspan="8" align="center" valign="middle">
    <img src="<?php echo plugins_url('lekkihill/includes/controllers/barcode.php?f=png&s=code-39-ascii&d='.$data['sku'].'&w=220'); ?>">
    <br><a href="<?php echo plugins_url('lekkihill/includes\controllers\barcode.download.php?d='.$data['sku']); ?>" title="Download Barcode"><i class="fas fa-download"></i></a>&nbsp;<a href="<?php echo plugins_url('lekkihill/includes\controllers\barcode.print.php?d='.$data['sku']); ?>" target="_blank" title="Print Barcode"><i class="fas fa-print"></i></a>
  </td>
  </tr>
  <tr>
    <td>Category</td>
    <td><?php echo inventory_category::getSingle( $data['category_id'] );  ?></td>
   </tr>
  <tr>
    <td>Cost </td>
    <td><?php echo "&#8358; ".number_format($data['cost'], 2);  ?></td>
  </tr>
  <tr>
    <td>Quantity </td>
    <td><?php echo $data['quantity'];  ?></td>
  </tr>
  <tr>
    <td>Status</td>
    <td><?php echo $data['status'];  ?></td>
  </tr>
  <tr>
    <td>Created By</td>
    <td><?php echo self::getuser( $data['created_by'] );  ?></td>
  </tr>
  <tr>
    <td>Created At</td>
    <td><?php echo $data['create_time'];  ?></td>
  </tr>
  <tr>
    <td>Last Modified by</td>
    <td><?php echo self::getuser( $data['last_modified_by'] );  ?></td>
  </tr>
  <tr>
    <td>Modified At</td>
    <td><?php echo $data['modify_time'];  ?></td>
  </tr>
  <tr>
    <td colspan="3">
      <button type="submit" class="button" title="Edit" onclick="location='<?php echo admin_url('admin.php?page=lh-add-inventory&return=lh-add-inventory-view&edit&id='.$data['ref']); ?>'"><i class="fas fa-edit fa-lg"></i>&nbsp;Edit</button>
      <button type="submit" class="button" title="Add Stock" onclick="location='<?php echo admin_url('admin.php?page=lh-add-inventory-stock&return=lh-add-inventory-view&add&id='.$data['ref']); ?>'"><i class="fas fa-plus-square fa-lg"></i>&nbsp;Add Stock</button>
      <button type="submit" class="button" title="Remove Stock" onclick="location='<?php echo admin_url('admin.php?page=lh-add-inventory-stock&return=lh-add-inventory-view&remove&id='.$data['ref']); ?>'"><i class="fas fa-minus-square fa-lg"></i>&nbsp;Remove Stock</button>
      <button type="submit" class="button" onclick="if (confirm('<?php echo $tag; ?>. Are you sure you want to Continue?')) location='<?php echo admin_url('admin.php?page=lh-inventory&return='.urlencode($_REQUEST['page'].'&id='.$data['ref']).'&changeStatus='.$data['status'].'&id='.$data['ref']); ?>'"><?php echo self::getLink($data['status'], true); ?></button>
      <button type="button" class="button" onclick="if (confirm('this action will remove this item. This action can not be undone. are you sure you want to continue ')) location='<?php echo admin_url('admin.php?page=lh-inventory&return=lh-inventory&remove&id='.$data['ref']); ?>'"><i class="fa fa-floppy-o fa-lg"></i>&nbsp;Delete</button>
    </td>
  </tr>
  </tbody>
</table>
<h3>History</h3>
<button type="button" class="button" onclick="location='<?php echo admin_url('admin.php?page=lh-add-inventory-view&id='.$_REQUEST['id']); ?>&downloadItemPDF'">
<i class="fas fa-file-pdf fa-lg"></i>&nbsp;Download or Print PDF
</button>
<button type="button" class="button" onclick="location='<?php echo admin_url('admin.php?page=lh-add-inventory-view&id='.$_REQUEST['id']); ?>&downloadItemCSV'">
<i class="fas fa-file-excel fa-lg"></i>&nbsp;Download as Excel
</button>
<table class='striped' id="datatable_list">
  <thead>
    <tr>
      <td>#</td>
      <td>Date</td>
      <td>Quantity Left</td>
      <td>Amount Added/Removed</td>
      <td>Total Quantity</td>
      <td>Added By</td>
    </tr>
  </thead>
  <tbody>
    <?php $count = 1;
    for ($i = 0;  $i < count($list); $i++) { ?>
    <tr>
      <td><?php echo $count; ?></td>
      <td><?php echo $list[$i]['create_time']; ?></td>
      <td><?php echo number_format( $list[$i]['inventory_before_added'] ); ?></td>
      <td><?php echo ($list[$i]['inventory_added'] < 0 ? "(".number_format( abs( $list[$i]['inventory_added'] ) ).")" : number_format( abs( $list[$i]['inventory_added'] ) ) ); ?></td>
      <td><?php echo number_format( $list[$i]['inventory_before_added']+$list[$i]['inventory_added'] ); ?></td>
      <td><?php echo self::getuser( $list[$i]['added_by'] ); ?></td>
    </tr>
    <?php $count++;
    } ?>
  </tbody>
</table>
</div>
<script>
jQuery(function ($) {
    $('#datatable_list').dataTable({
	  "pageLength": 25
	});
    $('#toplevel_page_lh-inventory').addClass('current wp-has-current-submenu wp-menu-open');
} );
</script>