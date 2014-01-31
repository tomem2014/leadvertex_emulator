<?php
class Renderer {
  protected $data;
  protected $page = 'index';
  protected $buttonText;
  protected $form;
  protected $fields;
  protected $scripts = array();

  private $_html;

  const VERSION = 2.2;

  function __construct($data = array(), $fields = array(), $form = array(), $buttonText = 'Оформить заказ')
  {
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
    $this->form = array_replace_recursive($formDefault,$form);

    $data_default = array(
      'title' => 'Мой интернет-магазин',
      'old_price' => 1990,
      'price' => 990,
      'meta_keywords' => 'ключевоеСлово1, ключевоеСлово2, ключевоеСлово3, ключевоеСлово4',
      'meta_description' => 'Мета-описание, задаваемое в конфигурации',
    );
    $data_system = array(
      'domain' => $_SERVER['HTTP_HOST'],
      'today' => date('j') . ' ' . $this->getMonthName(time()),
      'year' => date('Y'),
    );
    $this->data = array_merge($data_default, $data, $data_system);

    $this->fields = empty($fields) ? array_keys($this->form) : $fields;

    $this->buttonText = $buttonText;
  }
  private function registerFile($filename,$onTop = false)
  {
    $filename = strtolower($filename);
    $ext = substr(strrchr($filename, '.'), 1);
    if (!isset($this->scripts[$filename]) || 1==1) {
      if ($onTop === true) {
        if ($ext == 'js') $this->_html = str_ireplace('<head>', '<head><script type="text/javascript" src="'.$filename.'"></script>', $this->_html);
        else $this->_html = str_ireplace('<head>', '<head><link rel="stylesheet" href="'.$filename.'"/>', $this->_html);
      } else {
        if ($ext == 'js') $this->_html = str_ireplace('</head>', '<script type="text/javascript" src="'.$filename.'"></script></head>', $this->_html);
        else $this->_html = str_ireplace('</head>', '<link rel="stylesheet" href="'.$filename.'"/></head>', $this->_html);
      }
      $this->scripts[$filename] = $filename;
    }
  }
  public function formRender($number)
  {
    $form = $this->form;
    $fields = $this->fields;

    $html = '<form id="lv-form'.$number.'" class="lv-order-form" action="" method="post">';

    foreach ($fields as $field) {
      $name = $form[$field]['name'];
      $message = $form[$field]['message'];
      $error = $form[$field]['error'] ? '' : 'display:none;';
      $errorClass = $form[$field]['error'] ? ' lv-row-error ' : '';
      $type = $form[$field]['type'];
      $pattern = $form[$field]['pattern'];

      $html.='<div class="lv-row lv-row-'.$field.' '.($type == 'checkbox' ? 'lv-row-checkbox' : 'lv-row-input').$errorClass.'">';
      if ($type == 'checkbox') {
        $html.='<div class="lv-label">';
        $html.='<input name="Order['.$field.']" id="lv-form'.$number.'-'.$field.'" value="1" type="checkbox">';
        $html.='<label for="lv-form'.$number.'-'.$field.'">С условиями покупки согласен</label>';
        $html.='</div>';
      }
      else {
        $html.='<div class="lv-label"><label for="form'.$number.'_'.$field.'">'.$name.'</label></div>';
        $html.='<div class="lv-field">';

        if ($type == 'dropdown') {
          $html.='<select data-label="'.$name.'" name="Order['.$field.']" id="lv-form'.$number.'-'.$field.'">';
          $items = explode(',',$pattern);
          foreach ($items as $item) {
            $item = trim($item);
            if ($field == 'quantity') $item = $item.$form[$field]['unit'];
            $html.='<option>'.$item.'</option>';
          }
          $html.='</select>';
        }
        elseif ($type == 'string') $html.='<input data-label="'.$name.'" name="Order['.$field.']" id="lv-form'.$number.'-'.$field.'" type="text" maxlength="255" />';
        elseif ($type == 'text') $html.='<textarea data-label="'.$name.'" name="Order['.$field.']" id="lv-form'.$number.'-'.$field.'"></textarea>';

        $html.='</div>';
      }
      $html.='<div class="lv-error"><div class="lv-error-text" id="lv-form'.$number.'-'.$field.'_em_" style="'.$error.'">'.$message.'</div></div>';
      $html.='</div>';
    }
    $html.='<div class="lv-form-submit"><input class="lv-order-button" type="submit" name="yt0" value="'.$this->buttonText.'"></div>';
    $html.= '</form>';
    return $html;
  }
  private function getMonthName($timestamp)
  {
    $month = (int)date('m', $timestamp);
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

  private function tagJquery()
  {
    if (stripos($this->_html, '{{jquery}}') !== false) {
      $this->_html = str_ireplace('{{jquery}}', '', $this->_html);
      $this->registerFile('/assets/jquery-1.9.1.js',true);
    }
  }
  private function tagPrice($tag,$sumPrice) {
    if (preg_match_all('~(?:\{\{(?:'.$tag.')(?:(\+|\-)(\d+)(%)?)?\}\})~ui', $this->_html, $prices_all, PREG_SET_ORDER) > 0) {
        foreach ($prices_all as $matches) {
          $price = $sumPrice;
          if (isset($matches[1])) {
            $sum = $matches[2];
            if (isset($matches[3])) $sum = round($price / 100 * $matches[2]);
            if ($matches[1] == '-') $price = $price - $sum;
            else $price = $price + $sum;
          }
          $this->_html = str_ireplace($matches[0], $price, $this->_html);
        }
      }
  }
  private function tagFromTo(){
    if (preg_match_all('~(?:\{\{from_to(?:=(\d+))?\}\})~ui', $this->_html, $matches_all, PREG_SET_ORDER) > 0) {
        foreach ($matches_all as $matches) {
          $discountDuration = (isset($matches[1]) && !empty($matches[1])) ? (int)$matches[1] : 7;
          $oldDate = time() - $discountDuration * (60 * 60 * 24);
          $fromMonth = $this->getMonthName($oldDate);
          $toMonth = $this->getMonthName(time());

          if ($fromMonth != $toMonth) $from_to = date('j', $oldDate) . ' ' . $fromMonth . ' по ' . date('j') . ' ' . $toMonth;
          else $from_to = date('j', $oldDate) . ' по ' . date('j') . ' ' . $toMonth;
          $this->_html = str_ireplace($matches[0], $from_to, $this->_html);
        }
      }
  }
  private function tagOnlyTo(){
    if (preg_match_all('~(?:\{\{only_to(?:=(\d+))?\}\})~ui', $this->_html, $matches_all, PREG_SET_ORDER) > 0) {
        foreach ($matches_all as $matches) {
          $discountDuration = (isset($matches[1]) && !empty($matches[1])) ? (int)$matches[1] : 2;
          $toDate = time() + $discountDuration * 86400;
          $toMonth = $this->getMonthName($toDate);
          $to = date('j', $toDate) . ' ' . $toMonth;
          $this->_html = str_ireplace($matches[0], $to, $this->_html);
        }
      }
  }
  private function tagForm()
  {
    $forms = array();
    $object = &$this;
    $this->_html = preg_replace_callback('~\{\{form(?:_?(\d{1}))?\}\}~i', function ($matches) use (&$forms, $object) {
      if (isset($matches[1])) {
        $number = $matches[1];
        if ($number < 2) $number = 1;
        if (in_array($number, $forms)) $number = max($forms) + 1;
      } elseif (count($forms) > 0) $number = max($forms) + 1;
      else $number = 1;
      $forms[] = $number;
      if ($number == 1) $number = '';
      return $object->formRender($number);
    }, $this->_html);
    if (!empty($forms)) {
      $this->registerFile('/assets/jquery-1.9.1.js',true);
      $this->registerFile('/assets/placeholders.min.js');
      $this->registerFile('/assets/formHelper.js');
      $this->registerFile('/assets/form.css');
    }
  }
  private function tagGeo()
  {

    $this->_html = str_ireplace('{{geo_city}}', 'Москва', $this->_html);
    $this->_html = str_ireplace('{{geo_region}}', 'Московская область', $this->_html);
  }
  private function tagOrderNumber()
  {
    $this->_html = str_ireplace('{{order_number}}', rand(500,2000), $this->_html);
  }
  private function tagPhone(){
    $this->_html = str_ireplace('{{phone}}', $this->data['phone'], $this->_html);
  }
  private function tagEmail(){
    if (preg_match_all('~(?:\{\{email(?:="([^"\}]+)")?(?:\s*\|(protected))?\}\})~ui', $this->_html, $matches_all, PREG_SET_ORDER) > 0) {
      foreach ($matches_all as $matches) {
        $email = (isset($matches[1]) && !empty($matches[1])) ? $matches[1] : $this->data['email'];
        if (isset($matches[2]) && strtolower($matches[2]) == 'protected') {
          $email = str_replace('@', '@@', $email);
          $split = str_split($email, rand(4, 5));
          $email = '';
          foreach ($split as $part) $email .= '<!--' . uniqid('@') . '. -->' . $part;
          $email = str_replace('@@', '&#64;', $email);
          $email = str_replace('.', '&#46;', $email);
        }
        $this->_html = str_ireplace($matches[0], $email, $this->_html);
      }
    }
  }
  private function tagFiles(){
    $this->_html = str_ireplace('{{files}}', '/template/files', $this->_html);
  }
  private function tagUserVars(&$data){
    if (preg_match_all('~(?:\{\{([a-z\d_-]+)="([^\}"]*)"\}\})~ui', $this->_html, $matches_all, PREG_SET_ORDER) > 0) {
      foreach ($matches_all as $matches) {
        $data[$matches[1]] = $matches[2];
        $this->_html = str_ireplace($matches[0], '', $this->_html);
      }
    }
  }
  private function tagCountdownJs()
  {
    if (stripos($this->_html, '{{countdown.js}}') !== false) {
      $this->registerFile('/assets/jquery-1.9.1.js',true);
      $this->registerFile('/assets/countdown.js');
      $this->_html = str_ireplace('{{countdown.js}}','',$this->_html);
    }
  }

  protected function loadTemplate()
  {
    $this->_html = file_get_contents('template/index.html');
    if (stripos($this->_html, '{{content}}') !== false) {
      $page = file_get_contents('template/pages/' . $this->page . '.html');
      $this->_html = str_ireplace('{{content}}', $page, $this->_html);
    }
  }

  public function render($page)
  {
    if ($page) $this->page = $page;
    $this->loadTemplate();
    $this->tagJquery();
    $this->tagCountdownJs();
    $this->tagPrice('price',$this->data['price']);
    $this->tagPrice('oldPrice|old_price',$this->data['old_price']);
    $this->tagFromTo();
    $this->tagOnlyTo();
    $this->tagGeo();
    $this->tagOrderNumber();
    $this->tagPhone();
    $this->tagEmail();
    $this->tagFiles();
    $this->tagForm();
    $this->tagUserVars($this->data);

    foreach ($this->data as $key => $value) if (gettype($value) != 'object') $this->_html = str_ireplace('{{' . $key . '}}', $value, $this->_html);
    echo $this->_html;
  }
}