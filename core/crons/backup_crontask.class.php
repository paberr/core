<?php
 /*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:	     	http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2009
 * Date:        $Date$
 * -----------------------------------------------------------------------
 * @author      $Author$
 * @copyright   2009 sz3
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev$
 * 
 * $Id$
 */

if ( !defined('EQDKP_INC') ){
  header('HTTP/1.0 404 Not Found');exit;
}

if ( !class_exists( "backup_crontask" ) ) {
  class backup_crontask extends crontask {
		
		public function __construct(){
			$this->defaults['repeat'] = true;
			$this->defaults['repeat_type'] = 'daily';				
			$this->defaults['editable'] = true;
			$this->defaults['delay'] = false;
			$this->defaults['ajax'] = false;     
			$this->defaults['description'] = 'MySQL Backup';
    }
		
		
    public function run(){
      global $core, $db, $pcache, $backup;
  		
			$backup->create();
  		
    }	
  }
}
?>