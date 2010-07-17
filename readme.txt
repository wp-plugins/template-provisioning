=== Plugin Name ===
Contributors: jasontremblay
Donate link: http://www.bigbigtech.com/
Tags: template, theme, css, javascript
Requires at least: 2.8
Tested up to: 3.0
Stable tag: 0.2.3

The Template Provisioning plugin automatically links each blog page to .css and .js files that correspond to its template.

== Description ==

= Overview =

The Template Provisioning plugin automatically links to stylesheet and javascript files in your theme directories based on the template file that renders a page. It searches in several pre-defined locations for files, and includes whichever files are found.

I wrote this plugin because I prefer this method to using Wordpress's conditional tags in my header.  Keeping resources for different templates separate helps me keep my custom theme directories clean and organized.

For example, if I have a custom template "map.php" that is being used by a static page, I can create "css/map.css" and "js/map.js" files and they'll be automatically linked-up by this plugin.

= Requirements =

Will this plugin work with your theme? Probably. It's completely additive, and doesn't change anything that would affect other plugins.  I have been using most of this code since Wordpress 2.5 or so.  But be warned... I haven't thoroughly tested it across versions of Wordpress.  I'll try to do that soon and post the results.

== Installation ==

= Installation =

1. Download and unzip the plugin files (bigbig-template-provisioning.zip)
2. Move the 'bigbig-template-provisioning' folder into your '/wp-content/plugins/' directory.
3. Activate the plugin through the Wordpress Admin 'Plugins' page.
4. Modify your theme, per the usage instructions below.

= Usage =

Using the plugin is easy.  Just create some .css and .js files where the plugin expects them... in the same directory as your template files.  There are a series of files that it looks for when rendering a page using any given template file:

Stylesheets for "&lt;template\_name&gt;.php":
included in page &lt;head&gt; by wp\_head() function

* css/global.css
* css/ie/global.css
* css/&lt;template\_name&gt;.css
* css/ie/&lt;template\_name&gt;.css

Javascript files for "&lt;template\_name&gt;.php":
included in page &lt;head&gt; by wp\_head() function

* js/global.js
* js/&lt;template\_name&gt;.js

End-of-page Javascript files for "&lt;template\_name&gt;.php" footer:
included near the &lt;/body&gt; tag by wp\_footer() function

* js/global.footer.js
* js/&lt;template\_name&gt;.footer.js

If your script or stylesheet depends on others (i.e. jQuery) being loaded first, simply enqueue them in your template header before the call to wp_head().

You can also specify dependencies in comments in included scripts and stylesheets using the following syntax (dependencies should be comma-separated):
* // NEEDS: jquery, jquery-cycle

NOTE: the above syntax will not actually enqueue the dependencies... it will only require them for our included scripts.

== Frequently Asked Questions ==

None yet... post your questions to the [plugin homepage](http://www.bigbigtech.com/wordpress-plugins/template-provisioning "Template Provisioning Homepage")

== Changelog ==

= 0.2.3 =

* Scripts/stylesheets can now specify their own dependencies

= 0.2.2 =

* Removed file extension from enqueue handles
* Changed "Required at least" back to 2.8
* Don't enqueue resources on admin pages

= 0.2.1 =

* Use global $is_IE variable to conditionally include IE stylesheets
* If WordPress version &lt; 3.0, fall back to old plugin hooks

= 0.2 =

* Plugin now uses WordPress native enqueuing functions
* Plugin now looks for global.footer.js and &lt;template\_name&gt;.footer.js
* Plugin now hooks into "template_include" filter instead of separate template filters
* Replaced underscores with dashes in plugin / directory name
* Removed "BigBig" prefix from the base class and base file

= 0.1 =

* Initial version
