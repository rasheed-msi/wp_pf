=== Plugin Name ===
Contributors: Rasheed
Tags: security, passwords
Requires at least: 3.4
Tested up to: 3.9.1
Stable tag: 1.0.0
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

PF Encrypt Password switches WordPress's password hashes from MD5 to PF Current algorithm without affecting current user paswords, making it harder for them to be brute-forced if they are leaked.

== Description ==

WordPress uses phpass to store passwords. Because WordPress has to work everywere, it uses the portable version of phpass,
which uses MD5 to hash passwords. MD5 is not a very good hashing algorithm for passwords, because it's relatively fast.


**Note: this plugin requires PHP 5.3.0 or newer**

Be aware that if you use this plugin and then move to a host that does not support bcrypt, you will need to reset any user
account that you want to log in with.

== Installation ==

1. Upload the `pf-encrypt-password` directory to the `wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How do you change the hashes? =

Users' hashes are changed to bcrypt when they first login in after the plugin is activated. All of WordPress's built-in functions
will use bcrypt too, when intially creating an account, changing your password, or adding a password to a post.

= What happens if I deactivate the plugin? =

As long as you have bcrypt support (PHP 5.3.0 or newer) WordPress will happily continue checking passwords that are hashed using
bcrypt. Everything should work fine. But any new passwords you hash (for a new account, or changing an existing account) will be 
made using MD5.

== Changelog ==

= 1.0.1 =
* Readme improvements

= 1.0.0 =
* Initial release
