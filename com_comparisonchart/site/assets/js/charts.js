jQuery.noConflict();

function lightbox() {
    var links = jQuery('a[rel^=lightbox]');

    var overlay = jQuery(jQuery('<div id="overlay" style="display: none"></div>'));
    var container = jQuery(jQuery('<div id="lightbox" style="display: none"></div>'));
    var close = jQuery(jQuery('<a href="#close" class="close">&times; Close</a>'));
    var target = jQuery(jQuery('<div class="target"></div>'));

    jQuery('body').append(overlay).append(container);
    container.append(close).append(target);
    container.show().css({'top': Math.round(((jQuery(window).height() > window.innerHeight ? window.innerHeight : jQuery(window).height()) - container.outerHeight()) / 2) + 'px', 'left': Math.round((jQuery(window).width() - container.outerWidth()) / 2) + 'px', 'margin-top': 0, 'margin-left': 0}).hide();
    close.click(function(c) {
        c.preventDefault();
        overlay.add(container).fadeOut('normal');
    });

    links.each(function(index) {
        var link = jQuery(this);

        link.click(function(c) {
            c.preventDefault();
            open(link.attr('href'));
            links.filter('.selected').removeClass('selected');
            link.addClass('selected');
        });
        link.attr({'lb-position': index});
    });

    var open = function(url) {
        if (container.is(':visible')) {
            target.children().fadeOut('normal', function() {
                target.children().remove();
            });
        } else {
            target.children().remove();
            overlay.add(container).fadeIn('normal', function() {
                loadContent(url);
            });
        }
    }

    var loadContent = function(url) {
        if (container.is('.loading')) {
            return;
        }
        container.addClass('loading');
        jQuery.getJSON(url, function(data) {
            if (data.title) {
                var title = jQuery('<h3/>').addClass('title').html(data.title).css({
                    'text-align': 'left'
                });
            }
            if (data.description) {
                var desc = jQuery('<div/>').addClass('description').html(data.description).css({
                    'text-align': 'left'
                });
            }
            if (data.img) {
                var image = jQuery('<img/>').attr('src', data.img).css({
                    'float': 'right',
                    'margin': '10px',
                    'margin-right': '0px'
                });
                ;
            }
            target.append(title).append(image).append(desc);
            container.removeClass('loading');
        });
    }
}

var scroll_position = 0;

jQuery(document).ready(function() {
    jQuery('body').addClass('has-js');

    var twidth = jQuery("#rgMasterTable").width();
    var width = jQuery("#rgMasterTableContainer").width();

    if (twidth > width) {
        var colWidth = jQuery("#rgMasterTable tr:nth-child(1) td:first-child").width() + 21;
        var width = width - colWidth;

        jQuery("#rgMasterTable2Container")
                .html(jQuery("#rgMasterTableContainer").html().replace("rgMasterTable", "rgMasterTable2"))
                .css("left", colWidth + 1 + "px");

        jQuery("#rgMasterTable2").css("margin-left", "-" + colWidth - 1 + "px");
        jQuery("#rgMasterTable2Container").css("width", width - 1 + "px");

        jQuery("#rgMasterTable tr td").css("visibility", "hidden");
        jQuery("#rgMasterTable tr td:first-child").css("visibility", "visible");
        jQuery("#rgMasterTableContainer").css("overflow", "hidden");


    }
    if (!jQuery("#rgMasterTable2").length) {
        jQuery('.column1').css('border-right', '1px solid #ccc');
    }
    jQuery('tr.pline a.ch_hide_property').click(function(e) {
        e.preventDefault();
        var tr = jQuery(this).parents('tr').index() + 1;
        var tr2 = jQuery(this).parents('tr').attr('id');
        jQuery('#chart_xls').append('<input type="hidden" class="deleted_rows" name="deleted_rows[]" value="' + tr2 + '" />')


        jQuery('table.pdtable tr:nth-child(' + tr + ')').fadeOut().addClass('cmpTrHide');
       
        jQuery("table.pdtable tr:nth-child(" + tr + ")").find('td').addClass('cmpTdHide');

        //alert(tr);
    });
    jQuery('tr a.ch_hide_item').click(function(e) {
        e.preventDefault();
        var cell = jQuery(this).parents('td'),
                cell2 = jQuery(this).parents('td').attr('id');

        jQuery('#chart_xls').append('<input type="hidden" class="deleted_cols" name="deleted_cols[]" value="' + cell2 + '" />');

        var cellIndex = cell.parent("tr").children().index(cell) + 1;
        jQuery('tr.pline').find('td:nth-child(' + cellIndex + ')').fadeOut().addClass('cellHide');

        jQuery("#rgMasterTable tr").find('td:nth-child(' + cellIndex + ')').css("display", "none");

        cell.addClass('cellHide').fadeOut();
        jQuery('tr.pdsection:not(.pline) td').attr('colspan', jQuery('tr.pdsection td').attr('colspan') - 1);


        jQuery('.wr1').width(jQuery('#rgMasterTable2Container').width());
        jQuery('.cl').width(jQuery('#rgMasterTable').find('tr').width() - jQuery('#rgMasterTable').find('tr').find('td:first').width());

        var twidth = jQuery("#rgMasterTable").width(),
                width = jQuery("#rgMasterTableContainer").width();
        
        if (twidth <= width) {
            jQuery('.wr1').css({'overflow-x': 'hidden'});
        }

    });
    jQuery('a.ch_show_params').click(function(e) {
        jQuery('#chart_xls .deleted_rows').remove();
        e.preventDefault();
        jQuery("table.pdtable tr.cmpTrHide").show();
        jQuery("table.pdtable tr.cmpTrHide").removeClass('cmpTrHide');
        jQuery("table.pdtable tr.pline").show();

    });
    jQuery('a.ch_show_items').click(function(e) {
        e.preventDefault();
        jQuery('#chart_xls .deleted_cols').remove();
        var colspan = jQuery('table.pdtable tr:first td').length;
        jQuery('td.cellHide').show();
        //j('td.cellHide').css('visibility', 'visible');
        jQuery('tr.pdsection:not(.pline) td').attr('colspan', colspan);
        jQuery("#rgMasterTable tr td").css("display", "");

        jQuery("table.pdtable tr.pline").find('td').removeClass('cellHide');
        jQuery("table.pdtable tr.pline").find('td:not(.cellHide)').removeClass('cmpTdHide');
    });

    jQuery('a.ch_xls_export').click(function(e) {
        e.preventDefault();
        var form = jQuery('#chart_xls');
        form.submit();

    });

    jQuery('a.ch_toggle_equal').click(function(e) {
        e.preventDefault();
		
		jQuery('tr.pline:not(.pdsection)').each(function() {

			tr = jQuery(this);

			tds = tr.find('td:visible');
			tds_count = tds.length - 2;
		   // console.log(tds_count);

			hide = 0;
			res = 0;
			tds.each(function() {
				cur = jQuery(this).find('input').val();
				if (res == cur) {
					hide++;
				}
				res = cur;
			});
			if (hide == tds_count) {
				tr.toggle();
			}

		});
    });

    jQuery('#rgMasterTable2 tr.pdsection td').css('visibility', 'visible');


    var trHeight = 0;
    var trArray = jQuery('#rgMasterTable2 tr');
    trArray.each(function() {
        trHeight = jQuery(this).height();
        jQuery(this).css('height', trHeight)
    });

    var trHeight = 0;
    var trArray = jQuery('#rgMasterTable tr');
    trArray.each(function() {
        trHeight = jQuery(this).height();
        jQuery(this).css('height', trHeight)
    });

    lightbox();
});