<?php
    $tag = self::$tag;
    $list = self::$list;
?>
<style>
  .right {
  float: right;
}
</style>
<small class="right">Print Date: <?php echo date('l jS \of F Y h:i:s A'); ?></small>
<table class="widefat striped fixed" id="datatable_list">
    <thead>
    <tr>
        <th class="manage-column column-cb check-column"></th>
        <th class="manage-column column-columnname" scope="col">Invoice</th>
        <th class="manage-column column-columnname" scope="col">Patient</th>
        <th class="manage-column column-columnname" scope="col">Amount</th>
        <th class="manage-column column-columnname" scope="col">Pending</th>
        <th class="manage-column column-columnname" scope="col">Status</th>
        <th class="manage-column column-columnname" scope="col">Created</th>
        <th class="manage-column column-columnname" scope="col">Last Modified</th>
        <th class="manage-column column-columnname" scope="col"></th>
    </tr>
    </thead>
    <tbody>
    <?php $count = 1;
    for ($i = 0;  $i < count($list); $i++) { ?>
    <tr <?php echo $alt; ?>>
        <th class="check-column" scope="row"><?php echo $count; ?></th>
        <td class="column-columnname"><?php echo invoice::invoiceNumber( $list[$i]['ref'] ); ?></td>
        <td class="column-columnname"><?php echo patient::getSingle( $list[$i]['patient_id'] )." ".patient::getSingle( $list[$i]['patient_id'], "first_name" ); ?></td>
        <td class="column-columnname"><?php echo "&#8358; ".number_format( $list[$i]['amount'], 2 ); ?></td>
        <td class="column-columnname"><?php echo "&#8358; ".number_format( $list[$i]['amount'], 2 ); ?></td>
        <td class="column-columnname"><?php echo $list[$i]['status']; ?></td>
        <td class="column-columnname"><?php echo $list[$i]['create_time']; ?></td>
        <td class="column-columnname"><?php echo $list[$i]['modify_time']; ?></td>
        <td class="column-columnname">
            <?php if ($list[$i]['status'] == "UN-PAID") { ?>
            <a href="<?php echo admin_url('admin.php?page=lh-billing-invoice&remove&id='.$list[$i]['ref']); ?>" title="Cancel Invoice" onClick="return confirm('this action will remove this invoice. This action can not be undone. are you sure you want to continue ?')"><i class="fas fa-trash-alt" style="color:red"></i></a>
            <?php } ?>
        </td>
    </tr>
    <?php $count++;
    } ?>
    </tbody>
    <thead>
    <tr>
        <th class="manage-column column-cb check-column"></th>
        <th class="manage-column column-columnname" scope="col">Invoice</th>
        <th class="manage-column column-columnname" scope="col">Patient</th>
        <th class="manage-column column-columnname" scope="col">Amount</th>
        <th class="manage-column column-columnname" scope="col">Pending</th>
        <th class="manage-column column-columnname" scope="col">Status</th>
        <th class="manage-column column-columnname" scope="col">Created</th>
        <th class="manage-column column-columnname" scope="col">Last Modified</th>
        <th class="manage-column column-columnname" scope="col"></th>
    </tr>
    </thead>
</table>
<script>
    window.print();
    window.onfocus=function(){ window.close();}
</script>