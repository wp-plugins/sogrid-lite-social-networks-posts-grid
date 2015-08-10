<?php
if(!defined('ABSPATH'))die('');
if(!class_exists('Class_Wp_My_SoGrid_Facebook_Api')){
	class Class_Wp_My_SoGrid_Facebook_Api{
		private $app_secret;
		private $app_id;
		private $lib_path;
		private $session; 
		function Class_Wp_My_SoGrid_Facebook_Api($options=array()){
			if(!empty($options)){
				foreach($options as $k=>$v){
					$this->$k=$v;
				}
				
				
			}
			$this->lib_path=plugin_dir_path(__FILE__).'facebook-php-sdk/';
			require_once $this->lib_path.'autoload.php';
			
			
		}
		private function getSession(){
			FacebookSession::setDefaultApplication($this->app_id, $this->app_secret);
			$this->session = FacebookSession::newAppSession();
			
		}
		public function call_graph($type,$rest){
			if(!isset($this->session)){
				$has=$this->getSession();
				if($has===false)return false;	
				
			}
		}
		
	}
}	