<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-globe"></i>
                    <span>Course Instructors</span>
                </div>
                <div class="box-icons">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="expand-link">
                        <i class="fa fa-expand"></i>
                    </a>
                    <a class="close-link">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
                <div class="no-move"></div>
            </div>

            <div class="box-content">
                <button class="btn btn-success" type="button" id="add_course_instructor_btn" data-toggle="modal" data-target="#add_course_instructor_modal">
                    Add Course Instructor
                </button>
            </div>

            <div class="box-content no-padding">
                <table class="table table-bordered table-striped table-hover table-heading table-datatable" id="instructor_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Course Name</th>
                            <th>Year</th>
                            <th>Semester</th>
                            <th>Instructor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        foreach ($instructors as $instructor) {
                            ?> 
                            <tr id="course_instructor_<?php echo $instructor->CourseInstructorID; ?>">
                                <td><?php echo ++$i; ?></td>
                                <td><?php echo $instructor->Course; ?></td>
                                <td><?php echo $instructor->Year; ?></td>
                                <td><?php echo $instructor->Semester; ?></td>
                                <td><?php echo $instructor->Instructor; ?></td>
                                <td>
                                    <a href="<?php echo site_url(); ?>/courses/course_instructor_controller/delete_course_instructor/<?php echo $instructor->CourseInstructorID; ?>" title="Edit this Course Instructor">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a style="cursor: pointer;"   title="Delete this Course Instructor" onclick="delete_course_instructor(<?php echo $instructor->CourseInstructorID; ?>)">
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
<div class="modal fade" id="add_course_instructor_modal" tabindex="-1" role="dialog" aria-labelledby="add_course_instructor_modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="add_course_instructor_form" name="add_course_instructor_form" class="form-horizontal bootstrap-validator-form">
                <div class="modal-header tiles green text-center">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <br>
                    <i class="fa fa-globe fa-4x"></i>
                    <h4 id="add_course_instructor_modalLabel" class="semi-bold text-white">It's a new course instructor</h4>
                    <p class="no-margin text-white">Include course instructor details here.</p>
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
                                        <option value="<?php echo $course->CourseID; ?>"> <?php echo $course->Course; ?> </option>
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



                </div>
                <div id="add_course_instructor_msg" class="form-row"> </div>
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
        courseInstructorTable();
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
        LoadBootstrapValidatorScript(courseInstructorAddForm);
    });

</script>