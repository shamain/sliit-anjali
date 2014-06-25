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
                    location.reload();
                } else {
                    $("#add_semister_msg").html('<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>Error: The Semester has failed.</div>');
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
                    location.reload();
                } else {
                    $("#add_subject_msg").html('<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>Error: The Subject has failed.</div>');
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
                    location.reload();
                } else {
                    $("#add_designation_msg").html('<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>Error: The Designation has failed.</div>');
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