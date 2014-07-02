<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-flag-o"></i>
                    <span>Designations</span>
                </div>

                <div class="no-move"></div>
            </div>
            <div class="box-content">
                <form id="edit_designation_form" name="edit_designation_form" class="form-horizontal bootstrap-validator-form">
                    <div class="modal-header tiles green text-center">
                        <i class="fa fa-users fa-4x"></i>
                        <h4 id="edit_designation_modalLabel" class="semi-bold text-white">Edit designation</h4>
                        <p class="no-margin text-white">Include designation details here.</p>
                        <br>
                    </div>
                    <div class="modal-body">
                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Designation</label>
                                <div class="col-sm-5">
                                    <input id="designation_name" class="form-control" type="text" name="designation_name" value="<?php echo $designation->Designation; ?>" >  
                                </div>
                                <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                            </div>
                        </fieldset>

                        <input id="designation_id" class="form-control" type="hidden" name="designation_id" value="<?php echo $designation->DesignationID; ?>">  


                    </div>
                    <div id="edit_designation_msg" class="form-row"> </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-default" onclick="LoadAjaxContent('<?php echo site_url(); ?>/designations/designations_controller/manage_designations')">Back</button>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>


                            $(document).ready(function() {
                                LoadBootstrapValidatorScript(designationEditForm);
                            });

</script>