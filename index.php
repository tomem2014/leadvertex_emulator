<?php
include_once('config.php');
include_once('Renderer.php');

$renderer = new Renderer($config,$fields,$form,$buttonText);
$renderer->render(@$_GET['page']);