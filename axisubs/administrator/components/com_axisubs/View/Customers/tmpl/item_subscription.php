<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
 
defined('_JEXEC') or die();
use Flycart\Axisubs\Admin\Helper\Axisubs;

$this->current_customer = $this->item->customer ;
$curr = Axisubs::currency();
$status_helper = Axisubs::status();
?>
                            <div class="row">
                                <div class="col-md-6 ">
                                        <div class="text-right padbot">
                                            <?php echo JText::_('COM_AXISUBS_TITLE_SUBSCRIPTIONS_ID');?>
                                        </div>
                                        <div class="text-right padbot">
                                            <?php echo JText::_('COM_AXISUBS_SUBSCRIPTION_PLAN');?>
                                        </div>
                                        <div class="text-right padbot">
                                            <?php echo JText::_('COM_AXISUBS_SUBSCRIPTION_PLAN_AMOUNT');?>
                                        </div>
                                        <div class="text-right padbot">
                                            <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TRAIL_STARTS_ON');?> 
                                        </div>
                                        <div class="text-right padbot">
                                            <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TRAIL_ENDS_ON');?> 
                                        </div>
                                        <div class="text-right padbot">
                                            <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_CURRENT_TERM_STARTS_ON');?> 
                                        </div>
                                        <div class="text-right padbot">
                                            <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_CURRENT_TERM_ENDS_ON');?> 
                                        </div>
                                    
                                </div>
                                <div class="col-md-6">
                                    <div class="padbot"> 
                                        <?php if($this->item->axisubs_subscription_id==""): 
                                            echo "<br>"; 
                                        else :
                                            echo $this->item->axisubs_subscription_id;
                                        endif ?>    
                                    </div>
                                    <div class="padbot">
                                        <?php if($this->item->name==""): 
                                            echo "<br>"; 
                                        else :
                                            echo $this->item->name;
                                        endif ?> 
                                    </div>
                                    <div class="padbot">
                                        <?php echo $curr->format( $this->item->total, $this->item->currency); ?>
                                    </div>
                                    <div class="padbot">
                                        <?php if($this->item->trial_start==""): 
                                            echo "<br>"; 
                                        else :
                                            echo $this->item->trial_start;
                                        endif ?> 
                                        <?php echo $this->item->trial_start; ?> 
                                    </div>
                                    <div class="padbot"> 
                                        <?php if($this->item->trial_end==""): 
                                            echo "<br>"; 
                                        else :
                                            echo $this->item->trial_end;
                                        endif ?> 
                                        <?php echo $this->item->trial_end; ?>
                                    </div>
                                    <div class="padbot">
                                        <?php if($this->item->current_term_start==""): 
                                            echo "<br>"; 
                                        else :
                                            echo $this->item->current_term_start;
                                        endif ?> 
                                        <?php echo $this->item->current_term_start; ?> 
                                    </div>
                                    <div class="padbot">
                                        <?php if($this->item->current_term_end==""): 
                                            echo "<br>"; 
                                        else :
                                            echo $this->item->current_term_end;
                                        endif ?> 
                                        <?php echo $this->item->current_term_end; ?> 
                                    </div>
                                </div>
                            </div>
