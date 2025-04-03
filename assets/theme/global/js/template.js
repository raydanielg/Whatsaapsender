
(function($){
    "use strict";

  var bee;
  const modal = $('#globalModal');
  function loadTemplate(templateId = null) {
    $('.bee-plugin-preview').removeClass('d-none')
    $('#save-button').addClass('d-none')
    let baseUrl = $("meta[name=base-url]").attr("content");
    $("#bee-plugin-container").html("");
    $("#preview").hide(200);
    var endpoint = $("meta[name=bee-endpoint]").attr("content");
    var config = {
        uid: "demo_id_1",
        container: "bee-plugin-container",
        onSave: function (jsonFile, htmlFile) {
            
            if (modal) {
                modal.modal('hide')
            }
            $("#bee_template_json").val(jsonFile);
            $("#bee_template_html").val(htmlFile);
            $('#save-button').removeClass('d-none')
            $(".bee-plugin-preview").addClass('d-none');
            $("#html-image-data").html(htmlFile);
            $("#template-editor").hide();
            $("#preview").show(200);
        },
        onAutoSave: function (jsonFile, htmlFile) {
           
        },
        onSaveAsTemplate: function (jsonFile) {
            saveAs(
                new Blob([jsonFile], {
                    type: "text/plain;charset=utf-8",
                }),
                "test.json"
            );
        },
        onSend: function (htmlFile) {
     
        },
    };
    var payload = {
        client_id:  $("meta[name=bee-client-id]").attr("content"),
        client_secret: $("meta[name=bee-client-secret]").attr("content"),
        grant_type: "password",
    };

    $.post(endpoint, payload).done(function (data) {
        var token = data;
        window.BeePlugin.create(token, config, function (instance) {
            bee = instance;
          
            $.get(
                `${baseUrl}/admin/template/email/get/${templateId}`,
                function (template) {
                    bee.start(template);
                }
            );
        });
    });
}


  //choose a new teamplate
  $(document).on("change", "#choose-template", function () {

          loadTemplate($(this).val());
  });

  //edit a  template
  $(document).on("click", "#edit-template", function () {
    // $("#template-editor").show(200);
    if (modal) {
        modal.modal('show')
    }
    $("#preview-title").hide();
    $("#html-image-data").html("");
});
})(jQuery);