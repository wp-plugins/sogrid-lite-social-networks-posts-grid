<?php
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
/**
 * Get facebook session
 * @return \Facebook\FacebookSession
 */
function my_facebook_sdk_get_session(){
	global $my_app_id;
	global $my_app_key;
	FacebookSession::setDefaultApplication($my_app_id, $my_app_key);
	$session = FacebookSession::newAppSession();
	try {
		$session->validate();
		return $session;
	} catch (FacebookRequestException $ex) {
		// Session not valid, Graph API returned an exception with the reason.
		return $ex->getMessage();
	} catch (\Exception $ex) {
		// Graph API returned info, but it may mismatch the current app or have expired.
		return $ex->getMessage();
	}
	
}
/**
 * Get facebook posts
 * @param unknown $page_id
 * @param unknown $limit
 * @return string
 */
function my_facebook_get_page_posts($page_id,$limit){
	try{
		global $facebook_session;
		if(is_object($facebook_session)){
		$response=(
				new FacebookRequest($facebook_session,'GET','/'.$page_id.'/posts',array('limit'=>$limit))
		)->execute();
		//print_r($response);
		$object=$response->getGraphObject()->asArray();
		//print_r($object);
		return $object;
		//print_r($object);
		
		}else return 'Error session';
	}
	catch (FacebookRequestException $ex) {
		return $ex->getMessage();
	} catch (\Exception $ex) {
		return $ex->getMessage();
	}
}
/**
 * Get page photos
 * @param unknown $page_id
 * @param unknown $limit
 * @return multitype:|string
 */
function my_facebook_get_page_photos($page_id,$limit){
	try{
		global $facebook_session;
		if(is_object($facebook_session)){
			$response=(
					new FacebookRequest($facebook_session,'GET','/'.$page_id.'/photos',array('limit'=>$limit))
			)->execute();
			//print_r($response);
			$object=$response->getGraphObject()->asArray();
			//print_r($object);
			return $object;
			//print_r($object);

		}else return 'Error session';
	}
	catch (FacebookRequestException $ex) {
		return $ex->getMessage();
	} catch (\Exception $ex) {
		return $ex->getMessage();
	}
}
/**
 * Get post attachments
 * @param unknown $post_id
 * @return multitype:|string
 */
function my_facebook_get_post_attachments($post_id){
	try{
		global $facebook_session;
		if(is_object($facebook_session)){
			$response=(
					new FacebookRequest($facebook_session,'GET','/'.$post_id.'/attachments')
			)->execute();
			//print_r($response);
			$object=$response->getGraphObject()->asArray();
			//print_r($object);
			
			return $object;
			//print_r($object);

		}else return 'Error session';
	}
	catch (FacebookRequestException $ex) {
		return $ex->getMessage();
	} catch (\Exception $ex) {
		return $ex->getMessage();
	}
}
/**
 * Get facebook likes
 */
function my_facebook_get_facebook_likes($post_id){
	try{
		global $facebook_session;
		if(is_object($facebook_session)){
			$response=(
					new FacebookRequest($facebook_session,'GET','/'.$post_id.'/likes',array('limit'=>1,'summary'=>true))
			)->execute();
			//print_r($response);
			$object=$response->getGraphObject()->asArray();
			//print_r($object);
				
			return $object;
			//print_r($object);

		}else return 'Error session';
	}
	catch (FacebookRequestException $ex) {
		return $ex->getMessage();
	} catch (\Exception $ex) {
		return $ex->getMessage();
	}
}
/**
 * Get post attachments
 * @param unknown $post_id
 * @return multitype:|string
 */
function my_facebook_get_user_albums($user_id){
	try{
		global $facebook_session;
		if(is_object($facebook_session)){
			$response=(
					new FacebookRequest($facebook_session,'GET','/'.$user_id.'/albums')
			)->execute();
			//print_r($response);
			$object=$response->getGraphObject()->asArray();
			//print_r($object);
				
			return $object;
			//print_r($object);

		}else return 'Error session';
	}
	catch (FacebookRequestException $ex) {
		return $ex->getMessage();
	} catch (\Exception $ex) {
		return $ex->getMessage();
	}
}
function my_facebook_get_picture($post_id){
	try{
		global $facebook_session;
		if(is_object($facebook_session)){
			$response=(
					new FacebookRequest($facebook_session,'GET','/'.$post_id.'/picture',array('type'=>'normal'))
			)->execute();
			//print_r($response);
			$object=$response->getGraphObject()->asArray();
			//print_r($object);

			return $object;
			//print_r($object);

		}else return 'Error session';
	}
	catch (FacebookRequestException $ex) {
		return $ex->getMessage();
	} catch (\Exception $ex) {
		return $ex->getMessage();
	}
}

global $facebook_session;
$facebook_session=my_facebook_sdk_get_session(); 
//print_r($facebook_session);
