<?php
$list = self::$list;
$data = self::$viewData;
$patientList = patient::$patientList; ?>
<div class="wrap">
  <h1 class="wp-heading-inline">Appointment</h1>
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
        <?php if (isset($_REQUEST['open'])) { ?>
          <h2>Review Appointment</h2>
          <?php if (isset(self::$message)): ?><div class="updated"><p><?php echo self::$message; ?></p></div><?php endif; ?>
          <?php if (isset(self::$error_message)): ?><div class="error"><p><?php echo self::$error_message; ?></p></div><?php endif; ?>
          <form id="form2" name="form2" method="post" action="">
            <div class="form-field form-required term-names-wrap">
              <label for="names"> Patient's Name</label>
              <strong><?php echo $data['names']; ?></strong>
            </div>
            <div class="form-field form-required term-names-wrap">
              <label for="email"> Patient's Email</label>
              <strong><?php echo $data['email']; ?></strong>
            </div>
            <div class="form-field form-required term-names-wrap">
              <label for="email"> Patient's Phone Number</label>
              <strong><?php echo $data['phone']; ?></strong>
            </div>
            <div class="form-field form-required term-names-wrap">
              <label for="procedure">Procedure</label>
              <strong><?php echo $data['procedure']; ?></strong>
            </div>
            <div class="form-field form-required term-names-wrap">
              <label for="message">Message</label>
              <strong><?php echo $data['message']; ?></strong>
            </div>
            <div class="form-field form-required term-next_appointment-wrap">
              <label for="next_appointment"> Appointment Date</label>
              <input type="text" name="next_appointment" id="next_appointment" value="<?php echo $data['next_appointment']; ?>" required />
            </div>
            <input type="hidden" name="status" id="status" value="SCHEDULED" />
            <button name="submit" id="submit" type="submit" class="button button-primary"><i class="fa fa-calendar-check fa-lg"></i>&nbsp;<?php if ($data['status'] == "SCHEDULED") { ?>Re-Schedule Appointment<?php } else { ?>Book Appointment<?php } ?></button>
            <button name="cancel" id="submit" type="submit" class="button button-cancel"><i class="fa fa-trash-alt fa-lg"></i>&nbsp;Cancel Appointment</button>
          </form>
        <?php } else { ?>
          <h2>Add New Appointment</h2>
          <?php if (isset(self::$message)): ?><div class="updated"><p><?php echo self::$message; ?></p></div><?php endif; ?>
          <?php if (isset(self::$error_message)): ?><div class="error"><p><?php echo self::$error_message; ?></p></div><?php endif; ?>
          <form id="form2" name="form2" method="post" action="<?php echo admin_url('admin.php?page=lh-manage-appointments'); ?>">
            <?php if (!isset($_REQUEST['new'])) { ?>
            <div class="form-field form-required term-editable-select-wrap">
              <label for="editable-select"> Patient's Name</label>
              <select name="randomTex" id="editable-select" value="<?php echo $data['names']; ?>" onchange="changeName();">
              <option value="" selected></option>
              <?php for ($i = 0; $i < count($patientList); $i++) { ?>
                <option value="<?php echo $patientList[$i]['ref']; ?>"><?php echo $patientList[$i]['last_name']." ".$patientList[$i]['first_name']; ?></option>
              <?php } ?> 
              </select>
              <input name="names" id="names" value="<?php echo $data['names']; ?>" type="hidden">
            </div>
            <?php } else { ?>
            <div class="form-field form-required term-names-wrap">
              <label for="names"> Patient's Name</label>
            <input type="text" name="names" id="names" value="<?php echo $data['names']; ?>" required<?php if (isset($_REQUEST['new'])) { ?> readonly<?php } ?> />
            </div>
            <?php } ?>
            <div class="form-field form-required term-email-wrap">
              <label for="email"> Patient's Email</label>
              <input type="email" name="email" id="email" value="<?php echo $data['email']; ?>" required<?php if (isset($_REQUEST['new'])) { ?> readonly<?php } ?> />
            </div>
            <div class="form-field form-required term-phone-wrap">
              <label for="phone"> Patient's Phone Number</label>
              <input type="tel" name="phone" id="phone" value="<?php echo $data['phone']; ?>" required<?php if (isset($_REQUEST['new'])) { ?> readonly<?php } ?> />
            </div>
            <div class="form-field form-required term-procedure-wrap">
              <label for="procedure">Procedure</label></td>
              <select id="procedure" name="procedure" required>
                <option value="">Select One</option>
                <option value="Botox">Consultation</option>
                <option value="Botox">Investigations</option>
                <option value="Breast Augmentation">Breast Augmentation</option>
                <option value="Botox">Botox</option>
                <option value="Butt Implant">Butt Implant</option>
                <option value="Facial">Facial</option>
                <option value="Full Body">Full Body</option>
                <option value="Liposuction">Liposuction</option>
              </select>
            </div>
            <div class="form-field form-required term-next_appointment_date-wrap">
              <label for="next_appointment"> Appointment Date</label>
              <input type="text" name="next_appointment_date" id="next_appointment_date" value="<?php echo $data['next_appointment']; ?>" required />
            </div>
            <?php if (!isset($_REQUEST['new'])) { ?>
            <input type="hidden" name="patient_id" id="patient_id" value="<?php echo $data['patient_id']; ?>" />
            <?php } ?>
            <input type="hidden" name="status" id="status" value="SCHEDULED" />
            <button name="submit" id="submit" type="submit" class="button button-primary"><i class="fa fa-calendar-check fa-lg"></i>&nbsp;Book Appointment</button>
            <button name="reset" id="reset" type="reset" class="button"><i class="fa fa-undo fa-lg"></i>&nbsp;Reset</button>
          </form>
        <?php } ?>
        </div>
      </div>
    </div>
    <div id="col-right">
      <div class="col-wrap">
        <h2>Unscheduled Appointment Bookings</h2>
        <table class='widefat striped fixed' id="datatable_list">
          <thead>
            <tr>
              <th class="manage-column column-cb check-column"></th>
              <th class="manage-column column-columnname" scope="col">Name</th>
              <th class="manage-column column-columnname" scope="col">Procedure</th>
              <th class="manage-column column-columnname" scope="col">Contact</th>
              <th class="manage-column column-columnname" scope="col">Time</th>
              <th class="manage-column column-columnname" scope="col">Action</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th class="manage-column column-cb check-column"></th>
              <th class="manage-column column-columnname" scope="col">Name</th>
              <th class="manage-column column-columnname" scope="col">Procedure</th>
              <th class="manage-column column-columnname" scope="col">Contact</th>
              <th class="manage-column column-columnname" scope="col">Time</th>
              <th class="manage-column column-columnname" scope="col">Action</th>
            </tr>
          </tfoot>
          <tbody>
            <?php $count = 1;
            for ($i = 0;  $i < count($list); $i++) { ?>
            <tr>
              <th class="check-column" scope="row"><?php echo $count; ?></th>
              <td class="column-columnname"><?php echo $list[$i]['names']; ?></td>
              <td class="column-columnname"><?php echo $list[$i]['procedure']; ?></td>
              <td class="column-columnname"><a href="mailTo:<?php echo $list[$i]['email']; ?>"><i class="fas fa-envelope"></i></a>&nbsp;<a href="tel:<?php echo $list[$i]['phone']; ?>"><i class="fas fa-tty"></i></a></td>
              <td class="column-columnname"><?php echo $list[$i]['create_time']; ?></td>
              <td class="column-columnname"><a href="<?php echo admin_url('admin.php?page=lh-manage-appointments&open&id='.$list[$i]['ref']); ?>" title="Review"><i class="fas fa-folder-open"></i></a>&nbsp;<a href="<?php echo admin_url('admin.php?page=lh-manage-appointments&remove&id='.$list[$i]['ref']); ?>" style="color:red" title="Remove" onClick="return confirm('this action will remove this appointment completely. are you sure you want to continue ?')"><i class="far fa-trash-alt"></i></a></td>
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
    $('#next_appointment_date').datetimepicker({
        minDate:'<?php echo date("Y-m-d"); ?>',
        minTime:'9:00',
        maxTime:'16:30'
    });
    $('#datatable_list').DataTable();
    $('#editable-select').editableSelect().on('select.editable-select', function (e, li) {
        $('#last-selected').html(
            $('#names').val(li.text()),
            getData(li.val())
        );
    });
   
} );

function changeName() {
  document.getElementById('names').value = document.getElementById('editable-select').value
}

function getData(val) {
  var se_ajax_url = '<?php echo get_rest_url().'api/patient/'; ?>'+val;

  var api_key = Math.floor(Math.random() * 100001);
  var user_token = '<?php echo self::$userToken; ?>';
  var api_token = btoa(api_key+"_"+user_token)

  jQuery.ajaxSetup({
      headers: {
          'Content-Type': 'application/json',
          'api-key': api_key,
          'api-token': api_token
          }
  });
  jQuery.get( se_ajax_url, function( data ) {
    document.getElementById('patient_id').value = data.data.ref;
    document.getElementById('email').value = data.data.email;
    document.getElementById('phone').value = data.data.phone_number;
    console.log(data.data);
  });
}
</script>
