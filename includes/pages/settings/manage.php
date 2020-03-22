<div class="wrap">
  <h1 class="wp-heading-inline">Patients' Record</h1>
  <!-- <form class="search-form wp-clearfix" method="get">
    <p class="search-box">
      <label class="screen-reader-text" for="tag-search-input">Search Appointments:</label>
      <input type="search" id="tag-search-input" name="s" value="" />
      <input type="submit" id="search-submit" class="button" value="Search Appointments"  />
    </p>
  </form> -->
  <div id="col-container" class="wp-clearfix">
    <div id="col-left">
      <div class="col-wrap">
        <div class="form-wrap">
            <h2>Settings</h2>
          <?php if (isset(self::$message)): ?><div class="updated"><p><?php echo self::$message; ?></p></div><?php endif; ?>
          <?php if (isset(self::$error_message)): ?><div class="error"><p><?php echo self::$error_message; ?></p></div><?php endif; ?>
          <form id="form2" name="form2" method="post" action="">
            <div class="form-field form-required term-consultationFee-wrap">
              <label for="consultationFee"> Consultation Fee Component</label>
              <select id="consultationFee" name="consultationFee" required>
                <option value="">Select One</option>
                <?php for ($i = 0; $i < count(billing::$list); $i++) { ?>
                <option <?php if (billing::$list[$i]['cost'] == self::$consultationFee) { ?>selected <?php } ?>value="<?php echo billing::$list[$i]['cost']; ?>"><?php echo billing::$list[$i]['title']; ?></option>
                <?php } ?>
              </select>
            </div>
            <button name="submit" id="submit" type="submit" class="button button-primary"><i class="fa fa-calendar-check fa-lg"></i>&nbsp;Update Settings</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
</script>
