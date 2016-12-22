/**
 * Created by aron-destiny on 20/7/16.
 */

if(typeof(axisubs) == 'undefined') {
    var axisubs = {};
}
if(typeof(axisubs.jQuery) == 'undefined') {
    axisubs.jQuery = jQuery.noConflict();
}
// for Plan type Radio button
function planRadio(val){
    (function($) {
        if(val == '1'){
            $("#period, #trail").show('slow');
        } else {
            $("#period, #trail").hide('slow ');
        }
    })(axisubs.jQuery);
}

// for Recurring Radio button
function recurringRadio(val){
    (function($) {
        if(val == '1'){
            $("#billing_cycles").parent().parent('.control-group').show('slow');
        } else {
            $("#billing_cycles").parent().parent('.control-group').hide('slow ');
        }
    })(axisubs.jQuery);
}

//for Onlyonce Radio
function onlyOnceRadio(val){
    (function($) {
        recurringRadio('0');
        if(val == '0'){
            $("#recurring").parent().parent('.control-group').show('slow');
            //For Recurring radio button default
            var recurring = $("input[name=recurring]:checked").val();
            recurringRadio(recurring);
        } else {
            $("#recurring").parent().parent('.control-group').hide('slow ');
        }

    })(axisubs.jQuery);
}

//For customer form new/existing
function switchUserFields(val){
    (function($) {
        var userParent = $("#user_id").parent().parent();
        if(val == "new"){
            userParent.parent().parent('.control-group').hide('slow');
            $("#password, #password2").parent().parent('.control-group').show('slow');
            $("#user_id, #user_id_id").val('');
        } else {
            userParent.parent().parent('.control-group').show('slow');
            $("#password, #password2").parent().parent('.control-group').hide('slow');
        }
    })(axisubs.jQuery);
}
//function triggeronlyOnce(val){

(function($) {
    $(function() {
        //For Plan type radio button default
        var planType = $("input[name=plan_type]:checked").val();
        planRadio(planType);

        //For Recurring radio button default
        var recurring = $("input[name=recurring]:checked").val();
        recurringRadio(recurring);

        //For onlyOnce radio button default
        var only_once = $("input[name=only_once]:checked").val();
        onlyOnceRadio(only_once);

        //For onlyOnce radio button default
        var only_once = $("input[name=only_once]:checked").val();
        onlyOnceRadio(only_once);

        //For customer form new/existing
        var customerType = $("input[name=new_or_existing_customer]:checked").val();
        switchUserFields(customerType);
    });
})(axisubs.jQuery);