<?php
if(!defined('ABSPATH'))die('');
/*
 * changes 1.25.2015.
 * fonts options
 */
global $wp_my_sogrid_fonts;
global $wp_my_sogrid_font_weight;
$wp_my_sogrid_font_weight=array(
		'100'=>'100',
		'200'=>'200',
		'300'=>'300',
		'400'=>'400',
		'500'=>'500',
		'600'=>'600',
		'700'=>'700',
		'800'=>'800',
		'900'=>'900',
);
global $wp_my_sogrid_font_styles;
$wp_my_sogrid_font_styles=array(
		'normal'=>__("Normal","wp_my_sogrid_domain"),
		'italic'=>__("Italic","wp_my_sogrid_domain"),
		'oblique'=>__("Oblique","wp_my_sogrid_domain"),
);
global $wp_my_sogrid_font_sizes_array;
$wp_my_sogrid_font_sizes_array=array(
		'start'=>6,
		'end'=>50,
		'step'=>2
);
global $wp_my_sogrid_search_ids;
$wp_my_sogrid_search_ids=array(
	'google','facebook','twitter'
);
/*
 * end changes
 */
/*
*changes 1.20.2015. added order options
*/	
global $wp_my_sogrid_genereal_sogrid_options;
$wp_my_sogrid_genereal_sogrid_options=array(
		/*
		 * changes 1.21.2015.
		 */
	'dynamic'=>array(
		'type'=>'on_off',
		'title'=>__("Dynamic Grid","wp_my_sogrid_domain"),
		'tooltip'=>__("Plugin will order posts by default pattern, All Box are enabled Title ,Text etc. you can't change that options. ","wp_my_sogrid_domain"),
		'default'=>1
	),	
		/*
		 * end changes
		 */
	'order'=>array(
		'type'=>'on_off',
		'title'=>__("Random Order","wp_my_sogrid_domain"),
		'tooltip'=>__("Off value order by date, On value random order","wp_my_sogrid_domain"),
		'default'=>0
	),
		/*
		 * changes 2.11.2015
		 */
	'dynamic_loading'=>array(
			'type'=>'on_off',
			'title'=>__("Dynamic loading","wp_my_sogrid_domain"),
			'tooltip'=>__("Dynamic loading allows to load posts when users scrolls to the bottom of a page.","wp_my_sogrid_domain"),
			'default'=>1
	),
	'dynamic_loading_animation'=>array(
				'type'=>'on_off',
				'title'=>__("Animate  SoGrid on scroll","wp_my_sogrid_domain"),
				'tooltip'=>__("When user scrolls down animate elements.","wp_my_sogrid_domain"),
				'default'=>1
		),
	'enable_scroll_images'=>array(
				'type'=>'on_off',
				'title'=>__("Enable scroll image when they are higher than container","wp_my_sogrid_domain"),
				'tooltip'=>__("Enable scroll images.","wp_my_sogrid_domain"),
				'default'=>1
		),
		/*
		 * end
		 */
);

/*
 * end
 */
global $wp_my_sogrid_genereal_plugin_options;
$wp_my_sogrid_genereal_plugin_options=array(
	'twitter_id'=>array(
		'styles'=>array(
			'width'=>'80%',	
		),	
		'type'=>'text',
		'title'=>__("Twitter Id","wp_my_sogrid_domain"),
		'tooltip'=>__("Your Twitter Id","wp_my_sogrid_domain"),
		'default'=>''	
	),
	'twitter_consumer_key'=>array(
		'styles'=>array(
			'width'=>'80%',
		),
		'type'=>'password',
		'title'=>__("Consumer Key","wp_my_sogrid_domain"),
		'tooltip'=>__("Your Twitter API Consumer key","wp_my_sogrid_domain"),
		'default'=>''
	),
	'twitter_consumer_secret'=>array(
		'styles'=>array(
			'width'=>'80%',
		),
		'type'=>'password',
		'title'=>__("Consumer Secret ","wp_my_sogrid_domain"),
		'tooltip'=>__("Your Twitter API Consumer secret key","wp_my_sogrid_domain"),
		'default'=>''
	),
	'twitter_oauth_token'=>array(
		'styles'=>array(
			'width'=>'80%',
		),
		'type'=>'password',
		'title'=>__("Oauth API Access Token","wp_my_sogrid_domain"),
		'tooltip'=>__("Your Twitter API Access Token","wp_my_sogrid_domain"),
		'default'=>''
	),
	'twitter_oauth_secret_token'=>array(
		'styles'=>array(
			'width'=>'80%',
		),
		'type'=>'password',
		'title'=>__("OAuth API Secret Access Token","wp_my_sogrid_domain"),
		'tooltip'=>__("Your Twitter API Secret Access Token","wp_my_sogrid_domain"),
		'default'=>''
	),
	
		
		'facebook_app_key'=>array(
				'styles'=>array(
						'width'=>'80%',
				),
				'type'=>'password',
				'title'=>__("Facebook App Key","wp_my_sogrid_domain"),
				'tooltip'=>__("This key will be used to call GRAPH api.","wp_my_sogrid_domain"),
				'default'=>''
		),
		'facebook_app_id'=>array(
				'styles'=>array(
						'width'=>'80%',
				),
				'type'=>'text',
				'title'=>__("Facebook App Id","wp_my_sogrid_domain"),
				'tooltip'=>__("This ID will be used to call GRAPH api.","wp_my_sogrid_domain"),
				'default'=>''
		),
		
	/**Dont use facebook api use feed
	 *
	*
	'facebook_app_id'=>array(
				'styles'=>array(
						'width'=>'80%',
				),
				'type'=>'text',
				'title'=>__("Facebook APP ID","wp_my_sogrid_domain"),
				'tooltip'=>__("Your Facebook APP ID","wp_my_sogrid_domain"),
				'default'=>''
	),
		'facebook_app_secret'=>array(
				'styles'=>array(
						'width'=>'80%',
				),
				'type'=>'password',
				'title'=>__("Facebook APP Secret Key","wp_my_sogrid_domain"),
				'tooltip'=>__("Your Facebook APP Secret Key","wp_my_sogrid_domain"),
				'default'=>''
		),
	*/	
		
);

global $wp_my_sogrid_general_options;
$wp_my_sogrid_general_options=array(
	'id'=>array(
		'type'=>'text',
		'title'=>__("Id","wp_my_sogrid_domain"),
		'default'=>'',
		'required'=>true
	),
	/*
	 * chnages 1.20.2015
	 */	
	'max'=>array(
		'type'=>'text',
		'title'=>__("Maximum Results","wp_my_sogrid_domain"),
		'default'=>'100',
		'required'=>true
	),	
	'limit'=>array(
		'type'=>'on_off',
		'title'=>__("Limit results by date","wp_my_sogrid_domain"),
		'default'=>0,
			
	),	
	'limit_num'=>array(
		'type'=>'text',
		'title'=>__("Limit days","wp_my_sogrid_domain"),
		'default'=>'10',
		'required'=>true
	),
	/*
	 * end chnages
	 */
	'intro'=>array(
		'type'=>'on_off',
		'title'=>__("Enable Intro (Image,Date)","wp_my_sogrid_domain"),
		'default'=>1,
		
	
	),
	'thumb'=>array(
		'type'=>'on_off',
		'title'=>__("Enable Thumb","wp_my_sogrid_domain"),
		'default'=>1
	),
	'title'=>array(
		'type'=>'on_off',
		'title'=>__("Enable Intro Title","wp_my_sogrid_domain"),
		'default'=>1
	),
	'text'=>array(
		'type'=>'on_off',
		'title'=>__("Enable Text","wp_my_sogrid_domain"),
		'default'=>1
	),
	'share'=>array(
		'type'=>'on_off',
		'title'=>__("Share","wp_my_sogrid_domain"),
		'default'=>1
	)
	
);

global $wp_my_sogrid_help_topics;
