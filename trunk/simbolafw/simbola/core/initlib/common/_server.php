<?php

$_SERVER['SERVER_NAME'] = empty($_SERVER['SERVER_NAME']) ? "localhost" : $_SERVER['SERVER_NAME'];
$_SERVER['HTTP_HOST'] = empty($_SERVER['HTTP_HOST']) ? "localhost" : $_SERVER['HTTP_HOST'];
$_SERVER['REQUEST_URI'] = empty($_SERVER['REQUEST_URI']) ? "/index.php" : $_SERVER['REQUEST_URI'];
//args
$_SERVER['argc'] = empty($_SERVER['argc']) ? 0 : $_SERVER['argc'];
$_SERVER['argv'] = empty($_SERVER['argv']) ? array() : $_SERVER['argv'];