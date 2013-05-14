<?php
if ( !defined('GWIKI_NOTIFY_ITEMINFO') ) {
define('GWIKI_NOTIFY_ITEMINFO', 1);

function gwiki_notify_iteminfo($category, $item_id)
{
	global $xoopsDB;

	$dir = basename( dirname ( dirname( __FILE__ ) ) ) ;
	//include_once XOOPS_ROOT_PATH.'/modules/'.$dir.'/classes/gwikiPage.php';
	//$wikiPage = new gwikiPage;
	$module_handler =& xoops_gethandler('module');
	$module         =& $module_handler->getByDirname($dir);
	$module_id      =  $module->getVar('mid');
	$config_handler =& xoops_gethandler('config');
	$moduleConfig   =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));

//	$this->wikiLinkURL = $moduleConfig['wikilink_template'];
//	$this->wikiHomePage = $moduleConfig['wiki_home_page'];

	switch ($category) {
		case 'page':
			$item_id=intval($item_id);
			$sql  = 'SELECT i.keyword as keyword, display_keyword, title FROM ';
			$sql .=  $xoopsDB->prefix('gwiki_pageids').' i, '.$xoopsDB->prefix('gwiki_pages').' p ';
			$sql .= ' WHERE i.keyword = p.keyword AND active = 1 AND page_id = '.$item_id;

			$result = $xoopsDB->query($sql);
			$row = $xoopsDB->fetchArray($result);

			$item['name'] = $row['display_keyword'];
			if(empty($item['name'])) $item['name'] = $row['title'];
			if(empty($item['name'])) $item['name'] = $row['keyword'];

			$item['url']  = sprintf($moduleConfig['wikilink_template'],$row['keyword']);
			break;
		case 'namespace':
			$item_id=intval($item_id);
			$sql  = 'SELECT prefix, prefix_home FROM '.$xoopsDB->prefix('gwiki_prefix');
			$sql .= ' WHERE prefix_id = '.$item_id;

			$result = $xoopsDB->query($sql);
			$row = $xoopsDB->fetchArray($result);

			$item['name'] = $row['prefix'];
			$item['url']  = sprintf($moduleConfig['wikilink_template'],$row['prefix'].':'.$row['prefix_home']);
			break;
		default:
			$item['name'] = $category;
			$item['url']  = XOOPS_URL.'/modules/'.$dir.'/';
			break;
	}
	return $item;
}
}
?>
