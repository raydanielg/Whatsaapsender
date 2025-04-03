"use strict";

var dir = document.documentElement.getAttribute('dir') || 'ltr';
var editors = {};

//Ck editor 
function ck_editor(textarea) {  
    
    class MinHeightPlugin {
      constructor(editor) {
          this.editor = editor;
      }

      init() {
        this.editor.ui.view.editable.extendTemplate({
            attributes: {
                style: {
                    minHeight: '300px'
                }
            }
        });
      }
    }
    CKEDITOR.ClassicEditor.builtinPlugins.push(MinHeightPlugin);
    CKEDITOR.ClassicEditor.create(document.querySelector(textarea), {
        placeholder: document.querySelector(textarea).getAttribute("placeholder"),
        
        toolbar: {
          items: [
            'heading',
            'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
            'alignment', '|',
            'bold', 'italic', 'strikethrough', 'underline', 'subscript', 'superscript', 'removeFormat', 'findAndReplace', '-',
            'bulletedList', 'numberedList', '|',
            'outdent', 'indent', '|',
            'undo', 'redo',
            'link', 'insertImage', 'blockQuote', 'insertTable', 'mediaEmbed', '|',
            'horizontalLine', 'pageBreak', '|',
            'sourceEditing'
          ],
          shouldNotGroupWhenFull: true
        },
        list: {
          properties: {
            styles: true,
            startIndex: true,
            reversed: true
          }
        },

        heading: {
          options: [
            { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
            { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
            { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
            { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
            { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
            { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
            { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
          ]
        },
        fontFamily: {
          options: [
            'default',
            'Arial, Helvetica, sans-serif',
            'Courier New, Courier, monospace',
            'Georgia, serif',
            'Lucida Sans Unicode, Lucida Grande, sans-serif',
            'Tahoma, Geneva, sans-serif',
            'Times New Roman, Times, serif',
            'Trebuchet MS, Helvetica, sans-serif',
            'Verdana, Geneva, sans-serif'
          ],
          supportAllValues: true
        },
        fontSize: {
          options: [10, 12, 14, 'default', 18, 20, 22],
          supportAllValues: true
        },
        htmlSupport: {
          allow: [
            {
              name: /.*/,
              attributes: true,
              classes: true,
              styles: true
            }
          ]
        },
        htmlEmbed: {
          showPreviews: true
        },
        link: {
          decorators: {
            addTargetToExternalLinks: true,
            defaultProtocol: 'https://',
            toggleDownloadable: {
              mode: 'manual',
              label: 'Downloadable',
              attributes: {
                download: 'file'
              }
            }
          }
        },
        mention: {
          feeds: [
            {
              marker: '@',
              feed: [
                '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
                '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread', '@gummi', '@ice', '@jelly-o',
                '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding', '@sesame', '@snaps', '@soufflé',
                '@sugar', '@sweet', '@topping', '@wafer'
              ],
              minimumCharacters: 1
            }
          ]
        },
        removePlugins: [
          'CKBox',
          'CKFinder',
          'EasyImage',
          'RealTimeCollaborativeComments',
          'RealTimeCollaborativeTrackChanges',
          'RealTimeCollaborativeRevisionHistory',
          'PresenceList',
          'Comments',
          'TrackChanges',
          'TrackChangesData',
          'RevisionHistory',
          'Pagination',
          'WProofreader',
          'MathType'
        ]
    })
    .then(editor => {
      editors[textarea] = editor;
    })
    .catch(error => {
        console.error(error);
    });
}

//select two
function select2_search(placeholder_message, modal = null, tags = false) {
    
    $('.select2-search').select2({
        
        placeholder: placeholder_message,
        allowClear: false,
        dropdownAutoWidth: true,
        width: '100%',
        minimumResultsForSearch: 0,
        dir: dir,
        tags: tags, 
        dropdownParent: modal
    
    }).on('select2:open', function() {
    
        let maxItems = parseInt($(this).attr('data-show'), 10) || 10;
        $('.select2-results__options').css('max-height', `${maxItems * 40}px`); 
    });
}
    
//switch background update

function updateBackgroundClass() {

    $('.form-inner-switch').each(function() {

      const formInnerSwitch = $(this);
      const $checkbox = formInnerSwitch.closest('.form-inner').find('.switch-input');
  
    });
}

//Activate children checkbox fields

function setInitialVisibility() {
    
    if ($('.parent input[type="checkbox"]').is(':checked')) {

        $('.child').show().removeClass('d-none');
    } else {

        $('.child').hide().addClass('d-none');
    }
}

function toggleChildren() {

    if ($('.parent input[type="checkbox"]').is(':checked')) {

        $('.child').fadeIn(500).removeClass('d-none');
    } else {

        $('.child').fadeOut(500, function() {

            $(this).addClass('d-none');
        });
    }
}

//Copy text
function copy_text(button, message) {

    var inputField = button.closest('.input-group').find('.form-control').get(0); 
    var val = inputField.value; 

    navigator.clipboard.writeText(val)
    .then(function() {
        notify("success", message);
    })
    .catch(function(error) {
        console.error('Unable to copy text: ', error);
        notify("error", "Failed to copy text!");
    });
}

//Generate Random String 
function generateRandomString(length) {

    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var result = '';
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * characters.length));
    }
    notify("info", "Code successfully generated.");
    return result;
}

//Text Formatting 
function textFormat(symbols, data, replaceWith) {
  
  symbols = symbols || [];
  replaceWith = replaceWith || ' ';

  var convertedString = data.replace(new RegExp(symbols.join('|'), 'g'), replaceWith)
                            .toLowerCase()
                            .replace(/(?:^|\s)\S/g, function(a) {
                                return a.toUpperCase();
                            });

  return convertedString;
}


//Bulk Action 

$(document).on('click', '.check-all', function(e) {

    if ($(this).is(':checked')) {

        $('.data-checkbox').prop('checked', true);
        $('.bulk-action').removeClass('d-none');
    } else {

        $('.data-checkbox').prop('checked', false);
        $('.bulk-action').addClass('d-none');
    }
    checkCheckboxEvent('.data-checkbox', '.check-all');
});

$(document).on('click', '.data-checkbox', function(e) {

    checkCheckboxEvent('.data-checkbox', '.check-all');
});

$('#bulk_status').on('change', function() {

    const selectedStatus = $(this).val();
    const modal = $('#bulkAction');
    const form = modal.find('form');
    form.find('input[name="ids[]"], input[name="status"], input[name="type"]').remove();
    $('.data-checkbox:checked').each(function() {
      
        $('<input>').attr({
            type: 'hidden',
            name: 'ids[]',
            value: $(this).val()
        }).appendTo(form);
    });

    $('<input>').attr({

        type: 'hidden',
        name: 'status',
        value: selectedStatus
    }).appendTo(form);

    $('<input>').attr({

        type: 'hidden',
        name: 'type',
        value: 'status'
    }).appendTo(form);

    modal.modal('show');
});

$(document).on('click', '.bulk-delete-btn', function(e) {

    const modal = $('#bulkAction');
    const form = modal.find('form');
    form.find('input[name="ids[]"], input[name="status"], input[name="type"]').remove();
    $('.data-checkbox:checked').each(function() {

        $('<input>').attr({
            type: 'hidden',
            name: 'ids[]',
            value: $(this).val()
        }).appendTo(form);
    });

    $('<input>').attr({

        type: 'hidden',
        name: 'type',
        value: 'delete'
    }).appendTo(form);
    modal.modal('show');
});

function checkCheckboxEvent(checkboxSelector, checkAllSelector) {

    const totalCheckboxes   = $(checkboxSelector).length;
    const checkedCheckboxes = $(checkboxSelector + ':checked').length;

    if (checkedCheckboxes === totalCheckboxes) {

        $(checkAllSelector).prop('checked', true);
    } else {

        $(checkAllSelector).prop('checked', false);
    }

    if (checkedCheckboxes > 0) {

        $('.bulk-action').removeClass('d-none');
    } else {
      
        $('.bulk-action').addClass('d-none');
    }
}

$(document).on('click','.generate-token' ,function(e) {

  e.preventDefault();
  var randomString = generateRandomString(32);
  $('.verify_token').val(randomString);
});

$('.back-to-menu').on('click', function() {
  $('html').removeClass('menu-active');
  $('.sub-menu-wrapper').removeClass('show');
});

/**
 * Format a number with the specified precision and add suffixes for large numbers.
 * 
 * @param {number} number - The number to format.
 * @param {number} precision - The number of decimal places to round to.
 * @returns {string} - The formatted number.
 */
function formatNumber(number, precision = 2) {

  if (isNaN(number)) {

      return "Invalid number format";
  }

  number = parseFloat(number);
  const negative = number < 0;
  number = Math.abs(number);

  if (number < 1000) {
      return (negative ? "-" : "") + number.toFixed(precision);
  }

  const formatters = {
      1e24: ['Y', 1e24],
      1e21: ['Z', 1e21],
      1e18: ['E', 1e18],
      1e15: ['P', 1e15],
      1e12: ['T', 1e12],
      1e9: ['B', 1e9],
      1e6: ['M', 1e6],
      1e3: ['K', 1e3]
  };

  for (const [divisor, [suffix, value]] of Object.entries(formatters)) {
      if (number >= value) {
          const formattedNumber = (number / value).toFixed(precision);
          return (negative ? "-" : "") + formattedNumber + suffix;
      }
  }
  
  return (negative ? "-" : "") + number.toFixed(precision);
}







