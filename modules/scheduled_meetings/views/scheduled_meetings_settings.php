<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php
$newsticker_enabled     = get_option('newsticker_enabled');
$newsticker_content     = get_option('newsticker_content');
$newsticker_background_color = get_option('newsticker_background_color');
?>
<div role="tabpanel" class="tab-pane" id="newsticker_info">
    <div class="alert alert-info">
    <?php echo _l('settings_newsticker'); ?>
    </div>
    
 <div class="form-group col-sm-3"> 
            <label for="newsticker" class="control-label clearfix"><p id="" class="" style="font-size:18px"><?php echo _l('newsticker_enable'); ?></p></label> 
            <div class="radio radio-primary radio-inline">
                <input type="radio" id="y_newsticker_enabled" name="settings[newsticker_enabled]" value="1"<?php if ('1' == $newsticker_enabled) { echo ' checked';} ?>>
                <label for="y_newsticker_enabled"><?php echo _l('settings_yes'); ?></label>
            </div> 
            <div class="radio radio-primary radio-inline">
                <input type="radio" id="n_newsticker_enabled" name="settings[newsticker_enabled]" value="0" <?php if ('0' == $newsticker_enabled) { echo ' checked';} ?>>
                <label for="n_newsticker_enabled">
                    <?php echo _l('settings_no'); ?>
                </label>
            </div>
</div>   
    
<div class="clearfix"></div>
<hr />
<div class="col-md-12">	
 <?php echo render_textarea('settings[newsticker_content]', 'newsticker_content', clear_textarea_breaks(get_option('newsticker_content')), ['rows' => 7, 'style' => ''], [], '', 'tinymce'); ?>
 
</div>

<div class="clearfix"></div>
<hr />

<div class="form-group col-md-4" app-field-wrapper="newsticker_background_color">
<label for="newsticker" class="control-label clearfix"><p id="" class="" style="font-size:18px"><?php echo _l('newsticker_background_color'); ?></p></label>
<select name="settings[newsticker_background_color]"  id="newsticker_background_color" class="form-control">
  <option value="blue" style="color:white;background-color:#1D4ED8;" <?php if ('blue' == $newsticker_background_color) { echo ' selected';} ?>>Blue</option>
  <option value="black" style="color:white;background-color:#000000;" <?php if ('black' == $newsticker_background_color) { echo ' selected';} ?>> Black</option>
  <option value="purple" style="color:white;background-color:#7220ad;" <?php if ('purple' == $newsticker_background_color) { echo ' selected';} ?>>Purple</option>
  <option value="orange" style="color:white;background-color:#fc5121;" <?php if ('orange' == $newsticker_background_color) { echo ' selected';} ?>>Orange</option>
  <option value="red" style="color:white;background-color:#e0394a;" <?php if ('red' == $newsticker_background_color) { echo ' selected';} ?>>Red</option>
  <option value="green" style="color:white;background-color:#44910c;" <?php if ('green' == $newsticker_background_color) { echo ' selected';} ?>>Green</option>
  <option value="brown" style="color:white;background-color:#4e1f17;" <?php if ('brown' == $newsticker_background_color) { echo ' selected';} ?>>Brown</option>
  <option value="pink" style="color:white;background-color:#fa3ffe;" <?php if ('pink' == $newsticker_background_color) { echo ' selected';} ?>>Pink</option>
  <option value="navyblue" style="color:white;background-color:#111284;" <?php if ('navyblue' == $newsticker_background_color) { echo ' selected';} ?>>Navy Blue</option>
  <option value="gray" style="color:white;background-color:#5A5A5A;" <?php if ('gray' == $newsticker_background_color) { echo ' selected';} ?>>Gray</option>
  <option value="maroon" style="color:white;background-color:#61123e;" <?php if ('maroon' == $newsticker_background_color) { echo ' selected';} ?>>Maroon</option>
</select></p>
</div>

<div class="clearfix"></div>
<hr />