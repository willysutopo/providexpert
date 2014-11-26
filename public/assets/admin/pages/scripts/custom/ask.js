var AskManaged = function () {

    $('select[name=health_filter]').change(function()
    {
        var filter = $('select[name=health_filter]').val();

        if ( filter == "all" )
        {
            $(".health_answered").show();
            $(".health_unanswered").show();
        }
        else
        if ( filter == "0" )
        {
            $(".health_answered").hide();
            $(".health_unanswered").show();
        }
        else
        if ( filter == "1" )
        {
            $(".health_answered").show();
            $(".health_unanswered").hide();
        }
    });

    $('select[name=property_filter]').change(function()
    {
        var filter = $('select[name=property_filter]').val();

        if ( filter == "all" )
        {
            $(".property_answered").show();
            $(".property_unanswered").show();
        }
        else
        if ( filter == "0" )
        {
            $(".property_answered").hide();
            $(".property_unanswered").show();
        }
        else
        if ( filter == "1" )
        {
            $(".property_answered").show();
            $(".property_unanswered").hide();
        }
    });

    $('select[name=food_filter]').change(function()
    {
        var filter = $('select[name=food_filter]').val();

        if ( filter == "all" )
        {
            $(".food_answered").show();
            $(".food_unanswered").show();
        }
        else
        if ( filter == "0" )
        {
            $(".food_answered").hide();
            $(".food_unanswered").show();
        }
        else
        if ( filter == "1" )
        {
            $(".food_answered").show();
            $(".food_unanswered").hide();
        }
    });

    $('select[name=love_filter]').change(function()
    {
        var filter = $('select[name=love_filter]').val();

        if ( filter == "all" )
        {
            $(".love_answered").show();
            $(".love_unanswered").show();
        }
        else
        if ( filter == "0" )
        {
            $(".love_answered").hide();
            $(".love_unanswered").show();
        }
        else
        if ( filter == "1" )
        {
            $(".love_answered").show();
            $(".love_unanswered").hide();
        }
    });

    $('select[name=education_filter]').change(function()
    {
        var filter = $('select[name=education_filter]').val();

        if ( filter == "all" )
        {
            $(".education_answered").show();
            $(".education_unanswered").show();
        }
        else
        if ( filter == "0" )
        {
            $(".education_answered").hide();
            $(".education_unanswered").show();
        }
        else
        if ( filter == "1" )
        {
            $(".education_answered").show();
            $(".education_unanswered").hide();
        }
    });

    return {

        //main function to initiate the module
        init: function () {
            if (!jQuery().dataTable) {
                return;
            }
        }

    };

}();

var AskExpertManaged = function () {

    $('select[name=question_filter]').change(function()
    {
        var filter = $('select[name=question_filter]').val();

        if ( filter == "all" )
        {
            $(".question_answered").show();
            $(".question_unanswered").show();
        }
        else
        if ( filter == "0" )
        {
            $(".question_answered").hide();
            $(".question_unanswered").show();
        }
        else
        if ( filter == "1" )
        {
            $(".question_answered").show();
            $(".question_unanswered").hide();
        }
    });

    return {

        //main function to initiate the module
        init: function () {
            if (!jQuery().dataTable) {
                return;
            }
        }

    };

}();

var AskQuestion = function () {

    $(".submit_question_btn").click(function()
    {
        if ( confirm("Are you sure to submit? Your submission will cost you 1 credit.") )
        {
            $("#form_ask_question").submit();
        }
    });

}();