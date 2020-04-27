<?php
$tag = self::$tag;
$list = self::$list;
$data = self::$viewData; ?>
<div class="wrap">
<h1 class="wp-heading-inline">Invoices</h1>
  <div id="col-container" class="wp-clearfix">
    <div id="col-left">
      <div class="col-wrap">
        <div class="form-wrap">
          <h2>Create New Invoice</h2>
          <?php if (isset(self::$message)): ?><div class="updated"><p><?php echo self::$message; ?></p></div><?php endif; ?>
          <?php if (isset(self::$error_message)): ?><div class="error"><p><?php echo self::$error_message; ?></p></div><?php endif; ?>
          <form id="form2" name="form2" method="post" action="">
            <div class="form-field form-required term-title-wrap">
              <label for="title"> Patient</label>
              <input type="text" id="search" value="" required />
              <input type="hidden" name="patient_id" id="patient_id" value="" required />
            </div>
            <div class="form-field form-required term-age-wrap">
              <label for="next_appointment"> Due Date</label>
              <input type="date" name="due_date" id="due_date" value="<?php echo date("Y")."-".date("m")."-".date("d"); ?>" min="<?php echo date("Y")."-".date("m")."-".date("d"); ?>" required size="100%" />
            </div>
            <div class="form-field form-required term-age-wrap" id="div_0">
                <label for="component_0">Bill Component</label>
                <select id="component_0" name="component" data-id="0" required onchange="getComponent(0)">
                    <option value="">Select One</option>
                    <?php for ($i = 0; $i < count(billing::$list_component); $i++) { ?>
                        <option value="<?php echo billing::$list_component[$i]['ref']."_".billing::$list_component[$i]['cost']; ?>"><?php echo billing::$list_component[$i]['title']; ?></option>
                    <?php } ?>
                </select>
                <input type="hidden" name="billing_component[0][id]" id="comp_id_0" value="" />
                <label for="comp_qty_0">Quantity</label>
                <input type="number" name="billing_component[0][quantity]" id="comp_qty_0" value="1" placeholder="Quantity" required />
                <label for="comp_cost_0">Cost</label>
                <input type="number" name="billing_component[0][cost]" id="comp_cost_0" value="" class="costValue" step='0.01' placeholder='&#8358; 0.00' />
                <label for="comp_desc_0">Description (Optional)</label>
                <textarea name="billing_component[0][description]" id="comp_desc_0" placeholder="Description (Optional)"></textarea>
                <button type="button" id="add_button_0" data-id="0" class="button button-primary" onclick="add_button(0)"><i class="fas fa-plus-square fa-lg"></i></button>
            </div>
            <div id="other_content">
            </div>

            <?php if (isset($_REQUEST['return'])) { ?>
                <input type="hidden" name="return" id="return" value="<?php echo $_REQUEST['return']."&id=".$data['ref']; ?>" />
            <?php } ?>
            <button name="submit" id="submit" type="submit" class="button button-primary"><i class="fa fa-floppy-o fa-lg"></i><?php echo $tag; ?></button>
            <button name="reset" id="reset" type="reset" class="button"><i class="fa fa-undo fa-lg"></i>Reset</button>
          </form>
        </div>
      </div>
    </div>
    <div id="col-right">
      <div class="col-wrap">
        <h2>All Pending Invoices</h2>
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
              <td class="column-columnname"><a href="<?php echo admin_url('admin.php?page=lh-billing-invoice&edit&id='.$list[$i]['ref']); ?>" title="Manage Invoice"><?php echo invoice::invoiceNumber( $list[$i]['ref'] ); ?></a></td>
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
function getComponent( key ) {
    var locate = jQuery("#component_" + key ).attr("data-id");
    var data = jQuery("#component_" + key ).val();
    var result = data.split("_");
    jQuery("#comp_id_"+locate).val( result[0]);
    jQuery("#comp_cost_"+locate).val( result[1]);
}

function minus_button( key ) {
    jQuery( "#div_" + key ).remove();
}

function add_button( key ) {
    var locate = parseInt(jQuery("#add_button_" + key ).attr("data-id"));
    var next = locate+1;

    var data = '<div class="form-field form-required term-age-wrap" id="div_'+next+'">'
    +'<label for="component_'+next+'">Bill Component</label>'
    +'<select id="component_'+next+'" name="component" data-id="'+next+'" required onchange="getComponent('+next+')">'
    +'<option value="">Select One</option>'
    <?php for ($i = 0; $i < count(billing::$list_component); $i++) { ?>
        +'<option value="<?php echo billing::$list_component[$i]['ref']."_".billing::$list_component[$i]['cost']; ?>"><?php echo billing::$list_component[$i]['title']; ?></option>'
    <?php } ?>
    +'</select>'
    +'<input type="hidden" name="billing_component['+next+'][id]" id="comp_id_'+next+'" value="" />'
    +'<label for="comp_qty_'+next+'">Quantity</label>'
    +'<input type="number" name="billing_component['+next+'][quantity]" id="comp_qty_'+next+'" value="1" placeholder="Quantity" required />'
    +'<label for="comp_cost_'+next+'">Cost</label>'
    +'<input type="number" name="billing_component['+next+'][cost]" id="comp_cost_'+next+'" value="" class="costValue" step="0.01" placeholder="&#8358; 0.00" />'
    +'<label for="comp_desc_'+next+'">Description (Optional)</label>'
    +'<textarea name="billing_component['+next+'][description]" id="comp_desc_'+next+'" placeholder="Description (Optional)"></textarea>'
    +'<button type="button" id="add_button_'+next+'" data-id="'+next+'" class="button button-primary" onclick="add_button('+next+')"><i class="fas fa-plus-square fa-lg"></i></button>'
    +'<button type="button" id="minus_button_'+next+'" data-id="'+next+'" class="button button-primary" onclick="minus_button('+next+')"><i class="fas fa-minus-square fa-lg"></i></button>'
    +'</div>'

    jQuery('#other_content').append( data );
}

jQuery(function ($) {
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
            $('#patient_id').val( ui.item.id );
        }
    });

} );
</script>
