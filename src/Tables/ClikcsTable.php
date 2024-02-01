<?php
/**
 * @package linkstat
 * @author Bogdanov Andrey (swarzone2100@yandex.ru)
 */
namespace linkstat\Tables;

class ClikcsTable
{

  protected $wpdb;

  public function __construct()
  {
      global $wpdb;
      $this->wpdb = $wpdb;
  }

  public function Create()
  {
      $this->wpdb->get_results(
         "CREATE TABLE `" . $this->wpdb->prefix . "linkstat_clicks`
         (
         id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
         link_id BIGINT(20) UNSIGNED NOT NULL,
         user_id BIGINT(20) UNSIGNED NOT NULL,
         click_time DATETIME,
         click_fixing MEDIUMINT(10) UNSIGNED NOT NULL,
         UNIQUE KEY id (id)
         )"
      );
  }

  public function Delete()
  {
      $this->wpdb->get_results(
        "DROP TABLE `" . $this->wpdb->prefix . "linkstat_clicks`"
      );
  }

  public function GetCountOnLink($link_id)
  {
      return  $this->wpdb->get_results(
          "SELECT SUM(`click_fixing`) AS sum
          FROM `" . $this->wpdb->prefix . "linkstat_clicks`
          WHERE link_id = " . $link_id, ARRAY_A
      )[0]['sum'];
  }

  public function AddClick($link_id, $user_id)
  {
      $last_id = $this->wpdb->get_results(
          "SELECT MAX(`id`) AS last
          FROM `" . $this->wpdb->prefix . "linkstat_clicks`", ARRAY_A
      );

      $current_id = (int)$last_id[0]['last'] + 1;
      date_default_timezone_set('Europe/Moscow');
      $presence_time = date("Y-m-d H:i:s");

      $this->wpdb->get_results(
          "INSERT INTO `" . $this->wpdb->prefix . "linkstat_clicks` (`id`, `link_id`, `user_id`, `click_time`, `click_fixing`)
           VALUES (" . $current_id . ", " . $link_id . ", " . $user_id . ", '" . $presence_time . "', 1)"
      );
  }
}
