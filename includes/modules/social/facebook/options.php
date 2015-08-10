<?php
/*
 * changes 1.20.2015.
 * options_tooltipips
 * options
 * tooltips
 */
if(!defined('ABSPATH'))die('');
global $wp_my_sogrid_facebook_options;
global $wp_my_sogrid_facebook_options_tooltips;
global $wp_my_sogrid_facebook_item_options;
$wp_my_sogrid_facebook_item_options=array(
		/*'api_key'=>array(
				'type'=>'password',
				'title'=>__("Google Plus API Key","wp_my_sogrid_domain"),
				'default'=>'',
				'required'=>true,
				'tooltip'=>__("Google Plus API Key get from http://console.goolge.com/","wp_my_sogrid_domain")
		)*/
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
			'default'=>'#8a99b8'
		),
		'background_color'=>array(
				'type'=>'color_picker',
				'title'=>__("Box Background Color","wp_my_sogrid_domain"),
				'default'=>'#3c5993'
		),
		'background_share_color'=>array(
				'type'=>'color_picker',
				'title'=>__("Share box background Color","wp_my_sogrid_domain"),
				'default'=>"#3c5993"
		),
		'icon_color'=>array(
				'type'=>'color_picker',
				'title'=>__("Icon Color","wp_my_sogrid_domain"),
				'default'=>"#FFFFFF"
		),
		'date_color'=>array(
				'type'=>'color_picker',
				'title'=>__("Date Color","wp_my_sogrid_domain"),
				'default'=>"#7988a7"
		),
		'title_font'=>array(
				'type'=>'font',
				'title'=>__("Title Font","wp_my_sogrid_domain"),
				'default'=>array(
						'font'=>'Open+Sans',
						'font_size'=>'14px',
						'font_style'=>'normal',
						'font_weight'=>'400'
				)
		),
		'title_color'=>array(
				'type'=>'color_picker',
				'title'=>__("Title Color","wp_my_sogrid_domain"),
				'default'=>"#FFFFFF"
		),
		
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
				'default'=>"#FFFFFF"
		),
		'share_color'=>array(
				'type'=>'color_picker',
				'title'=>__("Share Link Color","wp_my_sogrid_domain"),
				'default'=>"#FFFFFF"
		),
		'share_hover_color'=>array(
				'type'=>'color_picker',
				'title'=>__("Share Hover Color","wp_my_sogrid_domain"),
				'default'=>"#FFFFFF"
		),
		'share_line_color'=>array(
				'type'=>'color_picker',
				'title'=>__("Share Line Color","wp_my_sogrid_domain"),
				'default'=>'#436aa5',
		),	
		/*'share_font'=>array(
				'type'=>'select',
				'default'=>'Open+Sans',
				'values'=>$wp_my_sogrid_fonts
		),*/	
		/*'share_font'=>array(
				'type'=>'font',
				'title'=>__("Share Links Font","wp_my_sogrid_domain"),
				'default'=>array(
						'font'=>'Open+Sans',
						'font_size'=>'14px',
						'font_style'=>'normal',
						'font_weight'=>'400'
				)
		),*/
		'link_color'=>array(
				'type'=>'color_picker',
				'title'=>__("Link Color","wp_my_sogrid_domain"),
				'default'=>"#000000"
		),
		'link_hover_color'=>array(
				'type'=>'color_picker',
				'title'=>__("Link Hover Color","wp_my_sogrid_domain"),
				'default'=>"#000000"
		),
		
		/*
		 * end changes
		*/
);
$wp_my_sogrid_facebook_options=array(
		'id',
		'max',
		'limit',
		'limit_num',
		'intro',
		'thumb',
		'title',
		'text',
		'share'
);
$wp_my_sogrid_facebook_options_tooltips=array(
		'id'=>__("Enter Facebook Page ID ,or / and gallery ID.Enter multiple IDs separated by commas.","wp_my_sogrid_domain"),
		'max'=>__("Call Api with max results parametar, max results for facebook is 250.","wp_my_sogrid_domain"),	
		'limit'=>__("Limit results to last n days.","wp_my_sogrid_domain"),
		'limit_num'=>__("Limit results to items published before x days.","wp_my_sogrid_domain"),
		'intro'=>__("Enable Facebook image and date ","wp_my_sogrid_domain"),
		'thumb'=>__("Show Facebook thumb","wp_my_sogrid_domain"),
		'title'=>__("Show Facenook post title","wp_my_sogrid_domain"),
		'text'=>__("Show Facebook plus text","wp_my_sogrid_domain"),
		'share'=>__("Share item on twitter,facebook,google+","wp_my_sogrid_domain")

);
