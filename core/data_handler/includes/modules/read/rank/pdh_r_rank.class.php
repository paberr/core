<?php
/*
* Project:		EQdkp-Plus
* License:		Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
* Link:			http://creativecommons.org/licenses/by-nc-sa/3.0/
* -----------------------------------------------------------------------
* Began:		2009
* Date:			$Date$
* -----------------------------------------------------------------------
* @author		$Author$
* @copyright	2006-2011 EQdkp-Plus Developer Team
* @link			http://eqdkp-plus.com
* @package		eqdkpplus
* @version		$Rev$
*
* $Id$
*/

if ( !defined('EQDKP_INC') ){
	die('Do not access this file directly.');
}

if ( !class_exists( "pdh_r_rank" ) ) {
	class pdh_r_rank extends pdh_r_generic{
		public static function __shortcuts() {
		$shortcuts = array('pdc', 'db',	'game');
		return array_merge(parent::$shortcuts, $shortcuts);
	}

		public $default_lang = 'english';
		public $ranks;

		public $hooks = array(
			'adjustment_update',
			'event_update',
			'item_update',
			'member_update',
			'raid_update',
			'rank_update'
		);

		public function reset(){
			$this->pdc->del('pdh_member_ranks');
			$this->ranks = NULL;
		}

		public function init(){
			$this->ranks = $this->pdc->get('pdh_member_ranks');
			if($this->ranks !== NULL) return true;
			
			$objQuery = $this->db->query("SELECT * FROM __member_ranks ORDER BY rank_sortid ASC;");
			if($objQuery){
				while($r_row = $objQuery->fetchAssoc()){
					$this->ranks[$r_row['rank_id']]['rank_id']	= $r_row['rank_id'];
					$this->ranks[$r_row['rank_id']]['prefix']	= $r_row['rank_prefix'];
					$this->ranks[$r_row['rank_id']]['suffix']	= $r_row['rank_suffix'];
					$this->ranks[$r_row['rank_id']]['name']		= $r_row['rank_name'];
					$this->ranks[$r_row['rank_id']]['hide']		= (int)$r_row['rank_hide'];
					$this->ranks[$r_row['rank_id']]['sortid']	= (int)$r_row['rank_sortid'];
					$this->ranks[$r_row['rank_id']]['default']	= (int)$r_row['rank_default'];
					$this->ranks[$r_row['rank_id']]['icon']		= $r_row['rank_icon'];
				}
			}

			if (!isset($this->ranks[0])) $this->ranks[0] = array('rank_id' => 0,	'prefix' => '',	'suffix' => '',	'name' => '', 'hide' => 0, 'sortid' => 0);

			$this->pdc->put('pdh_member_ranks', $this->ranks);
		}

		public function get_id($name) {
			if(!empty($this->ranks)) {
				foreach($this->ranks as $id => $rank) {
					if($rank['name'] == $name) return $id;
				}
			}
			return false;
		}

		public function get_ranks(){
			return $this->ranks;
		}

		public function get_id_list(){
			return array_keys($this->ranks);
		}

		public function get_name($rank_id){
			return $this->ranks[$rank_id]['name'];
		}

		public function get_html_name($rank_id){
			return $this->game->decorate('ranks', array($rank_id)).$this->ranks[$rank_id]['name'];
		}

		public function get_rank_image($rank_id){
			$strGameFolder = 'games/'.$this->game->get_game().'/ranks/';
			$strIcon = $this->get_icon($rank_id);
			
			$rankimage = (strlen($strIcon) && is_file($this->root_path.$strGameFolder.$strIcon)) ? $this->server_path.$strGameFolder.$strIcon : "";
			return ($rankimage != "") ? '<img src="'.$rankimage.'" alt="rank image" width="20"/>' : '';
		}

		public function get_prefix($rank_id){
			return $this->ranks[$rank_id]['prefix'];
		}

		public function get_suffix($rank_id){
			return $this->ranks[$rank_id]['suffix'];
		}

		public function get_is_hidden($rank_id){
			return $this->ranks[$rank_id]['hide'];
		}
		
		public function get_sortid($rank_id){
			return $this->ranks[$rank_id]['sortid'];
		}
		
		public function get_icon($rank_id){
			return $this->ranks[$rank_id]['icon'];
		}
		
		public function get_default_value($rank_id){
			return $this->ranks[$rank_id]['default'];
		}
		
		public function get_default(){
			if(is_array($this->ranks)){
				foreach($this->ranks as $key => $val){
					if ($val['default'] == 1) return $key;
				}
			}
			
			$arrIDs = $this->get_id_list();
			return ((isset($arrIDs[0])) ? $arrIDs[0] : 0);
		}
		
	}//end class
}//end if
?>