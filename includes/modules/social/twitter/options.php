<?php
if(!defined('ABSPATH'))die('');
global $wp_my_sogrid_twitter_options;
global $wp_my_sogrid_twitter_options_tooltips;
global $wp_my_sogrid_twitter_item_options;
$wp_my_sogrid_twitter_item_options=array(
		'include_rts'=>array(
				'type'=>'on_off',
				'title'=>__("Include retweets","wp_my_sogrid_domain"),
				'default'=>0,
				'tooltip'=>__("If you want to include rewteets in SoGrid check this option","wp_my_sogrid_domain")
		),
		'include_replies'=>array(
				'type'=>'on_off',
				'title'=>__("Include replies","wp_my_sogrid_domain"),
				'default'=>0,
				'tooltip'=>__("If you want to include replies check this option","wp_my_sogrid_domain")
		),
		/*
		 * changes 1.23.2015.
		*/
		'enable_border'=>array(
				'type'=>'on_off',
				'title'=>__("Enable Border","wp_my_sogrid_domain"),
				'default'=>1
		),
		'border_color'=>array(
				'type'=>'color_picker',
				'title'=>__("Box Border Color","wp_my_sogrid_domain"),
				'default'=>' #f6f6f6'
		),
		'background_color'=>array(
				'type'=>'color_picker',
				'title'=>__("Box Background Color","wp_my_sogrid_domain"),
				'default'=>'#FFFFFF'
		),
		'background_share_color'=>array(
				'type'=>'color_picker',
				'title'=>__("Share box background Color","wp_my_sogrid_domain"),
				'default'=>"#FFFFFF"
		),
		'icon_color'=>array(
				'type'=>'color_picker',
				'title'=>__("Icon Color","wp_my_sogrid_domain"),
				'default'=>"#41abdd"
		),
		'date_color'=>array(
				'type'=>'color_picker',
				'title'=>__("Date Color","wp_my_sogrid_domain"),
				'default'=>"#b7b7b7"
		),
		/*'title_font'=>array(
				'type'=>'font',
				'title'=>__("Title Font","wp_my_sogrid_domain"),
				'default'=>array(
						'font'=>'Open+Sans',
						'font_size'=>'16px',
						'font_style'=>'normal',
						'font_weight'=>'400'
				)
		),
		'title_color'=>array(
				'type'=>'color_picker',
				'title'=>__("Title Color","wp_my_sogrid_domain"),
				'default'=>"#000000"
		),*/
		
		'text_font'=>array(
				'type'=>'font',
				'title'=>__("Text Font","wp_my_sogrid_domain"),
				'default'=>array(
						'font'=>'Open+Sans',
						'font_size'=>'14px',
						'font_style'=>'normal',
						'font_weight'=>'400'
				)
		),
		'text_color'=>array(
				'type'=>'color_picker',
				'title'=>__("Text Color","wp_my_sogrid_domain"),
				'default'=>"#000000"
		),
		'share_color'=>array(
				'type'=>'color_picker',
				'title'=>__("Share Link Color","wp_my_sogrid_domain"),
				'default'=>"#ced7de"
		),
		'share_hover_color'=>array(
				'type'=>'color_picker',
				'title'=>__("Share Hover Color","wp_my_sogrid_domain"),
				'default'=>"#ced7de"
		),
		'share_number_color'=>array(
				'type'=>'color_picker',
				'title'=>__("Share Numbers Color","wp_my_sogrid_domain"),
				
				'default'=>'#d5ccc4'
		),
		'share_icon_color'=>array(
				'type'=>'color_picker',
				'title'=>__("Share Icon Color","wp_my_sogrid_domain"),
				'default'=>'#ced7de',
		),
		
		/*'share_button_background_color'=>array(
				'type'=>'color_picker',
				'title'=>__("Share Button Background Color","wp_my_sogrid_domain"),
				'default'=>"#000000"
		),
		'share_button_border_color'=>array(
				'type'=>'color_picker',
				'title'=>__("Share Button Border Color","wp_my_sogrid_domain"),
				'default'=>"#d9d9d9"
		),*/
		'share_line_color'=>array(
				'type'=>'color_picker',
				'title'=>__("Share Line Color","wp_my_sogrid_domain"),
				'default'=>'#eeeeee',
		),
		'link_color'=>array(
				'type'=>'color_picker',
				'title'=>__("Link Color","wp_my_sogrid_domain"),
				'default'=>"#41abdd"
		),
		'link_hover_color'=>array(
				'type'=>'color_picker',
				'title'=>__("Link Hover Color","wp_my_sogrid_domain"),
				'default'=>"#41abdd"
		),		
		
		/*
		 * end changes
		*/
		
);
$wp_my_sogrid_twitter_options=array(
		'id',
		'max',
		'limit',
		'limit_num',
		'intro',
		'thumb',
		'text',
		'share'
);
$wp_my_sogrid_twitter_options_tooltips=array(
		'id'=>__("Enter a twitter username without @ or enter /with list id,or enter # with search term.","wp_my_sogrid_domain"),
		'max'=>__("Call Api with max results parametar","wp_my_sogrid_domain"),
		'limit'=>__("Limit results to last n days.","wp_my_sogrid_domain"),
		'limit_num'=>__("Limit results to items published before x days.","wp_my_sogrid_domain"),
		'intro'=>__("Enable twitter image and date","wp_my_sogrid_domain"),
		'thumb'=>__("Show Twitter thumb","wp_my_sogrid_domain"),
		'text'=>__("Show Twitter text","wp_my_sogrid_domain"),
		'share'=>__("Share item on twitter,facebook,google+","wp_my_sogrid_domain")
);