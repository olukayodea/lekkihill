<?php
$data = self::$patientData;
?>
<style>
.right {
    float: right;
}
.notes {
    font-size: 16px;
}
.buttom {
    vertical-align: bottom;
}
</style>
<small class="right">Print Date: <?php echo date('l jS \of F Y h:i:s A'); ?></small>
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
<h2>Doctor's Notes</h2>
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
        <td scope="col"><h2>Notes</strong></h2>
    </tr>
    <?php foreach($data['notes'] as $row) {
        $user_info = get_userdata($row['added_by']); ?>
    <tr>
        <td colspan="2" class="manage-column column-columnname" scope="col">
            <span class="notes"><?php echo nl2br($row['report']); ?></span><br>
            <small>Added by<br>
            <?php echo $user_info->first_name." ".$user_info->last_name; ?></small><br>
            <small>Added On<br>
            <?php echo $row['create_time']; ?></small><br><br>
        </td>
    </tr>
    <?php } ?>
    </tbody>
</table> 
<script>
    window.print();
    window.onfocus=function(){ window.close();}
</script>