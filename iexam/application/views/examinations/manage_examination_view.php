<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-bookmark-o"></i>
                    <span>Examinations</span>
                </div>

                <div class="no-move"></div>
            </div>

            <div class="box-content">
                <button class="btn btn-success" type="button" id="add_exam_btn" data-toggle="modal" data-target="#add_exam_modal" style="margin-bottom: 10px;">
                    Add Exam
                </button>
                <table class="table table-bordered table-striped table-hover table-heading table-datatable" id="exam_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Examination</th>
                            <th>Examination Type</th>
                            <th>Year</th>
                            <th>Semester</th>
                            <th>Course</th>
                            <th>Instructor</th>
                            <th>No Of MCQs</th>
                            <th>No Of Short Answer Ques</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        foreach ($exams as $exam) {
                            ?> 
                            <tr id="exam_<?php echo $exam->ExaminationID; ?>">
                                <td><?php echo ++$i; ?></td>
                                <td><?php echo $exam->Name; ?></td>
                                <td><?php echo $exam->ExaminationType; ?></td>
                                <td><?php echo $exam->Year; ?></td>
                                <td><?php echo $exam->Semester; ?></td>
                                <td><?php echo $exam->Course; ?></td>
                                <td><?php echo $exam->Instructor; ?></td>
                                <td><?php echo $exam->NumberOfMCQs; ?></td>
                                <td><?php echo $exam->NumberOfShortAnswerQuestions; ?></td>
                                <td><?php echo $exam->StartDate; ?></td>
                                <td><?php echo $exam->EndDate; ?></td>
                                <td>
                                    <?php
                                    if ($exam->Active == 1) {
                                        ?>
                                        <code class="txt-success">Active</code>
                                        <?php
                                    } else {
                                        ?>
                                        <code>Inactive</code>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a href="<?php echo site_url(); ?>/examinations/exams_controller/edit_examination_view/<?php echo $exam->ExaminationID; ?>" title="Edit this Exam">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a style="cursor: pointer;"   title="Delete this Exam" onclick="delete_exam(<?php echo $exam->ExaminationID; ?>)">
                                        <i class="fa fa-times"></i>
                                    </a>

                                </td>
                            </tr>
                        <?php } ?>  
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="add_exam_modal" tabindex="-1" role="dialog" aria-labelledby="add_exam_modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="add_exam_form" name="add_exam_form" class="form-horizontal bootstrap-validator-form">
                <div class="modal-header tiles green text-center">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <br>
                    <i class="fa fa-bookmark-o fa-4x"></i>
                    <h4 id="add_exam_modalLabel" class="semi-bold text-white">It's a new exam</h4>
                    <p class="no-margin text-white">Include exam details here.</p>
                    <br>
                </div>
                <div class="modal-body">
                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Exam Name</label>
                            <div class="col-sm-5">
                                <input id="exam_name" class="form-control" type="text" name="exam_name" >  
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
                                        <option value="<?php echo $examination_type->ExaminationTypeID; ?>"> <?php echo $examination_type->ExaminationType; ?> </option>
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
                                        <option value="<?php echo $semester->SemesterID; ?>"> <?php echo $semester->Semester; ?> </option>
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
                                        <option value="<?php echo $course->CourseID; ?>"> <?php echo $course->Course; ?> </option>
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
                                        <option value="<?php echo $instructor->InstructorID; ?>"> <?php echo $instructor->Instructor; ?> </option>
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
                                <input id="year" class="form-control" type="text" name="year" >  
                            </div>
                            <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                        </div>
                    </fieldset>


                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">No of MCQS</label>
                            <div class="col-sm-5">
                                <input id="no_mcq" class="form-control" type="text" name="no_mcq" >  
                            </div>
                            <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">No Of Short Answer Questions</label>
                            <div class="col-sm-5">
                                <input id="no_short_ans" class="form-control" type="text" name="no_short_ans" >  
                            </div>
                            <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                        </div>
                    </fieldset>


                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Start Date</label>
                            <div class="col-sm-5">
                                <input id="start_date" class="form-control" type="text" name="start_date" >  
                            </div>
                            <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                        </div>
                    </fieldset>
                    
                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">End Date</label>
                            <div class="col-sm-5">
                                <input id="end_date" class="form-control" type="text" name="end_date" >  
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
                                        <input type="radio"  checked="true" value="1" name="active">
                                        Yes
                                        <i class="fa fa-circle-o small"></i>
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" value="0" name="active" >
                                        No 
                                        <i class="fa fa-circle-o small"></i>
                                    </label>
                                </div>

                            </div>
                            <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                        </div>
                    </fieldset>


                </div>
                <div id="add_exam_msg" class="form-row"> </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                </div>

            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<script>

    function AllTables() {
        examTable();
        LoadSelect2Script(MakeSelect2);
    }
    function MakeSelect2() {
        $('select').select2();
        $('.dataTables_filter').each(function() {
            $(this).find('label input[type=text]').attr('placeholder', 'Search');
        });
    }
    $(document).ready(function() {
        // Load Datatables and run plugin on tables 
        LoadDataTablesScripts(AllTables);
        LoadBootstrapValidatorScript(examAddForm);
        $('#start_date').datepicker({setDate: new Date()});
        $('#end_date').datepicker({setDate: new Date()});
    });

</script>