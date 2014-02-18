<?php
session_start();

function run_installer() {
    init();
    $data = array();
    $initData = init_check();
    $data['init'] = $initData;
    if(!in_array(FALSE, $initData)){
        setup_application();
    }
    $data['install'] = $_SESSION['installer'];
    view("home", $data);
}

function setup_application() {
    $appconfig = get_session("application");
    $dbconnconfig = get_session("dbconn");
    $dbaseconfig = get_session("dbase");    
    if($appconfig['execute'] == 'YES'){
        copy("templates/template.htaccess", "../.htaccess");
        $content = file_get_contents("templates/template.index.php");
        $content = str_replace("[APP_NAME]", $appconfig['app_name'], $content);
        $content = str_replace("[DB_VENDOR]", 'MYSQL', $content);
        $content = str_replace("[DB_SERVER]", $dbconnconfig['server'], $content);
        $content = str_replace("[DB_USERNAME]", $dbconnconfig['username'], $content);
        $content = str_replace("[DB_PASSWORD]", $dbconnconfig['password'], $content);
        $content = str_replace("[DB_NAME]", $dbaseconfig['dbname'], $content);
        file_put_contents("../index.php", $content);
        header("location:../");
    }
}

function init_check() {
    $data = array();
    //PHP version check
    $data['PHP'] = phpversion() >= 5.4;
    //MY SQL check        
    $dbconfig = get_session("dbconn");
    $conn = mysqli_connect($dbconfig['server'], $dbconfig['username'], $dbconfig['password']);
    $data['DBCONN'] = $conn !== false;
    $data['DBASE'] = $conn !== false;    
    if ($data['DBCONN']) {
        $dbaseconfig = get_session("dbase");
        $db = mysqli_select_db($conn, $dbaseconfig['dbname']);
        $data['DBASE'] = $db !== false;               
        if(!$data['DBASE'] && $dbaseconfig['dbcreate'] == "YES"){            
            $sql = "CREATE DATABASE {$dbaseconfig['dbname']}";            
            $res = mysqli_query($conn, $sql);            
            $data['DBASE'] = $res !== false;
        }
    }

    return $data;
}

function pview($view, $data = array(), $show = false) {
    $viewPath = "views/{$view}.php";
    $viewContent = file_get_contents($viewPath);
    ob_start();
    eval('?>' . $viewContent);
    $content = ob_get_clean();
    ob_start();
    if ($show) {
        echo $content;
    } else {
        return $content;
    }
}

function view($view, $data = array()) {
    $layoutPath = "views/_layout.php";
    $content = pview($view, $data, false);
    $layoutContent = file_get_contents($layoutPath);
    eval('?>' . $layoutContent);
    $content = ob_get_clean();
    echo $content;
}

function init() {
    $_SESSION['installer'] = array(
        'dbconn' => array('server' => 'localhost', 'password' => '', 'username' => 'root'),
        'dbase' => array('dbname' => 'simbola_db', 'dbcreate' => 'NO'),
        'application' => array('app_name' => 'Simbola Application', 'execute' => 'NO'),
    );

    foreach (array('dbconn','dbase','application') as $baseKey) {
        if (isset($_POST[$baseKey])) {
            foreach ($_POST[$baseKey] as $key => $value) {
                $_SESSION['installer'][$baseKey][$key] = $value;
            }
        }
    }   
}

function get_session($name){
    return $_SESSION['installer'][$name];
}