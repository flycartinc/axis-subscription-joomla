<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
defined('_JEXEC') or die();
use Flycart\Axisubs\Admin\Helper\Select;
use Flycart\Axisubs\Admin\Helper\Axisubs;
?>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="billing_address[first_name]">
				<?php echo JText::_('COM_AXISUBS_CUSTOMER_FIRST_NAME'); ?>
				<span>*</span>
            </label>
            <span id="billing_address[first_name].err" class="text-danger pull-right">&nbsp;</span>
            <input id="billing_address[first_name]" name="billing_address[first_name]" class="form-control" value="<?php echo $this->customer->first_name; ?>" validate="true" type="text" minlength="2"  required>

        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="billing_address[last_name]">
				<?php echo JText::_('COM_AXISUBS_CUSTOMER_LAST_NAME'); ?>
            </label>
            <span id="billing_address[last_name].err" class="text-danger pull-right">&nbsp;</span>
            <input id="billing_address[last_name]" name="billing_address[last_name]" class="form-control" minlength="1"  value="<?php echo $this->customer->last_name; ?>" validate="true" type="text" required>

        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="billing_address[company]">
                <?php echo JText::_('COM_AXISUBS_CUSTOMER_COMPANY'); ?>
                <span>*</span>
            </label>
            <span id="billing_address[company].err" class="text-danger pull-right">&nbsp;</span>
            <input id="billing_address[company]" name="billing_address[company]" class="form-control" value="<?php echo $this->customer->company; ?>" validate="true" type="text" minlength="2"  required>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="billing_address[vat_number]">
                <?php echo JText::_('COM_AXISUBS_CUSTOMER_VATNUMBER'); ?>
            </label>
            <span id="billing_address[vat_number].err" class="text-danger pull-right">&nbsp;</span>
            <input id="billing_address[vat_number]" name="billing_address[vat_number]" class="form-control" minlength="1"  value="<?php echo $this->customer->vat_number; ?>" validate="true" type="text" required>

        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">

            <label for="billing_address[address1]">
				<?php echo JText::_('COM_AXISUBS_USERS_FIELD_ADDRESS1'); ?>
                <span>*</span>
            </label>
            <span id="billing_address[address1].err" class="text-danger pull-right">&nbsp;</span>
            <input id="billing_address[address1]" name="billing_address[address1]" class="form-control"  value="<?php echo $this->customer->address1; ?>" validate="true" type="text" required>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="billing_address[address2]">
				<?php echo JText::_('COM_AXISUBS_USERS_FIELD_ADDRESS2'); ?>												
            </label>
            <span id="billing_address[address2].err" class="text-danger pull-right">&nbsp;</span>
            <input id="billing_address[address2]" name="billing_address[address2]" class="form-control" value="<?php echo $this->customer->address2; ?>" validate="true" type="text" required>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="billing_address[city]"><?php echo JText::_('COM_AXISUBS_USERS_FIELD_CITY'); ?>
                <span>*</span>
            </label>
            <span id="billing_address[city].err" class="text-danger pull-right">&nbsp;</span>
            <input id="billing_address[city]" name="billing_address[city]" class="form-control" value="<?php echo $this->customer->city; ?>"  validate="true" data-p-checkout="gen-order" type="text" required>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="billing_address[zip]"><?php echo JText::_('COM_AXISUBS_USERS_FIELD_ZIP'); ?>
                <span>*</span>
            </label>
            <span id="billing_address[zip].err" class="text-danger pull-right">&nbsp;</span>
            <input id="billing_address[zip]" name="billing_address[zip]" class="form-control" value="<?php echo $this->customer->zip; ?>" validate="true" data-p-checkout="gen-order" type="text" required>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group subsformwidth">
            <label for="billing_address[country]"><?php echo JText::_('COM_AXISUBS_USERS_FIELD_COUNTRY'); ?>
                <span>*</span>
            </label>
            <span id="billing_address[country].err" class="text-danger pull-right">&nbsp;</span>
            <?php echo Select::countries($this->customer->country,'billing_address[country]'); ?>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group subsformwidth">
            <label for="billing.region"><?php echo JText::_('COM_AXISUBS_USERS_FIELD_STATE'); ?>
                <span>*</span>
            </label>
            <span id="billing.region.err" class="text-danger pull-right">&nbsp;</span>
            <span id="billing_address[state].err" class="text-danger pull-right">&nbsp;</span>
            <span style="display: none;" id="billing_address[state_code].err" class="text-danger pull-right">&nbsp;</span>
          <span id="billing_address[country].err" class="text-danger pull-right">&nbsp;</span>
              <?php echo Select::states($this->customer->state, 'billing_address[state]'); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="billing_address[phone]"><?php echo JText::_('COM_AXISUBS_CUSTOMER_PHONE'); ?>                                             
                <span>*</span>
            </label>
            <span id="billing_address[phone].err" class="text-danger pull-right">&nbsp;</span>
            <input id="billing_address[phone]" name="billing_address[phone]" class="form-control" value="<?php echo $this->customer->phone; ?>"  validate="true" type="text" required>
        </div>
    </div>
</div>

<script>
    
(function($) {

    jQuery('#billing_addresscountry').change(function() {
         // get the zone / sub divisions and load
        jQuery.ajax({
            url: 'index.php?option=com_axisubs&view=Customers&task=getState&country=' + jQuery(this).val(),
            dataType: 'json',
            async   : false,
            success: function(json) {

                html = '<option value=""><?php echo JText::_('JSELECT'); ?></option>';

                jQuery.each(json, function(k, v) {
                    html += '<option value="' + k + '"';
                    if (k == '<?php echo $this->customer->state; ?>') {
                        html += ' selected="selected"';
                    }
                    html += '>' + v + '</option>';
                });

                jQuery('#billing_addressstate').html(html);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    jQuery('#billing_addresscountry').trigger('change');

    $('#customer\\[password1\\], #customer\\[password2\\]').on('change', function () {
        var password   = $('#customer\\[password1\\]'),
            repassword = $('#customer\\[password2\\]'),
            both       = password.add(repassword).removeClass('has-success has-error');
        if (password.val() != repassword.val()) {
            both.addClass('has-error');
        }
    });

})(axisubs.jQuery);

</script>