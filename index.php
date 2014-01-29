<?php
include_once('config.php');
include_once('Renderer.php');

if (!isset($_COOKIE['lv_lastcheck'])) {
  $actualCode = file_get_contents('https://raw.github.com/XAKEPEHOK/leadvertex_emulator/master/Renderer.php');
  if (preg_match('~const VERSION = (\d+\.\d+);~u',$actualCode,$matches)) {
    if ($matches[1] > Renderer::VERSION) {
      echo 'Вышла новая версия шаблонизатора. Просьба использовать именно её: <br>';
      die('<a href="https://github.com/XAKEPEHOK/leadvertex_emulator/">https://github.com/XAKEPEHOK/leadvertex_emulator/</a>');
    }
  } else setcookie('lv_lastcheck',1,time()+60*60*24,null,null,null,true);
}

$renderer = new Renderer($config,$fields,$form,$buttonText);
$renderer->render(@$_GET['page']);