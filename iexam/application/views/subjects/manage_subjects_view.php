<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-edit"></i>
                    <span>Subjects</span>
                </div>


            </div>
            <div class="box-content">
                <button class="btn btn-success" type="button" id="add_subject_btn" data-toggle="modal" data-target="#add_subject_modal">
                    Add Subject Type
                </button>
            </div>

            <div class="box-content">
                <table class="table table-bordered table-striped table-hover table-heading table-datatable" id="subject_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Subject</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        foreach ($subjects as $subject) {
                            ?> 
                            <tr id="subject_<?php echo $subject->SubjectID; ?>">
                                <td><?php echo ++$i; ?></td>
                                <td><?php echo $subject->Subject; ?></td>
                                <td>
                                    <a style="cursor: pointer" onclick="LoadAjaxContent('<?php echo site_url(); ?>/subjects/subjects_controller/edit_subject_view/<?php echo $subject->SubjectID; ?>')" title="Edit this Subject">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a style="cursor: pointer;"   title="Delete this Subject" onclick="delete_subject(<?php echo $subject->SubjectID; ?>)">
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
<div class="modal fade" id="add_subject_modal" tabindex="-1" role="dialog" aria-labelledby="add_subject_modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="add_subject_form" name="add_subject_form" class="form-horizontal bootstrap-validator-form">
                <div class="modal-header tiles green text-center">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <br>
                    <i class="fa fa-edit fa-4x"></i>
                    <h4 id="add_subject_modalLabel" class="semi-bold text-white">It's a new subject</h4>
                    <p class="no-margin text-white">Include subject details here.</p>
                    <br>
                </div>
                <div class="modal-body">
                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Subject</label>
                            <div class="col-sm-5">
                                <input id="subject_name" class="form-control" type="text" name="subject_name" >  
                            </div>
                            <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                        </div>
                    </fieldset>



                </div>
                <div id="add_subject_msg" class="form-row"> </div>
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
        subjectTable();
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
        LoadBootstrapValidatorScript(subjectAddForm);
    });

</script>