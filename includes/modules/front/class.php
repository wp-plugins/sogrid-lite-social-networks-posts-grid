<?php
if(!defined('ABSPATH'))die('');
if(!class_exists('Class_Wp_My_SoGrid_Front_View')){
	class Class_Wp_My_SoGrid_Front_View{
		private $id;
		private $sogrid_options;
		private $added_scripts;
		private $is_sogrid=false;
		private $google;
		private $twitter;
		private $facebook;
		private $youtube;
		private $instagram;
		private $matches;
		private $has_facebook_sdk=false;
		private $is_included_pinterest;
		private $pinterest;
		private $flickr;
		private $dribbble;
		private $tumblr;
		private $vkontakte;
		private $api_errors=array();
		/*
		 * changes 1.27.2015.
		 */
		private $is_included_facebook_js=false;
		/*
		 * end changes
		 */
		function Class_Wp_My_SoGrid_Front_View($options=array()){
			if(!empty($options)){
				foreach($options as $k=>$v){
					$this->$k=$v;
				}
			}
			
		}
		/**
		 * Init class
		 */
		function init(){
			add_action('wp',array(&$this,'is_sogrid'));
			add_action('wp_enqueue_scripts',array(&$this,'scripts'));
			add_action('wp_head',array(&$this,'wp_head'));
			add_shortcode('sogrid',array(&$this,'shortcode') );
		}
		/**
		 * Generate ajax more;
		 * @param unknown $options
		 * @param unknown $array
		 * @param unknown $poc
		 * @param unknown $count
		 */
		function generate_ajax_more($id,$options,$array,$poc,$count,$columns=3){
			global $my_wp_sogrid_debug;
			global $my_wp_sogrid_debug_data;
			$c=$poc;
			$end=$poc+$count;
			$my_so_grid_options=$options;
			$networks=$options['active_social'];
			if($my_wp_sogrid_debug){
				$my_wp_sogrid_debug_data['ajax_more']['network']=$networks;
				$my_wp_sogrid_debug_data['ajax_more']['poc']=$poc;
				$my_wp_sogrid_debug_data['ajax_more']['count']=$count;
			}
			/*if($my_wp_sogrid_debug){
				$my_wp_sogrid_debug_data['ajax_more']['array']=$array;
			}*/
			global $share_cache_12345;
			wp_my_sogrid_get_cached_share($id);
			$template_dir=MY_WP_SOGRID_MODULES_DIRNAME.'front/views/';
			$items='';
			$total=count($array);
			if($total<$end)$end=$total;
			$ids_array=array();
			for($i=$poc;$i<$end;$i++){
				$my_li_id='my_li_id_'.$id.'_'.$i;
				$ids_array[]=$my_li_id;
				$my_data_show=$i;
				if(!empty($array[$i])){
				$obj=$array[$i];
				/*if($my_wp_sogrid_debug){
					$my_wp_sogrid_debug_data['ajax_more']['obj'][]=$obj;
				}*/
				$social_type=$obj->getType();
				$file=$template_dir.$social_type.'.php';
				$my_id_1234=$obj->getProperty('id');
				$my_ajax_columns=$columns;
				ob_start();
				if(file_exists($file)){
					require $file;
				}
				$new_items_122345=ob_get_clean();
				if($my_wp_sogrid_debug){
					$my_wp_sogrid_debug_data['ajax_mores']['obj'][]=array(
							'i'=>$i,
							'id'=>$my_id_1234,
							'type'=>$social_type,
							'file'=>$file,
							'poc'=>$poc,
							'end'=>	$end,
							'items'=>$items,
							'new_item'=>$new_items_122345
					);
				}
				$items.=$new_items_122345;
				}
				
			}
			return array('items'=>$items,'ids'=>$ids_array);
		}
		/**
		 * 
		 */
		function get_post($network,$post_id){
			$this->instantiate_service($network);
			switch($network){
				case 'google':
					$obj=$this->google->get_post($post_id);
				break;
				case 'facebook':
					$obj=$this->facebook->get_likes($post_id);
				break;
				case 'twitter':
					$obj=$this->twitter->get_post($post_id);	
				break;
				case 'youtube':
					$obj=$this->youtube->get_post($post_id);
				break;			
				default:
					$obj=false;
				break;		
			}
			return $obj;
		}
		/**
		 * Shortcode
		 * @param unknown $attrs
		 * @return string
		 */
		function shortcode($attrs){
			extract($attrs);
			$is_exists=wp_my_sogrid_is_exist_object($id);
			$ret_html='';
			if($is_exists){
				
				$this->id=$id;
				$this->sogrid_options=wp_my_sogrid_get_object_and_meta($id);
				$networks=$this->sogrid_options['active_social'];
				//print_r($networks);
				/**
				 * Loadf object cache
				 */
				foreach($networks as $k=>$service){
					$file=MY_WP_SOGRID_SOCIAL_MODULES_DIRNAME.$service.'/class.php';
					if(file_exists($file)){
						//echo $file;
						require_once $file;
					}
				}
				$my_preview_12_12=@$_GET['my_preview_sogrid'];
				$has_cache=false;
				$do_cache=true;
				
				$do_cache=wp_my_sogrid_do_cache_request($id);
				$cache=wp_my_sogrid_get_cached_requests($id,true);	
				/*
				 * changes_1_16_2015
				 */			
				//$do_cache=false;
				/*
				 * end
				 */
				//echo 'Do cache ';
				//var_dump($do_cache);
				//echo '<br/>';
				//echo 'Cache error';var_dump($cache['error']);
				//echo '<br/>';
				//echo 'curent time';
				//echo date('Y/m/d H:i:s',time());
				//echo '<br/>';
				//echo 'cache lifetime';
				
				$lifetime=$cache['lifetime'];
				//echo date('Y/m/d H:i:s',$lifetime);
				//echo '<br/>';
				
				
				if($cache!==false){
					if($cache['error']==1){
						//$do_cache=true;
					}else{
						//$do_cache=false;
						$has_cache=true;
					}
				}else {
					$cache_html=$cache['html'];
					//global $share_cache_12345;
					//wp_my_sogrid_get_cached_share($this->id);
					//print_r($share_cache_12345);
					
				}
				/*if($my_preview_12_12){
					$has_cache=false;
					$do_cache=true;
				}*/
				//echo 'Has cache ';
				//var_dump($has_cache);
				//echo '<br/>';
				//$do_cache=true;
				//$has_cache=false;
				//$has_cache=false;
				//$do_cache=true;
				if(!$has_cache){
					/*
					 * chnages 1.20.2015.
					 */
					//echo "We dont have cache call api for request";
					/*
					 * 
					 */
					set_time_limit(300);
					if(!empty($networks)){
						$responses=array();
						foreach($networks as $k=>$v){
							$file=MY_WP_SOGRID_SOCIAL_MODULES_DIRNAME.$v.'/class.php';
							if(file_exists($file)){
								require_once $file;
								if($v=='google'){
									$options=array();
									$ids=$this->sogrid_options[$v]['id'];
									if(strpos($ids,",")!==false){
										$arr=explode(",",$ids);
									}else $arr=array($ids);
									$options['ids']=$arr;
									$options['api_key']=$this->sogrid_options[$v]['api_key'];
									$options['sogrid_id']=$id;
									/*
									 * changes 1.20.2015. call api with max num
									 */
									$options['max_num']=$this->sogrid_options[$v]['max'];
									/*
									 * end changes
									 */
									$this->google=new Class_My_Wp_SoGrid_Google_Plus($options);
									$this->google->call_api();
									$ret=$this->google->get_data();
									//$this->google->format_results();
									foreach($ret as $k=>$v){
										$responses[]=$v;
									}
								}
								else {
									$class=$this->instantiate_service($v);
									if($class){
										if($v=='vkontakte'){
											$this->vkontakte->call_api();
											$ret=$this->vkontakte->get_data();
											foreach($ret as $k=>$v){
												$responses[]=$v;
											}
										}
										else if($v=='tumblr'){
											$this->tumblr->call_api();
											$ret=$this->tumblr->get_data();
											foreach($ret as $k=>$v){
												$responses[]=$v;
											}
										}
										else if($v=='dribbble'){
											$this->dribbble->call_api();
											$ret=$this->dribbble->get_data();
											foreach($ret as $k=>$v){
												$responses[]=$v;
											}
										}
										else if($v=='flickr'){
											$this->flickr->call_api();
											$ret=$this->flickr->get_data();
											foreach($ret as $k=>$v){
												$responses[]=$v;
											}
										}
										else if($v=='twitter'){
											$this->twitter->call_api();
											$ret=$this->twitter->get_data();
											foreach($ret as $k=>$v){
												$responses[]=$v;
											}
										}else if($v=='facebook'){
											/*
											 * changes_1_16_2015
											 */
											//echo 'Call facebook API';
											/*
											 * end
											 */
											$this->facebook->call_api();
											$ret=$this->facebook->get_data();
											foreach($ret as $k=>$v){
												$responses[]=$v;
											}
										}else if($v=='pinterest'){
											$this->pinterest->call_api();
											$ret=$this->pinterest->get_data();
											foreach($ret as $k=>$v){
												$responses[]=$v;
											}
										}else if($v=='youtube'){
											$this->youtube->call_api();
											$ret=$this->youtube->get_data();
											foreach($ret as $k=>$v){
												$responses[]=$v;
											}
										}else if($v=='instagram'){
											$this->instagram->call_api();
											$ret=$this->instagram->get_data();
											foreach($ret as $k=>$v){
												$responses[]=$v;
											}
										}
									}
									
								}
								
							}
							
							/**
							 * cache request
							 */
							/*if($do_cache){
								wp_my_sogrid_cache_request($id, $responses,array(),true);
							}*/
							
						}
						$my_api_errors_12=array();
						foreach($networks as $k=>$v){
							if($v=='facebook'){
								$my_api_errors_12[$v]=$this->facebook->api_errors;
							}else if($v=='twitter'){
								$my_api_errors_12[$v]=$this->twitter->api_errors;
								
							}else if($v=='pinterest'){
								$my_api_errors_12[$v]=$this->pinterest->api_errors;
							}else if($v=='google'){
								$my_api_errors_12[$v]=$this->google->api_errors;
							}else if($v=='youtube'){
								$my_api_errors_12[$v]=$this->youtube->api_errors;
							}else if($v=='instagram'){
								$my_api_errors_12[$v]=$this->instagram->api_errors;
							}else if($v=='flickr'){
								$my_api_errors_12[$v]=$this->flickr->api_errors;
							}else if($v=='dribble'){
								$my_api_errors_12[$v]=$this->dribbble->api_errors;
							}else if($v=='tumblr'){
								$my_api_errors_12[$v]=$this->tumblr->api_errors;
							}else if($v=='vkontakte'){
								$my_api_errors_12[$v]=$this->vkontakte->api_errors;
							}
						}
						$my_api_resonses=$responses;
					
					}
				}else {
					global $share_cache_12345;
					wp_my_sogrid_get_cached_share($this->id);
						
					$responses=$cache;
					$parsed_cache=$cache['parsed'];
					/*
					 * changes 1.20.2015.
					 */
					//echo "We have cache";
					/*
					 *end 
					 */
					
				}
				//print_r($my_api_resonses);
				/*
				 * changes_1_16_2015
				 */
				//wp_my_sogrid_debug_object("Facebook", $responses);
				/*
				 * end
				 */
				/**
				 * Get html
				 */
				$is_dynamic_loading=$this->sogrid_options['general_options']['dynamic_loading'];
				$is_dynamic_loading_animation=$this->sogrid_options['general_options']['dynamic_loading_animation'];
				
				//echo 'Dynamic loading '.$is_dynamic_loading;
				$networks_array=array();
				if(empty($cache_html)){
					$sogrid_arr=array();
					//if(empty($parsed_cache)){
					if((!empty($responses)&&(!$has_cache))){
						//if((empty($parsed_cache)){
						foreach($responses as $k=>$v){
							//if(($k=='html')||$k=='error')continue;
							if(!is_array($v))continue;
							if($v['error']==0){
								$service=$v['service'];
								if(empty($this->$service)){
									$can_i=$this->instantiate_service($service);
									if($can_i==false)continue;
								}
								if(!empty($this->$service)){
									$new_arr_1=$this->$service->format_results_by_data($v['data'],$v['id']);
									if(!empty($new_arr_1)){
										$sogrid_arr=array_merge($sogrid_arr,$new_arr_1);
										/*
										 * changes
										 */
										if(!isset($networks_array[$service])){
											$networks_array[$service]=array();
										}
										$networks_array[$service]=array_merge($networks_array[$service],$new_arr_1);
									}
								}
							
							}
						}}
						$cache_lifetime='';
						if($has_cache){
							$cache_lifetime=$cache['lifetime'];
						}
						//echo $cache_lifetime.' '.date('Y/m/d H:i:s');
						if($is_dynamic_loading){
							if(empty($parsed_cache)){
							//wp_my_sogrid_debug_object("Networks array", $networks_array['google']);
								$sogrid_arr_new=wp_my_sogrid_format_response($this->sogrid_options,$networks_array,$this->id,$networks);
							//wp_my_sogrid_cache_request($id, $responses,$sogrid_arr_new,true);
							//wp_my_sogrid_cache_request($id, array(),$sogrid_arr_new,true);
								if($do_cache){
									wp_my_sogrid_cache_request($id, $my_api_resonses, $sogrid_arr_new,true,$cache_lifetime);
								}	
								$parsed_cache=$sogrid_arr_new;
							}else {
								//echo 'We have parsed cache';
								//$parsed_cache=json_decode($parsed_cache['data']);
								$parsed_cache=$parsed_cache['data'];
							}
						}else {
							if(empty($parsed_cache)){
								//wp_my_sogrid_debug_object("Networks array", $networks_array['google']);
								$sogrid_arr_new=wp_my_sogrid_format_response($this->sogrid_options,$networks_array,$this->id,$networks);
									
								if($do_cache){
									wp_my_sogrid_cache_request($id, $my_api_resonses,$sogrid_arr_new,true,$cache_lifetime);
								}
								$parsed_cache=$sogrid_arr_new;
							}else {
								$parsed_cache=$parsed_cache['data'];
							}
						}
						
						//}
						/*
						 * changes_1_16_2015
						 */
						/*$do=10;
						$c=0;
						if(!empty($sogrid_arr)){
							foreach($sogrid_arr as $k=>$obj){
								if($c==$do)break;
								wp_my_sogrid_debug_object("Facebook object", $obj);
								$c++;
							}
						}
						$do=50;*/
						$do=-1;
						$c=0;
						ob_start();
						if(in_array('pinterest', $networks)&&!$this->is_included_pinterest){
							?>
							<?php /*
							<script type="text/javascript">
								(function(d){
    								var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
   									 p.type = 'text/javascript';
   									 p.async = true;
    									p.src = '//assets.pinterest.com/js/pinit.js';
    								f.parentNode.insertBefore(p, f);
							}(document));
							</script>
							*/ ?>
							<script defer="defer" src="//assets.pinterest.com/js/pinit.js" data-pin-build="parsePins"></script>
							<?php
							$this->is_included_pinterest=true; 
						}
						if(in_array('facebook', $networks)&&(!$this->is_included_facebook_js)){
							$this->is_included_facebook_js=true;
							//chnaages not init like button
							//changes remove facebook like
							
							?>
							<div id="fb-root"></div>
							<script>
							<?php if($this->has_facebook_sdk!==false){?>
							window.fbAsyncInit = function() {
						        FB.init({
						          appId      : '<?php echo $this->has_facebook_sdk?>',
						          xfbml      : true,
						          version    : 'v2.1'
						        });
						      };
							<?php }?> 
						
							(function(d, s, id) {
								var js, fjs = d.getElementsByTagName(s)[0];
								if (d.getElementById(id)) return;
								js = d.createElement(s); js.id = id;
								js.src = "//connect.facebook.net/en_US/sdk.js";
								fjs.parentNode.insertBefore(js, fjs);
							}(document, 'script', 'facebook-jssdk'));
							<?php 
							/*
							(function(d, s, id) {
  									var js, fjs = d.getElementsByTagName(s)[0];
  									if (d.getElementById(id)) return;
  									js = d.createElement(s); js.id = id;
  									js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
  									fjs.parentNode.insertBefore(js, fjs);
									}(document, 'script', 'facebook-jssdk'));
									*/ ?>
							</script>
							
							<?php
							
							
							
						}
						/*
						 * end
						 */
						global $my_sogrid_dynamic_content_num;
						
						$template_dir=MY_WP_SOGRID_MODULES_DIRNAME.'front/views/';
						$my_so_grid_options=$this->sogrid_options;
						if($is_dynamic_loading){
							if(!empty($parsed_cache)){
								
								$total=count($parsed_cache);
								if($this->sogrid_options['general_options']['dynamic']){
									//if($c==11)break;
									$pages=ceil($total/12);
								}
								else {
									$pages=ceil($total/$my_sogrid_dynamic_content_num);//break;
									
								}
							foreach($parsed_cache as $k=>$obj){
								$my_li_id='my_li_id_'.$id.'_'.$c;
								//$my_li_id='my_li_id_'.$c;
								$my_data_show=$c;
								$social_type=$obj->getType();
								$file=$template_dir.$social_type.'.php';
								if(file_exists($file)){
									require $file;
								}
								if($this->sogrid_options['general_options']['dynamic']){
									if($c==11)break;
								}
								else {
									if($c==($my_sogrid_dynamic_content_num-1))break;
								}
								$c++;
							}
							}else {
								$pages=0;
								$total=0;
							}
						}
						else {
							if(!empty($parsed_cache)){
						
							foreach($parsed_cache as $k=>$obj){
								if($c==$do)break;
								//wp_my_sogrid_debug_object("Val", $obj);
								//var_dump($obj);
								$social_type=$obj->getType();
								/*
								 * changes 1.20.2015.
								 * limit by last x days
								 */
								/*if(isset($my_limit_social[$social_type]))continue;
								
								$limit_num=$this->sogrid_options[$social_type]['limit'];
								if($limit_num){
									//echo 'Limit ';
									$published=$obj->getProperty('published');
									$time=time()-$this->sogrid_options[$social_type]['limit_num']*DAY_IN_SECONDS;
									//echo 'Published '.$published.' time '.$time;
									if($published<$time){
										//echo 'Limit to last ten days';
										$my_limit_social[$social_type]=1;
									}
								}*/
								/*
								 * end changes
								 */
								/*
								 * changes 1.22.2015.
								 */
								//$my_li_id='my_li_id_'.$c;
								$my_li_id='my_li_id_'.$id.'_'.$c;
								$my_data_show='';
								/*
								 * end changes
								 */
								$file=$template_dir.$social_type.'.php';
								if(file_exists($file)){
									require $file;
								}
								$c++;
							}
						}
						}
						$so_grid_inner_html=ob_get_clean();
						ob_start();
						$file=$template_dir.'sogrid.php';
						$sogrid_id=$this->id;
						require $file;
						$ret_html=ob_get_clean();
						return $ret_html;
						
				
					
				//}else return $cache_html;
			}
			
			}
			return $ret_html;
		}
		/**
		 * Create instance of service
		 * @param unknown $service
		 */
		private function instantiate_service($service){
			$v=$service;
			$file=MY_WP_SOGRID_SOCIAL_MODULES_DIRNAME.$service.'/class.php';
			if(file_exists($file)){
				//echo $file;
				require_once $file;
				switch($service){
					case 'vkontakte':
						$ids=$this->sogrid_options[$v]['id'];
						if(strpos($ids,",")!==false){
							$arr=explode(",",$ids);
						}else $arr=array($ids);
						$options['ids']=$arr;
						$options['sogrid_id']=$this->id;
						$vk_a=get_option("_my_sogrid_vkontakte_access_token");
						$options['api_key']=$vk_a;
						//$api_key=wp_my_sogrid_get_option_by_key('dribbble_access_token');
						//$options['api_key']=$api_key;
						/*
						 * changes max num
						*/
						$options['max_num']=$this->sogrid_options[$v]['max'];
						$this->vkontakte=new Class_My_Wp_SoGrid_Vkontakte($options);
						return true;
						
					break;	
					case 'tumblr':
						$ids=$this->sogrid_options[$v]['id'];
						if(strpos($ids,",")!==false){
							$arr=explode(",",$ids);
						}else $arr=array($ids);
						$options['ids']=$arr;
						$options['sogrid_id']=$this->id;
						//$api_key=wp_my_sogrid_get_option_by_key('dribbble_access_token');
						//$options['api_key']=$api_key;
						/*
						 * changes max num
						*/
						$options['max_num']=$this->sogrid_options[$v]['max'];
						$this->tumblr=new Class_My_Wp_SoGrid_Tumblr($options);
						return true;
						
					break;	
					case 'dribbble':
						$ids=$this->sogrid_options[$v]['id'];
						if(strpos($ids,",")!==false){
							$arr=explode(",",$ids);
						}else $arr=array($ids);
						$options['ids']=$arr;
						$options['sogrid_id']=$this->id;
						$api_key=wp_my_sogrid_get_option_by_key('dribbble_access_token');
						$options['api_key']=$api_key;
						/*
						 * changes max num
						*/
						$options['max_num']=$this->sogrid_options[$v]['max'];
						$this->dribbble=new Class_My_Wp_SoGrid_Dribbble($options);
						return true;	
					break;	
					case 'flickr':
						$options=array();
						$ids=$this->sogrid_options[$v]['id'];
						if(strpos($ids,",")!==false){
							$arr=explode(",",$ids);
						}else $arr=array($ids);
						$options['ids']=$arr;
						$api_key=wp_my_sogrid_get_option_by_key('flickr_api_key');
						$options['api_key']=$api_key;	
						$options['sogrid_id']=$this->id;
						
						/*
						 * changes max num
						*/
						$options['max_num']=$this->sogrid_options[$v]['max'];
						$this->flickr=new Class_My_Wp_SoGrid_Flickr($options);
						return true;
					break;	
					case 'instagram':
						$options=array();
						$ids=$this->sogrid_options[$v]['id'];
						if(strpos($ids,",")!==false){
							$arr=explode(",",$ids);
						}else $arr=array($ids);
						$options['ids']=$arr;
						$app_key=wp_my_sogrid_get_option_by_key('instagram_client_secret');
						$app_id=wp_my_sogrid_get_option_by_key('instagram_client_id');
						$option_name='wp_my_sogrid_instagram_access_token_12_12';
						//update_option($option_name, '1990812293.b81ee9e.92fcd65098b24303a5128cdff143a1df');
						$token=get_option($option_name);
						if(empty($token))return false;
						$options['access_token']=$token;
						$options['api_key']=$app_key;
						$options['client_id']=$app_id;
						$options['sogrid_id']=$this->id;
						
						/*
						 * changes max num
						*/
						$options['max_num']=$this->sogrid_options[$v]['max'];
						//print_r($options);
						/*
						 * end changes
						*/
						$this->instagram=new Class_My_Wp_SoGrid_Instagram($options);
						return true;
					break;	
					case 'pinterest':
						$options=array();
						$ids=$this->sogrid_options[$v]['id'];
						if(strpos($ids,",")!==false){
							$arr=explode(",",$ids);
						}else $arr=array($ids);
						$options['ids']=$arr;
						$options['sogrid_id']=$this->id;
						$options['max_num']=$this->sogrid_options[$v]['max'];
						//print_r($options);
						/*
						 * end changes
						*/
						$this->pinterest=new Class_My_Wp_SoGrid_Pinterest($options);
						return true;
					case 'youtube':
						$options=array();
						$ids=$this->sogrid_options[$v]['id'];
						if(strpos($ids,",")!==false){
							$arr=explode(",",$ids);
						}else $arr=array($ids);
						$options['ids']=$arr;
						$options['api_key']=$this->sogrid_options[$v]['api_key'];
						$options['sogrid_id']=$this->id;
						/*
						 * changes max num
						*/
						$options['max_num']=$this->sogrid_options[$v]['max'];
						/*
						 * end changes
						*/
						$this->youtube=new Class_My_Wp_SoGrid_YouTube($options);
							
						return true;
						
					break;	
					case 'google':
						$options=array();
						$ids=$this->sogrid_options[$v]['id'];
						if(strpos($ids,",")!==false){
							$arr=explode(",",$ids);
						}else $arr=array($ids);
						$options['ids']=$arr;
						$options['api_key']=$this->sogrid_options[$v]['api_key'];
						$options['sogrid_id']=$this->id;
						/*
						 * changes max num
						 */
						$options['max_num']=$this->sogrid_options[$v]['max'];
						/*
						 * end changes
						 */
						$this->google=new Class_My_Wp_SoGrid_Google_Plus($options);
							
						return true;
					break;
					case 'facebook':
						$options=array();
						$ids=$this->sogrid_options[$v]['id'];
						if(strpos($ids,",")!==false){
							$arr=explode(",",$ids);
						}else $arr=array($ids);
						$options['ids']=$arr;
						$options['sogrid_id']=$this->id;
						/*
						 * 
						 */
						if (version_compare(phpversion(), '5.4', '<')) {
							$options['use_facebook_sdk']=false;
						}else {
							$app_key=wp_my_sogrid_get_option_by_key('facebook_app_key');
							$app_id=wp_my_sogrid_get_option_by_key('facebook_app_id');
							if(!empty($app_id)&&!empty($app_key)){
								$options['use_facebook_sdk']=true;
								$options['app_id']=$app_id;
								$options['app_key']=$app_key;
		
						}
						
						}
						/*
						 * changes max num
						*/
						$options['max_num']=$this->sogrid_options[$v]['max'];
						/*
						 * end changes
						*/
						$this->facebook=new Class_My_Wp_SoGrid_Facebook($options);
						return true;
					break;	
					case 'twitter':
						$options=array();
						$id=$this->sogrid_options[$v]['id'];
						$include_rts=$this->sogrid_options[$v]['include_rts'];
						$include_replies=$this->sogrid_options[$v]['include_replies'];
						$options['id']=$id;
						$options['sogrid_id']=$this->id;
						$options['api_options']=array();
						/*
						 * changes max num
						*/
						$options['max_num']=$this->sogrid_options[$v]['max'];
						/*
						 * end changes
						*/
						if($include_rts){
							$val='true';
						}else $val='false';
						$options['api_options']['include_rts']=$val;
						if(!$include_replies){
							$val='true';
						}else $val='false';
						$options['api_options']['exclude_replies']=$val;
						
						$options['api_options']['consumer_key']=wp_my_sogrid_get_option_by_key('twitter_consumer_key');
						$options['api_options']['consumer_secret']=wp_my_sogrid_get_option_by_key('twitter_consumer_secret');
						$options['api_options']['oauth_token']=wp_my_sogrid_get_option_by_key('twitter_oauth_token');
						$options['api_options']['oauth_token_secret']=wp_my_sogrid_get_option_by_key('twitter_oauth_secret_token');
						$this->twitter=new Class_My_Wp_SoGrid_Twitter($options);
						return true;	
					break;	
					default:
						return false;
					break;		
				}
			}
			return false;
		}
		
		/**
		 * Check to see if is sogrid
		 */
		function is_sogrid(){
			/*
			 * chnages 1.20.2015.
			 */
			
			$my_preview=@$_GET['my_preview_sogrid'];
			if(isset($my_preview)&&$my_preview==1){
				$id=@$_GET['id'];
				$shortcode='[sogrid id="'.$id.'"]';
				preg_match_all('/\[sogrid [^\[]+\]/ims', $shortcode,$all_matches);
				$this->matches=$all_matches;
				$this->is_sogrid=true;
			}
			/*
			 * end
			 */
			else if(is_single() || is_page()){
				global $wp_query;
				$post_id=$wp_query->get_queried_object_id();
				$my_post=get_post($post_id);
				/*
				 * changes_1_16_2015
				 */
				if(preg_match_all('/\[sogrid [^\[]+\]/ims', $my_post->post_content,$all_matches)){
					$this->matches=$all_matches;
					$this->is_sogrid=true;
					//echo 'Is so grid';
				}
				/*
				 * end
				 */
			}
		}
		
		/**
		 * Scipts
		 */
		function scripts(){
			if($this->is_sogrid){
				wp_enqueue_script('jquery');
				wp_enqueue_script("jquery-touch-pounch");
				wp_enqueue_script("jquery-ui-touch-pounch");
				/*$isotope_url_1=MY_WP_SOGRID_JSCRIPT_URL.'isotope/isotope_center.js';
				wp_enqueue_script('my_wp_sogrid_isotope_center_js',$isotope_url_1);
				*/
				$isotope_url=MY_WP_SOGRID_JSCRIPT_URL.'isotope/isotope.pkgd.js';
				wp_enqueue_script('my_wp_sogrid_isotope_js',$isotope_url);
				/*
				 * changes 1.27.2015. add layout mode	
				 */
				//$isotope_url=MY_WP_SOGRID_JSCRIPT_URL.'isotope/layout-modes/dynamic-grid.js';
				//wp_enqueue_script('my_wp_sogrid_isotope_dynamic_grid_js',$isotope_url);
				/*
				 * layout mode is in front js
				 */
				
				$font_url=MY_WP_SOGRID_ASSETS_URL.'css/font-awesome/css/font-awesome.min.css';
				wp_enqueue_style('my_wp_sogrid_font_awesome',$font_url);
				$url=MY_WP_SOGRID_MODULES_URL.'front/assets/';
				$css_url=$url.'css/front.css';
				wp_enqueue_style('my_wp_sogrid_front_module_css',$css_url);
				$j_url=$url.'jscipt/front.js';
				wp_enqueue_script('my_wp_sogrid_front_module_js',$j_url);
				/*
				 * chnages 1.20.2015.
				 */				
				$url=MY_WP_SOGRID_CSS_URL.'jquery.mCustomScrollbar.css';
				wp_enqueue_style('my_wp_sogrid_mcustomscrollbar_css',$url);
				$url=MY_WP_SOGRID_JSCRIPT_URL.'jquery.mCustomScrollbar.js';
				wp_enqueue_script('my_wp_sogrid_customscrollbar_js',$url);
				/*
				 * changes 4.30. 2015.
				 */
				//$url=MY_WP_SOGRID_CSS_URL.'colorbox/colorbox.css';
				//wp_enqueue_script('my_sogrid_colorbox_css',$url);
				
				//$url=MY_WP_SOGRID_JSCRIPT_URL.'jquery.colorbox.js';
				//wp_enqueue_script('my_sogrid_colorbox_jscript',$url);
				wp_enqueue_script('jquery-ui-core');
				wp_enqueue_script('jquery-ui-widget');
				wp_enqueue_script('jquery-ui-dialog');
				//$url=plugin_dir_url(__FILE__).'assets/css/smoothness/jquery-ui-1.10.3.custom.css';
				//$url=MY_WP_SOGRID_CSS_URL.'smoothness/jquery-ui-1.10.3.custom.css';
				//wp_enqueue_style('my_dialog_ui_css',$url);
				//$google_script='https://apis.google.com/js/platform.js';
				//wp_enqueue_script('my_wp_sogrid_google_platform_js',$google_script);				
			}
		}
		/**
		 * Wp head
		 */
		function wp_head(){
			/*
			 * changes 1.22.2015.
			 * show networks
			 * added css for network
			 */
			if($this->is_sogrid){
				$this->has_facebook_sdk=wp_my_sogrid_has_facebook_sdk();
				 $my_has_google=false;	
					foreach($this->matches[0] as $k=>$v){
						$id=preg_match("/id=\"([\d]+)\"/ims", $v,$matches);
						$id=$matches[1];
						$my_options=wp_my_sogrid_get_object_and_meta($id);
						if(isset($my_options['google']) || isset($my_options['youtube']))$my_has_google=true;
						$my_general_options=$my_options['general_options'];
						$networks=$my_options['active_social'];
						$added_css=wp_my_sogrid_generate_css_rules($id, $networks, $my_options);
						$my_dynamic_loading_animation=$my_general_options['dynamic_loading_animation'];
						$enable_scroll_images=$my_general_options['enable_scroll_images'];
						?>
						<style type="text/css" id="my_sogrid_css_<?php echo $id;?>">
						<?php echo $added_css;?>
						<?php 
						if(!$my_dynamic_loading_animation){
						?>
						
						#my_sogrid_id_<?php echo $id ?> .my_sogrid_itms,#my_sogrid_id_<?php echo $id ?> .my_sogrid_itms li {
  							-webkit-transition-duration: 0.8s;
     						-moz-transition-duration: 0.8s;
      						-ms-transition-duration: 0.8s;
       						-o-transition-duration: 0.8s;
          					transition-duration: 0.8s;
							}

						#my_sogrid_id_<?php echo $id ?> .my_sogrid_itms {
  							-webkit-transition-property: height, width;
     						-moz-transition-property: height, width;
     						 -ms-transition-property: height, width;
       						-o-transition-property: height, width;
          					transition-property: height, width;
							}

						#my_sogrid_id_<?php echo $id ?> .my_sogrid_itms li {
  						-webkit-transition-property: -webkit-transform, opacity;
     					-moz-transition-property:    -moz-transform, opacity;
      					-ms-transition-property:     -ms-transform, opacity;
       					-o-transition-property:      -o-transform, opacity;
         				 transition-property:         transform, opacity;
						}


					.isotope.no-transition,
					.isotope.no-transition .isotope-item,
					.isotope .isotope-item.no-transition {
  						-webkit-transition-duration: 0s;
     					-moz-transition-duration: 0s;
      					-ms-transition-duration: 0s;
      					 -o-transition-duration: 0s;
          				transition-duration: 0s;
						}
						<?php 
						}
						?>
						</style>
						<?php 
				/*
				 *changes on window load 
				 */
						$my_preview_sogrid=@$_GET['my_preview_sogrid'];
						if(!isset($my_preview_sogrid))$my_preview_sogrid=0;
				?>
				<script type="text/javascript">
					jQuery(document).ready(function($){
						//(function($){
						$(window).load(function(e){
						var o={};
						//o.like_msg="<?php echo 'Like Video {1}.'?>";
						//o.dislike_msg="<?php echo 'Dislike Video {1}.'?>";
						o.like_dislike_youtube_url="<?php $redirect_uri   =MY_WP_SOGRID_SOCIAL_MODULES_URL.'youtube/my_like_dislike.php';
						echo $redirect_uri?>";
						<?php if($this->has_facebook_sdk!==false){?>
						o.has_facebook_sdk=true;
						<?php }else {?>
						o.has_facebook_sdk=false;
						
						<?php }?>
						o.enable_scroll_images=<?php echo $enable_scroll_images;?>;
						o.borders={};
						<?php foreach ($networks as $key=>$val){
							$enable_border=$my_options[$val]['enable_border'];	
							?>
						o.borders['<?php echo $val?>']=<?php echo $enable_border;?>;	
						<?php }?>
						o.networks=[];
						<?php foreach($networks as $k1=>$v1){?>
						o.networks[o.networks.length]="<?php echo $v1;?>";
						<?php }?>
						o.content=['t','t','nt','nt','nt','nt','t','t'];
						o.id=<?php echo $id;?>;
						o.test="test";
						o.my_preview=<?php echo $my_preview_sogrid;?>;
						o.ajax_url="<?php echo admin_url('admin-ajax.php');?>";
						o.ajax_action="<?php global $my_sogrid_ajax_action_name;echo $my_sogrid_ajax_action_name?>";
						o.ajax_action_update="my_sogrid_update_posts_share";
						o.ajax_timeout=30000;
						o.gap=170;
						o.start_op=0.3;
						o.my_duration=600;
						o.nonce="<?php global $my_sogrid_ajax_action_name;echo wp_create_nonce($my_sogrid_ajax_action_name)?>";
						o.dynamic_loading="<?php echo $my_general_options['dynamic_loading'];?>";
						o.dynamic_loading_animation=<?php echo $my_general_options['dynamic_loading_animation'];?>;
						o.dynamic_grid=<?php echo $my_general_options['dynamic'];?>;
						o.sort_date=<?php if($my_general_options['order'])echo '0';else echo '1';?>;
						o.transition=1000;
						o.width=262;
						
						//var wpMySoGridFront_inst_<?php //echo $id;?>;
						wpMySoGridFront_inst_<?php echo $id;?>=new wpMySoGridFront(o);
						
						});
							});
					my_facebook_login_<?php echo $id?>=function(){
							FB.getLoginStatus(function(response){
								wpMySoGridFront_inst_<?php echo $id;?>.facebook_login(response);
							});
						};
					my_gplus_click_<?php echo $id?>=function(param){
						wpMySoGridFront_inst_<?php echo $id;?>.gplus_click(param);
						};
					my_gplus_share_<?php echo $id;?>=function(param){
						console.log("Shgare from function",param);
						wpMySoGridFront_inst_<?php echo $id;?>.gplus_share(param);
						};	
					
				</script>
				<?php 
				/*
				 * end changes
				 */
					}
					/*
					 * change 1.25.2015.
					 * add fonts link
					 */
					$fonts_link=wp_my_sogrid_get_google_fonts_link();
					if($fonts_link!==false){
					?>
					<link type="text/css" rel="stylesheet" href="<?php echo $fonts_link?>"/>
					<?php 
					}
					/*
					 * end changes
					 */
				if($my_has_google){
					/*
					 * changes 1.20.2015.
					 */
					//return;
					/*
					 * end changes
					 */
				?>
				<script src="https://apis.google.com/js/platform.js"  async defer></script>
				<?php /*
				<script type="text/javascript">
					jQuery(document).ready(function($){
						my_function_set_goolge_plus=function(){
							if(typeof window.gapi=='undefined'){
								if(window.console){
									console.log('Gapi is undefined');
									}
								setTimeout(my_function_set_goolge_plus,300);
										
							}else {
								if(window.console){
									console.log('****Gapi is defined*****');
									}
								window.gapi.plusone.render(".my_social_item_share_google" );
							}
					};
						setTimeout(my_function_set_goolge_plus,300);
					});
				</script>
				*/ ?>
				<?php 
				}	
				/*
				 * end
				 */	
			}
		}
		
	}
	
}