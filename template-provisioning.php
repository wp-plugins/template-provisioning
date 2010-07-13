<?php
/*
Plugin Name: Template Provisioning
Plugin URI: http://www.bigbigtech.com/wordpress-plugins/template-provisioning/
Description: Automatically links to css and js files for the current template file.
Version: 0.2.2
Author: Jason Tremblay
Author URI: http://www.alertmybanjos.com
*/

/*	Copyright 2010  Jason Tremblay  (email : jason@bigbigtech.com)

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
	  // WE ONLY WANT THIS ON THE FRONT-END, NOT ADMIN
	  // (although it would be cool to handle admin pages also)
	  if (is_admin()) return;
	  
		// INITIALIZE WITH THE SAME DEFAULT AS WORDPRESS
		Template_Provisioning::$template_basename = 'index';
		
		// DELAY TILL PLUGINS LOADED TO BE REASONABLY SURE FILTER IS ADDED LAST,
		// TAKING ADVANTAGE OF OTHER COOL TEMPLATE PLUGINS LIKE post-templates-by-cat.php
		// (WANTING TO BE LAST ISN'T GREEDY, SINCE I DON'T CHANGE ANYTHING, RIGHT?)
		add_action('plugins_loaded', array("Template_Provisioning","add_template_filters"), 10);
		
		// ADD ACTIONS TO OUTPUT THE HEAD CONTENT
		add_action('wp_head', array("Template_Provisioning","helpful_comment"));
		add_action('wp_print_styles', array("Template_Provisioning","enqueue_css"));
		add_action('wp_print_scripts', array("Template_Provisioning","enqueue_js"));
	}
	
	function add_template_filters()
	{
		
		// if this is an elder WordPress (for backward compatibility)
		if ( !isset($GLOBALS['wp_version']) || version_compare($GLOBALS['wp_version'], '3.0', '<') ) {
			
			// DO THIS FOR EVERY (PRE-3.0) TYPE OF TEMPLATE FILE
			add_filter('404_template', array('Template_Provisioning','store_template_filename'), 11);
			add_filter('archive_template', array('Template_Provisioning','store_template_filename'), 11);
			add_filter('attachment_template', array('Template_Provisioning','store_template_filename'), 11);
			add_filter('author_template', array('Template_Provisioning','store_template_filename'), 11);
			add_filter('category_template', array('Template_Provisioning','store_template_filename'), 11);
			add_filter('comments_popup_template', array('Template_Provisioning','store_template_filename'), 11);
			add_filter('date_template', array('Template_Provisioning','store_template_filename'), 11);
			add_filter('home_template', array('Template_Provisioning','store_template_filename'), 11);
			add_filter('page_template', array('Template_Provisioning','store_template_filename'), 11);
			add_filter('paged_template', array('Template_Provisioning','store_template_filename'), 11);
			add_filter('search_template', array('Template_Provisioning','store_template_filename'), 11);
			add_filter('single_template', array('Template_Provisioning','store_template_filename'), 11);
			add_filter('tag_template', array('Template_Provisioning','store_template_filename'), 11);
			add_filter('taxonomy_template', array('Template_Provisioning','store_template_filename'), 11);
		
		// if this is WordPress 3.0 or greater
		} else {

			// THIS IS THE NEW 3.0 HOTNESS!!
			add_filter('template_include', array('Template_Provisioning','store_template_filename'), 11);
			
		}
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
	
	function helpful_comment()
	{
		$template_basename = Template_Provisioning::$template_basename;
		echo "\n\n";
		echo sprintf("<!-- TEMPLATE PROVISIONING CSS: 'global.css', 'ie/global.css', '%s.css', 'ie/%s.css' -->", $template_basename, $template_basename);
		echo "\n";
		echo sprintf("<!-- TEMPLATE PROVISIONING JS: 'global.js', '%s.js', 'global.footer.js', '%s.footer.js' -->", $template_basename, $template_basename);
		echo "\n\n";
	}
	
	function enqueue_css()
	{
		global $is_IE;
		
		$stylesheets = array();
		$stylesheets[] = 'global.css';
		if ($is_IE) $stylesheets[] = 'ie/global.css';
		$stylesheets[] = Template_Provisioning::$template_basename.'.css';
		if ($is_IE) $stylesheets[] = 'ie/'.Template_Provisioning::$template_basename.'.css';
		
		foreach($stylesheets as $stylesheet) {
		  $file_path = TEMPLATEPATH.'/css/'.$stylesheet;
			if (file_exists($file_path)) {
				wp_enqueue_style(
					$handle = $stylesheet, 
					$src = get_bloginfo('template_directory').'/css/'.$stylesheet,
					$dependencies = array(),
					$version = filemtime($file_path),
					$media = false
				);
			}
		}
	}
	
	function enqueue_js()
	{
		$scripts = array(
			'global.js',
			'global.footer.js',
			Template_Provisioning::$template_basename.'.js',
			Template_Provisioning::$template_basename.'.footer.js'
		);
		foreach($scripts as $script) {
		  $file_path = TEMPLATEPATH.'/js/'.$script;
			if (file_exists($file_path)) {
				wp_enqueue_script(
					$handle = $script,
					$src = get_bloginfo('template_directory').'/js/'.$script,
					$dependencies = array(),
					$version = filemtime($file_path),
					$in_footer = (int) preg_match('/\.footer\.js$/', $script)
				);
			}
		}
	}

}

/* --------------------------------------------------
* INITIALIZE PLUGIN
* --------------------------------------------------*/
Template_Provisioning::initialize();

?>
