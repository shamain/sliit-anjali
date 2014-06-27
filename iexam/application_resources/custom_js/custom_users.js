var base_url = js_base_url;
var site_url = js_site_url;


//////////////////User Level//////////////////////////////////////////////////////////////


function userlevelTable() {
    $('#userlevel_table').dataTable({
        "aaSorting": [[0, "asc"]],
        "sDom": "<'box-content'<'col-sm-6'f><'col-sm-6 text-right'l><'clearfix'>>rt<'box-content'<'col-sm-6'i><'col-sm-6 text-right'p><'clearfix'>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sSearch": "",
            "sLengthMenu": '_MENU_'
        }
    });

}


function userlevelAddForm() {
    $('#add_userlevel_form').bootstrapValidator({
        message: 'This value is not valid',
        fields: {
            user_level_name: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            }

        }, submitHandler: function(form) {
            $.post(site_url + '/user_levels/user_levels_controller/add_new_user_level', $('#add_userlevel_form').serialize(), function(msg)
            {
                if (msg == 1) {
                    $("#add_user_level_msg").html('<div class="alert alert-success"><button class="close" data-dismiss="alert"></button>Success: The User Level has been added.</div>');
      
                    location.reload();
                } else {
                    $("#add_user_level_msg").html('<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>Error: The User Level has failed.</div>');
                }
            });
        }
    });
}


function userlevelEditForm() {
    $('#edit_user_level_form').bootstrapValidator({
        message: 'This value is not valid',
        fields: {
            user_level_name: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            }

        }, submitHandler: function(form) {
            $.post(site_url + '/user_levels/user_levels_controller/edit_user_level', $('#edit_user_level_form').serialize(), function(msg)
            {
                if (msg == 1) {
                    $("#edit_user_level_msg").html('<div class="alert alert-success"><button class="close" data-dismiss="alert"></button>Success: The User Level updated successfully.</div>');

                    location.reload();
                } else {
                    $("#edit_user_level_msg").html('<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>Error: The User Level has failed.</div>');
                }
            });
        }
    });
}


//delete User Level
function delete_userlevel(id) {

    if (confirm('Are you sure want to delete this User Level ?')) {

        $.ajax({
            type: "POST",
            url: site_url + '/user_levels/user_levels_controller/delete_user_level',
            data: "id=" + id,
            success: function(msg) {
                //alert(msg);
                if (msg == 1) {
                    //document.getElementById(trid).style.display='none';
                    $('#user_level_' + id).hide();
                }
                else if (msg == 2) {
                    alert('Cannot be deleted ');
                }
            }
        });
    }
}

/////////////////////Users///////////////////////////////////////

function usersTable() {
    $('#user_table').dataTable({
        "aaSorting": [[0, "asc"]],
        "sDom": "<'box-content'<'col-sm-6'f><'col-sm-6 text-right'l><'clearfix'>>rt<'box-content'<'col-sm-6'i><'col-sm-6 text-right'p><'clearfix'>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sSearch": "",
            "sLengthMenu": '_MENU_'
        }
    });

}

function usersAddForm() {
    $('#add_user_form').bootstrapValidator({
        message: 'This value is not valid',
        fields: {
            first_name: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            },
            user_level: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            },
            designation_id: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    },
                    emailAddress: {
                        message: 'The input is not a valid email address'
                    }
                }
            },
            reg_number: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            },
            nic: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            },
            user_name: {
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
            password: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    },
                    identical: {
                        field: 'cnfpassword',
                        message: 'The password and its confirm are not the same'
                    }
                }
            },
            cnfpassword: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    },
                    identical: {
                        field: 'password',
                        message: 'The password and its confirm are not the same'
                    }
                }
            }

        }, submitHandler: function(form) {
            $.post(site_url + '/users/users_controller/add_new_user', $('#add_user_form').serialize(), function(msg)
            {
                if (msg == 1) {
                    $("#add_user_msg").html('<div class="alert alert-success"><button class="close" data-dismiss="alert"></button>Success: The User  has been added.</div>');

                    location.reload();
                } else {
                    $("#add_user_msg").html('<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>Error: The User  has failed.</div>');
                }
            });
        }
    });
}

function usersEditForm() {
    $('#edit_user_form').bootstrapValidator({
        message: 'This value is not valid',
        fields: {
            first_name: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            },
            user_level: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            },
            designation_id: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    },
                    emailAddress: {
                        message: 'The input is not a valid email address'
                    }
                }
            },
            reg_number: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            },
            nic: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            },
            user_name: {
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
            password: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    },
                    identical: {
                        field: 'cnfpassword',
                        message: 'The password and its confirm are not the same'
                    }
                }
            },
            cnfpassword: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    },
                    identical: {
                        field: 'password',
                        message: 'The password and its confirm are not the same'
                    }
                }
            }

        }, submitHandler: function(form) {
            $.post(site_url + '/users/users_controller/edit_user', $('#edit_user_form').serialize(), function(msg)
            {
                if (msg == 1) {
                    $("#edit_user_msg").html('<div class="alert alert-success"><button class="close" data-dismiss="alert"></button>Success: The User  updated successfully.</div>');
                    location.reload();
                } else {
                    $("#edit_user_msg").html('<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>Error: The User  has failed.</div>');
                }
            });
        }
    });
}


$(function() {
    var btnUpload = $('#upload');
    var status = $('#status');
    new AjaxUpload(btnUpload, {
        action: '../../upload_file',
        name: 'uploadfile',
        onSubmit: function(file, ext) {
            if (!(ext && /^(jpg|png|jpeg|gif)$/.test(ext))) {
                // extension is not allowed 
                status.text('Only JPG, PNG or GIF files are allowed');
                return false;
            }
            //status.text('Uploading...Please wait');
            $("#sta").html("<img src='" + js_base_url + "/application_resources/img/ajaxloader.gif' />");

        },
        onComplete: function(file, response) {
            //On completion clear the status
            //status.text('');
            $("#sta").html("");
            //Add uploaded file to list
            if (response != "error") {

                $('#files').html("");
                $('<div></div>').appendTo('#files').html('<img src="../../../../uploads/user_avatar/' + response + '" alt="" width="100px" height="100px" /><br />');
                picFileName = response;
                document.getElementById('image').value = file;
                document.getElementById('pro_image').value = response;
            } else {
                $('<div></div>').appendTo('#files').text(file).addClass('error');
            }
        }
    });

});




$(document).ready(function() {

    LoadBootstrapValidatorScript(userlevelEditForm);
     LoadBootstrapValidatorScript(usersEditForm);

});

