Painless MVC
============

Version 1.0

_A lightweight, minimalist PHP MVC framework designed to separate work into models, views, and controllers, yet **not require a long learning curve**. It is ideal for working with teammates who are either new to MVC or already know another platform, and is especially useful if teammates know separate brands of MVCs._

FEATURES
--------

* Zero config -- copy to your server and you're ready to go
* Tidy separation of models, views, and controllers
* Extremely low learning curve to create controllers, models, and views
* Super easy file system layout
* Automatic URL routing
* Brainlessly easy URL routing to files
* Easily get base URL and base file path
* PLUGIN HOOKS!
* Helper functions for $_GET and $_POST
* Helper function for URL redirection
* NO long file paths -- some frameworks require that you put files several folders deep, and that's time-consuming and
cumbersome
* NO requirement to build a URL router -- some frameworks require you to edit a URL router file before you can use it
* NO requirement to build a front controller -- it's built for you, and you can extend it
* NO requirement to inherit classes or make your page controllers as class files -- that's less typing for you

DOES NOT INCLUDE
----------------

* Twitter Boostrap -- add that yourself
* jQuery -- add that yourself
* ORM or ActiveRecord -- use PDO and SQL instead, or add your favorite ORM or ActiveRecord engine
* Automatic database connection -- use the `config/config.php` and the `models/core.static.php` and build it yourself
* Security controls -- add that yourself, such as block XSS, directory views, etc.
* Helper functions for sessions -- add that yourself
* Helper functions for dates -- add that yourself
* Helper functions for Unicode and multi-byte strings -- add that yourself
* Helper functions for cookies -- add that yourself
* Helper functions for mail -- add that yourself
* Page templating engine -- use PHP Alternative Syntax or add your own templating engine in
* Scaffolding / automatic admin panels -- add that yourself
* Global untrapped error handler -- add that yourself with `set_error_handler()`
* Page and object caching -- add that yourself with `ob_*`, `serialize/unserialize` and file I/O functions

REQUIRES
--------

* PHP5
* .htaccess mod rewrite support (or rewrite into format that works with Nginx or other web server)

ZERO-CONFIG INSTALLATION
------------------------

1. Ensure your web server meets the requirements.
2. Copy these files to your web server.
3. Delete the README.md and other/put-other-stuff-here.txt files.
4. Connect to the site URL and run the demo.
5. With only about 30 minutes of time, read the very few lines of code behind the demo, starting in this order:

	_(In your first read-through of the code, please ignore anything dealing with plugins, and read about plugins below,
	later, as an advanced topic.)_

	* .htaccess
	* index.php
	* models/core.static.php
	* controllers/index.php
	* views/v-index.php
	* controllers/sample/sample-test.php
	* models/sample-model.static.php
	* views/sample/v-sample-test.php
	* controllers/hello-world.php
	* views/v-hello-world.php
	* controllers/sample/sample-test.php

6. Remove controllers/sample.
7. Remove models/sample-model.static.php.
8. Remove views/sample.
9. Remove views/v-hello-world.php.
10. Remove controllers/hello-world.php.
11. Revise controllers/index.php and views/v-index.php as necessary.
12. Add code either in your front controller (/index.php) or Core class (models/core.static.php) in order to establish
a PDO database connection and create a global $PDO object that you can reference in your other model classes.
13. This system autoloads static classes, but you can manually load regular classes. All the classes should be in the
models folder.
14. Create more controllers as necessary in the controllers folder, and subdirectories are also allowed.
15. Create more views as necessary in the views folder, and subdirectories are also allowed. Note that view files must
begin with v- prefix (because then it's easier to not get them confused with controllers in text editors).

HOW IT WORKS
------------

_(In your first read-through of the code, please ignore anything dealing with plugins, and read about plugins below,
later, as an advanced topic.)_

1. A user types a URL in the browser.
2. The .htaccess loads any URL or directory that's physically not found on that path as /index.php (which is the front
controller).
3. If the URL contains /css/, /js/, /img/, it rewrites these with a /views prefix.
4. The front controller loads the config (config/config.php) configuration and uses an autoloader to load the framework 
boostrap class (models/core.static.php). 
5. The front controller sets the global variables `$BASE_PATH`, `$BASE_URL`, and `$VIEW_FILE`.
6. The front controller reads the URL and adds /controllers/ into the path, then calls this file with `require_once()`.
7. The page controller is called under controllers.
8. The autoloader handles calling models automatically out of the models folder.
9. The page controller optionally sets $view object variables for insertion into the view.
9. When and if the page controller calls `Core::showView()`, it duplicates the page controller path but switches 
/controllers/ with /views/, and adds a v- prefix in front of the filename.
10. The view is displayed. PHP Alternative Syntax is used to display the $view object variables.

PLUGINS
-------

_This is a slightly advanced topic._

Painless is easy and minimalist, but can be made robust by adding plugins. That's one of the unique traits of this
framework.

A sample plugin is included called SetCaps, and it's in the folder plugins/SetCaps. All it does is take view variables,
before they are displayed, and capitalizes them. If you grep the source code of the project, you'll see that we created
plugin hooks that look similar to this:

`extract(Plugins::hook('pre_view',get_defined_vars()));`

What this does is pass in all defined variables into a plugin handler, and then it passes back variables which will get
automatically executed. The 'pre_view' is just a hook name, and in this case means "before we show the view". The
PHP native function that gets all the variables in the current scope is `get_defined_vars()`. The PHP native function
that extracts those into the current scope is `extract()`. This is then passed through Plugins::hook, which does the
job of seeing if we have an active plugin that hooks this event, and calls that function with the PHP native function
`call_user_func()`.

Currently in the Painless framework, we have established these hooks, however, you can create more of your own:

* `pre_view` -- Is called before we load the view (template) file.
* `post_view` -- Is called after we load the view (template) file.
* `pre_redirect` -- Is called right before we redirect to another URL with `Core::doRedirect()`.
* `post_param` -- Is called during every `Core::getParam()` ($_GET read).
* `post_field` -- Is called during every `Core::getField()` ($_POST read).
* `pre_page_controller` -- Is called in the juncture between handoff from front controller to page controller.
* `pre_model` -- Is called with every call to load a Model.

How to create a hook:

A _hook_ is a place in your code where variables can be altered in either a function or global scope. You give them a 
name like so...

`extract(Plugins::hook('my_hook',get_defined_vars()));`

...where "my_hook" is its name. Once placed anywhere in your code, your plugins can now utilize that hook.

How to create a plugin:

1. Create a folder for your plugin under the plugins folder. For purposes here, let's call it MyPlugin.
2. Copy the index.php and plugin.php from the SetCaps plugin and then edit these files.
3. Start with plugins/MyPlugin/plugin.php. Change the name to "My Plugin". Change the description to "This is a demonstration." Change
the "pre_view" callback to one of the ones you want above, or come up with your own hook. As long as your code has a
hook somewhere such as in a controller, view, or model, your plugin can utilize it. Change "SetCaps::setViewVars" to
whatever you want the class name and class method (function) to be, such as "MyPlugin::doSample". The 9999 can be
changed and is only there so that plugins can execute before other plugins. A number 1, for instance, would run before
other plugins could run.
4. Change plugins/MyPlugin/index.php. So, "SetCaps" then becomes "MyPlugin", and the class method (function) you see in there is changed
from "setViewVars" to "doSample". As a demonstration, you can remove the SetCaps function code inside and then add this 
code instead "`print_r($asArgs);`" and it will show you what variables are available, but you can also use the `global` keyword to access other variables
in the global scope. What we like to do is that short test first, and then when we know what's possible, we can alter
those variables and remove that `print_r()` line.
5. Now, in order for this plugin to be enabled, you need to edit plugins/plugins.php and add "MyPlugin" into that string
array. (Smart programmers can build a GUI to read/write this file so that you can enable or disable plugins with an admin
interface.)
6. At that point, when your code executes, the front controller (/index.php) will read your plugin list to know which
plugins are enabled. When a hook is encountered, it will know which plugin scripts to execute for those hooks. Those
plugin scripts then alter the variables in that given scope or global scope.

So, our MyPlugin class (plugins/MyPlugin/index.php) would look like so:

```
class MyPlugin {

public static function doSample($asArgs){

	// debug
	print_r($asArgs);

	return $asArgs;

}

} // end class
```


