<?php
if(!defined('ABSPATH'))die('');
if(!class_exists('Class_Wp_My_SoGrid_Backend_Class')){
	class Class_Wp_My_SoGrid_Backend_Class{
		private $plugin_pages;
		private $controller;
		function Class_Wp_My_SoGrid_Backend_Class($options=array()){
			if(!empty($options)){
				foreach($options as $k=>$v){
					$this->$k=$v;
				}
			}
		}
		/**
		 * Initialize backend class
		 */
		function init(){
			add_action('admin_menu',array(&$this,'admin_menu'));
			add_action('admin_head',array(&$this,'admin_head'));
		}
		
		/**
		 * Admin menu
		 */
		function admin_menu(){
			$c=1;
			
			foreach($this->plugin_pages as $k=>$v){
				$my_slug=$k;
				$title=$v['title'];
				$cap=$v['cap'];
				
				$page=add_menu_page($title,$title,$cap,$my_slug,array($this,'admin_page_'.$c));
				add_action('load-'.$page,array(&$this,'load_styles'));
				add_action('load-'.$page,array(&$this,'load_index_scripts'));
				
				if(!empty($v['subpages'])){
					foreach($v['subpages'] as $k1=>$v1){
						$c++;
						$my_slug=$k1;
						$title=$v1['title'];
						$cap=$v1['cap'];
						$subpage=add_submenu_page($k,$title,$title,$cap,$my_slug,array(&$this,'admin_page_'.$c));
						if($c==3){
							add_action('load-'.$subpage,array(&$this,'load_styles'));
							add_action('load-'.$subpage,array(&$this,'load_options_scripts'));
						}else {
							add_action('load-'.$subpage,array(&$this,'load_styles'));
							add_action('load-'.$subpage,array(&$this,'load_scripts'));
						}
					}
				}else $c++;
				
			}
		}
		/**
		 * Options page scripots
		 */
		function load_options_scripts(){
			wp_enqueue_script('jquery');
			wp_enqueue_script("jquery-touch-pounch");
			wp_enqueue_script("jquery-ui-core");
			wp_enqueue_script("jquery-ui-widget");
			wp_enqueue_script("jquery-ui-dialog");
			wp_enqueue_script("jquery-ui-tooltip");
			wp_enqueue_style('wp_my_sogrid_jquery_ui_css',MY_WP_SOGRID_CSS_URL.'smoothness/jquery-ui-1.9.2.custom.min.css');
				
			$url=MY_WP_SOGRID_MODULES_URL.'form/assets/form.js';
			wp_enqueue_script('wp_my_sogrid_module_form_js',$url);	
		}
		/**
		 * Load styles
		 */
		function load_styles(){
			$url=MY_WP_SOGRID_CSS_URL.'admin.css';
			wp_enqueue_style("wp_my_so_grid_admin_css",$url);
			
		}
		/**
		 * Load edit scripts
		 */
		function load_scripts(){
			wp_enqueue_script('jquery');
			/*
			 * changes 1.23.2015.
			 */
			wp_enqueue_script('iris');
			/*
			 * end changes
			 */
			wp_enqueue_script("jquery-touch-pounch");
			wp_enqueue_script("jquery-ui-core");
			wp_enqueue_script("jquery-ui-widget");
			wp_enqueue_script("jquery-ui-dialog");
			wp_enqueue_script("jquery-ui-tooltip");
			wp_enqueue_style('wp_my_sogrid_jquery_ui_css',MY_WP_SOGRID_CSS_URL.'smoothness/jquery-ui-1.9.2.custom.min.css');
			
			$url=MY_WP_SOGRID_MODULES_URL.'form/assets/form.js';
			wp_enqueue_script('wp_my_sogrid_module_form_js',$url);
			$url=MY_WP_SOGRID_JSCRIPT_URL.'admin_edit.js';
			wp_enqueue_script('wp_my_sogrid_admin_edit_js',$url);	
		}
		/**
		 * Load index sctipts
		 */
		function load_index_scripts(){
			wp_enqueue_script('jquery');	
		}
		/**
		 * Admin head
		 */
		function admin_head(){
			$page=@$_GET['page'];
			if(!empty($page)){
				if($page=='my-sogrid-edit'){
					/*
					 * changes 1.20.2015.
					 * preview url msgs preview
					 */
					?>
					<script type="text/javascript">
						jQuery(document).ready(function($){
							var o={};
							o.ajax_timeout=120000;
							o.ajax_url="<?php echo admin_url("admin-ajax.php");?>";
							o.ajax_action="my_sogrid_admin_action";
							o.msgs={};
							o.preview_url="<?php $url=get_bloginfo('url');$pos=strrpos($url,'/');$n=strlen($url)-1;if($pos!=$n)$url.='/';$url.='wp-content/plugins/sogrid/includes/views/admin/pages/preview.php?id={id}&my_preview_sogrid=1';echo $url;?>";
							o.msgs.preview_save="<?php echo wp_my_sogrid_format_str_to_jscript(__("To Preview SoGrid, please save sogrid!","wp_my_sogrid_domain"))?>";
							o.msgs.field_is_required="<?php echo wp_my_sogrid_format_str_to_jscript(__("Field {1} is required !","wp_my_sogrid_domain"))?>";
							o.msgs.hex_value="<?php echo wp_my_sogrid_format_str_to_jscript(__("HEX Value","wp_my_sogrid_domain"))?>";
							
							wpMySoGridAdmin_ins=new wpMySoGridAdmin(o);
						});
					</script>
					<?php 
					/*
					 * end changes
					 */
					
				}
			
			 if($page=='my-sogrid-general-options'){
				?>
				<script type="text/javascript">
					jQuery(document).ready(function($){
						$(".my_tooltip_form").tooltip({
							items:'div',
							content:function(){
							var html=$(this).children(".my_tooltip_content").html();
							return html;
							}
						});
						});
				</script> 
				<?php 
				}
			}
		}
		/**
		 * Admin index page
		 */
		function admin_page_1(){
			$file=MY_WP_SOGRID_CONTROLLERS_DIRNAME.'class-wp-my-sogrid-backend-controller.php';
			require_once $file;
			$options=array(
				'page'=>'my-sogrid-index',
				'function'=>'index',
				'cap'=>$this->plugin_pages['my-sogrid-index']['cap'],
				'url'=>admin_url("admin.php?page=my-sogrid-index")
			
			);
			$this->controller=new Class_Wp_My_SoGrid_Backend_Controller($options);
			$this->controller->route();
			
		}
		/**
		 * Admin edit so grid page
		 */
		function admin_page_2(){
			$file=MY_WP_SOGRID_CONTROLLERS_DIRNAME.'class-wp-my-sogrid-backend-controller.php';
			require_once $file;
			$options=array(
				'page'=>'my-sogrid-edit',
				'function'=>'edit',
				'cap'=>$this->plugin_pages['my-sogrid-index']['subpages']['my-sogrid-edit']['cap'],
				'url'=>admin_url("admin.php?page=my-sogrid-edit")
			
			);
			$this->controller=new Class_Wp_My_SoGrid_Backend_Controller($options);
			$this->controller->route();
			
		}
		function admin_page_3(){
			$file=MY_WP_SOGRID_CONTROLLERS_DIRNAME.'class-wp-my-sogrid-backend-controller.php';
			require_once $file;
			$options=array(
					'page'=>'my-sogrid-general-options',
					'function'=>'options',
					'cap'=>$this->plugin_pages['my-sogrid-index']['subpages']['my-sogrid-general-options']['cap'],
					'url'=>admin_url("admin.php?page=my-sogrid-general-options")
						
			);
			$this->controller=new Class_Wp_My_SoGrid_Backend_Controller($options);
			$this->controller->route();
				
		}
		function admin_page_4(){
			$file=MY_WP_SOGRID_CONTROLLERS_DIRNAME.'class-wp-my-sogrid-backend-controller.php';
			require_once $file;
			$options=array(
					'page'=>'my-sogrid-general-options',
					'function'=>'search_ids',
					'cap'=>$this->plugin_pages['my-sogrid-index']['subpages']['my-sogrid-general-options']['cap'],
					'url'=>admin_url("admin.php?page=my-sogrid-general-options")
		
			);
			$this->controller=new Class_Wp_My_SoGrid_Backend_Controller($options);
			$this->controller->route();
		
		}
	}
}