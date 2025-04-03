"use strict";

    const button_type = [
        "ACTION", 
        "COUPON_CODE", 
        "CURRENCY", 
        "DATE_TIME", 
        "DOCUMENT", 
        "EXPIRATION_TIME_MS", 
        "IMAGE", 
        "LIMITED_TIME_OFFER", 
        "LOCATION", 
        "ORDER_STATUS", 
        "PAYLOAD", 
        "TEXT", 
        "VIDEO", 
        "WEBVIEW_PRESENTATION"
    ];

    var allTemplates = [];
    toggleWhatsAppMode();
            
    if ($('.whatsapp_business_api option:selected').is(':disabled')) {

        $('.select-cloud-templates').addClass('d-none');
    }
            
    $('#whatsapp_sending_mode').change(function () {

        toggleWhatsAppMode();

        $('.select-cloud-templates').addClass('d-none');
        
        if( $('.whatsapp_business_api option:selected').is(':disabled')) {
            
            $('.select-cloud-templates').addClass('d-none');
        }
    });

    function toggleWhatsAppMode() {

        $('.whatsapp-cloud-steps .whatsapp-tabs').empty();
        $('.whatsapp-cloud-steps .whatsapp-tab-content').empty();
        $('.whatsapp_business_api').val($('.whatsapp_business_api option:first').val());
        $(".with-cloud-message").find(".old-template-fields").removeClass('d-none');
        $('.template-fields').empty();
        var modeName = $('#whatsapp_sending_mode').val();
        if (modeName == "cloud_api") {
            
            $('.whatsapp_device_select_label').text("Choose Business Account from the list");
        } else {

            $('.whatsapp_device_select_label').text("Choose a device from the list");
        }

        if (modeName == "without_cloud_api") {

            $('.select-cloud-templates').addClass('d-none');
            $('.whatsapp-cloud-steps').addClass('d-none');
            $('.with-cloud-message').addClass('d-none');
            $('.without-cloud-message').removeClass('d-none');
            $('.whatsapp_business_api').addClass('d-none');
            $('.whatsapp_node_devices').removeClass('d-none');
            $('input[name="whatsapp_mode"]').val('true');
            $('input[name="cloud_api"]').val('false'); 
            $('input[name="without_cloud_api"]').val('true');

        } else {
            $('.without-cloud-message').addClass('d-none');
            $('.with-cloud-message').removeClass('d-none');
            $('.whatsapp_node_devices').addClass('d-none');
            $('.whatsapp_business_api').removeClass('d-none');
            $('.select-cloud-templates').removeClass('d-none');
            $('input[name="whatsapp_mode"]').val('false');
            $('input[name="cloud_api"]').val('true'); 
            $('input[name="without_cloud_api"]').val('false');
        }
    }
            
    $('#whatsapp_template_id').change(function () {

        $('.whatsapp-cloud-steps').removeClass('d-none');
        $('.whatsapp-cloud-steps .whatsapp-tabs').empty();
        $('.whatsapp-cloud-steps .whatsapp-tab-content').empty();
        var selectedTemplateId = $(this).val(); 
        
        var selectedTemplate   = allTemplates.find(function (template) {

            return template.id == selectedTemplateId;
        });

        $('.whatsapp-tabs').empty();
        $('.whatsapp-tab-content').empty();
        
        $.each(selectedTemplate.template_data.components, function(index, element) {
           
            var tabId = element.type.toLowerCase() + "-tab-pane";
            var tabLabel = element.type.charAt(0).toUpperCase() + element.type.slice(1);
            var $tabButton = $('<a>')
                .addClass('nav-link')
                .attr({
                    'id': tabId + '-tab',
                    'data-bs-toggle': 'tab',
                    'href': '#' + tabId,
                    'role': 'tab',
                    'aria-controls': tabId,
                    'aria-selected': (index === 0) 
                })
                .html('<i class="ri-bar-chart-horizontal-line"></i> ' + tabLabel);

            var $tabContent = $('<div>')
                .addClass('tab-pane fade')
                .attr({
                    'id': tabId,
                    'role': 'tabpanel',
                    'aria-labelledby': tabId + '-tab',
                    'tabindex': '0'
                });

            if (element.type == "BUTTONS") {

                const $buttonContainer = $('<div>').addClass('button-container');
                
                $.each(element.buttons, function(buttonIndex, button) {
                    
                    var $buttonInput = createInputGroup(textFormat(["_"], button.type), button);
                    $buttonContainer.append($buttonInput);
                    
                    if(button.type == "QUICK_REPLY") {

                        var maxLengthButton = 25; 
                        var buttonValue = button.text;
                        const $buttonInput = $('<div>').addClass('col-md-12 mt-3 variables').append(
                            $('<textarea>').addClass('form-control').attr({
                                name: `${button.type.toLowerCase()}_button_${buttonIndex}`,
                                id: `${button.type.toLowerCase()}_button_${buttonIndex}`,
                                placeholder: `Enter value for quick reply button text`,
                                maxlength: maxLengthButton
                            }).val(buttonValue)
                        );
                        $buttonContainer.append($buttonInput);
                    }
                    if(button.type == "COPY_CODE") {

                        var maxLengthButton = 25; 
                        var buttonValue = button.example[0];
                        const $buttonInput = $('<div>').addClass('col-md-12 mt-3 variables').append(
                            $('<textarea>').addClass('form-control').attr({
                                name: `${button.type.toLowerCase()}_button_${buttonIndex}`,
                                id: `${button.type.toLowerCase()}_button_${buttonIndex}`,
                                placeholder: `Enter value for copy code button text`,
                                maxlength: maxLengthButton
                            }).val(buttonValue)
                        );
                        $buttonContainer.append($buttonInput);
                    }

                    if(button.hasOwnProperty("example") && button.type == "URL") {
                        
                        var maxLengthButton = 50; 
                        var buttonValue = button.example[0];
                        const $buttonInput = $('<div>').addClass('col-md-12 mt-3 variables').append(
                            $('<textarea>').addClass('form-control').attr({
                                name: `${button.type.toLowerCase()}_button_${buttonIndex}`,
                                id: `${button.type.toLowerCase()}_button_${buttonIndex}`,
                                placeholder: `Enter value for your url`,
                                maxlength: maxLengthButton
                            }).val(buttonValue+button.text)
                        );
                    
                        $buttonContainer.append($buttonInput);
                    
                        const textarea = $buttonInput.find('textarea')[0];
                        const initialText = buttonValue;
                        textarea.selectionStart = textarea.selectionEnd = textarea.value.length;
                    
                        $(textarea).on('input', function() {
                            if (!textarea.value.startsWith(initialText)) {
                                
                                textarea.value = initialText;
                                textarea.selectionStart = textarea.selectionEnd = textarea.value.length;
                            }
                        });
                    
                        $(textarea).on('keydown', function(event) {
                            if (textarea.selectionStart < initialText.length && event.key !== 'ArrowLeft' && event.key !== 'ArrowRight') {
                                
                                event.preventDefault();
                                textarea.selectionStart = textarea.selectionEnd = textarea.value.length;
                            }
                        });
                    }
                });

                $tabContent.append(
                    $('<div>').addClass('row g-4').append(
                        $('<div>').addClass('col-12').append(
                            $('<div>').addClass('form-element').append(
                                $('<div>').addClass('button-fields').append(
                                    $buttonContainer
                                )
                            )
                        )
                    )
                );
                
            } else {
                var inputGroup = createInputGroup(textFormat(["_"], element.type), element);
                $tabContent.append(inputGroup);
            }
            
            $('.whatsapp-tabs').append($('<li>').addClass('nav-item').append($tabButton));
            $('.whatsapp-tab-content').append($tabContent);
        });
        $('.whatsapp-tabs li:first-child button').tab('show');

        const headerInput = $('textarea[name="header_cloud"]');
        const bodyInput = $('textarea[name="body_cloud"]');
        const footerInput = $('textarea[name="footer_cloud"]');
        
        $('.whatsapp-message-preview .message-header').html(headerInput.val());
        $('.whatsapp-message-preview .message-body').html(bodyInput.val());
        $('.whatsapp-message-preview .message-footer').html(footerInput.val());
    });    

    function createInputGroup(type, data) {

        $(".with-cloud-message").find(".old-template-fields").addClass('d-none');

        let input;
        const inputGroup           = $('<div>').addClass('form-element');
        const label                = $('<label>').addClass('form-label').text(`${type} Input`);
        const placeholderContainer = $('<div>').addClass('row');
        const placeholders         = (data.text || '').match(/\{\{\d+\}\}/g) || [];
        const maxLength            = (type === 'Header' || type === 'Footer') ? 60 : (type === 'Buttons' ? 25 : undefined);

        if (placeholders.length > 0) {

            placeholders.forEach((placeholder, index) => {

                const textareaId = `${type.toLowerCase()}_placeholder_${index}`;

                let placeholderValue;

                if (data.example && data.example[type.toLowerCase() + '_text']) {

                    const exampleText = data.example[type.toLowerCase() + '_text'];

                    if (Array.isArray(exampleText) && exampleText.length > 0 && Array.isArray(exampleText[0])) {

                        placeholderValue = exampleText[0][index];
                    } else if (exampleText[index]) {

                        placeholderValue = exampleText[index];
                    }
                }
                const $placeholderInput = $('<div>').addClass('col-md-12 mt-3 variables').append(
                    $('<label>').addClass('form-label').attr({
                        for: textareaId,
                    }).text('{{' + (index + 1) + '}}'),

                    $('<textarea>').addClass('form-control').attr({
                        name: `${type.toLowerCase()}_placeholder_${index}`,
                        id: textareaId,
                        placeholder: `Enter value for ${placeholder}`,
                        colspan: type === 'Header' ? '1' : undefined,
                        maxlength: maxLength
                    }).val(placeholderValue),
                );
                placeholderContainer.append($placeholderInput);
            });
        }

        $(".cloudMediaLabel").click(function () { 

            whatsAppFileInputAttribute();
        });

        function getAcceptAttribute(format) {
            switch (format.toLowerCase()) {
                case 'image':
                    return 'image/*';
                case 'document':
                    return '.pdf,.doc,.docx';
                case 'video':
                    return 'video/*';
                default:
                    return '';
            }
        }

        function whatsAppFileInputAttribute() {
        
            $('#cloudUploadFile input[type="file"]').val('');
            $('#cloudUploadFile input[type="file"]').attr({
                'name': '',
                'id': 'cloudMediaUpload',
                'accept': getAcceptAttribute(format)
            });
        }

        if (type === 'Body' || type === 'Footer' || type === 'Header' || type === 'Buttons') {
            
            if (data.format === 'IMAGE' || data.format === 'DOCUMENT' || data.format === "VIDEO") {
                
                const mediaUploadLabel = $('<label>').addClass('cloudMediaLabel').attr('for', 'cloudMediaUpload');
                
                const mediaUploadInput = $('<input>').attr({
                    type: 'file',
                    name: `${data.format.toLowerCase()}_${type.toLowerCase()}_media`, 
                    id: 'cloudMediaUpload',
                    hidden: true,
                    accept: data.format.toLowerCase() === 'image' ? 'image/*' : (data.format.toLowerCase() === 'document' ? '.pdf,.doc,.docx' : 'video/*')
                });
                const mediaUploadButton = $('<div>').addClass('i-btn light--btn btn--sm ms-3').html('Add Media <i class="ri-attachment-line"></i>');

                mediaUploadLabel.append(
                    $('<div>').attr('id', 'cloudUploadFile').append(mediaUploadInput),
                    mediaUploadButton
                );

                input = mediaUploadLabel;
                mediaUploadInput.on('change', function() {
                    const file = this.files[0];
                    if (file) {
                        mediaUploadButton.text(file.name);
                    } else {
                        mediaUploadButton.html('Add Media <i class="ri-attachment-line"></i>');
                    }
                });

            } else {
                input = $('<textarea>').addClass('form-control').attr({
                    readonly: true,
                    name: type.toLowerCase()+'_cloud',
                    id: type.toLowerCase(),
                    placeholder: `Enter your ${type.toLowerCase()} text`,
                    style: type === 'Body' ? 'height:100px' : undefined
                }).text(data.text);
            }
            inputGroup.append(label, input);

        } else {
            
            input = $('<input>').addClass('form-control').attr({
                type: 'text',
                value: data.text,
                name: type.toLowerCase(),
                id: type.toLowerCase(),
                placeholder: data.text,
                'aria-label': 'number',
                'aria-describedby': 'basic-addon11',
                disabled: true
            });
            inputGroup.append(label, input);

            
        }

        if (placeholders.length > 0) {
            inputGroup.append(placeholderContainer);
        }

        return inputGroup;
    }

    // Listen for input events and update the preview
    $(document).on('keyup', '.whatsapp-tab-content textarea', function() {

        const headerInput = $('textarea[name="header_cloud"]');
        const bodyInput = $('textarea[name="body_cloud"]');
        const footerInput = $('textarea[name="footer_cloud"]');
        const type             = $(this).attr('name').split('_')[0];
        const value            = $(this).val();
        const previewElement   = $('.whatsapp-message-preview .message-' + type.toLowerCase());
        let previewText        = previewElement.text();
        whatsAppPreview($(this), type, value, previewElement, previewText);

        if (!value.trim()) {
            if (type == "header") {

                previewElement.text(headerInput.val());

            } else if (type == "body") {

                previewElement.text(bodyInput.val());
                // whatsAppPreview($(this), type, value, previewElement, previewText);
            } else {

                previewElement.text(footerInput.val());
            }
        }
    
        $(this).data('previous-value', value);
    });

    function whatsAppPreview(data, type, value, previewElement, previewText) {
      
        const placeholderMatch = data.attr('placeholder').match(/\{\{\d+\}\}/);
        const placeholder      = placeholderMatch ? placeholderMatch[0] : null;
        
    
        if (previewText && placeholder) {
           
            if (previewText.includes(placeholder)) {

                const regex = new RegExp(placeholder.replace(/[{}]/g, '\\$&'), 'g');
                previewText = previewText.replace(regex, value);
            } else {

                const previousValue = data.data('previous-value') || '';
                previewText = previewText.replace(previousValue, value);
                
            }
            previewElement.text(previewText);
            
        } 
    }

//End Cloud API

//Node Module Or Without CLoud API

    $(".media_upload_label").click(function () { 
        
        setDefaultFileInputAttributes();
    });

    $("#media_upload").change(function () {
        
        $('#add_media').removeClass('d-none');
        var file            = this.files[0];
        var formattedDate   = formatDate(file.lastModifiedDate);
        var fileDetailsHTML = '<div class="d-flex align-items-center justify-content-between gap-3">' +
            '<div class="d-flex align-items-center gap-3">' +
            getFileIcon(file.type) +
            '<div class="file-info file-type">' +
            "<p title='"+ file.name +"'><small>" + "File Name: " + file.name + '</small></p>' +
            "<p title='"+ file.type +"'><small>" + "File Type: " + file.type + '</small></p>' +
            "<p title='"+ bytesToSize(file.size) +"'><small>" + "File Size: " + bytesToSize(file.size) + '</small></p>' +
            '</div>' +
            '</div>' +
            ` <div class="file-preview-actions">
                        <button type="button" class="icon-btn btn-sm danger-soft hover circle remove__file">
                          <i class="ri-delete-bin-line"></i>
                        </button>
                      </div>`+
            '</div>';
        
        $("#add_media").html(fileDetailsHTML);

        var fileType = getFileType(file.type);

        setFileInputAttributes(fileType);

        if (fileType === 'image') {

            displayImagePreview(file);
        }

        $('.remove__file').click(function (e) {

            e.preventDefault();
            $("#add_media").html('');
            $('#add_media').addClass('d-none');
            setDefaultFileInputAttributes();
        });
    });

    function setDefaultFileInputAttributes() {
       
        $('#uploadfile input[type="file"]').val('');
        $('#uploadfile input[type="file"]').attr({
            'name': '',
            'id': 'media_upload',
            'accept': ''
        });
        $(".media_upload_label").attr('for', 'media_upload');
    }

    function bytesToSize(bytes) {

        var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        if (bytes == 0) return '0 Byte';
        var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
        return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
    }

    function formatDate(date) {

        var options = {
            weekday : 'short',
            year    : 'numeric',
            month   : 'short',
            day     : 'numeric',
            hour    : 'numeric',
            minute  : 'numeric',
            second  : 'numeric',
        };
        return date.toLocaleString('en-US', options);
    }

    function    getFileIcon(fileType) {
        
        switch (getFileType(fileType)) {
            case 'image':
                return '<span class="image__preview preview-image"><img src="" alt=""></span>';
            case 'audio': 
                return '<i class="ri-folder-music-line"></i>';
            case 'video':
                return '<i class="ri-video-chat-line"></i>';
            default:
                return '<i class="ri-file-line"></i>';
        }
    }

    function getFileType(fileType) {

        if (fileType.startsWith('image/')) {
            return 'image';
        } else if (fileType.startsWith('audio/')) {
            return 'audio';
        } else if (fileType.startsWith('video/')) {
            return 'video';
        } else if (fileType === 'application/pdf' || fileType === 'application/msword' || fileType === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
            return 'document';
        } else {
            return 'other';
        }
    }

    function setFileInputAttributes(fileType) {

        var fileInput = $('#media_upload');

        switch (fileType) {

            case 'video':

                fileInput.attr({
                    'name'  : 'video',
                    'id'    : 'video',
                    'accept': '.mp4,.mov,.avi'
                });
                break;
            case 'audio':

                fileInput.attr({
                    'name'  : 'audio',
                    'id'    : 'audio',
                    'accept': '.mp3,.wav'
                });
                break;
            case 'document':

                fileInput.attr({
                    'name'  : 'document',
                    'id'    : 'document',
                    'accept': '.doc,.docx,.pdf'
                });
                break;
            case 'image':

                fileInput.attr({
                    'name'  : 'image',
                    'id'    : 'image',
                    'accept': '.jpg,.jpeg,.png,.gif'
                });
                break;
            default:

                fileInput.attr({
                    'name' : '',
                    'id'   : 'media_upload'
                });
                break;
        }

        var labelFor = fileInput.attr('id');
        $(".media_upload_label").attr('for', labelFor);

    }

    function displayImagePreview(file) {

        var reader    = new FileReader();
        reader.onload = function (e) {

            $('.image__preview img').attr('src', e.target.result);
        };
        reader.readAsDataURL(file);
    }  





$('.style-link').on('click', function (e) {

    e.preventDefault();

    var style        = $(this).data('style');
    var textarea     = $('#message')[0];
    var selectedText = textarea.value.substring(textarea.selectionStart, textarea.selectionEnd);

    if (selectedText.trim() === '') {
        return;
    }

    var startChar = '';
    var endChar   = '';

    switch (style) {

        case 'bold' :

            startChar = '*';
            endChar   = '*';
            break;
        case 'italic' :

            startChar = '_';
            endChar   = '_';
            break;
        case 'mono' :

            startChar = '```';
            endChar   = '```';
            break;
        case 'strike' :

            startChar = '~';
            endChar   = '~';
            break;
    }

    var startOffset  = textarea.selectionStart;
    var endOffset    = textarea.selectionEnd;
    var modifiedText = startChar + selectedText + endChar;
    textarea.setRangeText(modifiedText, startOffset, endOffset, 'end');
    textarea.setSelectionRange(startOffset + startChar.length, startOffset + startChar.length + selectedText.length + endChar.length);
});



