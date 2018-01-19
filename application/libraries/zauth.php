<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Zauth {

	private $login;
	private $pass;

         public function __construct()
        {
			$CI =& get_instance();
			$CI->load->helper(array('email'));
			$CI->load->library(array('encryption'));
			$CI->load->model(array('zauth_model'));
			$CI->load->config('zauth_config');
			define('ZENKEY', $CI->config->config['encryption_key']);
        }
		
		public function index(){
			
		}
		
		public function login($email, $password, $username=NULL, $remenber = TRUE){
			$password = $password.ZENKEY;
			
			return password_verify($password, $hash);
		}
		
		public function register($email, $password, $username=NULL){
						//Vérifions si l'email entré est valide
						
			dump($this->zauth_model->get_by('email', $email));
			if(!(valid_email($email))){
				$this->session->set_flashdata("Le format de votre adresse email n'est pas valide");
			}
			//On vérifie si le login n"a aps encore été utilisé
			else if($this->_uniqueEmail($email)){
				$this->session->set_flashdata("Cette adresse email a déjà été utilisé");
			}
			else{
					$this->CI->zauth_model->insert(array(
					'email' => $email,
					'password' => $this->_cryptPass($password),
					'username' => (is_null($username))?$email:$username,
					'created' => time()
				));
			}
			
		}
		
		
		private function _uniqueEmail($email){
			if($this->CI->zauth_model->get_by('email', $email)){
				return true;
			}
			else{
				return false;
			}
		}
		//Fonction pour crypter le mot de passe
		private function _cryptPass($password){
			$password = password_hash($password.ZENKEY, PASSWORD_DEFAULT);
			return $password;
		}
		
		
		private function generate_zverifkey(){
			
		}
		
		private function recoverpass($id){
			
		}
}
