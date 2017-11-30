$(document).ready(function() {
    $("#loginForm").submit(function(e) {
        removeFeedback();
        var errors = validateForm();
        if (errors == "") {
            return true;
        } else {
            provideFeedback(errors);
            e.preventDefault();
            return false;
        }
    });

    function validateForm() {
        var errorFields = new Array();

        // Check required fields have something in them
        if($('#email').val() == "") {
            errorFields.push('email');
        }
        if($('#password').val() == "") {
            errorFields.push('password');
        }

        return errorFields;
    } // end function validateForm

    function provideFeedback(incomingErrors) {
        for (var i = 0; i < incomingErrors.length; i++) {
            $("#" + incomingErrors[i]).addClass("errorClass");
            $("#" + incomingErrors[i] + "Error").removeClass("errorFeedback");
        }
        $("#errorDiv").html("Errors encountered");
    } // end function provideFeedback

    function removeFeedback() {
        $("#errorDiv").html("");
        $('input').each(function() {
            $(this).removeClass("errorClass");
        });
        $('.errorSpan').each(function() {
            $(this).addClass("errorFeedback");
        });
    } // end function removeFeedback
});
