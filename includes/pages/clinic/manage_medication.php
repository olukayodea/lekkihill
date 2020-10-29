<?php
  $medicationList = self::$medicationList;
  $inventoryList = self::$inventoryList;
  $data = self::$viewData;
  $appointmentData = self::$appointmentData;
  $vitalsData = self::$vitalsData;
  $allVitalsData = self::$allVitalsData;
  $balance = billing::$balance;
  $listInvoice = billing::$list_invoice;
  
  $managePatient = true;

  $rowCount = 0;
  
  if ( mktime(0, 0, 0, date("m"), date("d"), date("Y")) < strtotime( $vitalsData['create_time'], time() ) ) {
    $managePatient = true;
  }
?>
<style>
  .right {
    float: right;
  }
</style>
<input type="hidden" name="patient_id" id="patient_id" value="<?php echo $_REQUEST['id']; ?>">
<div class="wrap">
  <h1 class="wp-heading-inline">Medication</h1>
  <div id="col-container" class="wp-clearfix">
    <div id="col-left">
      <div class="col-wrap">
        <div class="form-wrap">
          <h2>Search Patient</h2>
          <div class="updated" id="updatedMessage" style="display: none"></div>
          <div class="error" id="errorMessage" style="display: none"></div>
          <?php if (isset(self::$message)): ?><div class="updated"><p><?php echo self::$message; ?></p></div><?php endif; ?>
          <?php if (isset(self::$error_message)): ?><div class="error"><p><?php echo self::$error_message; ?></p></div><?php endif; ?>
          <?php if ($balance > 0): ?><div class="notice"><p><?php echo "A pending payment of &#8358; ".number_format($balance)." is due"; ?></p></div><?php endif; ?>
          <form id="form1" name="form1" method="post" action="<?php echo admin_url('admin.php?page=lh-manage-patient'); ?>">
            <div class="form-field form-required term-search-wrap">
              <label for="search"> Search</label>
              <input type="text" name="search" id="search" value="" required />
            </div>
          </form>
          <form id="form2" name="form2" method="post" action="">
            <?php if ( (isset($_REQUEST['patient'])) || (self::$showPatient) ) { ?>
                <h2>Patient's Details</h2>
                <div class="form-field form-required term-names-wrap">
                    <label for="names"> Patient's Name</label>
                    <strong><?php echo $data['last_name']." ".$data['first_name']; ?></strong>
                </div>
                <div class="form-field form-required term-names-wrap">
                    <label for="names"> Payment</label>
                    <select id="paymentMode" name="paymentMode" required >
                        <option value="">Select One</option>
                        <option value="now">Create Invoice and Pay Now</option>
                        <option value="later">Create Invoice and Pay Later</option>
                    </select>
                </div>
                <div class="form-field form-required term-age-wrap" id="div_0">
                    <label for="component_0">Medication</label>
                    <select id="component_0" name="medication[0][medication_id]" data-id="0" required>
                        <option value="">Select One</option>
                        <?php for ($i = 0; $i < count($inventoryList); $i++) { ?>
                            <option value="<?php echo $inventoryList[$i]['ref']; ?>"><?php echo $inventoryList[$i]['title']; ?></option>
                        <?php } ?>
                    </select>
                    <label for="comp_qty_0">Quantity</label>
                    <input type="number" name="medication[0][quantity]" id="comp_qty_0" value="1" placeholder="Quantity" required />
                    <label for="comp_dose_0">Dose</label>
                    <input type="number" name="medication[0][dose]" id="comp_dose_0" value="" required placeholder='Dose' />
                    <label for="comp_freq_0">Frequency</label>
                    <select id="comp_freq_0" name="medication[0][frequency]" required >
                        <option value="">Select One</option>
                        <option value="Morning Afternoon Evening">Morning Afternoon Evening</option>
                        <option value="Morning Evening">Morning Evening</option>
                        <option value="Afternoon Evening">Afternoon Evening</option>
                        <option value="Daily">Daily</option>
                        <option value="Weekly">Weekly</option>
                        <option value="Monthly">Monthly</option>
                    </select>
                    <label for="comp_notes_0">Notes (Optional)</label>
                    <textarea name="medication[0][notes]" id="comp_notes_0" placeholder="Notes (Optional)"></textarea>
                    <button type="button" id="add_button_0" data-id="0" class="button button-primary" onclick="add_button(0)"><i class="fas fa-plus-square fa-lg"></i></button>
                </div>
                <div id="other_content"></div>
                <input type="hidden" name="patient_id" id="patient_id" value="<?php echo $_REQUEST['id']; ?>">
                <button name="submit" id="submit" type="submit" class="button button-primary"><i class="fa fa-floppy-o fa-lg"></i>Save Medication</button>
                <button name="reset" id="reset" type="reset" class="button"><i class="fa fa-undo fa-lg"></i>Reset</button>
            <?php } ?>
          </form>
        </div>
      </div>
    </div>
    <div id="col-right">

      <div class="col-wrap">
        <div class="form-wrap">
            <?php if ( (isset($_REQUEST['patient'])) || (self::$showPatient) ) { ?>
                <div class="col-wrap control_bt" id="appointment_div">
                    <h2>Medication History</h2>
                    <table class='widefat striped fixed' id="appointment_list">
                        <thead>
                        <tr>
                            <th class="manage-column column-cb check-column"></th>
                            <th class="manage-column column-columnname" scope="col">Medication</th>
                            <th class="manage-column column-columnname" scope="col">Invoice</th>
                            <th class="manage-column column-columnname" scope="col">Quantity</th>
                            <th class="manage-column column-columnname" scope="col">Dose</th>
                            <th class="manage-column column-columnname" scope="col">Notes</th>
                            <th class="manage-column column-columnname" scope="col">Action</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th class="manage-column column-cb check-column"></th>
                            <th class="manage-column column-columnname" scope="col">Medication</th>
                            <th class="manage-column column-columnname" scope="col">Invoice</th>
                            <th class="manage-column column-columnname" scope="col">Quantity</th>
                            <th class="manage-column column-columnname" scope="col">Dose</th>
                            <th class="manage-column column-columnname" scope="col">Notes</th>
                            <th class="manage-column column-columnname" scope="col">Action</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        <?php $count = 1;
                        for ($i = 0;  $i < count($medicationList); $i++) { ?>
                        <tr>
                            <th class="check-column" scope="row"><?php echo $count; ?></th>
                            <td class="column-columnname">
                            <?php echo inventory::getSingle( $medicationList[$i]['medication_id'] ); ?></td>
                            <td class="column-columnname"><a href="<?php echo admin_url('admin.php?page=lh-billing-invoice&view&id='.$medicationList[$i]['invoice_id']); ?>" title="Manage Invoice"><?php echo invoice::invoiceNumber( $medicationList[$i]['invoice_id'] ); ?></a></td>
                            <td class="column-columnname"><?php echo $medicationList[$i]['quantity']; ?></td>
                            <td class="column-columnname"><?php echo $medicationList[$i]['dose'].", ".$medicationList[$i]['frequency']; ?></td>
                            <td class="column-columnname"><?php echo $medicationList[$i]['notes']; ?></td>
                            <td class="column-columnname">
                                <!-- <a href="<?php echo admin_url('admin.php?page=lh-manage-clinic&appointment&id='.$list[$i]['ref']); ?>" title="Open Records <?php echo $list[$i]['last_name']." ".$list[$i]['first_name']; ?>"><i class="fas fa-clinic-medical"></i></a> -->
                            </td>
                        </tr>
                        <?php $count++;
                        } ?>
                        </tbody>
                    </table> 
                </div>
            <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
    function minus_button( key ) {
        jQuery( "#div_" + key ).remove();
    }

    function add_button( key ) {
        var locate = parseInt(jQuery("#add_button_" + key ).attr("data-id"));
        var next = locate+1;

        var data = '<div class="form-field form-required term-age-wrap" id="div_'+next+'">'
            +'<label for="component_'+next+'">Medication</label>'
            +'<select id="component_'+next+'" name="medication['+next+'][medication_id]" data-id="'+next+'" required >'
                +'<option value="">Select One</option>'
                <?php for ($i = 0; $i < count($inventoryList); $i++) { ?>
                    +'<option value="<?php echo $inventoryList[$i]['ref']; ?>"><?php echo $inventoryList[$i]['title']; ?></option>'
                <?php } ?>
            +'</select>'
            +'<label for="comp_qty_'+next+'">Quantity</label>'
            +'<input type="number" name="medication['+next+'][quantity]" id="comp_qty_'+next+'" value="1" placeholder="Quantity" required />'
            +'<label for="comp_dose_'+next+'">Dose</label>'
            +'<input type="number" name="medication['+next+'][dose]" id="comp_dose_'+next+'" value="" required placeholder="Dose" />'
            +'<label for="comp_freq_'+next+'">Frequency</label>'
            +'<select id="comp_freq_'+next+'" name="medication['+next+'][frequency]" required >'
                +'<option value="">Select One</option>'
                +'<option value="Morning Afternoon Evening">Morning Afternoon Evening</option>'
                +'<option value="Morning Evening">Morning Evening</option>'
                +'<option value="Afternoon Evening">Afternoon Evening</option>'
                +'<option value="Daily">Daily</option>'
                +'<option value="Weekly">Weekly</option>'
                +'<option value="Monthly">Monthly</option>'
            +'</select>'
            +'<label for="comp_notes_'+next+'">Notes (Optional)</label>'
            +'<textarea name="medication['+next+'][notes]" id="comp_notes_'+next+'" placeholder="Notes (Optional)"></textarea>'
            +'<button type="button" id="add_button_'+next+'" data-id="'+next+'" class="button button-primary" onclick="add_button('+next+')"><i class="fas fa-plus-square fa-lg"></i></button>'
            +'<button type="button" id="minus_button_'+next+'" data-id="'+next+'" class="button button-primary" onclick="minus_button('+next+')"><i class="fas fa-minus-square fa-lg"></i></button>'
        +'</div>'

        jQuery('#other_content').append( data );
    }

    jQuery(function ($) {
        $('#appointment_list').DataTable();
        
        var se_ajax_url = '<?php echo get_rest_url().'api/patient/search'; ?>';

        var api_key = Math.floor(Math.random() * 100001);
        var user_token = '<?php echo self::$userToken; ?>';
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
                window.location = '<?php echo admin_url('admin.php?page=lh-manage-medication&patient&id='); ?>' + ui.item.id;
            }
        });
    
    } );
</script>