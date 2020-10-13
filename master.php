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
    .messenger {
      overflow-y: auto;
      flex-grow: 1;
      flex-shrink: 1;
      flex-basis: auto;
      padding: 0 1rem;
      max-width: 40vw;
      border-width: 10;
      max-height: calc(80vh - 5.5rem);
    }

    .messenger>ul {
      display: flex;
      flex-direction: column;
      list-style: none;
      max-width: 40vw;
    }

    .messenger>ul>li {
      margin: 1rem 0;
      padding: 1rem;
      display: flex;
      flex-direction: column;
      max-width: 25vw;
      border-radius: 20px;
      box-shadow: 5px 5px 35px -30px rgba(0, 0, 0, 0.75);
    }

    .messenger>ul>li>small {
      margin-top: .5rem;
      color: rgba(0, 0, 0, 0.4);
    }

    .messenger>ul>li.me {
      align-self: flex-end;
      background-color: rgba(202, 226, 251, 0.5);
      position: relative;
    }

    .messenger>ul>li.me>small {
      align-self: flex-end;
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

    .messenger>ul>li>h2>button {
      background-color: transparent;
      font-size: 20px;
      padding: 0;
      align-self: flex-end;
      float: right;
    }

    .messenger>ul>li>p {
      margin: 0;
      display: inline;
      padding-right: .5rem;
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
      border: 1;
    }

    form>input[type="submit"] {
      padding: .3rem 5rem;
      border: 1;
      transition: background-color ease-in-out 150ms, color ease-in-out 150ms;
      background-color: rgba(202, 226, 251, 0.5);
      border-left: none;
    }

    form>input[type="submit"]:hover {
      background-color: #3c8dbc;
      color: white;
    }
  </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">
    <!-- Main Header -->
    <header class="main-header">
      <!-- Logo -->
      <a href="index.php" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">MSG</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">Messenger</span>
      </a>
      <!-- Header Navbar -->
      <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>
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
                    <small>Database IP adress: <?php echo $ipadress . ":" . $port ?></small>
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
            <a href=""><i class="fa fa-circle text-success"></i> Online</a>
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
    <div class="content-wrapper">
      <!-- Main content -->
      <section class="content container-fluid">
        <div class="row">
          <div class="col-md-6" style="border-right: 1px solid #3c8dbc; margin: 0;">
            <section class="content-header">

              <img src="../dist/img/avatar5.png" class="img-circle" alt="User Image" width="45" height="45">

              <span class="hidden-xs"> <?php echo $ipadress ?></span>
              <a href=""><i class="fa fa-circle text-success"></i> Online</a>
            </section>
            <div class="messenger">
              <ul>
                <li>
                  <h3>Nazov cloveka</h3>
                  <p>meesagd uihasdyuasgyuidsgay dgsau dweodfgweiofgwogf iufhuiowehuofhweauiph fuhweofgyawe ghufwhuilfhwerauiofhweyuof gheawuilofg hwaeyulogfyukawergfyuawe gfuilywegafuilwaegiofgduiwagfyuiogiuzxhfuidsjiopf hasuiph fouiasdh fiusdahfuiosdah
                    fiusdhauifh sduioafh sduiopah fouidas hfuiopsdh uiofh asduiofhsdauipf hasipdofhjdiopas ufhipuasd fhuioasdhf uiopasdioufhiupsadhfui asdhuiofhsdauiof hsduioahfuioasdh fiouasd hfp uidsh uifhsdiufh asduip fhuipdas hfuipsdahpfi sdhapuifhasduip
                    fhasduipfh ipusdahf uipasdhe</p>
                  <small>7.10.2020 22:11</small>
                </li>
                <li>
                  <h3>Nazov cloveka</h3>
                  <p>meesage</p>
                  <small>7.10.2020 22:11</small>
                </li>
                <li>
                  <h3>Nazov cloveka</h3>
                  <p>meesage</p>
                  <small>7.10.2020 22:11</small>
                </li>
                <li>
                  <h3>Nazov cloveka</h3>
                  <p>meesage</p>
                  <small>7.10.2020 22:11</small>
                </li>
                <li>
                  <h3>Nazov cloveka</h3>
                  <p>meesage</p>
                  <small>7.10.2020 22:11</small>
                </li>
                <li class="me">
                  <p>meesagdgyu asyudgas uidgsukagh asughdiuoash duihasuiohduioh asidu hasuiodhuioashduioashiodh asuiodhasiou dhouiashd iouasdoyu fyuwehfyotgefouwegoy GFYUOG
                    EWYU FGYUWE GIFGQWEUIG FOYUWEG IOFUEWGUOFYGWEUIOG OYUGYUIOfg yuoh iuphioue<button class="btn"><i class="fa fa-trash"></i></button></p>
                  <small>7.10.2020 22:11</small>
                </li>
                <li>
                  <h3>Nazov cloveka</h3>
                  <p>meesage</p>
                  <small>7.10.2020 22:11</small>
                </li>
                <li>
                  <h3>Nazov cloveka</h3>
                  <p>meesage</p>
                  <small>7.10.2020 22:11</small>
                </li>
                <li>
                  <h3>Nazov cloveka</h3>
                  <p>meesage</p>
                  <small>7.10.2020 22:11</small>
                </li>
                <li>
                  <h3>Nazov cloveka</h3>
                  <p>meesage</p>
                  <small>7.10.2020 22:11</small>
                </li>
                <li>
                  <h3>Nazov cloveka</h3>
                  <p>meesage</p>
                  <small>7.10.2020 22:11</small>
                </li>
                <li>
                  <h3>Nazov cloveka</h3>
                  <p>meesage</p>
                  <small>7.10.2020 22:11</small>
                </li>
                <li class="me">
                  <h3>Nazov cloveka</h3>
                  <p>meesage</p>
                  <small><span>&#10003;&#10003;</span>7.10.2020 22:11</small>
                </li>
              </ul>
            </div>

            <form action="" method="post">
              <input type="text" name="message" placeholder="Type a message..." />
              <input type="submit" value="Send" />
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
    <footer class="main-footer">
      <!-- Default to the left -->
      <strong>Copyright &copy; 2020 Matej Drha</strong> All rights reserved.
    </footer>
    <!-- Add the sidebar's background. This div must be placed
  immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div>
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
    objDiv[0].scrollTop = objDiv[0].scrollHeight;;
  </script>
</body>

</html>