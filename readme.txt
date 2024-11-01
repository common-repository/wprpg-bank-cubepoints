=== Wordpress Bank with CP support ===
Contributors: uaktags
Tags: rpg, wpRPG, Role Playing Game, games, ezRPG
Donate link: http://tagsolutions.tk/donate/
Requires at least: 3.6
Tested up to: 3.8
Stable tag: 1.0.5
WPRPG: 1.0.11
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

RPG Attack plugin made for the WPRPG Engine

== Description ==
## This plugin is still an on-going development. Issues are sure to arise trying to build an engine that lays foundation for other plugins to build off of. Please use the support forums for help developing.

The idea of this plugin is to demonstrate a simple plugin that hooks into WPRPG and adds RPG Attack abilities.

== Installation ==
1. Upload \"wprpg_attack\" folder to the \"/wp-content/plugins/\" directory.
1. Activate the plugin through the \"Plugins\" menu in WordPress.

== Frequently Asked Questions ==
= What is a Text-based RPG? =
Role Playing Game. Typically a Text-based RPG consists of a player vs player or player vs world game where you must preform actions to collect items, defeat enemies, and/or build armies. There\'s little to no graphics outside of the websites theme.


== Changelog ==
= 1.0.5 = 
- Fixed a class loader issue where the plugin was checking for wpRPG before it was even loaded.

= 1.0.4 =
- Updated to use Player Class from WPRPG 1.0.15 or use old method if upgrade hasn't happened.

= 1.0.1-1.0.3 =
- A number of fixes to the CP intergration. 
- There was a bug sending 1 (bool) instead of a integer value of the input amount
- After CP integration was fixed, I had my CP=>Gold and Gold=>CP mixed up. Oops.

= 1.0.0 = 
- Converted structure to represent wpRPG's directory structure.
- Fixed issues with Attack button not hooking correctly

= 0.0.1-0.10.1 =
- Numerous additions and deletions. Bugs out the wazoo.  
- Initial Revision