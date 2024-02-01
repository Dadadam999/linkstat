<?php
/**
 * @package linkstat
 * @author Bogdanov Andrey (swarzone2100@yandex.ru)
 */
namespace linkstat;

use linkstat\TableMananger;
use WP_REST_Request;

class Main
{
    protected $tableMananger;
    protected $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->Init();
    }

    protected function init() : self
    {
         $this->tableMananger = new TableMananger;
         $this
             ->apiInit()
             ->scriptAdd()
             ->ShortcodeInit();

        return $this;
    }

    protected function scriptAdd() : self
    {
        wp_enqueue_style( 'shortcodes', plugins_url('linkstat/assets/css/shortcodes.css') );

        add_action('wp_enqueue_scripts', function()
        {
            wp_enqueue_script(
                'linkstat-client',
                plugins_url('linkstat/assets/js/linkstat-client.js'),
                [],
                '0.1.5'
            );
        });
        return $this;
    }

    protected function ShortcodeInit() : self
    {
        add_shortcode('track-link', function($atts, $content) {
            $atts = shortcode_atts([
                'url' => '',
                'name' => '',
                'css-class' => '',
                'css-style' => ''
            ], $atts);

            $user_id = get_current_user_id();

            if(empty($this->tableMananger->linksTable->GetLinkOnUrl($atts['url'])))
              $this->tableMananger->linksTable->AddLink($atts['url']);

            $link = $this->tableMananger->linksTable->GetLinkOnUrl($atts['url']);
            
            //echo '<pre>';
            //var_dump($link);
            //echo '</pre>';

            $html_id = htmlspecialchars('linkstat-presence-button');
            $html_url = $atts['url'];
            $html_style = htmlspecialchars($atts['css-style']);
            $html_class = htmlspecialchars($atts['css-class']);
            $html_md5 = md5('linkstat-button-' . $link['id']);
            $onclick = 'linkstatClient.click(\'' . $html_id . '\', \'' . $link['id'] . '\', \'' . $html_md5 . '\');';
            $html = '<a href="' . $html_url . '" id="' . $html_id . '" class="' . $html_class . '" style="' . $html_style . '"onclick="'. $onclick. '">' . $atts['name'] . '</a>';

            $user_info_per = get_userdata( get_current_user_id() );
            if ($user_info_per->user_level >= 7) {
              $html .= '<br><span>Всего прошло по ссылке: ' . $this->tableMananger->clikcsTable->GetCountOnLink($link['id']) . '</span>';
            }
            return $html;
        });

        return $this;
    }

    protected function apiInit() : self
    {
        add_action('rest_api_init', function() {
            register_rest_route(
                'linkstat/v1',
                '/click',
                [
                    'methods' => 'POST',
                    'callback' => function(WP_REST_Request $request) {
                        $link_id = (int)$request->get_param('linkstat-button-linkid');

                        if (empty($link_id))
                            return [
                                'code' => -99,
                                'message' => 'Too few arguments for this argument.'
                            ];

                        $user_id = get_current_user_id();

                        if(empty($user_id))
                          $user_id = -1;

                        $this->tableMananger->clikcsTable->AddClick($link_id, $user_id);

                        return [
                            'code' => 0,
                            'message' => 'Success.'
                        ];

                    }
                    //,
                    //'permission_callback' => function(WP_REST_Request $request) {
                    //    return $request->get_param('linkstat-button-key') === md5('linkstat-button-' . $request->get_param('linkstat-button-linkid'));
                    //}
                ]
            );
        });
        return $this;
    }
}
