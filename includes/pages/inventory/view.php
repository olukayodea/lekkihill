<div class="wrap">
<h2><i class="fas fa-capsules"></i>&nbsp;<?php echo $data['title'];  ?></h2>
<?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
<?php if (isset($error_message)): ?><div class="error"><p><?php echo $error_message; ?></p></div><?php endif; ?>
<table width="100%" border="0">
  <tr class="odd">
    <td>SKU</td>
    <td><?php echo $data['sku'];  ?></td>
    <td rowspan="8" align="center" valign="middle">
    <img src="<?php echo plugins_url('lekkihill/includes/controllers/barcode.php?f=png&s=code-39-ascii&d='.$data['sku'].'&w=220'); ?>">
    <br><a href="" title="Download Barcode"><i class="fas fa-download"></i></a>&nbsp;<a href="" title="Print Barcode"><i class="fas fa-print"></i></a>
  </td>
  </tr>
  <tr>
    <td>Category</td>
    <td><?php echo inventory_category::getSingle( $data['category_id'] );  ?></td>
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
</table>
<h3>History</h3>
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