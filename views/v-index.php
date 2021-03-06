<?php defined('PAINLESS') or die('No direct script access.'); 

// DEFAULT VIEW

?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="content-language" content="en" />
<title>Sample</title>
<base href="<?= $BASE_URL ?>/" />
<link rel="stylesheet" type="text/css" media="all" href="css/reset.css" />
<link rel="stylesheet" type="text/css" media="all" href="css/style.css" />
</head>
<body>
<!--[if lt IE 7]><div class="ie6"> <![endif]-->
<!--[if IE 7]><div class="ie7"> <![endif]-->
<!--[if IE 8]><div class="ie8"> <![endif]-->
<!--[if IE 9]><div class="ie9"> <![endif]-->

<h1>Sample</h1>
<h2>$BASE_URL = <?= @ $BASE_URL ?></h2>
<h2>$BASE_PATH = <?= @ $BASE_PATH ?></h2>

<p>&nbsp;</p>
<p>&nbsp;</p>

<form method="post" action="sample/sample-test?id=sample" accept-charset="UTF-8">

	<label class="block">Enter Your Name</label>
	<input type="text" class="block" value="" id="name" name="name" size="30" maxlength="60" autocomplete="off" />

	<p>&nbsp;</p>
	<input type="submit" value="Run Sample" />

</form>

<!--[if lt IE 7]></div> <![endif]-->
<!--[if IE 7]></div> <![endif]-->
<!--[if IE 8]></div> <![endif]-->
<!--[if IE 9]></div> <![endif]-->
</body>
</html>

