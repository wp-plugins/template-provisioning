<?php
/*
Plugin Name: Template Provisioning
Plugin URI: http://www.bigbigtech.com/wordpress-plugins/template-provisioning/
Description: Automatically links to css and js files for the current template file.
Version: 0.1
Author: Jason Tremblay
Author URI: http://www.alertmybanjos.com
*/

/*  Copyright 2010  Jason Tremblay  (email : jason@bigbigtech.com)

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
class Template_Provisioning
{
	static $template_basename;
	
	function initialize()
	{
		// INITIALIZE WITH THE SAME DEFAULT AS WORDPRESS
		Template_Provisioning::$template_basename = 'index';
		
		// DELAY TILL PLUGINS LOADED TO BE REASONABLY SURE FILTER IS ADDED LAST,
		// TAKING ADVANTAGE OF OTHER COOL TEMPLATE PLUGINS LIKE post-templates-by-cat.php
		// (WANTING TO BE LAST ISN'T GREEDY, SINCE I DON'T CHANGE ANYTHING, RIGHT?)
		add_action('plugins_loaded', array("Template_Provisioning","add_template_filters"), 10);
		
		// ADD ACTIONS TO OUTPUT THE HEAD CONTENT
		add_action('wp_head', array("Template_Provisioning","plugin_status"));
		add_action('wp_print_styles', array("Template_Provisioning","enqueue_css"));
		add_action('wp_head', array("Template_Provisioning","template_js"));
		add_action('wp_footer', array("Template_Provisioning","template_footer_js"));
	}
	
	function add_template_filters()
	{
	  // THIS WON'T WORK FOR PAGES MODIFIED BY TEMPLATE_REDIRECT ACTION
		add_filter('template_include', array('Template_Provisioning','store_template_filename'), 11);
	}
	
	// WHEN WORDPRESS DECIDES WHICH TEMPLATE TO USE, AND FILTERS THE PATH,
	// STORE THAT PATH IN AN INSTANCE VARIABLE FOR LATER USE
	function store_template_filename($template_filepath = '')
	{
		if ('' != $template_filepath) {
			Template_Provisioning::$template_basename = basename($template_filepath, '.php');
		}
		return $template_filepath;
	}
	
	function plugin_status()
	{
		// collect data for view and render the view
		$data = array(
			'template_basename' => Template_Provisioning::$template_basename,
			'TEMPLATEPATH' => TEMPLATEPATH,
			'STYLESHEETPATH' => STYLESHEETPATH
		);
		Template_Provisioning::display('plugin-status', $data);
	}
	
	function enqueue_css()
	{
	  $stylesheets = array(
	    'global.css',
	    'ie/global.css',
	    Template_Provisioning::$template_basename.'.css',
	    'ie/'.Template_Provisioning::$template_basename.'.css'
	  );
	  foreach($stylesheets as $stylesheet) {
  	  if (file_exists(TEMPLATEPATH.'/css/'.$stylesheet))
  	    wp_enqueue_style(preg_replace('/[^a-zA-Z0-9]/','-',$stylesheet), get_bloginfo('template_directory').'/css/'.$stylesheet);
	  }
	}
	
	function template_js()
	{
		// determine filenames and URLs
		$js_filename_template = Template_Provisioning::$template_basename.'.js';
		$js_src_global = (file_exists(TEMPLATEPATH."/js/global.js") ? get_bloginfo('template_directory')."/js/global.js" : '');
		$js_src_template = (file_exists(TEMPLATEPATH."/js/$js_filename_template") ? get_bloginfo('template_directory')."/js/$js_filename_template" : '');
		
		// collect data for view and render the view
		$data = array(
			'template_basename' => Template_Provisioning::$template_basename,
			'js_filename_template' => $js_filename_template,
			'js_src_global' => $js_src_global,
			'js_src_template' => $js_src_template,
		);
		Template_Provisioning::display('template-js', $data);
	}
	
	function template_footer_js()
	{
		// determine filenames and URLs
		$js_filename_template = Template_Provisioning::$template_basename.'_footer.js';
		$js_src_global = (file_exists(TEMPLATEPATH."/js/global_footer.js") ? get_bloginfo('template_directory')."/js/global_footer.js" : '');
		$js_src_template = (file_exists(TEMPLATEPATH."/js/$js_filename_template") ? get_bloginfo('template_directory')."/js/$js_filename_template" : '');
		
		// collect data for view and render the view
		$data = array(
			'template_basename' => Template_Provisioning::$template_basename,
			'js_filename_template' => $js_filename_template,
			'js_src_global' => $js_src_global,
			'js_src_template' => $js_src_template,
		);
		Template_Provisioning::display('template-js', $data);
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
Template_Provisioning::initialize();

?>