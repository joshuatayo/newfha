
    $(window).on('ajaxBeforeSend', function() {
        $('#contactFormSubmitButton').prop('disabled',true);
    });
    $(window).on('ajaxUpdateComplete', function() {
        $('#contactFormSubmitButton').prop('disabled',false);
    });
    $('#contact_form').on('ajaxSuccess', function() {
        document.getElementById('contact_form').reset();
        
        if(typeof grecaptcha != "undefined")
            grecaptcha.reset();
    });
    
