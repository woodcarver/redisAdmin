<html>
<head>
	<link href="<?php echo CSS_URL;?>global.css" rel="stylesheet" type="text/css">
</head>

<body style="background:#D0DCE0">
	<ul class="navigation">
	<?php
	foreach($rs as $v)
	{
		echo '<li>
<a href="index.php?m=Action&a=values&key='.$v['key'].'&type='.$v['type'].'&s=normal" target="frame_content">'.$v['key'].'<br/>('.$v['type'].')<br/></a>
			<a href="index.php?m=Action&a=emptyValues&key='.$v['key'].'"  target="frame_content">delete</a></li>';
	}
	 ?>
	</ul>
</body>
</html>
