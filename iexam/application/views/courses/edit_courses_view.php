<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-flag-o"></i>
                    <span>Courses</span>
                </div>

                <div class="no-move"></div>
            </div>
            <div class="box-content">

                <form id="edit_course_form" name="edit_course_form" class="form-horizontal bootstrap-validator-form">
                    <div class="modal-header tiles green">

                        <i class="fa fa-desktop fa-4x"></i>
                        <h4 id="edit_course_modalLabel" class="semi-bold text-white">Edit course</h4>
                        <p class="no-margin text-white">Include course details here.</p>
                        <br>
                    </div>
                    <div class="modal-body">


                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Course</label>
                                <div class="col-sm-5">
                                    <input id="course_name" class="form-control" type="text" name="course_name" value="<?php echo $course->Course; ?>">  
                                </div>
                                <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Subject</label>
                                <div class="col-sm-5">
                                    <select id="subject_id" name="subject_id" >
                                        <option>Please Select</option>
                                        <?php foreach ($subjects as $subject) { ?>
                                        <option value="<?php echo $subject->SubjectID; ?>"  <?php if($subject->SubjectID == $course->SubjectID){?> selected="true" <?php } ?>> <?php echo $subject->Subject; ?> </option>
                                        <?php } ?>

                                    </select>

                                </div>
                                <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                            </div>
                        </fieldset>


                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Course Code</label>
                                <div class="col-sm-5">
                                    <input id="course_code" class="form-control" type="text" name="course_code" value="<?php echo $course->CourseCode; ?>" >  
                                </div>
                                <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                            </div>
                        </fieldset>

                        <input id="course_id" class="form-control" type="hidden" name="course_id" value="<?php echo $course->CourseID; ?>">  

                    </div>
                    <div id="edit_course_msg" class="form-row"> </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

