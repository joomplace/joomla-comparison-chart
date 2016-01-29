jQuery.noConflict();

function JoomRate(id,rid,i,total,total_count,counter){
	var jrAjax;
	var live_site = window.location.protocol+'//'+window.location.host+sfolder;
	var div = jQuery('.jrate-'+id+'-'+rid);
	var div2 = jQuery('.rating-'+id+'-'+rid);
	
	div.html('<img src="'+live_site+'/components/com_comparisonchart/assets/images/loading.gif" border="0" width="16px" align="absmiddle" /> '+'<small>'+jrate_text[1]+'</small>');
	try	{
		jrAjax=new XMLHttpRequest();
	} catch (e) {
		try	{ jrAjax=new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try { jrAjax=new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {
				alert(jrate_text[0]);
				return false;
			}
		}
	}
	jrAjax.onreadystatechange=function() {
		var response;
		if(jrAjax.readyState==4){
			setTimeout(function(){ 
				response = jrAjax.responseText; 
				if(response=='thanks') div.html('<small>'+jrate_text[2]+'</small>');
				if(response=='login') div.html('<small>'+jrate_text[3]+'</small>');
				if(response=='voted') div.html('<small>'+jrate_text[4]+'</small>');
			},500);
			setTimeout(function(){
				if(response=='thanks'){
					var newtotal = total_count+1;
					var percentage = ((total + i)/(newtotal));
					div2.css('width', parseInt(percentage*20)+'%');
					
				}
				if(counter!=0){
					if(response=='thanks'){
						if(newtotal!=1)	
							var newvotes=newtotal+' '+jrate_text[5];
						else
							var newvotes=newtotal+' '+jrate_text[6];
						div.html('<small>( '+newvotes+' )</small>');
					} else {
						if(total_count!=0 || counter!=-1) {
							if(total_count!=1)
								var votes=total_count+' '+jrate_text[5];
							else
								var votes=total_count+' '+jrate_text[6];
							div.html('<small>( '+votes+' )</small>');
						} else {
							div.html('');
						}
					}
				} else {
					div.html('');
				}
			},2000);
		}
	}
	jrAjax.open("GET",live_site+"/index.php?option=com_comparisonchart&task=item.getRateAjax&user_rating="+i+"&id="+id+"&rid="+rid,true);
	jrAjax.send(null);
}