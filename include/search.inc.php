<?php
function gwiki_search($queryarray, $andor, $limit, $offset, $userid, $prefix=null)
{
	global $xoopsDB;

	$dir = basename( dirname ( dirname( __FILE__ ) ) ) ;

	$module_handler = xoops_gethandler('module');
	$module         = $module_handler->getByDirname($dir);
	$module_id      =  $module->getVar('mid');
	$config_handler = xoops_gethandler('config');
	$moduleConfig   = $config_handler->getConfigsByCat(0, $module->getVar('mid'));

	$baseurl = $moduleConfig['searchlink_template'];
	$args=implode('+',$queryarray); // template should include '&query='
    
	$sql = "SELECT DISTINCT * FROM ".$xoopsDB->prefix('gwiki_pages')." WHERE active=1 ";
	if (is_array($queryarray) && ($count = count($queryarray))) {
		$sql .= " AND (title LIKE '%$queryarray[0]%' OR search_body LIKE '%$queryarray[0]%' OR meta_keywords LIKE '%$queryarray[0]%' OR meta_description LIKE '%$queryarray[0]%')";
		for($i = 1; $i < $count; $i++) {
			$sql .= " $andor (title LIKE '%$queryarray[$i]%' OR search_body LIKE '%$queryarray[$i]%' OR meta_keywords LIKE '%$queryarray[$i]%' OR meta_description LIKE '%$queryarray[$i]%')";
		}
	} else {
		$sql .= " AND uid='$userid'";
	}
	$sql .= " ORDER BY lastmodified DESC";
    
	$items = array();
	$result = $xoopsDB->query($sql, $limit, $offset);
	while($myrow = $xoopsDB->fetchArray($result)) {
		$items[] = array(
			'title' => $myrow['title'],
			'link' => sprintf($baseurl,strtolower($myrow['keyword']),$args),
			'time' => $myrow['lastmodified'],
			'uid' => $myrow['uid'],
			'image' => 'images/search-result-icon.png'
		);
	}
    
	return $items;
}
?>