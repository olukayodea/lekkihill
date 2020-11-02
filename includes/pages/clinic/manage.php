<?php
  $list = appointments::$list;
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
  <h1 class="wp-heading-inline">Clinic</h1>
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
              <input type="hidden" name="names" id="names" value="<?php echo $data['last_name']." ".$data['first_name']; ?>" required />
              <div class="form-field form-required term-names-wrap">
                <label for="names"> Patient's Name</label>
                <strong><?php echo $data['last_name']." ".$data['first_name']; ?></strong>
              </div>
              <div class="form-field form-required term-names-wrap">
                <label for="email"> Patient's Email</label>
                <strong><?php echo $data['email']; ?></strong>
              </div>
              <div class="form-field form-required term-phone_number-wrap">
                <label for="phone_number"> Patient's Phone Number</label>
                <strong><?php echo $data['phone_number']; ?></strong>
              </div>
              <div class="form-field form-required term-age-wrap">
                <label for="age">Age</label>
                <strong><?php echo $data['age']; ?></strong>
              </div>
              <div class="form-field form-required term-sex-wrap">
                <label for="sex">Gender</label>
                <strong><?php echo $data['sex']; ?></strong>
              </div>
              <div class="form-field form-required term-address-wrap">
                <label for="address">Address</label>
                <strong><?php echo $data['address']; ?></strong>
              </div>
              <div class="form-field form-required term-next_of_Kin-wrap">
                <label for="next_of_Kin">Next of Kin</label>
                <strong><?php echo $data['next_of_Kin']; ?></strong>
              </div>
              <div class="form-field form-required term-next_of_contact-wrap">
                <label for="next_of_contact">Next of Kin Contact</label>
                <strong><?php echo $data['next_of_contact']; ?></strong>
              </div>
              <div class="form-field form-required term-next_of_address-wrap">
                <label for="next_of_address">Next of Kin Address</label>
                <strong><?php echo $data['next_of_address']; ?></strong>
              </div>
              <div class="form-field form-required term-allergies-wrap">
                <label for="allergies">Allergies</label>
                <strong><?php echo $data['allergies']; ?></strong>
              </div>
            <?php } ?>
            <?php if (isset($_REQUEST['appointment'])) { ?>
              <input type="hidden" name="names" id="names" value="<?php echo $appointmentData['names']; ?>" required />
              <h2>Appointment's Details</h2>
              <?php if (!self::$showPatient) { ?>
                <div class="form-field form-required term-names-wrap">
                  <label for="names"> Name</label>
                  <strong><?php echo $appointmentData['names']; ?></strong>
                </div>
                <div class="form-field form-required term-names-wrap">
                  <label for="email"> Email</label>
                  <strong><?php echo $appointmentData['email']; ?></strong>
                </div>
                <div class="form-field form-required term-names-wrap">
                  <label for="email"> Phone Number</label>
                  <strong><?php echo $appointmentData['phone']; ?></strong>
                </div>
              <?php } ?>
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
                <strong><?php echo self::get_time_stamp( strtotime( $appointmentData['next_appointment'] ) ); ?></strong>
              </div>
              <?php if ($appointmentData['status'] == "SCHEDULED") { ?>
                <?php if ($balance > 0) { ?>
                  <div class="form-field form-required term-next_appointment-wrap">
                    <label for="next_appointment"> Previous Due</label>
                    <strong><?php echo "&#8358; ".number_format( $balance, 2); ?></strong>
                  </div>
                <?php } ?>
                <div class="form-field form-required term-next_appointment-wrap">
                  <label for="next_appointment"> Amount Due Today</label>
                  <strong><?php echo "&#8358; ".number_format( get_option("lh-consultationFee-cost"), 2); ?></strong>
                </div>
                <div class="form-field form-required term-next_appointment-wrap">
                  <label for="next_appointment"> Total Due</label>
                  <strong><?php echo "&#8358; ".number_format( $balance+get_option("lh-consultationFee-cost"), 2); ?></strong>
                </div>
              <?php } ?>
            <?php } ?>
            <?php if ((isset($_REQUEST['appointment'])) || (isset($_REQUEST['patient']))) { ?>
            <?php if ($balance > 0) { ?>
              <button type="button" id="add_button_0" data-id="0" class="button button-primary" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-file-invoice fa-lg"></i>&nbsp;Pending Invoice</button>&nbsp;&nbsp;
            <?php } ?>
            <?php if ($balance+get_option("lh-consultationFee-cost") > 0) { ?>
              <button type="button" id="add_button_1" data-id="0" class="button button-primary" data-toggle="modal" data-target="#poatPayment"><i class="fas fa-money-check-alt fa-lg"></i>&nbsp;Post Payment</button>&nbsp;&nbsp;
            <?php } ?>
            <button type="button" id="add_button_5" data-id="0" class="button button-primary" onclick="location='<?php echo admin_url('admin.php?page=lh-manage-medication&patient&id='.$data['ref']); ?>'"><i class="fas fa-prescription"></i>&nbsp;Medications</button>&nbsp;&nbsp;
            <button type="button" id="add_button_2" data-id="0" class="button button-primary"><i class="fas fa-thermometer-quarter fa-lg"></i>&nbsp;Record Vitals</button>&nbsp;&nbsp;
            <?php if ($managePatient) { ?>
            <button type="button" id="add_button_3" data-id="0" class="button button-primary"><i class="fas fa-plus-square fa-lg"></i>&nbsp;Manage Patient</button>&nbsp;&nbsp;
            <button type="button" id="add_button_4" data-id="0" class="button button-primary" style="display: none"><i class="fas fa-plus-square fa-lg"></i>&nbsp;Close</button>&nbsp;&nbsp;
            <?php } else { ?>
            <div class="update-nag"><p><?php echo "You can not manage patient at the moment, patient's vitals has not been collected today"; ?></p></div><?php } ?>
            <?php } ?>
            <br>
            <i>Remove Later: None patient appointments has not been handled, they should be added as patients automatically before their vitals are taken</i>
            <br>
            <i>Remove Later: For patients without apppointment, schedule one and charge them when they click on check vitals</i>
            <br>
            <i>Remove Later: Disable manage patient until vitals are taken</i>
          </form>
        </div>
      </div>
    </div>
    <div id="col-right">

      <div class="col-wrap">
        <div class="form-wrap">
          <div class="col-wrap" id="control_buttons" style="display: none">
            <button type="button" id="control_doctors_note" data-id="0" class="button button-primary"><i class="fas fa-clinic-medical fa-lg"></i>&nbsp;Doctor's Notes</button>
            <button type="button" id="control_sm_bt" data-id="0" class="button button-primary"><i class="fas fa-clinic-medical fa-lg"></i>&nbsp;Summary</button>
            <button type="button" id="control_cs_bt" data-id="0" class="button button-primary"><i class="fas fa-notes-medical fa-lg"></i>&nbsp;Continuation Sheet</button>
            <button type="button" id="control_po_bt" data-id="0" class="button button-primary"><i class="fas fa-bed fa-lg"></i>&nbsp;Post Operative Note</button>
            <button type="button" id="control_m_bt" data-id="0" class="button button-primary"><i class="fas fa-prescription fa-lg"></i>&nbsp;Medications</button>
            <button type="button" id="control_mg_bt" data-id="0" class="button button-primary"><i class="fas fa-spa fa-lg"></i>&nbsp;Massage</button>
            <button type="button" id="control_fb_bt" data-id="0" class="button button-primary"><i class="fas fa-balance-scale-right fa-lg"></i>&nbsp;Fluid Balance</button>
            <button type="button" id="control_fo_bt" data-id="0" class="button button-primary"><i class="fab fa-wpforms fa-lg"></i>&nbsp;Forms</button>
          </div>
          <div class="col-wrap control_bt" id="control_doctors_note_div" style="display: none">
            <button type="button" class="right" onclick="window.open('<?php echo admin_url('admin.php?page=lh-manage-clinic&patient&all&id='.$_REQUEST['id']); ?>&PrintPDF','_blank')"><i class="fas fa-print"></i> Print All</button>&nbsp;<button type="button" class="right" onclick="window.open('<?php echo admin_url('admin.php?page=lh-manage-clinic&patient&all&id='.$_REQUEST['id']); ?>&downloadPatientPDF','_blank')"><i class="fas fa-download"></i> Download All</button>
            <span id="sm_notice"></span>
            <h2>Doctor's Note</h2>
            <form id="form3" name="form3" method="post" onsubmit="doctorsNotes();return false">
              <div class="form-field form-required term-doctors_note_data-wrap">
                <label for="doctors_note_data">Note</label>
                <textarea id="doctors_note_data" cols="85%" rows="15%" required></textarea>
              </div>
              <div class="form-field form-required term-title-wrap">
                <label for="product"> Recommended Mediacations/Products</label>
                <div class="form-field form-required term-age-wrap" id="div_0">
                    <label for="medication_id">Medication</label>
                    <select id="medication_id" name="medication_id" data-id="0" required>
                        <option value="">Select One</option>
                        <?php for ($i = 0; $i < count($inventoryList); $i++) { ?>
                            <option value="<?php echo $inventoryList[$i]['ref']; ?>"><?php echo $inventoryList[$i]['title']; ?></option>
                        <?php } ?>
                    </select>
                    <label for="quantity">Quantity</label>
                    <input type="number" name="quantity" id="quantity" value="1" placeholder="Quantity" required />
                    <label for="dose">Dose</label>
                    <input type="number" name="dose" id="dose" value="" required placeholder='Dose' />
                    <label for="frequency">Frequency</label>
                    <select id="frequency" name="frequency" required >
                        <option value="">Select One</option>
                        <option value="Morning Afternoon Evening">Morning Afternoon Evening</option>
                        <option value="Morning Evening">Morning Evening</option>
                        <option value="Afternoon Evening">Afternoon Evening</option>
                        <option value="Daily">Daily</option>
                        <option value="Weekly">Weekly</option>
                        <option value="Monthly">Monthly</option>
                    </select>
                    <label for="notes">Notes (Optional)</label>
                    <textarea name="notes" id="notes" placeholder="Notes (Optional)"></textarea><br>
                    <button type="button" id="add_button_0" data-id="0" class="button button-primary" onclick="add_button()"><i class="fas fa-plus-square fa-lg"></i></button>
                    <table id="drugList" width="100%">
                      <thead>
                        <tr>
                          <td>Medication</td>
                          <td>Quantity</td>
                          <td>Dose</td>
                          <td>Note</td>
                          <td>&nbsp;</td>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <div id="other_content"></div>
                    </table>
                </div>
                
              </div>
              <button type="submit" id="doctors_mote_button" class="button button-primary"><i class="fas fa-save fa-lg"></i>&nbsp;Save</button>
            </form>

            <h2>Past Notes</h2>
            <span id="doctors_notice"></span>
            <div id="doctors_list"></div>
          </div>
          <div class="col-wrap control_bt" id="control_sm_bt_div" style="display: none">
            <button type="button" class="right" onclick="window.open('<?php echo admin_url('admin.php?page=lh-manage-clinic&patient&all&id='.$_REQUEST['id']); ?>&PrintPDF','_blank')"><i class="fas fa-print"></i> Print All</button>&nbsp;<button type="button" class="right" onclick="window.open('<?php echo admin_url('admin.php?page=lh-manage-clinic&patient&all&id='.$_REQUEST['id']); ?>&downloadPatientPDF','_blank')"><i class="fas fa-download"></i> Download All</button>
            <span id="sm_notice"></span>
            <h2>Vitals</h2>
            <button type="button" class="right" onclick="window.open('<?php echo admin_url('admin.php?page=lh-manage-clinic&patient&vitals&id='.$_REQUEST['id']); ?>&PrintPDF','_blank')"><i class="fas fa-print"></i> Print Vitals</button>&nbsp;<button type="button" class="right" onclick="window.open('<?php echo admin_url('admin.php?page=lh-manage-clinic&patient&vitals&id='.$_REQUEST['id']); ?>&downloadPatientPDF','_blank')"><i class="fas fa-download"></i> Download Vitals</button>
            <table class='widefat striped fixed'>
              <tbody>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Weight (Kg)</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_v_weight"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Height (cm)</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_v_height"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>BMI</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_v_bmi"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>SPO2</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_v_spo2"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Respiratory (cpm))</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_v_respiratory"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Temprature (C)</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_v_temprature"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Pulse Rate (bpm)</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_v_pulse"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Blood Pressure-SYS (mmHg)</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_v_bp_sys"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Blood Pressure-DIA (mmHg)</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_v_bp_dia"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Added By</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_v_added_by"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Added On</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_v_create_time"><i>fetching data...</i></td>
                </tr>
              </tbody>
            </table> 
            <h2>Clinic Continuation</h2>
            <button type="button" class="right" onclick="window.open('<?php echo admin_url('admin.php?page=lh-manage-clinic&patient&continuationSheet&id='.$_REQUEST['id']); ?>&PrintPDF','_blank')"><i class="fas fa-print"></i> Print Clinic Continuation</button>&nbsp;<button type="button" class="right" onclick="window.open('<?php echo admin_url('admin.php?page=lh-manage-clinic&patient&continuationSheet&id='.$_REQUEST['id']); ?>&downloadPatientPDF','_blank')"><i class="fas fa-download"></i> Download Clinic Continuation</button>
            <p id="summary_c"><i>fetching data...</i></p>
            <small id="summary_added_by"><i>fetching data...</i></small>
            <small id="summary_c_create_time"><i>fetching data...</i></small><br>
            <a href="Javascript:void(0)" id="control_cs_bt_l">Add New</a>
            <h2>Post Operative Note</h2>
            <button type="button" class="right" onclick="window.open('<?php echo admin_url('admin.php?page=lh-manage-clinic&patient&operativeNote&id='.$_REQUEST['id']); ?>&PrintPDF','_blank')"><i class="fas fa-print"></i> Print Operative Note</button>&nbsp;<button type="button" class="right" onclick="window.open('<?php echo admin_url('admin.php?page=lh-manage-clinic&patient&operativeNote&id='.$_REQUEST['id']); ?>&downloadPatientPDF','_blank')"><i class="fas fa-download"></i> Download Operative Note</button>
            <table class='widefat striped fixed'>
              <tbody>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Surgery</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_p_surgery"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Category</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_p_surgery_category"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Indication</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_p_indication"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Surgeon</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_p_surgeon"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Assistant Surgeon</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_p_asst_surgeon"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Peri Op Nurse</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_p_per_op_nurse"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Circulating Nurse</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_p_circulating_nurse"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Anaestdesia</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_p_anaesthesia"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Anaestdesia Time</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_p_anaesthesia_time"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Knife on Skin</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_p_knife_on_skin"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Infiltration Time</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_p_infiltration_time"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Liposuction Time</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_p_liposuction_time"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>End of Surgery</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_p_end_of_surgery"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Procedure</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_p_procedure"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Transfered Fat (Right Buttock)</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_p_amt_of_fat_right"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Transfered Fat (Right Buttock)</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_p_amt_of_fat_left"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Transfered Fat (Otder Areas)</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_p_amt_of_fat_other"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>EBL</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_p_ebl"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Plan</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_p_plan"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Added By</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_p_added_by"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Added On</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_p_create_time"><i>fetching data...</i></td>
                </tr>
              </tbody>
            </table> 
            <a href="Javascript:void(0)" id="control_po_bt_l">Add New</a>
            <h2>Medication</h2>
            <button type="button" class="right" onclick="window.open('<?php echo admin_url('admin.php?page=lh-manage-clinic&patient&medication&id='.$_REQUEST['id']); ?>&PrintPDF','_blank')"><i class="fas fa-print"></i> Print Medication</button>&nbsp;<button type="button" class="right" onclick="window.open('<?php echo admin_url('admin.php?page=lh-manage-clinic&patient&medication&id='.$_REQUEST['id']); ?>&downloadPatientPDF','_blank')"><i class="fas fa-download"></i> Download Medication</button>
            <table class='widefat striped fixed'>
              <tbody>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Route</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_m_route"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Medication</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_m_medication"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Dose</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_m_dose"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Frequency</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_m_frequency"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Report Date</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_m_report_date"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Added By</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_m_added_by"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Added On</strong></th>
                  <td class="manage-column column-columnname" scope="col" id="summary_m_create_time"><i>fetching data...</i></td>
                </tr>
              </tbody>
            </table> 
            <a href="Javascript:void(0)" id="control_m_bt_l">Add New</a>
            <h2>Massage Register</h2>
            <button type="button" class="right" onclick="window.open('<?php echo admin_url('admin.php?page=lh-manage-clinic&patient&massage&id='.$_REQUEST['id']); ?>&PrintPDF','_blank')"><i class="fas fa-print"></i> Print Massage Register</button>&nbsp;<button type="button" class="right" onclick="window.open('<?php echo admin_url('admin.php?page=lh-manage-clinic&patient&massage&id='.$_REQUEST['id']); ?>&downloadPatientPDF','_blank')"><i class="fas fa-download"></i> Download Massage Register</button>
            <a href="Javascript:void(0)" id="control_mg_bt_l">Add New</a>
            <h2>Fluid Balance</h2>
            <button type="button" class="right" onclick="window.open('<?php echo admin_url('admin.php?page=lh-manage-clinic&patient&fluidBalance&id='.$_REQUEST['id']); ?>&PrintPDF','_blank')"><i class="fas fa-print"></i> Print Fluid Balance</button>&nbsp;<button type="button" class="right" onclick="window.open('<?php echo admin_url('admin.php?page=lh-manage-clinic&patient&fluidBalance&id='.$_REQUEST['id']); ?>&downloadPatientPDF','_blank')"><i class="fas fa-download"></i> Download Fluid Balance</button>
            <table class='widefat striped fixed'>
              <tbody>
                <tr>
                  <td class="manage-column column-columnname" scope="col" colspan="2"><strong>Intake (ML)</strong></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong> of IV Fluid</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Amount</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_fb_amount"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Oral Fluid</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_fb_oral_fluid"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>NG Tube Feeding</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_fb_ng_tube_feeding"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col" colspan="2"><strong>Output (ML)</strong></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Vomit</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_fb_vomit"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Urine</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_fb_urine"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Drains</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_fb_drains"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>NG Tube Drainage</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_fb_ng_tube_drainage"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Report Date</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_fb_report_date"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Added By</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_fb_added_by"><i>fetching data...</i></td>
                </tr>
                <tr>
                  <td class="manage-column column-columnname" scope="col"><strong>Added On</strong></td>
                  <td class="manage-column column-columnname" scope="col" id="summary_fb_create_time"><i>fetching data...</i></td>
                </tr>
              </tbody>
            </table> 
            <a href="Javascript:void(0)" id="control_fb_bt_l">Add New</a>
          </div>
          <div class="col-wrap control_bt" id="control_cs_bt_div" style="display: none">
            <h2>Clinic Continuation</h2>
            <button type="button" id="cs_add_record" data-id="0" class="button button-primary" style="display: none"><i class="fas fa-plus-square fa-lg"></i>&nbsp;Add Record</button>
            <button type="button" id="cs_show_record" data-id="0" class="button button-primary"><i class="fas fa-notes-medical fa-lg"></i>&nbsp;History</button>
            <div class="form-wrap" id="cs_add_record_div">
              <form name="continuation_sheet" id="continuation_sheet" onsubmit="continuationSheet();return false">
                <table width="100%">
                  <tr>
                    <td>
                      <div class="form-field form-required term-cs_notes-wrap">
                        <label for="cs_notes"> Notes</label>
                        <textarea name="cs_notes" id="cs_notes" rows="20" required></textarea>
                      </div>
                    </td>
                  </tr>
                </table>
                <button type="submit" id="continuation_button" data-id="0" class="button button-primary"><i class="fas fa-save fa-lg"></i>&nbsp;Save</button>
              </form>
            </div>
            <div class="form-wrap" id="cs_show_record_div" style="display: none">
              <span id="cs_notice"></span>
              <ol class='widefat striped fixed' id="cs_list"></ol>
            </div>
          </div>
          <div class="col-wrap control_bt" id="control_po_bt_div" style="display: none">
            <h2>Post Operative Note</h2>
            <button type="button" id="po_add_record" data-id="0" class="button button-primary" style="display: none"><i class="fas fa-plus-square fa-lg"></i>&nbsp;Add Record</button>
            <button type="button" id="po_show_record" data-id="0" class="button button-primary"><i class="fas fa-notes-medical fa-lg"></i>&nbsp;History</button>
            <div class="form-wrap" id="po_add_record_div">
              <form name="post_op" id="post_op" onsubmit="postOp();return false">
                <table width="100%">
                  <tr>
                    <td>
                      <div class="form-field form-required term-surgery-wrap">
                        <label for="surgery">Surgery</label>
                        <input type="text" name="surgery" id="surgery" value="" required />
                      </div>
                    </td>
                    <td>
                      <div class="form-field form-required term-surgery_category-wrap">
                        <label for="surgery_category"> Surgery Category</label>
                        <select name="surgery_category" id="surgery_category" required>
                          <option>Select One</option>
                          <option value="Major">Major</option>
                          <option value="Intermediate">Intermediate</option>
                          <option value="Minor">Minor</option>
                        </select>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <div class="form-field form-required term-names-wrap">
                        <label for="indication"> Indication</label>
                        <textarea name="indication" id="indication" required></textarea>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="form-field form-required term-surgeon-wrap">
                        <label for="surgeon"> Surgeon</label>
                        <input type="text" name="surgeon" id="surgeon" value="" required />
                      </div>
                    </td>
                    <td>
                      <div class="form-field form-required term-asst_surgeon-wrap">
                        <label for="asst_surgeon"> Assistant Surgeon</label>
                        <input type="text" name="asst_surgeon" id="asst_surgeon" value="" required />
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="form-field form-required term-per_op_nurse-wrap">
                        <label for="per_op_nurse"> Peri Op Nurse</label>
                        <input type="text" name="per_op_nurse" id="per_op_nurse" value="" required />
                      </div>
                    </td>
                    <td>
                      <div class="form-field form-required term-circulating_nurse-wrap">
                        <label for="circulating_nurse"> Circulating Nurse</label>
                        <input type="text" name="circulating_nurse" id="circulating_nurse" value="" required />
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="form-field form-required term-anaesthesia-wrap">
                        <label for="anaesthesia"> Anaesthesia</label>
                        <input type="text" name="anaesthesia" id="anaesthesia" value="" required />
                      </div>
                    </td>
                    <td>
                      <div class="form-field form-required term-names-wrap">
                        <label for="anaesthesia_time"> Anaesthesia Time</label>
                        <input type="datetime-local" name="anaesthesia_time" id="anaesthesia_time" value="" required />
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="form-field form-required term-knife_on_skin-wrap">
                        <label for="knife_on_skin"> Knife on Skin</label>
                        <input type="datetime-local" name="knife_on_skin" id="knife_on_skin" value="" required />
                      </div>
                    </td>
                    <td>
                      <div class="form-field form-required term-infiltration_time-wrap">
                        <label for="infiltration_time"> Infiltration Time</label>
                        <input type="datetime-local" name="infiltration_time" id="infiltration_time" value="" required />
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="form-field form-required term-liposuction_time-wrap">
                        <label for="liposuction_time"> Liposuction Time</label>
                        <input type="datetime-local" name="liposuction_time" id="liposuction_time" value="" required />
                      </div>
                    </td>
                    <td>
                      <div class="form-field form-required term-end_of_surgery-wrap">
                        <label for="end_of_surgery"> End of Surgery</label>
                        <input type="datetime-local" name="end_of_surgery" id="end_of_surgery" value="" required />
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <div class="form-field form-required term-procedure-wrap">
                        <label for="procedure"> Procedure</label>
                        <textarea name="procedure" id="procedure2" required></textarea>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="form-field form-required term-amt_of_fat_right-wrap">
                        <label for="amt_of_fat_right"> Amount of Fat transfered to Right Buttock</label>
                        <input type="text" name="amt_of_fat_right" id="amt_of_fat_right" value="" required />
                      </div>
                    </td>
                    <td>
                      <div class="form-field form-required term-amt_of_fat_left-wrap">
                        <label for="amt_of_fat_left"> Amount of Fat transfered to Left Buttock</label>
                        <input type="text" name="amt_of_fat_left" id="amt_of_fat_left" value="" required />
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="form-field form-required term-amt_of_fat_other-wrap">
                        <label for="amt_of_fat_other">Amount of Fat transfered to Other Areas</label>
                        <input type="text" name="amt_of_fat_other" id="amt_of_fat_other" value="" required />
                      </div>
                    </td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>
                      <div class="form-field form-required term-ebl-wrap">
                        <label for="ebl">EBL</label>
                        <textarea name="ebl" id="ebl" required></textarea>
                      </div>
                    </td>
                    <td>
                      <div class="form-field form-required term-plan-wrap">
                        <label for="plan">Plan</label>
                        <textarea name="plan" id="plan" required></textarea>
                      </div>
                    </td>
                  </tr>
                </table>

                <button type="submit" id="post_op_button" data-id="0" class="button button-primary"><i class="fas fa-save fa-lg"></i>&nbsp;Save</button>
              </form>
            </div>
            <div class="form-wrap" id="po_show_record_div" style="display: none">
              <span id="po_notice"></span>
              <table class='widefat striped fixed'>
                  <thead>
                  <tr>
                      <th class="manage-column column-cb check-column"></th>
                      <th class="manage-column column-columnname" scope="col">Surgery</th>
                      <th class="manage-column column-columnname" scope="col">Indication</th>
                      <th class="manage-column column-columnname" scope="col">Surgeon</th>
                      <th class="manage-column column-columnname" scope="col">Assistant Surgeon</th>
                      <th class="manage-column column-columnname" scope="col">Peri Op Nurse</th>
                      <th class="manage-column column-columnname" scope="col">Circulating Nurse</th>
                      <th class="manage-column column-columnname" scope="col">Anaesthesia</th>
                      <th class="manage-column column-columnname" scope="col">Anaesthesia Time</th>
                      <th class="manage-column column-columnname" scope="col">Knife on Skin</th>
                      <th class="manage-column column-columnname" scope="col">Infiltration Time</th>
                      <th class="manage-column column-columnname" scope="col">Liposuction Time</th>
                      <th class="manage-column column-columnname" scope="col">End of Surgery</th>
                      <th class="manage-column column-columnname" scope="col">Procedure</th>
                      <th class="manage-column column-columnname" scope="col">Transfered Fat (Right Buttock)</th>
                      <th class="manage-column column-columnname" scope="col">Transfered Fat (Right Buttock)</th>
                      <th class="manage-column column-columnname" scope="col">Transfered Fat (Other Areas)</th>
                      <th class="manage-column column-columnname" scope="col">EBL</th>
                      <th class="manage-column column-columnname" scope="col">Plan</th>
                      <th class="manage-column column-columnname" scope="col">Added By</th>
                  </tr>
                  </thead>
                  <tfoot>
                  <tr>
                      <th class="manage-column column-cb check-column"></th>
                      <th class="manage-column column-columnname" scope="col">Surgery</th>
                      <th class="manage-column column-columnname" scope="col">Indication</th>
                      <th class="manage-column column-columnname" scope="col">Surgeon</th>
                      <th class="manage-column column-columnname" scope="col">Assistant Surgeon</th>
                      <th class="manage-column column-columnname" scope="col">Peri Op Nurse</th>
                      <th class="manage-column column-columnname" scope="col">Circulating Nurse</th>
                      <th class="manage-column column-columnname" scope="col">Anaesthesia</th>
                      <th class="manage-column column-columnname" scope="col">Anaesthesia Time</th>
                      <th class="manage-column column-columnname" scope="col">Knife on Skin</th>
                      <th class="manage-column column-columnname" scope="col">Infiltration Time</th>
                      <th class="manage-column column-columnname" scope="col">Liposuction Time</th>
                      <th class="manage-column column-columnname" scope="col">End of Surgery</th>
                      <th class="manage-column column-columnname" scope="col">Procedure</th>
                      <th class="manage-column column-columnname" scope="col">Transfered Fat (Right Buttock)</th>
                      <th class="manage-column column-columnname" scope="col">Transfered Fat (Right Buttock)</th>
                      <th class="manage-column column-columnname" scope="col">Transfered Fat (Other Areas)</th>
                      <th class="manage-column column-columnname" scope="col">EBL</th>
                      <th class="manage-column column-columnname" scope="col">Plan</th>
                      <th class="manage-column column-columnname" scope="col">Added By</th>
                  </tr>
                  </tfoot>
                  <tbody id="po_list">
                  </tbody>
              </table> 
            </div>
          </div>
          <div class="col-wrap control_bt" id="control_m_bt_div" style="display: none">
            <h2>Medication</h2>
            <button type="button" id="m_add_record" data-id="0" class="button button-primary" style="display: none"><i class="fas fa-plus-square fa-lg"></i>&nbsp;Add Record</button>
            <button type="button" id="m_show_record" data-id="0" class="button button-primary"><i class="fas fa-notes-medical fa-lg"></i>&nbsp;History</button>
            <div class="form-wrap" id="m_add_record_div">
              <form name="medication" id="medication" onsubmit="addMedication();return false">
                <table width="100%">
                  <tr>
                    <td>
                      <div class="form-field form-required term-m_route-wrap">
                        <label for="m_route">Route of Administration</label>
                        <input type="text" name="m_route" id="m_route" value="" required />
                      </div>
                    </td>
                    <td>
                      <div class="form-field form-required term-m_medication-wrap">
                        <label for="m_medication"> Medication</label>
                        <input type="text" name="m_medication" id="m_medication" value="" required />
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="form-field form-required term-m_dose-wrap">
                        <label for="m_dose"> Dose</label>
                        <input type="text" name="m_dose" id="m_dose" value="" required />
                      </div>
                    </td>
                    <td>
                      <div class="form-field form-required term-m_frequency-wrap">
                        <label for="m_frequency"> Frequency</label>
                        <input type="text" name="m_frequency" id="m_frequency" value="" required />
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="form-field form-required term-m_report_date-wrap">
                        <label for="m_report_date"> Report Date</label>
                        <input type="date" name="m_report_date" id="m_report_date" value="" required />
                      </div>
                    </td>
                    <td>
                      <div class="form-field form-required term-m_report_time-wrap">
                        <label for="m_report_time"> Report Time</label>
                        <input type="time" name="m_report_time" id="m_report_time" value="" required />
                      </div>
                    </td>
                  </tr>
                </table>

                <button type="submit" id="medication_button" data-id="0" class="button button-primary"><i class="fas fa-save fa-lg"></i>&nbsp;Save</button>
              </form>
            </div>
            <div class="form-wrap" id="m_show_record_div" style="display: none">
              <span id="m_notice"></span>
              <table class='widefat striped fixed'>
                  <thead>
                  <tr>
                      <th class="manage-column column-cb check-column"></th>
                      <th class="manage-column column-columnname" scope="col">Route</th>
                      <th class="manage-column column-columnname" scope="col">Medication</th>
                      <th class="manage-column column-columnname" scope="col">Dose</th>	
                      <th class="manage-column column-columnname" scope="col">Frequency</th>
                      <th class="manage-column column-columnname" scope="col">Report Date</th>
                      <th class="manage-column column-columnname" scope="col">Added By</th>
                      <th class="manage-column column-columnname" scope="col">Added On</th>
                  </tr>
                  </thead>
                  <tfoot>
                  <tr>
                      <th class="manage-column column-cb check-column"></th>
                      <th class="manage-column column-columnname" scope="col">Route</th>
                      <th class="manage-column column-columnname" scope="col">Medication</th>
                      <th class="manage-column column-columnname" scope="col">Dose</th>
                      <th class="manage-column column-columnname" scope="col">Frequency</th>
                      <th class="manage-column column-columnname" scope="col">Report Date</th>
                      <th class="manage-column column-columnname" scope="col">Added By</th>
                      <th class="manage-column column-columnname" scope="col">Added On</th>
                  </tr>
                  </tfoot>
                  <tbody id="m_list">
                  </tbody>
              </table> 
            </div>
          </div>
          <div class="col-wrap control_bt" id="control_mg_bt_div" style="display: none">
            <h2>Massage Register</h2>
            <button type="button" id="mg_add_record" data-id="0" class="button button-primary" style="display: none"><i class="fas fa-plus-square fa-lg"></i>&nbsp;Add Record</button>
            <button type="button" id="mg_show_record" data-id="0" class="button button-primary"><i class="fas fa-notes-medical fa-lg"></i>&nbsp;History</button>
            <div class="form-wrap" id="mg_add_record_div">
            </div>
            <div class="form-wrap" id="mg_show_record_div" style="display: none">
            </div>
          </div>
          <div class="col-wrap control_bt" id="control_fb_bt_div" style="display: none">
            <h2>Fluid Balance Chart</h2>
            <button type="button" id="fb_add_record" data-id="0" class="button button-primary" style="display: none"><i class="fas fa-plus-square fa-lg"></i>&nbsp;Add Record</button>
            <button type="button" id="fb_show_record" data-id="0" class="button button-primary"><i class="fas fa-notes-medical fa-lg"></i>&nbsp;History</button>

            <div class="form-wrap" id="fb_add_record_div">
              <form name="fluid_balance" id="fluid_balance" onsubmit="fluidBalance();return false">
                <table width="100%">
                  <tr>
                    <td colspan="2"><h2>Intake (ML)</h2></td>
                  </tr>
                  <tr>
                    <td>
                      <div class="form-field form-required term-iv_fluid-wrap">
                        <label for="iv_fluid">Type of IV Fluid</label>
                        <input type="text" name="iv_fluid" id="iv_fluid" value="" required />
                      </div>
                    </td>
                    <td>
                      <div class="form-field form-required term-amount-wrap">
                        <label for="amount"> Amount</label>
                        <input type="number" name="amount" id="amount" step="0.01" value="" required />
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="form-field form-required term-oral_fluid-wrap">
                        <label for="oral_fluid"> Oral Fluid</label>
                        <input type="number" name="oral_fluid" id="oral_fluid" step="0.01" value="" required />
                      </div>
                    </td>
                    <td>
                      <div class="form-field form-required term-ng_tube_feeding-wrap">
                        <label for="ng_tube_feeding"> NG Tube Feeding</label>
                        <input type="number" name="ng_tube_feeding" id="ng_tube_feeding" step="0.01" value="" required />
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2"><h2>Output (ML)</h2></td>
                  </tr>
                  <tr>
                    <td>
                      <div class="form-field form-required term-vomit-wrap">
                        <label for="vomit"> Vomit</label>
                        <input type="number" name="vomit" id="vomit" step="0.01" value="" required />
                      </div>
                    </td>
                    <td>
                      <div class="form-field form-required term-urine-wrap">
                        <label for="urine"> Urine</label>
                        <input type="number" name="urine" id="urine" step="0.01" value="" required />
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="form-field form-required term-drains-wrap">
                        <label for="drains"> Drains</label>
                        <input type="number" name="drains" id="drains" step="0.01" value="" required />
                      </div>
                    </td>
                    <td>
                      <div class="form-field form-required term-ng_tube_drainage-wrap">
                        <label for="ng_tube_drainage"> NG Tube Drainage</label>
                        <input type="number" name="ng_tube_drainage" id="ng_tube_drainage" step="0.01" value="" required />
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="form-field form-required term-report_date-wrap">
                        <label for="report_date"> Report Date</label>
                        <input type="date" name="report_date" id="report_date" value="" required />
                      </div>
                    </td>
                    <td>
                      <div class="form-field form-required term-report_time-wrap">
                        <label for="report_time"> Report Time</label>
                        <input type="time" name="report_time" id="report_time" value="" required />
                      </div>
                    </td>
                  </tr>
                </table>

                <button type="submit" id="fluid_balance_button" data-id="0" class="button button-primary"><i class="fas fa-save fa-lg"></i>&nbsp;Save</button>
              </form>
            </div>
            <div class="form-wrap" id="fb_show_record_div" style="display: none">
              <span id="fb_notice"></span>
              <table class='widefat striped fixed'>
                  <thead>
                  <tr>
                      <th class="manage-column column-cb check-column"></th>
                      <th class="manage-column column-columnname" colspan="4" scope="col">Intake (ML)</th>
                      <th class="manage-column column-columnname" colspan="5" scope="col">Output (ML)</th>
                      <th class="manage-column column-columnname" scope="col"></th>
                      <th class="manage-column column-columnname" scope="col"></th>
                  </tr>
                  <tr>
                      <th class="manage-column column-cb check-column"></th>
                      <th class="manage-column column-columnname" scope="col">Type of IV Fluid</th>
                      <th class="manage-column column-columnname" scope="col">Amount</th>
                      <th class="manage-column column-columnname" scope="col">Oral Fluid</th>
                      <th class="manage-column column-columnname" scope="col">NG Tube Feeding</th>
                      <th class="manage-column column-columnname" scope="col">Vomit</th>
                      <th class="manage-column column-columnname" scope="col">Urine</th>
                      <th class="manage-column column-columnname" scope="col">Drains</th>
                      <th class="manage-column column-columnname" scope="col">NG Tube Drainage</th>
                      <th class="manage-column column-columnname" scope="col">Report Date</th>
                      <th class="manage-column column-columnname" scope="col">Added By</th>
                      <th class="manage-column column-columnname" scope="col">Added On</th>
                  </tr>
                  </thead>
                  <tfoot>
                  <tr>
                      <th class="manage-column column-cb check-column"></th>
                      <th class="manage-column column-columnname" scope="col">Type of IV Fluid</th>
                      <th class="manage-column column-columnname" scope="col">Amount</th>
                      <th class="manage-column column-columnname" scope="col">Oral Fluid</th>
                      <th class="manage-column column-columnname" scope="col">NG Tube Feeding</th>
                      <th class="manage-column column-columnname" scope="col">Vomit</th>
                      <th class="manage-column column-columnname" scope="col">Urine</th>
                      <th class="manage-column column-columnname" scope="col">Drains</th>
                      <th class="manage-column column-columnname" scope="col">NG Tube Drainage</th>
                      <th class="manage-column column-columnname" scope="col">Report Date</th>
                      <th class="manage-column column-columnname" scope="col">Added By</th>
                      <th class="manage-column column-columnname" scope="col">Added On</th>
                  </tr>
                  <tr>
                      <th class="manage-column column-cb check-column"></th>
                      <th class="manage-column column-columnname" colspan="4" scope="col">Intake (ML)</th>
                      <th class="manage-column column-columnname" colspan="5" scope="col">Output (ML)</th>
                      <th class="manage-column column-columnname" scope="col"></th>
                      <th class="manage-column column-columnname" scope="col"></th>
                  </tr>
                  </tfoot>
                  <tbody id="fb_list">
                  </tbody>
              </table> 
            </div>
          </div>
          <div class="col-wrap control_bt" id="control_fo_bt_div" style="display: none">
          </div>
          <div class="col-wrap" id="vital_signs" style="display: none">
            <h2><span class="fullName"></span>'s Vital Signs</h2>
            <button type="button" id="vital_add_record" data-id="0" class="button button-primary" style="display: none"><i class="fas fa-plus-square fa-lg"></i>&nbsp;Add Record</button>
            <button type="button" id="vital_show_record" data-id="0" class="button button-primary"><i class="fas fa-notes-medical fa-lg"></i>&nbsp;Show Previous</button>
            <div class="form-wrap" id="add_record_div">
              <form id="form4" name="form4" method="post" action="<?php echo admin_url('admin.php?'.$_SERVER['QUERY_STRING']); ?>">
                <div class="form-field form-required term-weight-wrap">
                  <label for="weight">Weight (Kg)</label>
                  <input type="number" name="weight" id="weight" value="<?php echo $vitalsData['weight']; ?>" required step="0.01" />
                </div>
                <div class="form-field form-required term-height-wrap">
                  <label for="height">Height (cm)</label>
                  <input type="number" name="height" id="height" value="<?php echo $vitalsData['height']; ?>" required step="0.01" />
                </div>
                <div class="form-field form-required term-bmi-wrap">
                  <label for="bmi">BMI</label>
                  <input type="number" name="bmi" id="bmi" value="<?php echo $vitalsData['bmi']; ?>" required step="0.01"/>
                </div>
                <div class="form-field form-required term-spo2-wrap">
                  <label for="spo2">SPO2</label>
                  <input type="text" name="spo2" id="spo2" value="<?php echo $vitalsData['spo2']; ?>" required />
                </div>
                <div class="form-field form-required term-respiratory-wrap">
                  <label for="respiratory">Respiratory (cpm))</label>
                  <input type="number" name="respiratory" id="respiratory" value="<?php echo $vitalsData['respiratory']; ?>" required step="0.01" />
                </div>
                <div class="form-field form-required term-temprature-wrap">
                  <label for="temprature">Temprature (C)</label>
                  <input type="number" name="temprature" id="temprature" value="<?php echo $vitalsData['temprature']; ?>" required step="0.01" />
                </div>
                <div class="form-field form-required term-pulse-wrap">
                  <label for="pulse">Pulse Rate (bpm)</label>
                  <input type="number" name="pulse" id="pulse" value="<?php echo $vitalsData['pulse']; ?>" required />
                </div>
                <div class="form-field form-required term-bp-wrap">
                  <label for="bp_sys">Blood Pressure-SYS (mmHg)</label>
                  <input type="number" name="bp_sys" id="bp_sys" value="<?php echo $vitalsData['bp_sys']; ?>" required />
                </div>
                <div class="form-field form-required term-bp-wrap">
                  <label for="bp_dia">Blood Pressure-DIA (mmHg)</label>
                  <input type="number" name="bp_dia" id="bp_dia" value="<?php echo $vitalsData['bp_dia']; ?>" required />
                </div>
                <button name="submit_vitals" id="submit_vitals" type="submit" class="button button-primary"><i class="fa fa-calendar-check fa-lg"></i>&nbsp;Save Vitals</button>
              </form>
            </div>
            <div class="form-wrap" id="show_record_div" style="display: none">
              <h2>Vitals' History</h2>
              <!-- show graphs -->
              <table class='widefat striped fixed' id="vital_list">
                <thead>
                <tr>
                    <th class="manage-column column-cb check-column"></th>
                    <th class="manage-column column-columnname" scope="col">Date</th>
                    <th class="manage-column column-columnname" scope="col">Weight</th>
                    <th class="manage-column column-columnname" scope="col">Height</th>
                    <th class="manage-column column-columnname" scope="col">BMI</th>
                    <th class="manage-column column-columnname" scope="col">SPO2</th>
                    <th class="manage-column column-columnname" scope="col">Respiratory</th>
                    <th class="manage-column column-columnname" scope="col">Temprature</th>
                    <th class="manage-column column-columnname" scope="col">BP</th>
                    <th class="manage-column column-columnname" scope="col">Pulse</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th class="manage-column column-cb check-column"></th>
                    <th class="manage-column column-columnname" scope="col">Date</th>
                    <th class="manage-column column-columnname" scope="col">Weight</th>
                    <th class="manage-column column-columnname" scope="col">Height</th>
                    <th class="manage-column column-columnname" scope="col">BMI</th>
                    <th class="manage-column column-columnname" scope="col">SPO2</th>
                    <th class="manage-column column-columnname" scope="col">Respiratory</th>
                    <th class="manage-column column-columnname" scope="col">Temprature</th>
                    <th class="manage-column column-columnname" scope="col">BP</th>
                    <th class="manage-column column-columnname" scope="col">Pulse</th>
                </tr>
                </tfoot>
                <tbody>
                <?php $count = 1;
                for ($i = 0;  $i < count($allVitalsData); $i++) { ?>
                <tr>
                    <th class="check-column" scope="row"><?php echo $count; ?></th>
                    <td class="column-columnname"><?php echo $allVitalsData[$i]['create_time']; ?></td>
                    <td class="column-columnname"><?php echo $allVitalsData[$i]['weight']."Kg"; ?></td>
                    <td class="column-columnname"><?php echo $allVitalsData[$i]['height']."cm"; ?></td>
                    <td class="column-columnname"><?php echo $allVitalsData[$i]['bmi']; ?></td>
                    <td class="column-columnname"><?php echo $allVitalsData[$i]['spo2']; ?></td>
                    <td class="column-columnname"><?php echo $allVitalsData[$i]['respiratory']."cpm)"; ?></td>
                    <td class="column-columnname"><?php echo $allVitalsData[$i]['temprature']."C"; ?></td>
                    <td class="column-columnname"><?php echo $allVitalsData[$i]['bp_sys']."/".$allVitalsData[$i]['bp_dia']."mmHg"; ?></td>
                    <td class="column-columnname"><?php echo $allVitalsData[$i]['pulse']."bpm"; ?></td>
                </tr>
                <?php $count++;
                } ?>
                </tbody>
              </table> 
            </div>
          </div>
          <div class="col-wrap control_bt" id="appointment_div">
            <h2>Appointments Scheduled for Today</h2>
            <table class='widefat striped fixed' id="appointment_list">
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
  </div>
</div>
<!-- pending invoice modal --->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="POST" action="">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Pending Invoice</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <?php foreach ($listInvoice as $dueData) { ?>
            <div class="form-group row">
              <label for="staticEmail" class="col-sm-3 col-form-label"><a href="<?php echo admin_url('admin.php?page=lh-billing-invoice&view&id='.$list[$i]['ref']); ?>" title="Manage Invoice"><?php echo invoice::invoiceNumber( $dueData['ref'] ); ?></a></label>
              <label for="staticEmail" class="col-sm-3 col-form-label">&nbsp;Total: <?php echo "&#8358; ".number_format($dueData['amount'], 2); ?></label>
              <label for="staticEmail" class="col-sm-2 col-form-label">Due</label>
              <div class="col-sm-4">
                <input type="hidden" name="patient_id" value="<?php echo $_REQUEST['id']; ?>">
                <input type="hidden" name="data[<?php echo $rowCount; ?>][ref]" value="<?php echo $dueData['ref']; ?>">
                <input type="number" name="data[<?php echo $rowCount; ?>][amount]" required max="<?php echo $dueData['due']; ?>" class="form-control-plaintext" id="staticEmail" value="<?php echo $dueData['due']; ?>">
              </div>
            </div>
          <?php $rowCount++;} ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" name="payInvoice" class="btn btn-primary">Pay Invoice</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- post payment invoice modal --->
<div class="modal fade" id="poatPayment" tabindex="-1" role="dialog" aria-labelledby="poatPaymentLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="POST" action="">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="poatPaymentLabel">Post Payment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group row">
            <label for="staticEmail" class="col-sm-3 col-form-label">Payment Type</label>
            <div class="col-sm-9">
              <select id="component" name="component" data-id="0" required onchange="getComponent(0)">
                <option value="">Select One</option>
                <?php for ($i = 0; $i < count(billing::$list_component); $i++) { ?>
                  <option value="<?php echo billing::$list_component[$i]['ref']."_".billing::$list_component[$i]['cost']; ?>"><?php echo billing::$list_component[$i]['title']; ?></option>
                <?php } ?>
                <option value="0">Others</option>
                </select>
                <input type="hidden" name="id" id="comp_id" value="" />
            </div>
          </div>
          <div class="form-group row">
            <label for="comp_qty_0" class="col-sm-3 col-form-label">Quantity</label>
            <div class="col-sm-9">
              <input type="number" class="form-control-plaintext" name="quantity" id="comp_qty" value="1" placeholder="Quantity" required />
            </div>
          </div>
          <div class="form-group row">
            <label for="comp_qty_0" class="col-sm-3 col-form-label">Cost</label>
            <div class="col-sm-9">
              <input type="number" class="form-control-plaintext" name="cost" id="comp_cost" step='0.01' value="" placeholder="&#8358; 0.00" required />
            </div>
          </div>
          <div class="form-group row">
            <label for="staticEmail" class="col-sm-3 col-form-label">Description (Optional)</label>
            <div class="col-sm-9">
              <textarea name="description" id="comp_desc" class="" placeholder="Description (Optional)"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="patient_id" value="<?php echo $_REQUEST['id']; ?>">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" name="postPayment" class="btn btn-primary">Post Payment</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script>
  function getComponent( ) {
      var data = jQuery("#component" ).val();
      var result = data.split("_");
      jQuery("#comp_id").val( result[0]);
      jQuery("#comp_cost").val( result[1]);
  }
  
  const product = [];
  jQuery(function ($) {
      $('#procedure').select2();
      $('#product').select2({
        placeholder: "Prescribe Medication",
        allowClear: true
      });
      $('#next_appointment').datetimepicker({
          minDate:'<?php echo date("Y-m-d"); ?>',
          minTime:'9:00',
          maxTime:'16:30'
      });
      $('#datatable_list, #appointment_list').DataTable();
      $('#editable-select').editableSelect();
      
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

      $( "#control_doctors_note" ).click(function() {
        $('#control_doctors_note_div').show();
        $('#control_sm_bt_div').hide();
        $('#control_cs_bt_div').hide();
        $('#control_po_bt_div').hide();
        $('#control_m_bt_div').hide();
        $('#control_mg_bt_div').hide();
        $('#control_fb_bt_div').hide();
        $('#control_fo_bt_div').hide();

        $('#updatedMessage').hide();
        $('#errorMessage').hide();

        doctorsNotesRecords();
      });

      $( "#control_sm_bt" ).click(function() {
        $('#control_doctors_note_div').hide();
        $('#control_sm_bt_div').show();
        $('#control_cs_bt_div').hide();
        $('#control_po_bt_div').hide();
        $('#control_m_bt_div').hide();
        $('#control_mg_bt_div').hide();
        $('#control_fb_bt_div').hide();
        $('#control_fo_bt_div').hide();

        $('#updatedMessage').hide();
        $('#errorMessage').hide();
      });

      $( "#control_cs_bt, #control_cs_bt_l" ).click(function() {
        $('#control_doctors_note_div').hide();
        $('#control_sm_bt_div').hide();
        $('#control_cs_bt_div').show();
        $('#control_po_bt_div').hide();
        $('#control_m_bt_div').hide();
        $('#control_mg_bt_div').hide();
        $('#control_fb_bt_div').hide();
        $('#control_fo_bt_div').hide();

        $('#cs_notes').focus();

        $('#updatedMessage').hide();
        $('#errorMessage').hide();
      });

      $( "#control_po_bt, #control_po_bt_l" ).click(function() {
        $('#control_doctors_note_div').hide();
        $('#control_sm_bt_div').hide();
        $('#control_cs_bt_div').hide();
        $('#control_po_bt_div').show();
        $('#control_m_bt_div').hide();
        $('#control_mg_bt_div').hide();
        $('#control_fb_bt_div').hide();
        $('#control_fo_bt_div').hide();

        $('#surgery').focus();

        $('#updatedMessage').hide();
        $('#errorMessage').hide();
      });
      
      $( "#control_m_bt, #control_m_bt_l" ).click(function() {
        $('#control_doctors_note_div').hide();
        $('#control_sm_bt_div').hide();
        $('#control_cs_bt_div').hide();
        $('#control_po_bt_div').hide();
        $('#control_m_bt_div').show();
        $('#control_mg_bt_div').hide();
        $('#control_fb_bt_div').hide();
        $('#control_fo_bt_div').hide();

        $('#m_route').focus();
        
        $('#updatedMessage').hide();
        $('#errorMessage').hide();
      });

      $( "#control_mg_bt, #control_mg_bt_l" ).click(function() {
        $('#control_doctors_note_div').hide();
        $('#control_sm_bt_div').hide();
        $('#control_cs_bt_div').hide();
        $('#control_po_bt_div').hide();
        $('#control_m_bt_div').hide();
        $('#control_mg_bt_div').show();
        $('#control_fb_bt_div').hide();
        $('#control_fo_bt_div').hide();

        $('#surgery').focus();

        $('#updatedMessage').hide();
        $('#errorMessage').hide();
      });

      $( "#control_fb_bt, #control_fb_bt_l" ).click(function() {
        $('#control_doctors_note_div').hide();
        $('#control_sm_bt_div').hide();
        $('#control_cs_bt_div').hide();
        $('#control_po_bt_div').hide();
        $('#control_m_bt_div').hide();
        $('#control_mg_bt_div').hide();
        $('#control_fb_bt_div').show();
        $('#control_fo_bt_div').hide();

        $('#iv_fluid').focus();

        $('#updatedMessage').hide();
        $('#errorMessage').hide();
      });

      $( "#control_fo_bt" ).click(function() {
        $('#control_doctors_note_div').hide();
        $('#control_sm_bt_div').hide();
        $('#control_cs_bt_div').hide();
        $('#control_po_bt_div').hide();
        $('#control_m_bt_div').hide();
        $('#control_mg_bt_div').hide();
        $('#control_fb_bt_div').hide();
        $('#control_fo_bt_div').show();

        $('#updatedMessage').hide();
        $('#errorMessage').hide();
      });

      $( "#add_button_4" ).click(function() {
        $('#vital_signs').hide();
        $('#control_buttons').hide();
        $('#appointment_div').show();
      });

      $( "#add_button_2" ).click(function() {
        $('.fullName').html($('#names').val());
        $('#vital_signs').show();

        $('#control_buttons').hide();
        $('.control_bt').hide();

        $('#appointment_div').hide();
      });

      // show patient button
      $( "#add_button_3" ).click(function() {
        $('.fullName').html($('#names').val());
        $('#control_buttons').show();
        $('#add_button_4').show();
        
        $('#appointment_div').hide();
        $('#vital_signs').hide();
        $('#add_button_3').hide();

        summary();
      });

      // close patient button
      $( "#add_button_4" ).click(function() {
        $('#appointment_div').show();
        $('#add_button_3').show();
        
        $('#control_buttons').hide();
        $('#vital_signs').hide();
        $('#add_button_4').hide();
        $('.control_bt').hide();
      });

      // vitals show and hide buttons
      $( "#vital_add_record" ).click(function() {
        $('#add_record_div').show();
        $('#show_record_div').hide();
        $('#vital_add_record').hide();
        $('#vital_show_record').show();
      });

      $( "#vital_show_record" ).click(function() {
        $('#show_record_div').show();
        $('#add_record_div').hide();
        $('#vital_add_record').show();
        $('#vital_show_record').hide();

        $('#vital_list').DataTable();
      });

      // medication show and hide buttons
      $( "#m_add_record" ).click(function() {
        $('#m_add_record').hide();
        $('#m_show_record').show();
        $('#m_show_record_div').hide();
        $('#m_add_record_div').show();
      });

      $( "#m_show_record" ).click(function() {
        $('#m_add_record').show();
        $('#m_show_record').hide();
        $('#m_show_record_div').show();
        $('#m_add_record_div').hide();
        medicationRecord();
      });

      // massage show and hide buttons
      $( "#mg_add_record" ).click(function() {
        $('#mg_add_record').hide();
        $('#mg_show_record').show();
      });

      $( "#mg_show_record" ).click(function() {
        $('#mg_add_record').show();
        $('#mg_show_record').hide();
      });

      // continuation sheet show and hide buttons
      $( "#cs_add_record" ).click(function() {
        $('#cs_add_record').hide();
        $('#cs_show_record').show();
        $('#cs_show_record_div').hide();
        $('#cs_add_record_div').show();
      });

      $( "#cs_show_record" ).click(function() {
        $('#cs_add_record').show();
        $('#cs_show_record').hide();
        $('#cs_show_record_div').show();
        $('#cs_add_record_div').hide();

        continuationSheetRecords();
      });

      // post op show and hide buttons
      $( "#po_add_record" ).click(function() {
        $('#po_add_record').hide();
        $('#po_show_record').show();
        $('#po_show_record_div').hide();
        $('#po_add_record_div').show();
      });

      $( "#po_show_record" ).click(function() {
        $('#po_add_record').show();
        $('#po_show_record').hide();
        $('#po_show_record_div').show();
        $('#po_add_record_div').hide();

        postOpRecord();
      });

      // fluid balance show and hide buttons
      $( "#fb_add_record" ).click(function() {
        $('#fb_add_record').hide();
        $('#fb_show_record').show();
        $('#fb_show_record_div').hide();
        $('#fb_add_record_div').show();
      });

      $( "#fb_show_record" ).click(function() {
        $('#fb_add_record').show();
        $('#fb_show_record').hide();
        $('#fb_show_record_div').show();
        $('#fb_add_record_div').hide();

        fluidBalanceRecord();
      });
      
      $('#weight, #height').keyup(function() {
        var weight = $('#weight').val();
        var height = ($('#height').val()/100);
        var bmi = (weight/(height*height))
        $('#bmi').val(bmi.toFixed(2));
      });

      $('#search').autocomplete({
          source: se_ajax_url,
          minLength: 2,
          select: function( event, ui ) {
              window.location = '<?php echo admin_url('admin.php?page=lh-manage-clinic&patient&id='); ?>' + ui.item.id;
          }
      });
    
  } );

  function minus_button( key ) {
    jQuery( "#div_" + key ).remove();
  }

  function add_button() {
    jQuery(function ($) {
      var medication_id = $("#medication_id").val();
      var medication_text = $("#medication_id option:selected").text();
      var quantity = $("#quantity").val();
      var dose = $("#dose").val();
      var frequency = $("#frequency").val();
      var notes = $("#notes").val();

      if ((medication_id == "") || (quantity == "") || (dose == "") || (frequency == "")) {
        alert("One or more needed field is empty");
        return false;
      }
      var data = [
        medication_id,
        quantity,
        dose,
        frequency,
        notes
      ]

      product.push(data);

      console.log(product);
      var len  = product.length-1;

      var dataArray = "<tr>"
        +"<td>"+medication_text+"</td>"
        +"<td>"+quantity+"</td>"
        +"<td>"+dose+" "+frequency+"</td>"
        +"<td>"+notes+"</td>"
        +"<td><a href='Javascript:void(0);' onclick='removeId("+len+")'>Remove</a></td>"
      +"</tr>"
      jQuery('#drugList tr:last').after(dataArray);

    });
  }

  function removeId(id) {
    var list = [];

    <?php for ($i = 0; $i < count($inventoryList); $i++) { ?>
      list[<?php echo $inventoryList[$i]['ref']; ?>] = "<?php echo $inventoryList[$i]['title']; ?>";
    <?php } ?>

    const index = product.indexOf(id);
    if (index > -1) {
      product.splice(index, 1);
    }

    var len = 0;

    jQuery("#drugList tbody").empty();
    product.forEach(function(number) {
      console.log(number);

      var dataArray = "<tr>"
        +"<td>"+list[number.medication_id]+"</td>"
        +"<td>"+number.quantity+"</td>"
        +"<td>"+number.dose+" "+number.frequency+"</td>"
        +"<td>"+number.notes+"</td>"
        +"<td><a href='Javascript:void(0);' onclick='removeId("+len+")'>Remove</a></td>"
      +"</tr>"

      jQuery('#drugList tr:last').after(dataArray);
      len++;
    });
  }

  function doctorsNotes() {
    jQuery(function ($) {
      var patient_id = $("#patient_id").val();
      var report = $("#doctors_note_data").val();

      var doctorsNotes = $('#form3').serializeArray();

      console.log(doctorsNotes);
      console.log(product);
      var sendInfo = {
        patient_id: patient_id,
        report: report,
        recommended: product
      };
      console.log(sendInfo);

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

      var url = '<?php echo get_rest_url()."api/patient/notes"; ?>';

      jQuery.post( url, JSON.stringify( sendInfo ), function( data ) {
        if (data.status == "200") {
          $('#updatedMessage').show();
          $('#updatedMessage').html('<p>Record Added!</p>');
          $("#doctors_note_data").val("");
          $("#doctors_note_data").focus();

          doctorsNotesRecords();
        } else {
          $('#errorMessage').show();
          $('#errorMessage').html('<p>An error occured, Try  Again!</p>');
        }
      });
    });
  }

  function doctorsNotesRecords() {
    jQuery(function ($) {
      $('#doctors_notice').html('loading data...');
      var patient_id = document.getElementById('patient_id').value;
      var se_ajax_url = '<?php echo get_rest_url().'api/patient/notes/'; ?>'+patient_id;

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

        $('#doctors_notice').html('');
        $("#doctors_list").html('');
        for (var key in data.data) {
          $("#doctors_list").append("<br>"+data.data[key].report+'<br><strong>Recommendations</strong><br><small><strong>Added By: '+data.data[key].added_by.user_nicename+' at '+data.data[key].create_time+'</strong></small><br><br>');
        }
      });

    });
  }

  function continuationSheet() {
    jQuery(function ($) {
      var patient_id = $("#patient_id").val();
      var notes = $("#cs_notes").val();

      var sendInfo = {
        patient_id: patient_id,
        notes: notes
      };

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

      var url = '<?php echo get_rest_url()."api/patient/continuation"; ?>';

      jQuery.post( url, JSON.stringify( sendInfo ), function( data ) {
        if (data.status == "200") {
          $('#cs_show_record_div').show();
          $('#cs_add_record_div').hide();

          $('#cs_add_record').hide();
          $('#cs_show_record').show();

          $('#updatedMessage').show();
          $('#updatedMessage').html('<p>Record Added!</p>');

          continuationSheetRecords();
        } else {
          $('#errorMessage').show();
          $('#errorMessage').html('<p>An error occured, Try  Again!</p>');
        }
      });
    });
  }

  function continuationSheetRecords() {
    jQuery(function ($) {
      $('#cs_notice').html('loading data...');
      var patient_id = document.getElementById('patient_id').value;
      var se_ajax_url = '<?php echo get_rest_url().'api/patient/continuation/'; ?>'+patient_id;

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

        $('#cs_notice').html('');
        $("#cs_list").html('');
        for (var key in data.data) {
          $("#cs_list").append('<li><h2>'+data.data[key].notes+'</h2><small><strong>Added By: '+data.data[key].added_by.user_nicename+' at '+data.data[key].create_time+'</strong></small></li>');
        }
      });

    });
  }

  function postOp() {
    jQuery(function ($) {
      var patient_id = $("#patient_id").val();
      var notes = $("#notes").val();
      var surgery = $("#surgery").val();
      var surgery_category = $("#surgery_category").val();
      var Indication = $("#Indication").val();
      var surgeon = $("#surgeon").val();
      var asst_surgeon = $("#asst_surgeon").val();
      var per_op_nurse = $("#per_op_nurse").val();
      var circulating_nurse = $("#circulating_nurse").val();
      var anaesthesia = $("#patient_anaesthesiaid").val();
      var anaesthesia_time = $("#anaesthesia_time").val();
      var knife_on_skin = $("#knife_on_skin").val();
      var infiltration_time = $("#infiltration_time").val();
      var liposuction_time = $("#liposuction_time").val();
      var procedure = $("#procedure2").val();
      var amt_of_fat_right = $("#amt_of_fat_right").val();
      var amt_of_fat_left = $("#amt_of_fat_left").val();
      var amt_of_fat_other = $("#amt_of_fat_other").val();
      var ebl = $("#ebl").val();
      var plan = $("#patient_id").val();

      var sendInfo = {
        patient_id: patient_id,
        notes: notes,
        surgery: surgery,
        surgery_category: surgery_category,
        Indication: Indication,
        surgeon: surgeon,
        asst_surgeon: asst_surgeon,
        per_op_nurse: per_op_nurse,
        circulating_nurse: circulating_nurse,
        anaesthesia: anaesthesia,
        anaesthesia_time: anaesthesia_time,
        knife_on_skin: knife_on_skin,
        infiltration_time: infiltration_time,
        liposuction_time: liposuction_time,
        procedure: procedure,
        amt_of_fat_right: amt_of_fat_right,
        amt_of_fat_left: amt_of_fat_left,
        amt_of_fat_other: amt_of_fat_other,
        ebl: ebl,
        plan: plan
      };

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

      var url = '<?php echo get_rest_url()."api/patient/postOp"; ?>';

      jQuery.post( url, JSON.stringify( sendInfo ), function( data ) {
        if (data.status == "200") {
          $('#po_show_record_div').show();
          $('#po_add_record_div').hide();
          
          $('#po_add_record').hide();
          $('#po_show_record').show();

          $('#updatedMessage').show();
          $('#updatedMessage').html('<p>Record Added!</p>');

          postOpRecord();
        } else {
          $('#errorMessage').show();
          $('#errorMessage').html('<p>An error occured, Try  Again!</p>');
        }
      });
    });
  }

  function postOpRecord() {
    jQuery(function ($) {
      $('#po_notice').html('loading data...');
      var patient_id = document.getElementById('patient_id').value;
      var se_ajax_url = '<?php echo get_rest_url().'api/patient/postOp/'; ?>'+patient_id;

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

      var tabledata = '';
      var count = 1;
      jQuery.get( se_ajax_url, function( data ) {

        $('#po_notice').html('');
        $("#po_list").html('');
        for (var key in data.data) {
          tabledata = '';

          tabledata = '<tr>'+
          '<th class="check-column" scope="row">'+count+'</th>'+
          '<td class="column-columnname">'+data.data[key].surgery+' ('+data.data[key].surgery_category+'}</td>'+
          '<td class="column-columnname">'+data.data[key].indication+'</td>'+
          '<td class="column-columnname">'+data.data[key].surgeon+'</td>'+
          '<td class="column-columnname">'+data.data[key].asst_surgeon+'</td>'+
          '<td class="column-columnname">'+data.data[key].per_op_nurse+'</td>'+
          '<td class="column-columnname">'+data.data[key].circulating_nurse+'</td>'+
          '<td class="column-columnname">'+data.data[key].anaesthesia+'</td>'+
          '<td class="column-columnname">'+data.data[key].anaesthesia_time+'</td>'+
          '<td class="column-columnname">'+data.data[key].knife_on_skin+'</td>'+
          '<td class="column-columnname">'+data.data[key].infiltration_time+'</td>'+
          '<td class="column-columnname">'+data.data[key].liposuction_time+'</td>'+
          '<td class="column-columnname">'+data.data[key].end_of_surgery+'</td>'+
          '<td class="column-columnname">'+data.data[key].procedure+'</td>'+
          '<td class="column-columnname">'+data.data[key].amt_of_fat_right+'</td>'+
          '<td class="column-columnname">'+data.data[key].amt_of_fat_left+'</td>'+
          '<td class="column-columnname">'+data.data[key].amt_of_fat_other+'</td>'+
          '<td class="column-columnname">'+data.data[key].ebl+'</td>'+
          '<td class="column-columnname">'+data.data[key].plan+'</td>'+
          '<td class="column-columnname">'+data.data[key].added_by.user_nicename+'</td>'+
          '</tr>';
          count++;

          $("#po_list").append(tabledata);
        }
      });

    });
  }

  function addMedication() {
    jQuery(function ($) {
      var patient_id = $("#patient_id").val();
      var route = $("#m_route").val();
      var medication = $("#m_medication").val();
      var dose = $("#m_dose").val();
      var frequency = $("#m_frequency").val();
      var report_date = $("#m_report_date").val();
      var report_time = $("#m_report_time").val();

      var sendInfo = {
        patient_id: patient_id,
        route: route,
        medication: medication,
        dose: dose,
        report_date: report_date,
        report_time: report_time,
        frequency: frequency
      };

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

      var url = '<?php echo get_rest_url()."api/patient/medication"; ?>';

      jQuery.post( url, JSON.stringify( sendInfo ), function( data ) {
        if (data.status == "200") {
          $('#m_show_record_div').show();
          $('#m_add_record_div').hide();
          
          $('#m_add_record').hide();
          $('#m_show_record').show();

          $('#updatedMessage').show();
          $('#updatedMessage').html('<p>Record Added!</p>');

          medicationRecord();
        } else {
          $('#errorMessage').show();
          $('#errorMessage').html('<p>An error occured, Try  Again!</p>');
        }
      });
    });
  }

  function medicationRecord() {
    jQuery(function ($) {
      $('#m_notice').html('loading data...');
      var patient_id = document.getElementById('patient_id').value;
      var se_ajax_url = '<?php echo get_rest_url().'api/patient/medication/'; ?>'+patient_id;

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

      var tabledata = '';
      var count = 1;
      jQuery.get( se_ajax_url, function( data ) {

        $('#m_notice').html('');
        $("#m_list").html('');
        for (var key in data.data) {
          tabledata = '';

          tabledata = '<tr>'+
          '<th class="check-column" scope="row">'+count+'</th>'+
          '<td class="column-columnname">'+data.data[key].route+'</td>'+
          '<td class="column-columnname">'+data.data[key].medication+'</td>'+
          '<td class="column-columnname">'+data.data[key].dose+'</td>'+
          '<td class="column-columnname">'+data.data[key].frequency+'</td>'+
          '<td class="column-columnname">'+data.data[key].report_date+' '+data.data[key].report_time+'</td>'+
          '<td class="column-columnname">'+data.data[key].added_by.user_nicename+'</td>'+
          '<td class="column-columnname">'+data.data[key].create_time+'</td>'+
          '</tr>';
          count++;

          $("#m_list").append(tabledata);
        }
      });

    });
  }

  function fluidBalance() {
    jQuery(function ($) {
      var patient_id = $("#patient_id").val();
      var iv_fluid = $("#iv_fluid").val();
      var amount = $("#amount").val();
      var oral_fluid = $("#oral_fluid").val();
      var ng_tube_feeding = $("#ng_tube_feeding").val();
      var vomit = $("#vomit").val();
      var urine = $("#urine").val();
      var drains = $("#drains").val();
      var ng_tube_drainage = $("#ng_tube_drainage").val();
      var report_date = $("#report_date").val();
      var report_time = $("#report_time").val();
      

      var sendInfo = {
        patient_id: patient_id,
        iv_fluid: iv_fluid,
        amount: amount,
        oral_fluid: oral_fluid,
        ng_tube_feeding: ng_tube_feeding,
        vomit: vomit,
        urine: urine,
        drains: drains,
        ng_tube_drainage: ng_tube_drainage,
        report_date: report_date,
        report_time: report_time
      };


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

      var url = '<?php echo get_rest_url()."api/patient/fluidBalance"; ?>';

      jQuery.post( url, JSON.stringify( sendInfo ), function( data ) {
        if (data.status == "200") {
          $('#fb_show_record_div').show();
          $('#fb_add_record_div').hide();
          
          $('#fb_add_record').hide();
          $('#fb_show_record').show();

          $('#updatedMessage').show();
          $('#updatedMessage').html('<p>Record Added!</p>');

          fluidBalanceRecord();
        } else {
          $('#errorMessage').show();
          $('#errorMessage').html('<p>An error occured, Try  Again!</p>');
        }
      });
    });
  }

  function fluidBalanceRecord() {
    jQuery(function ($) {
      $('#fb_notice').html('loading data...');
      var patient_id = document.getElementById('patient_id').value;
      var se_ajax_url = '<?php echo get_rest_url().'api/patient/fluidBalance/'; ?>'+patient_id;

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

      var tabledata = '';

      $("#fb_list").html('');
      var count = 1;
      jQuery.get( se_ajax_url, function( data ) {

        $('#fb_notice').html('');
        for (var key in data.data) {
          tabledata = '';

          tabledata = '<tr>'+
          '<th class="check-column" scope="row">'+count+'</th>'+
          '<td class="column-columnname">'+data.data[key].iv_fluid+'</td>'+
          '<td class="column-columnname">'+data.data[key].amount+'</td>'+
          '<td class="column-columnname">'+data.data[key].oral_fluid+'</td>'+
          '<td class="column-columnname">'+data.data[key].ng_tube_feeding+'</td>'+
          '<td class="column-columnname">'+data.data[key].vomit+'</td>'+
          '<td class="column-columnname">'+data.data[key].urine+'</td>'+
          '<td class="column-columnname">'+data.data[key].drains+'</td>'+
          '<td class="column-columnname">'+data.data[key].ng_tube_drainage+'</td>'+
          '<td class="column-columnname">'+data.data[key].report_date+' '+data.data[key].report_time+'</td>'+
          '<td class="column-columnname">'+data.data[key].added_by.user_nicename+'</td>'+
          '<td class="column-columnname">'+data.data[key].create_time+'</td>'+
          '</tr>';
          count++;

          $("#fb_list").append(tabledata);
        }
      });

    });
  }

  function summary() {
    jQuery(function ($) {
      $("#control_sm_bt_div").show();
      $('#sm_notice').html('loading data...');

      var patient_id = document.getElementById('patient_id').value;
      var vitals_ajax_url = '<?php echo get_rest_url().'api/patient/vitals/recent/'; ?>'+patient_id;
      var continuation_ajax_url = '<?php echo get_rest_url().'api/patient/continuation/recent/'; ?>'+patient_id;
      var postOp_ajax_url = '<?php echo get_rest_url().'api/patient/postOp/recent/'; ?>'+patient_id;
      var medication_ajax_url = '<?php echo get_rest_url().'api/patient/medication/recent/'; ?>'+patient_id;
      var fluidBalance_ajax_url = '<?php echo get_rest_url().'api/patient/fluidBalance/recent/'; ?>'+patient_id;

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


      jQuery.get( vitals_ajax_url, function( data ) {
        $('#summary_v_weight').html(data.data.weight);
        $('#summary_v_height').html(data.data.height);
        $('#summary_v_bmi').html(data.data.bmi);
        $('#summary_v_spo2').html(data.data.spo2);
        $('#summary_v_respiratory').html(data.data.respiratory);
        $('#summary_v_temprature').html(data.data.temprature);
        $('#summary_v_pulse').html(data.data.pulse);
        $('#summary_v_bp_sys').html(data.data.bp_sys);
        $('#summary_v_bp_dia').html(data.data.bp_dia);
        $('#summary_v_added_by').html(data.data.added_by.user_nicename);
        $('#summary_v_create_time').html(data.data.create_time);
      });

      jQuery.get( continuation_ajax_url, function( data ) {
        if (data.data === false ) {
          $('#summary_c').html("Not Available");
          $('#summary_c_create_time,#summary_added_by').html("");
        } else {
          $('#summary_c').html(data.data.notes);
          $('#summary_c_create_time').html("Added On: "+data.data.create_time);
          $('#summary_added_by').html("Added By: "+data.data.added_by.user_nicename);
        }
      });

      jQuery.get( postOp_ajax_url, function( data ) {
        if (data.data === false ) {
          $('#summary_p_surgery,#summary_p_surgery_category,#summary_p_indication,#summary_p_surgeon,#summary_p_asst_surgeon,#summary_p_per_op_nurse,#summary_p_circulating_nurse,#summary_p_anaesthesia,#summary_p_anaesthesia_time,#summary_p_knife_on_skin,#summary_p_infiltration_time,#summary_p_liposuction_time,#summary_p_end_of_surgery,#summary_p_procedure,#summary_p_amt_of_fat_right,#summary_p_amt_of_fat_left,#summary_p_amt_of_fat_other,#summary_p_ebl,#summary_p_plan,#summary_p_added_by,#summary_p_create_time').html("Not Available");
        } else {
          $('#summary_p_surgery').html(data.data.surgery);
          $('#summary_p_surgery_category').html(data.data.surgery_category);
          $('#summary_p_indication').html(data.data.indication);
          $('#summary_p_surgeon').html(data.data.surgeon);
          $('#summary_p_asst_surgeon').html(data.data.asst_surgeon);
          $('#summary_p_per_op_nurse').html(data.data.per_op_nurse);
          $('#summary_p_circulating_nurse').html(data.data.circulating_nurse);
          $('#summary_p_anaesthesia').html(data.data.anaesthesia);
          $('#summary_p_anaesthesia_time').html(data.data.anaesthesia_time);
          $('#summary_p_knife_on_skin').html(data.data.knife_on_skin);
          $('#summary_p_infiltration_time').html(data.data.infiltration_time);
          $('#summary_p_liposuction_time').html(data.data.liposuction_time);
          $('#summary_p_end_of_surgery').html(data.data.end_of_surgery);
          $('#summary_p_procedure').html(data.data.procedure);
          $('#summary_p_amt_of_fat_right').html(data.data.amt_of_fat_right);
          $('#summary_p_amt_of_fat_left').html(data.data.amt_of_fat_left);
          $('#summary_p_amt_of_fat_other').html(data.data.amt_of_fat_other);
          $('#summary_p_ebl').html(data.data.ebl);
          $('#summary_p_plan').html(data.data.plan);
          $('#summary_p_added_by').html(data.data.added_by.user_nicename);
          $('#summary_p_create_time').html(data.data.create_time);
        }
      });
      jQuery.get( medication_ajax_url, function( data ) {
        if (data.data === false ) {
          $('#summary_m_route,#summary_m_medication,#summary_m_dose,#summary_m_frequency,#summary_m_report_date,#summary_m_added_by,#summary_m_create_time').html("Not Available");
        } else {
          $('#summary_m_route').html(data.data.route);
          $('#summary_m_medication').html(data.data.medication);
          $('#summary_m_dose').html(data.data.dose);
          $('#summary_m_frequency').html(data.data.frequency);
          $('#summary_m_report_date').html(data.data.report_date+ " "+data.data.report_time);
          $('#summary_m_added_by').html(data.data.added_by.user_nicename);
          $('#summary_m_create_time').html(data.data.create_time);
        }
      });
      jQuery.get( fluidBalance_ajax_url, function( data ) {
        if (data.data === false ) {
          $('#summary_fb_iv_fluid,#summary_fb_amount,#summary_fb_oral_fluid,#summary_fb_ng_tube_feeding,#summary_fb_vomit,#summary_fb_urine,#summary_fb_drains,#summary_fb_ng_tube_drainage,#summary_fb_report_date,#summary_fb_added_by,#summary_fb_create_time').html("Not Available");
        } else {
          $('#summary_fb_iv_fluid').html(data.data.iv_fluid);
          $('#summary_fb_amount').html(data.data.amount);
          $('#summary_fb_oral_fluid').html(data.data.oral_fluid);
          $('#summary_fb_ng_tube_feeding').html(data.data.ng_tube_feeding);
          $('#summary_fb_vomit').html(data.data.vomit);
          $('#summary_fb_urine').html(data.data.urine);
          $('#summary_fb_drains').html(data.data.drains);
          $('#summary_fb_ng_tube_drainage').html(data.data.ng_tube_drainage);
          $('#summary_fb_report_date').html(data.data.report_date+ " "+data.data.report_time);
          $('#summary_fb_added_by').html(data.data.added_by.user_nicename);
          $('#summary_fb_create_time').html(data.data.create_time);
        }
      });

      $('#sm_notice').html('');
    });  
  }
</script>