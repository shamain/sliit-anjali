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

            <button class="btn btn-success btn-app-sm" type="button" id="add_user_btn" data-toggle="modal" data-target="#add_user_modal">
                <i class="fa fa-plus"></i>
            </button>

            <div class="box-content no-padding">
                <table class="table table-bordered table-striped table-hover table-heading table-datatable" id="user_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Designation</th>
                            <th>User Level</th>
                            <th>Email</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        foreach ($users as $user) {
                            ?> 
                            <tr id="user_<?php echo $user->UserID; ?>">
                                <td><?php echo ++$i; ?></td>
                                <td><?php echo $user->FirstName . ' ' . $user->MiddleName . ' ' . $user->LastName; ?></td>
                                <td><?php echo $user->Designation; ?></td>
                                <td><?php echo $user->userlevelname; ?></td>
                                <td><?php echo $user->Email; ?></td>
                                <td>
                                    <a href="<?php echo site_url(); ?>/project/project_controller/edit_project_view/<?php echo $user->UserID; ?>" title="Edit this User">
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
                <div class="modal-header tiles green">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <br>
                    <i class="fa fa-desktop fa-4x"></i>
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
                                <select id="user_level" name="user_level" class="form-control">
                                    <?php foreach ($subjects as $subject) { ?>
                                        <option value="<?php echo $subject->SubjectID; ?>"> <?php echo $subject->Subject; ?> </option>
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
                                <select id="designation_id" name="designation_id" class="form-control">
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
                                <select id="marital_status_id" name="marital_status_id" class="form-control">
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
                            <label class="col-sm-3 control-label">Semester</label>
                            <div class="col-sm-5">
                                <input id="subject_name" class="form-control" type="text" name="subject_name" >  
                            </div>
                            <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                        </div>
                    </fieldset>



                </div>
                <div id="add_subject_msg" class="form-row"> </div>
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
        subjectTable();
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
        LoadBootstrapValidatorScript(subjectAddForm);
    });

</script>