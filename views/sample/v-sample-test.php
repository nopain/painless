<?php defined('PAINLESS') or die('No direct script access.');

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

<p><strong>$_GET Passed Here:</strong> <?= @ $view->ID ?></p>
<p><strong>$_POST Passed Here:</strong> <?= @ $view->NAME ?></p>

<p>&nbsp;</p>

<p>In the above example, $_POST is reversed in our page controller via a model class. Then, a plugin called SetCaps
intercepted it and made it into ALL CAPS. This demonstrates use of a model and use of a plugin.</p>

<p>&nbsp;</p>

<p><a href="<?= @ $BASE_URL ?>">Do Another</a></p>

<p>&nbsp;</p>
<p><em>To see a redirect in this framework, try typing 'hello' for the name.</p>

<!--[if lt IE 7]></div> <![endif]-->
<!--[if IE 7]></div> <![endif]-->
<!--[if IE 8]></div> <![endif]-->
<!--[if IE 9]></div> <![endif]-->
</body>
</html>
