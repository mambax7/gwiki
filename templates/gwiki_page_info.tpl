<br clear="all"/>
<{if $gwiki.pageset.first.desc}>
    <div class="wikipagesetnav">
        <a href="<{$gwiki.pageset.first.link}>"><img src="<{$gwiki.modurl}>/assets/images/psfirst.png"
                                                     alt="<{$gwiki.pageset.first.desc}>"
                                                     title="<{$gwiki.pageset.first.text}>"/></a>
        <a href="<{$gwiki.pageset.prev.link}>"><img src="<{$gwiki.modurl}>/assets/images/psprev.png"
                                                    alt="<{$gwiki.pageset.prev.desc}>"
                                                    title="<{$gwiki.pageset.prev.text}>"/></a>
        <a href="<{$gwiki.pageset.home.link}>"><img src="<{$gwiki.modurl}>/assets/images/pshome.png"
                                                    alt="<{$gwiki.pageset.home.desc}>"
                                                    title="<{$gwiki.pageset.home.text}>"/></a>
        <a href="<{$gwiki.pageset.next.link}>"><img src="<{$gwiki.modurl}>/assets/images/psnext.png"
                                                    alt="<{$gwiki.pageset.next.desc}>"
                                                    title="<{$gwiki.pageset.next.text}>"/></a>
        <a href="<{$gwiki.pageset.last.link}>"><img src="<{$gwiki.modurl}>/assets/images/pslast.png"
                                                    alt="<{$gwiki.pageset.last.desc}>"
                                                    title="<{$gwiki.pageset.last.text}>"/></a>
    </div>
<{/if}>
<{if empty($hideInfoBar)}>
<{if $gwiki.pageFound}>
    <{if isset($gwiki.attachments)}>
        <br>
        <table id="wikiAttachList">
            <tr>
                <th colspan="4" style="text-align:center;"><{$smarty.const._MD_GWIKI_ATTACHMENT_LIST}></th>
            </tr>
            <tr>
                <th width="5%">&nbsp;</th>
                <th width="60%"><{$smarty.const._MD_GWIKI_FILES_NAME}></th>
                <th width="10%" align="right"><{$smarty.const._MD_GWIKI_FILES_SIZE}></th>
                <th width="25%" align="right"><{$smarty.const._MD_GWIKI_FILES_DATE}></th>
            </tr>
            <{foreach key=id item=attachment from=$gwiki.attachments}>
                <tr class="wikiAttachItem">
                    <td><a href="<{xoAppUrl /uploads/}><{$gwiki.moddir}>/<{$attachment.file_path}>"
                           title="<{$attachment.file_description}>"><img
                                    src="<{$gwiki.modurl}>/assets/icons/32px/<{$attachment.file_icon}>.png"
                                    alt="<{$attachment.file_icon}>"/></a></td>
                    <td><a href="<{xoAppUrl /uploads/}><{$gwiki.moddir}>/<{$attachment.file_path}>"
                           title="<{$attachment.file_description}>"><{$attachment.file_name}></a></td>
                    <td style="text-align:right;"><{$attachment.file_size|number_format}></td>
                    <td style="text-align:right;"><{$attachment.date}></td>
                </tr>
            <{/foreach}>
        </table>
    <{/if}>
<{/if}>
<p class="itemInfo"><{$smarty.const._MD_GWIKI_PAGE}>:
    <strong><{$gwiki.keyword}></strong> <{if $gwiki.pageFound}>- <{$smarty.const._MD_GWIKI_LASTMODIFIED}> <span
            class="itemPostDate"><{$gwiki.revisiontime}></span> <{$smarty.const._MD_GWIKI_BY}> <span
            class="itemPoster"><{$gwiki.author}><{/if}></span>
    <{if $gwiki.mayEdit}>
        <span style="margin-left:2em;">
    <{if $gwiki.admin_lock}><img src="<{$gwiki.modurl}>/assets/images/lockedicon.png"
                                 alt="<{$smarty.const._MD_GWIKI_PAGE_IS_LOCKED}>"
                                 title="<{$smarty.const._MD_GWIKI_PAGE_IS_LOCKED}>" />
    <{else}>
        <{if $gwiki.ineditor}>
        <{if $showwizard}>
            <a href="<{$gwiki.modurl}>/wizard.php?page=<{$gwiki.keyword}>"><img
                        src="<{$gwiki.modurl}>/assets/images/wizardicon.png" alt="<{$smarty.const._MD_GWIKI_IMAGES}>"
                        title="<{$smarty.const._MD_GWIKI_WIZARD}>"/></a>
        <{/if}>






        <a onclick="toggleDiv('wikiimageedit'); return false;"><img src="<{$gwiki.modurl}>/assets/images/imageicon.png"
                                                                    alt="<{$smarty.const._MD_GWIKI_IMAGES}>"
                                                                    title="<{$smarty.const._MD_GWIKI_IMAGES}>"/></a>
        <a onclick="toggleDiv('wikifileedit'); return false;"><img src="<{$gwiki.modurl}>/assets/images/attachicon.png"
                                                                   alt="<{$smarty.const._MD_GWIKI_ATTACHMENT_EDIT}>"
                                                                   title="<{$smarty.const._MD_GWIKI_ATTACHMENT_EDIT}>"/></a>
        <a onclick="bigWindow(1); return false;"><img src="<{$gwiki.modurl}>/assets/images/fullscreenicon.png"
                                                      alt="<{$smarty.const._MD_GWIKI_FULLSCREEN_EDIT}>"
                                                      title="<{$smarty.const._MD_GWIKI_FULLSCREEN_EDIT}>"/></a>
        <a onclick="helpwindow();return false;"><img src="<{$gwiki.modurl}>/assets/images/helpicon.png"
                                                     alt="<{$smarty.const._MD_GWIKI_WIKI_EDIT_HELP}>"
                                                     title="<{$smarty.const._MD_GWIKI_WIKI_EDIT_HELP}>"/></a>






                                                                                <{else}>






        <a href="<{$gwiki.modurl}>/edit.php?page=<{$gwiki.keyword}>&op=edit#gwikiform"><img
                    src="<{$gwiki.modurl}>/assets/images/editicon.png" alt="<{$smarty.const._EDIT}>"
                    title="<{$smarty.const._EDIT}>"/></a>






                                                                                    <{if $gwiki.pageset.first.link}>
            <a href="<{$gwiki.modurl}>/sortpageset.php?page=<{$gwiki.keyword}>" target="_self"><img
                        src="<{$gwiki.modurl}>/assets/images/sorticon.png"
                        alt="<{$smarty.const._MD_GWIKI_SORT_PAGE_FORM}>"
                        title="<{$smarty.const._MD_GWIKI_SORT_PAGE_FORM}>"/></a>
        <{/if}>
    <{/if}>
    <{/if}>
            <a href="<{$gwiki.modurl}>/history.php?page=<{$gwiki.keyword}>"><img
                        src="<{$gwiki.modurl}>/assets/images/historyicon.png" alt="<{$smarty.const._MD_GWIKI_HISTORY}>"
                        title="<{$smarty.const._MD_GWIKI_HISTORY}>"/></a>
    <a href="<{$gwiki.modurl}>/source.php?page=<{$gwiki.keyword}>" target="_blank"><img
                src="<{$gwiki.modurl}>/assets/images/texticon.png" alt="<{$smarty.const._MD_GWIKI_SOURCE}>"
                title="<{$smarty.const._MD_GWIKI_SOURCE}>"/></a>
</span>
    <{/if}>
    <{/if}>
