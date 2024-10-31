<?php

if ( ! class_exists( 'NF_Base_Controller' ) ) {

    class NF_Base_Controller {
            private $wpdb;
  
            public function __construct()
            {
                global $wpdb;
            	add_action( 'admin_menu', array( $this, 'nf_init' ) );
                $this->wpdb = $wpdb;
                $this->nfLoadConfig();
 
            }
                    
            public function nf_init(){
                add_menu_page( __( 'Notifyfox', 'notifyfox' ), 
                    __( 'Notifyfox', 'notifyfox' ), 'admin_dashboard', 'notifyfox', array( $this, 'nf_admin_option'), NF_URL.'images/mail.png', 51 );
                add_submenu_page('notifyfox', __( 'Setting', NF_TDOMAIN ), 
                    __( 'Setting', NF_TDOMAIN ), "manage_options", 'nf_settings-edit', array( $this, 'nf_setting' ));
            }


	         /**
	         * [setting : This function is reponsible for generating access token on basis of client credentials and firing a help email to plugin admin if needed]
	         */	
            public function nf_setting(){
                	$nf_error_found = $nf_success = FALSE; 
					if (isset($_POST['nf_form_submit']) && $_POST['nf_form_submit'] == 'yes') {
						$nf_data['nf_c_client_id'] = isset($_POST['nf_c_client_id']) ? sanitize_text_field($_POST['nf_c_client_id']) : '';
						$nf_data['nf_c_client_secret'] = isset($_POST['nf_c_client_secret']) ? sanitize_text_field($_POST['nf_c_client_secret']) : '';
						$nf_data['nf_c_apiusername'] = isset($_POST['nf_c_apiusername']) ? sanitize_text_field($_POST['nf_c_apiusername']) : '';
						$nf_data['nf_c_apipassword'] = isset($_POST['nf_c_apipassword']) ? sanitize_text_field($_POST['nf_c_apipassword']) : '';
						$nf_data['nf_c_id'] = isset($_POST['nf_c_id']) ? sanitize_text_field($_POST['nf_c_id']) : '1';
						if ($nf_data['nf_c_client_id'] == '') {
							$nf_errors[] = __( 'Please enter Client ID.', NF_TDOMAIN );
							$nf_error_found = TRUE;
						}
						if ($nf_data['nf_c_client_secret'] == '') {
							$nf_errors[] = __( 'Please enter Client Secret.', NF_TDOMAIN );
							$nf_error_found = TRUE;
						}
						if ($nf_data['nf_c_apiusername'] == '') {
							$nf_errors[] = __( 'Please enter API Username.', NF_TDOMAIN );
							$nf_error_found = TRUE;
						}
						if ($nf_data['nf_c_apipassword'] == '') {
							$nf_errors[] = __( 'Please enter API Password.', NF_TDOMAIN );
							$nf_error_found = TRUE;
						}
						//	No errors found, we can add this Group to the table
						if ($nf_error_found == FALSE) {	
							$nf_response = $this->nf_createAccessToken($nf_data['nf_c_apiusername'],$nf_data['nf_c_apipassword'],$nf_data['nf_c_client_id'],$nf_data['nf_c_client_secret']);
							if(isset($nf_response['error']) && $nf_response['error'] == 'invalid_grant'){
								 	$nf_errors[] = __( 'Wrong API Detail. Please enter another detail.!', NF_TDOMAIN );
								 	$nf_error_found = TRUE;
							}elseif(isset($nf_response['data']) && $nf_response['data'] == 'Unauthorized'){
								 	$nf_errors[] = __( 'Wrong API Detail. Please enter another detail.!', NF_TDOMAIN );
								 	$nf_error_found = TRUE;
							}else{
								$nf_resp = $this->nf_get_client_id($nf_response['access_token']);
								if(!empty($nf_resp)){
									$this->model->nf_updateData($nf_data['nf_c_apiusername'],$nf_data['nf_c_apipassword'],$nf_data['nf_c_client_id'],$nf_data['nf_c_client_secret'],$nf_resp['data'][0]['project_id'],0);
									$this->nf_setAccessToken($nf_response['access_token'],$nf_response['refresh_token'],$nf_response['expires_in']);
									$nf_success = __( 'Settings Saved.', NF_TDOMAIN );
								}else{
								 	$nf_errors[] = __( 'Something went wrong please try again!', NF_TDOMAIN );
								 	$nf_error_found = TRUE;
								}
							}
						}
					}
					if (isset($_POST['helpSubmitButton'])) { 
						if (!empty($_POST['nf_form_help_name']) && !empty($_POST['nf_form_help_email']) && !empty($_POST['nf_form_help_message'])) {
							$nf_message = "<table style='width: 100%;'><tr><th style='border: 1px solid #dddddd;'>Name</th><th style='border: 1px solid #dddddd;'>Email</th><th style='border: 1px solid #dddddd;'>Message</th></tr><tr><td style='border: 1px solid #dddddd;'>".sanitize_text_field($_POST['nf_form_help_name'])."</td><td style='border: 1px solid #dddddd;'>".sanitize_email($_POST['nf_form_help_email'])."</td><td style='border: 1px solid #dddddd;'>".sanitize_text_field($_POST['nf_form_help_message'])."</td></tr></table>";

							$nf_to = "admin@notifyfox.com";
							$nf_subject = " Need help from notifyfox plugin";
							$nf_headers = array('Content-Type: text/html; charset=UTF-8');
							$nf_mail_status = wp_mail($nf_to,$nf_subject,$nf_message,$nf_headers);
							if ($nf_mail_status) {
								$nf_success = __( ' Mail sent.', NF_TDOMAIN);
							} else {
								$nf_errors[] = __( ' Mail sent failed. Try again.', NF_TDOMAIN );

							}
						} else {
							$nf_errors[] = __( 'Please provide all the fields.', NF_TDOMAIN );
						}
					}
                if ($nf_error_found == FALSE){
                	$nf_form = $this->model->nf_getData();
                }else{
                	$nf_form = new stdClass();
                	$nf_form->nf_c_apiusername = $nf_data['nf_c_apiusername'];
                	$nf_form->nf_c_apipassword = $nf_data['nf_c_apipassword'];
                	$nf_form->nf_c_client_id = $nf_data['nf_c_client_id'];
                	$nf_form->nf_c_client_secret = $nf_data['nf_c_client_secret']; 
                }
                require NF_APP . '/view/setting/edit.php';
            }
           
            /**
			 * Creates an access token.
			 *
			 * @return     Array  ( response Array )
			 */
			public function nf_createAccessToken($usid,$pass,$nf_cid,$nf_cs)
			{	
				$nf_data = array('grant_type' => 'password', 'client_id' => $nf_cid, 'client_secret' => $nf_cs , 'username' => $usid, 'password' => $pass);
				$nf_url = NF_NODE_URL.'/api/oauth/token';
				$nf_response = self::nf_initCurl($nf_url,true,$nf_data);
				if ( 401 == $nf_response['info']['http_code'] ){
						$nf_response['data'] = 'Unauthorized';
				} elseif ( 200 == $nf_response['info']['http_code'] ) {
						$nf_response = json_decode($nf_response['data'], true);
				} else {
					$nf_response = self::nf_initCurl($nf_url,true,$nf_data);
					if(200 == $nf_response['info']['http_code']){
						$nf_response = json_decode($nf_response['data'], true);
					}else{
						$nf_response = json_decode($nf_response['data'], true);
					}	
				}
				return $nf_response;
			}

			
			public function nf_add_manifest(){
				echo '<link rel="manifest" href="' . get_home_url() . '/manifest.json">';
			}

            /**
             * Loads the "model".
             * @return object model
             */
            public function nfLoadConfig()
            {	
                wp_register_style( 'nf-style', NF_URL . 'css/nf-style.css' );
	     		wp_enqueue_style( 'nf-style' );
         		wp_register_script( 'nf-script', NF_URL . 'js/nf-script.js' );
				wp_enqueue_script( 'nf-script' );
	            require NF_APP . 'model/model.php';
                $this->model = new Model();
                $nf_resp = $this->model->nf_checkPlugin();
                if(!is_admin() && ($nf_resp->nf_c_client_id != NULL || $nf_resp->nf_c_client_id != "")):
					add_action('wp_head', array(__CLASS__, 'nf_add_manifest'), 5);
					wp_register_script( 'nf-cdnpath', NF_CDN_PATH.'/upload/cloader/'.$nf_resp->nf_p_id.'.js' );
					wp_enqueue_script( 'nf-cdnpath' );
                endif;
            }

		    /**
			 * { function_description }
			 *
			 * @param      String  $nf_url       The url
			 * @param      Boolean $postdata  The postdata
			 * @param      Array   $nf_data      The data
			 *
			 * @return     array   ( description_of_the_return_value )
			 */
			public function nf_initCurl($nf_url,$postdata,$nf_data=null)
			{
				global $wp_version;
				if(true == $postdata){
					$nf_args = array(
					    'method' => 'POST',
					    'timeout' => 45,
					    'redirection' => 5,
					    'httpversion' => '1.0',
					    'user-agent'  => 'WordPress/' . $wp_version . '; ' . home_url(),
					    'blocking'    => true,
					    'headers'     => array(),
					    'body'		  => $nf_data,
					    'sslverify'   => false,
					); 
				}else{
					$nf_args = array(
					    'method' => 'POST',
					    'timeout' => 45,
					    'redirection' => 5,
					    'httpversion' => '1.0',
					    'user-agent'  => 'WordPress/' . $wp_version . '; ' . home_url(),
					    'blocking'    => true,
					    'headers'     => array(),
					    'body'        => null,
					    'sslverify'   => false,
					); 
				}
				$response = wp_safe_remote_post( $nf_url, $nf_args);
				if ( is_wp_error( $response ) ) {
			  			$nf_return_array['data'] = $response->get_error_message();
						$nf_return_array['info']['http_code'] = 401;
						$nf_return_array['error'] = 'invalid_grant';
				} else {
						$nf_return_array['data'] = $response['body'];
						$nf_return_array['info']['http_code'] = $response['response']['code'];
						if(isset($response['response']['code']) && 403 == $response['response']['code'])
							$nf_return_array['error'] = 'invalid_grant';
						else
							$nf_return_array['error'] = 0;
				}
				return $nf_return_array;	

			}
			
			/**
			 * Sets the access token.
			 *
			 * @param      <type>  $nf_access_token  The access token
			 *
			 * @return     <type>  ( description_of_the_return_value )
			 */
			public function nf_setAccessToken($nf_access_token,$nf_refresh_token,$nf_expires_in){
				$_SESSION['nf_access_token']  = $nf_access_token;
				$_SESSION['nf_refresh_token'] = $nf_refresh_token;
				$_SESSION['nf_expires_in']    = (time() + $nf_expires_in);
				return true;
			}

			/**
			 * Gets the access token.
			 *
			 * @return     <type>  The access token.
			 */
			public function nf_getAccessToken(){
				return $_SESSION['nf_access_token'];
			}
			
			/**
			 * Gets the refresh token.
			 *
			 * @return     <type>  The refresh token.
			 */
			public function nf_getRefreshToken(){
				return $_SESSION['nf_refresh_token'];
			}


			/**
			 * { function_description }
			 *
			 * @return     String  ( access_token )
			 */
			public function nf_checkSession()
			{

				if(isset($_SESSION['nf_access_token'])){
					return $nf_access_token = $this->nf_getAccessToken(); 
				}else{
					$nf_resp = $this->model->nf_checkPlugin();
					$nf_response = $this->nf_createAccessToken($nf_resp->nf_c_apiusername,$nf_resp->nf_c_apipassword,$nf_resp->nf_c_client_id,$nf_resp->nf_c_client_secret);
					if((isset($nf_response['data']) && $nf_response['data'] == 'Unauthorized') || (isset($nf_response['error']) && $nf_response['error'] == 'invalid_grant') ){
						return 401;
					}else if(isset($nf_response['access_token'])){
						$this->nf_setAccessToken($nf_response['access_token'],$nf_response['refresh_token'],$nf_response['expires_in']);	
						return $nf_response['access_token']; 
					}else{
						$this->nf_get_access_token();
					}
				}
			}


			
			/**
			 * { function_description }
			 *
			 * @param      <type>  $nf_refresh_token  The refresh token
			 *
			 * @return     array   ( description_of_the_return_value )
			 */
			public function nf_refreshAccessToken($nf_refresh_token)
			{
				$nf_return_data = array();	
				$nf_resp = $this->model->nf_checkPlugin();
				$nf_cid = $nf_resp->nf_c_client_id;
				$nf_cs = $nf_resp->nf_c_client_secret;
				$nf_data = array('grant_type' => 'nf_refresh_token', 'client_id' => $nf_cid, 'client_secret' => $nf_cs , 'nf_refresh_token' => $nf_refresh_token);
				$nf_url = NF_NODE_URL.'/api/oauth/token';
				$nf_response = self::nf_initCurl($nf_url,true,$nf_data);
				if ( 200 == $nf_response['info']['http_code'] ) {
						$nf_return_data = json_decode($nf_response['data'], true);
				} else {
					$nf_response = self::nf_initCurl($nf_url,true,$nf_data);
					if(200 == $nf_response['info']['http_code']){
						$nf_return_data = json_decode($nf_response['data'], true);
					}	
				}
				return $nf_return_data;
			}

			/**
			 * { function_description }
			 *
			 * @return     integer  ( description_of_the_return_value )
			 */
			public function nf_checkTokenExpiration(){
				$nf_status = 0;
				if(isset($_SESSION['nf_expires_in'])){
					if(time() < $_SESSION['nf_expires_in'])
						$nf_status = 1;
					else
						$nf_status = 2;
				}	
				return $nf_status;
			}

			/**
			 * Gets the access token.
			 *
			 * @return     <type>  The access token.
			 */
			function nf_get_access_token()
			{
				$nf_access_token = $this->nf_checkSession();
				if($nf_access_token == 401)
					$nf_expiration = 401;
				else	
					$nf_expiration = $this->nf_checkTokenExpiration();
				switch ($nf_expiration) {
					case 0:
						unset($_SESSION['nf_access_token']);
						self::nf_get_access_token();
						break;
					case 1:
						break;
					case 2:
						break;
						$nf_response = $this->nf_refreshAccessToken($_SESSION['nf_refresh_token']);
						$this->nf_setAccessToken($nf_response['access_token'],$nf_response['refresh_token'],$nf_response['expires_in']);	
						$nf_access_token = $nf_response['access_token']; 
					case 401:
						$nf_access_token = 0;
						break;	
					default:
						unset($_SESSION['nf_access_token']);
						break;
				}
				return $nf_access_token;
			}
			
			/**
			 * Gets the client identifier.
			 *
			 * @param      <type>  $nf_access_token  The access token
			 *
			 * @return     array   The client identifier.
			 */
			function nf_get_client_id($nf_access_token)
			{
				$nf_return_data = array();
				$nf_url = NF_NODE_URL.'/subscriber/client';
				$nf_data = array('access_token' => $nf_access_token);
				$nf_response = self::nf_initCurl($nf_url,true,$nf_data);
				if ( 200 == $nf_response['info']['http_code'] ) {
						$nf_return_data = json_decode($nf_response['data'], true);
				} 
				return $nf_return_data;
			}
    } 
}
