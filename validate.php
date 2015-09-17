<?php 

require_once "./class.geetestlib.php";
$geetestlib = new GeetestLib();

$data = json_decode($_POST['data'],true);
$result = $geetestlib->validate($data['geetest_challenge'],$data['geetest_validate'],$data['geetest_seccode']);

 if ($result == 1) {
     echo "yes";
 }else{
    echo "no";
 }

 ?>