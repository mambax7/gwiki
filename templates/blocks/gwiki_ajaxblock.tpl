<!-- gwiki block -->
<script>
    if (typeof ajaxGwikiStack != 'object') var ajaxGwikiStack = new Object;
    ajaxGwikiStack['b<{$block.bid}>'] = ['<{$block.keyword}>'];

    if (typeof ajaxGwikiLoad != 'function') {

        window.ajaxGwikiLoad = function (keyword, bid) {
            ajaxGwikiStackPush(keyword, bid);
            var el = document.getElementById("wikiajaxblock" + bid);
            el.innerHTML = '<img src="<{$block.modurl}>/assets/images/loading-anim.gif" alt="Loading" />';
            // alert(keyword+bid);
            var split = keyword.split('#');
            keyword = split[0];
            var xmlhttp;
            var txt, x, _y, i;
            if (window.XMLHttpRequest) { // code for browsers
                xmlhttp = new XMLHttpRequest();
            } else {  // code for historical curiosities which should die (IE6, IE5) and will probably choke on something else
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    txt = xmlhttp.responseText;
                    el = document.getElementById("wikiajaxblock" + bid);
                    el.innerHTML = txt;
                    el.scrollTop = 0;
                    el.scrollLeft = 0;

                    // try to make sure the top of the just changed content is visible
                    // find the top of our div
                    for (var topPos = 0;
                         el != null;
                         topPos += el.offsetTop, el = el.offsetParent);

                    // figure out where we are viewing now
                    var scrOfY = 0;
                    if (typeof( window.pageYOffset ) == 'number') { //Netscape compliant
                        scrOfY = window.pageYOffset;
                    } else if (document.body && ( document.body.scrollLeft || document.body.scrollTop )) { //DOM compliant
                        scrOfY = document.body.scrollTop;
                    } else if (document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop )) { //IE6 standards compliant mode
                        scrOfY = document.documentElement.scrollTop;
                    }
                    // alert(scrOfY+' : '+topPos);
                    // try to make the browser reposition if content is not in view
                    if (scrOfY > topPos) {
                        document.body.scrollTop = topPos - 16;
                        document.documentElement.scrollTop = topPos - 16;
                    }
                    if (typeof split[1] !== 'undefined') {
                        location.hash = '#' + split[1];
                    }

                }
            };
            xmlhttp.open("GET", "<{$block.ajaxurl}>/ajaxgwiki.php?page=" + encodeURIComponent(keyword) + "&bid=" + bid, true);
            xmlhttp.send();
        };
        window.ajaxGwikiStackPush = function (keyword, bid) {
//      if(ajaxGwikiStack.length==0 && keyword!=ajaxGwikiKeywords['b'+bid]) ajaxGwikiStack.push(ajaxGwikiKeywords['b'+bid]); // initialze stack - first page is preloaded
            ajaxGwikiStack['b' + bid].push(keyword);
        };
        window.ajaxGwikiStackPop = function (bid) {
            // alert(ajaxGwikiStack.length);
            if (ajaxGwikiStack['b' + bid].length > 1) {
                var current = ajaxGwikiStack['b' + bid].pop();
                var keyword = ajaxGwikiStack['b' + bid].pop();
                ajaxGwikiLoad(keyword, bid);
            }
        };
        window.ajaxGwikiRedirect = function (script, fallback, bid) {
            // alert(ajaxGwikiStack.length);
            if (ajaxGwikiStack['b' + bid].length > 0) {
                fallback = ajaxGwikiStack['b' + bid].pop();
                window.location = "<{$block.modurl}>/" + script + ".php?op=edit&page=" + fallback + '#gwikiform';
            }
        }
    }
</script>
<div class="wikiblock">
    <div id="wikiajaxblock<{$block.bid}>" class="wikiajaxblock<{if $block.remotewiki}> wikiajaxremotewiki<{/if}>"></div>
</div>
<div class="wikiblocknav">
    <a href="javascript:ajaxGwikiLoad('<{$block.keyword}>','<{$block.bid}>');"><img
                src="<{$block.modurl}>/assets/images/homeicon.png" alt="<{$block.display_keyword}>"
                title="<{$block.display_keyword}>"/></a>
    <a href="javascript:ajaxGwikiStackPop('<{$block.bid}>');"><img src="<{$block.modurl}>/assets/images/backicon.png"
                                                                   alt="<{$smarty.const._BACK}>"
                                                                   title="<{$smarty.const._BACK}>"/></a>
    <{if $block.mayEdit}><a href="javascript:ajaxGwikiRedirect('edit', '<{$block.keyword}>','<{$block.bid}>');"><img
                src="<{$block.modurl}>/assets/images/editicon.png" alt="<{$smarty.const._EDIT}>"
                title="<{$smarty.const._EDIT}>"/></a> <a
            href="javascript:ajaxGwikiRedirect('history', '<{$block.keyword}>','<{$block.bid}>');"><img
                src="<{$block.modurl}>/assets/images/historyicon.png" alt="<{$smarty.const._MD_GWIKI_HISTORY}>"
                title="<{$smarty.const._MD_GWIKI_HISTORY}>"/></a><{/if}>
</div>
<script>
    ajaxGwikiLoad('<{$block.keyword}>', '<{$block.bid}>');
</script>
