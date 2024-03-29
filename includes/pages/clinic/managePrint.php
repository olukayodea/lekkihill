<?php
$data = self::$patientData;
?>
<style>
  .right {
  float: right;
}
.buttom {
    vertical-align: bottom;
}
</style>
<table class='widefat striped fixed'>
    <tr>
        <td class="buttom"><img src="https://lekkihill.com/wp-content/uploads/2020/05/new-lekki-logo-e1590591179900.png" width="150"></td>
        <td class="buttom">
            17, Omorinre Johnson Street,<br>
            Lekki Phase 1,<br>
            Lagos, Nigeria<br>
            +234 802 237 3339<br>
            info@lekkihill.com<br>
        </td>
    </tr>
</table>
<small class="right">Print Date: <?php echo date('l jS \of F Y h:i:s A'); ?></small>
<h2>Patient's Details</h2>
<table class='widefat striped fixed'>
    <tbody>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Patient ID</strong></td>
        <td class="manage-column column-columnname" scope="col"><i><?php echo $data['details']['patientId']; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Last Name</strong></td>
        <td class="manage-column column-columnname" scope="col"><i><?php echo $data['details']['last_name']; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Other Names</strong></td>
        <td class="manage-column column-columnname" scope="col"><i><?php echo $data['details']['first_name']; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Age</strong></td>
        <td class="manage-column column-columnname" scope="col"><i><?php echo $data['details']['age']; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Sex</strong></td>
        <td class="manage-column column-columnname" scope="col"><i><?php echo $data['details']['sex']; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Phone Number</strong></td>
        <td class="manage-column column-columnname" scope="col"><i><?php echo $data['details']['phone_number']; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Email</strong></td>
        <td class="manage-column column-columnname" scope="col"><i><?php echo $data['details']['email']; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Address</strong></td>
        <td class="manage-column column-columnname" scope="col"><i><?php echo $data['details']['address']; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Next of Kin</strong></td>
        <td class="manage-column column-columnname" scope="col"><i><?php echo $data['details']['next_of_Kin']; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Next of Kin Contact</strong></td>
        <td class="manage-column column-columnname" scope="col"><i><?php echo $data['details']['next_of_contact']; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Next of Kin Address</strong></td>
        <td class="manage-column column-columnname" scope="col"><i><?php echo $data['details']['next_of_address']; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Allergies</strong></td>
        <td class="manage-column column-columnname" scope="col"><i><?php echo $data['details']['allergies']; ?></i></td>
    </tr>
    </tbody>
</table> 
<?php if ((isset($_REQUEST['vitals'])) || (isset($_REQUEST['all']))) { ?>
<h2>Vitals</h2>
<table class='widefat striped fixed'>
    <tbody>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Weight (Kg)</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_v_weight"><i><?php echo ("" != $data['vitals']['weight']) ? $data['vitals']['weight'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Height (cm)</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_v_height"><i><?php echo ("" != $data['vitals']['height']) ? $data['vitals']['height'] : ""; ?></i></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>BMI (kg/m²)</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_v_bmi"><i><?php echo ("" != $data['vitals']['bmi']) ? $data['vitals']['bmi'] : ""; ?></i></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>SpO2 (%)</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_v_spo2"><i><?php echo ("" != $data['vitals']['spo2']) ? $data['vitals']['spo2'] : ""; ?></i></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Respiratory (cpm)</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_v_respiratory"><i><?php echo ("" != $data['vitals']['respiratory']) ? $data['vitals']['respiratory'] : ""; ?></i></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Temperature (⁰C)</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_v_temprature"><i><?php echo ("" != $data['vitals']['temprature']) ? $data['vitals']['temprature'] : ""; ?></i></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Pulse Rate (bpm)</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_v_pulse"><i><?php echo ("" != $data['vitals']['pulse']) ? $data['vitals']['pulse'] : ""; ?></i></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Blood Pressure-SYS (mmHg)</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_v_bp_sys"><i><?php echo ("" != $data['vitals']['bp_sys']) ? $data['vitals']['bp_sys'] : ""; ?></i></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Blood Pressure-DIA (mmHg)</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_v_bp_dia"><i><?php echo ("" != $data['vitals']['bp_dia']) ? $data['vitals']['bp_dia'] : ""; ?></i></i></td>
    </tr>
    <tr>
        <?php $user_info = get_userdata($data['vitals']['added_by']); ?>
        <td class="manage-column column-columnname" scope="col"><strong>Added By</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_v_added_by"><i><?php echo (0 != intval( $data['vitals']['added_by'] ) ) ? $user_info->first_name." ".$user_info->last_name : ""; ?></i></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Added On</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_v_create_time"><i><?php echo ("" != $data['vitals']['create_time']) ? $data['vitals']['create_time'] : ""; ?></i></i></td>
    </tr>
    </tbody>
</table>
<?php } ?>
<?php if ((isset($_REQUEST['continuationSheet'])) || (isset($_REQUEST['all']))) {
    $user_info = get_userdata($data['continuation']['added_by']); ?>
<h2>Clinic Continuation</h2>
<p id="summary_c"><i><?php echo ("" != $data['continuation']['notes']) ? nl2br($data['continuation']['notes']) : ""; ?></i></p>
<small id="summary_added_by"><i><?php echo ("" != $data['continuation']['added_by']) ? $user_info->first_name." ".$user_info->last_name : ""; ?></i></small>
<small id="summary_c_create_time"><i><?php echo ("" != $data['continuation']['create_time']) ? $data['continuation']['create_time'] : ""; ?></i></small><br>
<?php } ?>
<?php if ((isset($_REQUEST['operativeNote'])) || (isset($_REQUEST['all']))) { ?>
<h2>Post Operative Note</h2>
<table class='widefat striped fixed'>
    <tbody>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Surgery</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_p_surgery"><i><?php echo ("" != $data['post_op']['surgery']) ? $data['post_op']['surgery'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Category</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_p_surgery_category"><i><?php echo ("" != $data['post_op']['surgery_category']) ? $data['post_op']['surgery_category'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Indication</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_p_indication"><i><?php echo ("" != $data['post_op']['indication']) ? $data['post_op']['indication'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Surgeon</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_p_surgeon"><i><?php echo ("" != $data['post_op']['surgeon']) ? $data['post_op']['surgeon'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Assistant Surgeon</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_p_asst_surgeon"><i><?php echo ("" != $data['post_op']['asst_surgeon']) ? $data['post_op']['asst_surgeon'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Peri Op Nurse</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_p_per_op_nurse"><i><?php echo ("" != $data['post_op']['per_op_nurse']) ? $data['post_op']['per_op_nurse'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Circulating Nurse</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_p_circulating_nurse"><i><?php echo ("" != $data['post_op']['circulating_nurse']) ? $data['post_op']['circulating_nurse'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Anaestdesia</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_p_anaesthesia"><i><?php echo ("" != $data['post_op']['anaesthesia']) ? $data['post_op']['anaesthesia'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Anaestdesia Time</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_p_anaesthesia_time"><i><?php echo ("" != $data['post_op']['anaesthesia_time']) ? $data['post_op']['anaesthesia_time'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Knife on Skin</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_p_knife_on_skin"><i><?php echo ("" != $data['post_op']['knife_on_skin']) ? $data['post_op']['knife_on_skin'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Infiltration Time</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_p_infiltration_time"><i><?php echo ("" != $data['post_op']['infiltration_time']) ? $data['post_op']['infiltration_time'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Liposuction Time</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_p_liposuction_time"><i><?php echo ("" != $data['post_op']['liposuction_time']) ? $data['post_op']['liposuction_time'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>End of Surgery</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_p_end_of_surgery"><i><?php echo ("" != $data['post_op']['end_of_surgery']) ? $data['post_op']['end_of_surgery'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Procedure</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_p_procedure"><i><?php echo ("" != $data['post_op']['procedure']) ? $data['post_op']['procedure'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Transfered Fat (Right Buttock)</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_p_amt_of_fat_right"><i><?php echo ("" != $data['post_op']['amt_of_fat_right']) ? $data['post_op']['amt_of_fat_right'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Transfered Fat (Right Buttock)</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_p_amt_of_fat_left"><i><?php echo ("" != $data['post_op']['amt_of_fat_left']) ? $data['post_op']['amt_of_fat_left'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Transfered Fat (Otder Areas)</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_p_amt_of_fat_other"><i><?php echo ("" != $data['post_op']['amt_of_fat_other']) ? $data['post_op']['amt_of_fat_other'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>EBL</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_p_ebl"><i><?php echo ("" != $data['post_op']['ebl']) ? $data['post_op']['ebl'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Plan</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_p_plan"><i><?php echo ("" != $data['post_op']['plan']) ? $data['post_op']['plan'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Added By</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_p_added_by"><i><?php echo (0 != intval($data['post_op']['added_by'])) ? $data['post_op']['added_by'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Added On</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_p_create_time"><i><?php echo ("" != $data['post_op']['modify_time']) ? $data['post_op']['modify_time'] : ""; ?></i></td>
    </tr>
    </tbody>
</table> 
<?php } ?>
<?php if ((isset($_REQUEST['medication'])) || (isset($_REQUEST['all']))) { ?>
<h2>Medication</h2>
<table class='widefat striped fixed'>
    <tbody>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Route</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_m_route"><i><?php echo ("" != $data['medication']['route']) ? $data['medication']['route'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Medication</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_m_medication"><i><?php echo ("" != $data['medication']['medication']) ? $data['medication']['medication'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Dose</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_m_dose"><i><?php echo ("" != $data['medication']['dose']) ? $data['medication']['dose'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Frequency</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_m_frequency"><i><?php echo ("" != $data['medication']['frequency']) ? $data['medication']['frequency'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Report Date</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_m_report_date"><i><?php echo ("" != $data['medication']['report_date']) ? $data['medication']['report_date']." : ".$data['medication']['report_time'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Added By</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_m_added_by"><i><?php echo ("" != intval($data['medication']['added_by'])) ? $data['medication']['added_by'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Added On</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_m_create_time"><i><?php echo ("" != $data['medication']['create_time']) ? $data['medication']['create_time'] : ""; ?></i></td>
    </tr>
    </tbody>
</table> 
<?php } ?>
<?php if ((isset($_REQUEST['massage'])) || (isset($_REQUEST['all']))) { ?>
<h2>Massage Register</h2>
<?php } ?>
<?php if ((isset($_REQUEST['fluidBalance'])) || (isset($_REQUEST['all']))) { ?>
<h2>Fluid Balance</h2>
<table class='widefat striped fixed'>
    <tbody>
    <tr>
        <td class="manage-column column-columnname" scope="col" colspan="2"><strong>Intake (ML)</strong></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong> of IV Fluid</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i><?php echo ("" != $data['fluid_balance']['iv_fluid']) ? $data['fluid_balance']['iv_fluid'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Amount</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_fb_amount"><i><?php echo ("" != $data['fluid_balance']['amount']) ? $data['fluid_balance']['amount'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Oral Fluid</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_fb_oral_fluid"><i><?php echo ("" != $data['fluid_balance']['oral_fluid']) ? $data['fluid_balance']['oral_fluid'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>NG Tube Feeding</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_fb_ng_tube_feeding"><i><?php echo ("" != $data['fluid_balance']['ng_tube_feeding']) ? $data['fluid_balance']['ng_tube_feeding'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col" colspan="2"><strong>Output (ML)</strong></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Vomit</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_fb_vomit"><i><?php echo ("" != $data['fluid_balance']['vomit']) ? $data['fluid_balance']['vomit'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Urine</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_fb_urine"><i><?php echo ("" != $data['fluid_balance']['urine']) ? $data['fluid_balance']['urine'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Drains</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_fb_drains"><i><?php echo ("" != $data['fluid_balance']['drains']) ? $data['fluid_balance']['drains'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>NG Tube Drainage</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_fb_ng_tube_drainage"><i><?php echo ("" != $data['fluid_balance']['ng_tube_drainage']) ? $data['fluid_balance']['ng_tube_drainage'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Report Date</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_fb_report_date"><i><?php echo ("" != $data['fluid_balance']['report_date']) ? $data['fluid_balance']['report_date']." : ".$data['fluid_balance']['report_time'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Added By</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_fb_added_by"><i><?php echo (0 != intval($data['fluid_balance']['added_by'])) ? $data['fluid_balance']['added_by'] : ""; ?></i></td>
    </tr>
    <tr>
        <td class="manage-column column-columnname" scope="col"><strong>Added On</strong></td>
        <td class="manage-column column-columnname" scope="col" id="summary_fb_create_time"><i><?php echo ("" != $data['fluid_balance']['create_time']) ? $data['fluid_balance']['create_time'] : ""; ?></i></td>
    </tr>
    </tbody>
</table> 
<?php } ?>
<script>
    window.print();
    window.onfocus=function(){ window.close();}
</script>