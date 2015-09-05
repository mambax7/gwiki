<?php
/**
* cleanit.php - purge old revisions as specified in preferences
*
* @copyright  Copyright © 2013 geekwright, LLC. All rights reserved.
* @license    gwiki/docs/license.txt  GNU General Public License (GPL)
* @since      1.0
* @author     Richard Griffith <richard@geekwright.com>
* @package    gwiki
* @version    $Id$
*/
//	trigger_error("Clean Invoked");
include '../../mainfile.php';
    if (empty($_POST['check'])) { // this is set by the admin page option, not by a regular call
        $xoopsOption['template_main'] = 'gwiki_view.tpl';
        include XOOPS_ROOT_PATH."/header.php";
        do_clean();
        include XOOPS_ROOT_PATH."/footer.php";
    }
    else {
        $xoopsLogger->activated = false;
        do_clean();
        exit;
    }

function do_clean() {
global $xoopsDB;

    $dir = basename( dirname( __FILE__ ) ) ;
    // Access module configs from block:
    $module_handler = xoops_gethandler('module');
    $module         = $module_handler->getByDirname($dir);
    $config_handler = xoops_gethandler('config');
    $moduleConfig   = $config_handler->getConfigsByCat(0, $module->getVar('mid'));

    $retaindays=intval($moduleConfig['retain_days']);
    if($retaindays<=0) return;

    $lastmodifiedbefore=time()-($retaindays * 24 * 3600);
    $sql = 'DELETE FROM '.$xoopsDB->prefix('gwiki_pages')." WHERE active = 0 AND lastmodified< $lastmodifiedbefore";
    $result = $xoopsDB->queryF($sql);
    $cnt=$xoopsDB->getAffectedRows();
    if ($cnt>0) {
        $sql  = 'SELECT image_file FROM '.$xoopsDB->prefix('gwiki_page_images');
        $sql .= ' WHERE keyword NOT IN (SELECT keyword from '.$xoopsDB->prefix('gwiki_pages').')';
        $result = $xoopsDB->query($sql);
        while ($f = $xoopsDB->fetchArray($result)) {
            unlink(XOOPS_ROOT_PATH.'/uploads/'.$dir.'/'.$f['image_file']);
        }
        $sql  = 'DELETE FROM '.$xoopsDB->prefix('gwiki_page_images');
        $sql .= ' WHERE keyword NOT IN (SELECT keyword from '.$xoopsDB->prefix('gwiki_pages').')';
        $result = $xoopsDB->queryF($sql);

        $sql  = 'SELECT file_path FROM '.$xoopsDB->prefix('gwiki_page_files');
        $sql .= ' WHERE keyword NOT IN (SELECT keyword from '.$xoopsDB->prefix('gwiki_pages').')';
        $result = $xoopsDB->query($sql);
        while ($f = $xoopsDB->fetchArray($result)) {
            unlink(XOOPS_ROOT_PATH.'/uploads/'.$dir.'/'.$f['file_path']);
        }
        $sql  = 'DELETE FROM '.$xoopsDB->prefix('gwiki_page_files');
        $sql .= ' WHERE keyword NOT IN (SELECT keyword from '.$xoopsDB->prefix('gwiki_pages').')';
        $result = $xoopsDB->queryF($sql);

        $sql = 'DELETE FROM '.$xoopsDB->prefix('gwiki_pageids').' WHERE keyword NOT IN (SELECT keyword from '.$xoopsDB->prefix('gwiki_pages').')';
        $result = $xoopsDB->queryF($sql);
        $sql = 'OPTIMIZE TABLE '.$xoopsDB->prefix('gwiki_pages');
        $result = $xoopsDB->queryF($sql);
    }
}