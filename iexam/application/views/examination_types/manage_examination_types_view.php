<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-bookmark-o"></i>
                    <span>Examination Types</span>
                </div>

                <div class="no-move"></div>
            </div>

            <div class="box-content">
                <button class="btn btn-success" type="button" id="add_exam_type_btn" data-toggle="modal" data-target="#add_exam_type_modal" style="margin-bottom: 10px;">
                    Add Exam Type
                </button>
                <table class="table table-bordered table-striped table-hover table-heading table-datatable" id="exam_type_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Examination Type</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        foreach ($examination_types as $examination_type) {
                            ?> 
                            <tr id="examtype_<?php echo $examination_type->ExaminationTypeID; ?>">
                                <td><?php echo ++$i; ?></td>
                                <td><?php echo $examination_type->ExaminationType; ?></td>
                                <td>
                                    <a style="cursor: pointer" onclick="LoadAjaxContent('<?php echo site_url(); ?>/examination_types/examination_types_controller/edit_examination_type_view/<?php echo $examination_type->ExaminationTypeID; ?>')"  title="Edit this Exam Type">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a style="cursor: pointer;"   title="Delete this Exam Type" onclick="delete_examtype(<?php echo $examination_type->ExaminationTypeID; ?>)">
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
<div class="modal fade" id="add_exam_type_modal" tabindex="-1" role="dialog" aria-labelledby="add_exam_type_modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="add_exam_type_form" name="add_exam_type_form" class="form-horizontal bootstrap-validator-form">
                <div class="modal-header tiles green text-center">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <br>
                    <i class="fa fa-bookmark-o fa-4x"></i>
                    <h4 id="add_exam_type_modalLabel" class="semi-bold text-white">It's a new exam type</h4>
                    <p class="no-margin text-white">Include exam type details here.</p>
                    <br>
                </div>
                <div class="modal-body">
                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Exam Type</label>
                            <div class="col-sm-5">
                                <input id="exam_type_name" class="form-control" type="text" name="exam_type_name" >  
                            </div>
                            <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                        </div>
                    </fieldset>



                </div>
                <div id="add_exam_type_msg" class="form-row"> </div>
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
        examtypeTable();
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
        LoadBootstrapValidatorScript(examtypeAddForm);
    });

</script>