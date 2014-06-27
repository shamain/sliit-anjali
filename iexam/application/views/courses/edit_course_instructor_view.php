<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-flag-o"></i>
                    <span>Course Instructors</span>
                </div>

                <div class="no-move"></div>
            </div>
            <div class="box-content">

                <form id="edit_course_instructor_form" name="edit_course_instructor_form" class="form-horizontal bootstrap-validator-form">
                    <div class="modal-header tiles green text-center">
                        <i class="fa fa-globe fa-4x"></i>
                        <h4 id="edit_course_instructor_modalLabel" class="semi-bold text-white">Edit course instructor</h4>
                        <p class="no-margin text-white">Include course instructor details here.</p>
                        <br>
                    </div>
                    <div class="modal-body">
                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Course</label>
                                <div class="col-sm-5">
                                    <select id="course_id" name="course_id">
                                        <?php foreach ($courses as $course) { ?>
                                            <option value="<?php echo $course->CourseID; ?>" <?php if ($course->CourseID == $instructor->CourseID) { ?> selected="true" <?php } ?>> <?php echo $course->Course; ?> </option>
                                        <?php } ?>

                                    </select>    
                                </div>
                                <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Year</label>
                                <div class="col-sm-5">
                                    <input id="year" class="form-control" type="text" name="year" value="<?php echo $instructor->Year; ?>">  
                                </div>
                                <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Semester</label>
                                <div class="col-sm-5">
                                    <select id="semester_id" name="semester_id" >
                                        <?php foreach ($semesters as $semester) { ?>
                                            <option value="<?php echo $semester->SemesterID; ?>" <?php if ($semester->SemesterID == $instructor->SemisterID) { ?> selected="true" <?php } ?> > <?php echo $semester->Semester; ?> </option>
                                        <?php } ?>

                                    </select>

                                </div>
                                <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                            </div>
                        </fieldset>


                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Instructor</label>
                                <div class="col-sm-5">
                                    <select id="instructor_id" name="instructor_id" >
                                        <?php foreach ($instructors as $instruc) { ?>
                                            <option value="<?php echo $instruc->InstructorID; ?>" <?php if ($instruc->InstructorID == $instructor->InstructorID) { ?> selected="true" <?php } ?> > <?php echo $instruc->Instructor; ?> </option>
                                        <?php } ?>

                                    </select>
                                </div>
                                <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                            </div>
                        </fieldset>

                        <input id="course_ins_id"  type="hidden" name="course_ins_id" value="<?php echo $instructor->CourseInstructorID; ?>">  

                    </div>
                    <div id="edit_course_instructor_msg" class="form-row"> </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

