<?php
 /*
 * Project:		EQdkp-Plus
 * License:		Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:		2009
 * Date:		$Date$
 * -----------------------------------------------------------------------
 * @author		$Author$
 * @copyright	2006-2011 EQdkp-Plus Developer Team
 * @link		http://eqdkp-plus.com
 * @package		eqdkp-plus
 * @version		$Rev$
 * 
 * $Id$
 */

if (!defined('EQDKP_INC')){
	die('Do not access this file directly.');
}

if (!class_exists('exchange_get_salt')){
	class exchange_get_salt extends gen_class {
		public static $shortcuts = array('db', 'pex'=>'plus_exchange');

		public function post_get_salt($params, $body){
			$xml = simplexml_load_string($body);
			if ($xml && $xml->user){

				$objQuery = $this->db->prepare("SELECT user_password FROM __users WHERE LOWER(username)=? AND user_active='1'")->limit(1)->execute(clean_username($xml->user));
				if ($objQuery && $objQuery->numRows){
					$row = $objQuery->fetchAssoc();
					
					if (strpos($row['user_password'], ':') !== false){
						list($user_password, $user_salt) = explode(':', $row['user_password']);
						$out = array(
							'salt'	=> base64_encode($user_salt),
						);
						return $out;
					}
				} 

				return $this->pex->error('user not found');
			}
		}
	}
}
?>