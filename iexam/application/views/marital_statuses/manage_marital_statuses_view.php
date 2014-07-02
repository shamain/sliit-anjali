<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-plus-circle"></i>
                    <span>Marital Status</span>
                </div>
             
            </div>
             <div class="box-content">
            <button class="btn btn-success" type="button" id="add_marital_status_btn" data-toggle="modal" data-target="#add_marital_status_modal">
               Add Marital Status
            </button>
             </div>
            <div class="box-content">
                <table class="table table-bordered table-striped table-hover table-heading table-datatable" id="marital_status_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Marital Status</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        foreach ($marital_statuses as $marital_statuse) {
                            ?> 
                            <tr id="maritalstatus_<?php echo $marital_statuse->MaritalStatusID; ?>">
                                <td><?php echo++$i; ?></td>
                                <td><?php echo $marital_statuse->MaritalStatus; ?></td>
                                <td>
                                    <a style="cursor: pointer" onclick="LoadAjaxContent('<?php echo site_url(); ?>/marital_statuses/marital_statuses_controller/edit_marital_status_view/<?php echo $marital_statuse->MaritalStatusID; ?>')"  title="Edit this Marital Statuse">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a style="cursor: pointer;"   title="Delete this Marital Status" onclick="delete_maritalstatus(<?php echo $marital_statuse->MaritalStatusID; ?>)">
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
<div class="modal fade" id="add_marital_status_modal" tabindex="-1" role="dialog" aria-labelledby="add_marital_status_modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="add_marital_status_form" name="add_marital_status_form" class="form-horizontal bootstrap-validator-form">
                <div class="modal-header tiles green text-center">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <br>
                    <i class="fa fa-plus-circle fa-4x"></i>
                    <h4 id="add_marital_status_modalLabel" class="semi-bold text-white">It's a new marital status</h4>
                    <p class="no-margin text-white">Include marital status details here.</p>
                    <br>
                </div>
                <div class="modal-body">
                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Marital Status</label>
                            <div class="col-sm-5">
                                <input id="status_name" class="form-control" type="text" name="status_name" >  
                            </div>
                            <small class="help-block col-sm-offset-3 col-sm-9" style="display: none;"></small>
                        </div>
                    </fieldset>

                   

                </div>
                <div id="add_marital_status_msg" class="form-row"> </div>
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
                                            maritalstatusTable();
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
                                            LoadBootstrapValidatorScript(maritalstatusAddForm);
                                        });

</script>