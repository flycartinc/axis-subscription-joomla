<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
defined('_JEXEC') or die;
$sidebar = JHtmlSidebar::render();
?>
<div class="axisubs-bs3">
    <?php if(!empty( $sidebar )): ?>
    <div id="j-sidebar-container">
      <?php echo $sidebar ; ?>
    </div>
    <?php endif;?>
    <div id="j-main-container" class="col-md-12 ">
        <h2>Reports</h2>
    </div>
    <!--left side bar-->
    <div id="axisubs-settings-nav">
	    <nav class="axisubs-left-nav">
		    <div class="title">
		        <span></span>
		        REPORTS
		    </div>
		    <ul class="settings-list">
		        
		        <li class="settings-item inactive"><a href="#"><span class="first">At a glance</span></a></li>
		        
		        <li class="settings-item active"><a href="#"><span class="">Subscription Count</span></a></li>
		        
		        <li class="settings-item inactive"><a href="#"><span class="">Monthly Plan Revenue</span></a></li>
		        
		        <li class="settings-item inactive"><a href="#"><span class="">Monthly Addon Revenue</span></a></li>
		        
		        <li class="settings-item inactive"><a href="#"><span class="">ARPU</span></a></li>
		        
		        <li class="settings-item inactive"><a href="#"><span class="">Renewal Summary</span></a></li>
		        
		        <li class="settings-item inactive"><a href="#"><span class="">Transaction Summary</span></a></li>
		        
		        <li class="settings-item inactive"><a href="#"><span class="">Transaction Trend</span></a></li>
		        
		        <li class="settings-item inactive"><a href="#"><span class="">Lost Opportunities</span></a></li>
		        
		        <li class="settings-item inactive"><a href="#"><span class="">Refund Summary</span></a></li>
		        
		        <li class="settings-item inactive"><a href="#"><span class="">Export Data</span></a></li>
		        
		        <li class="settings-item inactive"><a href="#"><span class="">Exported List</span></a></li>
		        
		        <li class="settings-item inactive"><a href="#"><span class="">ShipStation Export</span></a></li>
		        
		    </ul>
		</nav>
	</div>
	<!-- end of left side bar-->
	<!--subscription count tab-->
	<div class="axisubs-detail-container">
    	<div class="axisubs-db-report">
	        <h1>
	            Subscription Count
	        </h1>
	        
			<table class="axisubs-table axisubs-clean-table table-hover">
			    <tbody>
				    <tr>
				        <th>Plan</th>
				        <th class="axisubs-reportValue">Subscription Count</th>
				    </tr>
				    
				    
				    <tr>
				        <td style="text-align: left;">
				            <em>
				                <a href="#">bronze</a>
				            </em>
				        </td>
				        <td class="axisubs-reportValue">
				            <span>6</span>
				        </td>
				    </tr>
				    
				    <tr>
				        <td style="text-align: left;">
				            <em>
				                <a href="#">Basic</a>
				            </em>
				        </td>
				        <td class="axisubs-reportValue">
				            <span>4</span>
				        </td>
				    </tr>
				    
				    <tr>
				        <td style="text-align: left;">
				            <em>
				                <a href="#">Enterprise</a>
				            </em>
				        </td>
				        <td class="axisubs-reportValue">
				            <span>2</span>
				        </td>
				    </tr>
				    
				    <tr>
				        <td style="text-align: left;">
				            <em>
				                <a href="#">Professional</a>
				            </em>
				        </td>
				        <td class="axisubs-reportValue">
				            <span>2</span>
				        </td>
				    </tr>
				    
				    <tr>
				        <td style="text-align: left;">
				            <em>
				                <a href="#">Enterprise Half Yearly</a>
				            </em>
				        </td>
				        <td class="axisubs-reportValue">
				            <span>2</span>
				        </td>
				    </tr>
				    
				    <tr>
				        <td style="text-align: left;">
				            <em>
				                <a href="#">3 months</a>
				            </em>
				        </td>
				        <td class="axisubs-reportValue">
				            <span>0</span>
				        </td>
				    </tr>
				    
				    <tr>
				        <td style="text-align: left;">
				            <em>
				                <a href="#">silver</a>
				            </em>
				        </td>
				        <td class="axisubs-reportValue">
				            <span>0</span>
				        </td>
				    </tr>
				    
				    <tr>
				        <td style="text-align: left;">
				            <em>
				                <a href="#">platinum</a>
				            </em>
				        </td>
				        <td class="axisubs-reportValue">
				            <span>0</span>
				        </td>
				    </tr>
				</tbody>
			</table>
		</div>
	</div>
	<!--end of subscription count tab-->
	<!--monthly plan review tab-->
	<div class="axisubs-detail-container">
    	<div class="axisubs-db-report">
	        <h1>Monthly Report Card for Plans
	        <span class="axisubs-more-filters axisubs-pull-right">Show filters</span>
	        </h1>        
			<table class="axisubs-table axisubs-clean-table table-hover">
    			<tbody>
    				<tr>
				        <th>
				        </th>
				        <th class="axisubs-reportValue">This Month</th>
				        <th class="axisubs-reportValue">Last Month</th>
				    </tr>
				    <tr>
				        <td>
				            <em>Pro</em>
				        </td>

				        <td class="axisubs-reportValue">
				            <span>$0</span>
				        </td>
				        <td class="axisubs-reportValue">
				            <span>$0</span>
				        </td>
				    </tr>				    
				    <tr>
				        <td>
				            <em>Basic</em>
				        </td>

				        <td class="axisubs-reportValue">
				            <span>$0</span>
				        </td>
				        <td class="axisubs-reportValue">
				            <span>$0</span>
				        </td>
				    </tr>				    
				    <tr>
				        <td>
				            <em>bronze</em>
				        </td>

				        <td class="axisubs-reportValue">
				            <span>$0</span>
				        </td>
				        <td class="axisubs-reportValue">
				            <span>$0</span>
				        </td>
				    </tr>				    
				    <tr>
				        <td>
				            <em>Enterprise</em>
				        </td>

				        <td class="axisubs-reportValue">
				            <span>$0</span>
				        </td>
				        <td class="axisubs-reportValue">
				            <span>$0</span>
				        </td>
				    </tr>				    
				    <tr>
				        <td>
				            <em>Enterprise half yearly plan</em>
				        </td>

				        <td class="axisubs-reportValue">
				            <span>$0</span>
				        </td>
				        <td class="axisubs-reportValue">
				            <span>$0</span>
				        </td>
				    </tr>				    
				    <tr>
				        <td>
				            <em>platinum</em>
				        </td>

				        <td class="axisubs-reportValue">
				            <span>$0</span>
				        </td>
				        <td class="axisubs-reportValue">
				            <span>$0</span>
				        </td>
				    </tr>				    
				    <tr>
				        <td>
				            <em>Professional</em>
				        </td>

				        <td class="axisubs-reportValue">
				            <span>$0</span>
				        </td>
				        <td class="axisubs-reportValue">
				            <span>$0</span>
				        </td>
				    </tr>			    
				    <tr>
				        <td>
				            <em>silver</em>
				        </td>

				        <td class="axisubs-reportValue">
				            <span>$0</span>
				        </td>
				        <td class="axisubs-reportValue">
				            <span>$0</span>
				        </td>
				    </tr>
    			</tbody>
    		</table>
    	</div>
    </div>
    





	<!--end of monthly plan review tab-->
	















</div>