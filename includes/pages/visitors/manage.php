<?php
$list = self::$list;
$data = self::$viewData; ?>
<div class="wrap">
  <h1 class="wp-heading-inline">Visitors</h1>
  <!-- <form class="search-form wp-clearfix" method="get">
    <p class="search-box">
      <label class="screen-reader-text" for="tag-search-input">Search Appointments:</label>
      <input type="search" id="tag-search-input" name="s" value="" />
      <input type="submit" id="search-submit" class="button" value="Search Appointments"  />
    </p>
  </form> -->
  <div id="col-container" class="wp-clearfix">
    <div id="col-left">
      <div class="col-wrap">
        <div class="form-wrap">
          <h2>Add New Visitor</h2>
          <?php if (isset(self::$message)): ?><div class="updated"><p><?php echo self::$message; ?></p></div><?php endif; ?>
          <?php if (isset(self::$error_message)): ?><div class="error"><p><?php echo self::$error_message; ?></p></div><?php endif; ?>
          <form id="form2" name="form2" method="post" action="<?php echo admin_url('admin.php?page=lh-manage-visitors'); ?>">
            <div class="form-field form-required term-last_name-wrap">
              <label for="last_name"> Last Name</label>
              <input type="text" name="last_name" id="last_name" value="<?php echo $data['last_name']; ?>" required />
            </div>
            <div class="form-field form-required term-first_name-wrap">
              <label for="first_name"> Other Names</label>
              <input type="text" name="first_name" id="first_name" value="<?php echo $data['first_name']; ?>" required />
            </div>
            <div class="form-field form-required term-email-wrap">
              <label for="email">Email</label>
              <input type="email" name="email" id="email" value="<?php echo $data['email']; ?>" required />
            </div>
            <div class="form-field form-required term-phone-wrap">
              <label for="phone">Phone Number</label>
              <input type="tel" name="phone_number" id="phone_number" value="<?php echo $data['phone_number']; ?>" required />
            </div>
            <div class="form-field form-required term-address-wrap">
              <label for="address"> Address</label>
              <textarea name="address" id="address" required><?php echo $data['address']; ?></textarea>
            </div>
            <div class="form-field form-required term-whom_to_see-wrap">
              <label for="whom_to_see">Whom to See</label>
              <input type="text" name="whom_to_see" id="whom_to_see" value="<?php echo $data['whom_to_see']; ?>" required />
            </div>
            <div class="form-field form-required term-resason-wrap">
              <label for="resason"> Reason of Visit</label>
              <textarea name="resason" id="resason" required><?php echo $data['resason']; ?></textarea>
            </div>
            <button name="submit" id="submit" type="submit" class="button button-primary"><i class="fa fa-calendar-check fa-lg"></i>&nbsp;Add Visitor</button>
            <button name="reset" id="reset" type="reset" class="button"><i class="fa fa-undo fa-lg"></i>&nbsp;Reset</button>
          </form>
        </div>
      </div>
    </div>
    <div id="col-right">
      <div class="col-wrap">
        <h2>Visitors</h2>
        <table class='widefat striped fixed' id="datatable_list">
          <thead>
            <tr>
              <th class="manage-column column-cb check-column"></th>
              <th class="manage-column column-columnname" scope="col">Last Name</th>
              <th class="manage-column column-columnname" scope="col">Other Names</th>
              <th class="manage-column column-columnname" scope="col">email</th>
              <th class="manage-column column-columnname" scope="col">Phone Number</th>
              <th class="manage-column column-columnname" scope="col">Address</th>
              <th class="manage-column column-columnname" scope="col">Whom to See</th>
              <th class="manage-column column-columnname" scope="col">Reason</th>
              <th class="manage-column column-columnname" scope="col">Time</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th class="manage-column column-cb check-column"></th>
              <th class="manage-column column-columnname" scope="col">Last Name</th>
              <th class="manage-column column-columnname" scope="col">Other Names</th>
              <th class="manage-column column-columnname" scope="col">email</th>
              <th class="manage-column column-columnname" scope="col">Phone Number</th>
              <th class="manage-column column-columnname" scope="col">Address</th>
              <th class="manage-column column-columnname" scope="col">Whom to See</th>
              <th class="manage-column column-columnname" scope="col">Reason</th>
              <th class="manage-column column-columnname" scope="col">Time</th>
            </tr>
          </tfoot>
          <tbody>
            <?php $count = 1;
            for ($i = 0;  $i < count($list); $i++) { ?>
            <tr>
              <th class="check-column" scope="row"><?php echo $count; ?></th>
              <td class="column-columnname"><?php echo $list[$i]['last_name']; ?></td>
              <td class="column-columnname"><?php echo $list[$i]['first_name']; ?></td>
              <td class="column-columnname"><?php echo $list[$i]['email']; ?></td>
              <td class="column-columnname"><?php echo $list[$i]['phone_number']; ?></td>
              <td class="column-columnname"><?php echo $list[$i]['address']; ?></td>
              <td class="column-columnname"><?php echo $list[$i]['whom_to_see']; ?></td>
              <td class="column-columnname"><?php echo $list[$i]['resason']; ?></td>
              <td class="column-columnname"><?php echo $list[$i]['create_time']; ?></td>
            </tr>
            <?php $count++;
            } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
jQuery(function ($) {
    $('#procedure').select2();
    $('#next_appointment').datetimepicker({
        minDate:'<?php echo date("Y-m-d"); ?>',
        minTime:'9:00',
        maxTime:'16:30'
    });
    $('#datatable_list').DataTable();
    $('#editable-select').editableSelect();
   
} );
</script>
