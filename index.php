<?php
//Параметры конфигурации
$config = array(
  'title' => 'Мой интернет-магазин',
  'oldPrice' => 1990,
  'price' => 990,
  'meta_keywords' => 'ключевоеСлово1, ключевоеСлово2, ключевоеСлово3, ключевоеСлово4',
  'meta_description' => 'Мета-описание, задаваемое в конфигурации',
);

/**
 * Параметры формы.
 * name - Название поля
 * message - текст ошибки
 * error - отображать ли ошибку
 * type - тип поля. Допустимы значения:
 *        string - однострочное поле
 *        text - многострочное поле
 *        dropdown - выпадающий список (элементы задаются в параметре 'pattern' => 'Россия, Украина, Казахстан')
 *        checkbox - галочка
 * pattern - используется только для выпадающих списов
 */
$form = array(
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
    'unit' => ' шт.'
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

//Текст кнопки заказа
$buttonText = 'Оформить заказ';

//Какие поля и в каком порядке будут отображены на форме
$fields = array('fio','country','city','address','phone','checkboxPersonalData');

include_once('renderer.inc');