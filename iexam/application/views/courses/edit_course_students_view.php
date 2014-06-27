<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-flag-o"></i>
                    <span>Course Student</span>
                </div>

                <div class="no-move"></div>
            </div>
            <div class="box-content">

                <form id="edit_course_student_form" name="edit_course_student_form" class="form-horizontal bootstrap-validator-form">
                    <div class="modal-header tiles green text-center">

                        <i class="fa fa-desktop fa-4x"></i>
                        <h4 id="edit_course_student_modalLabel" class="semi-bold text-white">Edit course student</h4>
                        <p class="no-margin text-white">Include course student details here.</p>
                        <br>
                    </div>
                    <div class="modal-body">

                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Course</label>
                                <div class="col-sm-5">
                                    <select id="course_id" name="course_id">
                                        <option>Please Select</option>
                                        <?php foreach ($courses as $course) { ?>
                                            <option value="<?php echo $course->CourseID; ?>" <?php if ($course->CourseID == $course_student->CourseID) { ?> selected="true" <?php } ?>  > <?php echo $course_student->Course; ?> </option>
                                        <?php } ?>

                                    </select>
                                </div>
                                <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Student</label>
                                <div class="col-sm-5">
                                    <select id="student" name="student" >
                                        <option>Please Select</option>
                                        <?php foreach ($students as $student) { ?>
                                            <option value="<?php echo $student->UserID; ?>" <?php if ($student->UserID == $course_student->StudentID) { ?> selected="true" <?php } ?>  > <?php echo $student->FirstName; ?> </option>
                                        <?php } ?>

                                    </select>

                                </div>
                                <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                            </div>
                        </fieldset>
                    </div>
                    <div id="edit_course_student_msg" class="form-row"> </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

