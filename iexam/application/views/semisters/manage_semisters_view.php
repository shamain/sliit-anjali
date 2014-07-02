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
                <button class="btn btn-success" type="button" id="add_semister_btn" data-toggle="modal" data-target="#add_semister_modal">
                    Add Semester Type
                </button>
            </div>
            <div class="box-content">
                <table class="table table-bordered table-striped table-hover table-heading table-datatable" id="semester_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Semester</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        foreach ($semesters as $semester) {
                            ?> 
                            <tr id="semester_<?php echo $semester->SemesterID; ?>">
                                <td><?php echo++$i; ?></td>
                                <td><?php echo $semester->Semester; ?></td>
                                <td>
                                    <a style="cursor: pointer" onclick="LoadAjaxContent('<?php echo site_url(); ?>/semister/semister_controller/edit_semister_view/<?php echo $semester->SemesterID; ?>')"  title="Edit this Semester">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a style="cursor: pointer;"   title="Delete this Semester" onclick="delete_semester(<?php echo $semester->SemesterID; ?>)">
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
<div class="modal fade" id="add_semister_modal" tabindex="-1" role="dialog" aria-labelledby="add_semister_modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="add_semister_form" name="add_semister_form" class="form-horizontal bootstrap-validator-form">
                <div class="modal-header tiles green text-center"> 
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <br>
                    <i class="fa fa-flag-o fa-4x"></i>
                    <h4 id="add_semister_modalLabel" class="semi-bold text-white">It's a new semester</h4>
                    <p class="no-margin text-white">Include semester details here.</p>
                    <br>
                </div>
                <div class="modal-body">
                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Semester</label>
                            <div class="col-sm-5">
                                <input id="semister_name" class="form-control" type="text" name="semister_name" >  
                            </div>
                            <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                        </div>
                    </fieldset>



                </div>
                <div id="add_semister_msg" class="form-row"> </div>
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
                                        semisterTable();
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
                                        LoadBootstrapValidatorScript(semisterAddForm);
                                        LoadBootstrapValidatorScript(semisterEditForm);
                                    });

</script>