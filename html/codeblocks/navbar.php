<nav class="navbar navbar-default navbar-static-top m-b-0">
            <div class="navbar-header">
                <div class="top-left-part">
                    <!-- Logo -->
                    <a class="logo" href="dashboard.php">
                        <b>
                        <img src="../plugins/images/admin-logo.png" alt="home" class="dark-logo" /><img src="../plugins/images/admin-logo-dark.png" alt="home" class="light-logo" />
                     </b>
                        <span class="hidden-xs">
                        <img src="../plugins/images/admin-text.png" alt="home" class="dark-logo" /><img src="../plugins/images/admin-text-dark.png" alt="home" class="light-logo" />
                     </span> </a>
                </div>
                <ul class="nav navbar-top-links navbar-left">
                    <li><a href="javascript:void(0)" class="open-close fa fa-bars waves-light"><i class="ti-menu"></i></a></li>
                </ul>
                <ul class="nav navbar-top-links navbar-right pull-right">
                    <li>
                        <a class="profile-pic" href="#"><b class="hidden-xs"><?php echo $_SESSION['usr_name']; ?></b></a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav slimscrollsidebar">
                <div class="sidebar-head">
                    <h3><span class="fa-fw open-close"><a href="#"><i class="fa fa-close"></i></a></span> <span class="hide-menu">Navigation</span></h3>
                </div>
                <ul class="nav" id="side-menu">
                    <li style="padding: 70px 0 0;">
                        <a href="dashboard.php" class="waves-effect"><i class="fa fa-dashboard fa-fw" aria-hidden="true"></i>Dashboard</a>
                    </li>
                    <li>
                        <a href="audio.php" class="waves-effect"><i class="fa fa-microphone fa-fw" aria-hidden="true"></i>Audio Setup</a>
                    </li>
                    <li>
                        <a href="control.php" class="waves-effect"><i class="fa fa-cogs fa-fw" aria-hidden="true"></i>Control Center</a>
                    </li>
                    <li>
                        <a href="files.php" class="waves-effect"><i class="fa fa-files-o fa-fw" aria-hidden="true"></i>File Sharing</a>
                    </li>
                    <li>
                        <a href="profile.php" class="waves-effect"><i class="fa fa-user fa-fw" aria-hidden="true"></i>My Profile</a>
                    </li>
                    <li>
                        <a href="users.php" class="waves-effect"><i class="fa fa-users fa-fw" aria-hidden="true"></i>Users</a>
                    </li>
                    <li>
                        <a href="logout.php" class="waves-effect"><i class="fa fa-sign-out fa-fw" aria-hidden="true"></i>Logout</a>
                    </li>
                </ul>
            </div>
        </div>