<div class="wrap">
<h2>Inventory Report</h2>
<?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
<?php if (isset($error_message)): ?><div class="error"><p><?php echo $error_message; ?></p></div><?php endif; ?>
<form id="form2" name="form2" method="post" action="<?php echo $url; ?>">
  <table width="50%" border="0">
    <tr>
      <td width="25%"><label for="from"> From</label></td>
      <td><input type="date" name="from" id="from" max="<?php echo date("Y-m-d"); ?>" value="<?php echo $_POST['from']; ?>" required /></td>
    </tr>
    <tr>
      <td width="25%"><label for="to"> To</label></td>
      <td><input type="date" name="to" id="to" max="<?php echo date("Y-m-d"); ?>" value="<?php echo $_POST['to']; ?>" required /></td>
    </tr>
    <tr>
      <td width="25%"><label for="view">Filter</label></td>
      <td>
          <select id="view" name="view">
              <option value="All"<?php if ($_POST['view'] == "All") { ?> selected<?php } ?>>All</option>
              <option value="search"<?php if ($_POST['view'] == "search") { ?> selected<?php } ?>>Search Keyword</option>
              <option value="category"<?php if ($_POST['view'] == "category") { ?> selected<?php } ?>>Category</option>
              <option value="user"<?php if ($_POST['view'] == "user") { ?> selected<?php } ?>>User</option>
          </select>
        </td>
    </tr>
    <tr id="category_row" style="display:none">
      <td width="25%"><label for="category">Category</label></td>
      <td>
          <select id="category" name="category">
              <option value="">Select One</option>
              <?php for ($i = 0; $i < count($list); $i++) { ?>
                <option value="<?php echo $list[$i]['ref']; ?>"<?php if ($list[$i]['ref'] == $_POST['category']) { ?> selected<?php } ?>><?php echo $list[$i]['title']; ?></option>
              <?php } ?>
          </select>
        </td>
    </tr>
    <tr id="user_row" style="display:none">
      <td width="25%"><label for="user">Users</label></td>
      <td>
          <select id="user" name="user">
              <option value="">Select One</option>
              <?php foreach ( $users as $user ) { ?>
                <option value="<?php echo $user->ID; ?>"<?php if ($user->ID == $_POST['user']) { ?> selected<?php } ?>><?php echo esc_html( $user->display_name) ?></option>
              <?php } ?>
          </select>
        </td>
    </tr>
    <tr id="search_row" style="display:none">
      <td width="25%"><label for="search"> Search</label></td>
      <td><input type="text" name="search" id="search" value="<?php echo $_POST['search']; ?>" /></td>
    </tr>
    <tr>
      <td width="25%">&nbsp;</td>
      <td>
        <button name="save" id="save" type="submit" class="button">
        <i class="fas fa-search fa-lg"></i>&nbsp;Generate Report
        </button>
      </td>
    </tr>
  </table>
</form>
<?php if ($show == true) { ?>
<h2>Query Result</h2>
<p><?php echo $tag; ?></p>
<button type="submit" class="button">
<i class="fas fa-download fa-lg"></i>&nbsp;Download Report
</button>
<button type="submit" class="button">
<i class="fas fa-print fa-lg"></i>&nbsp;Print Report
</button>
<table class='striped' id="datatable_list">
  <thead>
    <tr>
      <td>#</td>
      <td>Date</td>
      <td>Item</td>
      <td>SKU</td>
      <td>Quantity Left</td>
      <td>Amount Added/Removed</td>
      <td>Total Quantity</td>
      <td>Added By</td>
    </tr>
  </thead>
  <tbody>
    <?php $count = 1;
    for ($i = 0;  $i < count($result); $i++) { ?>
    <tr>
      <td><?php echo $count; ?></td>
      <td><?php echo $result[$i]['create_time']; ?></td>
      <td><?php echo $result[$i]['title']; ?></td>
      <td><?php echo $result[$i]['sku']; ?></td>
      <td><?php echo number_format( $result[$i]['inventory_before_added'] ); ?></td>
      <td><?php echo ($result[$i]['inventory_added'] < 0 ? "(".number_format( abs( $result[$i]['inventory_added'] ) ).")" : number_format( abs( $result[$i]['inventory_added'] ) ) ); ?></td>
      <td><?php echo number_format( $result[$i]['inventory_before_added']+$result[$i]['inventory_added'] ); ?></td>
      <td><?php echo self::getuser( $result[$i]['added_by'] ); ?></td>
    </tr>
    <?php $count++;
    } ?>
  </tbody>
</table>
<?php } ?>
</div>
<script>
jQuery(function ($) {
    checkState();
    $( "#from" ).change(function() {
        var val = $(this).val();
        $("#to").prop('min',val);
    });
    $( "#view" ).change(function() {
        checkState();
    });
    $('#datatable_list').dataTable({
	  "pageLength": 25
    });
    
    function checkState() {
        var val = $("#view").val();
        if (val == "search") {
            $('#category_row').hide();
            $('#user_row').hide();
            $('#search_row').show();
            $("#search").prop('required',true);
            $('#user, #category').removeAttr( "required" )
        } else if (val == "category") {
            $('#category_row').show();
            $('#user_row').hide();
            $('#search_row').hide();
            $("#category").prop('required',true);
            $('#search, #user').removeAttr( "required" )
        } else if (val == "user") {
            $('#category_row').hide();
            $('#user_row').show();
            $('#search_row').hide();
            $("#user").prop('required',true);
            $('#search, #category').removeAttr( "required" )
        } else {
            $('#category_row').hide();
            $('#user_row').hide();
            $('#search_row').hide();
            $('#search, #category, #user').removeAttr( "required" )
        }
    }
} );
</script>
