<?php

// code to print out the temperature database in tabular form

$username="root";
$password="raspberry";
$database="templog";
$server="localhost";
mysql_connect($server,$username,$password);
mysql_select_db($database);
  
$query="SELECT * FROM `temp-at-interrupt` ORDER BY `Date` DESC, `Time` DESC;"; 
$result=mysql_query($query);

?>
<html>
   <head>
      <title>Sensor Data</title>
   </head>
<body>
   <h1>Temperature readings</h1>

   <table border="1" cellspacing="1" cellpadding="1">
		<tr>
			<td>&nbsp;Date&nbsp;</td>
			<td>&nbsp;Time&nbsp;</td>
			<td>&nbsp;Temperature&nbsp;</td>
		</tr>

      <?php 
		  if($result!==FALSE){
		     while($row = mysql_fetch_array($result)) {
		        printf("<tr><td> &nbsp;%s </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td></tr>", 
		           $row["Date"], $row["Time"], $row["Temperature"]);
		     }
		     mysql_free_result($result);
		     mysql_close();
		  }
      ?>

   </table>
</body>
</html>
