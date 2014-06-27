////////////////////////////Log In////////////////////////////
var base_url = js_base_url;
var site_url = js_site_url;

function LoginForm() {
    $('#login_form').bootstrapValidator({
        message: 'This value is not valid',
        fields: {
            txtusername: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    },
                    stringLength: {
                        min: 6,
                        message: 'The username must be more than 6 characters long'
                    }
                }
            },
            txtpassword: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            }

        }, submitHandler: function(form) {
            var login_username = $('#txtusername').val();
            var login_password = $('#txtpassword').val();


//        var x = $('.load-anim').show().delay(5000);

            $.ajax({
                type: "POST",
                url: site_url + "/login/login_controller/authenticate_user",
                data: "login_username=" + login_username + "&login_password=" + login_password,
                async: false,
                success: function(msg) {
                    $('#msg').html('<span class="response-msg notice ui-corner-all">validating...</span>');
                    if (msg == 1) {
                        $('#msg').html('<span class="response-msg notice ui-corner-all">validating...</span>');
                        setTimeout("location.href = site_url+'/login/login_controller/';", 100);
                        x.fadeOut('slow');
                    } else {

                        $('#msg').html('<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>Invalid login details...</div>');
                    }

                }
            });
        }
    });
}
