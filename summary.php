<!-- connect to MySQL Database -->
<?php
$db_host = "localhost";
$db_user = "root";
$db_pass = "root";
$db_name = "project_se";
$connection = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die("Database Connection Error: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")");
?>

<?php
session_start();

//redirect user to login page if user isn't logged in
if (!isset($_SESSION['username'])) {
  header("Location: login.php");//use for the redirection to login page
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Conspectus</title>
    <link rel="stylesheet" type="text/css" href="jquery.ganttView-master/lib/jquery-ui-1.8.4.css" />
    <link rel="stylesheet" type="text/css" href="jquery.ganttView-master/example/reset.css" />
    <link rel="stylesheet" type="text/css" href="jquery.ganttView-master/jquery.ganttView.css" />
    <style type="text/css">
      body {
        font-family: tahoma, verdana, helvetica;
        font-size: 0.8em;
        padding: 10px;
      }
      #ganttChart {
        margin-left: auto;
        margin-right: auto;
      }
      .ganttview {

      }
      .course_table_gantt {
        text-align:center;
      }
    </style>

    <!-- Favicon -->
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
  </head>
  <body>
    <div id="ganttChart"></div>
    <br/>
    <br/>
    <div id="eventMessage"></div>

    <script>
      var ganttData = [
    <?php
      if (isset($_POST['report'])) {
        $user_course_table = $_POST['report'];

        //count the number of feilds (empty and non nonempty)
        for ($i=0; $i<(count($_POST['start'])-1) || $i<(count($_POST['end'])-1) ; $i++){
          //if any start is not empty put in databse
          if(!empty($_POST['start'][$i])){

            $submodule = $_POST['submodulename'][$i];
            $submodule_no =$_POST['submoduleno'][$i];

            $start = $_POST['start'][$i];

            $sql = "UPDATE `$user_course_table` SET `start_date`=\"$start\" WHERE sm_name=\"$submodule\";";
            $sql_result = mysqli_query($connection, $sql) or die("Database Connection Error: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")");

            if (!$sql_result) {
              die("Database Connection Error: " . mysqli_error($connection));
            }

          }
          if(!empty($_POST['end'][$i])){

            $submodule = $_POST['submodulename'][$i];

            $end = $_POST['end'][$i];

            $sql = "UPDATE `$user_course_table` SET `end_date`=\"$end\" WHERE sm_name=\"$submodule\";";
            $sql_result = mysqli_query($connection, $sql) or die("Database Connection Error: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")");

            if (!$sql_result) {
              die("Database Connection Error: " . mysqli_error($connection));
            }
          }

          echo "{
          id: 1, name: \"$submodule_no\", series: [
            { name: \"$submodule\", start: new Date(".str_replace("-",",",$start)."), end: new Date(".str_replace("-",",",$end).") },
    ]
    },";

        }

      } else {
        echo "no submit";
      }
    ?>
      ]
    </script>
    <h1 class="course_table_gantt"> <?php echo $user_course_table ?> </h1>

    <!--  -->
<!--
    <script>
      var ganttData = [
        {
          id: 1, name: "Feature 1", series: [
            { name: "<?php echo $submodule ?>", start: new Date(<?php echo str_replace("-",",",$start)?>), end: new Date(<?php echo str_replace("-",",",$end)?>) },
//          { name: "Actual", start: new Date(2010,00,02), end: new Date(2010,00,05), color: "#f0f0f0" }
          ]
        }]
    </script>
-->

    <script type="text/javascript" src="jquery.ganttView-master/lib/jquery-1.4.2.js"></script>
    <script type="text/javascript" src="jquery.ganttView-master/lib/date.js"></script>
    <script type="text/javascript" src="jquery.ganttView-master/lib/jquery-ui-1.8.4.js"></script>
    <script type="text/javascript" src="jquery.ganttView-master/jquery.ganttView.js"></script>

    <script type="text/javascript">
      $(function () {
        $("#ganttChart").ganttView({
          data: ganttData,
          slideWidth: 900,
          behavior: {
            onClick: function (data) {
              var msg = "You clicked on an event: { start: " + data.start.toString("dd/MM/yyyy") + ", end: " + data.end.toString("dd/MM/yyyy") + " }";
              $("#eventMessage").text(msg);
            },
            onResize: function (data) {
              var msg = "You resized an event: { start: " + data.start.toString("dd/MM/yyyy") + ", end: " + data.end.toString("dd/MM/yyyy") + " }";
              $("#eventMessage").text(msg);
            },
            onDrag: function (data) {
              var msg = "You dragged an event: { start: " + data.start.toString("dd/MM/yyyy") + ", end: " + data.end.toString("dd/MM/yyyy") + " }";
              $("#eventMessage").text(msg);
            }
          }
        });

        // $("#ganttChart").ganttView("setSlideWidth", 600);
      });
    </script>
  </body>
</html>