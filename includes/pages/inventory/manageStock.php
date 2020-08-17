<?php
$list = self::$list;
$data = self::$viewData;
?>
<div class="wrap">
<h2>Manage Inventory Item</h2>
<?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
<?php if (isset($error_message)): ?><div class="error"><p><?php echo $error_message; ?></p></div><?php endif; ?>
<?php if ($error != true) { ?>
<form id="form2" name="form2" method="post" action="" autocomplete="off">
  <table width="50%" border="0">
    <tr>
      <td width="25%"><label for="title"> Inventory Item</label></td>
      <td><input type="text" disabled name="title" id="title" value="<?php echo $data['title']; ?>" />
      <input type="hidden" name="inventory_id" id="inventory_id" value="<?php echo $data['ref']; ?>" />
      <input type="hidden" name="inventory_before_added" id="inventory_before_added" value="<?php echo $data['quantity']; ?>" />
      <input type="hidden" name="dir" id="dir" value="<?php echo $tag; ?>" />
    </td>
    </tr>
    <tr>
      <td width="25%"><label for="quantity"> Current Quantity</label></td>
      <td><input type="text" disabled name="quantity" id="quantity" value="<?php echo $data['quantity']; ?>" /></td>
    </tr>
    <tr>
      <td width="25%"><label for="inventory_added"> Quantity to <?php echo $tag; ?></label></td>
      <td><input type="number" <?php echo $label; ?> name="inventory_added" id="inventory_added" value="" required /></td>
    </tr>
    <tr>
      <td width="25%">&nbsp;</td>
      <td>
        <?php if (isset($_REQUEST['return'])) { ?>
        <input type="hidden" name="return" id="return" value="<?php echo $_REQUEST['return']."&id=".$data['ref']; ?>" />
        <?php } ?>
        <button name="submit" id="submit" type="submit" class="button">
        <i class="fa fa-floppy-o fa-lg"></i>
        Update Stock
        </button>
        <button name="reset" id="reset" type="reset" class="button">
        <i class="fa fa-undo fa-lg"></i>
        Reset
        </button>
      </td>
    </tr>
  </table>
</form>
<?php } ?>
</div>
<script>
jQuery(function ($) {
    $('#toplevel_page_lh-inventory').addClass('current wp-has-current-submenu wp-menu-open');
} );
</script>