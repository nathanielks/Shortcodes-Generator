# Shortcodes Generator #
Contributors: fightthecurrent   
Donate link: http://bit.ly/QhXuBc    
Tags: shortcodes, theme, generator    
Requires at least: 3.4.0   
Tested up to: 3.4.2    
Stable tag: 1.0    

A plugin to generate shortcodes and a corresponding button in the WordPress visual editor. Wicked!

## Description ##

Adding shortcodes to the Visual Editor can be a pain in the buttocks. Not anymore.
Now, you can add shortcodes and a button to the editor as simply as using an 
array and this plugin. [How neat is that?](http://bit.ly/Pvj4ie)

To check out more details on how to use the plugin, please visit [the plugin site.](http://fightthecurrent.org/plugins/shortcodes-generator)

## Credits ##

Some icons by [Yusuke Kamiyamane](http://p.yusukekamiyamane.com/). All rights reserved. Licensed under a [Creative Commons Attribution 3.0 License](href="http://creativecommons.org/licenses/by/3.0/).

## Installation ##

1. Upload `shortcodes-generator.zip` to the `/wp-content/plugins/` directory and unzip it
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Drag the `shortcodes` folder inside `shortcodes-generator` to the root of your theme
4. Create your shortcode via arrays. For more details, check out the FAQ, or visit [the plugin site.](http://fightthecurrent.org/plugins/shortcodes-generator)
5. That's it! Enjoy!

## Frequently Asked Questions ##

### So how do I add the shortcodes, anyway? ###

Well, after you've moved the `shortcodes` folder to your theme, open up
`shortcodes/array.php`. The simplest one you can make would look like this:

	$shortcodes['button'] = array(
		'shortcode'	=> 'button'
	);

That would create a shortcode that looks like this: `[button]`

Then, for every shortcode you wish to add, just repeat 
`$shortcodes['shortcode']` with the details filled in!

The template is as follows:

	$shortcodes['name'] = array(
		'shortcode'	=> '',
		'function' => '',
		'tag'	=> '',
		'selectable' => 0
		'params' => array(
			'name', 'name2'
		),
		'children'=> array(
			'child' => array(
				'shortcode' => '',
				'function' => '',
				'tag'	=> '',
				'selectable' => 0
				'params' => array(
				      'name', 'name2'
				),
			),
		)
	);

### This seems too simple. What if I want more control? ###

To use a specific function along with the short code, all you have to do is pass
`'function' => 'function_name'` as one of the paramaters for the array. Here's
an example:

	$shortcodes['button'] = array(  
		'shortcode' => 'button',  
		'function' => 'cur_button_shortcode',  
		'selectable' => 1  
		'params' => array(  
			'class', 'color'  
		),  
	);  

The shortcode will now use `cur_button_shortcode` as the function to run for
the shortcode. The shortcode will look like this:

	[button class="" color""][/button]

If you want to define some default parameters, just change params to this:

	'params' => array(
		'class' => 'small',
		'color' => 'green'
	)

It will produce this output:

	[button class="small" color"green"][/button]

Want to be able to select text and have the shortcode wrap around it? Just pass
`'selectable' => 1`.

I will eventually add a ThickBox dialogue so that people can have default
selections picked out for them, and actually have more of a UI. But this will
do for now.

## Changelog ##

### 1.0 ###
* Initial Release
