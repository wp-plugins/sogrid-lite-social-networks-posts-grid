<?php
if(!defined('ABSPATH'))die('');
if(!class_exists('Class_Wp_My_SoGrid_Backend_Controller')){
	class Class_Wp_My_SoGrid_Backend_Controller{
		private $cap;
		private $page;
		private $is_ajax=false;
		private $nonce_str;
		private $nonce_key; 
		private $allowed_ajax=array('save_sogrid');
		private $ajax_function;
		private $function;
		function Class_Wp_My_SoGrid_Backend_Controller($options){
			if(!empty($options)){
				foreach($options as $k=>$v){
					$this->$k=$v;
				}
			}
			$this->init();
		}
		private function init(){
			$this->data=array();
			$this->data['template_dir']=MY_WP_SOGRID_VIEWS_DIRNAME;
			
			
		}
		/**
		 * Check access for a page
		 * @param unknown_type $cap
		 */
		private function check_access($cap){
			if(!current_user_can($cap)){
				$msg='';
				ob_start();
				echo __("You can't acces this page","wp_my_sogrid_domain")
				?>
				<a href="mailto:<?php echo get_option('admin_email')?>"><?php echo __("Contact Administrator","wp_my_sogrid_domain")?></a>
				<?php 
				$msg=ob_get_clean();
				die($msg);
			}
		}
		public function route(){
			global $my_wp_sogrid_debug;
			global $my_wp_sogrid_debug_data;
			if($this->is_ajax){
				$ret['error']=0;
				$ret['msg']='';
				$str=$this->nonce_str;
				if(isset($_POST['data'])){
					$data=$_POST['data'];
					$data_arr=array();
					parse_str($data,$data_arr);
				}
				$nonce=$data_arr[$this->nonce_key];
				/**
				 * 
				 */
				if(!in_array($this->ajax_function,$this->allowed_ajax)){
					$ret['msg']=__("Error","wp_my_sogrid_domain");
					$ret['error']=1;
					if($my_wp_sogrid_debug){
						$ret['msg_error']='error not allowed function';
					}
					return $ret;
				}
				/**
				 * Check nonce
				 */
				if(!wp_verify_nonce($nonce,$str)){
					$ret['msg']=__("Error","wp_my_sogrid_domain");
					$ret['error']=1;
					if($my_wp_sogrid_debug){
						$ret['msg_error']='error nonce';
						$ret['str']=$str;
						$ret['nonce']=$nonce;
					}
					return $ret;
				}
				$function=$this->ajax_function;
				$ret=$this->$function($data_arr);
				return $ret;
			}else {
				$this->check_access($this->cap);
				$page=$this->function;
				$this->$page();
			}
		}
		/**
		 * Save so grid
		 */
		private function save_sogrid($data){
			global $my_wp_sogrid_debug;
			global $my_wp_sogrid_debug_data;
			//global $Class_My_Wp_SoGrid_Main_Class;
			//$active_networks=$Class_My_Wp_SoGrid_Main_Class->get_social_modules();
			$file=MY_WP_SOGRID_SOCIAL_MODULES_DIRNAME.'info.php';
			
			require_once $file;
			global $wp_my_sogrid_social_modules;
			$active_networks=$wp_my_sogrid_social_modules;
			//$networks=$data['my_active_networks'];
			if(empty($data['my_active_networks'])){
					$ret['msg']=__("Please select at least one social Network.","wp_my_sogrid_domain");
					
					$ret['error']=1;
					if($my_wp_sogrid_debug){
						$ret['msg_error']='error no active network';
					}
					return $ret;
				
			}else {
				$networks=$data['my_active_networks'];
				foreach($networks as $k=>$v){
					if(!array_key_exists($v,$active_networks)){
						unset($networks[$k]);
					}
				}
				if(empty($networks)){
					$ret['msg']=__("Please select at least one social Netowrk.","wp_my_sogrid_domain");
					
					$ret['error']=1;
					if($my_wp_sogrid_debug){
						$ret['msg_error']='error no active network';
					}
					return $ret;
				}else {
					if($my_wp_sogrid_debug){
						$my_wp_sogrid_debug_data['networks']=$networks;
					}
					$valid_options=wp_my_sogrid_generate_so_options($networks);
					if($my_wp_sogrid_debug){
						$my_wp_sogrid_debug_data['valid_options']=$valid_options;
					}
					$msg=wp_my_sogrid_save_sogrid($data,$networks,$valid_options);
					if($msg===true){
						$ret['msg']=__("SoGrid has been saved !","wp_my_sogrid_domain");
						$ret['error']=0;
						global $wp_my_sogrid_id;
						$ret['id']=$wp_my_sogrid_id;
					}else {
						$ret['msg']=$msg;
						$ret['error']=1;
					}
					return $ret;
					
				}
			}
		}
		/**
		 * Index action
		 */
		private function index(){
			/**
			 *	Delete sogrid
			 */
			$my_action=@$_POST['my_action'];
			if(!empty($my_action)){
				if($my_action=='delete'){
					$id=@$_POST['my_object_id'];
					if(!wp_my_sogrid_is_exist_object($id)){
						$error_msg=__("SoGrid with this id don't exists !","wp_my_sogrid_domain");
					}else {
						wp_my_so_grid_delete_sogrid($id);
						$ok_msg=__("SoGrid has been deleted!","wp_my_sogrid_domain");
							
					
					}
				}
			}
			$file=MY_WP_SOGRID_CLASS_DIRNAME.'class-wp-my-sogrid-table-view.php';
			require_once $file;
			$ret=wp_my_sogrid_get_grids(20);
			$my_table_view=new Class_Wp_My_SoGrid_Table_View(array('id'=>'my_sogrid','columns'=>$ret['columns'],'data'=>$ret,'actions'=>$ret['actions']));
			ob_start();
			echo $my_table_view->render();
			$this->data['table_html']=ob_get_clean();
			extract($this->data);
			$file=$template_dir.'admin/pages/index.php';
			require $file;
		}
		/**
		 * Edit action
		 */
		private function edit(){
			global $wp_my_sogrid_fonts;
			$wp_my_sogrid_fonts=wp_my_sogrid_get_google_fonts();
			$file=MY_WP_SOGRID_MODULES_DIRNAME.'form/class.php';
			require_once $file;
			global $wp_my_sogrid_general_options;
			global $Class_My_Wp_SoGrid_Main_Class;
			$this->data['social_modules']=$Class_My_Wp_SoGrid_Main_Class->get_social_modules();
			$this->data['general_options']=$wp_my_sogrid_general_options;
			$this->data['social']=array();
			/**
			 * Active Social networks
			 */
			$this->data['active_social']=array();
			foreach($this->data['social_modules'] as $k=>$v){
				$file=MY_WP_SOGRID_SOCIAL_MODULES_DIRNAME.$k.'/options.php';
				require_once $file;
				$name_1='wp_my_sogrid_'.$k.'_options';
				$name_2='wp_my_sogrid_'.$k.'_options_tooltips';
				$name_3='wp_my_sogrid_'.$k.'_item_options';
				global $$name_1;
				global $$name_2;
				global $$name_3;
				$this->data['social'][$k]['options']=$$name_1;
				$this->data['social'][$k]['tooltips']=$$name_2;
				$this->data['social'][$k]['item']=$$name_3;		
			}
			extract($this->data);
			$file=$template_dir.'admin/pages/edit.php';
			require $file;
		
		}
		private function search_ids(){
			extract($this->data);
			$file=$template_dir.'admin/pages/search_ids.php';
			require $file;
		}
		/**
		 * General plugin options
		 */
		private function options(){
			$file=MY_WP_SOGRID_MODULES_DIRNAME.'form/class.php';
			require_once $file;
			global $wp_my_sogrid_genereal_plugin_options;
			/**
			  * Save options	
			 */
			if(!empty($_POST['my-submit'])){
				$my_nonce_str='my_save_options_'.get_current_user_id();
				$nonce=@$_POST['my_nonce'];
				$my_do=true;
				if(empty($nonce)){
					$error_msg=__("Error nonce","wp_my_sogrid_domain");
					$my_do=false;
				}
				if(!wp_verify_nonce($nonce,$my_nonce_str)){
					$error_msg=__("Error nonce","wp_my_sogrid_domain");
					$my_do=false;

				}
				if($my_do){
					$post_arr=array();
					foreach($wp_my_sogrid_genereal_plugin_options as $k=>$v){
						$name=$k;
						$val=@$_POST[$name];
						if(isset($val)){
							$post_arr[$k]=$val;
						}
					}
					wp_my_sogrid_update_options($post_arr);
					$ok_msg=__("Options has been updated !","wp_my_sogrid_domain");
					wp_my_sogrid_get_options(true);
				}
			}
				
			$this->data['plugin_options']=$wp_my_sogrid_genereal_plugin_options;
			$saved_options=wp_my_sogrid_get_options();
			if(!empty($saved_options)){
				foreach($saved_options as $k=>$v){
					if(isset($this->data['plugin_options'][$k])){
						$this->data['plugin_options'][$k]['value']=$v;
					}
				}
			}
			extract($this->data);
			$file=$template_dir.'admin/pages/options.php';
			require $file;
		}
	}
}