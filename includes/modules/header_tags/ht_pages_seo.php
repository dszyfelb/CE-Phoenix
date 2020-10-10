<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2015 osCommerce

  Released under the GNU General Public License
*/

  class ht_pages_seo {
    var $code = 'ht_pages_seo';
    var $group = 'header_tags';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function __construct() {
      $this->title = MODULE_HEADER_TAGS_PAGES_SEO_TITLE;
      $this->description = MODULE_HEADER_TAGS_PAGES_SEO_DESCRIPTION;
      $this->description .= '<div class="alert alert-warning">' . MODULE_HEADER_TAGS_PAGES_SEO_HELPER . '</div>';

      if ( defined('MODULE_HEADER_TAGS_PAGES_SEO_STATUS') ) {
        $this->sort_order = MODULE_HEADER_TAGS_PAGES_SEO_SORT_ORDER;
        $this->enabled = (MODULE_HEADER_TAGS_PAGES_SEO_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate, $page;

      if ( (defined('META_SEO_TITLE')) && (strlen(META_SEO_TITLE) > 0) || (!empty($page['navbar_title'])) || (!empty($page['pages_title'])) ) {
        $title = defined('META_SEO_TITLE') ? META_SEO_TITLE : $page['navbar_title'] ?? $page['pages_title'] ?? null;
        if (tep_not_null($title)) {
          $oscTemplate->setTitle(tep_output_string($title)  . MODULE_HEADER_TAGS_PAGES_SEO_SEPARATOR . $oscTemplate->getTitle());
        }
      }
      if ( (defined('META_SEO_DESCRIPTION')) && (strlen(META_SEO_DESCRIPTION) > 0) || (!empty($page['pages_seo_description'])) ) {
        $desc = defined('META_SEO_DESCRIPTION') ? META_SEO_DESCRIPTION : $page['pages_seo_description'] ?? null;
        if (tep_not_null($desc)) {
          $oscTemplate->addBlock('<meta name="description" content="' . tep_output_string($desc) . '" />' . "\n", $this->group);
        }
      }
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_HEADER_TAGS_PAGES_SEO_STATUS');
    }

    function install() {
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Pages SEO Module', 'MODULE_HEADER_TAGS_PAGES_SEO_STATUS', 'True', 'Do you want to allow this module to write SEO to your Pages?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_PAGES_SEO_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_HEADER_TAGS_PAGES_SEO_STATUS', 'MODULE_HEADER_TAGS_PAGES_SEO_SORT_ORDER');
    }
  }
  
