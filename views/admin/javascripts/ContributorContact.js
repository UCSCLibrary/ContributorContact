jQuery(document).ready(function(){

    jQuery('#displayContribEmails').click(function(e) {
	jQuery.get('contributor-contact/index/emails',function(data) {
	    alert(formatEmails(jQuery.parseJSON(data),'; '));
	});
    });
});

function formatEmails(data,delimiter) {
    //displayString = "<h2>Contributor Email Addresses</h2><p>";
    displayString = "";
    if(typeof data == 'string')
	return data;
    jQuery.each(data,function(index,datum) { 
	displayString += datum.replace(' at ','@') + delimiter;
    });
    //displayString += "</h2>";
    return displayString;
}
