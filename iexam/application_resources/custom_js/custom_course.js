var base_url = js_base_url;
var site_url = js_site_url;


//////////////////Courses//////////////////////////////////////////////////////////////


function courseTable() {
    $('#course_table').dataTable({
        "aaSorting": [[0, "asc"]],
        "sDom": "<'box-content'<'col-sm-6'f><'col-sm-6 text-right'l><'clearfix'>>rt<'box-content'<'col-sm-6'i><'col-sm-6 text-right'p><'clearfix'>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sSearch": "",
            "sLengthMenu": '_MENU_'
        }
    });

}


function courseAddForm() {
    $('#add_course_form').bootstrapValidator({
        message: 'This value is not valid',
        fields: {
            course_code: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            },
            course_name: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            },
            subject_id: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            }

        }, submitHandler: function(form) {
            $.post(site_url + '/courses/courses_controller/add_new_course', $('#add_course_form').serialize(), function(msg)
            {
                if (msg == 1) {
                    $("#add_course_msg").html('<div class="alert alert-success"><button class="close" data-dismiss="alert"></button>Success: The Course has been added.</div>');
                    add_course_form.reset();
                     LoadAjaxContent(site_url+'/courses/courses_controller/manage_courses');
                } else {
                    $("#add_course_msg").html('<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>Error: The Course has failed.</div>');
                }
            });
        }
    });
}


function courseEditForm() {
    $('#edit_course_form').bootstrapValidator({
        message: 'This value is not valid',
        fields: {
            course_code: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            },
            course_name: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            },
            subject_id: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            }

        }, submitHandler: function(form) {
            $.post(site_url + '/courses/courses_controller/edit_course', $('#edit_course_form').serialize(), function(msg)
            {
                if (msg == 1) {
                    $("#edit_course_msg").html('<div class="alert alert-success"><button class="close" data-dismiss="alert"></button>Success: The Course updated successfully.</div>');
                    edit_course_form.reset();
                    LoadAjaxContent(site_url+'/courses/courses_controller/manage_courses');
                } else {
                    $("#edit_course_msg").html('<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>Error: The Course has failed.</div>');
                }
            });
        }
    });
}

//delete Course
function delete_course(id) {

    if (confirm('Are you sure want to delete this Course ?')) {

        $.ajax({
            type: "POST",
            url: site_url + '/courses/courses_controller/delete_course',
            data: "id=" + id,
            success: function(msg) {
                //alert(msg);
                if (msg == 1) {
                    //document.getElementById(trid).style.display='none';
                    $('#course_' + id).hide();
                }
                else if (msg == 2) {
                    alert('Cannot be deleted ');
                }
            }
        });
    }
}

///////////////////////Course Instructor///////////////


function courseInstructorTable() {
    $('#instructor_table').dataTable({
        "aaSorting": [[0, "asc"]],
        "sDom": "<'box-content'<'col-sm-6'f><'col-sm-6 text-right'l><'clearfix'>>rt<'box-content'<'col-sm-6'i><'col-sm-6 text-right'p><'clearfix'>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sSearch": "",
            "sLengthMenu": '_MENU_'
        }
    });
}

function courseInstructorAddForm() {
    $('#add_course_instructor_form').bootstrapValidator({
        message: 'This value is not valid',
        fields: {
            course_id: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            },
            year: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            },
            semester_id: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            },
            instructor_id: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            }

        }, submitHandler: function(form) {
            $.post(site_url + '/courses/course_instructor_controller/add_new_instructor', $('#add_course_instructor_form').serialize(), function(msg)
            {
                if (msg == 1) {
                    $("#add_course_instructor_msg").html('<div class="alert alert-success"><button class="close" data-dismiss="alert"></button>Success: The Course Instructor has been added.</div>');
                    add_course_instructor_form.reset();
                    LoadAjaxContent(site_url+'/courses/course_instructor_controller/manage_instructors');
                } else {
                    $("#add_course_instructor_msg").html('<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>Error: The Course Instructor has failed.</div>');
                }
            });
        }
    });
}

function courseInstructorEditForm() {
    $('#edit_course_instructor_form').bootstrapValidator({
        message: 'This value is not valid',
        fields: {
            course_id: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            },
            year: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            },
            semester_id: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            },
            instructor_id: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            }

        }, submitHandler: function(form) {
            $.post(site_url + '/courses/course_instructor_controller/edit_course_instructor', $('#edit_course_instructor_form').serialize(), function(msg)
            {
                if (msg == 1) {
                    $("#edit_course_instructor_msg").html('<div class="alert alert-success"><button class="close" data-dismiss="alert"></button>Success: The Course Instructor updated successfully.</div>');
                    edit_course_instructor_form.reset();
                    LoadAjaxContent(site_url+'/courses/course_instructor_controller/manage_instructors');
                } else {
                    $("#edit_course_instructor_msg").html('<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>Error: The Course Instructor has failed.</div>');
                }
            });
        }
    });
}


//delete Course Instructor
function delete_course_instructor(id) {

    if (confirm('Are you sure want to delete this Course Instructor ?')) {

        $.ajax({
            type: "POST",
            url: site_url + '/courses/course_instructor_controller/delete_course_instructor',
            data: "instructor_id=" + id,
            success: function(msg) {
                //alert(msg);
                if (msg == 1) {
                    //document.getElementById(trid).style.display='none';
                    $('#course_instructor_' + id).hide();
                }
                else if (msg == 2) {
                    alert('Cannot be deleted ');
                }
            }
        });
    }
}

//////////////////////////////////////Course Students///////////////////////////

function courseStudentTable() {
    $('#course_student_table').dataTable({
        "aaSorting": [[0, "asc"]],
        "sDom": "<'box-content'<'col-sm-6'f><'col-sm-6 text-right'l><'clearfix'>>rt<'box-content'<'col-sm-6'i><'col-sm-6 text-right'p><'clearfix'>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sSearch": "",
            "sLengthMenu": '_MENU_'
        }
    });
}


function courseStudentAddForm() {
    $('#add_course_student_form').bootstrapValidator({
        message: 'This value is not valid',
        fields: {
            course_id: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            },
            student: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            }

        }, submitHandler: function(form) {
            $.post(site_url + '/courses/course_student_controller/add_new_course_student', $('#add_course_student_form').serialize(), function(msg)
            {
                if (msg == 1) {
                    $("#add_course_student_msg").html('<div class="alert alert-success"><button class="close" data-dismiss="alert"></button>Success: The Course Student has been added.</div>');
                    add_course_student_form.reset();
                    LoadAjaxContent(site_url+'/courses/course_student_controller/manage_course_students');
                } else {
                    $("#add_course_student_msg").html('<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>Error: The Course Student has failed.</div>');
                }
            });
        }
    });
}

function courseStudentEditForm() {
    $('#edit_course_student_form').bootstrapValidator({
        message: 'This value is not valid',
        fields: {
            course_id: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            },
            student: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            }

        }, submitHandler: function(form) {
            $.post(site_url + '/courses/course_student_controller/edit_course_student', $('#edit_course_student_form').serialize(), function(msg)
            {
                if (msg == 1) {
                    $("#edit_course_student_msg").html('<div class="alert alert-success"><button class="close" data-dismiss="alert"></button>Success: The Course Student updated successfully.</div>');
                    edit_course_student_form.reset();
                    LoadAjaxContent(site_url+'/courses/course_student_controller/manage_course_students');
                } else {
                    $("#edit_course_student_msg").html('<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>Error: The Course Student has failed.</div>');
                }
            });
        }
    });
}



function delete_course_student(id) {

    if (confirm('Are you sure want to delete this Course Student ?')) {

        $.ajax({
            type: "POST",
            url: site_url + '/courses/course_student_controller/delete_course_student',
            data: "id=" + id,
            success: function(msg) {
                //alert(msg);
                if (msg == 1) {
                    //document.getElementById(trid).style.display='none';
                    $('#course_student_' + id).hide();
                }
                else if (msg == 2) {
                    alert('Cannot be deleted ');
                }
            }
        });
    }
}



$(document).ready(function() {
    LoadBootstrapValidatorScript(courseEditForm);
    LoadBootstrapValidatorScript(courseInstructorEditForm);
    LoadBootstrapValidatorScript(courseStudentEditForm);

});
