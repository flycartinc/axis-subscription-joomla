<?php
/**
 * @package   Module - Axisubs Line chart
 * @copyright Copyright (c)2016-2020 Ashlin / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$start_date_week = $lastWeekData->startDate;
$end_date_week = $lastWeekData->endDate;
$start_date_month = $lastMonthData->startDate;
$end_date_month = $lastMonthData->endDate;
$doc = JFactory::getDocument();
$doc->addScript(JUri::root().'media/mod_axisubs_linechart/js/chart.js');
?>
<script type="text/javascript">
    if(axisubs_linechart_js == undefined) {
        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(drawChart_<?php echo $module->id; ?>);
        var axisubs_linechart_js = 1;
    } else {
        google.charts.setOnLoadCallback(drawChart_<?php echo $module->id; ?>);
    }

    function drawChart_<?php echo $module->id; ?>() {
        var data = google.visualization.arrayToDataTable([
            /*['Year', 'Sales', 'Expenses'],
            ['2004',  1000,      400],
            ['2005',  1170,      460],
            ['2006',  660,       1120],
            ['2007',  1030,      540]*/
            <?php
            $date = $start_date_week;
            $firstRow = "['Date'";
            foreach ($lastWeekData->plans as $plan){
                $firstRow .= ", '".$plan->plan_name."'";
            }
            $firstRow .= ']';
            $rows = '';
            while (strtotime($date) <= strtotime($end_date_week)) {
                $rows .= ",['".$date."'";
                $rows .= "";
                foreach ($lastWeekData->plans as $plan){
                    if(isset($plan->subscriptions[$date])){
                        $rows .= ", ".$plan->subscriptions[$date]->count."";
                    } else {
                        $rows .= ", 0";
                    }
                }
                $rows .= ']';
                $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
            }
            echo $firstRow.$rows;
            ?>
        ]);

        var options = {
            title: '<?php echo JText::_('MOD_AXISUBS_LINECHART_WEEKLY_REPORT_TITLE'); ?>',
            curveType: 'function',
            legend: { position: 'bottom' },
            pointSize: 5
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart_week_<?php echo $module->id; ?>'));

        chart.draw(data, options);

        /*************************** For Monthly Report **********************************************/
        var dataMonth = google.visualization.arrayToDataTable([
            /*['Year', 'Sales', 'Expenses'],
             ['2004',  1000,      400],
             ['2005',  1170,      460],
             ['2006',  660,       1120],
             ['2007',  1030,      540]*/
            <?php
            $date = $start_date_month;
            $firstRow = "['Date'";
            foreach ($lastMonthData->plans as $plan){
                $firstRow .= ", '".$plan->plan_name."'";
            }
            $firstRow .= ']';
            $rows = '';
            while (strtotime($date) <= strtotime($end_date_month)) {
                $rows .= ",['".$date."'";
                $rows .= "";
                foreach ($lastMonthData->plans as $plan){
                    if(isset($plan->subscriptions[$date])){
                        $rows .= ", ".$plan->subscriptions[$date]->count."";
                    } else {
                        $rows .= ", 0";
                    }
                }
                $rows .= ']';
                $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
            }
            echo $firstRow.$rows;
            ?>
        ]);

        var optionsMonth = {
            title: '<?php echo JText::_('MOD_AXISUBS_LINECHART_MONTHLY_REPORT_TITLE'); ?>',
            curveType: 'function',
            legend: { position: 'bottom' },
            pointSize: 5
        };

        var chartMonth = new google.visualization.LineChart(document.getElementById('curve_chart_month_<?php echo $module->id; ?>'));

        chartMonth.draw(dataMonth, optionsMonth);
        jQuery('#tab1_<?php echo $module->id; ?>').removeClass('active');
    }
</script>

<div style="clear:both;"></div>
<div class="tabbable">
    <div class="settings-icon">
        <a href="index.php?option=com_modules&view=module&layout=edit&id=<?php echo $module->id; ?>"><span class="icon-cog"></span></a>
    </div>
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#tab0_<?php echo $module->id; ?>" data-toggle="tab" >
                <?php echo JText::_('MOD_AXISUBS_LINECHART_WEEKLY_REPORT_TITLE'); ?>
            </a>
        </li>
        <li >
            <a href="#tab1_<?php echo $module->id; ?>" data-toggle="tab">
                <?php echo JText::_('MOD_AXISUBS_LINECHART_MONTHLY_REPORT_TITLE'); ?>
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab0_<?php echo $module->id; ?>">
            <div id="curve_chart_week_<?php echo $module->id; ?>" style="width: 100%; height: 500px"></div>
        </div>
        <div class="tab-pane active" id="tab1_<?php echo $module->id; ?>">
            <div id="curve_chart_month_<?php echo $module->id; ?>" style="width: 100%; height: 500px"></div>
        </div>
    </div>
</div>

