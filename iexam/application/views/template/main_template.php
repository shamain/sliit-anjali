<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Online Examination System</title>
        <meta name="description" content="description">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="<?php echo base_url(); ?>application_resources/plugins/bootstrap/bootstrap.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>application_resources/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
        <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Righteous' rel='stylesheet' type='text/css'>
        <link href="<?php echo base_url(); ?>application_resources/plugins/fancybox/jquery.fancybox.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>application_resources/plugins/fullcalendar/fullcalendar.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>application_resources/plugins/xcharts/xcharts.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>application_resources/plugins/select2/select2.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>application_resources/css/style.css" rel="stylesheet">
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
                        <script src="http://getbootstrap.com/docs-assets/js/html5shiv.js"></script>
                        <script src="http://getbootstrap.com/docs-assets/js/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <!--Start Header-->
        <div id="screensaver">
            <canvas id="canvas"></canvas>
            <i class="fa fa-lock" id="screen_unlock"></i>
        </div>
        <div id="modalbox">
            <div class="devoops-modal">
                <div class="devoops-modal-header">
                    <div class="modal-header-name">
                        <span>Basic table</span>
                    </div>
                    <div class="box-icons">
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="devoops-modal-inner">
                </div>
                <div class="devoops-modal-bottom">
                </div>
            </div>
        </div>
        <header class="navbar">
            <div class="container-fluid expanded-panel">
                <div class="row">
                    <div id="logo" class="col-xs-12 col-sm-2">
                        <a href="">Online Exam System</a>
                    </div>
                    <div id="top-panel" class="col-xs-12 col-sm-10">
                        <div class="row">
                            <div class="col-xs-8 col-sm-4">
                                <a href="#" class="show-sidebar">
                                    <i class="fa fa-bars"></i>
                                </a>
<!--                                <div id="search">
                                    <input type="text" placeholder="search"/>
                                    <i class="fa fa-search"></i>
                                </div>-->
                            </div>
                            <div class="col-xs-4 col-sm-8 top-panel-right">
                                <ul class="nav navbar-nav pull-right panel-menu">
                                    
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle account" data-toggle="dropdown">
                                            <div class="avatar">
                                                <?php if ($this->session->userdata('USER_PROPIC') == '') { ?>
                                                    <img src="<?php echo base_url(); ?>uploads/user_avatar/avatar_default.png" class="img-rounded" alt="avatar" />

                                                <?php } else { ?>
                                                    <img src="<?php echo base_url(); ?>uploads/user_avatar/<?php echo $this->session->userdata('USER_PROPIC'); ?>" class="img-rounded" alt="avatar" />

                                                <?php } ?> 
                        
                                            </div>
                                            <i class="fa fa-angle-down pull-right"></i>
                                            <div class="user-mini pull-right">
                                                <span class="welcome">Welcome,</span>
                                                <span><?php echo ucfirst($this->session->userdata('USER_FIRST_NAME') . ' ' . $this->session->userdata('USER_MIDDLE_NAME') . ' ' . $this->session->userdata('USER_LAST_NAME')); ?></span>
                                            </div>
                                        </a>
                                        <ul class="dropdown-menu">

                                            <li>
                                                <a href="<?php echo site_url(); ?>/login/login_controller/logout"">
                                                    <i class="fa fa-power-off"></i>
                                                    <span class="hidden-sm text">Logout</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!--End Header-->
        <!--Start Container-->
        <div id="main" class="container-fluid">
            <div class="row">
                <div id="sidebar-left" class="col-xs-2 col-sm-2">
                    <ul class="nav main-menu">
                        <li>
                            <a href="" class="active ajax-link">
                                <i class="fa fa-dashboard"></i>
                                <span class="hidden-xs">Dashboard</span>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" id="basic_setting_parent_menu">
                                <i class="fa fa-cog"></i>
                                <span class="hidden-xs">Basic Settings</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="ajax-link " href="<?php echo site_url(); ?>/semister/semister_controller/manage_semisters">Manage Semesters</a></li>
                                <li><a class="ajax-link" href="<?php echo site_url(); ?>/subjects/subjects_controller/manage_subjects">Manage Subjects</a></li>
                                <li><a class="ajax-link" href="<?php echo site_url(); ?>/designations/designations_controller/manage_designations">Manage Designations</a></li>
                                <li><a class="ajax-link" href="<?php echo site_url(); ?>/marital_statuses/marital_statuses_controller/manage_marital_statuses">Manage Marital Statuses</a></li>
                            </ul>
                        </li>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle">
                                <i class="fa fa-certificate"></i>
                                <span class="hidden-xs">Examinations</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="ajax-link" href="<?php echo site_url(); ?>/examinations/exams_controller/manage_examinations">Manage Exams</a></li>
                                <li><a class="ajax-link" href="<?php echo site_url(); ?>/examination_types/examination_types_controller/manage_examination_types">Manage Exam Types</a></li>

                            </ul>
                        </li>


                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle">
                                <i class="fa fa-user"></i>
                                <span class="hidden-xs">Users</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="ajax-link" href="<?php echo site_url(); ?>/users/users_controller/manage_users">Manage Users</a></li>
                                <li><a class="ajax-link" href="<?php echo site_url(); ?>/user_levels/user_levels_controller/manage_user_levels">Manage User Levels</a></li>

                            </ul>
                        </li>


                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle">
                                <i class="fa fa-briefcase"></i>
                                <span class="hidden-xs">Courses</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="ajax-link" href="<?php echo site_url(); ?>/courses/courses_controller/manage_courses">Manage Courses</a></li>
                                <li><a class="ajax-link" href="<?php echo site_url(); ?>/courses/course_instructor_controller/manage_instructors">Manage Course Instructor</a></li>
                                <li><a class="ajax-link" href="<?php echo site_url(); ?>/courses/course_student_controller/manage_course_students">Manage Course Students</a></li>

                            </ul>
                        </li>


                    </ul>
                </div>
                <!--Start Content-->
                <div id="content" class="col-xs-12 col-sm-10">
                    <div class="preloader">
                        <img src="<?php echo base_url(); ?>application_resources/img/devoops_getdata.gif" class="devoops-getdata" alt="preloader"/>
                    </div>
                    <div id="ajax-content">

                        <?php echo $content; ?>
                    </div>
                </div>
                <!--End Content-->
            </div>
        </div>

        <script type="text/javascript">

            var js_base_url = "<?php echo base_url(); ?>";
            var js_site_url = "<?php echo site_url(); ?>";

            //alert(js_url_path);
        </script>

        <!--End Container-->
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <!--<script src="http://code.jquery.com/jquery.js"></script>-->
        <script src="<?php echo base_url(); ?>application_resources/plugins/jquery/jquery-2.1.0.min.js"></script>
        <script src="<?php echo base_url(); ?>application_resources/plugins/jquery-ui/jquery-ui.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="<?php echo base_url(); ?>application_resources/plugins/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>application_resources/plugins/bootstrapvalidator/bootstrapValidator.min.js"></script>
        <script src="<?php echo base_url(); ?>application_resources/plugins/justified-gallery/jquery.justifiedgallery.min.js"></script>
        <script src="<?php echo base_url(); ?>application_resources/plugins/tinymce/tinymce.min.js"></script>
        <script src="<?php echo base_url(); ?>application_resources/plugins/tinymce/jquery.tinymce.min.js"></script>
        <!-- All functions for this theme + document.ready processing -->
        <script src="<?php echo base_url(); ?>application_resources/js/devoops.js"></script>
        
        

        <script src="<?php echo base_url(); ?>application_resources/custom_js/custom_basic.js"></script>
        <script src="<?php echo base_url(); ?>application_resources/custom_js/custom_exam.js"></script>
        <script src="<?php echo base_url(); ?>application_resources/custom_js/custom_course.js"></script>
        <script src="<?php echo base_url(); ?>application_resources/custom_js/custom_users.js"></script>
    </body>
</html>
