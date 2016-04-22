<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
                <!--Card box-->
                <div class="container-fluid">
                    <div class="row">
                       <div class= "col-xs-12 col-sm-6 col-md-4 col-lg-4">
                            <div class="circleborder">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div id="test-circle1" ></div>
                                    </div>
                                    <div class="col-md-6 p-t-40">
                                        <h3 class="text-success counter"><?php echo str_replace(array('[', ']'), '', htmlspecialchars(json_encode($display_data->today_sale_amount), ENT_NOQUOTES));   ?></h3>
                                        <p class="text-muted text-nowrap"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TOTAL_SALES_TODAY');?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                        <div class="circleborder">
                            <div class="row">
                                <div class="col-md-6">
                                    <div id="test-circle2" ></div>
                                </div>
                                <div class="col-md-6 p-t-40">
                                    <h3 class="text-primary">$ <span class="counter"><?php echo str_replace(array('[', ']'), '', htmlspecialchars(json_encode($display_data->total_sale_amount), ENT_NOQUOTES));   ?></span></h3>
                                    <p class="text-muted text-nowrap"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TOTAL_EARNING');?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                        <div class="circleborder">
                            <div class="row">
                                <div class="col-md-6">
                                    <div id="test-circle3" ></div>
                                </div>
                                <div class="col-md-6 p-t-40">
                                    <h3 class="text-danger counter"><?php echo str_replace(array('[', ']'), '', htmlspecialchars(json_encode($display_data->total_pending), ENT_NOQUOTES));   ?></h3>
                                    <p class="text-muted text-nowrap"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_PENDING_ORDERS');?></p>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
                <!-- end of card box -->
             
                <!-- card box 2-->
               <div class="row">
                    <div class="col-lg-6">
                        <div class="card-box">
                            <h4 class="text-dark  text-left header-title m-t-0 m-b-30"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TOTAL_REVENUE');?></h4>

                            <div class="widget-chart text-center">
                                <div id="sparkline1"><canvas height="165" width="348" style="display: inline-block; width: 348px; height: 165px; vertical-align: top;"></canvas>
                                </div>
                                <ul class="list-inline m-t-15">
                                    <li>
                                        <h5 class="text-muted m-t-20"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_LAST_WEEK');?> </h5>
                                        <h4 class="m-b-0"><?php echo str_replace(array('[', ']'), '', htmlspecialchars(json_encode($display_data->week_sale_amount), ENT_NOQUOTES));   ?></h4>
                                    </li>
                                    <li>
                                        <h5 class="text-muted m-t-20"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_LAST_MONTH');?> </h5>
                                        <h4 class="m-b-0"><?php echo str_replace(array('[', ']'), '', htmlspecialchars(json_encode($display_data->month_sale_amount), ENT_NOQUOTES));   ?></h4>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card-box">
                            <h4 class="text-dark text-left header-title m-t-0 m-b-30"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_WEEKLY_SALES_REPORT');?>  </h4>

                            <div class="widget-chart text-center">
                                <div id="sparkline3"><canvas height="165" width="165" style="display: inline-block; width: 165px; height: 165px; vertical-align: top;"></canvas>
                                </div>
                                <ul class="list-inline m-t-15">
                                    <li>
                                        <h5 class="text-muted m-t-20"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_LAST_WEEK');?></h5>
                                        <h4 class="m-b-0"><?php echo str_replace(array('[', ']'), '', htmlspecialchars(json_encode($display_data->week_sale_amount), ENT_NOQUOTES));  ?></h4>
                                    </li>
                                    <li>
                                        <h5 class="text-muted m-t-20"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_LAST_MONTH');?></h5>
                                        <h4 class="m-b-0"><?php echo str_replace(array('[', ']'), '', htmlspecialchars(json_encode($display_data->month_sale_amount), ENT_NOQUOTES));   ?></h4>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- end of card box2 -->
                </div>
                <!-- end table1-->
            </div>
        </div>
    </div>


<!-- jQuery  for sparkline-->
 <script>
( function ($){
$( document ).ready(function() {

    var DrawSparkline = function() {
        $('#sparkline1').sparkline(<?php echo json_encode($display_data->firstweekdatearray); ?>, {
            type: 'line',
            width: $('#sparkline1').width(),
            height: '165',
            chartRangeMax: 10,
            lineColor: '#3bafda',
            fillColor: 'rgba(59,175,218,0.3)',
            highlightLineColor: 'rgba(0,0,0,.1)',
            highlightSpotColor: 'rgba(0,0,0,.2)',
        });

        $('#sparkline1').sparkline(<?php echo json_encode($display_data->secondweekdatearray); ?>, {
            type: 'line',
            width: $('#sparkline1').width(),
            height: '165',
            chartRangeMax: 10,
            lineColor: '#00b19d',
            fillColor: 'rgba(0, 177, 157, 0.3)',
            composite: true,
            highlightLineColor: 'rgba(0,0,0,.1)',
            highlightSpotColor: 'rgba(0,0,0,.2)',
        });

        $('#sparkline2').sparkline([3, 6, 7, 8, 6, 4, 7, 10, 12, 7, 4, 9, 12, 13, 11, 12], {
            type: 'bar',
            height: '165',
            barWidth: '10',
            barSpacing: '3',
            barColor: '#3bafda'
        });

        $('#sparkline3').sparkline(<?php echo json_encode($display_data->amount) ?>, {
            type: 'pie',
            width: '165',
            height: '165',
            sliceColors: ['#dcdcdc', '#3bafda', '#333333', '#00b19d','#BF00FF']
        });

    };

    DrawSparkline();
    var resizeChart;
    $(window).resize(function(e) {
        clearTimeout(resizeChart);
        resizeChart = setTimeout(function() {
            DrawSparkline();
        }, 300);
    });
});
})(jQuery.noConflict())
</script>
 <script>
( function ($){
    $( document ).ready(function() {
            $("#test-circle").circliful
            ({
                    foregroundColor: "#3498DB",
                    backgroundColor: "#eee",
                    fontColor: '#aaa',
                    percent:10
            });
            $("#test-circle1").circliful
            ({
                    foregroundColor: "#3498DB",
                    backgroundColor: "#eee",
                    fontColor: '#aaa',
                    percent:25,
                   
            });
            $("#test-circle2").circliful
            ({
                    foregroundColor: "#3498DB",
                    backgroundColor: "#eee",
                    fontColor: '#aaa',
                    percent:50
            });
            $("#test-circle3").circliful
            ({
                    foregroundColor: "#3498DB",
                    backgroundColor: "#eee",
                    fontColor: '#aaa',
                    percent:75
            });
    });
})(jQuery.noConflict())
</script>
