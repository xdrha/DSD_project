<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Messenger</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="../dist/css/skins/skin-blue.min.css">
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <style>
    html {
      --scrollbarBG: #444444;
      --thumbBG: #3c8dbc;
    }

    .messenger::-webkit-scrollbar {
      width: 11px;
    }

    .messenger::-webkit-scrollbar-track {
      background: var(--scrollbarBG);
    }

    .messenger::-webkit-scrollbar-thumb {
      background-color: var(--thumbBG);
      border-radius: 6px;
      border: 3px solid var(--scrollbarBG);
    }

    .messenger {
      overflow-y: auto;
      flex-grow: 1;
      flex-shrink: 1;
      flex-basis: auto;
      padding: 0 1rem;
      max-width: 40vw;
      border-width: 10;
      max-height: 80vh;
      scrollbar-width: thin;
      scrollbar-color: var(--thumbBG) var(--scrollbarBG);
    }

    .messenger>ul {
      display: flex;
      flex-direction: column;
      list-style: none;
      padding-left: 0px;
    }

    .messenger>ul>li {
      margin: 1rem 0;
      padding: 1rem;
      display: flex;
      flex-direction: column;
      max-width: 25vw;
      border-radius: 20px;
      background-color: #444444;
      box-shadow: 5px 5px 35px -30px rgba(255, 255, 255, 0.75);
      color: #ffffff;
    }

    .messenger>ul>li>small {
      margin-top: .5rem;
      color: #ffffff;
    }

    .messenger>ul>li.me {
      align-self: flex-end;
      background-color: #3c8dbc;
      position: relative;
      color: #ffffff;
    }

    .messenger>ul>li.me>small {
      align-self: flex-end;
      color: #ffffff;
    }

    .messenger>ul>li.me>small>span {
      color: rgb(5, 144, 20);
      margin-right: .75rem;
      font-weight: bold;
    }

    .messenger>ul>li.me>p>button {
      background-color: transparent;
      color: red;
      font-size: 20px;
      padding: 0;
      position: absolute;
      top: .5rem;
      right: .5rem;
    }

    .messenger>ul>li>h4>button {
      background-color: transparent;
      color: red;
      font-size: 20px;
      padding: 0;
      align-self: flex-end;
      float: right;
    }

    .messenger>ul>li>p {
      margin: 0;
      display: inline;
      padding-right: .5rem;
      font-size: 17px;
    }

    .messenger>ul>li.me>p {
      margin: 0;
      display: inline;
      padding-right: 2rem;
    }

    .messenger>ul>li>h4 {
      margin: 0;
      display: inline-block;
    }

    form {
      display: flex;
      margin-top: 1rem;
      flex-direction: row;
      max-width: 40vw;
      height: 40px;
    }

    form>input[type="text"] {
      flex: 1 1 auto;
      padding: 1rem;
      background-color: rgb(255, 255, 255);
      color: black;
      border: 0;
      outline: none;
    }

    .modal {
      text-align: center;
      padding: 0;
    }

    .modal:before {
      content: '';
      display: inline-block;
      height: 100%;
      vertical-align: middle;
      margin-right: -4px;
    }

    .modal-dialog {
      display: inline-block;
      text-align: left;
      vertical-align: middle;
    }

    ::placeholder {
      color: #ffffff;
      opacity: .7;
    }
  </style>
</head>

<body class="hold-transition skin-blue sidebar-mini" OnLoad='document.getElementById("message_input").focus();'>
  <div class="wrapper">
    <!-- Main Header -->
    <header class="main-header">
      <!-- Logo -->
      <a href="script.php" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">Messenger</span>
      </a>
      <!-- Header Navbar -->
      <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">


            <!-- User Account Menu -->
            <li class="dropdown user user-menu">
              <!-- Menu Toggle Button -->
              <a href="" class="dropdown-toggle" data-toggle="dropdown">
                <!-- The user image in the navbar-->
                <img src="../dist/img/avatar5.png" class="user-image" alt="User Image">
                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                <span class="hidden-xs"><?php echo $name ?></span>
              </a>
              <ul class="dropdown-menu">
                <!-- The user image in the menu -->
                <li class="user-header">
                  <img src="../dist/img/avatar5.png" class="img-circle" alt="User Image">
                  <p>
                    Matej Drha - Developer
                    <small>Database IP adress: <?php echo $name . ":" . $port ?></small>
                  </p>
                </li>

                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="#" class="btn btn-default btn-flat">Messages</a>
                  </div>
                  <div class="pull-right">
                    <a href="#" class="btn btn-default btn-flat">Sign out</a>
                  </div>
                </li>
              </ul>
            </li>
            <!-- Control Sidebar Toggle Button -->

          </ul>
        </div>
      </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
          <div class="pull-left image">
            <img src="../dist/img/avatar5.png" class="img-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <p><?php echo $name ?></p>
            <!-- Status -->
            <p style="font-size: 80%;"><i class="fa fa-circle text-success"></i> Online</p>
          </div>
        </div>
        <!-- search form (Optional) -->
        <form action="#" method="get" class="sidebar-form">
          <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Search...">
            <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
          </div>
        </form>
        <!-- /.search form -->
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
          <li class="header">Contacts</li>
          <!-- Optionally, you can add icons to the links -->
          <li class="treeview">
            <a href="#"><i class="fa fa-medkit"></i> <span>Doctors</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="/medibed/doctor/create.php">Create Doctor</a></li>
              <li><a href="/medibed/doctor">All Doctors</a></li>
            </ul>
          </li>
        </ul>

        <!-- /.sidebar-menu -->
      </section>

      <!-- /.sidebar -->
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="background-color: #222222;">
      <!-- Main content -->
      <section class="content container-fluid" style="padding-top: 5px; padding-bottom: 5px; background-color: #222222;">
        <div class="row" style="background-color: #222222;">
          <div class="col-md-6" style="border-right: 1px solid #3c8dbc; margin: 0;">
            <section class="content-header" style="border-bottom: 1px solid #3c8dbc; margin: 0; padding: 0;">
              <form class="search_class" method="POST" action="script.php" style="margin: 0;">
                <img src="../dist/img/avatar5.png" class="img-circle" alt="User Image" width="40" height="40" style="padding: 5px;">
                <span class="hidden-xs" style="padding: 10px; color: #ffffff;"><?php echo $name ?></span>
                <p style="color: #ffffff; font-size: 90%; padding-top: 12px; padding-bottom: 12px; padding-right: 40px"><i class=" fa fa-circle text-success"></i> Online</p>
                <input type="text" name="text" value="<?php echo $search_text ?>" placeholder="Search in messages..." id="message_search" autocomplete="off" style="color: #ffffff; background-color:#444444; margin-top: 5px; margin-bottom: 5px;"></input>
                <span class="input-group-append" style="border: 0; margin: 5px; margin-right: 0;margin-left: 0; background-color: #444444; padding: 5px;">
                  <div class=" input-group-text bg-transparent"><i class="fa fa-search" style="color: #ffffff; opacity: 0.7; font-size: 16px;"></i>
                  </div>
                </span>
              </form>


            </section>

            <div class="messenger">
              <?php
              if (count($messages) != 0) {
                echo "<ul>";
                foreach ($messages as $m) {
                  echo $m->get_html();
                }
                echo "</ul>";
              }
              ?>

              <div class="modal fade" id="remove_dialog" role="dialog">
                <div class="modal-dialog">

                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Remove message?</h4>
                    </div>
                    <div class="modal-body">
                      <p>Message will be removed for all users.</p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.message_id_to_delete = '';">Dismiss</button>
                      <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="remove_message(window.message_id_to_delete)">Remove</button>
                    </div>
                  </div>

                </div>
              </div>

            </div>

            <form action="add_message.php" method="POST">
              <input type="text" name="message" placeholder="Type a message..." id="message_input" autocomplete="off" style="background-color: #444444; color:#ffffff;" />
              <input type="hidden" name="name" value="<?php echo $name ?>" />
              <span class="input-group-append" aria-hidden="true" style="background-color: #444444; ">
                <div class=" input-group-text bg-transparent"><i class="glyphicon glyphicon-send" style="font-size: 25px; padding: 8px; min-width: 50px; color: #3c8dbc; "></i>
                </div>
              </span>
            </form>
          </div>
          <div class="col-md-6">
            druha strana
          </div>
        </div>
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <!-- Main Footer -->
    <footer class="main-footer" style="padding:5px; border: 0; background-color: #222d32; color: #ffffff; opacity: 0.7;">
      <!-- Default to the left -->
      <strong>Copyright &copy; 2020 Matej Drha</strong> All rights reserved.
    </footer>
    <!-- Add the sidebar's background. This div must be placed
  immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div>

  <form style="display:none;" action="remove_message.php" method="POST" id="message-to-delete-form">
    <input type="hidden" name="id" value="" id="message-to-delete" />
  </form>

  <!-- ./wrapper -->
  <!-- REQUIRED JS SCRIPTS -->
  <!-- jQuery 3 -->
  <script src="../bower_components/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- DataTables -->
  <script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../dist/js/adminlte.min.js"></script>
  <script>
    var objDiv = document.getElementsByClassName("messenger");
    objDiv[0].scrollTop = objDiv[0].scrollHeight;
    window.message_id_to_delete = "";

    function remove_message(id) {
      document.getElementById('message-to-delete').value = id;
      document.getElementById('message-to-delete-form').submit();
    }
  </script>

</body>

</html>