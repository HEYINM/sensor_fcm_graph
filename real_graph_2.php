<?php
// 최근 12시간동안수온,ph 변화
ini_set('display_errors', 'On');
error_reporting(E_ALL|E_STRICT);
 
// MySQL 접속
$mysql_host = 'localhost';
$mysql_user = 'phpmyadmin';
$mysql_password = 'root';
$mysql_db = 'Seaking';
$conn = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_db) or die("not connected.");
//$dbconn = mysqli_select_db($mysql_db, $conn);
 
// DB에서 원하는 데이터 검색
$sql="
select * from (
        SELECT DATE_FORMAT( timestamp,  '%m-%d %HH' )  mdh , COUNT( * ) cnt, SUM( ds18b20_temp ) , 
        round(SUM( ds18b20_temp ) / COUNT( * ),1)  atemper, round(SUM( ph ) / COUNT( * ),1)  ahumier
        FROM  `sensors`
        GROUP BY DATE_FORMAT( timestamp,  '%Y%m%d%H' )
        order by timestamp desc
        limit 12
        ) t_a
order by t_a.mdh
";
 
$result = mysqli_query($conn, $sql) ;
 
$str_mdh="";
$str_atemper="";
$str_ahumier="";
 
// 수온, pH  문자열 연결
while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
     $str_mdh .="'".$row['mdh']."',";
     $str_atemper .="".$row['atemper'].",";
     $str_ahumier .="".$row['ahumier'].",";
}
 
// 오른쪽 공백 제거
$str_mdh= substr($str_mdh,0,-1);
$str_atemper= substr($str_atemper,0,-1);
$str_ahumier= substr($str_ahumier,0,-1);

?>
<!DOCTYPE HTML>
<html>
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>House Monitor</title>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <style type="text/css">${demo.css}</style>
        <script type="text/javascript">
                                         
$(function () {
    $('#temp').highcharts({
        chart: {
            type: 'line',
            backgroundColor: null
        },
        title: {
            text: 'Temperature'
        },
        subtitle: {
            text: 'made by hyeri'
        },
        xAxis: {
            categories: [<?php echo $str_mdh?>]
        },
        yAxis: {
            title: {
                text: 'Temperature (°C)'
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: false                                                                                                                                  
            }
        },                                                                                                                                                                       
        series: [{
            name: 'VALUE',
            color: '#d5899a',
            data: [<?php echo $str_atemper?>]
        }]
    });
    $('#humi').highcharts({
        chart: {
            type: 'line',
            backgroundColor: 'transparent'
    },
        title: {
            text: 'pH'
        },
        xAxis: {
            categories: [<?php echo $str_mdh?>]
        },
        yAxis: {
            title: {
                text: 'pH'
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: false
            }
        },
        series: [{
            name: 'VALUE',
            color: '#d5899a',
            data: [<?php echo $str_ahumier?>]
        }]
    });
});
        </script>
</head>
<body bgcolor='A48BB4E8'>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <div id="temp" style="width: 410px; height: 440px; margin: 30px auto"></div>
        <div id="humi" style="width: 410px; height: 440px; margin: 30px auto"></div>
</body>
</html>
