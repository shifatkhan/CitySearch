/**
 * This javascript file will be doing alot of ajax for autocomplete
 * 
 * @author Shifat Khan
 */

// The number of characters the client needs to input
var MIN_LENGTH = 2;

$(document).ready(function () {
    $("#keyword").keyup(function () {
        var keyword = $("#keyword").val();
        if (keyword.length >= MIN_LENGTH) {
            var hr = new XMLHttpRequest();

            // Variables to send to our PHP file
            var url = "search.php";
            var vars = "keyword=" + keyword;

            // Set the request to GET
            hr.open("GET", url, true);
            hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            // Access the onreadystatechange event for the XMLHttpRequest object
            hr.onreadystatechange = function () {
                if (hr.readyState == 4 && hr.status == 200) {
                    // We expect to recieve a json return
                    var return_data = hr.responseJSON;
                    $('#results').html('');

                    // Parse the return as json (array)
                    var results = jQuery.parseJSON(return_data);

                    // Create the drop down list using the array recieved
                    $(results).each(function (key, value) {
                        $('#results').append('<div class="item">' + value + '</div>');
                    })

                    // Add a click listener to autocomplete to you text input 
                    $('.item').click(function () {
                        var text = $(this).html();
                    $('#keyword').val(text);
                    })
                }
            }
            // Execute request
            hr.send(vars);
        }
    });
    
    // To make the drop list fade out when not in focus and show when it is
    $("#keyword").blur(function () {
        $("#results").fadeOut(500);
    })
    .focus(function () {
        $("#results").show();
    });
});