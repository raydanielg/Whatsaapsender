<script type="text/javascript">
     let verification_complete = false;
     let isVerifying = false;
     let originalEmail = $('.check-email-address').val();
     let url = $('.check-email-address').attr('data-url');
     function verifyEmailHandler() {
          var emailInput = $('.check-email-address').val();
          if (isVerifying || emailInput.trim() === '' || verification_complete) return;

          isVerifying = true;
          $('#verify_email_text').addClass('d-none');
          $('#loading_spinner').removeClass('d-none');

          verifyEmailAjax(url, emailInput, function(response) {
               
               const emailVerificationStatus = response?.status ? "success" : "error";
               console.log(emailVerificationStatus);
               
               const emailVerificationMessage = response?.message;
               var verifyButton = $('#verify_email_button');
               $('#loading_spinner').addClass('d-none');
               verification_complete = true;
               if (response.status) {
                    verifyButton.html('<i class="ri-check-double-fill text-success me-3"></i> ' + verifyButton.data('verified-button-text'));
               } else {
                    verifyButton.html('<i class="ri-close-line text-danger me-3"></i> ' + verifyButton.data('unverified-button-text'));
               }
               notify(emailVerificationStatus, emailVerificationMessage);
               isVerifying = false;
          });
     }

     $('.check-email-address').on('input', function() {
          
          var emailInput = $(this).val();
          if (emailInput.trim() !== originalEmail.trim()) {
               verification_complete = false;
               originalEmail = emailInput;
               $('#verify_email_button').html('<span id="verify_email_text">{{ translate("Verify") }}</span><span id="loading_spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>');
               $('#verify_email_button').off('click').on('click', verifyEmailHandler);
          }
     });
</script>