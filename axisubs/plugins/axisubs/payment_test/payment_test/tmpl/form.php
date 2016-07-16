<?php
/**
 * @package   Axisubs - Test Payment
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

defined('_JEXEC') or die('Restricted access'); ?>

<p><?php echo JText::_($vars->onselection_text); ?></p>

<div id="p-card-fields">
    <div class="row">
        <div class="panel-body">

            <div class="row">
                <div class="col-xs-8">
                    <div class="form-group">
                        <label for="payment[cardholder]">
                            <?php echo JText::_( 'COM_AXISUBS_SUBSCRIBE_CARD_HOLDER_NAME'); ?>
                        </label>
                        <input type="text" class="form-control" name="payment[cardholder]" minlength="2" required/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="payment[cardnum]">
                            <?php echo JText::_( 'COM_AXISUBS_SUBSCRIBE_CARD_NUMBER'); ?>
                        </label>
                        <div class="input-group">
                            <input type="tel" class="form-control" name="payment[cardnum]" placeholder="Valid Card Number" autocomplete="cc-number" required autofocus />
                            <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-7 col-md-7">
                        <div class="form-group">
                            <label for="month">
                                <?php echo JText::_( 'COM_AXISUBS_SUBSCRIBE_EXIPRATION_DATE');?>
                            </label>
                            <br />
                            <select name="payment[month]" class="input-small">
                                <option value=""><?php echo JText::_('AXISUBS_MONTH'); ?></option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                            </select>
                            <select name="payment[year]" class="input-small">
                            <option value=""><?php echo JText::_('AXISUBS_YEAR'); ?></option>
                                <?php
                                $two_digit_year = date('y');
                                $four_digit_year = date('Y');
                                ?>
                                <?php for($i=$two_digit_year;$i<$two_digit_year+50;$i++) {?>
                                    <option value="<?php echo $four_digit_year;?>"><?php echo $four_digit_year;?></option>
                                <?php
                                $four_digit_year++;
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-5 col-md-5 pull-right">
                        <div class="form-group">
                            <label for="payment[cardcvv]">
                                <?php echo JText::_( 'COM_AXISUBS_SUBSCRIBE_CV_CODE'); ?>
                            </label>
                            <input type="tel" class="form-control" name="payment[cardcvv]" placeholder="CVV" required />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>