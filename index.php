<?php # Если есть авторизация 
 include __DIR__.'/config.php'; 
 if(AUTH) { 
?>
 
<html>
    <head>
        <title>ПланН</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css" integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">
        <link rel="stylesheet" href="./css/style.css">
        <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
        <meta name="viewport" charset="utf-8" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="theme-color" content="#FF8F00">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/js/bootstrap.min.js" integrity="sha384-XEerZL0cuoUbHE4nZReLT7nx9gQrQreJekYhJD9WNWhH8nEW+0c5qq7aIo2Wl30J" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.6.2"></script>
    </head>
    <body>
        <div class="preloader">
            <svg class="preloader__image" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path fill="currentColor" d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z"></path>
            </svg>
        </div>
    <?php require_once 'connection.php'; 
        $link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
        $link->set_charset("utf8");
        $query ="
        SELECT TL.OUID
             , TL.T_TITLE
             , TL.T_BODY
             , TL.T_CREATEDATE
             , TL.T_PLANDATE
             , TL.T_FACTDATE
             , R.A_NAME
             , I.A_NAME
             , U.A_NAME
             , ST.A_NAME
             , I.OUID
             , ST.OUID
             , (select curdate())
             , TL.T_MAKER
             , (select count(*) as cntA from TASK_LIST where T_IMPORTANT = 1 and T_STATUS_TASK = 5)
             , (select count(*) as cntB from TASK_LIST where T_IMPORTANT = 2 and T_STATUS_TASK = 5)
             , (select count(*) as cntC from TASK_LIST where T_IMPORTANT = 3 and T_STATUS_TASK = 5)
             , (select count(*) as cntD from TASK_LIST where T_IMPORTANT = 4 and T_STATUS_TASK = 5)
             , TL.T_IMPORTANT
             , TL.T_STATUS_TASK
          FROM TASK_LIST TL
     LEFT JOIN IMPORTANT I
            ON I.OUID = TL.T_IMPORTANT
     LEFT JOIN REPORT R
            ON R.OUID = TL.T_REPORT
     LEFT JOIN STATUS_TASK ST
            ON ST.OUID = TL.T_STATUS_TASK
     LEFT JOIN USERS U
            ON U.OUID = TL.T_MAKER
      ORDER BY TL.T_CREATEDATE
    ";?>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top" style="background-color: #7556f3">
            <a class="navbar-brand" href="index.php">
                 <img src="img/plann_logo_navbar.svg" width="35" height="35" class="d-inline-block align-top" alt="">  
            </a>
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">ПланН</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Настройки</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="dev.php">Редактор должностей</a>
                            <a class="dropdown-item" href="dev.php">Редактор пользователей</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="dev.php">Настройка системы</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a type="button" class="nav-link btn btn-sm btn-outline-warning" href="add.php">Добавить задачу</a>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text" style="border: none; background-color: #957cf7;"><img src="img/search.svg"></div>
                        </div>
                        <input type="search" class="form-control" style="background-color: #d5caff; border: 1px solid #c1b1ff;" id="myInput" placeholder="Поиск по задачам" aria-label="Search">
                    </div>
                </form>
            </div>
        </nav>
        <div class="container-fluid" style="background-color: #7556f3;height: 411px;padding-top: 80px;">
            <div class="card mb-3" style="max-width: 540px;">
                <div class="row no-gutters">
                    <div class="col-md-4">
                        <img src="..." class="card-img" alt="...">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">Тайтл</h5>
                            <p class="card-text">Просто обычный адаптивный абзац, текст и гифка, всякое разное вот тут.</p>
                            <p class="card-text"><small class="text-muted">Подтекст</small></p>
                        </div>
                    </div>
                </div>
            </div>    
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm-9">
                    <section class="tabs">
                        <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true" >Срочное и важное</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Срочное и не важное</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Не срочное но важное</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="deyatel-tab" data-toggle="tab" href="#deyatel" role="tab" aria-controls="deyatel" aria-selected="false">Не срочное и не важное</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">&nbsp;
                                <div class="container-fluid">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-md" id="myTable">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th> </th>
                                                    <th>№</th>
                                                    <th>Задача</th>
                                                    <th>Создано</th>
                                                    <th>Дедлайн</th>
                                                    <th>Исполнитель</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
                                                    if($result) {   
                                                        while ($row = mysqli_fetch_row($result)) {
                                                            $cntA = $row[14];
                                                            $cntB = $row[15];
                                                            $cntC = $row[16];
                                                            $cntD = $row[17];
                                                            if($row[18] === '1' & $row[19] === '5') {
                                                            echo "<tr>";
                                                                echo "<td> <a href='edit.php?id=",$row[0],"'>", "<button type='button' class='btn btn-warning btn-edit'>", "<img src='img/edit.svg' width='18' height='18' class='d-inline-block align-top'>","</button></a></td>";
                                                                echo "<td>",$row[0],"</td>";
                                                                echo "<td> <a href='task.php?id=",$row[0],"'>",$row[1],"</a></td>";
                                                                echo "<td width='110'>",$row[3],"</td>";
                                                                if($row[4] <= $row[12]){ $spans="badge-danger";} else {$spans="badge-success";} echo "<td width='110'> <span class='badge badge-pill $spans'>" ,$row[4],"</span></td>";
                                                                echo "<td width='10'> <a href='taskuser.php?id=",$row[13],"'>",$row[8],"</a></td>";
                                                            echo "</tr>";
                                                            }
                                                        }
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">&nbsp;
                                <div class="container-fluid">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-md" id="mytable">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th> </th>
                                                    <th>№</th>
                                                    <th>Задача</th>
                                                    <th>Создано</th>
                                                    <th>Дедлайн</th>
                                                    <th>Исполнитель</th>
                                                </tr>
                                            </thead>
                                            <tbody id="myTable">
                                                <?php $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
                                                    if($result) {   
                                                        while ($row = mysqli_fetch_row($result)) {
                                                            if($row[18] === '2' & $row[19] === '5') {
                                                                echo "<tr>";
                                                                echo "<td> <a href='edit.php?id=",$row[0],"'>", "<button type='button' class='btn btn-warning btn-edit'>", "<img src='img/edit.svg' width='18' height='18' class='d-inline-block align-top'>","</button></a></td>";
                                                                echo "<td>",$row[0],"</td>";
                                                                echo "<td> <a href='task.php?id=",$row[0],"'>",$row[1],"</a></td>";
                                                                echo "<td width='110'>",$row[3],"</td>";
                                                                if($row[4] <= $row[12]){ $spans="badge-danger";} else {$spans="badge-success";} echo "<td width='110'> <span class='badge badge-pill $spans'>" ,$row[4],"</span></td>";
                                                                echo "<td width='10'> <a href='taskuser.php?id=",$row[13],"'>",$row[8],"</a></td>";
                                                            echo "</tr>";
                                                            }
                                                        }
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">&nbsp;
                                <div class="container-fluid">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-md" id="mytable">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th> </th>
                                                    <th>№</th>
                                                    <th>Задача</th>
                                                    <th>Создано</th>
                                                    <th>Дедлайн</th>
                                                    <th>Исполнитель</th>
                                                </tr>
                                                </thead>
                                            <tbody id="myTable">
                                                <?php $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
                                                    if($result) {   
                                                        while ($row = mysqli_fetch_row($result)) {
                                                            if($row[18] === '3' & $row[19] === '5') {
                                                                echo "<tr>";
                                                                echo "<td> <a href='edit.php?id=",$row[0],"'>", "<button type='button' class='btn btn-warning btn-edit'>", "<img src='img/edit.svg' width='18' height='18' class='d-inline-block align-top'>","</button></a></td>";
                                                                echo "<td>",$row[0],"</td>";
                                                                echo "<td> <a href='task.php?id=",$row[0],"'>",$row[1],"</a></td>";
                                                                echo "<td width='110'>",$row[3],"</td>";
                                                                if($row[4] <= $row[12]){ $spans="badge-danger";} else {$spans="badge-success";} echo "<td width='110'> <span class='badge badge-pill $spans'>" ,$row[4],"</span></td>";
                                                                echo "<td width='10'> <a href='taskuser.php?id=",$row[13],"'>",$row[8],"</a></td>";
                                                            echo "</tr>";
                                                            }
                                                        }
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="deyatel" role="tabpanel" aria-labelledby="deyatel-tab">&nbsp;
                                <div class="container-fluid">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-md" id="mytable">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th> </th>
                                                    <th>№</th>
                                                    <th>Задача</th>
                                                    <th>Создано</th>
                                                    <th>Дедлайн</th>
                                                    <th>Исполнитель</th>
                                                </tr>
                                            </thead>
                                            <tbody id="myTable">
                                                <?php $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
                                                    if($result) {   
                                                        while ($row = mysqli_fetch_row($result)) {
                                                            if($row[18] === '4' & $row[19] === '5') {
                                                                echo "<tr>";
                                                                echo "<td> <a href='edit.php?id=",$row[0],"'>", "<button type='button' class='btn btn-warning btn-edit'>", "<img src='img/edit.svg' width='18' height='18' class='d-inline-block align-top'>","</button></a></td>";
                                                                echo "<td>",$row[0],"</td>";
                                                                echo "<td> <a href='task.php?id=",$row[0],"'>",$row[1],"</a></td>";
                                                                echo "<td width='110'>",$row[3],"</td>";
                                                                if($row[4] <= $row[12]){ $spans="badge-danger";} else {$spans="badge-success";} echo "<td width='110'> <span class='badge badge-pill $spans'>" ,$row[4],"</span></td>";
                                                                echo "<td width='10'> <a href='taskuser.php?id=",$row[13],"'>",$row[8],"</a></td>";
                                                            echo "</tr>";
                                                            }
                                                        }
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                        <!-- Горячий поиск по задачам -->
                                        <script>
                                            $(document).ready(function(){
                                                $("#myInput").on("keyup", function() {
                                                var value = $(this).val().toLowerCase();
                                                    $("#myTable tr").filter(function() {
                                                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                                                    });
                                                });
                                            });
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="col-sm-3"> 
                    <canvas id="Chart1" ></canvas>
                </div>   
            </div>
        </div>
        <script>
            window.onload = function () {
              document.body.classList.add('loaded_hiding');
              window.setTimeout(function () {
                document.body.classList.add('loaded');
                document.body.classList.remove('loaded_hiding');
              }, 500);
            }

              // Передаем данные в js
              var cntA = "<?php echo $cntA; ?>";
              var cntB = "<?php echo $cntB; ?>";
              var cntC = "<?php echo $cntC; ?>";
              var cntD = "<?php echo $cntD; ?>";

              // Строим график
              var ctx = document.getElementById('Chart1').getContext('2d');
              var chart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Срочных и важных', 'Срочных не важных', 'Важных не срочных', 'Не важных не срочных'],
                    datasets: [{
                        label: 'Dataset',
                        backgroundColor: ["rgb(66, 103, 252)","rgb(71, 224, 63)", "rgb(71, 124, 63)",  "rgb(71, 34, 22)"],
                        borderColor: 'rgb(255, 255, 255)',
                        data: [cntA, cntB, cntC, cntD],
                    }]
                },

                options: {
                responsive: true,
                plugins: {
                  legend: {
                    display: false,
                    position: 'top',
                  },
                  title: {
                    display: вот так,
                    text: 'Статистика по задачам'
                  }
                }
              },
            });
        </script>

    <?php  #Закрываем подключение
    mysqli_free_result($result);
    mysqli_close($link);
    ?>
    </body>    
</html>

<?php } else { #Нет авторизации ?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <title>Вход</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="img/favicon.ico"/>
  <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
  <link rel="stylesheet" type="text/css" href="css/util.css">
  <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
  <p style="background-color: #ebebeb; text-align: center; padding-top: 2%;"><img src="img/abcd_small.png" width="55"></p>
    <div class="container-login100">
      <div class="wrap-login100 p-l-85 p-r-85 p-t-55 p-b-55">    
        <form class="login100-form validate-form flex-sb flex-w" action="login.php" method="post">
            <span class="login100-form-title p-b-32">План№ — вход</span>
            <span class="txt1 p-b-11">Логин</span>
            <div class="wrap-input100 validate-input m-b-36" data-validate = "Имя пользователя не введено">
                <input class="input100" type="text" name="login" >
                <span class="focus-input100"></span>
            </div>
            <span class="txt1 p-b-11">Пароль</span>
            <div class="wrap-input100 validate-input m-b-12" data-validate = "Пароль не введен">
            <span class="btn-show-pass">
              <i class="fa fa-eye"></i>
            </span>
            <input class="input100" type="password" name="password" >
            <span class="focus-input100"></span>
          </div>
          <div class="flex-sb-m w-full p-b-48">
            <div class="contact100-form-checkbox">
              <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
              <label class="label-checkbox100" for="ckb1">Запомнить</label>
            </div>
          </div>

          <div class="container-login100-form-btn">
            <button class="login100-form-btn" type="submit">
              Вход
            </button>
          </div>
        </form>
      </div>  
    </div>
  <p style="background-color: #ebebeb; text-align: center;">Евгений Игнатуша</p>
  <script src="js/main.js"></script>
</body>
</html>

<?php } ?>
