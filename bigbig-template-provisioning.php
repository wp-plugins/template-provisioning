<?php
/*
Plugin Name: Template Provisioning
Plugin URI: http://www.bigbigtech.com/wordpress-plugins/template-provisioning/
Description: Automatically links to css and js files for the current template file.
Version: 0.1
Author: Jason Tremblay
Author URI: http://www.alertmybanjos.com
*/

/*  Copyright 2009  Jason Tremblay  (email : jason@bigbigtech.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* ----------------------------------------
* DO IT WITH CLASS
* ----------------------------------------*/
class BigBig_Template_Provisioning
{
	static $template_basename;
	
	function initialize()
	{
		// INITIALIZE WITH THE SAME DEFAULT AS WORDPRESS
		BigBig_Template_Provisioning::$template_basename = 'index';
		
		// DELAY TILL PLUGINS LOADED TO BE REASONABLY SURE FILTER IS ADDED LAST,
		// TAKING ADVANTAGE OF OTHER COOL TEMPLATE PLUGINS LIKE post-templates-by-cat.php
		// (WANTING TO BE LAST ISN'T GREEDY, SINCE I DON'T CHANGE ANYTHING, RIGHT?)
		add_action('plugins_loaded', array("BigBig_Template_Provisioning","add_template_filters"), 10);
		
		// ADD ACTIONS TO OUTPUT THE HEAD CONTENT
		add_action('wp_head', array("BigBig_Template_Provisioning","plugin_status"));
		add_action('wp_head', array("BigBig_Template_Provisioning","template_css"));
		add_action('wp_head', array("BigBig_Template_Provisioning","template_js"));
		add_action('wp_footer', array("BigBig_Template_Provisioning","template_footer_js"));
	}
	
	function add_template_filters()
	{
		// DO THIS FOR EVERY TYPE OF TEMPLATE FILE
		add_filter('404_template', array('BigBig_Template_Provisioning','store_template_filename'), 11);
		add_filter('archive_template', array('BigBig_Template_Provisioning','store_template_filename'), 11);
		add_filter('attachment_template', array('BigBig_Template_Provisioning','store_template_filename'), 11);
		add_filter('author_template', array('BigBig_Template_Provisioning','store_template_filename'), 11);
		add_filter('category_template', array('BigBig_Template_Provisioning','store_template_filename'), 11);
		add_filter('comments_popup_template', array('BigBig_Template_Provisioning','store_template_filename'), 11);
		add_filter('date_template', array('BigBig_Template_Provisioning','store_template_filename'), 11);
		add_filter('home_template', array('BigBig_Template_Provisioning','store_template_filename'), 11);
		add_filter('page_template', array('BigBig_Template_Provisioning','store_template_filename'), 11);
		add_filter('paged_template', array('BigBig_Template_Provisioning','store_template_filename'), 11);
		add_filter('search_template', array('BigBig_Template_Provisioning','store_template_filename'), 11);
		add_filter('single_template', array('BigBig_Template_Provisioning','store_template_filename'), 11);
		add_filter('tag_template', array('BigBig_Template_Provisioning','store_template_filename'), 11);
		add_filter('taxonomy_template', array('BigBig_Template_Provisioning','store_template_filename'), 11);
	}
	
	// WHEN WORDPRESS DECIDES WHICH TEMPLATE TO USE, AND FILTERS THE PATH,
	// STORE THAT PATH IN AN INSTANCE VARIABLE FOR LATER USE
	function store_template_filename($template_filepath = '')
	{
		if ('' != $template_filepath) {
			BigBig_Template_Provisioning::$template_basename = basename($template_filepath, '.php');
		}
		return $template_filepath;
	}
	
	function plugin_status()
	{
		// collect data for view and render the view
		$data = array(
			'template_basename' => BigBig_Template_Provisioning::$template_basename,
			'TEMPLATEPATH' => TEMPLATEPATH,
			'STYLESHEETPATH' => STYLESHEETPATH
		);
		BigBig_Template_Provisioning::display('plugin-status', $data);
	}
	
	function template_css()
	{
		// determine filenames and URLs
		$css_filename_template = BigBig_Template_Provisioning::$template_basename.'.css';
		$css_href_global = (file_exists(TEMPLATEPATH."/css/global.css") ? get_bloginfo('template_directory')."/css/global.css" : '');
		$css_href_global_ie = (file_exists(TEMPLATEPATH."/css/ie/global.css") ? get_bloginfo('template_directory')."/css/ie/global.css" : '');
		$css_href_template = (file_exists(TEMPLATEPATH."/css/$css_filename_template") ? get_bloginfo('template_directory')."/css/$css_filename_template" : '');
		$css_href_template_ie = (file_exists(TEMPLATEPATH."/css/ie/$css_filename_template") ? get_bloginfo('template_directory')."/css/ie/$css_filename_template" : '');
		
		// collect data for view and render the view
		$data = array(
			'template_basename' => BigBig_Template_Provisioning::$template_basename,
			'css_filename_template' => $css_filename_template,
			'css_href_global' => $css_href_global,
			'css_href_template' => $css_href_template,
			'css_href_ie' => $css_href_ie,
		);
		BigBig_Template_Provisioning::display('template-css', $data);
	}
	
	function template_js()
	{
		// determine filenames and URLs
		$js_filename_template = BigBig_Template_Provisioning::$template_basename.'.js';
		$js_src_global = (file_exists(TEMPLATEPATH."/js/global.js") ? get_bloginfo('template_directory')."/js/global.js" : '');
		$js_src_template = (file_exists(TEMPLATEPATH."/js/$js_filename_template") ? get_bloginfo('template_directory')."/js/$js_filename_template" : '');
		
		// collect data for view and render the view
		$data = array(
			'template_basename' => BigBig_Template_Provisioning::$template_basename,
			'js_filename_template' => $js_filename_template,
			'js_src_global' => $js_src_global,
			'js_src_template' => $js_src_template,
		);
		BigBig_Template_Provisioning::display('template-js', $data);
	}
	
	function template_footer_js()
	{
		// determine filenames and URLs
		$js_filename_template = BigBig_Template_Provisioning::$template_basename.'_footer.js';
		$js_src_global = (file_exists(TEMPLATEPATH."/js/global_footer.js") ? get_bloginfo('template_directory')."/js/global_footer.js" : '');
		$js_src_template = (file_exists(TEMPLATEPATH."/js/$js_filename_template") ? get_bloginfo('template_directory')."/js/$js_filename_template" : '');
		
		// collect data for view and render the view
		$data = array(
			'template_basename' => BigBig_Template_Provisioning::$template_basename,
			'js_filename_template' => $js_filename_template,
			'js_src_global' => $js_src_global,
			'js_src_template' => $js_src_template,
		);
		BigBig_Template_Provisioning::display('template-js', $data);
	}
	
	// RENDER A VIEW
	function display($view, $data = array())
	{
		extract($data);
		include(dirname(__FILE__)."/views/$view.php");
	}
	
	// REPLACE FILE EXTENSION
	function replace_extension($name,$newext)
	{
		return preg_replace("/\.[^.]*$/", ".$newext", $name);
	}

}

/* --------------------------------------------------
* INITIALIZE PLUGIN
* --------------------------------------------------*/
BigBig_Template_Provisioning::initialize();

?>
