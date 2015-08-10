<?php

if(!defined('ABSPATH'))die('');
if(!class_exists("Class_My_Wp_SoGrid_Facebook")){
	class Class_My_Wp_SoGrid_Facebook{
		private $type='facebook';
		private $ids;
		private $api_url='https://graph.facebook.com/{id}/photos?{fields}';
		private $google_apis_url='https://ajax.googleapis.com/ajax/services/feed/load?v=1.0&num={num}&q={url}';
		private $facebook_feed_url='https://www.facebook.com/feeds/page.php?id={id}&format=rss20';		
		private $fields='id,link,from,name,picture,images,comments';
		private $get_options;
		private $get_data;
		private $sogrid_id;
		private $max_num=100;
		private $formated_data;
		private $use_facebook_sdk=false;
		private $app_id;
		private $app_key;
		public $api_errors=array();
		
		function Class_My_Wp_SoGrid_Facebook($options=array()){
			if(!empty($options)){
				foreach($options as $k=>$v){
					$this->$k=$v;
				}
			}
			/**
			 * If options is not changed set options
			 */
			if(empty($this->get_options)){
				$this->get_options=array(
						'timeout'=>20
				);
			}
			/*
			 * changes limit to 500
			 */
			/**
			 * Limit maxnum to 500 
			 */
			if($this->use_facebook_sdk){
				if($this->max_num>250)$this->max_num=250;
				//echo 'Use facebook sdk';
			}
			else if($this->max_num>500)$this->max_num=500;
			/*
			 * end changes
			 */
		}
		/**
		 * Generate url
		 * @param unknown $id
		 */
		private function generate_url($id){
			if(strpos($id,"/")!==false){
				
				$ida=explode("/",$id);
				$my_id=$ida[1];			
				$url=$this->api_url;
				$url=str_replace('{id}', $my_id, $url);
				$url=str_replace('{fields}', $this->fields, $url);
				$url.='&limit='.$this->max_num;
				
			}else {
				$url=$this->google_apis_url;
				$url=str_replace('{num}', $this->max_num, $url);
				$feed_url=$this->facebook_feed_url;
				$feed_url=str_replace('{id}', $id, $feed_url);
				$feed_url=urlencode($feed_url);
				$url=str_replace('{url}', $feed_url, $url);
			}
			return $url;
		}
		/**
		 * get likes
		 * @param unknown $post_id
		 * @return boolean
		 */
		public function get_likes($post_id){
			global $my_app_id;
			global $my_app_key;
			$my_app_id=$this->app_id;
			$my_app_key=$this->app_key;
			$dir=plugin_dir_path(__FILE__);
			require_once $dir.'libs/facebook-php-sdk/autoload.php';
			require_once $dir.'libs/my_api_calls.php';
			$likes=my_facebook_get_facebook_likes($post_id);
			$str=json_encode($likes);
			$likes=json_decode($str);
			return $likes;
			//if(is_object($likes)){
				if(isset($likes->summary->total_count)){
					return $likes->summary->total_count;
				}
			//}
			return false;
		}
		/**
		 * Call api old 
		 */
		public function call_api(){
			if($this->use_facebook_sdk){
				global $my_app_id;
				global $my_app_key;
				$my_app_id=$this->app_id;
				$my_app_key=$this->app_key;
				$dir=plugin_dir_path(__FILE__);
				require_once $dir.'libs/facebook-php-sdk/autoload.php';
				require_once $dir.'libs/my_api_calls.php';
				
				/*$ret=FacebookSession::setDefaultApplication($this->app_id, $this->app_key);
				print_r($ret);
				$session = FacebookSession::newAppSession();
				*/
			}
			if($this->use_facebook_sdk){
				//echo 'Use facebook sdk';
			}
			if(!empty($this->ids)){
				foreach($this->ids as $k=>$v){
					
					$my_type_request='feed';
					if(strpos($v,"/")!==false){
						$my_type_request='graph';
					}
					if($this->use_facebook_sdk){
						if($my_type_request=='feed'){
							$response=my_facebook_get_page_posts($v, $this->max_num);
							//echo $response;
							//print_r($response);
							$my_c=0;
							if(is_array($response)){
								if(!empty($response['data'])){
									foreach($response['data'] as $key=>$val){
										$my_post_id=$val->id;
										$att=my_facebook_get_post_attachments($my_post_id);
										/*$my_new_post_id_12345=$my_post_id;
										/*if(strpos($my_post_id,'_')!==false){
											$my_arr_12345=explode('_',$my_post_id);
											$my_new_post_id_12345=$my_arr_12345[1];
										}
										$pic=my_facebook_get_picture($my_new_post_id_12345);
										if(is_array($pic)){
											$response['data'][$key]->my_pic=$pic;
										}*/
										//$likes=my_facebook_get_facebook_likes($my_post_id);
										
										/*if($my_c<10){
											wp_my_sogrid_debug_object("likes".$my_post_id, $likes);
										}*/
										/*if(is_array($likes)){
											$response['data'][$key]->my_likes=$likes;
										}*/
										if(is_array($att)){
											$response['data'][$key]->my_att=$att;
										}
										$my_c++;
										
									}
								}
								$this->get_data[]=array(
									'error'=>0,	
									'data'=>json_encode($response),
									'id'=>$v,
									'service'=>$this->type,
									'sdk'=>1			
								);
								
							}else {
								$this->get_data[]=array(
										'error'=>1,
										'data'=>$response,
										'id'=>$v,
										'service'=>$this->type,
										'sdk'=>1
								);
								$this->api_errors[$v]=array(
										'type'=>'Api error',
										'msg'=>__("Api error","wp_my_sogrid_domain"),
										'msg_1'=>$response
								);
								
							}
						}else {
							if(strpos($v,"/")!==false){
							
								$ida=explode("/",$v);
								$my_id=$ida[1];
							}
							$response=my_facebook_get_page_photos($my_id, $this->max_num);
							//print_r($response);
							if(is_array($response)){
								if(!empty($response['data'])){
									foreach($response['data'] as $key=>$val){
										$my_post_id=$val->id;
										
										//$likes=my_facebook_get_facebook_likes($my_post_id);
										/*if(is_array($likes)){
											$response['data'][$key]->my_likes=$likes;
										}*/
									}
								}
							}
							//echo $response;
							if(is_array($response)){
								$this->get_data[]=array(
										'error'=>0,
										'data'=>json_encode($response),
										'id'=>$v,
										'service'=>$this->type,
										'sdk'=>1
								);
							
							}else {
								$this->get_data[]=array(
										'error'=>1,
										'data'=>$response,
										'id'=>$v,
										'service'=>$this->type,
										'sdk'=>1
								);
								$this->api_errors[$v]=array(
										'type'=>'Api error',
										'msg'=>__("Api error","wp_my_sogrid_domain"),
										'msg_1'=>$response
								);
							}
						}
					}else {
					$api_url=$this->generate_url($v);
				
					//wp_my_sogrid_debug_object("Api url", $api_url);
					
					$data=wp_remote_get($api_url,$this->get_options);
				
					//wp_my_sogrid_debug_object("Data", $data);
					
					$response='';
					if(!is_wp_error($data)){
						if(isset($data['body'])){
							$response=$data['body'];
						}
					}
					if(is_wp_error($data)){
						$this->get_data[]=array(
								'data'=>$response,
								'id'=>$v,
								'service'=>$this->type,
								'error'=>1
						);
						$this->api_errors[$v]=array(
							'type'=>'network',	
							'msg'=>__("Network Error","wp_my_sogrid_domain"),
							'msg_1'=>$data->get_error_message()	
						);
					}else {
							
						$my_error=0;
						if($data['response']['code']==200){
						if($my_type_request=='feed'){
							
								
								if($response!=''){
									$resp_obj=json_decode($response);
									if($resp_obj==NUll)$my_error=1;
									else if($resp_obj->responseStatus!=200){
									$my_error=1;
									}
								}
								if($my_error){
									$this->api_errors[$v]=array(
											'type'=>'api',
											'msg'=>__("Api Error","wp_my_sogrid_domain"),
											'msg_1'=>__("Error","wp_my_sogrid_domain")
											);
									if(isset($resp_obj->responseDetails)){
										$this->api_errors[$v]['msg_1']=$resp_obj->responseDetails;
									}
								}
								$this->get_data[]=array(
									'data'=>$response,
									'id'=>$v,
									'service'=>$this->type,
									'error'=>$my_error
								);
							
							}else {
								if($response!=''){
									$resp_obj=json_decode($response);
									if($resp_obj==NUll)$my_error=1;
									else if(isset($resp_obj->error)){
										$my_error=1;
									}
									//print_r($resp_obj);
								}
								if($my_error){
									$this->api_errors[$v]=array(
											'type'=>'api',
											'msg'=>__("Api Error","wp_my_sogrid_domain"),
											'msg_1'=>__("Error","wp_my_sogrid_domain")
									);
									if(isset($resp_obj->error)){
										$this->api_errors[$v]['msg_1']=$resp_obj->error->message;
									}
								}
								$this->get_data[]=array(
										'data'=>$response,
										'id'=>$v,
										'service'=>$this->type,
										'error'=>$my_error
								);
							}
						/*
						 * end
						 */
						}else {
							$this->get_data[]=array(
									'data'=>$response,
									'id'=>$v,
									'service'=>$this->type,
									'error'=>1
							);
							if($my_type_request=='feed'){
									
							
								if($response!=''){
									$resp_obj=json_decode($response);
									if($resp_obj==NUll)$my_error=1;
									else if($resp_obj->responseStatus!=200){
										$my_error=1;
									}
								}
								if($my_error){
									$this->api_errors[$v]=array(
											'type'=>'api',
											'msg'=>__("Api Error","wp_my_sogrid_domain"),
											'msg_1'=>__("Error","wp_my_sogrid_domain")
									);
									if(isset($resp_obj->responseDetails)){
										$this->api_errors[$v]['msg_1']=$resp_obj->responseDetails;
									}
								}
							}else {
								if($response!=''){
									$resp_obj=json_decode($response);
									if($resp_obj==NUll)$my_error=1;
									else if(isset($resp_obj->error)){
										$my_error=1;
									}
									//print_r($resp_obj);
								}
								if($my_error){
									$this->api_errors[$v]=array(
											'type'=>'api',
											'msg'=>__("Api Error","wp_my_sogrid_domain"),
											'msg_1'=>__("Error","wp_my_sogrid_domain")
									);
									if(isset($resp_obj->error)){
										$this->api_errors[$v]['msg_1']=$resp_obj->error->message;
									}
								}
								
							}
						}
					}
					
					
				}}
			}
		}
		/**
		* Get data
		* @return multitype:string number unknown
		*/
		function get_data(){
			return $this->get_data;
		}
		/**
		 * Format results by added data
		 * @param unknown $data
		 */
		public function format_results_by_data($data,$id){
			if(strpos($id,'/')!==false)$gall=true;
			else $gall=false;
			if($gall)$subtype='gall';
			else $subtype='feed';
			$obj=json_decode($data);
			if($obj==NULL)return false;
			$arr=array();
			if($this->use_facebook_sdk){
				$data_obj=$obj->data;
				//echo count($data);
			}else {
				
				
				if($gall)$data_obj=$obj->data;
				else $data_obj=$obj->responseData->feed->entries;
			}
			/*
			 * changes 1.20.2015.
			 */
			//wp_my_sogrid_debug_object("Photos", $data_obj);
			/*
			 * end changes
			 */
			if(!empty($data_obj)){
				foreach($data_obj as $k=>$item){
					if($subtype!='gall'){
						unset($item->comments);
						unset($item->shares);
						unset($item->likes);
					}
					$new_obj=new Class_My_Wp_SoGrid_Facebook_Object($item,$subtype,$this->use_facebook_sdk);
					$this->formated_data[]=$new_obj;
					$arr[]=$new_obj;
				}
			}
			return $arr;
		}
		
		
	}
}
if(!class_exists("Class_My_Wp_SoGrid_Facebook_Object")){
	class Class_My_Wp_SoGrid_Facebook_Object{
		private $type='facebook';
		private $subtype='feed';
		private $is_sdk=false;
		private $item;
		private $url;
		private $id;
		private $title;
		private $published;
		private $name;
		private $contentSnippet;
		private $content;
		private $image;
		private $like_url='http://www.facebook.com/plugins/like.php?href={url}&width&layout=standard&action=like&show_faces=false&share=false&height=35';
		private $share_url='https://www.facebook.com/sharer.php?u={url}&t={title}';
	    private $facebook_link='https://www.facebook.com/';
		private $from_id;
		private $attachments;
		private $comments=array();
		private $likes=array();
		private $show_thumb=true;
		private $link;
		private $is_f=true;
		private $new_url;
		private $gall_name='';
		function Class_My_Wp_SoGrid_Facebook_Object($item,$subtype='feed',$is_sdk=false){
			$this->item=$item;
			$this->subtype=$subtype;
			$this->is_sdk=$is_sdk;
			
			$this->fetchData();
		}
		public function setNoShowThumb(){
			$this->show_thumb=false;
		}
		private function isFacebookLink($url){
			$new_url=$url;
			if(strpos($url,'https://')===0){
				$new_url=str_replace('https://', '', $new_url);
				if(strpos($new_url,'www.facebook.com')===0)return true;
				else return false;
				
			}else if(strpos($url,'http://')===0){
				$new_url=str_replace('http://', '', $new_url);
				if(strpos($new_url,'www.facebook.com')===0)return true;
				else return false;
			}
			return false;
		}
		public function getCommentsUrl(){
			
			$u=urlencode($this->url);
			$url=MY_WP_SOGRID_URL.'includes/views/front/my-facebook-comments.php?my_url='.$u;
			$url=$this->url;
			if(isset($this->i_url)){
				$url=$this->i_url;
			}
			
			
			return $url;
		}
		public function getShareUrl(){
			$url=$this->share_url;
			if(isset($this->i_url)){
				$u=urlencode($this->i_url);
			}
			else $u=urlencode($this->url);
			$url=str_replace('{url}', $u, $url);
			$t=urlencode($this->title);
			$url=str_replace('{title}', $t, $url);
			return $url;
		}
		public function getLikeUrl(){
			$like_url=$this->like_url;
			if(isset($this->i_url)){
				$url=urlencode($this->i_url);
			}
			else $url=urlencode($this->url);
			//$url=urlencode($this->url);
			$like_url=str_replace('{url}', $url, $like_url);
			return $like_url;
			
		}
		/**
		 * get property
		 * @param unknown $name
		 * @return string
		 */
		public function getProperty($name){
			if(!isset($this->$name))return 'not-set';
			else return $this->$name;
		}
		private function format_date($date){
			return strtotime($date);
		}
		/**
		 * Get obvejct type
		 * @return string
		 */
		public function getType(){
			return $this->type;
		}
		public function getImageNew(){
			if(!empty($this->image))return true;
			else return false;
		}
		public function getImage(){
			$att_html='';
			if(!$this->show_thumb)return $att_html;
			if($this->subtype=='gall'){
				if(!empty($this->image)){
				ob_start();
				?>
								<img src="<?php echo $this->image;?>"/>
				<?php 
				$att_html=ob_get_clean();
				}
			}else {
				if(!empty($this->image)){
				ob_start();
					?>
				<img src="<?php echo $this->image;?>"/>
				<?php 
				$att_html=ob_get_clean();
				}
			}
			return $att_html;
		}
		/**
		 * Get total likes
		 */
		public function getTotallikes(){
			if(isset($this->item->my_likes)){
				$j=$this->item->my_likes;
				if(isset($j->summary->total_count)){
					return $j->summary->total_count;
				}
			}
			return false;
		}
		/*
		 * changes_1_16_2015
		 */
		
		/*
		 * end
		 */
		/**
		 * Fetch by 
		 */
		private function fetchData(){
			
			if(($this->subtype=='gall') || $this->is_sdk){
			if(isset($this->item->id)){
				$this->id=$this->item->id;
			}
			if(isset($this->item->publishedDate)){
				$this->published=$this->format_date($this->item->publishedDate);
				}
			if(isset($this->item->title)){
				$this->title=$this->item->title;
			}
			if(isset($this->item->name)){
				$this->gall_name=$this->item->name;
			}
				if(isset($this->item->link)){
					$url=$this->item->link;
					if(!$this->isFacebookLink($url)){
						//echo 'no facebook link '.print_r($this->from);
						if(isset($this->item->from->name)){
							$my_id=$this->id;
							if(strpos($my_id,'_')!==false){
								$my_id_arr=explode("_",$my_id);
								$new_id=$my_id_arr[1];
							}else $new_id=$my_id;
							
							$url=$this->facebook_link.$this->item->from->id.'/posts/'.$new_id;
							$this->i_url=$url;
							//echo 'New url '.$url;
							$this->url=$url;
						}else{
							$this->url=$url;
						}
					}else $this->url=$this->item->link;
				}else {
					if(isset($this->item->from->name)){
						$my_id=$this->id;
						if(strpos($my_id,'_')!==false){
							$my_id_arr=explode("_",$my_id);
							$new_id=$my_id_arr[1];
						}else $new_id=$my_id;
		
						$url=$this->facebook_link.$this->item->from->name.'/posts/'.$new_id;
						$this->i_url=$url;
						//echo 'New url '.$url;
						$this->url=$url;
				}else $this->url='';
				}
				if(isset($this->item->from->id)){
					$this->from_id=$this->item->from->id;
				}
				if(isset($this->item->created_time)){
					$date=$this->item->created_time;
					$this->published=$this->format_date($date);
				}
				if(isset($this->item->from->name)){
					$this->name=$this->item->from->name;
				}
				if(isset($this->item->message)){
					$this->content=$this->item->message;
				}
				else if(isset($this->item->story)){
					$this->content=$this->item->story;
				}
				if(!empty($this->item->caption)){
					$this->title=$this->item->caption;
				}else {
				$this->title=$this->content;
				if(empty($this->title)){
					if(isset($this->item->name))$this->title=$this->item->name;
				}
				}
				$my_found_att=false;
				if(!empty($this->item->my_att)){
					global $wp_my_sogrid_image_prop_x;
					global $wp_my_sogrid_image_prop_y;
					$att=array();
					$pre_att=array();
					foreach($this->item->my_att as $k=>$v){
						if(isset($v->media->image)){
							$obj=$v->media->image;
							$w=$obj->width;
							$h=$obj->height;
							$pre_att[]=$v;
							if(($w>=$wp_my_sogrid_image_prop_x)&&($h>=$wp_my_sogrid_image_prop_y)){
								$att=$v;
							}else break;
						}
					}
					if(empty($att)&&!empty($pre_att)){
						$max_w=0;
						$found='';
						foreach($pre_att as $k=>$v){
							$obj=$v->media->image;
							$w=$obj->width;
							$h=$obj->height;
							if($w>$max_w){
								$found=$v;
								$max_w=$w;
							}
						}
					}
					if(!empty($att)){
						$this->attachments[]=$att;
	
						$this->image=$att->media->image->src;
						$my_found_att=true;
					}
				} 
				if(!$my_found_att){
				if(!empty($this->item->images)){
					global $wp_my_sogrid_image_prop_x;
					global $wp_my_sogrid_image_prop_y;
	
				
					$att=array();
					foreach($this->item->images as $k=>$v){
						$w=$v->width;
						$h=$v->height;
						if(($w>=$wp_my_sogrid_image_prop_x)&&($h>=$wp_my_sogrid_image_prop_y)){
							$att=$v;
						}else break;
					}
					if(empty($att)){
						$att=$this->item->images[0];
					}
					$this->attachments[]=$att;
					if(!empty($att))
					$this->image=$att->source;
					
				}else if(!empty($this->item->picture)){
					$this->attachments[]=$this->item->picture;
					$this->image=$this->item->picture;
				}
				}
				if(!empty($this->item->comments->data)){
					$this->comments=$this->item->comments->data;
				}
				if(!empty($this->item->likes->data)){
					$this->likes=$this->item->likes->data;
				}
				/*
				 * end
				 */
			}else if($this->subtype=='feed'){
				if(isset($this->item->publishedDate)){
					$this->published=$this->format_date($this->item->publishedDate);
				}
				if(isset($this->item->title)){
					$this->title=$this->item->title;
				}
				if(isset($this->item->link)){
					$this->url=$this->item->link;
				}
				if(isset($this->item->contentSnippet)){
					$this->contentSnippet=$this->item->contentSnippet;
				}
				if(isset($this->item->content)){
					$this->content=$this->item->content;
					$images=preg_match_all('/<img([^>]+)>/ims', $this->content,$matches);
					//print_r($matches);
					$c=0;
					if(!empty($matches[1])){
						foreach($matches[1] as $k=>$v){
							if($c==1)break;
							if(preg_match_all('/src="([^"]+)"/ims', $v,$src_match)){
								//wp_my_sogrid_debug_object("SRC", $src_match);
							if(isset($src_match[1][0])){
								$src=$src_match[1][0];
								//wp_my_sogrid_debug_object("Img src", $src);
								if(strpos($v,'safe_image.php')!==false){
									preg_match_all('/url=([^&]+)/ims', $src,$matches_1);
									$url='';
									if(!empty($matches_1[1])){
										
										//print_r($matches_1);
										
										$url=urldecode($matches_1[1][0]);
									}
															
								}else {
									
									$url=$src;
									/*$arr=explode("_",$v);
									if(isset($arr[1])){
										$url='http://graph.facebook.com/'.$arr[1].'/picture?type=normal';
									}
									$url=$v;
									*/
									/*
									 * end
									 */
								}
							//	wp_my_sogrid_debug_object("URL", $url);
								$this->image=$url;
							
								$c++;	
								}
							}
						}
					}
					$this->content=strip_tags($this->content,'<a>');
				}
				
				
				
			}
			
		}
		
	}
	
}