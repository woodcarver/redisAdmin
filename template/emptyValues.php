<html>
<head>
<title>values</title>
</head>

<body>
<?php 
	echo "<br/>value start<br/>";
	foreach($rs as $data)
	{
		if(!is_array($data))
			echo $data;
		else
			foreach($data as $k=>$value)
				echo $k.':'.$value.'|<br/>';
	}
?>
</body>
</html>
