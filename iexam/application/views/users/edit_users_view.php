<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-flag-o"></i>
                    <span>User</span>
                </div>

                <div class="no-move"></div>
            </div>
            <div class="box-content">
                <form id="edit_user_form" name="edit_user_form" class="form-horizontal bootstrap-validator-form">
                    <div class="modal-header tiles green text-center">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <br>
                        <i class="fa fa-desktop fa-4x"></i>
                        <h4 id="edit_user_modalLabel" class="semi-bold text-white">Edit user </h4>
                        <p class="no-margin text-white">Include user details here.</p>
                        <br>
                    </div>
                    <div class="modal-body">
                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">User Level </label>
                                <div class="col-sm-5">
                                    <input id="user_level_name" class="form-control" type="text" name="user_level_name" value="<?php echo $user_level->userlevelname; ?>" >  
                                </div>
                                <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
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

