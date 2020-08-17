<?php
$tag = self::$tag;
$list = self::$list;
$data = self::$viewData;
$url = self::$url; ?>

<style>
  .right {
    float: right;
  }
</style>
<div class="wrap">
<h1 class="wp-heading-inline">Finance Records</h1>
  <div id="col-container" class="wp-clearfix">
    <div id="col-left">
      <div class="col-wrap">
        <div class="form-wrap">
          <?php if (isset(self::$message)): ?><div class="updated"><p><?php echo self::$message; ?></p></div><?php endif; ?>
          <?php if (isset(self::$error_message)): ?><div class="error"><p><?php echo self::$error_message; ?></p></div><?php endif; ?>
          <h2>Get By Patients</h2>
          <form id="form2" name="form2" method="post" action="">
            <div class="form-field form-required term-title-wrap">
              <label for="title"> Patient</label>
              <input type="text" id="search" value="" required />
              <input type="hidden" name="patient_id" id="patient_id" value="" required />
            </div>
            <button name="submitPatient" id="submitPatient" type="submit" class="button button-primary"><i class="fas fa-search"></i>Get Patient's Records</button>
            <button name="reset" id="reset1" type="reset" class="button"><i class="fa fa-undo fa-lg"></i>Reset</button>
          </form>
          <h2>Filter Records</h2>
          <form id="form3" name="form3" method="post" action="">
              <?php if (self::$id > 0) { ?>
            <div class="form-field form-required term-patient_id-wrap">
              <label for="patient_id"> Paatient</label>
              <select id="patient_id2" name="patient_id" required>
                    <option value="ALL">All</option>
                    <option value="<?php echo self::$id; ?>"><?php echo patient::getSingle( self::$id )." ".patient::getSingle( self::$id, "first_name" ); ?></option>
              </select>
            </div>
              <?php } ?>
            <div class="form-field form-required term-status-wrap">
              <label for="status"> Invoice Type</label>
              <select id="status" name="status" required>
                    <option value="ALL">All</option>
                    <option value="UN-PAID">Un-Paid</option>
                    <option value="PAID">Paid</option>
              </select>
            </div>
            <div class="form-field form-required term-from_date-wrap">
              <label for="next_appointment"> From Date</label>
              <input type="date" name="from_date" id="from_date" value="<?php echo date("Y")."-".date("m")."-".date("d"); ?>" max="<?php echo date("Y")."-".date("m")."-".date("d"); ?>" required size="100%" />
            </div>
            <div class="form-field form-required term-to_date-wrap">
              <label for="next_appointment"> To Date</label>
              <input type="date" name="to_date" id="to_date" value="<?php echo date("Y")."-".date("m")."-".date("d"); ?>" max="<?php echo date("Y")."-".date("m")."-".date("d"); ?>" required size="100%" />
            </div>
            <button name="submitFilter" id="submitFilter" type="submit" class="button button-primary"><i class="fas fa-search"></i>Search Records</button>
            <button name="reset" id="reset" type="reset" class="button"><i class="fa fa-undo fa-lg"></i>Reset</button>
          </form>
        </div>
      </div>
    </div>
    <div id="col-right">
      <div class="col-wrap">
        <h2><?php echo $tag; ?></h2>
        <button type="button" class="right" onclick="window.open('<?php echo admin_url('admin.php?page=lh-billing&PrintInvoice'.$url); ?>','_blank')"><i class="fas fa-print"></i> Print</button>&nbsp;<button type="button" class="right" onclick="window.open('<?php echo admin_url('admin.php?page=lh-billing&DownloadInvoice'.$url); ?>','_blank')"><i class="fas fa-download"></i> Download</button>
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
              <td class="column-columnname"><a href="<?php echo admin_url('admin.php?page=lh-billing-invoice&view&id='.$list[$i]['ref']); ?>" title="Manage Invoice"><?php echo invoice::invoiceNumber( $list[$i]['ref'] ); ?></a></td>
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
      </div>
    </div>
  </div>
</div>

<script>
jQuery(function ($) {
    $('#datatable_list').dataTable({
	  "pageLength": 50
    });

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
            $('#patient_id').val( ui.item.id );
        }
    });

} );
</script>