//function changeTab(evt, tabName) {
//
//// 	alert(tabName);
//	
//    // Declare all variables
//    var i, tabcontent, tablinks;
//
//    // Get all elements with class="tabcontent" and hide them
//    tabcontent = document.getElementsByClassName("tabcontent");
//    for (i = 0; i < tabcontent.length; i++) {
//        tabcontent[i].style.display = "none";
//    }
//
//    // Get all elements with class="tablinks" and remove the class "active"
//    tablinks = document.getElementsByClassName("tablinks");
//    for (i = 0; i < tablinks.length; i++) {
//        tablinks[i].className = tablinks[i].className.replace(" active", "");
//    }
//
//    // Show the current tab, and add an "active" class to the button that opened the tab
//    document.getElementById(tabName).style.display = "block";
//    evt.currentTarget.className += " active";
//}

function setNewTab()
{
	if (arguments.length==1) {
		var go = arguments[0];
		replaceParameterInUrl('go', go);
	}
	else if (arguments.length > 1)
	{
		var url = window.location.href;
//		console.log("url 0: " + url);
		for (var i = 0; i < arguments.length; i++)
		{
			var go = arguments[i];
			
//		    console.log("argument" + i + " :" + arguments[i]);
//		    console.log("go=" + go);
		    
		    if(i > 0)
		    {
				for (var key in go) 
				{
					if(go.hasOwnProperty(key)) 
					{
//					    console.log(key + " -> " + go[key]);
						url = changeURL(url, key,  go[key]);
//						console.log("url 1: " + url);
						break;
					}
				}
		    }
		    else
		    {
		    	url = changeURL(url, 'go', go);
//		    	console.log("url 2: " + url);
		    }
		}
//		console.log("url 3: " + url);
		window.location.href = url;
	}
}

function setNewErhebungsJahr(selectObject)
{
	var value = selectObject.value;
	replaceParameterInUrl('year', value);
}

function setNewBehoerde(selectObject)
{
	var value = selectObject.value;
	replaceParameterInUrl('behoerde', value);
}

function setNewAdressat(selectObject)
{
	var value = selectObject.value;
	replaceParameterInUrl('adressat', value);
}

function setNewUrlParameter(selectObject, urlParameter)
{
	var value = selectObject.value;
	replaceParameterInUrl(urlParameter, value);
}

function changeURL(url, key, value) {
	if(url != null)
	{
//		if (url.indexOf('go=wasserentnahmebenutzer') == -1){
		//
//				if (url.indexOf('?') > -1){
//					url += "&go=wasserentnahmebenutzer";
//				}
//				else
//				{
//					url += "?go=wasserentnahmebenutzer";
//				}	
//			}
		
//		console.log("key: " + key);
		
		if (url.indexOf('?') > -1) {
			if (url.indexOf(key) > -1) {
				if (url.indexOf(key + '=' + value) > -1) {
				} else {
					var oldValue = url.substring(url.indexOf(key) + key.length + 1,
							url.length);
					if (oldValue.indexOf('&') > -1) {
						oldValue = oldValue.substring(0, oldValue.indexOf('&'));
					}
//					console.log("oldValue: " + oldValue);
//					console.log("url: " + url);
					url = url.replace(key + '=' + oldValue, key + '=' + value);
				}
			} else {
				url += '&' + key + '=' + value;
			}
		} else {
			url += '?' + key + '=' + value;
		}
	}
	
	return url;
}

function replaceParameterInUrl(key, value)
{
	var url = window.location.href;
	url = changeURL(url, key, value)
	window.location.href = url;
}

$(document).ready(function() {
    $("#numberField").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 188, 190]) !== -1 ||
             // Allow: Ctrl/cmd+A
            (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: Ctrl/cmd+C
            (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: Ctrl/cmd+X
            (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
    
//  $('form').submit(function(e){
//         alert($('form').attr('id'));
//         e.preventDefault(e);
//      });
});

// function setAuswahlToHiddenForm(id, key, value)
// {
// 	alert($('#aufforderung_form'));

// // 	$("#aufforderung_form").find("input").each(function()
// // 	{
// //        var input = $(this);
// //        alert(input.html());
// // //        input.remove();
// //     });

// 	$('#aufforderung_form').append("<input type='hidden' id='" + id + "' name='" + key + "' value='" + value + "'>");
// }

// $(document).ready(function() {
//     //option A
//     $("#aufforderung_form").submit(function(e){
// //         alert($("form").attr('action'));
// //         e.preventDefault(e);
// //         $('form').attr('action', "index.php?go=wasserentnahmebenutzer&request=post").submit();
//     });
// });