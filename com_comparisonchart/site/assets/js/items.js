jQuery.noConflict();

function setupLabel() {
    if (jQuery('.label_check input').length) {
        jQuery('.label_check').each(function () {
            jQuery(this).removeClass('c_on');
        });
        jQuery('.label_check input:checked').each(function () {
            jQuery(this).parent('label').addClass('c_on');
        });
    }
    ;
}

function bindLabel() {
    jQuery('label.label_check input').click(function() {
     setupLabel();

     var task = 'index.php?option=com_comparisonchart&task=item.toCompare';
     var chart_id = jQuery('input[name="chart-id"]').val();
     var item_id = jQuery(this).val();
        jQuery.post(task, {
                'chart':chart_id,
                'item':item_id
            },
            function (data) {

                var num = data.split(';');

                jQuery('#chart-notice').css('display', 'block');
                jQuery('#chart-notice').animate({
                    opacity:1
                }, 500);

                if (num[1]!=0) {
                   // jQuery('#chart-notice').html(num[1] + ' item(-s) added to compare. <a href="#" onclick="jQuery(\'#charts-form\').submit()">Compare now.</a>');

                    jQuery('#chart-notice').html(num[1] + ' item(-s) <a href="#" onclick="jQuery(\'#charts-form\').submit()">added to compare.</a>');
                } else {
                    jQuery('#chart-notice').html('No items selected.');
                }
            });
        jQuery('#chart-notice').animate({
            opacity:0
        }, 100);
    });
}

function resetLabels() {
    if (jQuery('.label_check input').length) {
        jQuery('.label_check').each(function () {
            jQuery(this).removeClass('c_on');
        });

        jQuery('.label_check input:checked').each(function () {
            jQuery(this).removeClass('c_on');
            jQuery(this).parent('label').find('input[type="checkbox"]').attr('checked', false);

        });

        var task = 'index.php?option=com_comparisonchart&task=item.clearCompare';
        var chart_id = jQuery('input[name="chart-id"]').val();
        jQuery.post(task, {
                'chart':chart_id
            }, function (data) {
                jQuery('#chart-notice').css('display', 'block');
                jQuery('#chart-notice').animate({
                    opacity:1

                }, 500);
                jQuery('#chart-notice').html('The list is empty');
            }

        );
        jQuery('#chart-notice').animate({
            opacity:0

        }, 100);

    }
    ;
}

function loadData(e) {
    e.preventDefault();
    var div = '<div class="loading" />';
    var form = jQuery('form[name="items-form"]');
    form.append(div);
    var href = jQuery(this).attr('href');
    href = href + '&layout=default_items&format=raw';
    form.load(href, function () {
        //jQuery('a.pagenav').click(loadData);
        setupLabel();
        bindLabel();
    });
}

jQuery(document).ready(function () {
    jQuery('body').addClass('has-js');
    setupLabel();
    bindLabel();
   // jQuery('a.pagenav').click(loadData);

    jQuery('.ch_reset').click(function (e) {
        resetLabels();
    });

});