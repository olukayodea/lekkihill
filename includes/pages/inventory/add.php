<div class="wrap">
<h2>Add Inventory Item</h2>
<?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
<?php if (isset($error_message)): ?><div class="error"><p><?php echo $error_message; ?></p></div><?php endif; ?>
<form id="form2" name="form2" method="post" action="<?php echo $url; ?>">
  <table width="50%" border="0">
    <tr>
      <td width="25%"><label for="title"> Item Name</label></td>
      <td><input type="text" name="title" id="title" value="<?php echo $data['title']; ?>" required /></td>
    </tr>
    <?php if (!isset($_REQUEST['id'])) { ?>
    <tr>
      <td width="25%"><label for="inventory_added">Opening Stock</label></td>
      <td><input type="number" name="inventory_added" id="inventory_added" required /></td>
    </tr>
    <?php } else { ?>
        <input type="hidden" name="ref" id="ref" value="<?php echo $data['ref']; ?>" />
    <?php } ?>
    <tr>
      <td width="25%"><label for="category_id">Category</label></td>
      <td>
          <select id="category_id" name="category_id" required>
              <option value="">Select One</option>
              <?php for ($i = 0; $i < count($list); $i++) { ?>
                <option value="<?php echo $list[$i]['ref']; ?>"<?php if ($list[$i]['ref'] == $data['category_id']) { ?> selected<?php } ?>><?php echo $list[$i]['title']; ?></option>
              <?php } ?>
          </select>
        </td>
    </tr>
    <tr>
      <td width="25%"><label for="status">Status</label></td>
      <td>
          <select id="status" name="status" required>
              <option value="ACTIVE"<?php if ($data['status'] == "ACTIVE") { ?> selected<?php } ?>>Active</option>
              <option value="INACTIVE"<?php if ($data['status'] == "INACTIVE") { ?> selected<?php } ?>>In-Active</option>
            </select>
        </td>
    </tr>
    <tr>
      <td width="25%">&nbsp;</td>
      <td>
      <button name="save" id="save" type="submit" class="button">
      <i class="fa fa-floppy-o fa-lg"></i>
      <?php echo $tag; ?>
      </button>
      <button name="reset" id="reset" type="reset" class="button">
      <i class="fa fa-undo fa-lg"></i>
      Reset
      </button></td>
    </tr>
  </table>
</form>
</div>
