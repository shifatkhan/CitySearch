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
                        
                        var count = 0;
                        
                        // Create the item list displaying each city
                        $(results).each(function (key, value) {
                            count++;
                            if(count <= 5)
                                $('#results').append('<div class="item">' + value + '</div>');
                        })

                        // Make the list clickable. When clicked, put the keyword in the textbox.
                        // Also, add the keyword into the search history database
                        $('.item').click(function () {
                            var text = $(this).html();
                            $('#keyword').val(text);

                            var keyword = $("#keyword").val();
                            // Send keyword to searchHistory.php and get a response
                            $.post("searchHistory.php", {keyword: keyword})
                                    .done(function (data) {
                                        // Display the status
                                        $("<p class=\"alert\" style=\"color: white\">" + data + "</p>")
                                                .insertBefore("#results");
                                    });
                        })

                    });
        } else {
            $('#results').html('');
        }
    });

    $("#keyword").blur(function () {
        $(".alert").fadeOut(500);
    })

    // Make it so the list fades away when not in focus and appear when it is
    $("#keyword").blur(function () {
        $("#results").fadeOut(500);
    }).focus(function () {
        $("#results").show();
    });

});

/**
 * This will run when the user click Submit. It will then store the keyword in
 * the database as history
 */
$(document).ready(function () {
    $("#submit").click(function () {
        var keyword = $("#keyword").val();
        
        // Send keyword to searchHistory.php and get a response
        $.post("searchHistory.php", {keyword: keyword})
                .done(function (data) {
                    $('#keyword').val(data);
                    
                    // Display the status 
                    $("<p class=\"alert\" style=\"color: white\">" + data + "</p>")
                            .insertBefore("#results");
                });
    });
});

/**
 * This will run when the user click Delete History. it will delete all the past
 * search terms from the database
 */
$(document).ready(function () {
    $("#delete").click(function () {
        var keyword = $("#keyword").val();
        // Send keyword to deleteSearchHistory.php and get a response
        $.post("deleteSearchHistory.php", {keyword: keyword})
                .done(function (data) {
                    
                    // Display the status 
                    $("<p class=\"alert\" style=\"color: white\">" + data + "</p>")
                            .insertBefore("#results");
                });
    });
});
