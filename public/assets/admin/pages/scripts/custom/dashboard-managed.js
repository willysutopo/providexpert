var DashboardManaged = function () {

    $(".newsletter_delete").click( function()
    {
        var identifier = $(this).attr("identifier");
        if ( confirm( "Are you sure you want to delete this entry?") )
        {
            var form_affected = "#form_delete_" + identifier;
            $(form_affected).submit();
        }
    });

    $(".newsletter_send").click( function()
    {
        var identifier = $(this).attr("identifier");
        var when = $("#sending_time_when_"+identifier).val();
        var message = "";

        // set the confirmation question according to when the newsletter is gonna be sent
        if ( when == "later" )
          message = "Are you sure to send this newsletter according to the schedule you set?";
        else
        if ( when == "now" )
          message = "Are you sure you want to send this newsletter now?";

        // ask confirmation message
        if ( confirm(message) )
        {
            var form_affected = "#form_send_" + identifier;
            $(form_affected).submit();
        } 
    })

    return {

        //main function to initiate the module
        init: function () {
            if (!jQuery().dataTable) {
                return;
            }
        }

    };

}();