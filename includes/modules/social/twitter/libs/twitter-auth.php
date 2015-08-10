<?php
if(!defined('ABSPATH'))die('');
if(!class_exists('Class_Wp_My_SoGrid_Twitter_Api')){
	class Class_Wp_My_SoGrid_Twitter_Api{
		private $http_code;
		private $url;
		private $timeout=30;
		private $conn_timeout=30;
		private $format='json';
		private $useragent="Twiiter API v1.0";
		private $consumer_key;
		private $consumer_secret;
		private $oauth_token;
		private $oauth_token_secret;
		private $host='https://api.twitter.com/1.1/';
		private $http_method='get';
		private $parameters;
		private $rest;
		private $oauth_signature_method="HMAC-SHA1";
		private $get_options;
		private $my_debug=true;
		private $include_rts;
		private $exclude_replies;
		private $count;
		private $q;
		private $id;
		function Class_Wp_My_SoGrid_Twitter_Api($options=array()){
			if(!empty($options)){
				foreach($options as $k=>$v){
					$this->$k=$v;
				}
			}
			if(empty($this->get_options)){
				$this->get_options=array(
						'timeout'=>$this->timeout,
						
				);
			}
			$this->parameters=array();
			if($this->rest=='lists/list'){
				$this->parameters['screen_name']=$this->screen_name;
			}
			else if($this->rest=='list/show'){
				$this->parameters['slug']=$this->list_slug;
				$this->parameters['owner_screen_name']=$this->owner_screen_name;
			}
			else if(isset($this->id)&&($this->rest=='statuses/user_timeline')){
				$this->parameters['screen_name']=$this->id;
			}else if(isset($this->id)&&($this->rest=='lists/statuses')){
				$this->parameters['list_id']=$this->id;
			}else {
				$this->parameters['q']=$this->q;
			}
			$this->parameters['oauth_version']='1.0';
			$this->parameters['oauth_nonce']=$this->generate_nonce();
			$this->parameters['oauth_timestamp']=$this->generate_timestamp();
			$this->parameters['oauth_consumer_key']=$this->consumer_key;
			if(!empty($this->oauth_token)){
				$this->parameters['oauth_token']=$this->oauth_token;
			}
			if(isset($this->include_rts)){
				$this->parameters['include_rts']=$this->include_rts;
			}
			if(isset($this->exclude_replies)){
				$this->parameters['exclude_replies']=$this->exclude_replies;
			}
			if(isset($this->count)){
				$this->parameters['count']=$this->count;
			}
			
			
		}
		private function generate_timestamp() {
			return time();
		}
		
		/**
		 *Generate nonce
		 */
		private function generate_nonce(){
			$mt = microtime();
			$rand = mt_rand();
			
			return md5($mt . $rand); 
		}
		/**
		 * Call get api
		 * @param unknown $url
		 */
		public function callApi(){
			/**
			  * Api url		
			 */
			$url=$this->host.$this->rest.'.'.$this->format;
			$this->url=$url;
			if($this->my_debug){
				//wp_my_sogrid_debug_object("Api url", $this->url);
			}
			$this->setParametar("oauth_signature_method", $this->oauth_signature_method);
			$sign=$this->buildSignature();
			$this->setParametar("oauth_signature", $sign);
			$query=$this->buildQuery($this->parameters);
			if($this->my_debug){
				//wp_my_sogrid_debug_object("Query", $query);
			}
			$url.='?'.$query;		
			$data=wp_remote_get($url,$this->get_options);
			if($this->my_debug){
				//wp_my_sogrid_debug_object("Call Api", $data);
			}
			return $data;
			
			
		}
		/*
		 * end
		 */
		private function setParametar($key,$val){
			$this->parameters[$key]=$val;
		}
		/**
		 * Url encode
		 * @param unknown $input
		 * @return multitype:|mixed|string
		 */
		private function urlEncode($input){
			if (is_array($input)) {
				return array_map(array(&$this, 'urlEncode'), $input);
			} else if (is_scalar($input)) {
				return str_replace(
						'+',
						' ',
						str_replace('%7E', '~', rawurlencode($input))
				);
			} else {
				return '';
			}
		}
		/**
		 * Build http query
		 */
		private function buildQuery($params){
			if (empty($params)) return '';
			
			// Urlencode both keys and values
			$keys = $this->urlEncode(array_keys($params));
			$values =$this->urlEncode(array_values($params));
			$params = array_combine($keys, $values);
			
			// Parameters are sorted by name, using lexicographical byte value ordering.
			// Ref: Spec: 9.1.1 (1)
			uksort($params, 'strcmp');
			
			$pairs = array();
			foreach ($params as $parameter => $value) {
				if (is_array($value)) {
					// If two or more parameters share the same name, they are sorted by their value
					// Ref: Spec: 9.1.1 (1)
					natsort($value);
					foreach ($value as $duplicate_value) {
						$pairs[] = $parameter . '=' . $duplicate_value;
					}
				} else {
					$pairs[] = $parameter . '=' . $value;
				}
			}
			// For each parameter, the name is separated from the corresponding value by an '=' character (ASCII code 61)
			// Each name-value pair is separated by an '&' character (ASCII code 38)
			return implode('&', $pairs);
		}
		/**
		 * Get request base string
		 */
		private function getSignatureBaseString(){
			$parts = array(
					$this->normalized_http_method(),
					$this->normalized_http_url(),
					$this->signable_parameters()
			);
			
			$parts = $this->urlEncode($parts);
			
			return implode('&', $parts);
		}
		/**
		 * Normalized http method
		 */
		private function normalized_http_method(){
			return strtoupper($this->http_method);
		}
		private function signable_parameters(){
			$params=$this->parameters;
			if (isset($params['oauth_signature'])) {
				unset($params['oauth_signature']);
			}
			return $this->buildQuery($params);
		}
		/**
		 * Get normalized url
		 */
		private function normalized_http_url(){
			$parts = parse_url($this->url);
			
			$port = @$parts['port'];
			$scheme = $parts['scheme'];
			$host = $parts['host'];
			$path = @$parts['path'];
			
			$port or $port = ($scheme == 'https') ? '443' : '80';
			
			if (($scheme == 'https' && $port != '443')
			|| ($scheme == 'http' && $port != '80')) {
				$host = "$host:$port";
			}
			return "$scheme://$host$path";
			
		}
		/**
		 * Build signature
		 */
		private function buildSignature(){
			$base_string = $this->getSignatureBaseString();
			
			$key_parts = array(
					$this->consumer_secret,
					
			);
			if(!empty($this->oauth_token_secret)){
				$key_parts[]=$this->oauth_token_secret;
			}
		
			$key_parts = $this->urlEncode($key_parts);
			$key = implode('&', $key_parts);
		
			return base64_encode(hash_hmac('sha1', $base_string, $key, true));
		}
	}
}