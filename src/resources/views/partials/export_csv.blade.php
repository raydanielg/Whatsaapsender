<script type="text/javascript">
    $(document).ready(function() {

        $('.export-csv').on('click', function(e) {

            e.preventDefault();
            var button = $(this);
            var icon = button.find('i');
            var originalIconClass = icon.attr('class');
            
            icon.removeClass().addClass('spinner-border spinner-border-sm');
            button.prop('disabled', true);

            var formData = $('form').serialize();
            var data_config = {!! json_encode($csv_data['parameters']) !!};
            formData += '&data_config=' + encodeURIComponent(JSON.stringify(data_config));

            $.ajax({
                url: '{{ $csv_data["url"] }}',
                method: '{{ $csv_data["method"] }}',
                data: formData,
                xhrFields: {
                    responseType: 'blob'  
                },
                success: function(blob, status, xhr) {

                    var statusHeader  = xhr.getResponseHeader('X-Status');
                    var messageHeader = xhr.getResponseHeader('X-Message');
                    
                    if (statusHeader && statusHeader === 'true') {

                        notify('success', messageHeader || 'File successfully generated!');

                        var link = document.createElement('a');
                        var url = window.URL.createObjectURL(blob);
                        link.href = url;
                        var fileName = xhr.getResponseHeader('X-Filename') || 'contact_export.csv';
                        link.download = fileName; 
                        link.click();
                        window.URL.revokeObjectURL(url); 

                    } else {
                        
                        notify('error', messageHeader || "{{translate('An error occurred while exporting the file.')}}");
                    }
                },
                error: function(xhr) {

                    var response = xhr.responseJSON;

                    if (response && response.status === false) {
                        notify('error', response.message);
                    } else {
                        notify('error', 'Export failed: ' + xhr.statusText);
                    }
                },
                complete: function() {
                    icon.removeClass().addClass(originalIconClass);
                    button.prop('disabled', false);
                }
            });
        });
    });
</script>
