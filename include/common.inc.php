<?php
@session_start();
header("Content-Type:text/html;charset=utf-8");
error_reporting(E_ALL & ~E_NOTICE);
define('PHPMYWIND_INC', preg_replace("/[\/\\\\]{1,}/", '/', dirname(__FILE__)));
define('PHPMYWIND_ROOT', preg_replace("/[\/\\\\]{1,}/", '/', substr(PHPMYWIND_INC, 0, -8)));
define('IN_PHPMYWIND', TRUE);

require_once(PHPMYWIND_INC.'/conn.inc.php'); 
require_once(PHPMYWIND_INC.'/func.class.php'); 
require_once(PHPMYWIND_INC.'/common.func.php'); 

//引入数据库类
if($cfg_mysql_type == 'mysqli' &&
   function_exists('mysqli_init'))
   require_once(PHPMYWIND_INC.'/mysqli.class.php');
else
   require_once(PHPMYWIND_INC.'/mysql.class.php');

require_once(PHPMYWIND_INC.'/../action.php'); 
?>