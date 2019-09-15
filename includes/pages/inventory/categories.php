<div class="wrap">
<h2>Manage Inventory Category</h2>
<?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
<?php if (isset($error_message)): ?><div class="error"><p><?php echo $error_message; ?></p></div><?php endif; ?>
<form id="form2" name="form2" method="post" action="">
  <table width="50%" border="0">
    <tr>
      <td width="25%"><label for="title"> Category Name</label></td>
      <td><input type="text" name="title" id="title" value="<?php echo $title; ?>" required /></td>
    </tr>
    <tr>
      <td width="25%"><label for="status">Status</label></td>
      <td><select id="status" name="status" required>
      <option value="ACTIVE"<?php if ($data['status'] == "ACTIVE") { ?> selected<?php } ?>>Active</option>
      <option value="INACTIVE"<?php if ($data['status'] == "INACTIVE") { ?> selected<?php } ?>>In-Active</option>
    </select></td>
    </tr>
    <tr>
      <td width="25%">&nbsp;</td>
      <td>
      <button name="submit" id="submit" type="submit" class="button">
      <i class="fa fa-floppy-o fa-lg"></i>
      Add Category
      </button>
      <button name="reset" id="reset" type="reset" class="button">
      <i class="fa fa-undo fa-lg"></i>
      Reset
      </button></td>
    </tr>
  </table>
</form>
<h2>All Categories</h2>
<table class='striped' id="datatable_list">
  <thead>
    <tr>
      <td>#</td>
      <td>Category</td>
      <td>Status</td>
      <td>Created By</td>
      <td>Time</td>
      <td>Last Modified</td>
      <td>Time</td>
      <td>Action</td>
    </tr>
  </thead>
  <tbody>
    <?php $count = 1;
    for ($i = 0;  $i < count($list); $i++) { ?>
    <tr>
      <td><?php echo $count; ?></td>
      <td><?php echo $list[$i]['title']; ?></td>
      <td><?php echo $list[$i]['status']; ?></td>
      <td><?php echo self::getuser( $list[$i]['created_by'] ); ?></td>
      <td><?php echo $list[$i]['create_time']; ?></td>
      <td><?php echo self::getuser( $list[$i]['last_modified_by'] ); ?></td>
      <td><?php echo $list[$i]['modify_time']; ?></td>
      <td><?php echo inventory::getLink($list[$i]['status']); ?> <i class="fas fa-edit" title="Edit"></i> <i class="fas fa-trash-alt"></i></td>
    </tr>
    <?php $count++;
    } ?>
  </tbody>
</table>

<h3>Legend</h3>
<i class="fas fa-play" title="Activate" style="color:green"></i>&nbsp;&nbsp;Activate Inventory Category<br>
<i class="fas fa-stop" title="Deactivate" style="color:red"></i>&nbsp;&nbsp;Deactivate Inventory Category<br>
<i class="fas fa-edit" title="Edit"></i>&nbsp;&nbsp;Edit Category<br>
<i class="fas fa-trash-alt"></i>&nbsp;&nbsp;Remove Category
</div>

<script>
jQuery(function ($) {
    $('#datatable_list').dataTable({
	  "pageLength": 25
	});
} );
</script>
