<!-- Begin Main Menu -->
<div class="ewMenu">
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(22, $Language->MenuPhrase("22", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(6, $Language->MenuPhrase("6", "MenuText"), "t_examinationslist.php", 22, "", AllowListMenu('{FDF9D461-C8C4-4B01-893C-EAF280CCB667}t_examinations'), FALSE);
$RootMenu->AddMenuItem(5, $Language->MenuPhrase("5", "MenuText"), "t_examination_typeslist.php", 22, "", AllowListMenu('{FDF9D461-C8C4-4B01-893C-EAF280CCB667}t_examination_types'), FALSE);
$RootMenu->AddMenuItem(3, $Language->MenuPhrase("3", "MenuText"), "t_courseslist.php", -1, "", AllowListMenu('{FDF9D461-C8C4-4B01-893C-EAF280CCB667}t_courses'), FALSE);
$RootMenu->AddMenuItem(1, $Language->MenuPhrase("1", "MenuText"), "t_course_instructorlist.php", 3, "", AllowListMenu('{FDF9D461-C8C4-4B01-893C-EAF280CCB667}t_course_instructor'), FALSE);
$RootMenu->AddMenuItem(2, $Language->MenuPhrase("2", "MenuText"), "t_course_studentslist.php", 3, "", AllowListMenu('{FDF9D461-C8C4-4B01-893C-EAF280CCB667}t_course_students'), FALSE);
$RootMenu->AddMenuItem(13, $Language->MenuPhrase("13", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(8, $Language->MenuPhrase("8", "MenuText"), "t_semisterslist.php", 13, "", AllowListMenu('{FDF9D461-C8C4-4B01-893C-EAF280CCB667}t_semisters'), FALSE);
$RootMenu->AddMenuItem(9, $Language->MenuPhrase("9", "MenuText"), "t_subjectslist.php", 13, "", AllowListMenu('{FDF9D461-C8C4-4B01-893C-EAF280CCB667}t_subjects'), FALSE);
$RootMenu->AddMenuItem(4, $Language->MenuPhrase("4", "MenuText"), "t_designationslist.php", 13, "", AllowListMenu('{FDF9D461-C8C4-4B01-893C-EAF280CCB667}t_designations'), FALSE);
$RootMenu->AddMenuItem(7, $Language->MenuPhrase("7", "MenuText"), "t_marital_statuseslist.php", 13, "", AllowListMenu('{FDF9D461-C8C4-4B01-893C-EAF280CCB667}t_marital_statuses'), FALSE);
$RootMenu->AddMenuItem(14, $Language->MenuPhrase("14", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(10, $Language->MenuPhrase("10", "MenuText"), "t_userslist.php", 14, "", AllowListMenu('{FDF9D461-C8C4-4B01-893C-EAF280CCB667}t_users'), FALSE);
$RootMenu->AddMenuItem(12, $Language->MenuPhrase("12", "MenuText"), "userlevelslist.php", 14, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE);
$RootMenu->AddMenuItem(-1, $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
</div>
<!-- End Main Menu -->
