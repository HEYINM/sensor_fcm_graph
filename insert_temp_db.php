<?php
// mysql access information
$mysql_host = 'localhost'; 
$mysql_user = 'phpmyadmin'; 
$mysql_password = 'root'; 
$mysql_db = 'test_tempdb'; 
// 접속 
//$conn = mysqli_connect($mysql_host, $mysql_user, $mysql_password); 
//$dbconn = mysqli_select_db($mysql_db, $conn); 
$con = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_db) or die("not connected.");


// charset 설정, 설정하지 않으면 기본 mysql 설정으로 됨, 대체적으로 euc-kr를 많이 사용
//mysql_query("set names utf8"); 

//리눅스 명령어를 cat을 실행해서 온도센서의 데이터를 읽어 들인다. 
$re=exec("cat /sys/bus/w1/drivers/w1_slave_driver/28-*/w1_slave"); 
//데이터중 온도값의 값을 추출하기위해서 "t="기준으로 배열로 분리한다. 
$tem_c=explode("t=",$re); //온도 
$data['temp_c']=round($tem_c[1]/1000,2); 
//날짜 
$data['temp_ymd']=date("Ymd"); 
//시간 
$data['temp_his']=date("His"); 

//insert sql 스트링 만들기 
$query = "INSERT INTO `temperature`(`datetime`, `temperature`) "; 
$query .= "VALUES (now(),'".$data['temp_c']."' )"; 
//테스트를 위해서 쿼리출력 
echo $query; 
//쿼리 실행 
$res = mysqli_query($con, $query); 
?>
