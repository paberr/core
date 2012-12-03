<?php
 /*
 * Project:     EQdkp Plus Patcher
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2010-05-27 13:31:17 +0200 (Do, 27. Mai 2010) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2007-2009 sz3
 * @link        http://eqdkp-plus.com
 * @package     plus patcher
 * @version     $Rev: 7900 $
 *
 * $Id: update_07012.class.php 7900 2010-05-27 11:31:17Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
  header('HTTP/1.0 404 Not Found');exit;
}

include_once($eqdkp_root_path.'maintenance/includes/sql_update_task.class.php');

class update_07016 extends sql_update_task {
  public $author = 'Wallenium';
  public $version = '0.7.0.16'; //new plus-version
  public $name = '0.7.0.16 Developer-Update';

  public function __construct(){
  	parent::__construct();
  	
  	$this->langs = array(
  		'english' => array(
  			'update_07016' => 'eqDKP Plus 0.7.0.16 Developer-Update package',
  			'task01' => 'Alter user-Table (add user timezone)',
		),
		'german' => array(
			'update_07016' => 'eqDKP Plus 0.7.0.16 Developer-Update Paket',
			  'task01' => 'Erweitere user-Tabelle (füge Benutzerzeitzone hinzu)',
		),
	);

    $this->sqls = array(
				'task01'	=> "ALTER TABLE __users ADD `user_timezone` varchar(255) COLLATE utf8_bin NULL",
	);
  }
}
?>