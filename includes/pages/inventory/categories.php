<?php
$list = self::$list;
$data = self::$viewData;
?>
<div class="wrap">
  <h1 class="wp-heading-inline">Categories</h1>
  <form class="search-form wp-clearfix" method="get">
    <p class="search-box">
      <label class="screen-reader-text" for="tag-search-input">Search Categories:</label>
      <input type="search" id="tag-search-input" name="s" value="" />
      <input type="submit" id="search-submit" class="button" value="Search Categories"  />
    </p>
  </form>
  <div id="col-container" class="wp-clearfix">
    <div id="col-left">
      <div class="col-wrap">
        <div class="form-wrap">
          <h2>Manage Inventory Category</h2>
          <?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
          <?php if (isset($error_message)): ?><div class="error"><p><?php echo $error_message; ?></p></div><?php endif; ?>
          <form id="form2" name="form2" method="post" action="" autocomplete="off">
            <div class="form-field form-required term-title-wrap">
              <label for="title"> Category Name</label>
              <input type="text" name="title" id="title" value="<?php echo $data['title']; ?>" required />
            </div>

            <?php if (isset($_REQUEST['id'])) { ?>
                <input type="hidden" name="ref" id="ref" value="<?php echo $data['ref']; ?>" />
            <?php } ?>
            <div class="form-field form-required term-status-wrap">
              <label for="status">Status</label></td>
              <select id="status" name="status" required>
                <option value="ACTIVE"<?php if ($data['status'] == "ACTIVE") { ?> selected<?php } ?>>Active</option>
                <option value="INACTIVE"<?php if ($data['status'] == "INACTIVE") { ?> selected<?php } ?>>In-Active</option>
              </select>
            </div>
            <?php if (isset($_REQUEST['return'])) { ?>
              <input type="hidden" name="return" id="return" value="<?php echo $_REQUEST['return']."&id=".$data['ref']; ?>" />
            <?php } ?>
            <button name="submit" id="submit" type="submit" class="button button-primary"><i class="fa fa-floppy-o fa-lg"></i><?php echo self::$tag; ?></button>
            <button name="reset" id="reset" type="reset" class="button"><i class="fa fa-undo fa-lg"></i>Reset</button>
          </form>
        </div>
      </div>
    </div>
    <div id="col-right">
      <div class="col-wrap">
        <h2>All Categories</h2>
        <table class='widefat striped fixed' id="datatable_list">
          <thead>
            <tr>
              <th class="manage-column column-cb check-column"></th>
              <th class="manage-column column-columnname" scope="col">Category</th>
              <th class="manage-column column-columnname" scope="col">Status</th>
              <th class="manage-column column-columnname" scope="col">Created By</th>
              <th class="manage-column column-columnname" scope="col">Time</th>
              <th class="manage-column column-columnname" scope="col">Last Modified</th>
              <th class="manage-column column-columnname" scope="col">Time</th>
              <th class="manage-column column-columnname" scope="col">Action</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th class="manage-column column-cb check-column"></th>
              <th class="manage-column column-columnname" scope="col">Category</th>
              <th class="manage-column column-columnname" scope="col">Status</th>
              <th class="manage-column column-columnname" scope="col">Created By</th>
              <th class="manage-column column-columnname" scope="col">Time</th>
              <th class="manage-column column-columnname" scope="col">Last Modified</th>
              <th class="manage-column column-columnname" scope="col">Time</th>
              <th class="manage-column column-columnname" scope="col">Action</th>
            </tr>
          </tfoot>
          <tbody>
            <?php $count = 1;
            for ($i = 0;  $i < count($list); $i++) { ?>
            <tr>
              <th class="check-column" scope="row"><?php echo $count; ?></th>
              <td class="column-columnname"><?php echo $list[$i]['title']; ?></td>
              <td class="column-columnname"><?php echo $list[$i]['status']; ?></td>
              <td class="column-columnname"><?php echo self::getuser( $list[$i]['created_by'] ); ?></td>
              <td class="column-columnname"><?php echo $list[$i]['create_time']; ?></td>
              <td class="column-columnname"><?php echo self::getuser( $list[$i]['last_modified_by'] ); ?></td>
              <td class="column-columnname"><?php echo $list[$i]['modify_time']; ?></td>
              <td class="column-columnname"><a href="<?php echo admin_url('admin.php?page=lh-category-inventory&changeStatus='.$list[$i]['status'].'&id='.$list[$i]['ref']); ?>" onClick="return confirm('<?php echo $tag; ?>. are you sure you want to continue ?')"><?php echo self::getLink($list[$i]['status']); ?></a> <a href="<?php echo admin_url('admin.php?page=lh-category-inventory&edit&id='.$list[$i]['ref']); ?>" title="Edit"><i class="fas fa-edit"></i></a>
              <a href="<?php echo admin_url('admin.php?page=lh-category-inventory&remove&id='.$list[$i]['ref']); ?>" onClick="return confirm('this action will remove this item. This action can not be undone. are you sure you want to continue ?')"><i class="fas fa-trash-alt"style="color:red"></i></a></td>
            </tr>
            <?php $count++;
            } ?>
          </tbody>
        </table>
      </div>
    </div>
    <h3>Legend</h3>
    <i class="fas fa-play" title="Activate" style="color:green"></i>&nbsp;&nbsp;Activate Inventory Category<br>
    <i class="fas fa-stop" title="Deactivate" style="color:red"></i>&nbsp;&nbsp;Deactivate Inventory Category<br>
    <i class="fas fa-edit" title="Edit"></i>&nbsp;&nbsp;Edit Category<br>
    <i class="fas fa-trash-alt"></i>&nbsp;&nbsp;Remove Category
  </div>
</div>

<script>
jQuery(function ($) {
} );
</script>
