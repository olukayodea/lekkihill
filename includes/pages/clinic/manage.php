<?php
  $list = appointments::$list;
  $data = self::$viewData;
  $appointmentData = self::$appointmentData;
  $vitalsData = self::$vitalsData;
  $allVitalsData = self::$allVitalsData;
  $balance = billing::$balance;
  
  $managePatient = false;
  if ( mktime(0, 0, 0, date("m"), date("d"), date("Y")) < strtotime( $vitalsData['create_time'] ) ) {
    $managePatient = true;
  }
?>
<div class="wrap">
  <h1 class="wp-heading-inline">Clinic</h1>
  <div id="col-container" class="wp-clearfix">
    <div id="col-left">
      <div class="col-wrap">
        <div class="form-wrap">
            <h2>Search Patient</h2>
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
              <button type="button" id="add_button_0" data-id="0" class="button button-primary" onclick=""><i class="fas fa-file-invoice fa-lg"></i>&nbsp;Pending Invoice</button>
            <?php } ?>
            <?php if ($balance+get_option("lh-consultationFee-cost") > 0) { ?>
              <button type="button" id="add_button_1" data-id="0" class="button button-primary"><i class="fas fa-money-check-alt fa-lg"></i>&nbsp;Post Payment</button>
            <?php } ?>
            <button type="button" id="add_button_2" data-id="0" class="button button-primary"><i class="fas fa-thermometer-quarter fa-lg"></i>&nbsp;Record Vitals</button>
            <?php if ($managePatient) { ?>
            <button type="button" id="add_button_3" data-id="0" class="button button-primary"><i class="fas fa-plus-square fa-lg"></i>&nbsp;Manage Patient</button>
            <button type="button" id="add_button_4" data-id="0" class="button button-primary" style="display: none"><i class="fas fa-plus-square fa-lg"></i>&nbsp;Close</button>
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
            <button type="button" id="control_sm_bt" data-id="0" class="button button-primary"><i class="fas fa-clinic-medical fa-lg"></i>&nbsp;Summary</button>
            <button type="button" id="control_cs_bt" data-id="0" class="button button-primary"><i class="fas fa-notes-medical fa-lg"></i>&nbsp;Continuation Sheet</button>
            <button type="button" id="control_po_bt" data-id="0" class="button button-primary"><i class="fas fa-bed fa-lg"></i>&nbsp;Post Operative Note</button>
            <button type="button" id="control_m_bt" data-id="0" class="button button-primary"><i class="fas fa-prescription fa-lg"></i>&nbsp;Medications</button>
            <button type="button" id="control_mg_bt" data-id="0" class="button button-primary"><i class="fas fa-spa fa-lg"></i>&nbsp;Massage</button>
            <button type="button" id="control_fb_bt" data-id="0" class="button button-primary"><i class="fas fa-balance-scale-right fa-lg"></i>&nbsp;Fluid Balance</button>
            <button type="button" id="control_fo_bt" data-id="0" class="button button-primary"><i class="fab fa-wpforms fa-lg"></i>&nbsp;Forms</button>
          </div>
          <div class="col-wrap control_bt" id="control_sm_bt_div" style="display: none">
          </div>
          <div class="col-wrap control_bt" id="control_cs_bt_div" style="display: none">
          </div>
          <div class="col-wrap control_bt" id="control_po_bt_div" style="display: none">
            <h2>Post Operative Note</h2>
            <button type="button" id="po_add_record" data-id="0" class="button button-primary" style="display: none"><i class="fas fa-plus-square fa-lg"></i>&nbsp;Add Record</button>
            <button type="button" id="po_show_record" data-id="0" class="button button-primary"><i class="fas fa-notes-medical fa-lg"></i>&nbsp;History</button>
            <form name="post_op" id="post_op" onsubmit="alert('submit!');return false">
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
                      <input type="time" name="anaesthesia_time" id="anaesthesia_time" value="" required />
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="form-field form-required term-knife_on_skin-wrap">
                      <label for="knife_on_skin"> Knife on Skin</label>
                      <input type="time" name="knife_on_skin" id="knife_on_skin" value="" required />
                    </div>
                  </td>
                  <td>
                    <div class="form-field form-required term-infiltration_time-wrap">
                      <label for="infiltration_time"> Infiltration Time</label>
                      <input type="time" name="infiltration_time" id="infiltration_time" value="" required />
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="form-field form-required term-liposuction_time-wrap">
                      <label for="liposuction_time"> Liposuction Time</label>
                      <input type="time" name="liposuction_time" id="liposuction_time" value="" required />
                    </div>
                  </td>
                  <td>
                    <div class="form-field form-required term-end_of_surgery-wrap">
                      <label for="end_of_surgery"> End of Surgery</label>
                      <input type="time" name="end_of_surgery" id="end_of_surgery" value="" required />
                    </div>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <div class="form-field form-required term-procedure-wrap">
                      <label for="procedure"> Procedure</label>
                      <textarea name="procedure" id="procedure" required></textarea>
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
                      <label for="amt_of_fat_left"> Amount of Fat transfered to Right Buttock</label>
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

              <button type="submit" id="post_op_button" data-id="0" class="button button-primary"><i class="fas fa-save fa-lg"></i>&nbsp;Close</button>
            </form>
          </div>
          <div class="col-wrap control_bt" id="control_m_bt_div" style="display: none">
          </div>
          <div class="col-wrap control_bt" id="control_mg_bt_div" style="display: none">
          </div>
          <div class="col-wrap control_bt" id="control_fb_bt_div" style="display: none">
            <h2>Fluid Balance Chart</h2>
            <button type="button" id="fb_add_record" data-id="0" class="button button-primary" style="display: none"><i class="fas fa-plus-square fa-lg"></i>&nbsp;Add Record</button>
            <button type="button" id="fb_show_record" data-id="0" class="button button-primary"><i class="fas fa-notes-medical fa-lg"></i>&nbsp;History</button>
            <form name="fluid_balance" id="fluid_balance" onsubmit="alert('submit!');return false">
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
                  <td>&nbsp;</td>
                </tr>
              </table>

              <button type="submit" id="fluid_balance_button" data-id="0" class="button button-primary"><i class="fas fa-save fa-lg"></i>&nbsp;Close</button>
            </form>
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

    $( "#control_sm_bt" ).click(function() {
      $('#control_sm_bt_div').show();
      $('#control_cs_bt_div').hide();
      $('#control_po_bt_div').hide();
      $('#control_m_bt_div').hide();
      $('#control_mg_bt_div').hide();
      $('#control_fb_bt_div').hide();
      $('#control_fo_bt_div').hide();
    });

    $( "#control_cs_bt" ).click(function() {
      $('#control_sm_bt_div').hide();
      $('#control_cs_bt_div').show();
      $('#control_po_bt_div').hide();
      $('#control_m_bt_div').hide();
      $('#control_mg_bt_div').hide();
      $('#control_fb_bt_div').hide();
      $('#control_fo_bt_div').hide();
    });

    $( "#control_po_bt" ).click(function() {
      $('#control_sm_bt_div').hide();
      $('#control_cs_bt_div').hide();
      $('#control_po_bt_div').show();
      $('#control_m_bt_div').hide();
      $('#control_mg_bt_div').hide();
      $('#control_fb_bt_div').hide();
      $('#control_fo_bt_div').hide();
    });
    
    $( "#control_m_bt" ).click(function() {
      $('#control_sm_bt_div').hide();
      $('#control_cs_bt_div').hide();
      $('#control_po_bt_div').hide();
      $('#control_m_bt_div').show();
      $('#control_mg_bt_div').hide();
      $('#control_fb_bt_div').hide();
      $('#control_fo_bt_div').hide();
    });

    $( "#control_mg_bt" ).click(function() {
      $('#control_sm_bt_div').hide();
      $('#control_cs_bt_div').hide();
      $('#control_po_bt_div').hide();
      $('#control_m_bt_div').hide();
      $('#control_mg_bt_div').show();
      $('#control_fb_bt_div').hide();
      $('#control_fo_bt_div').hide();
    });

    $( "#control_fb_bt" ).click(function() {
      $('#control_sm_bt_div').hide();
      $('#control_cs_bt_div').hide();
      $('#control_po_bt_div').hide();
      $('#control_m_bt_div').hide();
      $('#control_mg_bt_div').hide();
      $('#control_fb_bt_div').show();
      $('#control_fo_bt_div').hide();
    });

    $( "#control_fo_bt" ).click(function() {
      $('#control_sm_bt_div').hide();
      $('#control_cs_bt_div').hide();
      $('#control_po_bt_div').hide();
      $('#control_m_bt_div').hide();
      $('#control_mg_bt_div').hide();
      $('#control_fb_bt_div').hide();
      $('#control_fo_bt_div').show();
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

    // post op show and hide buttons
    $( "#po_add_record" ).click(function() {
      $('#po_add_record').hide();
      $('#po_show_record').show();
    });

    $( "#po_show_record" ).click(function() {
      $('#po_add_record').show();
      $('#po_show_record').hide();
    });

    // fluid balance show and hide buttons
    $( "#fb_add_record" ).click(function() {
      $('#fb_add_record').hide();
      $('#fb_show_record').show();
    });

    $( "#fb_show_record" ).click(function() {
      $('#fb_add_record').show();
      $('#fb_show_record').hide();
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
</script>
