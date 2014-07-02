var base_url = js_base_url;
var site_url = js_site_url;


//////////////////Semester//////////////////////////////////////////////////////////////


function semisterTable() {
    $('#semester_table').dataTable({
        "aaSorting": [[0, "asc"]],
        "sDom": "<'box-content'<'col-sm-6'f><'col-sm-6 text-right'l><'clearfix'>>rt<'box-content'<'col-sm-6'i><'col-sm-6 text-right'p><'clearfix'>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sSearch": "",
            "sLengthMenu": '_MENU_'
        }
    });

}



function semisterAddForm() {
    $('#add_semister_form').bootstrapValidator({
        message: 'This value is not valid',
        fields: {
            semister_name: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            }

        }, submitHandler: function(form) {
            $.post(site_url + '/semister/semister_controller/add_new_semister', $('#add_semister_form').serialize(), function(msg)
            {
                if (msg == 1) {
                    $("#add_semister_msg").html('<div class="alert alert-success"><button class="close" data-dismiss="alert"></button>Success: The Semester has been added.</div>');
                    add_semister_form.reset();
                    LoadAjaxContent(site_url + '/semister/semister_controller/manage_semisters');
                } else {
                    $("#add_semister_msg").html('<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>Error: The Semester has failed.</div>');
                }
            });
        }
    });
}



function semisterEditForm() {
    $('#edit_semister_form').bootstrapValidator({
        message: 'This value is not valid',
        fields: {
            semister_name: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            }

        }, submitHandler: function(form) {
            $.post(site_url + '/semister/semister_controller/edit_semister', $('#edit_semister_form').serialize(), function(msg)
            {
                if (msg == 1) {
                    $("#edit_semister_msg").html('<div class="alert alert-success"><button class="close" data-dismiss="alert"></button>Success: The Semester updated successfully.</div>');
                    edit_semister_form.reset();
                    LoadAjaxContent(site_url + '/semister/semister_controller/manage_semisters');
                } else {
                    $("#edit_semister_msg").html('<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>Error: The Semester has failed.</div>');
                }
            });
        }
    });
}


//delete Semester
function delete_semester(id) {

    if (confirm('Are you sure want to delete this Semester ?')) {

        $.ajax({
            type: "POST",
            url: site_url + '/semister/semister_controller/delete_semister',
            data: "id=" + id,
            success: function(msg) {
                //alert(msg);
                if (msg == 1) {
                    //document.getElementById(trid).style.display='none';
                    $('#semester_' + id).hide();
                }
                else if (msg == 2) {
                    alert('Cannot be deleted ');
                }
            }
        });
    }
}


/////////////////////////////////////////////////////Subjects////////////////////////////////////////
function subjectTable() {
    $('#subject_table').dataTable({
        "aaSorting": [[0, "asc"]],
        "sDom": "<'box-content'<'col-sm-6'f><'col-sm-6 text-right'l><'clearfix'>>rt<'box-content'<'col-sm-6'i><'col-sm-6 text-right'p><'clearfix'>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sSearch": "",
            "sLengthMenu": '_MENU_'
        }
    });

}

function subjectAddForm() {
    $('#add_subject_form').bootstrapValidator({
        message: 'This value is not valid',
        fields: {
            subject_name: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            }

        }, submitHandler: function(form) {
            $.post(site_url + '/subjects/subjects_controller/add_new_subject', $('#add_subject_form').serialize(), function(msg)
            {
                if (msg == 1) {
                    $("#add_subject_msg").html('<div class="alert alert-success"><button class="close" data-dismiss="alert"></button>Success: The Subject has been added.</div>');
                    add_subject_form.reset();
                    LoadAjaxContent(site_url + '/subjects/subjects_controller/manage_subjects');
                } else {
                    $("#add_subject_msg").html('<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>Error: The Subject has failed.</div>');
                }
            });
        }
    });
}



function subjectEditForm() {
    $('#edit_subject_form').bootstrapValidator({
        message: 'This value is not valid',
        fields: {
            subject_name: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            }

        }, submitHandler: function(form) {
            $.post(site_url + '/subjects/subjects_controller/edit_subject', $('#edit_subject_form').serialize(), function(msg)
            {
                if (msg == 1) {
                    $("#edit_subject_msg").html('<div class="alert alert-success"><button class="close" data-dismiss="alert"></button>Success: The Subject updated successfully.</div>');
                    edit_subject_form.reset();
                    LoadAjaxContent(site_url + '/subjects/subjects_controller/manage_subjects');
                } else {
                    $("#edit_subject_msg").html('<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>Error: The Subject has failed.</div>');
                }
            });
        }
    });
}

//delete Subjects
function delete_subject(id) {

    if (confirm('Are you sure want to delete this Subject ?')) {

        $.ajax({
            type: "POST",
            url: site_url + '/subjects/subjects_controller/delete_subject',
            data: "id=" + id,
            success: function(msg) {
                //alert(msg);
                if (msg == 1) {
                    //document.getElementById(trid).style.display='none';
                    $('#subject_' + id).hide();
                }
                else if (msg == 2) {
                    alert('Cannot be deleted ');
                }
            }
        });
    }
}


////////////////////////////////////////Designation///////////////////////////////////////////////
function designationTable() {
    $('#designation_table').dataTable({
        "aaSorting": [[0, "asc"]],
        "sDom": "<'box-content'<'col-sm-6'f><'col-sm-6 text-right'l><'clearfix'>>rt<'box-content'<'col-sm-6'i><'col-sm-6 text-right'p><'clearfix'>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sSearch": "",
            "sLengthMenu": '_MENU_'
        }
    });

}

function designationAddForm() {
    $('#add_designation_form').bootstrapValidator({
        message: 'This value is not valid',
        fields: {
            designation_name: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            }

        }, submitHandler: function(form) {
            $.post(site_url + '/designations/designations_controller/add_new_designation', $('#add_designation_form').serialize(), function(msg)
            {
                if (msg == 1) {
                    $("#add_designation_msg").html('<div class="alert alert-success"><button class="close" data-dismiss="alert"></button>Success: The Designation has been added.</div>');
                    add_designation_form.reset();
                    LoadAjaxContent(site_url + '/designations/designations_controller/manage_designations');
                } else {
                    $("#add_designation_msg").html('<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>Error: The Designation has failed.</div>');
                }
            });
        }
    });
}

function designationEditForm() {
    $('#edit_designation_form').bootstrapValidator({
        message: 'This value is not valid',
        fields: {
            designation_name: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            }

        }, submitHandler: function(form) {
            $.post(site_url + '/designations/designations_controller/edit_designation', $('#edit_designation_form').serialize(), function(msg)
            {
                if (msg == 1) {
                    $("#edit_designation_msg").html('<div class="alert alert-success"><button class="close" data-dismiss="alert"></button>Success: The Designation updated successfully.</div>');
                    edit_designation_form.reset();
                    LoadAjaxContent(site_url + '/designations/designations_controller/manage_designations');
                } else {
                    $("#edit_designation_msg").html('<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>Error: The Designation has failed.</div>');
                }
            });
        }
    });
}

//delete Designation
function delete_designation(id) {

    if (confirm('Are you sure want to delete this Designation ?')) {

        $.ajax({
            type: "POST",
            url: site_url + '/designations/designations_controller/delete_designation',
            data: "id=" + id,
            success: function(msg) {
                //alert(msg);
                if (msg == 1) {
                    //document.getElementById(trid).style.display='none';
                    $('#designation_' + id).hide();
                }
                else if (msg == 2) {
                    alert('Cannot be deleted ');
                }
            }
        });
    }
}


//////////////////////////////////Marital Status////////////////////////////////////////////

function maritalstatusTable() {
    $('#marital_status_table').dataTable({
        "aaSorting": [[0, "asc"]],
        "sDom": "<'box-content'<'col-sm-6'f><'col-sm-6 text-right'l><'clearfix'>>rt<'box-content'<'col-sm-6'i><'col-sm-6 text-right'p><'clearfix'>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sSearch": "",
            "sLengthMenu": '_MENU_'
        }
    });

}



function maritalstatusAddForm() {
    $('#add_marital_status_form').bootstrapValidator({
        message: 'This value is not valid',
        fields: {
            status_name: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            }

        }, submitHandler: function(form) {
            $.post(site_url + '/marital_statuses/marital_statuses_controller/add_new_marital_statuses', $('#add_marital_status_form').serialize(), function(msg)
            {
                if (msg == 1) {
                    $("#add_marital_status_msg").html('<div class="alert alert-success"><button class="close" data-dismiss="alert"></button>Success: The Marital Status has been added.</div>');
                    add_marital_status_form.reset();
                    LoadAjaxContent(site_url + '/marital_statuses/marital_statuses_controller/manage_marital_statuses');
                } else {
                    $("#add_marital_status_msg").html('<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>Error: The Marital Status has failed.</div>');
                }
            });
        }
    });
}


function maritalstatusEditForm() {
    $('#edit_marital_status_form').bootstrapValidator({
        message: 'This value is not valid',
        fields: {
            status_name: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            }

        }, submitHandler: function(form) {
            $.post(site_url + '/marital_statuses/marital_statuses_controller/edit_marital_status', $('#edit_marital_status_form').serialize(), function(msg)
            {
                if (msg == 1) {
                    $("#edit_marital_status_msg").html('<div class="alert alert-success"><button class="close" data-dismiss="alert"></button>Success: The Marital Status updated successfully.</div>');
                    edit_marital_status_form.reset();
                    LoadAjaxContent(site_url + '/marital_statuses/marital_statuses_controller/manage_marital_statuses');
                } else {
                    $("#edit_marital_status_msg").html('<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>Error: The Marital Status has failed.</div>');
                }
            });
        }
    });
}



//delete Marital Status
function delete_maritalstatus(id) {

    if (confirm('Are you sure want to delete this Marital Status ?')) {

        $.ajax({
            type: "POST",
            url: site_url + '/marital_statuses/marital_statuses_controller/delete_marital_status',
            data: "id=" + id,
            success: function(msg) {
                //alert(msg);
                if (msg == 1) {
                    //document.getElementById(trid).style.display='none';
                    $('#maritalstatus_' + id).hide();
                }
                else if (msg == 2) {
                    alert('Cannot be deleted ');
                }
            }
        });
    }
}


////////////////////////////Log In////////////////////////////

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



$(document).ready(function() {
    LoadBootstrapValidatorScript(semisterEditForm);
    LoadBootstrapValidatorScript(subjectEditForm);
    LoadBootstrapValidatorScript(designationEditForm);
    LoadBootstrapValidatorScript(maritalstatusEditForm);
});

