<?php
$html = '';
$jquery = false;
$formCss = false;

function getMonthName($timestamp)
{
  $month = date('m', $timestamp);
  $mArray = array(
    1 => 'января',
    2 => 'февраля',
    3 => 'марта',
    4 => 'апреля',
    5 => 'мая',
    6 => 'июня',
    7 => 'июля',
    8 => 'августа',
    9 => 'сентября',
    10 => 'октября',
    11 => 'ноября',
    12 => 'декабря',
  );
  return $mArray[$month];
}

function getFakeForm($isSecond = false)
{
  $content = file_get_contents('assets/form.html');
  if ($isSecond) {
    $content = str_replace('Order', 'OrderDummy', $content);
    $content = str_replace('id="orders-form"', 'id="orders-form2"', $content);
    $content = str_replace('id="form"', 'id="form2"', $content);
  }
  return $content;
}

function registerJquery()
{
  global $jquery, $html;
  if ($jquery === false) {
    $html = str_ireplace('{{jquery}}', '', $html);
    $html = str_ireplace('<head>', '<head><script type="text/javascript" src="/assets/jquery-1.9.1.js"></script>', $html);
    $jquery = true;
  }
}

function registerFormCSS()
{
  global $formCss, $html;
  if ($formCss === false) {
    $html = str_ireplace('<head>', '<head><link rel="stylesheet" href="/assets/form.css"/>', $html);
    $formCss = true;
  }
}

function renderForm($second = false) {
  global $form, $fields, $buttonText;
  registerFormCSS();
  registerJquery();
  $formDefault = array(
    'fio' => array(
      'name' => 'Ф.И.О.',
      'message' => 'Ф.И.О. заполненно неверно',
      'error' => false,
      'type' => 'string',
      'pattern' => '',
    ),
    'country' => array(
      'name' => 'Страна',
      'message' => 'Страна указана неверно',
      'error' => false,
      'type' => 'dropdown',
      'pattern' => 'Россия, Украина, Казахстан',
    ),
    'postIndex' => array(
      'name' => 'Индекс',
      'message' => 'Почтовый индекс неверн',
      'error' => false,
      'type' => 'string',
      'pattern' => '',
    ),
    'region' => array(
      'name' => 'Край/регион/область',
      'message' => 'Край/регион/область заполненно неверно',
      'error' => false,
      'type' => 'string',
      'pattern' => '',
    ),
    'city' => array(
      'name' => 'Город',
      'message' => 'Город заполненно неверно',
      'error' => false,
      'type' => 'string',
      'pattern' => '',
    ),
    'address' => array(
      'name' => 'Адрес',
      'message' => 'Адрес указан неверно',
      'error' => false,
      'type' => 'text',
      'pattern' => '',
    ),
    'phone' => array(
      'name' => 'Телефон.',
      'message' => 'Телефон указан неверно',
      'error' => false,
      'type' => 'string',
      'pattern' => '',
    ),
    'email' => array(
      'name' => 'Email',
      'message' => 'Email заполненно неверно',
      'error' => false,
      'type' => 'string',
      'pattern' => '',
    ),
    'quantity' => array(
      'name' => 'Количество',
      'message' => 'Количество указано неверно',
      'error' => false,
      //Меняь 'type' и 'pattern' ЗАПРЕЩЕНО!!!
      'type' => 'dropdown',
      'pattern' => '1, 2, 3, 4, 5, 6, 7, 8, 9, 10',
    ),
    'comment' => array(
      'name' => 'Комментарий',
      'message' => 'Комментарий заполненн неверно',
      'error' => false,
      'type' => 'text',
      'pattern' => '',
    ),
    'checkboxPersonalData' => array(
      'name' => 'Согласен на обработку персональных данных',
      'message' => 'Для оформления заказа вы должны дать согласие на обработку своих персональных данных',
      'error' => false,
      'type' => 'checkbox',
      'pattern' => '',
    ),
    'checkboxAgreeTerms' => array(
      'name' => 'Ф.И.О.',
      'message' => 'С условиями покупки согласен',
      'error' => false,
      'type' => 'checkbox',
      'pattern' => '',
    ),
    'additional1' => array(
      'name' => 'Доп.поле #1',
      'message' => 'Доп.поле #1 заполненно неверно',
      'error' => false,
      'type' => 'string',
      'pattern' => '',
    ),
    'additional2' => array(
      'name' => 'Доп.поле #2',
      'message' => 'Доп.поле #2 заполненно неверно',
      'error' => false,
      'type' => 'string',
      'pattern' => '',
    ),
    'additional3' => array(
      'name' => 'Доп.поле #3',
      'message' => 'Доп.поле #3 заполненно неверно',
      'error' => false,
      'type' => 'string',
      'pattern' => '',
    ),
    'additional4' => array(
      'name' => 'Доп.поле #4',
      'message' => 'Доп.поле #4 заполненно неверно',
      'error' => false,
      'type' => 'string',
      'pattern' => '',
    ),
  );
  $form = array_replace_recursive($formDefault,$form);

  $postfix = $second ? '2' : '';
  $className = $second ? 'OrderDummy' : 'Order';
  $html = '<form id="order-form'.$postfix.'" action="" method="post">';
  $html.= '<div id="form'.$postfix.'" class="order-form">';

  foreach ($fields as $field) {
    $name = $form[$field]['name'];
    $message = $form[$field]['message'];
    $error = $form[$field]['error'] ? '' : 'display:none;';
    $errorClass = $form[$field]['error'] ? 'error' : '';
    $type = $form[$field]['type'];
    $pattern = $form[$field]['pattern'];

    $html.='<div class="field field_'.$field.'">';
    if ($type == 'checkbox') {
      $html.='<div class="form_label '.$errorClass.'">';
      $html.='<input name="'.$className.'['.$field.']" id="'.$className.'_'.$field.'" value="1" type="checkbox">';
      $html.='<label for="'.$className.'_'.$field.'">С условиями покупки согласен</label>';
      $html.='</div>';
    }
    else {
      $html.='<div class="form_label '.$errorClass.'"><label for="'.$className.'_'.$field.'">'.$name.'</label></div>';
      $html.='<div class="form_field '.$errorClass.'">';

      if ($type == 'dropdown') {
        $html.='<select data-label="'.$name.'" name="'.$className.'['.$field.']" id="'.$className.'_'.$field.'">';
        $items = explode(',',$pattern);
        foreach ($items as $item) {
          $item = trim($item);
          if ($field == 'quantity') $item = $item.$form[$field]['unit'];
          $html.='<option>'.$item.'</option>';
        }
        $html.='</select>';
      }
      elseif ($type == 'string') $html.='<input data-label="'.$name.'" name="'.$className.'['.$field.']" id="'.$className.'_'.$field.'" type="text" maxlength="255" />';
      elseif ($type == 'text') $html.='<textarea data-label="'.$name.'" name="'.$className.'['.$field.']" id="'.$className.'_'.$field.'"></textarea>';

      $html.='</div>';
    }
    $html.='<div class="form_error"><div class="errorMessage" id="'.$className.'_'.$field.'_em_" style="'.$error.'">'.$message.'</div></div>';
    $html.='</div>';
  }
  $html.='<div class="form_submit"><input class="order-button" type="submit" name="yt0" value="'.$buttonText.'"></div>';
  $html.= '</div>';
  $html.= '</form>';
  return $html;
}

function render($html)
{
  global $config, $html, $form, $fields;
  $default = array(
    'title' => 'Мой интернет-магазин',
    'oldPrice' => 1990,
    'price' => 990,
    'meta_keywords' => 'ключевоеСлово1, ключевоеСлово2, ключевоеСлово3, ключевоеСлово4',
    'meta_description' => 'Мета-описание, задаваемое в конфигурации',
  );
  $system = array(
    'domain' => $_SERVER['HTTP_HOST'],
    'today' => date('j') . ' ' . getMonthName(time()),
    'year' => date('Y'),
    'countdown.js' => '/assets/countdown.js',
  );
  $data = array_merge($default, $config, $system);

  if (stripos($html, '{{jquery}}') !== false) registerJquery();

  if (preg_match_all('~(?:\{\{from_to(?:=(\d+))?\}\})~ui', $html, $matches_all, PREG_SET_ORDER) > 0) {
    foreach ($matches_all as $matches) {
      $discountDuration = (isset($matches[1]) && !empty($matches[1])) ? (int)$matches[1] : 7;
      $oldDate = time() - $discountDuration * (60 * 60 * 24);
      $fromMonth = getMonthName($oldDate);
      $toMonth = getMonthName(time());

      if ($fromMonth != $toMonth) $from_to = date('j', $oldDate) . ' ' . $fromMonth . ' по ' . date('j') . ' ' . $toMonth;
      else $from_to = date('j', $oldDate) . ' по ' . date('j') . ' ' . $toMonth;
      $html = str_ireplace($matches[0], $from_to, $html);
    }
  }
  if (preg_match_all('~(?:\{\{only_to(?:=(\d+))?\}\})~ui', $html, $matches_all, PREG_SET_ORDER) > 0) {
    foreach ($matches_all as $matches) {
      $discountDuration = (isset($matches[1]) && !empty($matches[1])) ? (int)$matches[1] : 2;
      $toDate = time() + $discountDuration * 86400;
      $toMonth = getMonthName($toDate);
      $to = date('j', $toDate) . ' ' . $toMonth;
      $html = str_ireplace($matches[0], $to, $html);
    }
  }



  if (stripos($html, '{{form}}') !== false) $html = str_ireplace('{{form}}', renderForm(), $html);
  if (stripos($html, '{{form_2}}') !== false) $html = str_ireplace('{{form_2}}', renderForm(true), $html);

  $html = str_ireplace('{{geo_city}}', 'Москва', $html);
  $html = str_ireplace('{{geo_region}}', 'Московская область', $html);


  $html = str_ireplace('{{phone}}', '+7 (999) 777 77 77', $html);
  if (preg_match_all('~(?:\{\{email(?:="([^"\}]+)")?(?:\s*\|(protected))?\}\})~ui', $html, $matches_all, PREG_SET_ORDER) > 0) {
    foreach ($matches_all as $matches) {
      $email = (isset($matches[1]) && !empty($matches[1])) ? $matches[1] : 'email@example.com';
      if (isset($matches[2]) && strtolower($matches[2]) == 'protected') {
        $email = str_replace('@', '@@', $email);
        $split = str_split($email, rand(4, 5));
        $email = '';
        foreach ($split as $part) $email .= '<!--' . uniqid('@') . '. -->' . $part;
        $email = str_replace('@@', '&#64;', $email);
        $email = str_replace('.', '&#46;', $email);
      }
      $html = str_ireplace($matches[0], $email, $html);
    }
  }
  if (preg_match_all('~(?:\{\{([a-z\d_-]+)="([^\}"]*)"\}\})~ui', $html, $matches_all, PREG_SET_ORDER) > 0) {
    foreach ($matches_all as $matches) {
      $data[$matches[1]] = $matches[2];
      $html = str_ireplace($matches[0], '', $html);
    }
  }
  $html = str_ireplace('{{files}}', '/template/files', $html);
  foreach ($data as $key => $value) $html = str_ireplace('{{' . $key . '}}', $value, $html);
  return $html;
}

if (isset($_GET['page']) && !empty($_GET['page'])) $pageName = $_GET['page'];
else  $pageName = 'index';
$html = file_get_contents('template/index.html');
if (stripos($html, '{{content}}') !== false) {
  $page = file_get_contents('template/pages/' . $pageName . '.html');
  $html = str_ireplace('{{content}}', $page, $html);
}
echo render($html);