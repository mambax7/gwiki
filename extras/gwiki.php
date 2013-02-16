<?php
/**
 * wiki page anywhere - call it anything, put it anywhere
 *
 * @copyright		geekwright, LLC
 * @license		GNU General Public License (GPL) V2 or greater
 * @since		1.0
 * @author		Richard Griffith richard@geekwright.com
 * @package		gwiki
 * @version		$Id$
 */

// adjust these two lines to reflect your installation
include_once '../../../mainfile.php';
$dir = 'gwiki';  // wiki module directory

// $_GET variables we use
$page = isset($_GET['page'])?cleaner($_GET['page']):null;
$highlight = isset($_GET['query'])?cleaner($_GET['query']):null;

function cleaner($string) {
	$string=stripcslashes($string);
	$string=html_entity_decode($string);
	$string=strip_tags($string); // DANGER -- kills wiki text
	$string=trim($string);
	$string=stripslashes($string);
	return $string;
}

function prepOut(&$var)
{
	if(is_array($var)) {
		foreach($var as $i => $v) $var[$i]=prepOut($v);
	} else {
		if(is_string($var)) $var=htmlspecialchars($var);
	}
	return $var;
}

function loadLanguage($name, $domain = '',$language = null)
{
global $xoopsConfig;
	if ( !@include_once XOOPS_ROOT_PATH . "/modules/{$domain}/language/" . $xoopsConfig['language'] . "/{$name}.php") {
		include_once XOOPS_ROOT_PATH . "/modules/{$domain}/language/english/{$name}.php" ;
	}
}

function getUserName($uid)
{
    global $xoopsConfig;
    
    $uid = intval($uid);
    
    if ($uid > 0) {
        $member_handler =& xoops_gethandler('member');
        $user =& $member_handler->getUser($uid);
        if (is_object($user)) {
            return "<a href=\"".XOOPS_URL."/userinfo.php?uid=$uid\">".htmlspecialchars($user->getVar('uname'), ENT_QUOTES)."</a>";
        }
    }
    
    return $xoopsConfig['anonymous'];
}

	$script = (!empty($_SERVER['HTTPS']))
		? "https://".$_SERVER['SERVER_NAME'].parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) 
		: "http://".$_SERVER['SERVER_NAME'].parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	
	// Access module configs from outside module:
	$module_handler = xoops_gethandler('module');
	$module         = $module_handler->getByDirname($dir);
	$config_handler = xoops_gethandler('config');
	$moduleConfig   = $config_handler->getConfigsByCat(0, $module->getVar('mid'));

	loadLanguage('main',$dir);
	loadLanguage('modinfo',$dir);
	include_once XOOPS_ROOT_PATH.'/modules/'.$dir.'/classes/gwikiPage.php';

	$wikiPage = new gwikiPage;
	$wikiPage->setRecentCount($moduleConfig['number_recent']);
	$wikiPage->setWikiLinkURL($script.'?page=%s');

	if(empty($page)) $page=$wikiPage->wikiHomePage;

	// if we get a naked or external prefix, try and do something useful
	$pfx=$wikiPage->getPrefix($page);
	if ($pfx) {
		$page=$pfx['actual_page'];
		if($pfx['prefix_is_external']) {
			header("Location: {$pfx['actual_page']}");
			exit;
		}
	}

	$pageX = $wikiPage->getPage($page);
	$attachments=$wikiPage->getAttachments($page);
	$mayEdit = $wikiPage->checkEdit();

	if($pageX) {
		$pageX['body']=$wikiPage->renderPage($wikiPage->body);
		$pageX['author'] = getUserName($wikiPage->uid);
		$pageX['revisiontime']=date($wikiPage->dateFormat,$pageX['lastmodified']);
		$pageX['mayEdit'] = $mayEdit;
		$pageX['pageFound'] = true;
		if(!empty($highlight)) $pageX['body'] = $wikiPage->highlightWords($highlight);
	}
	else {
		$pageX=array();
		$pageX['title']=_MD_GWIKI_NOEDIT_NOTFOUND_TITLE;
		$pageX['body']=_MD_GWIKI_NOEDIT_NOTFOUND_BODY;
		$pageX['author']='';
		$pageX['revisiontime']='';
		$pageX['mayEdit'] = $mayEdit;
		$pageX['pageFound'] = false;
	}

	$pageX['moddir']  = $dir;
	$pageX['modpath'] = XOOPS_ROOT_PATH .'/modules/' . $dir;
	$pageX['modurl']  = XOOPS_URL .'/modules/' . $dir;
	if(!empty($attachments)) $pageX['attachments']  = prepOut($attachments);

	$xoopsOption['template_main'] = $wikiPage->getTemplateName(); // 'gwiki_view.html';
	include XOOPS_ROOT_PATH."/header.php";

	$pageX['title']=prepOut($pageX['title']);
	$xoopsTpl->assign('gwiki', $pageX);

	$xoTheme->addStylesheet(XOOPS_URL.'/modules/'.$dir.'/module.css');
	if($pageX['pageFound']) {
		$xoTheme->addMeta('meta','keywords',htmlspecialchars($pageX['meta_keywords'], ENT_QUOTES,null,false));
		$xoTheme->addMeta('meta','description',htmlspecialchars($pageX['meta_description'], ENT_QUOTES,null,false));
	}
	$title=$pageX['title'];
	$xoopsTpl->assign('xoops_pagetitle', $title);
	$xoopsTpl->assign('icms_pagetitle', $title);

include XOOPS_ROOT_PATH.'/footer.php';
?>