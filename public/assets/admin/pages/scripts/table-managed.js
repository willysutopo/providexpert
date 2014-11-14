var TableManaged = function () {

    var initTable1 = function () {

        var table = $('#sample_1');

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
                "orderable": true
            }, {
                "orderable": true
            }, {
                "orderable": true
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
                "targets": [0]
            }],
            "order": [
                [1, "asc"]
            ] // set first column as a default sort by asc
        });

        var tableWrapper = jQuery('#sample_1_wrapper');

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
        tableWrapper.find('.dataTables_filter input').addClass("form-control input-large input-inline"); // modify table per page dropdown     
    }

    

    // when a dropdown "members filter" changes its value
    $("#members_filter").change(function()
    {
      var table = $('#sample_1');
      var search = $("#sample_1_filter input").val();
      var selected_filter = $("#members_filter").val();
      var column_affected = 1;

      var table2 = table.DataTable();

      var target_array;

      if ( selected_filter == "all" )
        target_array = [0];
      else
      if ( selected_filter == "first_name" )
        target_array = [0,2,3];
      else
      if ( selected_filter == "last_name" )
        target_array = [0,1,3];
      else
      if ( selected_filter == "email" )
        target_array = [0,1,2];

      table2.destroy();

      // reinitialize member's data table
      table2 = table.dataTable({
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
            "orderable": true
        }, {
            "orderable": true
        }, {
            "orderable": true
        }, {
            "orderable": false
        }],
        "lengthMenu": [
            [30, -1],
            [30, "All"] // change per page values here
        ],
        // set the initial value
        "pageLength": 5,            
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
            "targets": target_array
        }],
        "order": [
            [1, "asc"]
        ] // set first column as a default sort by asc
      });

      //table2.draw();

      var tableWrapper = $('#sample_1_wrapper');

      tableWrapper.find('.dataTables_filter input').addClass("form-control input-large input-inline"); 
      // modify table per page dropdown     

      /*
      table
        .column( column_affected )
        .search( search )
        .draw();
      */

      //table.draw();
    });

    var initTable2 = function () {

        var table = $('#sample_2');

        table.dataTable({
            "lengthMenu": [
                [30, -1],
                [30, "All"] // change per page values here
            ],
            // set the initial value
            "pageLength": 5,
            "language": {
                "lengthMenu": " _MENU_ records",
                "paging": {
                    "previous": "Prev",
                    "next": "Next"
                }
            },
            "columnDefs": [{  // set default column settings
                'orderable': false,
                'targets': [0]
            }, {
                "searchable": false,
                "targets": [0]
            }],
            "order": [
                [1, "asc"]
            ] // set first column as a default sort by asc
        });

        var tableWrapper = jQuery('#sample_2_wrapper');

        table.find('.group-checkable').change(function () {
            var set = jQuery(this).attr("data-set");
            var checked = jQuery(this).is(":checked");
            jQuery(set).each(function () {
                if (checked) {
                    $(this).attr("checked", true);
                } else {
                    $(this).attr("checked", false);
                }
            });
            jQuery.uniform.update(set);
        });

        tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown
    }

    var initTable3 = function () {

        var table = $('#sample_3');

        // begin: third table
        table.dataTable({
            "lengthMenu": [
                [30, -1],
                [30, "All"] // change per page values here
            ],
            // set the initial value
            "pageLength": 5,
            "language": {
                "lengthMenu": " _MENU_ records"
            },
            "columnDefs": [{  // set default column settings
                'orderable': false,
                'targets': [0]
            }, {
                "searchable": false,
                "targets": [0]
            }],
            "order": [
                [1, "asc"]
            ] // set first column as a default sort by asc
        });

        var tableWrapper = jQuery('#sample_3_wrapper');

        table.find('.group-checkable').change(function () {
            var set = jQuery(this).attr("data-set");
            var checked = jQuery(this).is(":checked");
            jQuery(set).each(function () {
                if (checked) {
                    $(this).attr("checked", true);
                } else {
                    $(this).attr("checked", false);
                }
            });
            jQuery.uniform.update(set);
        });

        tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown
    }

    return {

        //main function to initiate the module
        init: function () {
            if (!jQuery().dataTable) {
                return;
            }

            initTable1();
            initTable2();
            initTable3();
        }

    };

}();