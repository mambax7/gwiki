<!-- block contains moddir,modpath,modurl,prefixes (array of prefix_id,prefix for each namespace user can edit) -->
<div class="wikiblocknewpage">
    <table>
        <form action="<{$block.modurl}>/<{$block.action}>" id="newwikipageform" name="newwikipageform" method="get">
            <{if is_array($block.prefixes)}>
                <{if count($block.prefixes) > 0 }>
                    <tr>
                        <td style="font-size:75%;"><{$smarty.const._MB_GWIKI_PICK_NAMESPACE}></td>
                        <td><select name="nsid">
                                <{foreach key=pid item=prefix from=$block.prefixes}>
                                    <option value="<{$prefix.prefix_id}>"><{$prefix.prefix}></option>
                                <{/foreach}>
                            </select></td>
                    </tr>
                <{/if}>
            <{/if}>
            <tr>
                <td style="font-size:75%;"><{$smarty.const._MB_GWIKI_PAGE_NAME}></td>
                <td><input type="text" id="newwikipagename" name="page" size="10"/></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>
                    <button type="submit"><{$smarty.const._MB_GWIKI_NEWPAGE}></button>
                </td>
            </tr>
        </form>
    </table>
</div>
