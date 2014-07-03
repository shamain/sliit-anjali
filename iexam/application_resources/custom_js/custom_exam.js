var base_url = js_base_url;
var site_url = js_site_url;


//////////////////Exam Type//////////////////////////////////////////////////////////////


function examtypeTable() {
    $('#exam_type_table').dataTable({
        "aaSorting": [[0, "asc"]],
        "sDom": "<'box-content'<'col-sm-6'f><'col-sm-6 text-right'l><'clearfix'>>rt<'box-content'<'col-sm-6'i><'col-sm-6 text-right'p><'clearfix'>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sSearch": "",
            "sLengthMenu": '_MENU_'
        }
    });

}


function examtypeAddForm() {
    $('#add_exam_type_form').bootstrapValidator({
        message: 'This value is not valid',
        fields: {
            exam_type_name: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            }

        }, submitHandler: function(form) {
            $.post(site_url + '/examination_types/examination_types_controller/add_new_examination_type', $('#add_exam_type_form').serialize(), function(msg)
            {
                if (msg == 1) {
                    $("#add_exam_type_msg").html('<div class="alert alert-success"><button class="close" data-dismiss="alert"></button>Success: The Exam Type has been added.</div>');
                    add_exam_type_form.reset();
                    LoadAjaxContent(site_url + '/examination_types/examination_types_controller/manage_examination_types');
                } else {
                    $("#add_exam_type_msg").html('<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>Error: The Exam Type has failed.</div>');
                }
            });
        }
    });
}

function examtypeEditForm() {
    $('#edit_exam_type_form').bootstrapValidator({
        message: 'This value is not valid',
        fields: {
            exam_type_name: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            }

        }, submitHandler: function(form) {
            $.post(site_url + '/examination_types/examination_types_controller/edit_examination_type', $('#edit_exam_type_form').serialize(), function(msg)
            {
                if (msg == 1) {
                    $("#edit_exam_type_msg").html('<div class="alert alert-success"><button class="close" data-dismiss="alert"></button>Success: The Exam Type updated successfully.</div>');
                    edit_exam_type_form.reset();
                    LoadAjaxContent(site_url + '/examination_types/examination_types_controller/manage_examination_types');
                } else {
                    $("#edit_exam_type_msg").html('<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>Error: The Exam Type has failed.</div>');
                }
            });
        }
    });
}


//delete Exam Type
function delete_examtype(id) {

    if (confirm('Are you sure want to delete this Exam Type ?')) {

        $.ajax({
            type: "POST",
            url: site_url + '/examination_types/examination_types_controller/delete_examination_type',
            data: "id=" + id,
            success: function(msg) {
                //alert(msg);
                if (msg == 1) {
                    //document.getElementById(trid).style.display='none';
                    $('#examtype_' + id).hide();
                }
                else if (msg == 2) {
                    alert('Cannot be deleted ');
                }
            }
        });
    }
}


//////////////////////////Exam/////////////////////////////////////////////
function examTable() {
    $('#exam_table').dataTable({
        "aaSorting": [[0, "asc"]],
        "sDom": "<'box-content'<'col-sm-6'f><'col-sm-6 text-right'l><'clearfix'>>rt<'box-content'<'col-sm-6'i><'col-sm-6 text-right'p><'clearfix'>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sSearch": "",
            "sLengthMenu": '_MENU_'
        }
    });

}


function examAddForm() {
    $('#add_exam_form').bootstrapValidator({
        message: 'This value is not valid',
        fields: {
            exam_name: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            },
            exam_type_id: {
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
            course_id: {
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
            },
            year: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    },
                    digits: {
                        message: 'The value can contain only digits'
                    }
                }
            },
            no_mcq: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    },
                    digits: {
                        message: 'The value can contain only digits'
                    }
                }
            },
            no_short_ans: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    },
                    digits: {
                        message: 'The value can contain only digits'
                    }
                }
            },
            start_date: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            },
            end_date: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            }



        }, submitHandler: function(form) {
            $.post(site_url + '/examinations/exams_controller/add_new_examination', $('#add_exam_form').serialize(), function(msg)
            {
                if (msg == 1) {
                    $("#add_exam_msg").html('<div class="alert alert-success"><button class="close" data-dismiss="alert"></button>Success: The Exam  has been added.</div>');
                    add_exam_form.reset();
                    LoadAjaxContent(site_url + '/examinations/exams_controller/manage_examinations');
                } else {
                    $("#add_exam_msg").html('<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>Error: The Exam  has failed.</div>');
                }
            });
        }
    });
}



function examEditForm() {
    $('#edit_exam_form').bootstrapValidator({
        message: 'This value is not valid',
        fields: {
            exam_name: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            },
            exam_type_id: {
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
            course_id: {
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
            },
            year: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    },
                    digits: {
                        message: 'The value can contain only digits'
                    }
                }
            },
            no_mcq: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    },
                    digits: {
                        message: 'The value can contain only digits'
                    }
                }
            },
            no_short_ans: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    },
                    digits: {
                        message: 'The value can contain only digits'
                    }
                }
            },
            start_date: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            },
            end_date: {
                validators: {
                    notEmpty: {
                        message: 'This feild is required \n'
                    }
                }
            }



        }, submitHandler: function(form) {
            $.post(site_url + '/examinations/exams_controller/edit_examination', $('#edit_exam_form').serialize(), function(msg)
            {
                if (msg == 1) {
                    $("#edit_exam_msg").html('<div class="alert alert-success"><button class="close" data-dismiss="alert"></button>Success: The Exam  updated successfully.</div>');
                    edit_exam_form.reset();
                    LoadAjaxContent(site_url + '/examinations/exams_controller/manage_examinations');
                } else {
                    $("#edit_exam_msg").html('<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>Error: The Exam  has failed.</div>');
                }
            });
        }
    });
}


//delete Exam
function delete_exam(id) {

    if (confirm('Are you sure want to delete this Exam ?')) {

        $.ajax({
            type: "POST",
            url: site_url + '/examinations/exams_controller/delete_examination',
            data: "id=" + id,
            success: function(msg) {
                //alert(msg);
                if (msg == 1) {
                    //document.getElementById(trid).style.display='none';
                    $('#exam_' + id).hide();
                }
                else if (msg == 2) {
                    alert('Cannot be deleted ');
                }
            }
        });
    }
}



$(document).ready(function() {
    LoadBootstrapValidatorScript(examtypeEditForm);
    LoadBootstrapValidatorScript(examEditForm);

});