=== Plugin Name ===
Contributors: jasontremblay
Donate link: http://www.bigbigtech.com/
Tags: template, theme, css, javascript
Requires at least: 2.8.0
Tested up to: 2.8.1
Stable tag: 0.1

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

* js/global\_footer.js
* js/&lt;template\_name&gt;\_footer.js

== Frequently Asked Questions ==

None yet... post your questions to the [plugin homepage](http://www.bigbigtech.com/wordpress-plugins/template-provisioning "Template Provisioning Homepage")

== Changelog ==

= 0.2 =

* Replaced underscores with dashes in plugin / directory name

= 0.1 =

* Initial version
