<?php
if(!defined('ABSPATH'))die('');
global $my_wp_sogrid_options_name;
global $my_wp_sogrid_options;
global $my_wp_sogrid_fetched_options;
$my_wp_sogrid_fetched_options=false;
$my_wp_sogrid_options_name='my_sogrid_general_options';
global $my_wp_sogrid_debug;
global $my_wp_sogrid_debug_data;
global $wp_my_sogrid_object_cache_time;
$wp_my_sogrid_object_cache_time=3600;
$my_wp_sogrid_debug=false;
global $wp_my_sogrid_image_prop_x;
global $wp_my_sogrid_image_prop_y;
$wp_my_sogrid_image_prop_x=400;
$wp_my_sogrid_image_prop_y=400;
/*
 * changes 1.25.2015.
 * fonts
 */
global $my_sogrid_fonts_new;
global $my_sogrid_fonts_include;
$my_sogrid_fonts_include=array();
global $my_sogrid_checked_fonts;
global $my_sogrid_page_included_fonts_1234;
$my_sogrid_page_included_fonts_1234=array();
$my_woo_checked_fonts=0;
/*
 * changes 2.11.2015.
 */
global $my_sogrid_ajax_action_name;
$my_sogrid_ajax_action_name='wp_my_ajax_sogrid_get_more';
global $my_sogrid_dynamic_content_array;
$my_sogrid_dynamic_content_array=array('t','t','nt','nt','nt','nt','t','t');
global $my_sogrid_dynamic_content_num;
$my_sogrid_dynamic_content_num=9;//9 bilo
/**
 * Genrate api url
 * @param unknown $url
 * @param unknown $params
 * @return string
 */
function wp_my_sogrid_generate_api_url($url,$data){
	$my_get_str='';
	foreach($data as $key=>$val){
		if(strlen($my_get_str)>0)$my_get_str.='&';
		$my_get_str.=$key.'='.urlencode($val);
	}
	$url.='?'.$my_get_str;
	return $url;
}
/**
 * Get galleries
 * @param unknown $username
 * @return Ambigous <string, mixed>|mixed
 */
function wp_my_sogrid_flickr_get_gall_id($username){
	$api_key=wp_my_sogrid_get_option_by_key('flickr_api_key');
	if(empty($api_key)){
		return __("Please add API key for Flickr","wp_my_sogrid_domain");
	}else {
		$url='https://api.flickr.com/services/rest/';
		$data=array(
				'api_key'=>$api_key,
				'user_id'=>$username,
				'method'=>'flickr.galleries.getList',
				'format'=>'json',
				'nojsoncallback'=>1,
				'per_page'=>100
		);
		$my_get_str='';
		foreach($data as $key=>$val){
			if(strlen($my_get_str)>0)$my_get_str.='&';
			$my_get_str.=$key.'='.urlencode($val);
		}
		$url.='?'.$my_get_str;
		echo 'url '.$url;
		$data=wp_remote_get($url);
		//print_r($data);
		if(is_wp_error($data)){
			return __("Error","wp_my_sogrid_domain");
		}
		if($data['response']['code']==200){
			$obj=json_decode($data['body']);
			//if($obj->stat=='ok'){
				
			return $obj;
			//}else return __("Error","wp_my_sogrid_domain");
		}else {
			$obj=json_decode($data['body']);
			return $obj;
		}

	}

}
/**
 * Get groups ids
 * @param unknown $name
 * @return Ambigous <string, mixed>|mixed
 */
function wp_my_sogrid_get_vkontakte_groups($name){
	$url='https://api.vk.com/method/groups.search?q='.urlencode($name);
	$vk_a=get_option("_my_sogrid_vkontakte_access_token");
	$url.='&access_token='.urlencode($vk_a).'&count=100';
	$data=wp_remote_get($url);
	//print_r($data);
	if(is_wp_error($data)){
		return __("Error","wp_my_sogrid_domain");
	}
	if($data['response']['code']==200){
		$obj=json_decode($data['body']);
		//echo '<pre>';print_r($obj);echo '</pre>';
		if(isset($obj->error)){
			return __("Error getting results","wp_my_sogrid_domain");
		}else return $obj;
		return $obj;
	
	}else {
		$obj=json_decode($data['body']);
		return $obj;
	}
	
}
/**
 * 
 * @param unknown $id
 * @return Ambigous <string, mixed>|mixed
 */
function wp_my_sogrid_get_vkontakte_albums($id){
	$url='https://api.vk.com/method/photos.getAlbums?owner_id='.urlencode($id);
	$url.='&need_covers=1&count=100&photo_sizes=1';
	//$url.='&fields='.urlencode('nickname, screen_name, sex, bdate (birthdate), city, country, timezone, photo, photo_medium, photo_big, has_mobile, rate, contacts, education, online ');
	//$vk_a=get_option("_my_sogrid_vkontakte_access_token");
	//$url.='&access_token='.urlencode($vk_a);
	
	//echo $url;
	$data=wp_remote_get($url);
	//print_r($data);
	if(is_wp_error($data)){
		return __("Error","wp_my_sogrid_domain");
	}
	if($data['response']['code']==200){
		$obj=json_decode($data['body']);
		//echo '<pre>';print_r($obj);echo '</pre>';
		if(isset($obj->error)){
			return __("Error getting results","wp_my_sogrid_domain");
		}else return $obj;
		return $obj;
	
	}else {
		$obj=json_decode($data['body']);
		return $obj;
	}
}
/**
 * Users get id by screen name
 * @param unknown $name
 * @return Ambigous <string, mixed>|mixed
 */
function wp_my_sogrid_get_vkontakte_users_by_screenname($name){
	$url='https://api.vk.com/method/users.get?user_ids='.urlencode($name);
	$url.='&fields='.urlencode('nickname, screen_name, sex, bdate (birthdate), city, country, timezone, photo, photo_medium, photo_big, has_mobile, rate, contacts, education, online ');
	//$vk_a=get_option("_my_sogrid_vkontakte_access_token");
	//$url.='&access_token='.urlencode($vk_a);
	
	//echo $url;
	$data=wp_remote_get($url);
	//print_r($data);
	if(is_wp_error($data)){
		return __("Error","wp_my_sogrid_domain");
	}
	if($data['response']['code']==200){
		$obj=json_decode($data['body']);
		//echo '<pre>';print_r($obj);echo '</pre>';
		if(isset($obj->error)){
			return __("Error getting results","wp_my_sogrid_domain");
		}else return $obj;
		return $obj;
	
	}else {
		$obj=json_decode($data['body']);
		return $obj;
	}
	
}
/**
 * Search vkonatkte for a user
 * @param unknown $name
 * @return Ambigous <string, mixed>|mixed
 */
function wp_my_sogrid_get_vkontakte_users($name){
	//$a='0421948962294f702f63347986fbc0193c6be04cf20a1b7bebd96583e3894f3fe890bd64f6b4c0e74bd5c';
	//update_option('_my_sogrid_vkontakte_access_token', $a);
	$url='https://api.vk.com/method/users.search?q='.urlencode($name).'&count=100';
	$vk_a=get_option("_my_sogrid_vkontakte_access_token");
	$url.='&fields='.urlencode('nickname, screen_name, sex, bdate (birthdate), city, country, timezone, photo, photo_medium, photo_big, has_mobile, rate, contacts, education, online ');
	$url.='&access_token='.urlencode($vk_a);
	$data=wp_remote_get($url);
	//print_r($data);
	if(is_wp_error($data)){
		return __("Error","wp_my_sogrid_domain");
	}
	if($data['response']['code']==200){
		$obj=json_decode($data['body']);
		//echo '<pre>';print_r($obj);echo '</pre>';
		if(isset($obj->error)){
			return __("Error getting results","wp_my_sogrid_domain");
		}else return $obj;
		return $obj;
		
	}else {
		$obj=json_decode($data['body']);
		return $obj;
	}
}
/**
 * get ndis of some user
 * @param unknown $username
 * @return Ambigous <string, mixed>|mixed
 */
function wp_my_sogrid_flickr_get_user_id($username){
	$api_key=wp_my_sogrid_get_option_by_key('flickr_api_key');
	if(empty($api_key)){
		return __("Please add API key for Flickr","wp_my_sogrid_domain");
	}else {
		$url='https://api.flickr.com/services/rest/';
		$data=array(
			'api_key'=>$api_key,
			'username'=>$username,
			'method'=>'flickr.people.findByUsername',	
			'format'=>'json',
			'nojsoncallback'=>1
		);
		$my_get_str='';
		foreach($data as $key=>$val){
			if(strlen($my_get_str)>0)$my_get_str.='&';
			$my_get_str.=$key.'='.urlencode($val);
		}
		$url.='?'.$my_get_str;
		//echo 'url '.$url;
		$data=wp_remote_get($url);
		//print_r($data);
		if(is_wp_error($data)){
			return __("Error","wp_my_sogrid_domain");
		}
		if($data['response']['code']==200){
			$obj=json_decode($data['body']);
			if($obj->stat=='ok'){
				$url='https://api.flickr.com/services/rest/';
				$data=array(
						'api_key'=>$api_key,
						'user_id'=>$obj->user->nsid,
						'method'=>'flickr.people.getInfo',
						'format'=>'json',
						'nojsoncallback'=>1
				);
				$my_get_str='';
				foreach($data as $key=>$val){
					if(strlen($my_get_str)>0)$my_get_str.='&';
					$my_get_str.=$key.'='.urlencode($val);
				}
				$url='https://api.flickr.com/services/rest/';
				$url.='?'.$my_get_str;
				$data=wp_remote_get($url);
				if(!is_wp_error($data)){
					if($data['response']['code']==200){
					//print_r($data);
						$obj_12=json_decode($data['body']);
						$obj_1=$obj_12->person;
						$obj->iconserver=$obj_1->iconserver;
						$obj->iconfarm=$obj_1->iconfarm;
						$nsid=$obj_1->nsid;
							
						$obj->nsid=$nsid;
						$obj->id=$obj_1->id;
						if($obj->iconserver>0){
							$my_icon='http://farm{icon-farm}.staticflickr.com/{icon-server}/buddyicons/{nsid}.jpg';
							$my_icon=str_replace('{icon-farm}', $obj->iconfarm, $my_icon);
							$my_icon=str_replace('{icon-server}', $obj->iconserver, $my_icon);
							$my_icon=str_replace('{nsid}', $nsid, $my_icon);
							$obj->my_icon=$my_icon;	
								
							
						}else {
							$obj->my_icon='https://www.flickr.com/images/buddyicon.gif';
						}
						$check_fields=array(
							'realname',
							'description',
							'count'		
						);
						foreach($check_fields as $k=>$v){
							$obj->$v="";
							if(isset($obj_1->$v->_content)){
								$obj->$v=$obj_1->$v->_content;
							}
						}
					}
				}
				
			}
			return $obj;
		}else {
			$obj=json_decode($data['body']);
			return $obj;
		}
		
	}
	
}
/**
 * Save liked disliked from a site
 * @param unknown $video_id
 * @param unknown $sogrid_id
 * @param string $network
 */
function wp_my_sogrid_update_youtube_posts($video_id,$sogrid_id,$network='youtube'){
	$id=(int)$sogrid_id;
	$post_id=$video_id;
	$is_exists=wp_my_sogrid_is_exist_object($id);
	if(!empty($is_exists)){
		$options=wp_my_sogrid_get_object_and_meta($id);
		$networks=$options['active_social'];
		if(!in_array('youtube', $networks)){
			
		}else {
			$file=MY_WP_SOGRID_MODULES_DIRNAME.'front/class.php';
			require_once $file;
			$options_class=array('id'=>$id,'sogrid_options'=>$options);
			$front_module=new Class_Wp_My_SoGrid_Front_View($options_class);
			$obj=$front_module->get_post($network, $post_id);
			if($obj!==false){
				wp_my_sogrid_save_share_cache($id, $network, $post_id, $obj);
			}
			//$ret['obj']=$obj;
		}
	}
}
/**
 * Force limit a text by subtsr
 * @param unknown $text
 * @param unknown $limit
 * @return unknown|mixed|string
 */
function wp_my_sogrid_force_limit_text($text,$limit){
	if(strlen($text)<$limit)return $text;
	else {
		$text=preg_replace('/&#[0-9]+;/', '', $text);
		$text=preg_replace('/&[a-z]+;/', '', $text);
		if(strlen($text)<$limit)return $text;
		/*
			$text=substr($text,0,$limit).'...';
		return $text;
		*/
		$new_text='';
		$text_arr=explode(" ", $text);
		if(!empty($text_arr)){
			$len=0;
			foreach($text_arr as $k=>$v){
				$new_len=$len+strlen($v);
				if($new_len>$limit){
					$new_text.='...';
					break;
				}else {
					if(strlen($new_text)>0){
						$new_text.=" ";
						$len++;
					}
					$new_text.=$v;
					$len+=strlen($v);
				}
			}
			return $new_text;
		}else {
			$text=substr($text,0,$limit).'...';
			return text;
		}
		
	}
	
}
/**
 * Has youtube client id client secret
 * @return boolean
 */
function wp_my_sogrid_has_youtube_client_id(){
	$client_id  = wp_my_sogrid_get_option_by_key('google_client_id');
	$client_secret  = wp_my_sogrid_get_option_by_key('google_client_secret');
	if(!empty($client_id)&&!empty($client_secret))return true;
	else return false;
}
/*
 * Get network name
 */
function wp_my_sogrid_get_network_name_12($key){
	global $wp_my_sogrid_social_modules;
	if(isset($wp_my_sogrid_social_modules[$key])){
		return $wp_my_sogrid_social_modules[$key]['title'];
	}
	return '';
}
function wp_my_sogrid_get_site_url(){
	$url=get_site_url();
	$len=strlen($url)-1;
	if(strrpos($url,"/")!=$len){
		$url.='/';
	}
	return $url;
}
global $my_sogrid_instagram_access_token;
function wp_my_sogrid_check_instagram_access_token(){
	
	$option_name='wp_my_sogrid_instagram_access_token_12_12';
	//update_option($option_name, '1990812293.b81ee9e.92fcd65098b24303a5128cdff143a1df');
	$token=get_option($option_name);
	//echo 'token '.$token;
	if(empty($token)){
		return false;
	}else {
		$url='https://api.instagram.com/v1/users/self/feed?access_token='.$token.'';
		$data=wp_remote_get($url);
		//print_r($data);
		if(is_wp_error($data)){
			return false;
		}else {
			//echo $token;
			$response='';
			if(isset($data['body']))$response=$data['body'];
			//print_r($response);
			if($data['response']['code']==200){
				return true;
			}else return false;
			
		}
	}
}
/**
 * Format facebook image
 * @param unknown $image
 * @return Ambigous <unknown, string>
 */
function wp_my_sogrid_format_facebook_image($image){
	$url=$image;
	if(strpos($image,'url=')!==false){
		$arr=parse_url($url);
		//print_r($arr);
		if(!empty($arr['query'])){
			if(strpos($arr['query'],'&')!==false){
			$arr_1=explode("&",$arr['query']);
			//print_r($arr_1);
			if(!empty($arr_1)){
				$c=count($arr_1)-1;
				for($i=0;$i<=$c;$i++){
					$cval=$arr_1[$i];
					$new_arr=explode("=",$cval);
					if(!empty($new_arr)){
						$key=$new_arr[0];
						$val=$new_arr[1];
						if($key=='url'){
							$url=urldecode($val);
							break;
						}
					}
				}
			}
			}else {
				if(strpos($arr['query'],'=')!==false){
				$new_arr=explode("=",$arr['query']);
				if(!empty($new_arr)){
					$key=$new_arr[0];
					$val=$new_arr[1];
					if($key=='url'){
						$url=urldecode($val);
						//break;
					}
				}}
			}
		}
	}
	return $url;
}
/**
 * Limit ttitle text
 * @param unknown $text
 * @param number $limit
 * @return unknown|mixed|string
 */
function wp_my_sogrid_limit_title_text($text,$limit=108){
	
	if(strlen($text)<$limit)return $text;
	else {
		$text=preg_replace('/&#[0-9]+;/', '', $text);
		$text=preg_replace('/&[a-z]+;/', '', $text);
		if(strlen($text)<$limit)return $text;
		/*
		$text=substr($text,0,$limit).'...';
		return $text;
		*/
		$new_text='';
		$text_arr=explode(" ", $text);
		if(!empty($text_arr)){
			$len=0;
			foreach($text_arr as $k=>$v){
				$new_len=$len+strlen($v);
				if($new_len>$limit){
					$new_text.='...';
					break;
				}else {
					if(strlen($new_text)>0){
						$new_text.=" ";
						$len++;
					}
					$new_text.=$v;
					$len+=strlen($v);
				}		
			}
		}else return $text;
		return $new_text;
	}
}
/**
 * Limit text
 * @param unknown $text
 * @param number $limit
 */
function wp_my_sogrid_limit_text($text,$limit=120){
	$text_arr=explode(" ",$text);
	$ret='';
	if(!empty($text_arr)){
		foreach($text_arr as $k=>$v){
			$ret_new=$ret;
			if(strlen($ret_new)>0)$ret_new.=' ';
			$ret_new.=$v;
			if(strlen($ret_new)>$limit){
				$ret.='...';
				break;
			}else {
				$ret=$ret_new;
			}
		
		}
	}else $ret=$text;
	//if(!empty($ret))$ret.='...';
	return $ret;
}
/**
 * has facebook sdk
 * @return boolean|Ambigous <Ambigous, boolean, unknown>
 */
function wp_my_sogrid_has_facebook_sdk(){
	if (version_compare(phpversion(), '5.4', '<')) {
		return false;
	}else {
		$app_key=wp_my_sogrid_get_option_by_key('facebook_app_key');
		$app_id=wp_my_sogrid_get_option_by_key('facebook_app_id');
		if(empty($app_id))return false;
		else return $app_id;
	}
}
/**
 * 
 * @param unknown $username
 * @return boolean|mixed
 */
function wp_my_sogrid_find_instagram_location($lat,$lon,$dis){
	$option_name='wp_my_sogrid_instagram_access_token_12_12';
	$token=get_option($option_name);
	if(!empty($token)){
		$url='https://api.instagram.com/v1/locations/search?lat='.$lat.'&lng='.$lon.'&distance='.$dis.'&access_token='.$token.'&count=50';
		//echo $url;
		$data=wp_remote_get($url);
		//print_r($data);
		if(is_wp_error($data)){
			return false;
		}else {
			//echo $token;
			$response='';
			if(isset($data['body']))$response=$data['body'];
			//print_r($response);
			if($data['response']['code']==200){
				return json_decode($response);
			}else return false;

		}
	}else {
		return false;
	}
}
/**
 * 
 * @param unknown $username
 * @return boolean|mixed
 */
function wp_my_sogrid_find_instagram_user($username){
	$option_name='wp_my_sogrid_instagram_access_token_12_12';
	$token=get_option($option_name);
	if(!empty($token)){
		$url='https://api.instagram.com/v1/users/search?q='.urlencode($username).'&access_token='.$token;
		$data=wp_remote_get($url);
		//print_r($data);
		if(is_wp_error($data)){
			return false;
		}else {
			//echo $token;
			$response='';
			if(isset($data['body']))$response=$data['body'];
			//print_r($response);
			if($data['response']['code']==200){
				return json_decode($response);
			}else return false;
				
		}
	}else {
		return false;
	}
	}
/**
 * Get channel for username
 * @param unknown $name
 * @return boolean|mixed
 */	
function wp_my_sogrid_find_youtube_channel_id($name){
	$my_saved_google_api_key=wp_my_sogrid_get_option_by_key('google_api_key');
	$url='https://www.googleapis.com/youtube/v3/channels?key='.$my_saved_google_api_key.'&forUsername='.urlencode($name).'&maxResults=50&part=snippet';
	$data=wp_remote_get($url);
	//print_r($data);
	if(is_wp_error($data)){
		return false;
	}else {
		//echo $token;
		$response='';
		if(isset($data['body']))$response=$data['body'];
		//print_r($response);
		if($data['response']['code']==200){
			return json_decode($response);
		}else return false;
	
	}
}	
/**
 * Get facebook albums
 * @param unknown $user_id
 * @return Ambigous <string, mixed>|Ambigous <multitype:, string, multitype:>
 */
function wp_my_sogrid_get_facebook_albumns($user_id){
	$options['use_facebook_sdk']=false;
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
	if(!$options['use_facebook_sdk']){
		return __("Error Plugin can't use facebook SDK","wp_my_sogrid_domain");
	}else {
		global $my_app_id;
		global $my_app_key;
		$my_app_id=$options['app_id'];
		$my_app_key=$options['app_key'];
		//$dir=plugin_dir_path(__FILE__);
		$dir=MY_WP_SOGRID_MODULES_DIRNAME.'social/facebook/';
		require_once $dir.'libs/facebook-php-sdk/autoload.php';
		require_once $dir.'libs/my_api_calls.php';
		$ret=my_facebook_get_user_albums($user_id);
		if(!is_array($ret)){
			return __("Error getting user albums.","wp_my_sogrid_domain");
		}else return $ret;

	}
}
/**
 * Wp my get list id
 * @param unknown $slug
 * @return Ambigous <string, mixed>
 */
function wp_my_sogrid_twitter_get_list_id($owner){
	$file=MY_WP_SOGRID_SOCIAL_MODULES_DIRNAME.'twitter/libs/twitter-auth.php';
	require_once $file;
	$options['consumer_key']=wp_my_sogrid_get_option_by_key('twitter_consumer_key');
	$options['consumer_secret']=wp_my_sogrid_get_option_by_key('twitter_consumer_secret');
	$options['oauth_token']=wp_my_sogrid_get_option_by_key('twitter_oauth_token');
	$options['oauth_token_secret']=wp_my_sogrid_get_option_by_key('twitter_oauth_secret_token');
	$has_api=true;
	foreach($options as $k=>$v){
		if(empty($v))$has_api=false;
		break;
	}	
	$options['screen_name']=$owner;
	//$options['list_slug']=$slug;
	//$options['owner_screen_name']=$owner;
	if(!$has_api){
		return __("Please Add Twitter Api details.","wp_my_sogrid_domain");
	}
	$options['rest']='lists/list';
	$class=new Class_Wp_My_SoGrid_Twitter_Api($options);
	$ret=$class->callApi();
	 
	if(is_wp_error($ret)){
		return __("Error getting data.","wp_my_sogrid_domain");
	}else {
	if($ret['response']['code']==200){	
		//if(!empty($ret['body'])){
			$data=$ret['body'];
			$obj=json_decode($data);
			//print_r($obj);
			if($obj==null)return __("Error getting data.","wp_my_sogrid_domain");
			return $obj;
		//}else return __("Error getting data.","wp_my_sogrid_domain");
	}
	else return __("Error getting data.","wp_my_sogrid_domain");
}
	//print_r($ret);
	
}
/**
 * 
 * @param unknown $name
 * @return mixed|boolean
 */
function wp_my_sogrid_get_facebook_id($name){
	$url='https://graph.facebook.com/'.urlencode($name);
	$data=wp_remote_get($url);
	if(!is_wp_error($data)){
		if(isset($data['body'])){
			$json=$data['body'];
			$obj=json_decode($json);
			return $obj;
		}
	}else return false;
}
/**
 * Get google ids
 * @param unknown $term
 * @param unknown $api_key
 * @return mixed|boolean
 */
function wp_my_sogrid_get_google_ids($term,$api_key){
	$url='https://www.googleapis.com/plus/v1/people?query='.urlencode($term).'&key='.$api_key;
	$data=wp_remote_get($url);
	if(!is_wp_error($data)){
		if(isset($data['body'])){
			$json=$data['body'];
			$obj=json_decode($json);
			return $obj;
		}
	}else return false;
}
/**
 * Find has thumb post 
 * @param unknown $network
 * @param unknown $networks
 * @param unknown $has_thumb
 * @return Ambigous <boolean, number>
 */
function wp_my_sogrid_find_thumb_post($network,$networks,&$has_thumb){
	$found=false;
	$found_network=$network;
	if(count($has_thumb[$network])==0){
		foreach($networks as $k=>$v){
			if($v==$network)continue;
			if(count($has_thumb[$v])>0){
				$found_network=$v;
				$found=$has_thumb[$v][0];
				$has_thumb[$v]=array_slice($has_thumb[$v], 1);
				//wp_my_sogrid_debug_object("Find thumb ".$v, $has_thumb[$v]);
				break;
			}
		}
		
	}else {
		$found=$has_thumb[$network][0];
		$has_thumb[$network]=array_slice($has_thumb[$network], 1);
		//wp_my_sogrid_debug_object("Find thumb ".$network, $has_thumb[$network]);
		
	}
	if($found!==false){
		$ret['find']=$found;
		$ret['network']=$found_network;
		return $ret;
	}
	return $found;
}
/**
 * Has no thumb
 * @param unknown $network
 * @param unknown $networks
 * @param unknown $has_thumb
 * @param unknown $has_no_thumb
 * @return number|unknown|Ambigous <boolean, number>
 */
function wp_my_sogrid_find_no_thumb_post($network,$networks,&$has_thumb,&$has_no_thumb){
	$found=false;
	$found_network=$network;
	if(count($has_no_thumb[$network])==0){
		foreach($networks as $k=>$v){
			if($v==$network)continue;
			if(count($has_no_thumb[$v])>0){
				$found=$has_no_thumb[$v][0];
				$found_network=$v;
				$has_no_thumb[$v]=array_slice($has_no_thumb[$v], 1);
				//wp_my_sogrid_debug_object("New array", $has_no_thumb[$v]);
				break;
			}
		}
	}else {
		
			$found=$has_no_thumb[$network][0];
			$has_no_thumb[$network]=array_slice($has_no_thumb[$network], 1);
		
		//wp_my_sogrid_debug_object("New array", $has_no_thumb[$network]);
		
	}
	if($found===false){
		$k=wp_my_sogrid_find_thumb_post($network, $networks, $has_thumb);
		if($k!==false){
			//$found='thumb_'.$k;
			$ret['find']=$k['find'];
			$ret['network']=$k['network'];
			$ret['thumb']=1;
			return $ret;
		}
	}else {
		$ret['find']=$found;
		$ret['network']=$found_network;
		return $ret;
	}
	return $found;
	
}
/**
 * 
 * @param unknown $options
 * @param unknown $sogrid_array
 * @param unknown $id
 * @param unknown $networks
 */
function wp_my_sogrid_format_response($options,&$sogrid_array,$id,$networks){
	global $my_sogrid_dynamic_content_array;
	
	$dynamic=$options['general_options']['dynamic'];
	$order=$options['general_options']['order'];
	$limit_arr=array();
	/**
	 * Limit array
	 */
	$total_posts=0;
	//wp_my_sogrid_debug_object("Networks",$networks);
	foreach($networks as $k=>$v){
		$limit_num=$options[$v]['limit'];
		if(!empty($sogrid_array[$v])){
		//wp_my_sogrid_debug_object("Network ".$v, $limit_num);
		if($limit_num){
			//wp_my_sogrid_debug_object("Network ".$v, $limit_num);
			$time=time()-$options[$v]['limit_num']*DAY_IN_SECONDS;
			//wp_my_sogrid_debug_object("Last time", date('Y/m/d H:i:s',$time));
			$limit_arr[$v]=array();
			foreach($sogrid_array[$v] as $key=>$obj){
				$published=$obj->getProperty('published');
				//$type=$obj->getType();
				//$title=$obj->getProperty('title');
				//wp_my_sogrid_debug_object("Object network=".$v, array("type"=>$type,"title"=>$title));
				//$obj->setNoShowThumb();
				//wp_my_sogrid_debug_object("Time", date('Y/m/d H:i:s',$published));
				if($published<$time)break;
				$limit_arr[$v][]=$sogrid_array[$v][$key];
			}
			
		}else $limit_arr[$v]=$sogrid_array[$v];
		$total_posts+=count($limit_arr[$v]);
		}else $limit_arr[$v]=array();
	}
	//wp_my_sogrid_debug_object("Google", $limit_arr['google']);
	if($dynamic){
		$format_array=array();
		$has_thumb=array();
		$has_no_thumb=array();
		foreach($networks as $k=>$v){
			$has_no_thumb[$v]=array();
			$has_thumb[$v]=array();
			foreach($limit_arr[$v] as $key=>$obj){
				$att=$obj->getImageNew();
				if(!empty($att)){
					$has_thumb[$v][]=$key;
				}else $has_no_thumb[$v][]=$key;
			}
		}
		/*wp_my_sogrid_debug_object("Has thumb", $has_thumb);
		wp_my_sogrid_debug_object("Has no thumb", $has_no_thumb);
		wp_my_sogrid_debug_object("Total posts", $total_posts);
		*/
		$added=0;
		$c_network=0;
		$no_thumb_else=false;
		$c=0;
		$network='';
		$c_network=0;
		$c_option=0;
		while($added<$total_posts){
			$network=$networks[$c_network];
			$option_str=$my_sogrid_dynamic_content_array[$c_option];
			//wp_my_sogrid_debug_object("Added ".$added, array('network'=>$network,'option_str'=>$option_str));
				
			if(!$no_thumb_else){
				if($option_str=='t'){
					$find=wp_my_sogrid_find_thumb_post($network, $networks, $has_thumb);
					//wp_my_sogrid_debug_object("Find thumb", $find);
					if($find===false){
						$no_thumb_else=true;
					}else {
						$k=$find['find'];
						$network=$find['network'];
						//echo 'k= '.$k.' network '.$network;
						//$id=$has_thumb[$network][$k];
						$format_array[]=$limit_arr[$network][$k];
						//$obj=$limit_arr[$network][$k];
						//$type=$obj->getType();
						//$title=$obj->getProperty('title');
						//wp_my_sogrid_debug_object("Object", array("type"=>$type,"title"=>$title));
							
					}
				}else {
					/*if($network=='pinterest'){
						$c_network++;
						if(count($networks)==$c_network)$c_network=0;
						$network=$networks[$c_network];
					}*/
					$find=wp_my_sogrid_find_no_thumb_post($network, $networks, $has_thumb, $has_no_thumb);
					if($find!==false){
					//wp_my_sogrid_debug_object("Find no thumb", $find);
					$k=$find['find'];
					$network=$find['network'];
					//echo 'k= '.$k.' network '.$network;
					if(isset($find['thumb'])){
						//$id=$has_thumb[$network][$k];
						$obj=$limit_arr[$network][$k];
						$obj->setNoShowThumb();
					}
					//$obj=$limit_arr[$network][$k];
					//$type=$obj->getType();
					//$title=$obj->getProperty('title');
					//wp_my_sogrid_debug_object("Object", array("type"=>$type,"title"=>$title));
					
					//else $id=$has_no_thumb[$network][$k];
					$format_array[]=$limit_arr[$network][$k];
					}
				}
			}
			if($no_thumb_else){
				/*if($network=='pinterest'){
					$c_network++;
					if(count($networks)==$c_network)$c_network=0;
					$network=$networks[$c_network];
				}*/
				$find=wp_my_sogrid_find_no_thumb_post($network, $networks, $has_thumb, $has_no_thumb);
				if($find!==false){
				$k=$find['find'];
				$network=$find['network'];
				//echo 'k= '.$k.' network '.$network;
				//wp_my_sogrid_debug_object("Find no thumb", $find);
				if(isset($find['thumb'])){
					//$id=$has_thumb[$network][$k];
					$obj=$limit_arr[$network][$k];
					/*if(!is_object($obj)){
						wp_my_sogrid_debug_object("Network array", $limit_arr[$network]);
					}
					else 
					*/
					$obj->setNoShowThumb();
				}
				//$obj=$limit_arr[$network][$k];
				//$type=$obj->getType();
				//$title=$obj->getProperty('title');
				//wp_my_sogrid_debug_object("Object", array("type"=>$type,"title"=>$title));
					
				//else $id=$has_no_thumb[$network][$k];
				$format_array[]=$limit_arr[$network][$k];
				}
			}
			$c++;
			$added++;
			$c_network++;
			$c_option++;
			if(count($networks)==$c_network)$c_network=0;
			if(count($my_sogrid_dynamic_content_array)==$c_option)$c_option=0;
			
		}
	//	wp_my_sogrid_debug_object("Format array", $format_array);
	/*foreach($format_array as $k=>$obj){
		$type=$obj->getType();
		$title=$obj->getProperty('title');
		$has_thumb_1=$obj->getProperty('show_thumb');
		if($has_thumb_1){
			wp_my_sogrid_debug_object("Has thumb 1 id=".$k, array("type"=>$type,"title"=>$title));
			
		}else {
			wp_my_sogrid_debug_object("Has thumb 0 id=".$k, array("type"=>$type,"title"=>$title));
				
			
		}
		//wp_my_sogrid_debug_object("Object", array("type"=>$type,"title"=>$title));
		
	}*/
		//wp_my_sogrid_debug_object("Has thumb", $has_thumb);
		//wp_my_sogrid_debug_object("Has no thumb", $has_no_thumb);
		//echo count($format_array);
		return $format_array;
		
		
		
	}else if($order==1){
		/**
		 * random order
		 */
		$format_array=array();
		foreach($networks as $k=>$v){
			$arr=$limit_arr[$v];
			//$arr=shuffle($arr);
			$format_array=array_merge($format_array,$arr);
		}
		shuffle($format_array);
		return $format_array;
		
	}else if($order==0){
		/**
		 * published order
		 */
		$format_array=array();
		foreach($networks as $k=>$v){
			$arr=$limit_arr[$v];
			//$arr=shuffle($arr);
			$format_array=array_merge($format_array,$arr);
		}
		usort($format_array, 'wp_my_sogrid_sort_compare');
		return $format_array;
		
	}
	
}
function wp_my_sogrid_sort_compare($a,$b){
	$p_1=$a->getProperty('published');
	$p_2=$b->getProperty('published');
	if($p_1==$p_2)return 0;
	if($p_1<$p_2)return 1;
	else return -1;
}
/**
 * Generate css rules
 * @param unknown $networks
 * @param unknown $options
 */
function wp_my_sogrid_generate_css_rules($sogrid_id,$networks, $options){
	$added_css='';
	$my_id='my_sogrid_id_'.$sogrid_id;
	$my_has_disabled_borders=array();
	foreach($networks as $k=>$network){
		$file=MY_WP_SOGRID_SOCIAL_MODULES_DIRNAME.$network.'/options.php';
		require_once $file;
		$name_3='wp_my_sogrid_'.$network.'_item_options';
		//echo $name_3;
		global $$name_3;
		$network_options=$$name_3;
		//print_r($network_options);
		if(!empty($$name_3)){
		foreach($$name_3 as $key=>$val){
			if(!isset($options[$network][$key]))continue;
			switch ($key){
				case 'author_color':
					ob_start();
					ob_start();
					if($network=='flickr'){
						?>
						#<?php echo $my_id;?> .<?php echo 'my_social_item_share_flickr'?> span {
						color:<?php echo $options[$network][$key];?> !important;
																}
						<?php 					
						}else if($network=='dribbble'){
						?>
						#<?php echo $my_id;?> .<?php echo 'my_social_item_share_dribbble'?> span {
						color:<?php echo $options[$network][$key];?> !important;
																}
						<?php 					
						}
					$css=ob_get_clean();
					$added_css.=$css;
				break;	
				case 'icons_color':
					ob_start();
					if($network=='flickr'){
						?>
						#<?php echo $my_id;?> .<?php echo 'my_sogrid_flickr_metadata'?> span i{
												color:<?php echo $options[$network][$key];?> !important;
											}
						<?php 					
					}else if($network=='dribbble'){
						?>
						#<?php echo $my_id;?> .<?php echo 'my_sogrid_dribbble_metadata'?> span i{
												color:<?php echo $options[$network][$key];?> !important;
											}
						<?php 					
					}
					$css=ob_get_clean();
					$added_css.=$css;
				break;	
				case 'stat_color':
					ob_start();
					if($network=='youtube'){
					?>
					#<?php echo $my_id;?> .<?php echo 'my_sogrid_youtube_metadata'?> span , #<?php echo $my_id;?> .<?php echo 'my_social_item_share_youtube'?> span{
						color:<?php echo $options[$network][$key];?> !important;
					}
					<?php }else if($network=='flickr'){?>
					#<?php echo $my_id;?> .<?php echo 'my_sogrid_flickr_metadata'?> span {
						color:<?php echo $options[$network][$key];?> !important;
					}
					<?php }else if($network=='dribbble'){?>
					#<?php echo $my_id;?> .<?php echo 'my_sogrid_dribbble_metadata'?> span {
						color:<?php echo $options[$network][$key];?> !important;
					}
					<?php }?>
					<?php 
					$css=ob_get_clean();
					$added_css.=$css;
				break;	
				case 'numbers_color':
						$css_class='my_sogrid_pinterest_social_new';
						ob_start();
						?>
						#<?php echo $my_id;?> .<?php echo $css_class?> span{
							color:<?php echo $options[$network][$key];?> !important;
						}
						<?php 
						$css=ob_get_clean();
						$added_css.=$css;
				break;	
				case 'author_border_color':
					$css_class='my_sogrid_pinterest_author_box';
					ob_start();
					?>
					#<?php echo $my_id?> .<?php echo $css_class?>{
					 border-top-color:<?php echo $options[$network][$key];?> !important;
					}
					<?php 
					$css=ob_get_clean();
					$added_css.=$css;	
				break;
				case 'author_box_name_color':
					$css_class='my_sogrid_pinterest_name';
					ob_start();
					?>
					#<?php echo $my_id?> .<?php echo $css_class?>{
					 color:<?php echo $options[$network][$key];?> !important;
										}
					<?php 
					$css=ob_get_clean();
					$added_css.=$css;
				break;	 	
				case 'author_box_group_color':
					$css_class='my_sogrid_pinterest_board';
					ob_start();
					?>
					#<?php echo $my_id?> .<?php echo $css_class?>{
						color:<?php echo $options[$network][$key];?> !important;
															}
					<?php 
					$css=ob_get_clean();
					$added_css.=$css;
				break;
				case 'author_box_group_hover_color':
					$css_class='my_sogrid_pinterest_board';
					ob_start();
					?>
					#<?php echo $my_id?> .<?php echo $css_class?> :hover{
						color:<?php echo $options[$network][$key];?> !important;
																			}
					<?php 
					$css=ob_get_clean();
					$added_css.=$css;
				break;				
				case 'background_author_color':
					$css_class='my_sogrid_pinterest_author_box';
					ob_start();
					?>
					#<?php echo $my_id?> .<?php echo $css_class?>{
						background-color:<?php echo $options[$network][$key];?> !important;
					}
					<?php 
					$css=ob_get_clean();
					$added_css.=$css;		
				break;	
				case 'share_icon_color':
					$css_class='my_social_share_link_twitter';
					ob_start();
					?>
					#<?php echo $my_id?> .<?php echo $css_class?> i{
						color:<?php echo $options[$network][$key];?> !important;
					}
				<?php 	
					$css=ob_get_clean();
						$added_css.=$css;
				 							
				break;	
				case 'enable_border':
					$val_1234=$options[$network][$key];
					if(!$val_1234){
						$my_has_disabled_borders[]=$network;
						$css_class='my_social_'.$network.'_inner';
						$css_class_1='my_so_grid_'.$network.'_thumb';
						ob_start();
						?>
						
						#<?php echo $my_id?> .<?php echo $css_class?>{
							border:none !important;
							
						}
						<?php 
						$css=ob_get_clean();
						$added_css.=$css;
					}
				break;	
				case 'border_color':
					$enable_border=$options[$network]['enable_border'];
					if($enable_border){
					$css_class='my_social_'.$network.'_inner';
					ob_start();
					?>
					#<?php echo $my_id?> .<?php echo $css_class?>{
						border-color:<?php echo $options[$network][$key];?> !important;
					}
					<?php 
					$css=ob_get_clean();
					$added_css.=$css;
					}
				break;	
				case 'background_color':
					$css_class='my_social_'.$network.'_inner';
					ob_start();
					?>
					#<?php echo $my_id?> .<?php echo $css_class?>{
						background-color:<?php echo $options[$network][$key];?> !important;
					}
					<?php 
					$css=ob_get_clean();
					$added_css.=$css;
				break;
				case 'background_share_color':
					if($network=='instagram'){
						$css_class='my_social_item_intro_instagram';
					}
					else $css_class='my_social_item_share_'.$network;
					ob_start();
					?>
					#<?php echo $my_id?> .<?php echo $css_class?>{
						background-color:<?php echo $options[$network][$key];?> !important;
					}
					<?php 
					$css=ob_get_clean();
					$added_css.=$css;
				break;		
				case 'title_color':
						$css_class='my_social_item_title_'.$network;
						ob_start();
						?>
						#<?php echo $my_id?> .<?php echo $css_class?> a , #<?php echo $my_id?> .<?php echo $css_class?>{
							color:<?php echo $options[$network][$key];?> !important;
						}
						<?php 
						$css=ob_get_clean();
						$added_css.=$css;
				break;	
				case 'title_font':
					$css_class='my_social_item_title_'.$network;
					$font=$options[$network][$key]['font'];
					$my_font_family=wp_my_sogrid_get_font_famyly($font);
					ob_start();
					?>
					#<?php echo $my_id?> .<?php echo $css_class?> , #<?php echo $my_id?> .<?php echo $css_class?> a{
						<?php if($font!='default'){?>
						font-family:"<?php echo $my_font_family?>" , serif !important;
						<?php }?>
						font-size:<?php echo $options[$network][$key]['font_size']?> !important;
						font-style:<?php echo $options[$network][$key]['font_style']?> !important;
						font-weight:<?php echo $options[$network][$key]['font_weight']?> !important;
					}
					<?php 
					$css=ob_get_clean();
					$added_css.=$css;
					if($font!='default'){
						wp_my_sogrid_add_font($sogrid_id, $options[$network][$key]);
					}	
				break;
				case 'text_color':
					if($network=='instagram'){
						?>
						#<?php echo $my_id?> .<?php echo 'my_social_item_likes_instagram'?>, #<?php echo $my_id?> .<?php echo 'my_social_item_comments_instagram'?> {
							color:<?php echo $options[$network][$key]?> !important;
						}
						<?php 	
						$css=ob_get_clean();
						$added_css.=$css;
							
					}
					else {
					$css_class='my_social_item_text_'.$network;
					ob_start();
					?>
					#<?php echo $my_id?> .<?php echo $css_class?>{
					color:<?php echo $options[$network][$key]?> !important;
					}
					<?php 
					$css=ob_get_clean();
					$added_css.=$css;
					}	
				break;		
				case 'text_font':
					if($network=='instagram'){
						$font=$options[$network][$key]['font'];
						$my_font_family=wp_my_sogrid_get_font_famyly($font);
						ob_start();
						?>
						#<?php echo $my_id?> .<?php echo 'my_social_item_date_instagram'?>, #<?php echo $my_id?> .my_font_insatgram {
											<?php if($font!='default'){?>
													font-family:"<?php echo $my_font_family?>" , serif !important;
											<?php }?>
													
													font-style:<?php echo $options[$network][$key]['font_style']?> !important;
													font-weight:<?php echo $options[$network][$key]['font_weight']?> !important;
											}
											<?php 
											
											
					}else {
						$css_class='my_social_item_text_'.$network;
					
					$font=$options[$network][$key]['font'];
					$my_font_family=wp_my_sogrid_get_font_famyly($font);
					ob_start();
					
					#<?php echo $my_id?> .<?php echo $css_class?>{
					<?php if($font!='default'){?>
							font-family:"<?php echo $my_font_family?>" , serif !important;
					<?php }?>
							font-size:<?php echo $options[$network][$key]['font_size']?> !important;
							font-style:<?php echo $options[$network][$key]['font_style']?> !important;
							font-weight:<?php echo $options[$network][$key]['font_weight']?> !important;
					}
					<?php 
					if($network=='twitter'){
						?>
						#<?php echo $my_id?> .<?php echo $css_class?> a{
					<?php if($font!='default'){?>
							font-family:"<?php echo $my_font_family?>" , serif !important;
					<?php }?>
							font-size:<?php echo $options[$network][$key]['font_size']?> !important;
							font-style:<?php echo $options[$network][$key]['font_style']?> !important;
							font-weight:<?php echo $options[$network][$key]['font_weight']?> !important;
					}
						<?php 
					}
					$css=ob_get_clean();
					$added_css.=$css;
					}
					if($font!='default'){
						wp_my_sogrid_add_font($sogrid_id, $options[$network][$key]);
					}	
					
				break;	
				case 'icon_color':
					$css_class='my_social_item_icon_font_'.$network;
					ob_start();
					?>
					#<?php echo $my_id?> .<?php echo $css_class;?>{
					color:<?php echo $options[$network][$key];?> !important;
					}
					<?php 
					$css=ob_get_clean();
					$added_css.=$css;
				break;
				case 'date_color':
					$css_class='my_social_item_date_'.$network;
					ob_start();
					?>
					#<?php echo $my_id?> .<?php echo $css_class;?>{
						color:<?php echo $options[$network][$key];?> !important;
					}
					<?php 
					$css=ob_get_clean();
					$added_css.=$css;
				break;
				case 'link_color':
					$css_class='my_social_item_text_'.$network;
					ob_start();
					?>
					#<?php echo $my_id?> .<?php echo $css_class;?> a{
						color:<?php echo $options[$network][$key];?> !important;
					}
					<?php 
					$css=ob_get_clean();
					$added_css.=$css;						
				break;						 	 	
				case 'link_hover_color':
					$css_class='my_social_item_text_'.$network;
					ob_start();
					?>
					#<?php echo $my_id?> .<?php echo $css_class;?> a:hover{
						color:<?php echo $options[$network][$key];?> !important;
					}
					<?php 
					$css=ob_get_clean();
					$added_css.=$css;						
					break;
				case 'share_color':
					if($network=='facebook'){
						$css_class='my_social_share_link_'.$network;
					}else if($network=='google'){
						ob_start();
						?>
						#<?php echo $my_id;?> .my_sogrid_plusone_tweek span,
						#<?php echo $my_id;?> .my_sogrid_share_<?php echo $network?> a i,
						#<?php echo $my_id;?> .my_sogrid_share_<?php echo $network?> a span,
						#<?php echo $my_id;?> .my_sogrid_plusone_tweek_1 span ,
						#<?php echo $my_id;?> .my_sogrid_plusone_tweek_1 i
						{
							color:<?php echo $options[$network][$key];?> !important;
						}
						<?php 
						$css=ob_get_clean();
						$added_css.=$css;
					}else if($network=='twitter'){
						ob_start();
						?>
						#<?php echo $my_id?> .my_social_share_link_twitter i{
							color:<?php echo $options[$network][$key];?> !important;
						}
						<?php 
						$css=ob_get_clean();
						$added_css.=$css;
					}
					if($network=='facebook'){
							
					ob_start();
					?>
					#<?php echo $my_id?> .<?php echo $css_class;?>{
						color:<?php echo $options[$network][$key];?> !important;
					}
					<?php 
					
					$css=ob_get_clean();
					$added_css.=$css;
					}	
				break;	 	
				case 'share_hover_color':
					if($network=='facebook'){
						$css_class='my_social_share_link_'.$network;
					}
					else if($network=='google'){
						ob_start();
						?>
						#<?php echo $my_id;?> .my_sogrid_plusone_tweek span:hover,
						#<?php echo $my_id;?> .my_sogrid_share_<?php echo $network?> a:hover i,
						#<?php echo $my_id;?> .my_sogrid_share_<?php echo $network?> a:hover span,
						#<?php echo $my_id;?> .my_sogrid_plusone_tweek_1:hover span,
						#<?php echo $my_id;?> .my_sogrid_plusone_tweek_1:hover i
						
						{
							color:<?php echo $options[$network][$key];?>!important;
						}
						<?php 
						$css=ob_get_clean();
						$added_css.=$css;
					}else if($network=='twitter'){
						ob_start();
						?>
						#<?php echo $my_id?> .my_social_share_link_twitter:hover i{
							color:<?php echo $options[$network][$key];?> !important;
						}
						<?php 
						$css=ob_get_clean();
						$added_css.=$css;
					}
					if($network=='facebook'){
					ob_start();
					?>
					#<?php echo $my_id?> .<?php echo $css_class;?>:hover{
						color:<?php echo $options[$network][$key];?> !important;
					}
					<?php 
					$css=ob_get_clean();
					$added_css.=$css;	
					}
				break;	
				case 'share_number_color':
					if($network=='twitter'){
						ob_start();
						?>
						#<?php echo $my_id?> .my_social_share_span_twitter{
							color:<?php echo $options[$network][$key]?> !important;
						}
						<?php 
						$css=ob_get_clean();
						$added_css.=$css;
					}
				break;	 
				case  'share_button_background_color':
					if($network=='google'){
						ob_start();
						?>
						#<?php echo $my_id;?> .my_sogrid_plusone_tweek , #<?php echo $my_id;?> .my_sogrid_plusone_tweek_1 ,
						#<?php echo $my_id;?> .my_sogrid_share_<?php echo $network?>{
						background-color:<?php echo $options[$network][$key]?> !important;
					}
					<?php 
					$css=ob_get_clean();
					$added_css.=$css;
					}
				break;
				case 'share_button_border_color':
					if($network=='google'){
						ob_start();
						?>
						#<?php echo $my_id;?> .my_sogrid_plusone_tweek , #<?php echo $my_id;?> .my_sogrid_plusone_tweek_1 ,
						#<?php echo $my_id;?> .my_sogrid_share_<?php echo $network?>{
							border-color:<?php echo $options[$network][$key]?> !important;
							}
					<?php 
						$css=ob_get_clean();
						$added_css.=$css;
					}
				break;	 	
				case 'share_line_color':
					$css_clas='my_social_item_share_'.$network;
					ob_start();
					?>
					#<?php echo $my_id;?> .<?php echo $css_clas?>{
						border-top-color:<?php echo $options[$network][$key]?> !important;
					}
					<?php 
					$css=ob_get_clean();
					$added_css.=$css;
				break;	 
				default:
				break;	
			}
		}
		}
	}
	if(in_array('tumblr', $networks)){
		$css_class='my_social_item_title_tumblr';
					ob_start();
					?>
					#<?php echo $my_id?> .<?php echo $css_class?> , #<?php echo $my_id?> .<?php echo $css_class?> a{
						
						line-height:26px !important;
					}
		<?php 
		$css=ob_get_clean();
		$added_css.=$css;
	}
	global $my_sogrid_disable_borders_12345;
	
	if(!empty($my_has_disabled_borders)){
		$my_sogrid_disable_borders_12345[$sogrid_id]=1;
		
		foreach($networks as $key=>$val){
			if(!in_array($val, $my_has_disabled_borders)){
					$css_class='my_social_'.$val.'_inner';	
				ob_start();
						?>
						
						#<?php echo $my_id?> .<?php echo $css_class?>{
							border:none !important;
							
						}
						<?php 
						$css=ob_get_clean();
						$added_css.=$css;
						
			}
		}
	}
	return $added_css;
}
/**
 * get sogrid font
 * @param unknown $font_family
 * @return mixed
 */
function wp_my_sogrid_get_font_famyly($font_family){
	$font_family=str_replace('+', ' ', $font_family);
	return $font_family;
}
function wp_my_sogrid_get_google_fonts_link(){
	//global $my_woo_page_included_fonts_1234;
	//global $my_woo_image_mapper_fonts_include;
	global $my_sogrid_page_included_fonts_1234;
	global $my_sogrid_fonts_include;
	if(empty($my_sogrid_fonts_include))return false;
	$families=array();

	foreach($my_sogrid_fonts_include as $k=>$v){
		$family=wp_my_sogrid_get_font_famyly($k);
		$family_key_added=$k;

		if(!empty($v['variants'])){
			$my_variants=$v['variants'];
			foreach($my_variants as $k1=>$v1){
				$my_new_key=$family_key_added.$v1;
				if(isset($my_sogrid_page_included_fonts_1234[$my_new_key]))
					unset($my_variants[$k1]);
			}
			if(!empty($my_variants)){
				$families[]=$family.':'.implode(",",$my_variants);
			}else {
				if(!isset($my_sogrid_page_included_fonts_1234[$family_key_added])){
					$families[]=$family;
				}
			}
			foreach($v['variants'] as $k1=>$v1){
				$my_var=$v1;
				$my_sogrid_page_included_fonts_1234[$family_key_added.'_'.$my_var]=1;
			}
		}else {
			if(!isset($my_sogrid_page_included_fonts_1234[$family_key_added])){
				$families[]=$family;
			}
		}
		$my_sogrid_page_included_fonts_1234[$family_key_added]=1;

	}
	if(!empty($families)){
		$ret='http'.(is_ssl()?'s':'');
		$ret.='://fonts.googleapis.com/css?family=';
		$ret.=urlencode(implode("|",$families));
		return $ret;
	}else return false;
}
/**
 * Add font
 * @param unknown $id
 * @param unknown $font
 * @return boolean
 */
function wp_my_sogrid_add_font($id,$font){
	//global $my_woo_checked_fonts;
	//global $my_woo_immaper_font;
	global $my_sogrid_checked_fonts;
	//global $my_woo_immaper_fonts_new;
	global $my_sogrid_fonts_new;
	global $wp_my_sogrid_fonts;
	if(empty($my_sogrid_fonts_new)&&$my_sogrid_checked_fonts==1)return false;
	if(empty($my_sogrid_fonts_new)){
		$my_sogrid_fonts_new=get_option('br0_sogrid_my_admin_web_fonts');//my_woo_image_mapper_get_google_fonts(true);
		$my_sogrid_checked_fonts=1;
		if(empty($my_sogrid_fonts_new))return false;
	}

	//global $my_so;
	global $my_sogrid_fonts_include;
	$font_family=$font['font'];
	if($font_family=='default')return true;
	$font_size=$font['font_size'];
	$font_style=$font['font_style'];
	$font_weight=$font['font_weight'];
	//echo $font_family.' '.$font_style.'<br/>';
	$search_arr=array();
	if($font_style=='italic'){
		$search_arr[]=$font_style;
		$search_arr[]=$font_weight.$font_style;
	}else if($font_style=='normal'){
		$search_arr[]='regular';
		$search_arr[]=$font_weight;

	}
	/*echo '<pre>';
	 print_r($my_woo_immaper_fonts);
	echo '</pre>';
	*/

	if(isset($my_sogrid_fonts_new[$font_family])){
		$google_font=$my_sogrid_fonts_new[$font_family];
		//print_r($google_font);
		$google_variants=$google_font['variants'];
		//print_r($google_variants);
		if(!isset($my_sogrid_fonts_include[$font_family])){
			//echo 'Not set';
			$my_sogrid_fonts_include[$font_family]=array(
					'added'=>true,
					'variants'=>array()
			);
			if(!empty($search_arr)){

				//print_r($search_arr);
				foreach($search_arr as $k=>$v){
					//if($v=='regular')continue;
					if(in_array($v,$google_variants)){
						$my_sogrid_fonts_include[$font_family]['variants'][]=$v;
					}
				}
			}
		}else {
			if(!empty($search_arr)){
				foreach($search_arr as $k=>$v){
					//if($v=='regular')continue;
					if(in_array($v,$google_variants)){
						if(!in_array($v,$my_sogrid_fonts_include[$font_family]['variants'])){
							$my_sogrid_fonts_include[$font_family]['variants'][]=$v;
						}
					}
				}
			}
		}

	}
	return true;
}
/*
 * end changes
 */
/*
 * changes 1.23.2015.
 */
function wp_my_sogrid_get_google_fonts($json=false){
	$my_get_file=false;
	$current_date = getdate(date("U"));
	$current_date = $current_date['weekday'] . $current_date['month'] . $current_date['mday'] . $current_date['year'];
	if (!get_option('br0_sogrid_my_admin_webfonts')) {
		$file_get = wp_remote_fopen("http://www.shindiristudio.com/responder/fonts.txt");
		if (strlen($file_get) > 100) {
			add_option('br0_sogrid_my_admin_webfonts', $file_get);
			add_option('br0_sogrid_my_admin_webfonts_date', $current_date);
			$my_get_file=true;	
		}
	}else {
		if (get_option('br0_sogrid_my_admin_webfonts_date') != $current_date){
			$file_get = wp_remote_fopen("http://www.shindiristudio.com/responder/fonts.txt");
			if (strlen($file_get) > 100) {
				update_option('br0_sogrid_my_admin_webfonts', $file_get);
				update_option('br0_sogrid_my_admin_webfonts_date', $current_date);
				$my_get_file=true;
			}	
		}
	}
	$fontsjson = get_option('br0_sogrid_my_admin_webfonts');
	$decode = json_decode($fontsjson, true);
	$webfonts = array();
	$webfonts['default'] = 'Default' . '|' . 'def';
	$my_web_fonts=array();
	if(!empty($decode['items'])){
		foreach ($decode['items'] as $key => $value) {
			$item_family = $decode['items'][$key]['family'];
			$item_family_trunc = str_replace(' ', '+', $item_family);
			$webfonts[$item_family_trunc] = $item_family . '|' . $item_family_trunc;
			$my_web_fonts[$item_family_trunc]=$value;
		}
	}
	 
	update_option("br0_sogrid_my_admin_web_fonts",$my_web_fonts);
	if ($json)
		return $fontsjson;
	return $webfonts;
}
/*
 * end
 */
/**
 * Debug function
 * @param unknown $t
 * @param unknown $o
 */
function wp_my_sogrid_debug_object($t,$o){
	global $my_wp_sogrid_debug;
	if($my_wp_sogrid_debug){
		?>
		<div class="my_debug">
			<h4><?php echo $t; ?></h4>
			<pre><?php print_r($o);?></pre>
		</div>
		<?php 
	}
}
/**
 * Format k number
 * @param unknown $n
 * @return unknown|string
 */
function wp_my_sogrid_format_k_number($n){
	if($n<1000){
		return $n;
	}else if($n>1000){
		$m=floor((double)$n/1000000);
		if($m>0){
			$k=round(($n-$m*1000000)/100000,0);
			if($k!=0)$str=$m.'.'.$k;
			else $str=$m;
			return $str.__("M","wp_my_sogrid_domain");
		}else {
			$k=floor((double)$n/1000);
			$s=round(($n-($k*1000))/100,0);
			//echo 'n='.$n.' k='.$k.' s='.$s;
			if($s!=0)$str=$k.'.'.$s;
			else $str=$k;
			return $str.__("K","wp_my_sogrid_domain");
		}
	}
}
/**
 * Linkify text
 * @param unknown $text
 */
function wp_my_sogrid_linkify_text($text,$target='_blank'){
	$text_arr=explode(" ",$text);
	$new_text="";
	if(!empty($text_arr)){
		foreach($text_arr as $k=>$v){
			if(!empty($v)){
				if(wp_my_so_grid_is_link($v)){
					$new_text.=' <a href="'.$v.'" target="'.$target.'">'.$v.'</a>';
					
				}else $new_text.=' '.$v;
			}
		}
		return $new_text;
	}else {
		if(wp_my_so_grid_is_link($text)){
			$new_text=' <a href="'.$v.'" target="'.$target.'">'.$text.'</a>';
				
		}else $new_text=$text;
		
	}
	return $new_text;
	
}
/**
 * Check to see if we have a link
 * @param unknown $url
 */
function wp_my_so_grid_is_link($url){
	if(strpos($url, 'http://')===0)return true;
	if(strpos($url, 'https://')===0)return true;
	if(strpos($url, 'www.')===0)return true;
	return false;
	
}
/**
 * Delete SoGrid
 * @param unknown $id
 * tested works fine
 */
function wp_my_so_grid_delete_sogrid($id){
	global $wpdb;
	global $wp_my_sogrid_table_object;
	global $wp_my_sogrid_table_object_meta;
	$query="DELETE FROM ".$wp_my_sogrid_table_object_meta." WHERE ";
	$query.="object_id=%d";
	$query=$wpdb->prepare($query, $id);
	//echo 'DELETE Query '.$query;
	$wpdb->query($query);
	$query="DELETE FROM ".$wp_my_sogrid_table_object." WHERE ";
	$query.="ID=%d";
	$query=$wpdb->prepare($query, $id);
	//echo 'delete query '.$query;
	$wpdb->query($query);
	wp_my_sogrid_delete_cache_files($id);
	
}
/**
 * Delete cache files when delete sogrid
 * @param unknown $sogrid_id
 * tested works fine but enable delte file for live sites
 * 
 */
function wp_my_sogrid_delete_cache_files($sogrid_id){
	$dir=MY_WP_SOGRID_DIRNAME.'tmp/cache/'.$sogrid_id.'/';
	if(is_writable($dir)){
		$during_cache=$dir.'my_caching.php';
		$cache_file=$dir.'my_cache_file.php';
		/**
		 * If process is blocked then dont truncate file
		 * if some other process has been written to file
		 */
		$c_fp=fopen($cache_file,'c');
		$has_lock=true;
		$try=1;
		$delete_files=array();
		/**
		 * Check for lock only once
		 */
		if(flock($c_fp, LOCK_EX)){
			$fp_during=fopen($during_cache, 'w');
			fwrite($fp_during,1);
			fclose($fp_during);
			$data_saved_str=require $cache_file;
			$data_saved=maybe_unserialize($data_saved_str);
			
			if(!empty($data_saved['saved'])){
				foreach($data_saved['saved'] as $k=>$v){
					$f=$v['file'];
					$new_file=$dir.$f;
					if(file_exists($new_file)){
						//echo 'unlink '.$new_file.'<br/>';
						unlink($new_file);
					}
				}
			}
			$new_file=$dir.'sogrid.php';
			if(file_exists($new_file)){
				//echo 'unlink '.$new_file.'<br/>';
				unlink($new_file);
			}
			flock($c_fp, LOCK_UN);
			fclose($c_fp);
			unlink($during_cache);
		}
		//echo 'unlink '.$cache_file;
		//echo 'remove dir '.$dir;
		unlink($cache_file);
		///rmdir($dir);
	}	
}
/**
 * When saving immaper invalidate cache
 * @param unknown $sogrid_id
 */
function wp_my_so_grid_invalidate_cache($sogrid_id){
	$dir=MY_WP_SOGRID_DIRNAME.'tmp/cache/'.$sogrid_id.'/';
	if(is_writable($dir)){
		$during_cache=$dir.'my_caching.php';
		$cache_file=$dir.'my_cache_file.php';
		/**
		 * If process is blocked then dont truncate file
		 * if some other process has been written to file
		 */
		$c_fp=fopen($cache_file,'c');
		$has_lock=true;
		$try=1;
		/**
		 * Check for lock only once
		 */
		if(flock($c_fp, LOCK_EX)){
			$fp_during=fopen($during_cache, 'w');
			fwrite($fp_during,1);
			fclose($fp_during);
			
			$data_saved_str=require $cache_file;
			$data_saved=maybe_unserialize($data_saved_str);
			$data_saved['lifetime']=0;
			//echo 'Data saved '.print_r($data_saved);
			ftruncate($c_fp, 0);
			rewind($c_fp);
			$str="<?php if(!defined('ABSPATH'))die('');\n";
			$str.="ob_start();?>";
			$str.=maybe_serialize($data_saved);
			$str.="<?php ";
			$str.="\$s=ob_get_clean();\n ";
			$str.="return \$s;\n ";
			fwrite($c_fp,$str);
			fflush($c_fp);
			flock($c_fp, LOCK_UN);
			fclose($c_fp);
			unlink($during_cache);
		}
	}
	
}
/**
 * Get options
 * @return unknown|Ambigous <mixed, boolean>
 */
function wp_my_sogrid_get_options($force=false){
	global $my_wp_sogrid_options_name;
	global $my_wp_sogrid_options;
	global $my_wp_sogrid_fetched_options;
	if($my_wp_sogrid_fetched_options&&!$force){
		return $my_wp_sogrid_options;
	}
	$arr=get_option($my_wp_sogrid_options_name);
	if(empty($arr))$arr=array();
	$my_wp_sogrid_fetched_options=true;
	$my_wp_sogrid_options=$arr;
	return $my_wp_sogrid_options;
}
/**
 * Get options by key
 * @param unknown $key
 * @return Ambigous <>
 */
function wp_my_sogrid_get_option_by_key($key){
	global $my_wp_sogrid_options_name;
	global $my_wp_sogrid_options;
	global $my_wp_sogrid_fetched_options;
	if(!$my_wp_sogrid_fetched_options){
		wp_my_sogrid_get_options();
	}
	if(isset($my_wp_sogrid_options[$key])){
		return $my_wp_sogrid_options[$key];
	}else return false;
	
}
/**
 * Update options
 * @param unknown $arr
 */
function wp_my_sogrid_update_options($arr){
	global $my_wp_sogrid_options_name;
	global $my_wp_sogrid_options;
	global $my_wp_sogrid_fetched_options;
	global $wp_my_sogrid_genereal_plugin_options;
	if(!$my_wp_sogrid_fetched_options){
		wp_my_sogrid_get_options();
	}
	if(!empty($arr)){
		foreach($arr as $k=>$v){
			if(isset($wp_my_sogrid_genereal_plugin_options[$k])){
				$my_wp_sogrid_options[$k]=$v;
			}
		}
		update_option($my_wp_sogrid_options_name, $my_wp_sogrid_options);
	}
	
}
/*
 * end
 */
/**
 * Get object meta and
 * @param $id
 */
function wp_my_sogrid_get_object_and_meta($id){
	$ret=array();
	$ret['active_social']=wp_my_so_grid_get_object_meta($id,'networks');
	$networks=$ret['active_social'];
	if(!empty($networks)){
		foreach($networks as $k=>$v){
			$ret[$v]=array();
			$var=wp_my_so_grid_get_object_meta($id,$v);
			$ret[$v]=$var;
		}
	}
	/*
	 * changes 1.20.105. general options
	 */
	$ret['general_options']=wp_my_so_grid_get_object_meta($id, 'general_options');
	/*
	 * end
	 */
	return $ret;
}
/**
 * Get object meta
 * @param $object_id
 * @param $meta_key
 */
function wp_my_so_grid_get_object_meta($object_id,$meta_key){
	global $wpdb;
	global $my_wp_sogrid_debug;
	global $my_wp_sogrid_debug_data;
	global $wp_my_sogrid_table_object;
	global $wp_my_sogrid_table_object_meta;
	$query="SELECT meta_value FROM ".$wp_my_sogrid_table_object_meta;
	$query.=" WHERE object_id=%d AND meta_key=%s";
	$query=$wpdb->prepare($query,$object_id,$meta_key);
	$var=$wpdb->get_var($query);
	$var=maybe_unserialize($var);
	$var=stripslashes_deep($var);
	if($my_wp_sogrid_debug){
		$my_wp_sogrid_debug_data['queries'][]=array(
			'object_id'=>$object_id,
			'meta_key'=>$meta_key,
			'var'=>$var,
			
		);
	}
	return $var;
	
	
}
/**
 * Save meta
 * @param $object_id
 * @param $meta_key
 * @param $val
 */
function wp_my_sogrid_save_meta($object_id,$meta_key,$val){
	global $wpdb;
	global $wp_my_sogrid_table_object;
	global $wp_my_sogrid_table_object_meta;
	global $my_wp_sogrid_debug;
	global $my_wp_sogrid_debug_data;
	
	$val=maybe_serialize($val);
	$id=wp_my_sogrid_get_meta_id($object_id,$meta_key);
	if(empty($id)){
		
		$wpdb->insert($wp_my_sogrid_table_object_meta,array(
			'object_id'=>$object_id,
			'meta_key'=>$meta_key,
			'meta_value'=>$val
		));
		$id=$wpdb->insert_id;
		if($my_wp_sogrid_debug){
			$my_wp_sogrid_debug_data['queries'][]=array(
				'insert'=>'insert',
				'object_id'=>$object_id,
				'meta_id'=>$id	
			);
		}
		return $id;
	}else {
		$wpdb->update($wp_my_sogrid_table_object_meta,array(
			'meta_value'=>$val
		),array('meta_id'=>$id));
		//$id=$wpdb->insert_id;
		if($my_wp_sogrid_debug){
			$my_wp_sogrid_debug_data['queries'][]=array(
				'update'=>'update',
				'object_id'=>$object_id,
				'meta_id'=>$id	
			);
		}
		return $id;
	}
}
/**
 * Get object meta id
 * @param $object_id
 * @param $meta_key
 */
function wp_my_sogrid_get_meta_id($object_id,$meta_key){
	global $wp_my_sogrid_table_object;
	global $wp_my_sogrid_table_object_meta;
	global $my_wp_sogrid_debug;
	global $my_wp_sogrid_debug_data;
	
	global $wpdb;
	$query="SELECT meta_id FROM ".$wp_my_sogrid_table_object_meta;
	$query.=" WHERE object_id=%d AND meta_key=%s";
	$query=$wpdb->prepare($query,$object_id,$meta_key);
	$var=$wpdb->get_var($query);
	if($my_wp_sogrid_debug){
		$my_wp_sogrid_debug_data['queries']['get_object_title']=array(
			'query'=>$query,
			'var'=>$var,
		);
	}
	return $var;
}
/**
 * Generate array of defined options per social
 * networks 
 * @param unknown_type $networks
 * @return array[$network]=valid options:
 */
function wp_my_sogrid_generate_so_options($networks=array()){
	global $wp_my_sogrid_general_options;
	$ret=array();
	foreach($networks as $k=>$v){
		$ret[$v]=array();
		$file=MY_WP_SOGRID_SOCIAL_MODULES_DIRNAME.$v.'/options.php';
		require_once $file;
		$name_1='wp_my_sogrid_'.$v.'_options';
		//$name_2='wp_my_sogrid_'.$v.'_options_tooltips';
		$name_3='wp_my_sogrid_'.$v.'_item_options';
		global $$name_1;
				//global $$name_2;
		global $$name_3;
		$options=$$name_1;
		$added_options=$$name_3;
		if(!empty($options)){
			foreach($options as $k1=>$v1){
				$ret[$v][$v1]=$wp_my_sogrid_general_options[$v1];
			}
		}
		if(!empty($added_options)){
			foreach($added_options as $k1=>$v1){
				$ret[$v][$k1]=$v1;
			}
		}
		
	}
	return $ret;
}
/**
 * 
 * @param $val
 * @param $field
 */
function wp_my_sogrid_check_field($val,$field){
	
	
}
/**
 * 
 * @param $val
 * @param $field
 */
function wp_my_sogrid_mapper_check_on_off($val,$field){
	if(empty($val))return 0;
	
	if(!in_array($val,array('true','false'))){
		$val=$field['default'];
	}
	if($val=='true')return 1;
	else if($val=='false')return 0;
	
}

/**
 * Save so grid function
 * @param $data
 * @param $valid_params
 */
function wp_my_sogrid_save_sogrid($data,$networks,$valid_params=array()){
	global $wp_my_sogrid_id;
	$wp_my_sogrid_id='';
	global $wpdb;
	global $wp_my_sogrid_table_object;
	global $wp_my_sogrid_table_object_meta;
	global $my_wp_sogrid_debug;
	global $my_wp_sogrid_debug_data;
	
	$send_sogrid_id=$data['sogrid_id'];
	$sogrid_title=$data['image_mapper_title'];
	/*$networks=$data['my_active_networks'];
	if(empty($networks)){
		return __("Please select at least one social Netowrk.","wp_my_sogrid_domain");
	}*/
	$can_save=true;
	/**
	 * Check required options
	 */
	foreach($networks as $k=>$v){
		$params=$valid_params[$v];
		foreach($params as $k1=>$v1){
			if(isset($v1['required'])&&($v1['required'])){
				$key=$v.'_'.$k1;
				$val=$data[$key];
				if(empty($val)){
					$msg=__("Field {1} is required !","wp_my_sogrid_domain");
					$msg=str_replace('{1}',$v1['title'],$msg);
					return $msg;
				}
			}
		}
	}
	if(empty($sogrid_title)){
		$sogrid_title=__("Default SoGrid Title","wp_my_sogrid_domain");
	}
	if(empty($send_sogrid_id)){
		$wpdb->insert($wp_my_sogrid_table_object,array(
			'title'=>$sogrid_title
		));
		$wp_my_sogrid_id=$wpdb->insert_id;
	}else {
		$wpdb->update($wp_my_sogrid_table_object,array(
				'title'=>$sogrid_title
		),array('ID'=>$send_sogrid_id));
		$wp_my_sogrid_id=$send_sogrid_id;
		/**
		 * Invalid cache when saving sogrid again
		 */
		wp_my_so_grid_invalidate_cache($send_sogrid_id);	
	}
	/**
	 * Save networks data
	 */
	/**
	 * Save networks associated with so grid
	 */
	wp_my_sogrid_save_meta($wp_my_sogrid_id,'networks',$networks);
	/*
	 *changes font saving
	 *1.25.2015. 
	 */
	foreach ($networks as $k=>$v){
		$network_data=array();
	
		$params=$valid_params[$v];
		foreach($params as $k1=>$v1){
			$key=$v.'_'.$k1;
			if(isset($data[$key])){
				$val=$data[$key];
			}else $val='';
			if($v1['type']=='on_off'){
				$val=wp_my_sogrid_mapper_check_on_off($val,$v1);
			}else if($v1['type']=='font'){
				$val=array();
				foreach($v1['default'] as $k2=>$v2){
					$font_key=$k2.$key;
					
					$val[$k2]=$data[$font_key];
				}
				
			}
			$network_data[$k1]=$val;
		}
		if($my_wp_sogrid_debug){
			$my_wp_sogrid_debug_data[$v]['data']=$network_data;
			$my_wp_sogrid_debug_data[$v]['valid_options']=$valid_params[$v];
			
		}
		wp_my_sogrid_save_meta($wp_my_sogrid_id,$v,$network_data);
	}
	/*
	 *changes 1.20.2015. save general options 
	 */
	global $wp_my_sogrid_genereal_sogrid_options;
	$my_general_options=array();
	foreach($wp_my_sogrid_genereal_sogrid_options as $k=>$v1){
		$key='my_general_option_'.$k;
		if(isset($data[$key])){
			$val=$data[$key];
		}else $val='';
		if($v1['type']=='on_off'){
			$val=wp_my_sogrid_mapper_check_on_off($val,$v1);
		}
		$my_general_options[$k]=$val;
	}
	wp_my_sogrid_save_meta($wp_my_sogrid_id, 'general_options', $my_general_options);
	/*
	 * end
	 */
	return true;
}
/**
 * Get object title
 * @param unknown_type $id
 * @return string
 */
function wp_my_sogrid_get_object_title($id){
	global $wpdb;
	global $wp_my_sogrid_table_object;
	global $my_wp_sogrid_debug;
	global $my_wp_sogrid_debug_data;
	
	$query="SELECT title FROM ".$wp_my_sogrid_table_object;
	$query.=" WHERE ID=%d";
	$query=$wpdb->prepare($query,$id);
	$var=$wpdb->get_var($query);
	if($my_wp_sogrid_debug){
		$my_wp_sogrid_debug_data['queries']['get_object_title']=array(
			'query'=>$query,
			'var'=>$var,
		);
	}
	$var=stripslashes($var);
	return $var;
	
}
/**
 * Is exist so grid object
 * @param unknown_type $id
 */
function wp_my_sogrid_is_exist_object($id){
	global $wpdb;
	global $wp_my_sogrid_table_object;
	global $wp_my_sogrid_table_object_meta;
	global $my_wp_sogrid_debug;
	global $my_wp_sogrid_debug_data;
	$query="SELECT COUNT(*) AS num FROM ".$wp_my_sogrid_table_object." ";
	$query.="WHERE id=%d";
	$query=$wpdb->prepare($query,$id);
	$var=$wpdb->get_var($query);
	if($my_wp_sogrid_debug){
		$my_wp_sogrid_debug_data['queries']['is_exists_object']=array(
			'query'=>$query,
			'var'=>$var,
		);
	}
	return $var;
	
}	
/**
 * get share cache new
 * @param unknown $network
 * @param unknown $post_id
 */
function wp_my_sogrid_get_post_cached_shares($network,$post_id){
	global $my_has_sogrid_share_cache_12345;
	global $share_cache_12345;
	if($my_has_sogrid_share_cache_12345===false)return false;
	if(isset($share_cache_12345[$network][$post_id])){
		return $share_cache_12345[$network][$post_id];
	}else return false;
}
/**
 * Get cache objects for shares
 * @param unknown $sogrid_id
 * @return multitype:|boolean
 */
function wp_my_sogrid_get_cached_share($sogrid_id){
	global $my_has_sogrid_share_cache_12345;
	global $share_cache_12345;
	$share_cache_12345=array();
	$my_has_sogrid_share_cache_12345=false;
	$dir=MY_WP_SOGRID_DIRNAME.'tmp/cache/'.$sogrid_id.'/';
	if(is_writable($dir)){
		$during_cache=$dir.'my_caching_share.php';
		if(!file_exists($during_cache)){
			$cache_file=$dir.'sogrid.php';
			$c_fp=fopen($cache_file,'r');
			if(flock($c_fp, LOCK_SH)){
				$str=require $cache_file;
				$my_obj=array();
				if(!empty($str)){
					$my_obj=maybe_unserialize($str);
					if(count($my_obj)>0){
						$my_has_sogrid_share_cache_12345=true;
					}
				}	
					if(empty($my_obj)){
						$my_obj=array();
					}
					$share_cache_12345=$my_obj;
					return $my_obj;
				
				flock($c_fp,LOCK_UN);
			}
			fclose($c_fp);
		}else return false;	
	}
}

/**
 * 
 */
function wp_my_sogrid_save_share_cache($sogrid_id,$network,$post_id,$obj){
	$dir=MY_WP_SOGRID_DIRNAME.'tmp/cache/'.$sogrid_id.'/';
	if(is_writable($dir)){
		$during_cache=$dir.'my_caching_share.php';
		$wait=10;
		$start_time=time();
		while(file_exists($during_cache)){
			$el=time()-$start_time;
			if($el>$wait)return false;
		}
		$cache_file=$dir.'sogrid.php';
		if(file_exists($cache_file)){
			$obj_str=require $cache_file;
			
		}else $obj_str='';		
		$c_fp=fopen($cache_file,'c');
		if(flock($c_fp, LOCK_EX)){

			$fp_during=fopen($during_cache, 'w');
			fwrite($fp_during,1);
			fclose($fp_during);
			if(!empty($obj_str)){
				$obj_data=maybe_unserialize($obj_str);
			}else $obj_data=array();
			if(!isset($obj_data[$network])){
				$obj_data[$network]=array();
			}
			$obj_data[$network][$post_id]=$obj;
			$data=maybe_serialize($obj_data);
			$str="<?php if(!defined('ABSPATH'))die('');\n";
			$str.="ob_start();?>";
			$str.=$data;
			$str.="<?php ";
			$str.="\$ret=ob_get_clean();\n";
			$str.="return \$ret;";
			ftruncate($c_fp, 0);
			fwrite($c_fp,$str);
			fflush($c_fp);
			flock($c_fp, LOCK_UN);
			
			fclose($c_fp);
			if(file_exists($during_cache)){
				unlink($during_cache);
			}
		}
		
		
}
}
/**
 * Cache service request to file in cache dir
 * @param unknown_type $id
 * @param unknown_type $service
 * @param unknown_type $service_k
 */
function wp_my_sogrid_cache_request($sogrid_id,$responses,$parsed_responses,$cache_responses=false,&$cache_lifetime,$html=''){
	global $wp_my_sogrid_object_cache_time;
	/*
	 * changes 1.20.2015. remove debuging
	 */	
	$time=time();
	$dir=MY_WP_SOGRID_DIRNAME.'tmp/cache/'.$sogrid_id.'/';
	$dir_12_34=MY_WP_SOGRID_DIRNAME.'tmp/cache/';
	if(is_writable($dir_12_34)){
		if(!file_exists($dir)){
			mkdir($dir);
		}
	}else return false;
	
	if(is_writable($dir)){
		
		$during_cache=$dir.'my_caching.php';
		if(file_exists($during_cache))return false;	
		
		$cache_file=$dir.'my_cache_file.php';
		/**
		  * If process is blocked then dont truncate file
		  * if some other process has been written to file
		 */
		$c_fp=fopen($cache_file,'c');
		$has_lock=true;
		$try=1;
		/**
		 * Check for lock only once
		 */
		if(flock($c_fp, LOCK_EX)){
			/**
			  * Again check if we need to cache file	
			 */
			//$my_preview_12_12=@$_GET['my_preview_sogrid'];
			//if(!$my_preview_12_12)
			$my_do_cache_again=wp_my_sogrid_do_cache_request($sogrid_id);
			
			if(!$my_do_cache_again){	
				flock($c_fp, LOCK_UN);
				fclose($c_fp);	
				return false;			
			}
			ftruncate($c_fp, 0);
			rewind($c_fp);
			$fp_during=fopen($during_cache, 'w');
			fwrite($fp_during,1);
			fclose($fp_during);
			
			$saved=array();
			$saved_files=array();
			if($cache_responses){
				if(!empty($responses)){
				foreach($responses as $k=>$v){
					$data=$v['data'];
					$id=$v['id'];
					$service=$v['service'];
					$file=$service.'_'.$k.'.php';
					$full_file=$dir.$file;
					$saved[]=array(
						'service'=>$service,
						'id'=>$id,
						'file'=>$file
					);
					$saved_files[]=$file;
					$fp=fopen($full_file,'w');
					if (flock($fp, LOCK_EX)){
						$str="<?php if(!defined('ABSPATH'))die('');\n";
						$str.="ob_start();?>";
						$str.=$data;
						$str.=" <?php ";
						$str.="\$ret=ob_get_clean();\n";
						$str.="return \$ret;";
						fwrite($fp,$str);
						fflush($fp);
						flock($fp, LOCK_UN);
											}
					fclose($fp);						
				}
				}
				/* 2.11.2015.
				 * changed cache parsed responses
				 */
				//foreach($parsed_responses as $k=>$v){
					$data=maybe_serialize($parsed_responses);//$v['data'];
					$id='';
					$service='parsed';//$v['service'];
					$file='Parsed_all.php';
					$full_file=$dir.$file;
					$saved[]=array(
							'service'=>$service,
							'id'=>$id,
							'file'=>$file
					);
					$saved_files[]=$file;
					$fp=fopen($full_file,'w');
					if (flock($fp, LOCK_EX)){
						$str="<?php if(!defined('ABSPATH'))die('');\n";
						$str.="ob_start();?>";
						$str.=$data;
						$str.=" <?php ";
						$str.="\$ret=ob_get_clean();\n";
						$str.="return \$ret;";
						fwrite($fp,$str);
						fflush($fp);
						flock($fp, LOCK_UN);
					}
					fclose($fp);
				//}
				/*
				 * end changes
				 */
						
			}
			
			//if(!empty($html)){
				$file=$dir.'sogrid.php';
				$html_fp=fopen($file, "w");
				if(flock($html_fp, LOCK_EX)){
					$str="<?php if(!defined('ABSPATH'))die('');\n";
					$str.="ob_start();\n ?>";
					$str.=$html;
					$str.="<?php \$html=ob_get_clean();\n ";
					$str.="return \$html;";
					fwrite($html_fp,$str);
					fflush($html_fp);
					flock($html_fp, LOCK_UN);
				}
				fclose($html_fp);
				
				
			//}
			$old_files=wp_my_so_grid_get_file_from_dir($dir);
			//print_r($old_files);
			/**
			 * Delete old files
			 */
			if(!empty($old_files)){
				foreach($old_files as $k=>$v){
					if($v=='sogrid.php')continue;
					if(!in_array($v, $saved_files)){
						$u=$dir.$v;
						//echo 'Delete file '.$u;
						if(file_exists($u)){
							//echo 'File exists '.$u;
							unlink($u);
						}
					}
				}
			}
			$time=time();
			$data_saved=array();
			$data_saved['saved']=$saved;
			$data_saved['lifetime']=$time+$wp_my_sogrid_object_cache_time;
			$cache_lifetime=$time+$wp_my_sogrid_object_cache_time;
			//echo 'Data saved '.print_r($data_saved); 
			$str="<?php if(!defined('ABSPATH'))die('');\n";
			$str.="ob_start();?>";
			$str.=maybe_serialize($data_saved);
			$str.="<?php ";
			$str.="\$s=ob_get_clean();\n ";
			$str.="return \$s;\n ";
			fwrite($c_fp,$str);
			fflush($c_fp);
			flock($c_fp, LOCK_UN);
			fclose($c_fp);
			unlink($during_cache);
			return true;
		}else return false;
	}
	/*
	 * end remove echo
	 */
	
}
/**
 * Check if we do a cache
 * @param unknown $sogrid_id
 * @return boolean
 */
function wp_my_sogrid_do_cache_request($sogrid_id){
	$time=time();
	$dir=MY_WP_SOGRID_DIRNAME.'tmp/cache/'.$sogrid_id.'/';
	$cache_file=$dir.'my_cache_file.php';
	/*
	 * Cache file dont exists
	 */
	if(!file_exists($cache_file))return true;
	$during_cache=$dir.'my_caching.php';
	/**
	 * During cache exists dont cache
	 */
	if(file_exists($during_cache))return false;
	$val_str=require $cache_file;
	$val=maybe_unserialize($val_str);
	if($val['lifetime']<$time)return true;
	else return false;
	
}
/**
 * Get cached requests
 * @param unknown $sogrid_id
 */
function wp_my_sogrid_get_cached_requests($sogrid_id,$requests=false,$only_parsed=false){
	$time=time();
	$dir=MY_WP_SOGRID_DIRNAME.'tmp/cache/'.$sogrid_id.'/';
	$ret_arr=array();
	if(is_writable($dir)){
		$during_cache=$dir.'my_caching.php';
		if(file_exists($during_cache))return false;
		$cache_file=$dir.'my_cache_file.php';
		if(!file_exists($cache_file))return false;	
		$c_fp=fopen($cache_file,'r');
		$my_has=false;
		$has_lock=true;
		/*
		 * changes 1.20.2015.
		 * remove echo
		 */
		if(flock($c_fp, LOCK_SH)){
			$my_has=true;
			$saved_files_str=require $cache_file;
			//echo $saved_files_str;
			$saved_files=maybe_unserialize($saved_files_str);
			$time=time();
			//echo '<br/>Saved files ';print_r($saved_files);echo '<br/>';
			//echo 'Time '.$time;
			if($saved_files['lifetime']>$time){
				if($only_parsed){
					$my_has_found_1234=false;
					foreach($saved_files['saved'] as $k=>$v){
						$f=$v['file'];
						$service=$v['service'];
						$id=$v['id'];
						//echo 'Service '.$service.' id'.$id.'<br/>';
						//if($f=='sogrid.php')continue;
						$file=$dir.$f;
						$resp_str=require $file;
						$resp=$resp_str;
						//$ret_arr[$service][$id]=$resp;
						if($service=='parsed'){
							$my_has_found_1234=true;
							$my_data=maybe_unserialize($resp);
							$ret_arr['parsed']=array(
								'service'=>$service,
								'id'=>$id,
								'error'=>0,
								'data'=>$my_data
						);
						}
					}
					if($my_has_found_1234){
						$ret_arr['error']=0;
						$ret_arr['lifetime']=$saved_files['lifetime'];
					}else {
						$ret_arr['error']=0;
						$ret_arr['lifetime']=$saved_files['lifetime'];
	
					}
					
				}else {
				if($requests){
					if(!empty($saved_files['saved'])){
					foreach($saved_files['saved'] as $k=>$v){
						$f=$v['file'];
						$service=$v['service'];
						$id=$v['id'];
						//echo 'Service '.$service.' id'.$id.'<br/>';
						//if($f=='sogrid.php')continue;
						$file=$dir.$f;
						$resp_str=require $file;
						$resp=$resp_str;
						//$ret_arr[$service][$id]=$resp;
						if($service=='parsed'){
							$my_data=maybe_unserialize($resp);
							$ret_arr['parsed']=array(
								'service'=>$service,
								'id'=>$id,
								'error'=>0,
								'data'=>$my_data
							);
						}else {
							$ret_arr[]=array(
								'service'=>$service,
								'id'=>$id,
								'error'=>0,
								'data'=>$resp			
							);
						}
					}
				}
				}//get requests
				$html_file=$dir.'sogrid.php';
				$ret_arr['html']='';
				//if(file_exists($html_file)){
					/*$my_sogrid_share_str=file_get_contents($html_file);
					if(!empty($my_sogrid_share_str)){
						$my_sogrid_share_obj=json_decode($my_sogrid_share_str);
						if(empty($my_sogrid_share_obj)){
							$my_sogrid_share_obj=array();
						}	
						
					}else $my_sogrid_share_obj=array();
					$ret_arr['html']=$my_sogrid_share_obj;*/
					//$ret_arr['cache_']
				//}else $ret_arr['html']='';
				$ret_arr['error']=0;
				$ret_arr['lifetime']=$saved_files['lifetime'];
				}
			}else {
				$ret_arr['lifetime']=$saved_files['lifetime'];
				$ret_arr['error']=1;
				return $ret_arr;
				//$my_has=false;
			}
			
			flock($c_fp,LOCK_UN);
		}
		/*
		 * end changes
		 */
		fclose($c_fp);
		
		if(!$my_has)return false;
		else return $ret_arr;
	}
	return false;

}
/*
 * end changes
 */
/**
 * Get files from dirname
 * @param unknown $dir
 * @param unknown $remove
 */
function wp_my_so_grid_get_file_from_dir($dir,$remove=array('.','..','my_caching.php','my_cache_file.php')){
	$ret=array();
	if(function_exists('scandir')){
		$ret=scandir($dir);
		
	}else {
		$dh  = opendir($dir);
		while (false !== ($filename = readdir($dh))) {
			$ret[] = $filename;
		}
	}
	if(!empty($ret)){
		foreach($ret as $k=>$v){
			if(in_array($v, $remove))unset($ret[$k]);
	
		}
			
	}
	return $ret;
}
/**
 * Get grids
 * @param unknown_type $per_page
 * @return number|Ambigous <multitype:, stdClass>
 */
function wp_my_sogrid_get_grids($per_page=1){
	global $wpdb;
	global $wp_my_sogrid_table_object;
	global $wp_my_sogrid_table_object_meta;
	
	$page=@$_REQUEST['my_page'];
	if(!isset($page))$page=1;
	$wpdb->show_errors();
	$count_query="SELECT COUNT(*) AS num ";
	//$count_query.=" FROM ".$wpdb->prefix.'image_mapper';
	$count_query.=" FROM ".$wp_my_sogrid_table_object;
	//$count_query=$wpdb->prepare($count_query,$post_id);
	$count=$wpdb->get_var($count_query);
	//echo 'Query '.$count_query.' count '.$count;
	$ret['page']=$page;
	$ret['count']=$count;
	$ret['results']=array();
	$ret['columns']['ID']=array('width'=>'5%','title'=>__("ID","wp_my_sogrid_domain"),'order'=>false);
	$ret['columns']['name']=array('width'=>'30%','title'=>__("Name","wp_my_sogrid_domain"),'order'=>false);
	$ret['columns']['shortcode']=array('width'=>'30%','title'=>__("Shortcode","wp_my_sogrid_domain"),'order'=>false);
	$ret['actions']=array('delete'=>__("Delete","wp_my_sogrid_domain"));
	
	
	$ret['form_params']['my_page']=$page;
	$pages=ceil($count/$per_page);
	$ret['page']=$page;
	$ret['pages']=$pages;
	
	if(($page<=0) ||($page>$pages))$page=1;
	$poc=($page-1)*$per_page;
	
	if($count==0){
		return $ret;
	}
	/*$my_order_col=@$_REQUEST['my_order_col'];
	if(!isset($my_order_col)){
		$my_order_col='';
		$my_order='';
	}else {
		$my_order=@$_REQUEST['my_order'];
	}*/
	$query="SELECT ID,title FROM ".$wp_my_sogrid_table_object;
	$query.=" limit $poc,$per_page";
	$res=$wpdb->get_results($query);
	$ret_arr=array();
	if(!empty($res)){
		foreach($res as $k=>$v){
			$title=$v->title;
			$title=stripslashes($title);
			$id=$v->ID;
			$obj=new stdClass();
			$obj->ID=$id;
			$o_title=$title;
			if(strlen($o_title)>40){
				$o_title=substr($o_title,0,37).'...';
			}
			$url=admin_url('admin.php?page=my-sogrid-edit&id='.$id);
			$obj->name='<a title="'.__("Edit","wp_my_sogrid_domain").'" href="'.$url.'">'.$o_title.'</a>';
			$obj->shortcode='[sogrid id="'.$id.'"]';
			$ret_arr[]=$obj;
		}
	}
	$ret['results']=$ret_arr;
	return $ret;
	
	
}
/**
 * Str to javascript
 * @param $msg
 */
function wp_my_sogrid_format_str_to_jscript($msg){
	$msg=preg_replace('/"/ims','',$msg);
	return $msg;
}
