<?php
/*
Plugin Name: Bootstrap Shortcodes
Plugin URI: http://wp-snippets.com/freebies/bootstrap-shortcodes or https://github.com/filipstefansson/bootstrap-shortcodes
Description: The plugin adds a shortcodes for all Bootstrap elements.
Version: 1.1
Author: Filip Stefansson
Author URI: http://wp-snippets.com
Modified by: TwItCh AKA Dustin Crisman twitch@twitch.es
Modified URI: https://github.com/TwItChDW/bootstrap-shortcodes/
Modified by: KayCreations
Modified URI: https://github.com/KayCreations/bootstrap-shortcodes/
License: GPL2
*/

class BoostrapShortcodes
{

    /**
     * construct
     */
    function __construct()
    {
        add_action('init', array($this, 'add_shortcodes'));

        // Remove wpautop from content
        remove_filter('the_content', 'wpautop');
        add_filter('the_content', 'wpautop', 99);
    }

    /**
     * add_shortcoes
     */
    function add_shortcodes()
    {
        add_shortcode('button', array($this, 'bs_button'));
        add_shortcode('alert', array($this, 'bs_alert'));
        add_shortcode('code', array($this, 'bs_code'));
        add_shortcode('span', array($this, 'bs_span'));
        add_shortcode('row', array($this, 'bs_row'));
        add_shortcode('label', array($this, 'bs_label'));
        add_shortcode('badge', array($this, 'bs_badge'));
        add_shortcode('icon', array($this, 'bs_icon'));
        add_shortcode('icon_white', array($this, 'bs_icon_white'));
        add_shortcode('table', array($this, 'bs_table'));
        add_shortcode('collapsibles', array($this, 'bs_collapsibles'));
        add_shortcode('collapse', array($this, 'bs_collapse'));
        add_shortcode('well', array($this, 'bs_well'));
        add_shortcode('tabs', array($this, 'bs_tabs'));
        add_shortcode('tab', array($this, 'bs_tab'));
    }

    /**
     * bs_button
     * 
     * @return string button
     */
    function bs_button($atts, $content = null)
    {
        $class = array('btn');

        if (isset($atts['type']) && ($type = preg_replace('/^btn-/', '', $atts['type']))) {
            $class[] = esc_attr('btn-' . $type);
        }

        if (isset($atts['size']) && ($type = preg_replace('/^btn-/', '', $atts['size']))) {
            $class[] = esc_attr('btn-' . $type);
        }

        if (isset($atts['extra'])) {
            $class[] = esc_attr($atts['extra']);
        }

        return sprintf('<a href="%s" class="%s">%s</a>', 
            $atts['link'], 
            join(' ', $class), 
            do_shortcode($content)
        );
    }

    /**
     * bs_alert
     * 
     * @return string alert
     */
    function bs_alert($atts, $content = null)
    {
        $class = array('alert');

        if (isset($atts['type']) && ($type = preg_replace('/^alert-/', '', $atts['type']))) {
            $class[] = esc_attr('alert-' . $type);
        }

        if (isset($atts['close']))
            $close = '<button type="button" class="close" data-dismiss="alert">&times;</button>';

        return sprintf('<div class="alert %s">%s %s</div>',
            join(' ', $class),
            do_shortcode($content), 
            $close
        );

    }

    /**
     * bs_code
     * 
     * @return string code
     */
    function bs_code($atts, $content = null)
    {
        return sprintf('<pre><code>%s</code></pre>', do_shortcode($content));
    }

    /**
     * bs_span
     * 
     * @return string span
     */
    function bs_span($atts, $content = null)
    {
        $class = array();

        if (isset($atts['size'])) {
            $class[] = esc_attr('span' . $atts['size']);
        } else {
            $class[] = 'span6';
        }

        if (isset($atts['extra'])) {
            $class[] = esc_attr($atts['extra']);
        }

        return sprintf('<div class="%s">%s</div>',
            join(' ', $class),
            do_shortcode($content)
        );
    }

    /**
     * bs_row
     * 
     * @return string row
     */
    function bs_row($atts, $content = null)
    {
        $class = array();

        if (isset($atts['method']) && ($method = preg_replace('/^row-/', '', $atts['method']))) {
            $class[] = esc_attr('row-' . $method);
        } else {
            $class[] = 'row';
        }

        if (isset($atts['extra']) && (preg_match('/^[a-z]+$/', $atts['extra']))) {
            $class[] = esc_attr($atts['extra']);
        } 

        return sprintf("<div class='%s'>%s</div>",
            join(' ', $class),
            do_shortcode($content) 
        );
    }

    /**
     * bs_label
     * 
     * @return string label
     */
    function bs_label($atts, $content = null)
    {
        $class = array('label');

        if (isset($atts['type']) && preg_match('/^[a-z]+$/', $atts['type'])) {
            $class[] = 'label-' . esc_attr($atts['type']);
        }

        return sprintf('<span class="%s">%s</span>', 
            join(' ', $class),
            do_shortcode($content)
        );
    }

    /**
     * bs_badge
     * 
     * @return string badge
     */
    function bs_badge($atts, $content = null)
    {
        $class = array('badge');

        if (isset($atts['type']) && ($type = preg_replace('/^badge-/', '', $atts['type']))) {
            $class[] = esc_attr('badge-' . $type);
        }

        return sprintf('<span class="%s">%s</span>', 
            join(' ', $class), 
            do_shortcode($content)
        );
    }

    /**
     * bs_icon
     * 
     * @return string icon
     */
    function bs_icon($atts, $content = null)
    {
        $class = array();

        if (isset($atts['type']) && ($type = preg_replace('/^icon-/', '', $atts['type']))) {
            $class[] = esc_attr('icon-' . $type);
        } else {
            $class[] = 'icon-heart';
        }

        if (isset($atts['color'])) {
            $class[] = 'icon-white';
        }

        return sprintf('<i class="%s"></i>',
            join(' ', $class)
        );
    }

    /**
     * bs_table
     * 
     * @return string table
     */
    function bs_table($atts)
    {
        extract(shortcode_atts(array(
            'cols' => 'none',
            'data' => 'none',
            'type' => 'type'
        ), $atts));

        $cols = explode(',', $cols);
        $data = explode(',', $data);
        $total = count($cols);
        $output = '';
        $output .= '<table class="table table-' . $type . ' table-bordered"><tr>';
        foreach ($cols as $col):
            $output .= '<th>' . $col . '</th>';
        endforeach;
        $output .= '</tr><tr>';
        $counter = 1;
        foreach ($data as $datum):
            $output .= '<td>' . $datum . '</td>';
            if ($counter % $total == 0):
                $output .= '</tr>';
            endif;
            $counter++;
        endforeach;
        $output .= '</table>';

        return $output;
    }


    /**
     * bs_well
     * 
     * @return string well
     */
    function bs_well($atts, $content = null)
    {
        $class = array('well');

        if (isset($atts['type']) && ($type = preg_replace('/^well-/', '', $atts['type']))) {
            $class[] = esc_attr('well-' . $type);
        }

        return sprintf('<div class="%s">%s</div>',
            join(' ', $class),
            do_shortcode($content)
        );
    }

    /**
     * bs_tabs
     * Modified by TwItCh twitch@designweapon.com
     * Now acts a whole nav/tab/pill shortcode solution!
     * 
     * @return string tabs
     */
    function bs_tabs($atts, $content = null)
    {

        if (isset($GLOBALS['tabs_count']))
            $GLOBALS['tabs_count']++;
        else
            $GLOBALS['tabs_count'] = 0;
        extract(shortcode_atts(array(
            'tabtype' => 'nav-tabs',
            'tabdirection' => '',
        ), $atts));
        //DW $defaults = array('tabtype' => 'bla', 'tabdirection' => 'one');
        //DW extract( shortcode_atts( $defaults, array(), $atts ) );

        // Extract the tab titles for use in the tab widget.
        preg_match_all('/tab title="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE);

        $tab_titles = array();
        if (isset($matches[1])) {
            $tab_titles = $matches[1];
        }

        $output = '';

        if (count($tab_titles)) {
            $output .= '<div class="tabbable tabs-' . $tabdirection . '"><ul class="nav ' . $tabtype . '" id="custom-tabs-' . rand(1, 100) . '">';

            $i = 0;
            foreach ($tab_titles as $tab) {
                if ($i == 0)
                    $output .= '<li class="active">';
                else
                    $output .= '<li>';

                $output .= '<a href="#custom-tab-' . $GLOBALS['tabs_count'] . '-' . sanitize_title($tab[0]) . '"  data-toggle="tab">' . $tab[0] . '</a></li>';
                $i++;
            }

            $output .= '</ul>';
            $output .= '<div class="tab-content">';
            $output .= do_shortcode($content);
            $output .= '</div></div>';
        } else {
            $output .= do_shortcode($content);
        }

        return $output;
    }

    /**
     * bs_tab
     * 
     * @return string tab
     */
    function bs_tab($atts, $content = null)
    {
        if (!isset($GLOBALS['current_tabs'])) {
            $GLOBALS['current_tabs'] = $GLOBALS['tabs_count'];
            $state = 'active';
        } else {

            if ($GLOBALS['current_tabs'] == $GLOBALS['tabs_count']) {
                $state = '';
            } else {
                $GLOBALS['current_tabs'] = $GLOBALS['tabs_count'];
                $state = 'active';
            }
        }

        $defaults = array('title' => 'Tab');
        extract(shortcode_atts($defaults, $atts));

        return '<div id="custom-tab-' . $GLOBALS['tabs_count'] . '-' . sanitize_title($title) . '" class="tab-pane ' . $state . '">' . do_shortcode($content) . '</div>';
    }

    /**
     * bs_collapsibles
     * 
     * @return string collapsibles
     */
    function bs_collapsibles($atts, $content = null)
    {
        if (isset($GLOBALS['collapsibles_count']))
            $GLOBALS['collapsibles_count']++;
        else
            $GLOBALS['collapsibles_count'] = 0;

        $defaults = array();
        extract(shortcode_atts($defaults, $atts));

        // Extract the tab titles for use in the tab widget.
        preg_match_all('/collapse title="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE);

        $tab_titles = array();
        if (isset($matches[1])) {
            $tab_titles = $matches[1];
        }

        $output = '';

        if (count($tab_titles)) {
            $output .= '<div class="accordion" id="accordion-' . $GLOBALS['collapsibles_count'] . '">';
            $output .= do_shortcode($content);
            $output .= '</div>';
        } else {
            $output .= do_shortcode($content);
        }

        return $output;
    }

    /**
     * bs_collapse
     * 
     * @return string collapse
     */
    function bs_collapse($atts, $content = null)
    {

        if (!isset($GLOBALS['current_collapse']))
            $GLOBALS['current_collapse'] = 0;
        else
            $GLOBALS['current_collapse']++;


        $defaults = array('title' => 'Tab', 'state' => '');
        extract(shortcode_atts($defaults, $atts));

        if (!empty($state))
            $state = 'in';

        return '
    <div class="accordion-group">
      <div class="accordion-heading">
        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-' . $GLOBALS['collapsibles_count'] . '" href="#collapse_' . $GLOBALS['current_collapse'] . '_' . sanitize_title($title) . '">
          ' . $title . ' 
        </a>
      </div>
      <div id="collapse_' . $GLOBALS['current_collapse'] . '_' . sanitize_title($title) . '" class="accordion-body collapse ' . $state . '">
        <div class="accordion-inner">
          ' . $content . ' 
        </div>
      </div>
    </div>
    ';
    }

}

new BoostrapShortcodes()

?>