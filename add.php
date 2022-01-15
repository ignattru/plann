<html>
    <head>
        <title>Новая задача</title>
        <link rel="stylesheet" href="css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
        <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="https://cdn.ckeditor.com/ckeditor5/18.0.0/classic/ckeditor.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    </head>

<body>
    
<?php
    include __DIR__.'/config.php'; 
    require_once 'connection.php'; 

    if(isset($_POST['title']) && isset($_POST['body']) && isset($_POST['dcreate']) && isset($_POST['dplan']) && isset($_POST['important']) && isset($_POST['maker']) ){
    // Connect to server
        $link = mysqli_connect($host, $user, $password, $database) 
        or die("Ошибка " . mysqli_error($link)); 
	    $link->set_charset("utf8");
     
    // Hide symb
        $title = htmlentities(mysqli_real_escape_string($link, $_POST['title']));
        $body = $_POST['body'];
        $dcreate = htmlentities(mysqli_real_escape_string($link, $_POST['dcreate']));
        $dplan = htmlentities(mysqli_real_escape_string($link, $_POST['dplan'])); 
        $important = htmlentities(mysqli_real_escape_string($link, $_POST['important'])); 
        $maker = htmlentities(mysqli_real_escape_string($link, $_POST['maker'])); 
     
    // Set a query
        $query ="INSERT INTO TASK_LIST VALUES(NULL, '$title','$body', '$dcreate', '$dplan', NULL, NULL, '$important', '$maker', '5' )";
        $queryId ="SELECT MAX(OUID) FROM TASK_LIST"; 
  
    // Run query
    $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
    $result_id = mysqli_query($link, $queryId) or die("Ошибка " . mysqli_error($link)); 
    while ($row = mysqli_fetch_row($result_id)) {
        $id = $row[0];
    }

    if($important == 1){
        $isimp = "Важно";
    } else {$isimp = "Не важно";}

    if($result)
    {
        echo "<div class='alert alert-success' role='alert'>Задача успешно создана</div>";


    }
    // закрываем подключение
    mysqli_close($link);
}
?>

 <div class="container" style="background-color:#f0ad4e";>
    <a href="index.php" class="btn btn-default navbar-btn" role="button">Закрыть</a>
 </div>

 <div class="container" style="background-color:#fbfbfb";>
    <h1 style="text-align: center">
        <small>Добавить задачу</small>
    </h1>

    <form method="POST">
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for="inputAddress">Заголовок:</label>
                <input type="text" class="form-control" id="inputAddress" placeholder="Заголовок задачи" name="title">
            </div>
        </div>
  
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for="exampleFormControlTextarea1">Описание:</label>
                <textarea class="form-control" name="body" id="editor" placeholder="Описание задачи длинной не более 700 символов" rows="3"></textarea>
            </div>
        </div>
  
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="datetimepickerStart">Старт задачи:</label>
                <div class="input-group date" id="datetimepickerStart">
                    <span class="input-group-addon datepickerbutton"><span class="glyphicon glyphicon-calendar"></span></span>
                    <input type="text" class="form-control" name="dcreate"/>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove2"></span></span>
                </div>
            </div>

            <div class="form-group col-md-6">
                <label for="datetimepickerEnd">Дата исполнения:</label>
                <div class="input-group date" id="datetimepickerEnd">
                    <span class="input-group-addon datepickerbutton"><span class="glyphicon glyphicon-calendar"></span></span>
                    <input type="text" class="form-control" name="dplan"/>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove2" ></span></span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="form-group col-md-6">
                <label for="inputState">Исполнитель:</label>
                <select id="inputState" class="form-control" name="maker">
                    <option value = "1" selected>user1</option>
                    <option value = "2">user2</option>
                    <option value = "3">user3</option>
                    <option value = "4">user4</option>
                </select>
            </div>
        </div>
  
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputState">Важно?</label>
                <select id="inputState" class="form-control" name="important">
                    <option value = "1" selected>Срочно и важно</option>
                    <option value = "2">Срочно и не важно</option>
                    <option value = "3">Не срочно но важно</option>
                    <option value = "4">Не срочно и не важно</option>
                </select>
            </div>
        </div>    
    
        <div class="form-row">
            <div class="form-group col-md-6">
                <button type="submit" class="btn btn-warning">Создать</button>
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
      $('#datetimepickerStart').datetimepicker({
	    locale: 'ru',
		stepping:10,
		format: 'YYYY-MM-DD',
		defaultDate: moment(),
		daysOfWeekDisabled:[0,6]
	});
        
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
  </script>
  
<?php
 // закрываем подключение
mysqli_free_result($result);
mysqli_close($link);
?>

</body>
</html>