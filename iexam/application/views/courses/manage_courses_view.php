<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-credit-card"></i>
                    <span>Courses</span>
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
                <button class="btn btn-success " type="button" id="add_course_btn" data-toggle="modal" data-target="#add_course_modal">
                    Add Course
                </button>
            </div>
            <div class="box-content no-padding">
                <table class="table table-bordered table-striped table-hover table-heading table-datatable" id="course_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Course Code</th>
                            <th>Course</th>
                            <th>Subject</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        foreach ($courses as $course) {
                            ?> 
                            <tr id="course_<?php echo $course->CourseID; ?>">
                                <td><?php echo ++$i; ?></td>
                                <td><?php echo $course->CourseCode; ?></td>
                                <td><?php echo $course->Course; ?></td>
                                <td><?php echo $course->Subject; ?></td>
                                <td>
                                    <a style="cursor: pointer" onclick="LoadAjaxContent('<?php echo site_url(); ?>/courses/courses_controller/edit_course_view/<?php echo $course->CourseID; ?>')"  title="Edit this Course">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a style="cursor: pointer;"   title="Delete this Course" onclick="delete_course(<?php echo $course->CourseID; ?>)">
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
<div class="modal fade" id="add_course_modal" tabindex="-1" role="dialog" aria-labelledby="add_course_modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="add_course_form" name="add_course_form" class="form-horizontal bootstrap-validator-form">
                <div class="modal-header tiles green text-center">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <br>
                    <i class="fa fa-credit-card fa-4x"></i>
                    <h4 id="add_course_modalLabel" class="semi-bold text-white">It's a new course</h4>
                    <p class="no-margin text-white">Include course details here.</p>
                    <br>
                </div>
                <div class="modal-body">


                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Course</label>
                            <div class="col-sm-5">
                                <input id="course_name" class="form-control" type="text" name="course_name" >  
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
                                        <option value="<?php echo $subject->SubjectID; ?>"> <?php echo $subject->Subject; ?> </option>
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
                                <input id="course_code" class="form-control" type="text" name="course_code" >  
                            </div>
                            <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                        </div>
                    </fieldset>



                </div>
                <div id="add_course_msg" class="form-row"> </div>
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
        courseTable();
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
        LoadBootstrapValidatorScript(courseAddForm);
    });

</script>