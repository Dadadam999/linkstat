<?php
/**
 * @package linkstat
 * @author Bogdanov Andrey (swarzone2100@yandex.ru)
 */

namespace linkstat\Tables;

class LinksTable
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
           "CREATE TABLE `" . $this->wpdb->prefix . "linkstat_links`
           (
           id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	         url VARCHAR(2048) NOT NULL,
	         UNIQUE KEY id (id)
           )"
        );
    }

    public function Delete()
    {
        $this->wpdb->get_results(
          "DROP TABLE `" . $this->wpdb->prefix . "linkstat_links`"
        );
    }

    public function GetAll()
    {
      return $this->wpdb->get_results(
         "SELECT id, url
         FROM `" . $this->wpdb->prefix . "linkstat_links`",
         ARRAY_A
        );
    }

    public function GetLink($id)
    {
      return $this->wpdb->get_results(
         "SELECT id, url
         FROM `" . $this->wpdb->prefix . "linkstat_links`
         WHERE id = " . $id,
         ARRAY_A
        )[0];
    }

    public function GetLinkOnUrl($url)
    {
      return $this->wpdb->get_results(
         "SELECT id, url
         FROM `" . $this->wpdb->prefix . "linkstat_links`
         WHERE url = '" . $url . "'",
         ARRAY_A
        )[0];
    }

    public function AddLink($link_url)
    {
      $last_id = $this->wpdb->get_results(
      "SELECT MAX(`id`) AS last
       FROM `" . $this->wpdb->prefix . "linkstat_links`", ARRAY_A
      );

      $current_id = (int)$last_id[0]['last'] + 1;

      $this->wpdb->get_results(
        "INSERT INTO `" . $this->wpdb->prefix . "linkstat_links` (`id`, `url`)
        VALUES (" . $current_id . ", '" . $link_url . "')"
      );
    }

    public function DeleteLink($id)
    {
        $this->wpdb->get_results(
        "DELETE FROM `" . $this->wpdb->prefix . "linkstat_links` WHERE id = " . $id
        );
    }
}
