<script type="text/javascript">
     var email_verification_message = '';
 
     function verifyEmailAjax(url, email, callback) {
         return $.ajax({
             url: url,
             method: 'POST',
             data: {
                 email: email,
                 _token: '{{ csrf_token() }}'
             },
             success: function(response) {
                email_verification_message = response.message;
                if (typeof callback === 'function') {
                    callback(response);
                }
             },
             error: function(xhr) {
                 email_verification_message = `{{translate("An error occurred during email verification.")}}`;
             }
         });
     }
 </script>