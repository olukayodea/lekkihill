<?php
$list = appointments::$list;
$data = self::$viewData;
$appointmentData = self::$appointmentData; ?>
<div class="wrap">
  <h1 class="wp-heading-inline">Clinic</h1>
  <div id="col-container" class="wp-clearfix">
    <div id="col-left">
      <div class="col-wrap">
        <div class="form-wrap">
            <h2>Search Patient</h2>
          <?php if (isset(self::$message)): ?><div class="updated"><p><?php echo self::$message; ?></p></div><?php endif; ?>
          <?php if (isset(self::$error_message)): ?><div class="error"><p><?php echo self::$error_message; ?></p></div><?php endif; ?>
          <form id="form2" name="form2" method="post" action="<?php echo admin_url('admin.php?page=lh-manage-patient'); ?>">
            <div class="form-field form-required term-search-wrap">
              <label for="search"> Search</label>
              <input type="text" name="search" id="search" value="" required />
            </div>
          </form>

          <?php if (isset($_REQUEST['appointment'])) { ?>
            <h2>Appointment's Details</h2>

            <form id="form2" name="form2" method="post" action="">
              <div class="form-field form-required term-names-wrap">
                <label for="names"> Patient's Name</label>
                <strong><?php echo $appointmentData['names']; ?></strong>
              </div>
              <div class="form-field form-required term-names-wrap">
                <label for="email"> Patient's Email</label>
                <strong><?php echo $appointmentData['email']; ?></strong>
              </div>
              <div class="form-field form-required term-names-wrap">
                <label for="email"> Patient's Phone Number</label>
                <strong><?php echo $appointmentData['phone']; ?></strong>
              </div>
              <div class="form-field form-required term-names-wrap">
                <label for="procedure">Procedure</label>
                <strong><?php echo $appointmentData['procedure']; ?></strong>
              </div>
              <div class="form-field form-required term-names-wrap">
                <label for="message">Message</label>
                <strong><?php echo $appointmentData['message']; ?></strong>
              </div>
              <div class="form-field form-required term-next_appointment-wrap">
                <label for="next_appointment"> Appointment Date</label>
                <strong><?php echo $appointmentData['next_appointment']; ?></strong>
              </div>
            </form>
          <?php } ?>
          <?php if ( (isset($_REQUEST['patient'])) || (self::$showPatient) ) { ?>
            <h2>Patient's Details</h2>
          <?php } ?>
        </div>
      </div>
    </div>
    <div id="col-right">
      <div class="col-wrap">
        <h2>Appointments Scheduled for Today</h2>
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
                <td class="column-columnname"><?php echo $list[$i]['next_appointment']; ?></td>
                <td class="column-columnname">
                    <a href="<?php echo admin_url('admin.php?page=lh-manage-clinic&appointment&id='.$list[$i]['ref']); ?>" title="Open Records <?php echo $list[$i]['last_name']." ".$list[$i]['first_name']; ?>"><i class="fas fa-clinic-medical"></i></a>
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
    
    var se_ajax_url = '<?php echo get_rest_url().'api/patient/search'; ?>';

    var api_key = Math.floor(Math.random() * 100001);
    var user_token = '<?php echo self::$logged_in_user->user_token; ?>';
    var api_token = btoa(api_key+"_"+user_token)

    $.ajaxSetup({
        headers: {
            'Content-Type': 'application/json',
            'api-key': api_key,
            'api-token': api_token
            }
    });

    $('#search').autocomplete({
        source: se_ajax_url,
        minLength: 2,
        select: function( event, ui ) {
            window.location = '<?php echo admin_url('admin.php?page=lh-manage-clinic&patient&='); ?>' + ui.item.id;
        }
    });
   
} );
</script>
