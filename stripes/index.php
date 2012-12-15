<?php
	session_start();
	include('./include/config.php');
	include('./include/function.php');
	$objContent = simplexml_load_file('./content/content.xml');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Language" content="de" />
		<meta http-equiv="Content-Style-Type" content="text/css" />
		<meta http-equiv="Content-Script-Type" content="text/javascript" />
		<meta name="keywords" content="stripes" />
		<meta name="robots" content="index,follow" />
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
		<link rel="icon" href="favicon.ico" type="image/x-icon" />
		<link href="<?php echo HTTP_ROOT; ?>css/index.css" rel="stylesheet" type="text/css" charset="utf-8" />
		<script src="<?php echo HTTP_ROOT; ?>js/index.js" type="text/javascript"></script>
		<title>Stripes</title>
	</head>
	<body>
		<div id="container">
			<?php echo getOutput($objContent); ?>
		</div>
	</body>
</html>