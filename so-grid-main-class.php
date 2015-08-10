<?php
/*
Plugin Name: SoGrid
Plugin URI: http://www.shindiristudio.com/sogrid/
Description: Social grid
Author: Br0
Version: 1.0
Author URI: http://www.shindiristudio.com/
*/
if(!class_exists('Class_My_Wp_SoGrid_Main_Class')){
	class Class_My_Wp_SoGrid_Main_Class{
		private $plugin_dir;
		private $plugin_url;
		private $social_modules;
		private $is_admin;
		private $backend_class;
		private $options;
		private $pages;
		private $front_module;
		private $has_twitter_api;
		function Class_My_Wp_SoGrid_Main_Class(){
			$this->plugin_dir=plugin_dir_path(__FILE__);
			$this->plugin_url=plugin_dir_url(__FILE__);
			$this->pages=array(
				'my-sogrid-index'=>array(
					'title'=>__("SoGrid","wp_my_sogrid_domain"),
					'cap'=>'manage_options',
					'subpages'=>array(
						'my-sogrid-edit'=>array(
							'title'=>__("Add New/Edit","wp_my_sogrid_domain"),
							'cap'=>'manage_options',
					
						),
						'my-sogrid-general-options'=>array(
									'title'=>__("Options","wp_my_sogrid_domain"),
									'cap'=>'administrator',
										
						),
						'my-sogrid-search-ids'=>array(
									'title'=>__("Get Social Ids","wp_my_sogrid_domain"),
									'cap'=>'manage_options',
							
						),
					)	
				)
			);
		}
		public function load_lang(){
			$dir=dirname(plugin_dir_path(__FILE__)).'/lang';
			load_plugin_textdomain('wp_my_sogrid_domain',false,$dir);
		} 
		/**
		 * Init plugin
		 */
		function init(){
			add_action('plugins_loadded',array($this,'load_lang'));
			add_action('init',array(&$this,'start_session'));
			global $wp_my_sogrid_table_object;
			global $wp_my_sogrid_table_object_meta;
			global $wpdb;
			$wp_my_sogrid_table_object=$wpdb->base_prefix.'sogrid_objects';
			$wp_my_sogrid_table_object_meta=$wpdb->base_prefix.'sogrid_object_meta';
			
			register_activation_hook(__FILE__,array('Class_My_Wp_SoGrid_Main_Class','activate'));
			/**
			 * Define dirnames
			 * @var unknown_type
			 */
			define('MY_WP_SOGRID_DIRNAME',$this->plugin_dir);
			define('MY_WP_SOGRID_CLASS_DIRNAME',$this->plugin_dir.'includes/class/');
			define('MY_WP_SOGRID_CONTROLLERS_DIRNAME',$this->plugin_dir.'includes/controllers/');
			define('MY_WP_SOGRID_VIEWS_DIRNAME',$this->plugin_dir.'includes/views/');
			define('MY_WP_SOGRID_MODULES_DIRNAME',$this->plugin_dir.'includes/modules/');
			define('MY_WP_SOGRID_FUNCTIONS_DIRNAME',$this->plugin_dir.'includes/functions/');
			
			define('MY_WP_SOGRID_SOCIAL_MODULES_DIRNAME',$this->plugin_dir.'includes/modules/social/');
			/**
			 * Define URLS
			 */
			define('MY_WP_SOGRID_URL',$this->plugin_url);
			define('MY_WP_SOGRID_ASSETS_URL',$this->plugin_url.'assets/');
			define('MY_WP_SOGRID_CSS_URL',$this->plugin_url.'assets/css/');
			define('MY_WP_SOGRID_IMAGES_URL',$this->plugin_url.'assets/images/');
			define('MY_WP_SOGRID_JSCRIPT_URL',$this->plugin_url.'assets/jscript/');
			define('MY_WP_SOGRID_MODULES_URL',$this->plugin_url.'includes/modules/');
			define('MY_WP_SOGRID_SOCIAL_MODULES_URL',$this->plugin_url.'includes/modules/social/');
			/**
			 * Load functions
			 */
			$file=MY_WP_SOGRID_FUNCTIONS_DIRNAME.'functions.php';
			require_once $file;
			$file=MY_WP_SOGRID_FUNCTIONS_DIRNAME.'general-options.php';
			require_once $file;
			/**
			 * Admin ajax
			 */
			add_action('wp_ajax_my_sogrid_admin_action',array(&$this,'admin_ajax'));
			add_action('admin_notices',array(&$this,'admin_notices'));
			/**
			 * Dynamic loading
			 */
			global $my_sogrid_ajax_action_name;
			add_action('wp_ajax_'.$my_sogrid_ajax_action_name,array(&$this,'sogrid_more'));
			add_action('wp_ajax_nopriv_'.$my_sogrid_ajax_action_name,array(&$this,'sogrid_more'));
			
			add_action('wp_ajax_my_sogrid_update_posts_share',array(&$this,'update_posts'));
			add_action('wp_ajax_nopriv_my_sogrid_update_posts_share',array(&$this,'update_posts'));

			add_action('wp_ajax_save_instagram_token',array(&$this,'save_instagram_token'));
			add_action('wp_enqueue_scripts',array(&$this,'scripts'));	
				
			/**
			 * Load registered social modules
			 * @var unknown_type
			 */
			$file=MY_WP_SOGRID_SOCIAL_MODULES_DIRNAME.'info.php';
			
			require_once $file;
			/**
			 * Get options	
			 */
			$this->options=wp_my_sogrid_get_options(true);
			
			global $wp_my_sogrid_social_modules;
			$this->social_modules=$wp_my_sogrid_social_modules;		
			$this->is_admin=is_admin();
			/**
			 * Changes 
			 */
			add_action('wp_footer',array($this,'instagram_code'));
			/*
			 * 
			 */
			if($this->is_admin){
				$file=MY_WP_SOGRID_CLASS_DIRNAME.'class-wp-my-sogrid-backend-class.php';
				require_once $file;
				$options=array(
					'plugin_pages'=>$this->pages
				);
				$this->backend_class=new Class_Wp_My_SoGrid_Backend_Class($options);
				$this->backend_class->init();
			}
			
			if(!$this->is_admin){
				$file=MY_WP_SOGRID_MODULES_DIRNAME.'front/class.php';
				require_once $file;
				$this->front_module=new Class_Wp_My_SoGrid_Front_View();
				$this->front_module->init();
				
			}
			add_action('init',array(&$this,'my_get_vkontakte_access_token'));
			
			
		
		}
		function my_get_vkontakte_access_token(){
			$name='my_vkontakte_12_12_get_code';
			$var=@$_GET[$name];
			if(isset($var)){
				$code=@$_GET['code'];
				$error=@$_GET['error'];
				$error_descr=@$_GET['error_description'];
				if(isset($error)){
					$file=MY_WP_SOGRID_DIRNAME.'tmp/vkontakte.error';
					$fp=fopen($fp,$file);
					fwrite($fp,$error);
					fwrite($fp,",".$error_descr);
					fclose($fp);
				}
				else if(isset($code)){
					$file=MY_WP_SOGRID_DIRNAME.'tmp/vkontakte.error';
					if(file_exists($file)){
						unlink($file);
					}
					$vkontakte_client_id=wp_my_sogrid_get_option_by_key('vkontakte_client_id');
					$vkontakte_client_secret=wp_my_sogrid_get_option_by_key('vkontakte_client_secret');
						
					$red_url_12=wp_my_sogrid_get_site_url().'?my_vkontakte_12_12_get_code=1';
						
					$url='https://oauth.vk.com/access_token?client_id='.urlencode($vkontakte_client_id);  
					$url.='&client_secret='.urlencode($vkontakte_client_secret).'&';  
					$url.='code='.urlencode($code).'&';  
					$url.='redirect_uri='.urlencode($red_url_12);
					$data=wp_remote_get($url);
					//print_r($data);
					if(!is_wp_error($data)){
						if($data['response']['code']==200){
							$obj=json_decode($data['body']);
							$error=__("Error Getting Vkontakte acces token","wp_my_sogrid_domain");
							if(empty($obj)){
								$file=MY_WP_SOGRID_DIRNAME.'tmp/vkontakte.error';
								$fp=fopen($fp,$file);
								fwrite($fp,$error);
									
								//fwrite($fp,",".$error_descr);
								fclose($fp);
							}else {
								if(isset($obj->error)){
									$file=MY_WP_SOGRID_DIRNAME.'tmp/vkontakte.error';
									$fp=fopen($fp,$file);
									fwrite($fp,$obj->error);
									fwrite($fp,",".$obj->error_description);
									fclose($fp);
								}else {
									$access_token=$obj->access_token;
									$expires=$obj->expires_in;
									update_option("_my_sogrid_vkontakte_access_token", $access_token);
									$t=time();
									$t+=$expires;
									update_option("_my_sogrid_vkontakte_access_token_expires", $t);
									
										
								}
							}	
							
						}else {
							$error=__("Error Getting Vkontakte acces token","wp_my_sogrid_domain");
							$file=MY_WP_SOGRID_DIRNAME.'tmp/vkontakte.error';
							$fp=fopen($fp,$file);
							fwrite($fp,$error);
							
							//fwrite($fp,",".$error_descr);
							fclose($fp);
						}
					}	
				}
			}
		}
		function start_session(){
			session_start();
		}
		function save_instagram_token(){
			if(is_user_logged_in()){
				if(current_user_can('manage_options')){
					$data=@$_POST;
					$nonce=@$_POST['nonce'];
					if(wp_verify_nonce($nonce,'save_instagram_token')){
						$token=@$_POST['token'];
						$option_name='wp_my_sogrid_instagram_access_token_12_12';
						update_option($option_name, $token);
					}
					echo json_encode($data);
					
				}
			}
			die('');
		}
		function scripts(){
			$code=@$_GET['my_instagram_12_12_get_code'];
			if(isset($code)){
				wp_enqueue_script("jquery");
			}
		}
		function instagram_code(){
			//$get_id=get_option('wp_my_sogrid_get_option_12_12');
			$code=@$_GET['my_instagram_12_12_get_code'];
			if(isset($code)&&current_user_can('manage_options')){
				/*//$code_1=@$_GET['code'];
				$req_uri=get_site_url().@$_SERVER['REQUEST_URI'];
				$arr=parse_url($req_uri);
				$code_1=$arr['fragment'];
				print_r($arr);
				/*$file=MY_WP_SOGRID_DIRNAME.'tmp/cache/'.$this->sogrid_id.'/my_instagram.php';
				$str="<?php if(!defined('ABSPATH'))die('');\n";
				$str.="ob_start();?>";
				$str.=$code_1;
				$str.=" <?php ";
				$str.="\$ret=ob_get_clean();\n";
				$str.="return \$ret;";
				$fp=fopen($file,'w');
				fwrite($fp,$str);
				fclose($fp);*/
				//$option_name='wp_my_sogrid_instagram_access_token_12_12';
				//update_option($option_name, $code_1);
				?>
				<script type="text/javascript">
					jQuery(document).ready(function($){
						var token=location.hash.split('=')[1];
						var data={};
						data.nonce="<?php echo wp_create_nonce("save_instagram_token")?>";
						data.action="save_instagram_token";
						data.token=token;
						console.log("Data",data);
						$.ajax({
							url:"<?php echo admin_url("admin-ajax.php");?>",
							dataType:'json',
							data:data,
							cache:false,
							timeout:3000,
							type:'POST',
							success:function(data){
								console.log("Data",data);
							},
							error:function(){
							}	
						});
						});
				</script>
				<?php
				
			}
		}
		function update_posts(){
			global $my_wp_sogrid_debug;
			global $my_wp_sogrid_debug_data;
			$ret['error']=0;
			//$ret['msg']='Ok';
			$nonce=@$_POST['nonce'];
			$id=@$_POST['id'];
			$post_id=@$_POST['post_id'];
			$network=@$_POST['network'];
			$c=@$_POST['c'];
			$type=@$_POST['type'];
			$my_error=false;
			if(!preg_match('/^[\d]+$/ims', $id)){
				$ret['error']=1;
				$ret['msg']=__("Error","wp_my_sogrid_domain");
				$my_error=true;
					
			}
			global $my_sogrid_ajax_action_name;
			$my_nonce_str=$my_sogrid_ajax_action_name;
			if(!($my_error)&&!wp_verify_nonce($nonce,$my_nonce_str)){
				$ret['error']=1;
				$ret['msg']=__("Error","wp_my_sogrid_domain");
				$my_error=true;
			}
			$is_exists=wp_my_sogrid_is_exist_object($id);
			if(!($my_error)&&empty($is_exists)){
				$ret['error']=1;
				$ret['msg']=__("Error","wp_my_sogrid_domain");
				$my_error=true;
			}
			if($my_error===false){
				$options=wp_my_sogrid_get_object_and_meta($id);
				$networks=$options['active_social'];
				if(!in_array($network, $networks)){
					$ret['error']=1;
					$ret['msg']=__("Error","wp_my_sogrid_domain");
				}else{
					$file=MY_WP_SOGRID_MODULES_DIRNAME.'front/class.php';
					require_once $file;
					$options_class=array('id'=>$id,'sogrid_options'=>$options);
					$this->front_module=new Class_Wp_My_SoGrid_Front_View($options_class);
					$obj=$this->front_module->get_post($network, $post_id);
					$ret['obj']=$obj;
					
					if($obj===false){
						$ret['error']=1;
					}else {
						switch($network){
							case 'twitter':
								$ret['error']=0;
								$ret['data']=$obj;
								$ret['type']=$type;
								$ret['c']=$c;
								$ret['network']=$network;
								/*if($obj!==false){
									$ret['data']=$obj;
								}else {
									$ret['error']=1;
								}*/
								$my_save_cache=false;
								if($type=='retweeted'){
									if($obj['retweeted']!=$c){
										$my_save_cache=true;
									}
								}
								if($type=='favourites_count'){
									if($obj['favourites_count']!=$c){
										$my_save_cache=true;
									}
								}
								if($my_save_cache){
									wp_my_sogrid_save_share_cache($id, $network, $post_id, $obj);
								}
							break;	
							case 'facebook':
								if(isset($obj->summary->total_count)){
									
									$new_val=$obj->summary->total_count;
									$ret['new_val']=$new_val;
								
								if($c!=$new_val){
									$ret['post_id']=$post_id;
									$ret['error']=0;
									$ret['type']=$type;
									$ret['c']=$c;
									$ret['network']=$network;
									wp_my_sogrid_save_share_cache($id, $network, $post_id, $new_val);
								}
								}
								else $ret['new_val']='empty';
							break;	
							case 'google':
								
									$shares=false;
									if(isset($obj->resharers->totalItems)){
										$shares=$obj->resharers->totalItems;
									}
									$plusone=false;
									if(isset($obj->plusoners->totalItems)){
										$plusone=$obj->plusoners->totalItems;
									}
									$ret['debug']['plusone']=$obj->plusoners;
									$ret['debug']['reshares']=$obj->resharers;
								if($type=='share'){	
									if(($shares!==false)&&$shares!=$c){
										$save_obj=new stdClass();
										$save_obj->resharers=$obj->resharers;
										$save_obj->plusoners=$obj->plusoners;
										wp_my_sogrid_save_share_cache($id, $network, $post_id, $save_obj);
									}
										$ret['error']=0;
										$ret['type']=$type;
										$ret['c']=$c;
										$ret['new_c']=$shares;
										$ret['network']=$network;
										$ret['post_id']=$post_id;
									}else if($type=='plusone'){
										if(($plusone!==false)&&($plusone!=$c)){
											$save_obj=new stdClass();
											$save_obj->resharers=$obj->resharers;
											$save_obj->plusoners=$obj->plusoners;
											wp_my_sogrid_save_share_cache($id, $network, $post_id, $save_obj);
											
										}
										$ret['post_id']=$post_id;
										$ret['error']=0;
										$ret['type']=$type;
										$ret['c']=$c;
										$ret['network']=$network;
									}
								//}
							break;
							default:
							$ret['error']=1;
							break;		
						}
					}
				}
				
			}
			echo json_encode($ret);
			die('');
		}
		/**
		 * Load sogrid more
		 */
		function sogrid_more(){
			global $my_wp_sogrid_debug;
			global $my_wp_sogrid_debug_data;
			$ret['error']=0;
			//$ret['msg']='Ok';
			$nonce=@$_POST['nonce'];			
			$id=@$_POST['id'];
			$page=@$_POST['page'];
			$time=@$_POST['cache_time'];
			$columns=@$_POST['columns'];
			$columns=(int)$columns;
			if(!in_array($columns,array(1,2,3)))$columns=3;
			$my_error=false;
			$ret['no_cache']=0;
			if(!preg_match('/^[\d]+$/ims', $page)){
				$ret['error']=1;
				$ret['msg']=__("Error","wp_my_sogrid_domain");
				$my_error=true;
					
			}
			if(!preg_match('/^[\d]+$/ims', $id)){
				$ret['error']=1;
				$ret['msg']=__("Error","wp_my_sogrid_domain");
				$my_error=true;
					
			}
			global $my_sogrid_ajax_action_name;
			$my_nonce_str=$my_sogrid_ajax_action_name;
			if(!wp_verify_nonce($nonce,$my_nonce_str)){
				$ret['error']=1;
				$ret['msg']=__("Error","wp_my_sogrid_domain");
				$my_error=true;
			}
			$is_exists=wp_my_sogrid_is_exist_object($id);
			if(empty($is_exists)){
				$ret['error']=1;
				$ret['msg']=__("Error","wp_my_sogrid_domain");
				$my_error=true;
			}
			if($my_error===false){
				$options=wp_my_sogrid_get_object_and_meta($id);
				$dynamic_loading=$options['general_options']['dynamic_loading'];
				$networks=$options['active_social'];
				
				foreach ($networks as $k=>$v){
					$service=$v;
					$file=MY_WP_SOGRID_SOCIAL_MODULES_DIRNAME.$service.'/class.php';
					if(file_exists($file)){
						if($my_wp_sogrid_debug){
						
							$my_wp_sogrid_debug_data['required_files'][]=$file;
						}
						require_once $file;
					}
				}
				if($dynamic_loading==1){
					$cache=wp_my_sogrid_get_cached_requests($id,true,true);
					
					if($cache!==false){
						if($cache['lifetime']!=$time){
							$ret['no_cache']=1;
						}
						else {
							if($cache['error']==0){
						
							$parsed=$cache['parsed'];
							if($my_wp_sogrid_debug){
								
								$my_wp_sogrid_debug_data['parsed_cache']=$cache;
							}	
								global $my_sogrid_dynamic_content_num;
								$limit=$my_sogrid_dynamic_content_num;
								$total=count($parsed['data']);
								if($options['general_options']['dynamic']){
									//if($c==11)break;
									$pages=ceil($total/12);
									$limit=12;
								}
								else {
									$pages=ceil($total/$my_sogrid_dynamic_content_num);//break;
									$limit=$my_sogrid_dynamic_content_num;
													
								}
								if($my_wp_sogrid_debug){
								
									$my_wp_sogrid_debug_data['query']=array(
										'lifetime'=>$time,
										'cache_filetime'=>$cache['lifetime'],
										'total'=>$total,
										'pages'=>$pages,
										'page'=>$page				
									);
								}
								if($page>$pages){
									$ret['error']=1;
									$ret['msg']=__("No more results","wp_my_sogrid_domain");
								}else {
									$poc=($page-1)*$limit;
									//generate html
									$file=MY_WP_SOGRID_MODULES_DIRNAME.'front/class.php';
									require_once $file;
									$this->front_module=new Class_Wp_My_SoGrid_Front_View();
									$s_123=$this->front_module->generate_ajax_more($id,$options,$parsed['data'], $poc, $limit,$columns);
									$ret['items']=$s_123['items'];
									$ret['ids']=$s_123['ids'];
								}
								
							
							
						}else $ret['no_cache']=1;
					}}else $ret['no_cache']=1;
					
				}
				
			}
			if($my_wp_sogrid_debug){
				$ret['debug']=$my_wp_sogrid_debug_data;
			}
			
			echo json_encode($ret);
			die('');
		}
		function admin_notices(){
			$file=MY_WP_SOGRID_DIRNAME.'tmp/cache/';
			$file_vk_12=MY_WP_SOGRID_DIRNAME.'tmp/vkontakte.error';
			
			$vk_a=get_option("_my_sogrid_vkontakte_access_token");
			$vk_e=get_option("_my_sogrid_vkontakte_access_token_expires");
			/*if(!empty($vk_a)){
				?>
				<div class="error">
				<p><strong><?php echo __("VKontakte Accces token will expire","wp_my_sogrid_domain");?></strong>
				<?php echo date('Y/m/d H:i:s',$vk_e);?>
				<?php 
				$vkontakte_client_id=wp_my_sogrid_get_option_by_key('vkontakte_client_id');
				$red_url_12=wp_my_sogrid_get_site_url().'?my_vkontakte_12_12_get_code=1';
				$my_url_12='https://oauth.vk.com/authorize?client_id='.$vkontakte_client_id;
				$my_url_12.='&scope=offline&redirect_uri='.urlencode($red_url_12);
				$my_url_12.='&response_type=code&v='.urlencode('5.33');
				?>
				<a href="<?php echo $my_url_12;?>" target="_blank"><?php echo __("Get Access Token","wp_my_sogrid_domain");?></a>
	
				</p>
				</div>
				<?php 
			}*/	
			if(file_exists($file_vk_12)){
					$c_vk_12=file_get_contents($file_vk_12);
				
				?>
				<div class="error">
				<p><strong><?php echo __("VKontakte Error","wp_my_sogrid_domain");?></strong>
				<?php echo $c_vk_12;?>
				</p>
				</div>
				<?php 
			}		
			if(!is_writable($file)){
				//echo $file.' '.ABSPATH;
				$rel_path=$file;//str_ireplace(ABSPATH, '', $file);
				?>
				<div class="error">
					<p><strong><?php echo __("Error","wp_my_sogrid_domain");?></strong>
					<?php echo __("Please make a folder ","wp_my_sogrid_domain").' '.$rel_path;echo __(" writeable by server.","wp_my_sogrid_domain");?>
					</p>
					</div>
				<?php 
			}
			$this->do_we_have_twitter_api();
			if(!$this->has_twitter_api){
				$page=@$_GET['page'];
				$my_do=false;
				if(!empty($page)&&$page=='my-sogrid-general-options'){
					$my_do=true;
				}
				if($my_do){				
					if(isset($this->social_modules['twitter'])){
						unset($this->social_modules['twitter']);
					}
					?>
						<div class="error">
							<p><strong><?php echo __("Error","wp_my_sogrid_domain");?></strong>
								<?php echo __("Please add your twitter API data in the option page !","wp_my_sogrid_domain");?>
								<a href="<?php echo admin_url('admin.php?page=my-sogrid-general-options')?>"><?php echo __("Add API keys","wp_my_sogrid_domain");?></a>
							</p>
						</div>
				<?php 
				}
			}
			
			
		}
		function do_we_have_twitter_api(){
			if(empty($this->options['twitter_consumer_key'])
				||empty($this->options['twitter_consumer_secret'])
				||empty($this->options['twitter_oauth_token'])
				||empty($this->options['twitter_oauth_secret_token'])
				){
					$this->has_twitter_api=false;
			
			}
			else $this->has_twitter_api=true;
		}
		function admin_ajax(){
			global $my_wp_sogrid_debug;
			global $my_wp_sogrid_debug_data;
			$my_action=@$_REQUEST['my_action'];
			$file=MY_WP_SOGRID_CONTROLLERS_DIRNAME.'class-wp-my-sogrid-backend-controller.php';
			require_once $file;
			
			$options=array(
				'ajax_function'=>$my_action,
				'is_ajax'=>true,
				'function'=>$my_action,
				'cap'=>$this->pages['my-sogrid-index']['cap'],
				
			);
			if($my_action=='save_sogrid'){
				$options['nonce_str']='my_sogrid_save_'.get_current_user_id();
				$options['nonce_key']='my_save_nonce';	
			}
			$this->controller=new Class_Wp_My_SoGrid_Backend_Controller($options);
			$ret=$this->controller->route();
			if($my_wp_sogrid_debug){
				$ret['debug_data']=$my_wp_sogrid_debug_data;
			}
			echo json_encode($ret);
			exit();
		}
		/**
		 * Get Social Modules
		 * @return multitype:multitype:string
		 */
		function get_social_modules(){
			return $this->social_modules;
		}
		/**
		 * Activate plugin
		 */
		static function activate(){
			//require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				
			global $wpdb;
			$table_name = $wpdb->base_prefix . 'sogrid_objects';
			if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) 
			{
				$sql="CREATE TABLE IF NOT EXISTS ".$table_name."
									(ID bigint(20) NOT NULL AUTO_INCREMENT,
										title tinytext NOT NULL COLLATE utf8_general_ci,
										PRIMARY KEY (id)
										)";
				//dbDelta($sql);
				$wpdb->query($sql);
			}
			$table_name = $wpdb->base_prefix . 'sogrid_object_meta';
		
			if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) 
			{
				$sql="CREATE TABLE IF NOT EXISTS ".$table_name." (
  									meta_id bigint(20) unsigned NOT NULL auto_increment,
  									object_id bigint(20) unsigned NOT NULL default '0' COLLATE utf8_general_ci,
  									meta_key varchar(255) default NULL COLLATE utf8_general_ci,
  									meta_value longtext COLLATE utf8_general_ci,
  									PRIMARY KEY  (meta_id),
  									KEY object_id (object_id),
  									KEY meta_key (meta_key)
					)";
				//dbDelta($sql);	
				$wpdb->query($sql);
			}
			/*
			$table_name = $wpdb->base_prefix . 'sogrid_cache';
			if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name){
					$sql="CREATE TABLE IF NOT EXISTS ".$table_name." (
  									ID bigint(20) NOT NULL AUTO_INCREMENT,
									object_id char(255) COLLATE utf8_general_ci,
									network char(255) COLLATE utf8_general_ci,
									dir char(255) COLLATE utf8_general_ci,
									valid datetime,
									file char(255) COLLATE utf8_general_ci,
									type char(10) COLLATE utf8_general_ci,
									sogrid_id bigint(20) NOT NULL,
									PRIMARY KEY  (ID),
  									KEY object_id (object_id),
  									KEY sogrid_id (sogrid_id)
					)";
					dbDelta($sql);

			}*/
			
			
		}
	}
	
}
if(class_exists('Class_My_Wp_SoGrid_Main_Class')){
	global $Class_My_Wp_SoGrid_Main_Class;
	$Class_My_Wp_SoGrid_Main_Class=new Class_My_Wp_SoGrid_Main_Class();
	$Class_My_Wp_SoGrid_Main_Class->init();
	
}

