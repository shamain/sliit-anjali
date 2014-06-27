<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-flag-o"></i>
                    <span>Semesters</span>
                </div>

                <div class="no-move"></div>
            </div>
            <div class="box-content">

                <form id="edit_semister_form" name="edit_semister_form" class="form-horizontal bootstrap-validator-form">
                    <div class="modal-header tiles green text-center"> 

                        <i class="fa fa-flag-o fa-4x"></i>
                        <h4 id="edit_semister_modalLabel" class="semi-bold text-white">Edit semester</h4>
                        <p class="no-margin text-white">Include semester details here.</p>
                        <br>
                    </div>
                    <div class="modal-body">
                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Semester</label>
                                <div class="col-sm-5">
                                    <input id="semister_name" class="form-control" type="text" name="semister_name" value="<?php echo $semister->Semester; ?>">  
                                </div>
                                <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                            </div>
                        </fieldset>

                        <input id="semister_id" class="form-control" type="hidden" name="semister_id" value="<?php echo $semister->SemesterID; ?>">  

                    </div>
                    <div id="edit_semister_msg" class="form-row"> </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-default"  onclick="history.go(-1);">Close</button>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#basic_setting_parent_menu').addClass('active-parent active');
    $('#semister_menu').removeClass('active-parent active');
</script>