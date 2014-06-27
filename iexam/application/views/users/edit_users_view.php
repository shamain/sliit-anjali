
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-user-md"></i>
                    <span>User</span>
                </div>

                <div class="no-move"></div>
            </div>
            <div class="box-content">
                <form id="edit_user_form" name="edit_user_form" class="form-horizontal bootstrap-validator-form">
                    <div class="modal-header tiles green text-center">

                        <i class="fa  fa-user fa-4x"></i>
                        <h4 id="edit_user_modalLabel" class="semi-bold text-white">Edit user </h4>
                        <p class="no-margin text-white">Include user details here.</p>
                        <br>
                    </div>
                    <div class="modal-body">

                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">First Name</label>
                                <div class="col-sm-5">
                                    <input id="first_name" class="form-control" type="text" name="first_name" value="<?php echo $user->FirstName; ?>">  
                                </div>
                                <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Middle Name</label>
                                <div class="col-sm-5">
                                    <input id="middle_name" class="form-control" type="text" name="middle_name" value="<?php echo $user->MiddleName; ?>" >  
                                </div>
                                <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Last Name</label>
                                <div class="col-sm-5">
                                    <input id="last_name" class="form-control" type="text" name="last_name" value="<?php echo $user->LastName; ?>" >  
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
                                            <option value="<?php echo $user_level->userlevelid; ?>" <?php if ($user_level->userlevelid == $user->UserLevel) { ?> selected="true"<?php } ?>> <?php echo $user_level->userlevelname; ?> </option>
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
                                            <option value="<?php echo $designation->DesignationID; ?>" <?php if ($designation->DesignationID == $user->DesignationID) { ?> selected="true"<?php } ?>> <?php echo $designation->Designation; ?> </option>
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
                                            <option value="<?php echo $marital_status->MaritalStatusID; ?>" <?php if ($marital_status->MaritalStatusID == $user->MaritalStatusID) { ?> selected="true"<?php } ?>> <?php echo $marital_status->MaritalStatus; ?> </option>
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
                                    <input id="email" class="form-control" type="text" name="email"  value="<?php echo $user->Email; ?>">  
                                </div>
                                <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Registration Number</label>
                                <div class="col-sm-5">
                                    <input id="reg_number" class="form-control" type="text" name="reg_number"  value="<?php echo $user->RegistrationNumber; ?>">  
                                </div>
                                <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Nic No</label>
                                <div class="col-sm-5">
                                    <input id="nic" class="form-control" type="text" name="nic"  value="<?php echo $user->NICNumber; ?>">  
                                </div>
                                <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">User Name</label>
                                <div class="col-sm-5">
                                    <input id="user_name" class="form-control" type="text" name="user_name" value="<?php echo $user->Username; ?>">  
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
                                            <input type="radio"  <?php if ($user->Gender == 0) { ?> checked="true"<?php } ?> value="0" name="gender">
                                            Male
                                            <i class="fa fa-circle-o small"></i>
                                        </label>
                                    </div>

                                    <div class="radio">
                                        <label>
                                            <input type="radio" <?php if ($user->Gender == 1) { ?> checked="true"<?php } ?> value="1" name="gender" >
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
                                    <input id="dob" class="form-control" type="text" name="dob" value="<?php echo $user->DateOfBirth; ?>">  
                                </div>
                                <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Registration Valid Till</label>
                                <div class="col-sm-5">
                                    <input id="reg_valid_til" class="form-control" type="text" name="reg_valid_til" value="<?php echo $user->RegistrationValidTill; ?>">  
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
                                            <input type="radio"  <?php if ($user->Activated == 1) { ?> checked="true"<?php } ?> value="1" name="active_status">
                                            Yes
                                            <i class="fa fa-circle-o small"></i>
                                        </label>
                                    </div>

                                    <div class="radio">
                                        <label>
                                            <input type="radio" <?php if ($user->Activated == 0) { ?> checked="true"<?php } ?> value="0" name="active_status" >
                                            No 
                                            <i class="fa fa-circle-o small"></i>
                                        </label>
                                    </div>

                                </div>
                                <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                            </div>
                        </fieldset>





                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Upload Photo</label>
                                <div class="col-sm-5">
                                    <div id="upload">
                                        <input type="text" id="image" name="image" value="<?php echo $user->PhotoPath; ?>"/><input type="button"  value="Browse" id="browse" class="btn btn-default "/>
                                        <input type="text" id="pro_image" name="pro_image" style="visibility: hidden" value="<?php echo $user->PhotoPath; ?>" />

                                    </div>

                                    <div id="files" >
                                        <img src="<?PHP echo base_url(); ?>uploads/user_avatar/<?php echo $user->PhotoPath; ?>" alt="" width="100px" height="100px" />
                                    </div>

                                    <div id="sta"><span id="status" ></span></div>
                                </div>

                            </div>
                        </fieldset>

                        <input id="user_id" class="form-control" type="hidden" name="user_id" value="<?php echo $user->UserID; ?>">

                    </div>
                    <div id="edit_user_msg" class="form-row"> </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

