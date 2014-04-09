<?php
include_once('config.php');
include_once('Renderer.php');

if (!isset($_COOKIE['lv_lastcheck'])) {
  $actualCode = file_get_contents('https://raw.github.com/XAKEPEHOK/leadvertex_emulator/master/Renderer.php');
  if (preg_match('~const VERSION = (\d+\.\d+);~u',$actualCode,$matches)) {
    if ($matches[1] > Renderer::VERSION) {
      echo 'Вышла новая версия шаблонизатора. Просьба использовать именно её. О новых возможностях шаблонизатора читайте в README.md <br>';
      die('<a href="https://github.com/XAKEPEHOK/leadvertex_emulator/">https://github.com/XAKEPEHOK/leadvertex_emulator/</a>');
    }
  } else setcookie('lv_lastcheck',1,time()+60*60*24,null,null,null,true);
}

if (file_exists('template/index.html')) $html = file_get_contents('template/index.html');
else $html = '{{content}}';

$page = isset($_GET['page']) ? $_GET['page'] : 'index';
if (stripos($html, '{{content}}') !== false) {
  $page = file_get_contents('template/pages/' . $page . '.html');

  if (stripos($page, '{{no_layout}}') === false) $html = str_ireplace('{{content}}', $page, $html);
  else $html = str_ireplace('{{no_layout}}', '', $page);
}

$renderer = new Renderer($config,$fields,$form,$buttonText);
$renderer->render($html);