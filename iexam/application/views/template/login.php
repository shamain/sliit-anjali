<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Online Examination System</title>
        <meta name="description" content="description">
        <meta name="keyword" content="keywords">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="<?php echo base_url(); ?>application_resources/plugins/bootstrap/bootstrap.css" rel="stylesheet">
        <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Righteous' rel='stylesheet' type='text/css'>
        <link href="<?php echo base_url(); ?>application_resources/css/style.css" rel="stylesheet">
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
                        <script src="http://getbootstrap.com/docs-assets/js/html5shiv.js"></script>
                        <script src="http://getbootstrap.com/docs-assets/js/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="container-fluid">
            <div id="page-login" class="row">
                <form id="login_form" name="login_form" class="bootstrap-validator-form">
                    <div class="col-xs-12 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
                        <div class="box">
                            <div class="box-content">
                                <div class="text-center">
                                    <h3 class="page-header">Online Examination System</h3>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Username</label>
                                    <input type="text" class="form-control" name="txtusername" id="txtusername" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Password</label>
                                    <input type="password" class="form-control" name="txtpassword" id="txtpassword" />
                                </div>
                                <div class="text-center">
                                    <input type="submit" class="btn btn-primary" value="Sign in" />
                                </div>
                                <div id="msg" class="form-row"> 
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>

<script type="text/javascript">

                                        var js_base_url = "<?php echo base_url(); ?>";
                                        var js_site_url = "<?php echo site_url(); ?>";

                                        //alert(js_url_path);
</script>

<script src="<?php echo base_url(); ?>application_resources/plugins/jquery/jquery-2.1.0.min.js"></script>
<script src="<?php echo base_url(); ?>application_resources/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?php echo base_url(); ?>application_resources/plugins/bootstrap/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>application_resources/plugins/justified-gallery/jquery.justifiedgallery.min.js"></script>
<script src="<?php echo base_url(); ?>application_resources/plugins/tinymce/tinymce.min.js"></script>
<script src="<?php echo base_url(); ?>application_resources/plugins/tinymce/jquery.tinymce.min.js"></script>
<!-- All functions for this theme + document.ready processing -->
<script src="<?php echo base_url(); ?>application_resources/js/devoops.js"></script>

<script src="<?php echo base_url(); ?>application_resources/custom_js/login.js"></script>

<script>

    $(document).ready(function() {

        LoadBootstrapValidatorScript(LoginForm);
    });

</script>