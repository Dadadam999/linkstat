<?php
/**
 * Plugin Name: linkstat
 * Plugin URI: https://github.com/
 * Description: Плагин, для отслеживания и подсчета переходов по ссылкам. Шорткод: [track-link url="www.site.ru/site" name="Отоброжаемое имя ссылки" css-class="класс" css-style="атрибуты css"]
 * Version: 1.0.0
 * Author: Bogdanov Andrey
 * Author URI: mailto://swarzone2100@yandex.ru
 *
 * @package Кнопка НМО
 * @author Bogdanov Andrey (swarzone2100@yandex.ru)
 * @since 1.0.9
*/
require_once __DIR__.'/linkstat-autoload.php';

use linkstat\TableMananger;
use linkstat\Main;

register_activation_hook(__FILE__, 'Install');
register_deactivation_hook(__FILE__, 'Uninstall');

function Install()
{
  $tables = new TableMananger();
  $tables->Install();
}

function Uninstall()
{
  $tables = new TableMananger();
  $tables->Uninstall();
}

new Main();
