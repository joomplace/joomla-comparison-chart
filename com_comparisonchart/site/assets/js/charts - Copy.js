var j = jQuery.noConflict();

function lightbox() {
	var links = j('a[rel^=lightbox]');
		
	var overlay = j(jQuery('<div id="overlay" style="display: none"></div>'));
	var container = j(jQuery('<div id="lightbox" style="display: none"></div>'));
	var close = j(jQuery('<a href="#close" class="close">&times; Close</a>'));
	var target = j(jQuery('<div class="target"></div>'));

	j('body').append(overlay).append(container);
	container.append(close).append(target);
	container.show().css({'top': Math.round(((j(window).height() > window.innerHeight ? window.innerHeight : j(window).height()) - container.outerHeight()) / 2) + 'px', 'left': Math.round((j(window).width() - container.outerWidth()) / 2) + 'px', 'margin-top': 0, 'margin-left': 0}).hide();
	close.click(function(c) {
		c.preventDefault();
		overlay.add(container).fadeOut('normal');
	});

	links.each(function(index) {
		var link = j(this);
		
		link.click(function(c) {
			c.preventDefault();
			open(link.attr('href'));
			links.filter('.selected').removeClass('selected');
			link.addClass('selected');
		});
		link.attr({'lb-position': index});
	});
		
	var open = function(url) {
		if(container.is(':visible')) {
			target.children().fadeOut('normal', function() {
				target.children().remove();
			});
		} else {
			target.children().remove();
			overlay.add(container).fadeIn('normal',function(){
				loadContent(url);
			});
		}
	}
	
	var loadContent = function(url) {
		if(container.is('.loading')) { return; }
		container.addClass('loading');
		j.getJSON(url, function(data) {
			if (data.title) {
				var title = j('<h3/>').addClass('title').html(data.title).css({
					'text-align':'left'
				});
			}
			if (data.description) {
				var desc = j('<div/>').addClass('description').html(data.description).css({
					'text-align': 'left'
				});
			}
			if (data.img) {
				var image = j('<img/>').attr('src', data.img).css({
					'float': 'right',
					'margin': '10px',
					'margin-right': '0px'
				});;
			}
			target.append(title).append(image).append(desc);
			container.removeClass('loading');
		});
	}
}

var scroll_position = 0;

j(document).ready(function(){
	j('body').addClass('has-js');
	
	var twidth = j("#rgMasterTable").width();
	var width = j("#rgMasterTableContainer").width();
	
	if (twidth > width) {
		var colWidth = j("#rgMasterTable tr:nth-child(1) td:first-child").width() + 21;
		var width = width - colWidth;

		j("#rgMasterTable2Container")
			.html(j("#rgMasterTableContainer").html().replace("rgMasterTable", "rgMasterTable2"))
			.css("left", colWidth + "px");

		j("#rgMasterTable2").css("margin-left", "-" + colWidth + "px");
		j("#rgMasterTable2Container").css("width",width+"px");

		j("#rgMasterTable tr td").css("visibility", "hidden");
		j("#rgMasterTable tr td:first-child").css("visibility", "visible");
		j("#rgMasterTableContainer").css("overflow", "hidden");

		j("#upper_scroll > div").css("width",(j("#rgMasterTable2").width())+"px");
	 
		var entry_left = j("#upper_scroll > div").offset().left;
	 
		j("#upper_scroll").scroll(function(){
			var new_left = entry_left - j("#upper_scroll > div").offset().left;
			new_left = new_left + "px";
			j("#rgMasterTable2Container").scrollTo({top:"0px",left:new_left});
		});
		j("#rgMasterTable2Container").scroll(function(){
			var new_left = entry_left - j("#rgMasterTable2").offset().left;
			new_left = new_left + "px";
			j("#upper_scroll").scrollTo({top:"0px",left:new_left});
		});
	}
	
	j('tr.pline a.ch_hide_property').click(function(e) {
		e.preventDefault();
		var tr = j(this).parents('tr').index()+1;
		j('table.pdtable tr:nth-child('+tr+')').fadeOut();
	});
	j('tr a.ch_hide_item').click(function(e) {
		e.preventDefault();
		var cell = j(this).parents('td');
		var cellIndex = cell.parent("tr").children().index(cell) + 1;
		j('tr.pline').find('td:nth-child('+cellIndex+')').addClass('cellHide').fadeOut();
		
		j("#rgMasterTable tr").find('td:nth-child('+cellIndex+')').css("display", "none");
		
		cell.addClass('cellHide').fadeOut();
		j('tr.pdsection:not(.pline) td').attr('colspan', j('tr.pdsection td').attr('colspan')-1);
		//alert(cellIndex);
	});
	j('a.ch_show_params').click(function(e) {
		e.preventDefault();
		j('tr.pline').show();
	});
	j('a.ch_show_items').click(function(e) {
		e.preventDefault();
		var colspan = j('table.pdtable tr:first td').length;
		j('td.cellHide').show();
		j('tr.pdsection:not(.pline) td').attr('colspan', colspan);
		j("#rgMasterTable tr td").css("display", "");
	});
	
	j('tr.pline:not(.pdsection)').each(function() {
		
		tr = j(this);
		//tds = tr.find('td:not(.pdinfohead)');
		tds = tr.find('td (.pdinfohead)');
		tds_count = tds.length - 1;
		//alert(tds_count);
		hide = 0;
		res = 0;
		tds.each(function() {
			cur = j(this).html();
			if (res == cur) {
				hide++;
			}
			res = cur;
		});
		if (hide == tds_count) {
			tr.addClass('equal');
		}
		
	});
	j('a.ch_toggle_equal').click(function(e) {
		e.preventDefault();
		j('tr.equal').toggle();
	});
	
	j('#rgMasterTable2 tr.pdsection td').css('visibility', 'visible');
	
		
	var trHeight = 0;
	var trArray = j('#rgMasterTable2 tr');
	trArray.each(function(){
		trHeight = j(this).height();
		j(this).css('height', trHeight)
	});
	
	var trHeight = 0;
	var trArray = j('#rgMasterTable tr');
	trArray.each(function(){
		trHeight = j(this).height();
		j(this).css('height', trHeight)
	});
	
	j('#rgMasterTable2 tr td:first-child').not('#rgMasterTable2 tr.pdsection td').html('').css('visibility', 'visible');
	
	lightbox();
});