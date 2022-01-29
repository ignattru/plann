<html>
  <head>
    <title>Редактировать задачу</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/18.0.0/classic/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" charset="utf-8">
  </head>
<body>

 <?php
  $id = (int) $_GET[ "id" ];
  require_once 'connection.php';
 
  // Connect to server
  $link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
  $link->set_charset("utf8");
	
$query ="
   select tl.OUID  
        , tl.T_TITLE
        , tl.T_BODY  
        , tl.T_CREATEDATE
        , tl.T_PLANDATE
        , tl.T_FACTDATE
        , r.A_NAME   
        , i.A_NAME
        , u.A_NAME    
        , ts.A_NAME
        , i.OUID
        , ts.OUID 
        , (select curdate())
        , u.OUID
     from TASK_LIST tl
left join IMPORTANT i
       on i.OUID = tl.T_IMPORTANT
left join REPORT r
       on r.OUID = tl.T_REPORT
left join STATUS_TASK ts
       on ts.OUID = tl.T_STATUS_TASK
left join USERS u ON u.OUID = tl.T_MAKER
    where tl.OUID = $id
";

$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
$row = mysqli_fetch_row($result);


if(isset($_POST['dfact']) && isset($_POST['report']) && isset($_POST['statustask']) && isset($_POST['title']) && isset($_POST['body']) && isset($_POST['important']) && isset($_POST['maker'])){
    
    // Screen
    $dfact = $_POST['dfact']; 
    $report = $_POST['report']; 
    $title = strip_tags($_POST['title']); 
    $body = $_POST['body']; 
    $statustask = $_POST['statustask'];
    $important = $_POST['important'];
    $maker = $_POST['maker'];
    
    // Construct query 
    $sql_insert_report = "INSERT INTO REPORT VALUES(NULL, '$report')";   
    $query2 ="UPDATE TASK_LIST SET T_FACTDATE = '$dfact', T_REPORT = (SELECT MAX(OUID) FROM REPORT), T_STATUS_TASK = '$statustask', T_TITLE = '$title', T_BODY = '$body', T_IMPORTANT = '$important', T_MAKER = '$maker' WHERE TASK_LIST.OUID = $id ";
    $resultinsert = mysqli_query($link, $sql_insert_report) or die("Ошибка " . mysqli_error($link)); 
    
    $result2 = mysqli_query($link, $query2) or die("Статус задачи не выбран!"); 
    if($result2) {
        echo "<div class='alert alert-success' role='alert'>Задача успешно обновлена</div>";
    }
   mysqli_free_result($result2);
   mysqli_free_result($resultinsert);
}
?>

  <div class="container" style="background-color:#7556f3";>
    <a href="index.php" class="btn btn-default navbar-btn" role="button">Закрыть</a>
    <b style="color:#fff;">&nbsp;&nbsp;Задача</b>
    <span class="label label-default"><?php echo $row[0]; ?></span>
  </div>
<div class="container" style="background-color:#fbfbfb";>
<div class="page-header">
        <h2>
          <b><?php echo $row[1]; ?></b>
        </h2>
        <p>
          <span class="label" style="background-color:#7556f3"><?php echo $row[9]; ?></span>
        </p>
</div>
  <div class="row">
  <form method="POST">
<div class="form-row">
    <div class="form-group col-md-12">
    <label for="inputAddress">Заголовок:</label>
    <input type="text" class="form-control" id="inputAddress" value="<?php echo $row[1]; ?>" name="title">
    </div>
  </div>
<div class="form-row">
    <div class="form-group col-md-12">
    <label for="exampleFormControlTextarea3">Описание</label>
    <textarea class="form-control" name="body" id="editor" placeholder="Описание задачи не более 700 символов" rows="3"><?php echo $row[2]; ?></textarea>
    </div>
  </div>
  <div class="form-group">
   <div class="form-group col-md-6">
    <label for="inputState">Сменить исполнителя:</label>
      <select id="inputState" class="form-control" name="maker">
  <?php 
  $sqlMaker = "SELECT usr.OUID, usr.A_NAME FROM USERS usr";
  $linkMaker = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($linkMaker));
  $linkMaker->set_charset("utf8"); 
    $resultMaker = mysqli_query($linkMaker, $sqlMaker) or die("Ошибка " . mysqli_error($linkMaker)); 
    if($resultMaker) {   
        while ($rowMaker = mysqli_fetch_row($resultMaker)) { 
            if ($rowMaker[0] == $row[13]) { $selected = "selected";
            } else {
              $selected = " ";
            }
            echo "<option value = '$rowMaker[0]' $selected>",$rowMaker[1],"</option>";
        }
    }
    mysqli_free_result($resultMaker);
   mysqli_close($linkMaker);
?>
      </select>
    </div>
  </div>
  
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputState">Важность</label>
      <select id="inputState" class="form-control" name="important">
         <?php 
  $sqlIfImport = "SELECT imp.OUID, imp.A_NAME FROM IMPORTANT imp";
  $linkIfImport = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($linkIfImport));
  $linkIfImport->set_charset("utf8"); 

    $resultIfImport = mysqli_query($linkIfImport, $sqlIfImport) or die("Ошибка " . mysqli_error($linkIfImport)); 
    if($resultIfImport) {   
        while ($rowIfImport = mysqli_fetch_row($resultIfImport)) { 
            if ($rowIfImport[0] == $row[10]) { $selected = "selected";
            } else {
              $selected = " ";
            }
            echo "<option value = '$rowIfImport[0]' $selected>",$rowIfImport[1],"</option>";
        }
    }
    mysqli_free_result($resultIfImport);
   mysqli_close($linkIfImport);
?>
      </select>
    </div>
  <div class="form-row">
    <div class="form-group">
      <div class="form-group col-md-6">
        <label for="inputState">Статус:</label>
        <select id="inputState" class="form-control" name="statustask">
       <?php 
        $sqlStatus = "SELECT stat.OUID, stat.A_NAME FROM STATUS_TASK stat";
        $linkStatus = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($linkStatus));
        $linkStatus->set_charset("utf8"); 

        $resultStatus = mysqli_query($linkStatus, $sqlStatus) or die("Ошибка " . mysqli_error($linkStatus)); 
        if($resultStatus) {   
          while ($rowStatus = mysqli_fetch_row($resultStatus)) { 
            if ($rowStatus[0] == $row[11]) { $selected = "selected";
            } else {
                $selected = " ";
              }
            echo "<option value = '$rowStatus[0]' $selected>",$rowStatus[1],"</option>";
            }
        }
        mysqli_free_result($resultStatus);
        mysqli_close($linkStatus);
        ?>
        </select>
      </div>
  </div>
    <div class="form-group col-md-6">
      <label for="datetimepickerEnd">Исполнено:</label>
      <div class="input-group date" id="datetimepickerEnd">
        <span class="input-group-addon datepickerbutton">
          <span class="glyphicon glyphicon-calendar"></span>
        </span>
      <input type="text" class="form-control" name="dfact"/>
        <span class="input-group-addon">
          <span class="glyphicon glyphicon-remove2" ></span>
        </span>
      </div>
    </div>
  </div>
 
<div class="form-row">
  <div class="form-group col-md-12">
    <label for="exampleFormControlTextarea1">Отчет об исполнении:</label>
    <textarea class="form-control" name="report" id="editorReport" placeholder="Введите отчет об исполнении" rows="3"><?php echo $row[6]; ?></textarea>
  </div>
</div>

<div class="form-row">
  <div class="form-group col-md-6">
    <button type="submit" class="btn btn-default">Сохранить</button>
  </div>
</div>
</form>
</div>

<script src="js/jquery-3.2.1.min.js"></script>
  <script src="js/moment-with-locales.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/bootstrap-datetimepicker.min.js"></script>
  <script type="text/javascript">
    $(function () {
        $('#datetimepickerEnd').datetimepicker({
	    locale: 'ru',
		stepping:10,
		format: 'YYYY-MM-DD',
		defaultDate: moment(),
		daysOfWeekDisabled:[0,6]
	    });
    });

    ClassicEditor
        .create( document.querySelector( '#editor' ) )
        .catch( error => {
            console.error( error );
        } );

  ClassicEditor
        .create( document.querySelector( '#editorReport' ) )
        .catch( error => {
            console.error( error );
        } );
  </script>
<?php
 // Closed connection
mysqli_free_result($result);
mysqli_close($link);
?>


</body>
</html>