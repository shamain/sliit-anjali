<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-flag-o"></i>
                    <span>Examinations</span>
                </div>

                <div class="no-move"></div>
            </div>
            <div class="box-content">
                <form id="edit_exam_form" name="edit_exam_form" class="form-horizontal bootstrap-validator-form">
                    <div class="modal-header tiles green text-center">
                        <i class="fa fa-bookmark-o fa-4x"></i>
                        <h4 id="edit_exam_modalLabel" class="semi-bold text-white">Edit exam</h4>
                        <p class="no-margin text-white">Include exam details here.</p>
                        <br>
                    </div>
                    <div class="modal-body">
                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Exam Name</label>
                                <div class="col-sm-5">
                                    <input id="exam_name" class="form-control" type="text" name="exam_name" value="<?php echo $examination->Name; ?>">  
                                </div>
                                <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                            </div>
                        </fieldset>


                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Exam Types</label>
                                <div class="col-sm-5">
                                    <select id="exam_type_id" name="exam_type_id" >
                                        <option>Please Select</option>
                                        <?php foreach ($examination_types as $examination_type) { ?>
                                            <option value="<?php echo $examination_type->ExaminationTypeID; ?>" <?php if ($examination_type->ExaminationTypeID == $examination->ExaminationTypeID) { ?> selected="true" <?php } ?>> <?php echo $examination_type->ExaminationType; ?> </option>
                                        <?php } ?>

                                    </select>

                                </div>
                                <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Semester</label>
                                <div class="col-sm-5">
                                    <select id="semester_id" name="semester_id" >
                                        <option>Please Select</option>
                                        <?php foreach ($semesters as $semester) { ?>
                                            <option value="<?php echo $semester->SemesterID; ?>" <?php if ($semester->SemesterID == $examination->SeminsterID) { ?> selected="true" <?php } ?>> <?php echo $semester->Semester; ?> </option>
                                        <?php } ?>

                                    </select>

                                </div>
                                <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                            </div>
                        </fieldset>


                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Course</label>
                                <div class="col-sm-5">
                                    <select id="course_id" name="course_id">
                                        <option>Please Select</option>
                                        <?php foreach ($courses as $course) { ?>
                                            <option value="<?php echo $course->CourseID; ?>" <?php if ($course->CourseID == $examination->CourseID) { ?> selected="true" <?php } ?>> <?php echo $course->Course; ?> </option>
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
                                        <option>Please Select</option>
                                        <?php foreach ($instructors as $instructor) { ?>
                                            <option value="<?php echo $instructor->InstructorID; ?>" <?php if ($instructor->InstructorID == $examination->InsttructorID) { ?> selected="true" <?php } ?>> <?php echo $instructor->Instructor; ?> </option>
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
                                    <input id="year" class="form-control" type="text" name="year" value="<?php echo $examination->Year; ?>">  
                                </div>
                                <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                            </div>
                        </fieldset>


                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">No of MCQS</label>
                                <div class="col-sm-5">
                                    <input id="no_mcq" class="form-control" type="text" name="no_mcq" value="<?php echo $examination->NumberOfMCQs; ?>">  
                                </div>
                                <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">No Of Short Answer Questions</label>
                                <div class="col-sm-5">
                                    <input id="no_short_ans" class="form-control" type="text" name="no_short_ans" value="<?php echo $examination->NumberOfShortAnswerQuestions; ?>">  
                                </div>
                                <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                            </div>
                        </fieldset>


                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Start Date</label>
                                <div class="col-sm-5">
                                    <input id="start_date" class="form-control" type="text" name="start_date" value="<?php echo $examination->StartDate; ?>">  
                                </div>
                                <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">End Date</label>
                                <div class="col-sm-5">
                                    <input id="end_date" class="form-control" type="text" name="end_date" value="<?php echo $examination->EndDate; ?>">  
                                </div>
                                <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Active</label>
                                <div class="col-sm-5">

                                    <div class="radio">
                                        <label>
                                            <input type="radio"  <?php if ($examination->Active == 1) { ?> checked="true"<?php } ?> value="1" name="active">
                                            Yes
                                            <i class="fa fa-circle-o small"></i>
                                        </label>
                                    </div>

                                    <div class="radio">
                                        <label>
                                            <input type="radio" <?php if ($examination->Active == 0) { ?> checked="true"<?php } ?> value="0" name="active" >
                                            No 
                                            <i class="fa fa-circle-o small"></i>
                                        </label>
                                    </div>

                                </div>
                                <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                            </div>
                        </fieldset>

                        <input id="exam_id" class="form-control" type="hidden" name="exam_id" value="<?php echo $examination->ExaminationID; ?>">  
                    </div>
                    <div id="edit_exam_msg" class="form-row"> </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-default" onclick="LoadAjaxContent('<?php echo site_url(); ?>/examinations/exams_controller/manage_examinations')">Back</button>

                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<script>


    $(document).ready(function() {
        LoadBootstrapValidatorScript(examEditForm);
        $('#start_date').datepicker({setDate: new Date()});
        $('#end_date').datepicker({setDate: new Date()});
    });

</script>