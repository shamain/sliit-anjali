<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-users"></i>
                    <span>Designations</span>
                </div>
              
            </div>
            <div class="box-content">
            <button class="btn btn-success" type="button" id="add_designation_btn" data-toggle="modal" data-target="#add_designation_modal">
               Add Designation Type
            </button>
            </div>
            <div class="box-content">
                <table class="table table-bordered table-striped table-hover table-heading table-datatable" id="designation_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Designation</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        foreach ($designations as $designation) {
                            ?> 
                            <tr id="designation_<?php echo $designation->DesignationID; ?>">
                                <td><?php echo++$i; ?></td>
                                <td><?php echo $designation->Designation; ?></td>
                                <td>
                                    <a href="<?php echo site_url(); ?>/project/project_controller/edit_project_view/<?php echo $designation->DesignationID; ?>" title="Edit this Designation">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a style="cursor: pointer;"   title="Delete this Designation" onclick="delete_designation(<?php echo $designation->DesignationID; ?>)">
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
<div class="modal fade" id="add_designation_modal" tabindex="-1" role="dialog" aria-labelledby="add_designation_modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="add_designation_form" name="add_designation_form" class="form-horizontal bootstrap-validator-form">
                <div class="modal-header tiles green text-center">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <br>
                    <i class="fa fa-users fa-4x"></i>
                    <h4 id="add_designation_modalLabel" class="semi-bold text-white">It's a new designation</h4>
                    <p class="no-margin text-white">Include designation details here.</p>
                    <br>
                </div>
                <div class="modal-body">
                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Designation</label>
                            <div class="col-sm-5">
                                <input id="designation_name" class="form-control" type="text" name="designation_name" >  
                            </div>
                            <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                        </div>
                    </fieldset>

                   

                </div>
                <div id="add_designation_msg" class="form-row"> </div>
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
                                            designationTable();
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
                                            LoadBootstrapValidatorScript(designationAddForm);
                                        });

</script>