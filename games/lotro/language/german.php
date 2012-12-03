<?php
 /*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       01.07.2009
 * Date:        $Date: 2009-05-17 16:10:37 +0200 (So, 17 Mai 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: hoofy_leon $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 4885 $
 *
 * $Id: filter.php 4885 2009-05-17 14:10:37Z sz3 $
 */

if ( !defined('EQDKP_INC') )
{
    header('HTTP/1.0 404 Not Found');
    exit;
}

$german_array = array(
		'class' => array(
        	0 => 'Unbekannt',
            1 => 'Barde',
            2 => 'Hauptmann',
            3 => 'Jäger',
            4 => 'Kundiger',
            5 => 'Schurke',
            6 => 'Wächter',
            7 => 'Waffenmeister',
            8 => 'Runenbewahrer',
            9 => 'Hüter',
		),
      	'race' => array(
        	'Unknown',
        	'Mensch',
        	'Hobbit',
        	'Elb',
        	'Zwerg'
      	),
		'faction' => array(
			'Normal',
			'MonsterPlay'
		),
		'lang' => array(
			'lotro' => 'Lord of the Rings Online',
			'heavy' => 'Schwere Rüstung',
			'medium' => 'Mittlere Rüstung',
			'light' => 'Leichte Rüstung',
		),
);
?>