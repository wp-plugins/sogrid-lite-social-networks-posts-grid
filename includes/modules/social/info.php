<?php
if(!defined('ABSPATH'))die('');
global $wp_my_sogrid_social_vkontakte_version;
$wp_my_sogrid_social_vkontakte_version='5.33';
global $wp_my_sogrid_social_modules;
$wp_my_sogrid_social_modules=array(
	
	'facebook'=>array(
		'dir'=>'facebook',
		'title'=>__("Facebook","wp_my_sogrid_domain"),
		'desc'=>__("Getting facebook page postx.","wp_my_sogrid_domain")
					
	),
	'twitter'=>array(
		'dir'=>'twitter',
		'title'=>__("Twitter","wp_my_sogrid_domain"),
		'desc'=>__("Getting twitter items by user,list or search.","wp_my_sogrid_domain")
			
	),
	
);
