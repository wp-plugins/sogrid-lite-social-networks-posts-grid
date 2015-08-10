<?php
if(!defined('ABSPATH'))die('');
if(!class_exists('Class_My_Wp_SoGrid_Twitter')){
	class Class_My_Wp_SoGrid_Twitter{
		private $id;
		private $get_options;
		private $api_options;
		private $get_data;
		private $sogrid_id;
		private $max_num=100;
		private $formated_data;
		private $api_class;
		private $type='twitter';
		public $api_errors;
		function Class_My_Wp_SoGrid_Twitter($options=array()){
			if(!empty($options)){
				foreach($options as $k=>$v){
					$this->$k=$v;
				}
			}
			/*
			 * changes limit to 500
			*/
			/**
			 * Limit maxnum to 500
			 */
			if($this->max_num>200)$this->max_num=200;
			/*
			 * end changes
			*/
			if(empty($this->api_options))$this->api_options=array();
			$this->api_options['count']=$this->max_num;
			if(strpos($this->id,'/')===0){
				$this->api_options['rest']='lists/statuses';
				$this->api_options['id']=substr($this->id, 1);
				unset($this->api_options['exclude_replies']);
			}else if(strpos($this->id,'#')===0){
				$this->api_options['q']=substr($this->id, 1);
				unset($this->api_options['exclude_replies']);
				$this->api_options['rest']='search/tweets';	
			}else {
				$this->api_options['id']=$this->id;
				$this->api_options['rest']='statuses/user_timeline';
			}
		}
		public function get_post($id){
			$this->api_options['rest']='statuses/show/'.$id;
			$file=MY_WP_SOGRID_SOCIAL_MODULES_DIRNAME.'twitter/libs/twitter-auth.php';
			require_once $file;
			$this->api_class=new Class_Wp_My_SoGrid_Twitter_Api($this->api_options);
			$data=$this->api_class->callApi();
			$response='';
			if(!is_wp_error($data)){
				if(isset($data['body'])){
					$response=$data['body'];
				}
			}
			if(is_wp_error($data)){
				return false;
			}
			if($data['response']['code']==200){
				$obj=json_decode($response);
				//return $obj;
				$item=$obj;
				$favourites_count=0;
				$retweeted=0;
				if(isset($item->favorite_count)){
					$favourites_count=$item->favorite_count;
				}else $favourites_count=0;
				if(isset($item->retweet_count)){
					$retweeted=$item->retweet_count;
				}
				return array('retweeted'=>$retweeted,'favourites_count'=>$favourites_count);
				
			}
			return false;
		}
		/**
		 * Call api
		 */
		public function call_api(){
			$file=MY_WP_SOGRID_SOCIAL_MODULES_DIRNAME.'twitter/libs/twitter-auth.php';
			require_once $file;
			$this->api_class=new Class_Wp_My_SoGrid_Twitter_Api($this->api_options);
			$data=$this->api_class->callApi();
			$response='';
			if(!is_wp_error($data)){
				if(isset($data['body'])){
					$response=$data['body'];
				}
			}
			if(is_wp_error($data)){
				$this->get_data[]=array(
						'data'=>$response,
						'id'=>$this->id,
						'service'=>$this->type,
						'error'=>1
				);
				$this->api_errors[$this->id]=array(
						'type'=>'network',
						'msg'=>__("Network Error","wp_my_sogrid_domain"),
						'msg_1'=>$data->get_error_message()
				);
			}else {
				if($data['response']['code']==200){
					$my_error=0;
					if($response!=''){
						$resp_obj=json_decode($response);
						if($resp_obj==NUll)$my_error=1;
						else if(isset($resp_obj->errors)){
							$my_error=1;
						}
					}
					if($my_error){
						$my_msg_12='';
						if(isset($resp_obj->errors[0]->message)){
							$my_msg_12=$resp_obj->errors[0]->message;
						}
						$this->api_errors[$this->id]=array(
							'type'=>'api',
							'msg'=>__("API error","wp_my_sogrid_domain"),
							'msg_1'=>$my_msg_12
						);
					}
					$this->get_data[]=array(
							'data'=>$response,
							'id'=>$this->id,
							'service'=>$this->type,
							'error'=>$my_error
					);
			
						
				}else {
					if($response!=''){
						$resp_obj=json_decode($response);
						if($resp_obj==NUll)$my_error=1;
						else if(isset($resp_obj->errors)){
							$my_error=1;
						}
					}
					if($my_error){
						$my_msg_12='';
						if(isset($resp_obj->errors[0]->message)){
							$my_msg_12=$resp_obj->errors[0]->message;
						}
						$this->api_errors[$this->id]=array(
								'type'=>'api',
								'msg'=>__("API error","wp_my_sogrid_domain"),
								'msg_1'=>$my_msg_12
						);
					}else {
					$this->api_errors[$this->id]=array(
							'type'=>'network',
							'msg'=>__("Network Error","wp_my_sogrid_domain"),
							'msg_1'=>''					
					);
					}
					$this->get_data[]=array(
							'data'=>$response,
							'id'=>$this->id,
							'service'=>$this->type,
							'error'=>1
					);
				}
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
		public function format_results_by_data($data){
			$obj=json_decode($data);
			$arr=array();
			if($obj==NULL)return false;
			if(strpos($this->id,'#')===0){
				if(isset($obj->statuses))$obj=$obj->statuses;
			}
			if(!empty($obj)){
				foreach($obj as $k=>$item){
					$new_obj=new Class_My_Wp_SoGrid_Twitter_Object($item);
					$arr[]=$new_obj;
					
				}
			
			}
			
			return $arr;
		}
		
	}
}
if(!class_exists('Class_My_Wp_SoGrid_Twitter_Object')){
	class Class_My_Wp_SoGrid_Twitter_Object{
		private $type='twitter';
		private $item;
		private $published;
		private $date;
		private $content;
		private $screen_name;
		private $user_id;
		private $user_thumb;
		private $screen_thumb;
		private $attachments;
		private $id;
		private $url;
		private $hastags_url='https://twitter.com/hashtag/{tag}?src=hash';
		private $user_url='https://twitter.com/{id}/';
		private $share_url='https://twitter.com/intent/tweet?in_reply_to={id}&via={via}';
		private $retweet_url='https://twitter.com/intent/retweet?tweet_id={id}&via={via}';
		private $favorite_url='https://twitter.com/intent/favorite?tweet_id={id}';
		private $retweet_count;
		private $favourites_count;
		private $retweeted=false;
		private $show_thumb=true;
		
		function Class_My_Wp_SoGrid_Twitter_Object($item){
			$this->item=$item;
			if(isset($this->item->created_at)){
				$this->date=$this->item->created_at;
			}
			$this->published=$this->format_date($this->date);
			$this->content=$this->item->text;
			if(isset($this->item->user->screen_name)){
				$this->screen_name=$this->item->user->screen_name;		
			}
			if(isset($this->item->user->profile_image_url_https)){
				$this->user_thumb=$this->item->user->profile_image_url_https;
			}
			if(isset($this->item->id_str)){
				$this->id=$this->item->id_str;
			}
			if(isset($this->item->retweet_count)){
				$this->retweet_count=$this->item->retweet_count;
			}else $this->retweet_count=0;
			
			if(isset($this->item->favorite_count)){
				$this->favourites_count=$this->item->favorite_count;
			}else $this->favourites_count=0;
			if(isset($this->item->retweeted)){
				$this->retweeted=$this->item->retweeted;
			}
			if(!empty($this->item->entities->media)){
				$c=0;
				foreach($this->item->entities->media as $k=>$v){
					$att=array();
					$att['src']=$v->media_url_https;
					$att['sizes']=$v->sizes;
					$this->attachments[]=$att;
					$c++;
					if($c==1)break;
				}
			}
			$this->url='https://www.twitter.com/'.$this->screen_name.'/status/'.$this->id;
			
		}
		public function setNoShowThumb(){
			$this->show_thumb=false;
		}
		public function format_text($target='_blank'){
			$text=$this->content;
			$rt=false;
			if(strpos($text,'RT @')==0)$rt=true;
			/**
			 * Change urls
			 */
			$replace_text=array();
			
			if(!empty($this->item->entities->urls)){
				//wp_my_sogrid_debug_object("Urls", $this->item->entities->urls);
				foreach($this->item->entities->urls as $k=>$v){
					$url=$v->url;
					$ind=$v->indices;
					$len=$ind[1]-$ind[0]+1;
					//print_r($ind);
					$a_text=substr($text,$ind[0],$len);
					$a='<a href="'.$url.'" target="'.$target.'">'.$a_text.'</a>';
					//$text=substr($text,0,$ind[0]).' '.$a.' '.substr($text, $ind[1]);
					$replace_text[]=array(
						'type'=>'urls',
						'start'=>$ind[0],
						'url'=>$url,
						'a'=>$a,
						'ind'=>$ind,
						'item'=>$v,
						'len'=>$len								
					);
				}
			}
			/*$added_u_m=0;
			if(!empty($this->item->entities->user_mentions)){
				foreach($this->item->entities->user_mentions as $k=>$v){
					$screen_name=$v->screen_name;
					$url=$this->getUserUrl($screen_name);
					$ind=$v->indices;
					if(($rt)&&($added_u_m>0)){
						$len=$ind[1]-$ind[0]+1;
						$start=$ind[0]+2;	
						//print_r($ind);
						$a_text=substr($text,$start,$len);
							
					}else {
						$len=$ind[1]-$ind[0]+1;
					
					//print_r($ind);
						$a_text=substr($text,$ind[0],$len);
					}
					$a='<a href="'.$url.'" target="'.$target.'">'.$a_text.'</a>';
					//$text=substr($text,0,$ind[0]).$a.substr($text, $ind[1]);
					$replace_text[]=array(
						'type'=>'user_mentions',
						'start'=>$ind[0],
						'url'=>$url,
						'item'=>$v,		
						'ind'=>$ind,
						'len'=>$len,
						'text'=>$a_text			
					);
					$added_u_m++;
				}
			}*/
			if(!empty($this->item->entities->hashtags)){
				foreach($this->item->entities->hashtags as $k=>$v){
					$text=$v->text;
					$ind=$v->indices;
					$url=$this->getHashTagUrl($text);
					$replace_text[]=array(
							'type'=>'hashtags',
							'url'=>$url,
							'start'=>$ind[0],
							'text'=>$text
							
					);
				}
			}	
			$text=$this->content;
			if(preg_match_all('/@\w+/', $text,$matches)){
				//wp_my_sogrid_debug_object("Mentions", $matches);
				if(!empty($matches[0])){
				foreach($matches[0] as $k=>$v){
					$user=substr($v,1);
					$url=$this->getUserUrl($user);
					$a='<a href="'.$url.'" target="'.$target.'">'.$v.'</a>';				
					if(strpos($text,$v)!==false){
						$text=str_replace($v, $a, $text);
					}//else echo 'not found user';
				}
				}
			}
			//wp_my_sogrid_debug_object("Entities", $replace_text);
			if(!empty($replace_text)){
				foreach($replace_text as $k=>$v){
					if($v['type']=='urls'){
						$t=$v['url'];
					}else if($v['type']=='user_mentions'){
						$t=$v['text'];
						//$t='@'.$v['item']->screen_name;
					}else if($v['type']=='hashtags'){
						$t='#'.$v['text'];
					}
					$url=$v['url'];
					$a='<a href="'.$url.'" target="'.$target.'">'.$t.'</a>';
					if(strpos($text,$t)!==false){
						$text=str_replace($t, $a, $text);
					}else {
						//echo 'not found';
						
					}
				}
				
			}
			
			return $text;
		} 
		private function getHashTagUrl($text){
			$url=$this->hastags_url;
			$url=str_replace('{tag}', $text, $url);
			return $url;
		}
		private function getUserUrl($name){
			$url=$this->user_url;
			$url=str_replace('{id}', $name,$url);
			return $url;
		}
		/**
		 * Get share reply url
		 * @return mixed
		 */
		public function getShareReplyUrl(){
			$id=$this->id;
			$via=wp_my_sogrid_get_option_by_key('twitter_id');
			if(empty($via))$via='';
			$href=$this->share_url;
			$href=str_replace('{id}', $id, $href);
			$href=str_replace('{via}', $via, $href);
			return $href;
			
		}
		/**
		 * Get retweet url
		 * @return mixed
		 */
		public function getRetweetUrl(){
			$id=$this->id;
			$via=wp_my_sogrid_get_option_by_key('twitter_id');
			if(empty($via))$via='';
			$href=$this->retweet_url;
			$href=str_replace('{id}', $id, $href);
			$href=str_replace('{via}', $via, $href);
			return $href;
			
		}
		public function getFavoriteUrl(){
			$id=$this->id;
			$href=$this->favorite_url;
			$href=str_replace('{id}', $id, $href);
			return $href;
			
		}
		public function getImageNew(){
			if(!empty($this->attachments))return true;
			else return false;
		}
		public function getImage($size='medium'){
			$att_html='';
			if(!$this->show_thumb)return $att_html;
			if(!empty($this->attachments)){
				$v=$this->attachments[0];
				ob_start();
				?>
				<img src="<?php echo $v['src'];?>:<?php echo $size;?>"/>
				<?php 
				$att_html=ob_get_clean();
			}
			return $att_html;
		}
		/**
		 * Get type
		 * @return string
		 */
		public function getType(){
			return $this->type; 
		}
		/*
		 * 
		 */
		/**
		 * get property
		 * @param unknown $name
		 * @return string
		 */
		public function getProperty($name){
			if(!isset($this->$name))return 'not-set';
			else return $this->$name;
		}
		/**
		 * Format date
		 * @return number
		 */
		private function format_date($date){
			return strtotime($date);
		}
		
	}
}
	