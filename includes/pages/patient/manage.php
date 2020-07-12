<?php
$list = self::$list;
$data = self::$viewData; ?>
<div class="wrap">
  <h1 class="wp-heading-inline">Patients' Record</h1>
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
          <?php if (isset($_REQUEST['edit'])) { ?>
              <h2><?php echo "Modify ".$data['last_name']."'s Record"; ?></h2>
          <?php } else { ?>
              <h2>Add New Patient</h2>
          <?php } ?>
          <?php if (isset(self::$message)): ?><div class="updated"><p><?php echo self::$message; ?></p></div><?php endif; ?>
          <?php if (isset(self::$error_message)): ?><div class="error"><p><?php echo self::$error_message; ?></p></div><?php endif; ?>
          <form id="form2" name="form2" method="post" action="<?php echo admin_url('admin.php?page=lh-manage-patient'); ?>">
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
              <label for="phone"> Patient's Phone Number</label>
              <input type="tel" name="phone_number" id="phone_number" value="<?php echo $data['phone_number']; ?>" required />
            </div>
            <div class="form-field form-required term-sex-wrap">
              <label for="sex">Sex</label></td>
              <select id="sex" name="sex" required>
                <option value="">Select One</option>
                <option <?php if ($data['sex'] == "Female") { ?>selected <?php } ?>value="Female">Female</option>
                <option <?php if ($data['sex'] == "Male") { ?>selected <?php } ?> value="Male">Male</option>
              </select>
            </div>
            <div class="form-field form-required term-address-wrap">
              <label for="address"> Address</label>
              <textarea name="address" id="address" required><?php echo $data['address']; ?></textarea>
            </div>
            <div class="form-field form-required term-age-wrap">
              <label for="next_appointment"> Date of Birth</label>
              <input type="date" name="age" id="age" value="<?php echo $data['age']; ?>" max="<?php echo (date("Y")-18)."-".date("m")."-".date("d"); ?>" required value="<?php echo $data['age']; ?>" />
            </div>
            <div class="form-field form-required term-next_of_Kin-wrap">
              <label for="next_of_Kin"> Next of Kin</label>
              <input type="text" name="next_of_Kin" id="next_of_Kin" value="<?php echo $data['next_of_Kin']; ?>" required />
            </div>
            <div class="form-field form-required term-next_of_contact-wrap">
              <label for="phone"> Next of Kin Contact</label>
              <input type="tel" name="next_of_contact" id="next_of_contact" value="<?php echo $data['next_of_contact']; ?>" required />
            </div>
            <div class="form-field form-required term-next_of_address-wrap">
              <label for="next_of_address"> Next of Kin Address</label>
              <textarea name="next_of_address" id="next_of_address" required><?php echo $data['next_of_address']; ?></textarea>
            </div>
            <?php if (isset($_REQUEST['edit'])) { ?>
                <input type="hidden" name="ref" value="<?php echo $data['ref']; ?>" required />
                <button name="submit" id="submit" type="submit" class="button button-primary"><i class="fa fa-calendar-check fa-lg"></i>&nbsp;Save Modification</button>
                <button name="button" id="button" type="button" class="button"><i class="fa fa-undo fa-lg"></i>&nbsp;Cancel Edit</button>
            <?php } else { ?>
                <button name="submit" id="submit" type="submit" class="button button-primary"><i class="fa fa-calendar-check fa-lg"></i>&nbsp;Add Patient</button>
                <button name="reset" id="reset" type="reset" class="button"><i class="fa fa-undo fa-lg"></i>&nbsp;Reset</button>
            <?php } ?>
          </form>
        </div>
      </div>
    </div>
    <div id="col-right">
      <div class="col-wrap">
        <h2>Current Patients</h2>
        <table class='widefat striped fixed' id="datatable_list">
          <thead>
            <tr>
              <th class="manage-column column-cb check-column"></th>
              <th class="manage-column column-columnname" scope="col">Patient No</th>
              <th class="manage-column column-columnname" scope="col">Last Name</th>
              <th class="manage-column column-columnname" scope="col">Other Names</th>
              <th class="manage-column column-columnname" scope="col">email</th>
              <th class="manage-column column-columnname" scope="col">Phone Number</th>
              <th class="manage-column column-columnname" scope="col">Action</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th class="manage-column column-cb check-column"></th>
              <th class="manage-column column-columnname" scope="col">Patient No</th>
              <th class="manage-column column-columnname" scope="col">Last Name</th>
              <th class="manage-column column-columnname" scope="col">Other Names</th>
              <th class="manage-column column-columnname" scope="col">email</th>
              <th class="manage-column column-columnname" scope="col">Phone Number</th>
              <th class="manage-column column-columnname" scope="col">Action</th>
            </tr>
          </tfoot>
          <tbody>
            <?php $count = 1;
            for ($i = 0;  $i < count($list); $i++) { ?>
            <tr>
              <th class="check-column" scope="row"><?php echo $count; ?></th>
              <td class="column-columnname"><?php echo self::patienrNumber( $list[$i]['ref'] ); ?></td>
              <td class="column-columnname"><?php echo $list[$i]['last_name']; ?></td>
              <td class="column-columnname"><?php echo $list[$i]['first_name']; ?></td>
              <td class="column-columnname"><?php echo $list[$i]['email']; ?></td>
              <td class="column-columnname"><?php echo $list[$i]['phone_number']; ?></td>
              <td class="column-columnname">
                <a href="<?php echo admin_url('admin.php?page=lh-manage-clinic&patient&id='.$list[$i]['ref']); ?>" title="Go to Clinic"><i class="fas fa-clinic-medical"></i></a>&nbsp;
                <a href="<?php echo admin_url('admin.php?page=lh-manage-patient&edit&id='.$list[$i]['ref']); ?>" title="Edit <?php echo $list[$i]['last_name']." ".$list[$i]['first_name']; ?>"><i class="far fa-edit"></i></a>&nbsp;
                <a href="<?php echo admin_url('admin.php?page=lh-manage-appointments&new&id='.$list[$i]['ref']); ?>" title="Schedule Appointment <?php echo $list[$i]['last_name']." ".$list[$i]['first_name']; ?>"><i class="far fa-clock"></i></a>
              </td>
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
