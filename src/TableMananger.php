<?php
/**
 * @package linkstat
 * @author Bogdanov Andrey (swarzone2100@yandex.ru)
 */

 namespace linkstat;

 use WP_REST_Request;
 use wpdb;

 use linkstat\Tables\LinksTable;
 use linkstat\Tables\ClikcsTable;

 class TableMananger
 {
   protected $wpdb;
   public $linksTable;
   public $clikcsTable;

   public function __construct()
   {
       global $wpdb;
       $this->wpdb = $wpdb;
       $this->Init();
   }

   protected function Init() : self
   {
     $this->linksTable = new LinksTable();
     $this->clikcsTable = new ClikcsTable();
     return $this;
   }

   public function Install()
   {
     $this->linksTable->Create();
     $this->clikcsTable->Create();
   }

   public function Uninstall()
   {
     $this->linksTable->Delete();
     $this->clikcsTable->Delete();
   }
 }
?>
