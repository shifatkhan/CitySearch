/**
 * This javascript file will be doing alot of ajax for autocomplete
 * 
 * @author Shifat Khan
 */

// The number of characters the client needs to input
var MIN_LENGTH = 2;

/**
 * This part will be run whenever the client writes something in the textbox. 
 * It will send the keyword to the search.php and get back the response as a
 * json array. It will then create a list with that array.
 */
$(document).ready(function () {
    $("#keyword").keyup(function () {
        var keyword = $("#keyword").val();
        if (keyword.length >= MIN_LENGTH) {

            // Send keyword to search.php and get a response
            $.get("search.php", {keyword: keyword})
                    .done(function (data) {
                        $('#results').html('');
                        
                        // Parse the data to JSON
                        var results = jQuery.parseJSON(data);
                        
                        // Create the item list displaying each city
                        $(results).each(function (key, value) {
                            $('#results').append('<div class="item">' + value + '</div>');
                        })

                        // Make the list clickable. When clicked, put the keyword in the textbox
                        $('.item').click(function () {
                            var text = $(this).html();
                            $('#keyword').val(text);
                        })

                    });
        } else {
            $('#results').html('');
        }
    });

    // Make it so the list fades away when not in focus and appear when it is
    $("#keyword").blur(function () {
        $("#results").fadeOut(500);
    }).focus(function () {
        $("#results").show();
    });

});

/**
 * This function will be getting the keyword and sending it to a php file that
 * will then put it in the database as a search history.
 * @returns {undefined}
 */
function searchTerm(){
    var xhr = new XMLHttpRequest();
    
    // Variables to be passed to the php file
    var url = "searchHistory.php";
    var keyword = $("#keyword").val();
    var vars = "keyword="+keyword;
    
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    
    xhr.onreadystatechange = function(){
        if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;
			document.getElementById("status").innerHTML = return_data;
	    }
    }
    
    // Execute the request.
    xhr.send(vars);
    $("<p class=\"alert\" style=\"color: white\">Submitting...</p>").insertBefore("#results");
}