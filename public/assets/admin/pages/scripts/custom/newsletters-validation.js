var FormValidation = function () {

    // advance validation
    var handleNewsletterValidation = function() {
        // for more info visit the official plugin documentation: 
        // http://docs.jquery.com/Plugins/Validation
        $("#category_ids").select2({placeholder: "Leave blank for all categories"});
        $("#source_ids").select2({placeholder: "Leave blank for all sources"});
        $("#location_ids").select2({placeholder: "Leave blank for all locations"});

        $(".form_datetime").datetimepicker({
          autoclose: true,
          isRTL: Metronic.isRTL(),
          format: "yyyy-mm-dd hh:ii",
          pickerPosition: (Metronic.isRTL() ? "bottom-right" : "bottom-left")
        });

        tinymce.init({
          selector: "textarea",
          plugins: [
            "advlist autolink lists link image charmap preview anchor", "searchreplace visualblocks code fullscreen", "insertdatetime media table contextmenu paste textcolor responsivefilemanager"
          ],
          toolbar1: "insertfile undo redo | styleselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify",
          toolbar2: " | responsivefilemanager | bullist numlist outdent indent | link image",
          image_advtab: true,
          convert_urls: false,
          relative_urls:false,
          external_filemanager_path:"/assets/filemanager/",
          filemanager_title:"File Manager",
          external_plugins: { "filemanager" : "/assets/filemanager/plugin.min.js"}
        });

        $('.iframe-btn').fancybox({
          'type': 'iframe',
          'autoScale': true,
          'width':1000,
          'height':600
        });

        $("#sending_time_div").hide();

        show_or_hide_sending_time_when();
    }

    var handleWysihtml5 = function() {
        if (!jQuery().wysihtml5) {
          return;
        }

        if ($('.wysihtml5').size() > 0) {
          $('.wysihtml5').wysihtml5({
            "stylesheets": ["../../assets/global/plugins/bootstrap-wysihtml5/wysiwyg-color.css"]
          });
        }
    }

    // previewing content when the Preview button is clicked
    $(".view_preview").click( function()
    {
      var content = tinyMCE.activeEditor.getContent();

      if ( $.trim(content) == "" )
      {
        content = '<html><head><title></title></head><body>Please fill in html text</body></html>'
      }
      
      var doc = document.getElementById('preview-content').contentWindow.document;
      doc.open('text/htmlreplace');
      doc.write( content );
      doc.close();
    });

    // in Add mode, when send button is clicked
    $("#form_add_newsletter .send_now").click(function()
    {
      var confirmation_msg = "";

      if ($("input[name=sending_time_when]:checked").val() == "later")
        confirmation_msg = "Are you sure to send this newsletter according to the schedule you set?";
      else
      if ($("input[name=sending_time_when]:checked").val() == "now")
        confirmation_msg = "Are you sure to send this newsletter now?";

      if ( confirm(confirmation_msg) )
      {
        $("#status").val("1");
        $("#form_add_newsletter").submit();
      }
    });

    // in Edit mode, when send button is clicked
    $("#form_edit_newsletter .send_now").click(function()
    {
      if ($("input[name=sending_time_when]:checked").val() == "later")
        confirmation_msg = "Are you sure to send this newsletter according to the schedule you set?";
      else
      if ($("input[name=sending_time_when]:checked").val() == "now")
        confirmation_msg = "Are you sure to send this newsletter now?";

      if ( confirm(confirmation_msg) )
      {
        $("#status").val("1");
        $("#form_edit_newsletter").submit();
      }
    });

    // when save as draft is clicked
    $("#form_add_newsletter .save_draft").click(function()
    {
      $("#status").val("0");
      $("#form_add_newsletter").submit();
    });

    $("#form_edit_newsletter .save_draft").click(function()
    {
      $("#status").val("0");
      $("#form_edit_newsletter").submit();
    });

    // when pressing cancel, go back to previous page
    $(".newsletter_cancel").click(function()
    {
      history.go(-1);
    });

    // when the All button besides the label category is clicked
    $("#all_categories_btn").click(function()
    {
      var arr_categories = [];
      var i = 0;

      $('#category_ids option').each(function() 
      {
        arr_categories[i] = $(this).val();
        i++;
      });

      $("#category_ids").select2("val", arr_categories);
    });

    // if the sending time ( now or later ) is clicked
    $('input[name=sending_time_when]').change(function()
    {
      change_send_text();
      show_or_hide_sending_time_when();
    });

    // when people choose certain template
    $("#template_id").change(function()
    {
      var template_id = $("#template_id").val();

      if ( template_id == "0" )
      {
        //$("#content").val("");
        tinyMCE.activeEditor.setContent("");
        $("#attachments_div").html("");
      }
      else
      {
        var content = $("#hid_template_content_"+template_id).val();
        content = stripslashes( rawurldecode( content ) );
        //$("#content").val( content );
        tinyMCE.activeEditor.setContent(content);

        // get attachment according to selected template
        $.ajax({
          type: "GET",  
          url: '/newsletters/get_attachments',
          data: "template_id="+template_id,
          timeout: 30000,
          success: function( resp )
          {
            $("#attachments_div").html(resp); 
          },  
          error: function(e){  
            
          } 
        });
      }
    });

    return {
        //main function to initiate the module
        init: function () {

          handleWysihtml5();
          /*
          handleValidation1();
          handleValidation2();
          handleValidation3();
          */

          handleNewsletterValidation();
        }

    };

}();

function change_send_text()
{
  if ($("input[name=sending_time_when]:checked").val() == "later")
  {
    $(".send_now").html("Send Later By Schedule");
  }
  else
  if ($("input[name=sending_time_when]:checked").val() == "now")
  {
    $(".send_now").html("Send Now");
  }
}

function show_or_hide_sending_time_when()
{
  if ($("input[name=sending_time_when]:checked").val() == "later")
  {
    $("#sending_time_div").show();
  }
  else
  if ($("input[name=sending_time_when]:checked").val() == "now")
  {
    $("#sending_time_div").hide();
  }
}

function insert_img_tag_into_textarea( media_link )
{
  var chosen_media = '<img src="'+media_link+'" alt="" border="0" />';

  // get current cursor position
  var position = $("#content").getCursorPosition();

  // get current content for textarea
  var content = $("#content").val();

  var newContent = content.substr(0, position) + chosen_media + content.substr(position);
  $('#content').val(newContent);

  $("#insert-image").modal('hide');
}

function insert_video_tag_into_textarea( media_link )
{
  // get current content for textarea
  var content = $("#content").val();
  var added_media = '<attachment>' + media_link + '</attachment>';

  var newContent = content + added_media;
  $('#content').val(newContent);

  $("#attach-video").modal('hide');
}

function insert_document_tag_into_textarea( media_link, media_name, media_id )
{
  var attachment_template = '<div class="alert alert-block alert-success fade in attachment">\
      <button type="button" class="close" data-dismiss="alert"></button>\
      <input type="hidden" name="attachment_ids[]" value="'+media_id+'" />\
      <a href="'+media_link+'">'+media_name+'</a>\
    </div>';
  
  $("#attachments_div").append( attachment_template );
  $("#attach-document").modal('hide');
}

function append_attachment_callback( url )
{
  var last_slash_pos = strrpos(url, "/", 0);  
  var filename = url.substring( last_slash_pos + 1);

  var attachment_template = '<div class="alert alert-block alert-success fade in attachment">\
    <button type="button" class="close" data-dismiss="alert"></button>\
    <input type="hidden" name="attachments[]" value="'+rawurlencode(url)+'" />\
    <a href="'+url+'">'+filename+'</a>\
  </div>';
  
  $("#attachments_div").append( attachment_template );
}
