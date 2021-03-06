<?php
/*	Project:	EQdkp-Plus
 *	Package:	EQdkp-plus
 *	Link:		http://eqdkp-plus.eu
 *
 *	Copyright (C) 2006-2016 EQdkp-Plus Developer Team
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU Affero General Public License as published
 *	by the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU Affero General Public License for more details.
 *
 *	You should have received a copy of the GNU Affero General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class core extends gen_class {

	public $error_message			= array();			// Array of errors		@public $error_message
	public $header_inc				= false;			// Printed header?		@public $header_inc
	public $tail_inc				= false;			// Printed footer?		@public $tail_inc
	public $template_file			= '';				// Template filename	@public $template_file
	public $error_template_file		= '';				// Error Tp filename	@public $template_file
	public $default_game			= '';				// Defaultgame			@public $default_game
	public $game_language			= '';				// Defaultgame			@public $default_game
	public $icon_error				= '<i class="icon-red fa fa-times"></i>';
	public $icon_ok					= '<i class="icon-green fa fa-check"></i>';

	public function StatusIcon($mystat= 'ok') {
		return ($mystat=='ok') ? $this->icon_ok : $this->icon_error;
	}

	public function __construct($template, $template_file, $error_template_file){
		$this->template_file = $template_file;
		$this->error_template_file = $error_template_file;

		$this->tpl->set_template($template);
		$this->tpl->assign_vars(array(
			'TYEAR'				=> $this->time->date('Y', time()),
			'MSG_TITLE'			=> '',
			'MSG_TEXT'			=> '')
		);

		$this->tpl->set_filenames(array(
			'body' => $this->template_file)
		);
	}

	public function message_die($text = '', $title = ''){
		$this->tpl->set_filenames(array(
			'body'			=> $this->error_template_file
		));
		$this->tpl->assign_vars(array(
			'MSG_TITLE'		=> ( $title != '' ) ? $title : '&nbsp;',
			'MSG_TEXT'		=> ( $text  != '' ) ? $text  : '&nbsp;',
		));
		if ( !$this->header_inc ){
			$this->page_header();
		}
		$this->page_tail();
	}

	public function check_auth(){
		if(defined('EQDKP_UPDATE') && EQDKP_UPDATE) return true;

		if (!$this->user->check_auth('a_maintenance', false)){
			if ($this->config->get('pk_maintenance_mode') == '1'){
				redirect('maintenance/maintenance.php'.$this->SID, false, false, false);
			} else {
				redirect('index.php'.$this->SID, false, false, false);
			}
			die("Authentication Failed");
		}
	}

	public function create_breadcrump($name, $url = false) {
		$this->tpl->assign_block_vars('breadcrumps', array (
			'BREADCRUMP'	=> (($url) ? '<a href="'.$url.'">'.$name.'</a>' : '<a href="#">'.$name.'</a>')
		));
	}

	public function error_out($die = false){
		$error_message = $this->pdl->get_html_log(3);
		$error_count = $this->pdl->get_log_size(3);

		if ( $die ){
			$this->message_die($error_message, (( $error_count == 1 ) ? $this->user->lang['error'] : $this->user->lang['errors']));
		}else{
			$log = $this->pdl->get_log();
			$cc = 0;
			foreach($log as $type => $entries) {
				$cc++;
				$this->tpl->assign_block_vars('debug_types', array(
					'ID' => $cc,
					'TYPE' => $type)
				);
				foreach($entries as $entry) {
					$this->tpl->assign_block_vars('debug_types.debug_messages', array('MESSAGE' => $this->pdl->html_format_log_entry($type, $entry)));
				}
			}
			$this->tpl->assign_vars(array(
				'MAX_ID'	=> $cc,
				'L_CLICK'	=> $this->user->lang('click2show'))
			);
		}
	}

	public function page_header(){
		//Has there been a redirect before?
		$blnHeadersSent = headers_sent();

		@header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
		@header('Content-Type: text/html; charset=utf-8');
		//Disable Browser Cache
		@header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
		@header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Datum in der Vergangenheit
		$protocol = ($this->env->protocol == 'HTTP/1.1') ? "HTTP/1.1" : "HTTP/1.0";
		//Send 503 for SEO if there is no redirect
		if(!$blnHeadersSent && !$this->user->is_signedin()) @header( "$protocol 503 Service Unavailable", true, 503 );
		@header('Retry-After: 300');//300 seconds

		$this->header_inc = true;
		$this->tpl->assign_vars(array(
			'L_ADMIN_PANEL' => $this->user->lang('admin_acp'),
			'U_ACP' => $this->root_path.'admin/index.php'.$this->SID,
			'L_ADMIN_PANEL' => $this->user->lang('admin_acp'),
			'L_MMODE' => $this->user->lang('maintenance_mode'),
			'L_TASK_MANAGER' => $this->user->lang('task_manager'),
			'U_MMODE' => $this->root_path.'maintenance/'.$this->SID,
			'L_ACTIVATE_INFO' => $this->user->lang('activate_info'),
			'L_ACTIVATE_MMODE' => $this->user->lang('activate_mmode'),
			'L_LEAVE_MMODE' => $this->user->lang('leave_mmode'),
			'L_DEACTIVATE_MMODE' => $this->user->lang('deactivate_mmode'),
			'S_MMODE_ACTIVE' => ($this->config->get('pk_maintenance_mode') == 1 || (defined('EQDKP_UPDATE') && EQDKP_UPDATE)) ?  true : false,
			'MAINTENANCE_MESSAGE' => $this->config->get('pk_maintenance_message'),
			'S_SPLASH' => ($this->in->get('splash') == 'true') ? true : false,
			'SID'	=> $this->SID,
			'ROOT_PATH' => $this->root_path,
			'EQDKP_VERSION_CONST' => substr(md5(VERSION_EXT), 0, 7),
			'L_MMODE_INFO'		=> $this->user->lang('mmode_info'),
			'S_IS_ADMIN'	=> ($this->user->check_auth('a_maintenance', false)),
		));
		if($this->in->get('splash') == 'true') {
			$this->tpl->assign_vars(array(
				'L_SPLASH_WELCOME' => $this->user->lang('splash_welcome'),
				'L_SPLASH_DESC' => $this->user->lang('splash_desc'),
				'L_SPLASH_NEW' => $this->user->lang('splash_new'),
				'L_SPLASH_QUICKSTART'  => $this->user->lang('splash_quickstart'),
				'L_SPLASH_START_QUICKSTART' => $this->user->lang('splash_start_quickstart'),
				'L_TOUR_START' => $this->user->lang('start_tour'),
				'L_CLOSE_INFO' => $this->user->lang('splash_close_info'),
				'L_CLOSE_SPLASH' => $this->user->lang('splash_close'),
			));
		}
	}

	public function page_tail() {
		$this->tpl->assign_var('S_SHOW_BUTTON', true);
		if($this->pdl->get_log_size(3) > 0){
			$this->tpl->assign_var('S_SHOW_BUTTON', false);
			$this->error_out(false);
		}

		$this->tpl->assign_var('EQDKP_VERSION', VERSION_INT);
		$this->tpl->display();
	}
}
