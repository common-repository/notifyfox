function changeTab(evt, tabId) {
    var i, tabcontent, tablinks;
   tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabId).style.display = "block";
    evt.currentTarget.className += " active";
}

// For Widget
function nf_submit_page(id) {
	$.preventDefault();
	var url = document.getElementById(id).getAttribute("data-url");
	var postid = document.getElementById(id).getAttribute("data-post-id");
	var title = document.getElementById(id).getAttribute("data-title");
	var content = document.getElementById(id).getAttribute("data-content");
	var postimage = document.getElementById(id).getAttribute("data-image");
	var nf_author = document.getElementById(id).getAttribute("data-author");
	var nf_url = document.getElementById(id).getAttribute("data-nf_url");
	document.getElementById(id).innerHTML = nf_message.nf_process;
	var mynumber = Math.random();
	var str= "nf_postid="+ encodeURI(postid) + "&nf_title=" + encodeURI(title) + "&nf_content=" + encodeURI(content)+ "&nf_url=" + encodeURI(nf_url) + "&nf_postimage=" + encodeURI(postimage)+ "&nf_author=" + encodeURI(nf_author);
	nf_submit_request(url, str);
}

var http_req = false;
function nf_submit_request(url, parameters) {
	
	http_req = false;
	if (window.XMLHttpRequest) {
		
		http_req = new XMLHttpRequest();
		
	} else if (window.ActiveXObject) {
		try {
			http_req = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				http_req = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {
				
			}
		}
	}
	if (!http_req) {
		alert(nf_message.nf_ajax_error);
		return false;
	}
	//alert( url+'&'+parameters);
	http_req.open('POST', url+'&'+parameters, true);

	http_req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	// http_req.setRequestHeader("Content-length", parameters.length);
	// http_req.setRequestHeader("Connection", "close");
	http_req.send();
	http_req.onreadystatechange = nf_submitresult;
}

function nf_submitresult() {
	//alert(http_req.responseText);
	if (http_req.readyState == 4) {
		if (http_req.status == 200) {
		 	if (http_req.readyState==4 || http_req.readyState=="complete") { 
				if((http_req.responseText).trim() == "subscribed-successfully") {
					document.getElementById("nf-subscribe").innerHTML = nf_message.nf_unsubcribe;							
					$d=document.getElementById("nf-subscribe").getAttribute("data-url").split('?');					
					document.getElementById("nf-subscribe").setAttribute("data-url", $d[0]+'/?nf=unsubscribe');
					
				}else if((http_req.responseText).trim() == "unsubscribe-successfully"){
					document.getElementById("nf-subscribe").innerHTML = nf_message.nf_subscribe;
					$d=document.getElementById("nf-subscribe").getAttribute("data-url").split('?');					
					document.getElementById("nf-subscribe").setAttribute("data-url", $d[0]+'/?nf=subscribe');
				} 
			}
		} else {
			alert(nf_message.nf_problem_request);
		}
	}
}