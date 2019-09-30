<div class="wrap">
  <h2>Inventory Report</h2>
  <?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
  <?php if (isset($error_message)): ?><div class="error"><p><?php echo $error_message; ?></p></div><?php endif; ?>
  <div id="col-container" class="wp-clearfix">
    <div id="col-left">
      <div class="col-wrap">
        <div class="form-wrap">
          <form id="form2" name="form2" method="post" action="<?php echo $url; ?>">
            <div class="form-field form-required term-category-wrap">  
              <label for="from"> From</label>
              <input type="date" name="from" id="from" max="<?php echo date("Y-m-d"); ?>" value="<?php echo $_POST['from']; ?>" required />
            </div>
            <div class="form-field form-required term-category-wrap">  
              <label for="to"> To</label>
              <input type="date" name="to" id="to" max="<?php echo date("Y-m-d"); ?>" value="<?php echo $_POST['to']; ?>" required />
            </div>
            
            <div class="form-field form-required term-category-wrap">  
              <label for="view">Filter</label>
              <select id="view" name="view">
                <option value="All"<?php if ($_POST['view'] == "All") { ?> selected<?php } ?>>All</option>
                <option value="search"<?php if ($_POST['view'] == "search") { ?> selected<?php } ?>>Search Keyword</option>
                <option value="category"<?php if ($_POST['view'] == "category") { ?> selected<?php } ?>>Category</option>
                <option value="user"<?php if ($_POST['view'] == "user") { ?> selected<?php } ?>>User</option>
              </select>
            </div>
            <div id="category_row" style="display:none">
              <div class="form-field form-required term-category-wrap">  
                <label for="category">Category</label>
                <select id="category" name="category">
                  <option value="">Select One</option>
                  <?php for ($i = 0; $i < count($list); $i++) { ?>
                    <option value="<?php echo $list[$i]['ref']; ?>"<?php if ($list[$i]['ref'] == $_POST['category']) { ?> selected<?php } ?>><?php echo $list[$i]['title']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div id="user_row" style="display:none">
              <div class="form-field form-required term-user-wrap">  
                <label for="user">Users</label>
                <select id="user" name="user">
                  <option value="">Select One</option>
                  <?php foreach ( $users as $user ) { ?>
                    <option value="<?php echo $user->ID; ?>"<?php if ($user->ID == $_POST['user']) { ?> selected<?php } ?>><?php echo esc_html( $user->display_name) ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div id="search_row" style="display:none">
              <div class="form-field form-required term-search-wrap">  
                <label for="search"> Search</label>
                <input type="text" name="search" id="search" value="<?php echo $_POST['search']; ?>" />
              </div>
            </div>
            <button name="save" id="save" type="submit" class="button button-primary"><i class="fas fa-search fa-lg"></i>&nbsp;Generate Report</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <?php if ($show == true) { ?>
  <h2>Query Result</h2>
  <p><?php echo $tag; ?></p>
  <button type="button" class="button" onclick="location='<?php echo admin_url('admin.php?page=lh-report-inventory&view='.$_POST['view'].'&search='.$_POST['search'].'&category='.$_POST['category'].'&user='.$_POST['user'].'&from='.urlencode($_POST['from']).'&to='.urlencode($_POST['to'])); ?>&downloadInventoryPDF'">
  <i class="fas fa-file-pdf fa-lg"></i>&nbsp;Download or Print PDF
  </button>
  <button type="button" class="button" onclick="location='<?php echo admin_url('admin.php?page=lh-report-inventory&view='.$_POST['view'].'&search='.$_POST['search'].'&category='.$_POST['category'].'&user='.$_POST['user'].'&from='.urlencode($_POST['from']).'&to='.urlencode($_POST['to'])); ?>&downloadInventoryCSV'">
  <i class="fas fa-file-excel fa-lg"></i>&nbsp;Download as Excel
  </button>
  <table class='widefat striped fixed' id="datatable_list">
    <thead>
      <tr>
        <th class="manage-column column-cb check-column"></th>
        <th class="manage-column column-columnname" scope="col">Date</th>
        <th class="manage-column column-columnname" scope="col">Item</th>
        <th class="manage-column column-columnname" scope="col">SKU</th>
        <th class="manage-column column-columnname" scope="col">Cost</th>
        <th class="manage-column column-columnname" scope="col">Quantity Left</th>
        <th class="manage-column column-columnname" scope="col">Amount Added/Removed</th>
        <th class="manage-column column-columnname" scope="col">Total Quantity</th>
        <th class="manage-column column-columnname" scope="col">Added By</th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th class="manage-column column-cb check-column"></th>
        <th class="manage-column column-columnname" scope="col">Date</th>
        <th class="manage-column column-columnname" scope="col">Item</th>
        <th class="manage-column column-columnname" scope="col">SKU</th>
        <th class="manage-column column-columnname" scope="col">Cost</th>
        <th class="manage-column column-columnname" scope="col">Quantity Left</th>
        <th class="manage-column column-columnname" scope="col">Amount Added/Removed</th>
        <th class="manage-column column-columnname" scope="col">Total Quantity</th>
        <th class="manage-column column-columnname" scope="col">Added By</th>
      </tr>
    </tfoot>
    <tbody>
      <?php $count = 1;
      for ($i = 0;  $i < count($result); $i++) { ?>
      <tr>
        <th class="check-column" scope="row"><?php echo $count; ?></th>
        <td class="column-columnname"><?php echo $result[$i]['create_time']; ?></td>
        <td class="column-columnname"><a href="<?php echo admin_url('admin.php?page=lh-add-inventory-view&id='.$result[$i]['ref']); ?>" title="View More"><?php echo $result[$i]['title']; ?></a></td>
        <td class="column-columnname"><?php echo $result[$i]['sku']; ?></td>
        <td class="column-columnname"><?php echo "&#8358; ".number_format($result[$i]['cost'], 2); ?></td>
        <td class="column-columnname"><?php echo number_format( $result[$i]['inventory_before_added'] ); ?></td>
        <td class="column-columnname"><?php echo ($result[$i]['inventory_added'] < 0 ? "(".number_format( abs( $result[$i]['inventory_added'] ) ).")" : number_format( abs( $result[$i]['inventory_added'] ) ) ); ?></td>
        <td class="column-columnname"><?php echo number_format( $result[$i]['inventory_before_added']+$result[$i]['inventory_added'] ); ?></td>
        <td class="column-columnname"><?php echo self::getuser( $result[$i]['added_by'] ); ?></td>
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
