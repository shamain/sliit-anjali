

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-user-md"></i>
                    <span>Users</span>
                </div>
                <div class="box-icons">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="expand-link">
                        <i class="fa fa-expand"></i>
                    </a>
                    <a class="close-link">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
                <div class="no-move"></div>
            </div>

            <div class="box-content">
                <button class="btn btn-success" type="button" id="add_user_btn" data-toggle="modal" data-target="#add_user_modal">
                    Add User
                </button>
            </div>

            <div class="box-content no-padding">
                <table class="table table-bordered table-striped table-hover table-heading table-datatable" id="user_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Designation</th>
                            <th>User Level</th>
                            <th>Email</th>
                            <th>Reg No</th>
                            <th>NIC</th>
                            <th>Gender</th>
                            <th>Marital Status</th>
                            <th>DOB</th>
                            <th>Registered On</th>
                            <th>Status</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        foreach ($users as $user) {
                            ?> 
                            <tr id="user_<?php echo $user->UserID; ?>">
                                <td><?php echo++$i; ?></td>
                                <td><?php echo $user->FirstName . ' ' . $user->MiddleName . ' ' . $user->LastName; ?></td>
                                <td><?php echo $user->Designation; ?></td>
                                <td><?php echo $user->userlevelname; ?></td>
                                <td><?php echo $user->Email; ?></td>
                                <td><?php echo $user->RegistrationNumber; ?></td>
                                <td><?php echo $user->NICNumber; ?></td>
                                <td>
                                    <?php
                                    if ($user->Gender == 0) {
                                        echo 'Male';
                                    } else {
                                        echo 'FeMale';
                                    }
                                    ?>
                                </td>
                                <td><?php echo $user->MaritalStatus; ?></td>
                                <td>
                                    <?php
                                    if ($user->DateOfBirth == '0000-00-00') {
                                        echo 'Not Set';
                                    } else {
                                        echo $user->DateOfBirth;
                                    }
                                    ?>
                                </td>
                                <td><?php echo $user->RegisteredOn; ?></td>
                                <td>
                                    <?php
                                    if ($user->Activated == 1) {
                                        ?>
                                    <code class="txt-success">Active</code>
                                        <?php
                                    } else {
                                        ?>
                                        <code>Inactive</code>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a href="<?php echo site_url(); ?>/users/users_controller/edit_user_view/<?php echo $user->UserID; ?>" title="Edit this User">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a style="cursor: pointer;"   title="Delete this User" onclick="delete_user(<?php echo $user->UserID; ?>)">
                                        <i class="fa fa-times"></i>
                                    </a>

                                </td>
                            </tr>
                        <?php } ?>  
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="add_user_modal" tabindex="-1" role="dialog" aria-labelledby="add_user_modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="add_user_form" name="add_user_form" class="form-horizontal bootstrap-validator-form">
                <div class="modal-header tiles green text-center">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <br>
                    <i class="fa  fa-user fa-4x"></i>
                    <h4 id="add_user_modalLabel" class="semi-bold text-white">It's a new user</h4>
                    <p class="no-margin text-white">Include user details here.</p>
                    <br>
                </div>
                <div class="modal-body">

                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">First Name</label>
                            <div class="col-sm-5">
                                <input id="first_name" class="form-control" type="text" name="first_name" >  
                            </div>
                            <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Middle Name</label>
                            <div class="col-sm-5">
                                <input id="middle_name" class="form-control" type="text" name="middle_name" >  
                            </div>
                            <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Last Name</label>
                            <div class="col-sm-5">
                                <input id="last_name" class="form-control" type="text" name="last_name" >  
                            </div>
                            <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">User Level</label>
                            <div class="col-sm-5">

                                <select id="user_level" name="user_level" >
                                    <option>Please Select</option>
                                    <?php foreach ($user_levels as $user_level) { ?>
                                        <option value="<?php echo $user_level->userlevelid; ?>"> <?php echo $user_level->userlevelname; ?> </option>
                                    <?php } ?>

                                </select>
                            </div>
                            <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Designation</label>
                            <div class="col-sm-5">
                                <select id="designation_id" name="designation_id" >
                                    <option>Please Select</option>
                                    <?php foreach ($designations as $designation) { ?>
                                        <option value="<?php echo $designation->DesignationID; ?>"> <?php echo $designation->Designation; ?> </option>
                                    <?php } ?>

                                </select>
                            </div>
                            <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Marital Status</label>
                            <div class="col-sm-5">

                                <select id="marital_status_id" name="marital_status_id" >
                                    <option>Please Select</option>
                                    <?php foreach ($marital_statuses as $marital_status) { ?>
                                        <option value="<?php echo $marital_status->MaritalStatusID; ?>"> <?php echo $marital_status->MaritalStatus; ?> </option>
                                    <?php } ?>

                                </select>
                            </div>
                            <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Email</label>
                            <div class="col-sm-5">
                                <input id="email" class="form-control" type="text" name="email" >  
                            </div>
                            <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Registration Number</label>
                            <div class="col-sm-5">
                                <input id="reg_number" class="form-control" type="text" name="reg_number" >  
                            </div>
                            <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Nic No</label>
                            <div class="col-sm-5">
                                <input id="nic" class="form-control" type="text" name="nic" >  
                            </div>
                            <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">User Name</label>
                            <div class="col-sm-5">
                                <input id="user_name" class="form-control" type="text" name="user_name" >  
                            </div>
                            <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Password</label>
                            <div class="col-sm-5">
                                <input id="password" class="form-control" type="text" name="password" >  
                            </div>
                            <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Confirm Password</label>
                            <div class="col-sm-5">
                                <input id="cnfpassword" class="form-control" type="text" name="cnfpassword" >  
                            </div>
                            <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                        </div>
                    </fieldset>



                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Gender</label>
                            <div class="col-sm-5">
                                <div class="radio">
                                    <label>
                                        <input type="radio"  checked="true" value="0" name="gender">
                                        Male
                                        <i class="fa fa-circle-o small"></i>
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" value="1" name="gender" >
                                        Female 
                                        <i class="fa fa-circle-o small"></i>
                                    </label>
                                </div>

                            </div>
                            <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Date Of Birth</label>
                            <div class="col-sm-5">
                                <input id="dob" class="form-control" type="text" name="dob" >  
                            </div>
                            <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Registration Valid Till</label>
                            <div class="col-sm-5">
                                <input id="reg_valid_til" class="form-control" type="text" name="reg_valid_til" >  
                            </div>
                            <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Activate</label>
                            <div class="col-sm-5">

                                <div class="radio">
                                    <label>
                                        <input type="radio"  checked="true" value="1" name="active_status">
                                        Yes
                                        <i class="fa fa-circle-o small"></i>
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" value="0" name="active_status" >
                                        No 
                                        <i class="fa fa-circle-o small"></i>
                                    </label>
                                </div>

                            </div>
                            <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                        </div>
                    </fieldset>

                    <script type="text/javascript">

                                    $(function() {
                                        var btnUpload = $('#upload');
                                        var status = $('#status');
                                        new AjaxUpload(btnUpload, {
                                            action: '<?PHP echo site_url(); ?>/users/upload_file',
                                            name: 'uploadfile',
                                            onSubmit: function(file, ext) {
                                                if (!(ext && /^(jpg|png|jpeg|gif)$/.test(ext))) {
                                                    // extension is not allowed 
                                                    status.text('Only JPG, PNG or GIF files are allowed');
                                                    return false;
                                                }
                                                //status.text('Uploading...Please wait');
                                                $("#sta").html("<img src='<?php echo base_url(); ?>/application_resources/img/ajaxloader.gif' />");

                                            },
                                            onComplete: function(file, response) {
                                                //On completion clear the status
                                                //status.text('');
                                                $("#sta").html("");
                                                //Add uploaded file to list
                                                if (response != "error") {

                                                    $('#files').html("");
                                                    $('<div></div>').appendTo('#files').html('<img src="<?PHP echo base_url(); ?>uploads/user_avatar/' + response + '" alt="" width="100px" height="100px" /><br />');
                                                    picFileName = response;
                                                    document.getElementById('image').value = file;
                                                    document.getElementById('pro_image').value = response;
                                                } else {
                                                    $('<div></div>').appendTo('#files').text(file).addClass('error');
                                                }
                                            }
                                        });

                                    });

                    </script>



                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Upload Photo</label>
                            <div class="col-sm-5">
                                <div id="upload">
                                    <input type="text" id="image" name="image"/><input type="button"  value="Browse" id="browse" class="btn btn-default "/>
                                    <input type="text" id="pro_image" name="pro_image" style="visibility: hidden" />

                                </div>
                                <div id="sta"><span id="status" ></span></div>
                                <div id="files" ></div>
                            </div>

                        </div>
                    </fieldset>



                </div>
                <div id="add_user_msg" class="form-row"> </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                </div>

            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<script>

    function AllTables() {
        usersTable();
        LoadSelect2Script(MakeSelect2);
    }
    function MakeSelect2() {
        $('select').select2();
        $('.dataTables_filter').each(function() {
            $(this).find('label input[type=text]').attr('placeholder', 'Search');
        });
    }
    $(document).ready(function() {
        // Load Datatables and run plugin on tables 
        LoadDataTablesScripts(AllTables);
        LoadBootstrapValidatorScript(usersAddForm);
        $('#reg_valid_til').datepicker({setDate: new Date()});
        $('#dob').datepicker({setDate: new Date()});
    });

</script>