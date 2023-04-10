=== WP OER ===
Contributors: navigationnorth, joehobson, johnpaulbalagolan, josepheneldas, arobotnamedchris
Tags: OER, Open Educational Resources, Education, Teaching, Learning
Requires at least: 4.4
Tested up to: 6.0.2
Requires PHP: 7.0
Stable tag: 0.9.3
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Open Educational Resource (OER) management and curation, metadata publishing, and alignment to Common Core State Standards.

== Description ==
WP OER is a free plugin which allows you to create your own open educational resource repository on any WordPress website. Why pay for a proprietary system with limited options? WP OER is customizable, easy to use, and free.

Open Educational Resources (OER) are freely accessible, openly licensed documents and media useful for teaching, learning, and assessing as well as for research purposes.

Alternative and more flexible licensing options have become available as a result of the work of Creative Commons, an organization providing ready-made licensing agreements less restrictive than the "all rights reserved" terms of standard international copyright. These new options have become a "critical infrastructure service for the OER movement." Another license, typically used by developers of OER software, is the GNU General Public License from the free and open-source software (FOSS) community.

More information about Open Education and the U.S. Department of Education's #GoOpen campaign can be found on the Office of Educational Technology's website.

This project has been funded at least or in part with Federal funds from the U.S. Department of Education under Contract Number ED-OOS-13-R-0064. The content of this publication does not necessarily reflect the views or policies of the U.S. Department of Education nor does mention of trade names, commercial products, or organizations imply endorsement by the U.S. Government.

== Installation ==
1. Log in to your site's Dashboard (e.g. www.yourwebsite.com/wp-admin)
2. Click on the "Plugins" tab in the left panel, then click "Add New".
3. Search for "WP OER" and the latest version will appear at the top of the list of results.
4. Install it by clicking the "Install Now" link.
5. When installation finishes, click "Activate Plugin".
6. Now click the blue button on the top right that says "Setup"
7. Choose to import resources, subject areas, academic standards, and resource thumbnails.
8. Visit the "Settings" page of WP OER to customize styles and display options.
9. Enjoy!

== Frequently Asked Questions ==
No frequently asked questions.

== Screenshots ==
1. Import open educational resources and subject areas.
2. Create individual resources and subject areas.
3. Display educational resources on your WordPress website!

== Changelog ==
= 0.9.3 =
* Feature additions for OESE Native American Language Resource Center

= 0.9.2 =
* Fixed the overlapping text on the settings and import pages including the standard list display
* Fixed the Resource Block display error when the resource has no selected subject areas
* Fixed the front-end issue on Subject Resources block sorting and display count
* Tested up to WP 6.0.2

= 0.9.1 =
* Replaced move_uploaded_file with wp_handle_upload function when importing subject areas and resources
* Removed quote around string placeholders used in $wpdb->prepare statements

= 0.9.0 =
* Implemented further sanitizing of input and escaping of displayed data 

= 0.8.9 =
* Upgrade Bootstrap library to 5.1.3
* Applied proper sanitizing of server variables when saving resources and subject areas 
* Replaced usage of $_SERVER['DOCUMENT_ROOT'] variable with ABSPATH

= 0.8.8 =
* Replaced usage of cURL and file_get_contents with HTTP API
* Changed SLL embed code to enqueue external javascript instead of putting it inline
* Applied proper escaping and sanitizing of data for improved plugin security
* Tested up to WP 6.0

= 0.8.7 =
* More interactive admin eperience with the use of REST API
* Made blocks backwards compatible
* Fixed display and alignment issues
* Prevented duplicates when importing resources
* More responsive subject area selection in blocks
* Fixed metafields display in settings

= 0.8.6 =
* Fix block information display being cut-off on WP.org Plugins page

= 0.8.5 =
* Updated the block information on WP.org Plugins page
* Tested up to WP 5.8.2

= 0.8.4 =
* Added Grade Levels settings and customization
* Converted the OER Subjects Index shortcode to a Gutenberg block
* OER Subject Resources Block UI improvements
* Fixed the issue with plain permalinks displaying warnings and preventing the users from logging in
* Updated the Readme Block Names
* Updated the text internationalization
* Updated the texts in the plugin settings
* Tested up to WP 5.8.1

= 0.8.3 =
* Added translations to subject area taxonomy page
* Added support for older versions of Wordpress (WP 5.7 or earlier) when adding a custom block category
* Replaced block_categories with block_categories_all due to deprecation
* Fixed the expand/collapse issue with resource links
* Fixed the sublevels display issue due to attribute escaping

= 0.8.2 =
* Fixed the issue where WYSIWYG visual tab is freezing
* Fixed the issue in Subjects Block where the resources disappear after changing the block's parameters
* Fixed the sorting issue in Subjects Block where unrelated resources appear
* Updated the Bootstrap Library to 4.6.0, Font Awesome to 5.15.3
* Displayed the default value of each metadata field as a placeholder
* Cleaned up code duplication on display templates
* Metadata Fields are now enabled by default
* Setup tab is now visible until used
* Added a Gutenberg block for displaying Resources and Curriculums
* Tested up to WP 5.7.2

= 0.8.1 =
* Improvements, bug fixes, and terminology refactoring for blocks
* Fixes for visual editor on admin metaboxes in WP 5.5 or later

= 0.8.0 =
* New Gutenberg block for Subject Resources
* Fixes to live preview of blocks
* Display styling updates
* Compatibility for latest WordPress versions

= 0.7.8 =
* Fixes for resource display, better styling single resources by type
* Better display of age ranges
* Hide display of domain on internally hosted resources

= 0.7.5 =
* Additional Metadata options - article/information, age levels, instructional time, repository, licensing
* Display connections to curriculum, if wp-curriculum plugin is available
* Separate resource view into different templates based on type
* Embed PDF, Audio, and Video resources for easier access by users
* Related Resources - optional connections, now selectable by editor
* When displaying excerpts, strip HTML tags for legibility

= 0.7.0 =
* Gutenberg blocks - updated for improved displaying OER
* Transcription field - transcripts can now be added on Resources, especially helpful for videos
* Sensitive Materials Warning field - Resources that have potentially sensitive content can now have written notices using this field
* Date Created Estimate field - Resources with no exact date of creation can use this field, which accepts plain text (e.g. "early 1900s")
* Format field - added field for storing information about the original format (physical or otherwise) of resources
* Local media files are now supported and can be selected as a Resource
* Standards are now optional and can be activated/deactivated using the new WP Academic Standards plugin (https://github.com/navnorth/wp-academic-standards)
* Labels of Metadata Fields can be customized using the Metadata Fields Options in the Settings

= 0.6.5 =
* Options for PDF display on resource view
* Pages to browse by Standard
* Preliminary support for Gutenberg editor

= 0.6.1 =
* Improved display of featured resources on subject pages
* Display default image on Recommended Content slider if none available
* Fix saving and display of created and modified dates
* Fix taxonomy query on subject area resource lists

= 0.6.0 =
* Additional keyboard accessibility fixes
* Allow null values for Learning Resource Type and Interactivity

= 0.5.9 =
* Make Youtube video display responsive
* Get Youtube thumbnail image and use as featured image for youtube resource
* Add original resource link below resource description
* Add metadata such as grade and domain to resources in search result
* Fix broken resource tag links
* Fix bug showing nonce error when deleting resource
* Trim search result and subject area resource description to 3 lines with ellipsis using pure css

= 0.5.8 =
* Display YouTube resources as video embeds
* Fix bug with nonce verification when saving resource edits
* Add fallback for image resizing when gd library not installed

= 0.5.7 =
* Updates to adhere to WordPress plugin directory requirements

= 0.5.0 =
* Added image upload when adding/editing subject areas
* Removed old "Subject Images" from admin interface
* Added Next Generation Science Standards to the set of default standards
* Added helper text on General and Style Tab of Settings
* Applied lazy loading on highlighted resources
* Auto reset permalink on plugin activation
* Added initial setup process

= 0.3.0 =
* Created import page for resources, standards and subject areas
* Added load more pagination on list of resources
* Added sorting option on browse resources section
* Made highlighted resources responsive

= 0.2.5 =
* Cleaned up errors when using the plugin
* Added Readme.txt file

== Upgrade Notice ==
No upgrades at this time.
