<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-flag-o"></i>
                    <span>Subjects</span>
                </div>

                <div class="no-move"></div>
            </div>
            <div class="box-content">

                <form id="edit_subject_form" name="edit_subject_form" class="form-horizontal bootstrap-validator-form">
                    <div class="modal-header tiles green text-center">
                        <i class="fa fa-edit fa-4x"></i>
                        <h4 id="edit_subject_modalLabel" class="semi-bold text-white">Edit subject</h4>
                        <p class="no-margin text-white">Include subject details here.</p>
                        <br>
                    </div>
                    <div class="modal-body">
                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Subject</label>
                                <div class="col-sm-5">
                                    <input id="subject_name" class="form-control" type="text" name="subject_name" value="<?php echo $subject->Subject; ?>">  
                                </div>
                                <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                            </div>
                        </fieldset>

                        <input id="subject_id" class="form-control" type="hidden" name="subject_id" value="<?php echo $subject->SubjectID; ?>">  

                    </div>
                    <div id="edit_subject_msg" class="form-row"> </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-default" onclick="LoadAjaxContent('<?php echo site_url(); ?>/subjects/subjects_controller/manage_subjects')">Back</button>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>


                            $(document).ready(function() {
                                LoadBootstrapValidatorScript(subjectEditForm);
                            });

</script>