<?php
//temperature data reading through linux cat
$re=exec("cat /sys/bus/w1/drivers/w1_slave_driver/28-*/w1_slave");
//'t=' split to array for temperature data extraction
$tem_c=explode("t=",$re);
//current time & temp / 1000, .xx 
echo ( "Time = ".date("Y-m-d H:i:s")." , Temperature = ".round($tem_c[1]/1000,2)." C ");
?>
