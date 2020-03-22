<?php
$list = self::$list; ?>
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
              <a href="<?php echo admin_url('admin.php?page=lh-manage-clinic&appointment&id='.$list[$i]['ref']); ?>" title="Open Records <?php echo $list[$i]['names']; ?>"><i class="fas fa-clinic-medical"></i></a>&nbsp;
              <a href="<?php echo admin_url('admin.php?page=lh-manage-appointments&open&id='.$list[$i]['ref']); ?>" title="Manage Appointment"><i class="fas fa-edit"></i></a>
            </td>
        </tr>
        <?php $count++;
        } ?>
        </tbody>
    </table>
    </div>
  </div>
</div>

<script>
jQuery(function ($) {
    $('#datatable_list').DataTable();
} );
</script>
