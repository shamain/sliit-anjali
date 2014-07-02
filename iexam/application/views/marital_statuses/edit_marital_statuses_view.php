<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-flag-o"></i>
                    <span>Marital Status</span>
                </div>

                <div class="no-move"></div>
            </div>
            <div class="box-content">

                <form id="edit_marital_status_form" name="edit_marital_status_form" class="form-horizontal bootstrap-validator-form">
                    <div class="modal-header tiles green text-center">

                        <i class="fa fa-plus-circle fa-4x"></i>
                        <h4 id="edit_marital_status_modalLabel" class="semi-bold text-white">Edit marital status</h4>
                        <p class="no-margin text-white">Include marital status details here.</p>
                        <br>
                    </div>
                    <div class="modal-body">
                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Marital Status</label>
                                <div class="col-sm-5">
                                    <input id="status_name" class="form-control" type="text" name="status_name" value="<?php echo $marital_status->MaritalStatus; ?>" >  
                                </div>
                                <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                            </div>
                        </fieldset>

                        <input id="status_id" class="form-control" type="hidden" name="status_id" value="<?php echo $marital_status->MaritalStatusID; ?>">  

                    </div>
                    <div id="edit_marital_status_msg" class="form-row"> </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-default" onclick="LoadAjaxContent('<?php echo site_url(); ?>/marital_statuses/marital_statuses_controller/manage_marital_statuses')">Back</button>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>


                            $(document).ready(function() {
                                LoadBootstrapValidatorScript(maritalstatusEditForm);
                            });

</script>