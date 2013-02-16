<?php
/**
* include/fbcomment_plugin.php - supply gwiki meta data open graph style for fbcomment module
*
* This file is part of gwiki - geekwright wiki
*
* @copyright  Copyright © 2010 geekwright, LLC. All rights reserved. 
* @license    gwiki/docs/license.txt  GNU General Public License (GPL)
* @since      1.0
* @author     Richard Griffith <richard@geekwright.com>
* @package    gwiki
* @version    $Id: gwlotoPrintJob.php 4 2010-09-11 02:19:21Z rgriffith $
*/
/*
 * example plugin for tadgallery
 	$metas['fb:admins'] = $admins;
	$metas['fb:app_id'] = $appid;

	$metas['og:type']=$type;
	$metas['og:url']=$oururl;
	$metas['og:title']=$title;
	$metas['og:description']=$description;
	$metas['og:image']=$image;
	$metas['og:site_name'] = $sitename;
 *
 */
 
function fbcom_plugin(&$metas, $plugin_env) {
global $xoopsDB;

	$dir = basename( dirname ( dirname( __FILE__ ) ) ) ;
	// Access module configs from block:
	$module_handler = xoops_gethandler('module');
	$module         = $module_handler->getByDirname($dir);
	$config_handler = xoops_gethandler('config');
	$moduleConfig   = $config_handler->getConfigsByCat(0, $module->getVar('mid'));

	$wikihome=strtolower($moduleConfig['wiki_home_page']);

	// fake a full url with page if at top of module
	if(substr($metas['og:url'],-1)=='/' && !isset($plugin_env['page'])) {
		$plugin_env['page']=$wikihome;
		$metas['og:url']=$metas['og:url'].'index.php';
	}

	if(isset($plugin_env['page'])) {
		// connonicalize our url with our rules
		// - page needs to be case insensitve (AbCde and AbcDe yield the same page)
		$keyword=strtolower($plugin_env['page']);
		// - eliminate index.php?page=wikihome
		$ourscript= explode ('?', urldecode($metas['og:url']) );
		$ourscript_parts = pathinfo($ourscript[0]);
		if ($ourscript_parts['basename']!='index.php') return false;
		if ($ourscript_parts['basename']=='index.php' && $keyword==$wikihome) {
			$newscript=$ourscript_parts['dirname'].'/';
		}
		else {
			$newscript=$ourscript[0].'?page='.$keyword;
		}
		$metas['og:url']=$newscript;
/*
SELECT title, meta_description, search_body, image_file FROM gwwiki_gwiki_pages p 
left join gwwiki_gwiki_page_images i on p.keyword=i.keyword and use_to_represent = 1 
where p.keyword = 'sheep' and active=1
*/
		$wikitable=$xoopsDB->prefix('gwiki_pages');
		$imagetable=$xoopsDB->prefix('gwiki_page_images');
		$sql = "SELECT title, meta_description, search_body, image_file FROM {$wikitable} p ";
		$sql.= " left join {$imagetable} i on p.keyword=i.keyword and use_to_represent = 1 ";
		$sql.= " where p.keyword = '{$keyword}' and active=1 ";

		// set title and description
		$result = $xoopsDB->query($sql);
		if ($result) { 
//			if(!$xoopsDB->getRowsNum($result)) return false;
			if($myrow=$xoopsDB->fetchArray($result)) {
				if(!empty($myrow['title'])) $metas['og:title']=$myrow['title'];
				if(!empty($myrow['search_body'])) {
					$description=$myrow['search_body'];
					$description=substr($description,0,40).'...';
					$metas['og:description']=$description;
				}
				if(!empty($myrow['meta_description'])) {
					$description=$myrow['meta_description'];
					$metas['og:description']=$description;
				}
				if(!empty($myrow['image_file'])) {
					$image_file=$myrow['image_file'];
					$metas['og:image']=XOOPS_URL.'/uploads/'.$dir.'/'.$image_file;
				}
				return true;
			}
		}
	}
	return false;
}

?>