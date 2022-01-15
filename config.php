<?php
    header('Content-type: text/html;charset=utf-8');
    session_start();
    define('SALT', 'As913yr-1u3 -ru1 mr=1r=1 m=0r813'); 

// Получения зашифрованный пароль
function getPassword($password){   
    return md5($password.SALT);
}

// Пользователи в массиве, пароли сравниваются с хэшами 
// Пароль = getPassword('string')
$users = array( 
    'admin' => array('password' => '0d211321e0ad20a7139f68a014776fa0', 'name' => 'Админ'),
);

// Если нет сессии пользователя, но есть куки с пользовательским логином и паролем авторизуемся 
if(!isset($_SESSION['user']) && isset($_COOKIE['login']) && isset($_COOKIE['password']) && isset($users[$_COOKIE['login']]) && getPassword($users[$_COOKIE['login']]['password']) == $_COOKIE['password']) {
    
     $_SESSION['user'] = $_COOKIE['login'];
}

// Флаг аторизованы или нет
define('AUTH', isset($_SESSION['user']) && isset($users[$_SESSION['user']])); 
$user = AUTH ? $users[$_SESSION['user']] : null;


$message = '';
if(!empty($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
 