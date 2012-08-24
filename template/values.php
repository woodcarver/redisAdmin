<html>
<head>
<title>values</title>
<link href="<?php echo CSS_URL;?>global.css" rel="stylesheet" type="text/css">
</head>

<body>
<table>
<?php
$total = count($rs);//var_dump($rs);
echo "total ".$total;
//µÝ¹éÏÔÊ¾Ò»Ìõ
function showdata($data,$key='')
{
	if(!is_array($data))
	{
		echo  '<td>'.$key.':'.$data.'</td>';
	}
	else{
		echo '<tr>';
		foreach($data as $k=>$value)
			showdata($value,$k);
		echo '</tr>';
	}
}
showdata($rs)
?>
</table>
</body>
</html>
