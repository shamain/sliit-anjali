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
                    add_userlevel_form.reset();
//                    location.reload();
                } else {
                    $("#add_user_level_msg").html('<div class="alert alert-error"><button class="close" data-dismiss="alert"></button>Error: The User Level has failed.</div>');
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

