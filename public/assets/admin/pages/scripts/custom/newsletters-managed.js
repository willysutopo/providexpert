var TableManaged = function () {

    var initNewsletterTable = function () {

        var table = $('#newsletter_list');

        // begin first table
        table.dataTable({
            "fnDrawCallback": function ( oSettings ) 
            {
              /* Need to redo the counters if filtered or sorted */
              if ( oSettings.bSorted || oSettings.bFiltered )
              {
                for ( var i=0, iLen=oSettings.aiDisplay.length ; i<iLen ; i++ )
                {
                  $('td:eq(0)', oSettings.aoData[ oSettings.aiDisplay[i] ].nTr ).html( i+1 );
                }
              }
            },
            "columns": [ {
                "orderable": false
            }, {
                "orderable": false
            }, {
                "orderable": false
            }, {
                "orderable": false
            }, {
                "orderable": false
            }, {
                "orderable": false
            }, {
                "orderable": false
            }],
            "lengthMenu": [
                [30, -1],
                [30, "All"] // change per page values here
            ],
            // set the initial value
            "pageLength": 30,
            "pagingType": "bootstrap_full_number",
            "language": {
                "lengthMenu": "  _MENU_ records",
                "paginate": {
                    "previous":"Prev",
                    "next": "Next",
                    "last": "Last",
                    "first": "First"
                }
            },
            "columnDefs": [{  // set default column settings
                'orderable': false,
                'targets': [0]
            }, {
                "searchable": false,
                "targets": [2]
            }],
            "order": [
                
            ] // set first column as a default sort by asc
        });

        var tableWrapper = jQuery('#newsletter_list_wrapper');

        table.find('.group-checkable').change(function () {
            var set = jQuery(this).attr("data-set");
            var checked = jQuery(this).is(":checked");
            jQuery(set).each(function () {
                if (checked) {
                    $(this).attr("checked", true);
                    $(this).parents('tr').addClass("active");
                } else {
                    $(this).attr("checked", false);
                    $(this).parents('tr').removeClass("active");
                }
            });
            jQuery.uniform.update(set);
        });

        table.on('change', 'tbody tr .checkboxes', function () {
            $(this).parents('tr').toggleClass("active");
        });

        tableWrapper.find('.dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown
    }

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

            initNewsletterTable();
        }

    };

}();