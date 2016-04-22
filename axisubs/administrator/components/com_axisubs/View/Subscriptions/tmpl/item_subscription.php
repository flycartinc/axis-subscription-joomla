<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
// Protect from unauthorized access
defined('_JEXEC') or die();
//$customer = $this->item->customer ;

use Flycart\Axisubs\Admin\Helper\Axisubs;

$this->current_customer = $this->item->customer ;
$curr = Axisubs::currency();
$status_helper = Axisubs::status();
?>
                            <table class="table borderless">
                                <tr>
                                    <div class="viewcontent-text">
                                        <td class="">
                                            <?php echo JText::_('COM_AXISUBS_TITLE_SUBSCRIPTIONS_ID');?>
                                        </td>
                                        <td>
                                            <?php echo $this->item->axisubs_subscription_id; ?>
                                        </td> 
                                    </div>
                                </tr>
                                <tr>
                                    <td class="">
                                        <?php echo JText::_('COM_AXISUBS_SUBSCRIPTION_PLAN');?>
                                    </td>
                                    <td>
                                        <a href="index.php?option=com_axisubs&view=Plans&id=<?php echo $this->item->plan->axisubs_plan_id; ?>&task=read" class="tu" id="plans.details">
                                            <?php echo  $this->item->plan->name; ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="">
                                        <?php echo JText::_('COM_AXISUBS_SUBSCRIPTION_PLAN_AMOUNT');?>
                                    </td>
                                    <td class="">
                                    <?php echo $curr->format( $this->item->total, $this->item->currency); ?>
                                    </td>
                                </tr>
                                 <tr>
                                    <td class="">
                                        <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TRAIL_STARTS_ON');?>
                                    </td>
                                    <td class="">
                                    <?php echo $this->item->trial_start; ?>
                                    </td>
                                </tr>
                                 <tr>
                                    <td class="">
                                        <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TRAIL_ENDS_ON');?>
                                    </td>
                                    <td class="">
                                    <?php echo $this->item->trial_end; ?>
                                    </td>
                                </tr>
                                 <tr>
                                    <td class="">
                                        <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_CURRENT_TERM_STARTS_ON');?>
                                    </td>
                                    <td class="">
                                    <?php echo $this->item->current_term_start; ?>
                                    </td>
                                </tr>
                                 <tr>
                                    <td class="">
                                        <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_CURRENT_TERM_ENDS_ON');?>
                                    </td>
                                    <td class="">
                                    <?php echo $this->item->current_term_end; ?>
                                    </td>
                                </tr>
                            </table>