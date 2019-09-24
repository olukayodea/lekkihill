<div class="wrap">
<h2>Manage Bill Component</h2>
<?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
<?php if (isset($error_message)): ?><div class="error"><p><?php echo $error_message; ?></p></div><?php endif; ?>
<form id="form2" name="form2" method="post" action="">
  <table width="50%" border="0">
    <tr>
      <td width="25%"><label for="title"> Title</label></td>
      <td><input type="text" name="title" id="title" value="<?php echo $data['title']; ?>" required /></td>
    </tr>
    <tr>
      <td width="25%"><label for="cost"> Item Cost</label></td>
      <td><input type="number" name="cost" id="cost" value="<?php echo $data['cost']; ?>" step='0.01' placeholder='&#8358; 0.00' /></td>
    </tr>
    <?php if (isset($_REQUEST['id'])) { ?>
        <input type="hidden" name="ref" id="ref" value="<?php echo $data['ref']; ?>" />
    <?php } ?>
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
        <?php if (isset($_REQUEST['return'])) { ?>
        <input type="hidden" name="return" id="return" value="<?php echo $_REQUEST['return']."&id=".$data['ref']; ?>" />
        <?php } ?>
        <button name="submit" id="submit" type="submit" class="button">
        <i class="fa fa-floppy-o fa-lg"></i>
        <?php echo $tag; ?>
        </button>
        <button name="reset" id="reset" type="reset" class="button">
        <i class="fa fa-undo fa-lg"></i>
        Reset
        </button
      ></td>
    </tr>
  </table>
</form>
<h2>All Billing Components</h2>
<table class='striped' id="datatable_list">
  <thead>
    <tr>
      <td>#</td>
      <td>Title</td>
      <td>Cost</td>
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
      <td><?php echo $list[$i]['cost']; ?></td>
      <td><?php echo $list[$i]['status']; ?></td>
      <td><?php echo self::getuser( $list[$i]['created_by'] ); ?></td>
      <td><?php echo $list[$i]['create_time']; ?></td>
      <td><?php echo self::getuser( $list[$i]['last_modified_by'] ); ?></td>
      <td><?php echo $list[$i]['modify_time']; ?></td>
      <td><a href="<?php echo admin_url('admin.php?page=lh-billing-component&changeStatus='.$list[$i]['status'].'&id='.$list[$i]['ref']); ?>" onClick="return confirm('<?php echo $tag; ?>. are you sure you want to continue ?')"><?php echo self::getLink($list[$i]['status']); ?></a> <a href="<?php echo admin_url('admin.php?page=lh-billing-component&edit&id='.$list[$i]['ref']); ?>" title="Edit"><i class="fas fa-edit"></i></a>
      <a href="<?php echo admin_url('admin.php?page=lh-billing-component&remove&id='.$list[$i]['ref']); ?>" onClick="return confirm('this action will remove this item. This action can not be undone. are you sure you want to continue ?')"><i class="fas fa-trash-alt"style="color:red"></i></a></td>
    </tr>
    <?php $count++;
    } ?>
  </tbody>
</table>

<h3>Legend</h3>
<i class="fas fa-play" title="Activate" style="color:green"></i>&nbsp;&nbsp;Activate Component<br>
<i class="fas fa-stop" title="Deactivate" style="color:red"></i>&nbsp;&nbsp;Deactivate Component<br>
<i class="fas fa-edit" title="Edit"></i>&nbsp;&nbsp;Edit Component<br>
<i class="fas fa-trash-alt"></i>&nbsp;&nbsp;Remove Component
</div>

<script>
jQuery(function ($) {
    $('#datatable_list').dataTable({
	  "pageLength": 25
	});
} );
</script>
