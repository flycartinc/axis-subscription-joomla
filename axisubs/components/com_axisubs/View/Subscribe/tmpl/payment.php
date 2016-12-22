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
<h2> <?php echo JText::_('COM_AXISUBS_PAYMENT'); ?> </h2>
<div class="axisubs-bs3">
    <div class="row">
        <?php
        $countRightModule = JDocumentHtml::getInstance()->countModules('axisubs-subscribeform-sidebar');
        $this->subscription->calculateTotals();
        ?>
        <div class="<?php echo $countRightModule ? 'col-md-8': 'col-md-12'?>">
            <div id="p-main-content">
                <div id="p-order">
                    <!-- <h3 id="p-order-title">
                        <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ORDER_SUMMARY'); ?>
                    </h3> -->
                    <div id="p-order-summary-main-list">
                        <ul class="list-unstyled header-list">
                            <li class="row">
                              <div class="col-xs-8 summary-list">
                                <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ORDER_SUMMARY'); ?>
                              </div>
                              <div class="col-xs-4 text-right">
                                <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ITEM_PRICE'); ?>
                              </div>
                            </li>
                          </ul>
                      </div>
                    <div id="p-order-summary-main-list" class="main-content">
                        <ul class="list-unstyled">
                            <li class="row">
                                <div class="col-xs-8 summary-list">
                                  <div class="list-image">
                                      <?php $image = ($this->subscription->plan->image != '')? $this->subscription->plan->image : 'media/com_axisubs/images/ico_noimage.png';
                                      $image = JUri::base().$image;
                                      ?>
                                      <img src="<?php echo $image; ?>"/>
                                  </div>
                                  <div class="list-content">
                                    <span class="product-title">
                                      <strong>
                                          <?php echo $this->subscription->plan->name; ?>
                                          ( <?php echo Axisubs::currency()->format($this->subscription->plan_price); ?> x <?php echo $this->subscription->plan_quantity; ?>)
                                          </strong>
                                      </span>
                                      <?php echo $this->subscription->plan->description; ?>
                                  </div>
                                </div>
                                <div class="col-xs-4 text-right">
                                     <span class="product_price"><?php echo Axisubs::currency()->format($this->subscription->plan_price); ?></span>
                                     <?php if ($this->subscription->setup_fee > 0): ?>
                                       <div>
                                       <span class="price_lable"><?php echo $this->subscription->plan->name; ?> - <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_SETUP_FEE'); ?>: </span>
                                       <span class="product_price"><?php echo Axisubs::currency()->format($this->subscription->setup_fee); ?></span>
                                     </div>
                                    <?php endif; ?>
                                    <?php if ($this->subscription->tax > 0): ?>
                                      <div>
                                        <?php
                                        foreach ($this->subscription->tax_details as $tax_item){
                                            ?>
                                            <span class="price_lable"><?php echo $tax_item['label']; ?> (<?php echo $tax_item['rate']; ?>%):</span>
                                            <span class="product_price"><?php echo Axisubs::currency()->format($tax_item['price']); ?></span>
                                            <?php
                                        }
                                        ?>
                                      </div>
                                        <div>
                                         <span class="price_lable"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TOTAL_TAX'); ?>: </span>
                                          <span class="product_price"><?php echo Axisubs::currency()->format($this->subscription->tax); ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </li>
                            <?php
                            // To display more price list like discount
                            echo $this->morePriceList;
                            ?>
                        </ul>
                    </div>

                    <div id="p-order-summary-sub-list" class="text-right">
                    </div>
                    <div id="p-order-total" class="row text-right">
                        <div class="col-xs-12 product-total">
                            <b><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TOTAL'); ?>:
                            <?php echo Axisubs::currency()->format($this->subscription->total); ?></b>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-7">
                    <table class="table table-bordered user-info">
                        <tr>
                            <td>
                                <?php echo JText::_('COM_AXISUBS_CUSTOMER_NAME'); ?>
                            </td>
                            <td>
                                <?php echo $this->subscription->subscriptioninfo->billing_first_name; ?>
                                <?php echo $this->subscription->subscriptioninfo->billing_last_name; ?>
                            </td>
                        </tr>
                        <?php if (!empty($this->subscription->subscriptioninfo->billing_company)): ?>
                        <tr>
                            <td>
                                <?php echo JText::_('AXISUBS_ADDRESS_COMPANY_NAME'); ?>
                            </td>
                            <td>
                                <?php echo $this->subscription->subscriptioninfo->billing_company; ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <?php if (!empty($this->subscription->subscriptioninfo->vat_number)): ?>
                            <tr>
                                <td>
                                    <?php echo JText::_('AXISUBS_ADDRESS_TAX_NUMBER'); ?>
                                </td>
                                <td>
                                    <?php echo $this->subscription->subscriptioninfo->vat_number; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td>
                                <?php echo JText::_('COM_AXISUBS_CUSTOMER_EMAIL'); ?>
                            </td>
                            <td>
                                <?php echo $this->subscription->subscriptioninfo->billing_email; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo JText::_('COM_AXISUBS_CUSTOMER_ADDRESS'); ?>
                            </td>
                            <td>
                                <?php echo $this->subscription->subscriptioninfo->billing_address1; ?>
                                <?php echo $this->subscription->subscriptioninfo->billing_address2; ?>,
                                <?php echo $this->subscription->subscriptioninfo->billing_city; ?>, <br>
                                <?php $stateSelected = Select::getZones($this->subscription->subscriptioninfo->billing_country);
                                if(isset($stateSelected[$this->subscription->subscriptioninfo->billing_state])){
                                    echo $stateSelected[$this->subscription->subscriptioninfo->billing_state].', ';
                                } ?>
                                <?php echo Select::decodeCountry($this->subscription->subscriptioninfo->billing_country); ?> <br>
                                <?php echo $this->subscription->subscriptioninfo->billing_zip; ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-5 pull-right payment">
                  <div class="pay-method">
                    <?php echo JText::_('AXISUBS_PAYMENT_METHOD'); ?>:
                    <?php if(JDocumentHtml::getInstance()->countModules('axisubs-subscribe-beforepaymentbutton')){ ?>
                    <div class="row">
                        <?php echo $this->getContainer()->template->loadPosition('axisubs-subscribe-beforepaymentbutton'); ?>
                    </div>
                    <?php } ?>
                    <span class="pay-type"><?php  echo $this->prePaymentForm ; ?></span>
                    <form action="index.php" name="axisubsPaymentForm" id="axisubs-payment-form">
                        <div class="axisubs-payment-fields">

                        </div>
                        <input type="hidden" name="option" value="com_axisubs" >
                        <input type="hidden" name="view" value="Subscribe" >
                        <?php if($this->subscription->plan->plan_type) { ?>
                            <input type="hidden" name="task" value="confirmPayment" >
                        <?php }else { ?>
                            <input type="hidden" name="task" value="confirmFreeSubscription" >
                        <?php } ?>
                        <input type="hidden" name="orderpayment_type" value="<?php echo $this->payment_method; ?>" >
                        <input type="hidden" name="subscription_id" value="<?php echo $this->subscription_id; ?>" >
                        <input type="hidden" name="plan_id" value="<?php echo $this->plan_id; ?>" >
                        <input type="hidden" name="user_id" value="<?php echo $this->user_id; ?>" >
                        <div class="axisubs-pay-button pull-right hide ">
                            <input type="submit" class="btn btn-lg btn-info" value="<?php echo JText::_('AXISUBS_PAY_NOW'); ?>">
                        </div>
                    </form>
                  </div>
                    <div class="col-md-12 pay-button">
                        <?php if($this->subscription->plan->plan_type) { ?>
                        <a href="#" onclick="processPayment()"  class="btn btn-info" ><?php echo JText::_('AXISUBS_PAY_NOW');?></a>
                        <?php } else {
                            ?>
                            <a href="#" onclick="jQuery('#axisubs-payment-form').submit();"  class="btn btn-info" ><?php echo JText::_('AXISUBS_CONFIRM_SUBSCRIBE');?></a>
                            <?php
                        }?>
                    </div>
                    <?php if(JDocumentHtml::getInstance()->countModules('axisubs-subscribe-afterpaymentbutton')){ ?>
                    <div class="row">
                        <?php echo $this->getContainer()->template->loadPosition('axisubs-subscribe-afterpaymentbutton'); ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php if($countRightModule){ ?>
        <div class="col-md-4 hidden-xs">
            <div class="subscribeform-sidebar">
                <?php echo $this->getContainer()->template->loadPosition('axisubs-subscribeform-sidebar'); ?>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
