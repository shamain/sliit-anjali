<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-flag-o"></i>
                    <span>Examination Types</span>
                </div>

                <div class="no-move"></div>
            </div>
            <div class="box-content">
                <form id="edit_exam_type_form" name="edit_exam_type_form" class="form-horizontal bootstrap-validator-form">
                    <div class="modal-header tiles green text-center">
                        <i class="fa fa-bookmark-o fa-4x"></i>
                        <h4 id="edit_exam_type_modalLabel" class="semi-bold text-white">Edit exam type</h4>
                        <p class="no-margin text-white">Include exam type details here.</p>
                        <br>
                    </div>
                    <div class="modal-body">
                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Exam Type</label>
                                <div class="col-sm-5">
                                    <input id="exam_type_name" class="form-control" type="text" name="exam_type_name" value="<?php echo $examination_type->ExaminationType; ?>">  
                                </div>
                                <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                            </div>
                        </fieldset>

                        <input id="exam_type_id" class="form-control" type="hidden" name="exam_type_id" value="<?php echo $examination_type->ExaminationTypeID; ?>">  

                    </div>
                    <div id="edit_exam_type_msg" class="form-row"> </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

