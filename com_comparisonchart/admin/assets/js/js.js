	function updateTemplate(el) {
		if (el == '') {
			var el = '1';
		}
		$$('div.comparisonchart')[0].setProperty('class', 'comparisonchart').addClass('style-'+el);		
	}
	
	function processReqChange() {
		var response = "";
		if (req.readyState == 4) {		// only if "OK"
			if (req.status == 200) {	// ...processing statements go here...
				response  = req.responseXML.documentElement;
				style = response.getElementsByTagName('style')[0].firstChild.data;
				$$('#innerStyle').set('text', '');
				$$('#innerStyle').set('text', style);				
			} else {
				return false;
			}
		}
	}
	
	function loadXMLDoc(onstate, url) 
	{
	// branch for native XMLHttpRequest object
	if (window.XMLHttpRequest) {
		req = new XMLHttpRequest();
		req.onreadystatechange = eval(onstate);
		req.open("POST", url, true);
		req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		req.setRequestHeader("Content-length", url.length);
		req.setRequestHeader("Connection", "close");
		req.send(url);
	// branch for IE/Windows ActiveX version
	} else if (window.ActiveXObject) {
		req = new ActiveXObject("Microsoft.XMLHTTP");
		if (req) {
			req.onreadystatechange = eval(onstate);
			req.open("POST", url, true);
			req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			req.setRequestHeader("Content-length", url.length);
			req.setRequestHeader("Connection", "close");
			req.send(url);
		}
		}
	}
		
	function makeRequest(url) {

		var http_request = false;
	
		if (window.XMLHttpRequest) { // Mozilla, Safari,...
			http_request = new XMLHttpRequest();
			if (http_request.overrideMimeType) {
				http_request.overrideMimeType('text/xml');
				// See note below about this line
			}
		} else if (window.ActiveXObject) { // IE
			try {
				http_request = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try {
					http_request = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e) {}
			}
		}
	
		if (!http_request) {
			// alert('Giving up: Cannot create an XMLHTTP instance');
			return false;
		}
		if (url.indexOf('latestNews') == -1){
			http_request.onreadystatechange = function() { alertContents(http_request); }
		} else{
			http_request.onreadystatechange = function() { alertContentsNews(http_request); }
		}
		http_request.open('GET', url, true);
		http_request.send(null);
	}

    function alertContents(http_request) {

        if (http_request.readyState == 4) {
            if ((http_request.status == 200) && (http_request.responseText.length < 1025)) {
				document.getElementById('compchart_LatestVersion').innerHTML = '&nbsp;'+http_request.responseText;
            } else {
                document.getElementById('compchart_LatestVersion').innerHTML = 'There was a problem with the request.';
            }
        }

    }
	function alertContentsNews(http_request) {

        if (http_request.readyState == 4) {
            if ((http_request.status == 200) && (http_request.responseText.length < 1025)) {
				document.getElementById('compchart_LatestNews').innerHTML = '&nbsp;'+http_request.responseText;
            } else {
                document.getElementById('compchart_LatestNews').innerHTML = 'There was a problem with the request.';
            }
        }

    }

	function compchart_CheckNews(){
		document.getElementById('compchart_LatestNews').innerHTML = 'Checking latest news now...';
    	makeRequest('index.php?option=com_comparisonchart&task=latestNews&no_html=1');
    	return false;
	}

    function compchart_CheckVersion() {
    	document.getElementById('compchart_LatestVersion').innerHTML = 'Checking latest version now...';
    	makeRequest('index.php?option=com_comparisonchart&task=latestVersion&no_html=1');
    	return false;
    }
    function compchart_InitAjax() {
    	makeRequest('index.php?option=com_comparisonchart&task=latestVersion&no_html=1');
    }
	
	function jb_dateAjaxRef() 
    {
        jQuery.ajax({
            type: "POST",
            url: "index.php?option=com_comparisonchart&task=datedb"
        });
        window.open("http://www.joomplace.com/joomla-components/comparison-chart-description.html", "_blank");
    }

    function jb_dateAjaxIcon() 
    {
        jQuery.ajax({
            type: "POST",
            url: "index.php?option=com_comparisonchart&task=datedb" 
        });
        jQuery('#notification').remove();
    }
