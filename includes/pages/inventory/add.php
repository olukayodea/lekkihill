<div class="wrap">
<h2>Add Inventory Item</h2>
<?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
<?php if (isset($error_message)): ?><div class="error"><p><?php echo $error_message; ?></p></div><?php endif; ?>
  <div id="col-container" class="wp-clearfix">
    <div id="col-left">
      <div class="col-wrap">
        <div class="form-wrap">
          <form id="form2" name="form2" method="post" action="<?php echo $url; ?>">
            <div class="form-field form-required term-title-wrap">
              <label for="title"> Item Name</label>
              <input type="text" name="title" id="title" value="<?php echo $data['title']; ?>" required />
            </div>
            <div class="form-field form-required term-title-wrap">
              <label for="cost"> Item Cost</label></td>
              <input type="number" name="cost" id="cost" value="<?php echo $data['cost']; ?>" required step='0.01' placeholder='&#8358; 0.00' />
            </div>
            <?php if (!isset($_REQUEST['id'])) { ?>
              <div class="form-field form-required term-title-wrap">
                <label for="inventory_added">Opening Stock</label>
                <input type="number" name="inventory_added" id="inventory_added" required />
              </div>
            <?php } else { ?>
              <input type="hidden" name="ref" id="ref" value="<?php echo $data['ref']; ?>" />
            <?php } ?>
            <div class="form-field form-required term-title-wrap">
              <label for="category_id">Category</label>
              <select id="category_id" name="category_id" required>
                <option value="">Select One</option>
                <?php for ($i = 0; $i < count($list); $i++) { ?>
                  <option value="<?php echo $list[$i]['ref']; ?>"<?php if ($list[$i]['ref'] == $data['category_id']) { ?> selected<?php } ?>><?php echo $list[$i]['title']; ?></option>
                <?php } ?>
            </select>
            </div>
            <div class="form-field form-required term-title-wrap">
              <label for="status">Status</label>
              <select id="status" name="status" required>
                <option value="ACTIVE"<?php if ($data['status'] == "ACTIVE") { ?> selected<?php } ?>>Active</option>
                <option value="INACTIVE"<?php if ($data['status'] == "INACTIVE") { ?> selected<?php } ?>>In-Active</option>
              </select>
            </div>

            <?php if (isset($_REQUEST['return'])) { ?>
            <input type="hidden" name="return" id="return" value="<?php echo $_REQUEST['return']."&id=".$data['ref']; ?>" />
            <?php } ?>
            <button name="save" id="save" type="submit" class="button button-primary"><i class="fa fa-floppy-o fa-lg"></i><?php echo $tag; ?></button>
            <button name="reset" id="reset" type="reset" class="button"><i class="fa fa-undo fa-lg"></i>Reset</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

