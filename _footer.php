<div id="footer">
    <div id="footerMiddle">

        <a href="http://markus.wernersbacher.de/pages/about-this-website/">&copy; wernersbacher 2015-2016</a> | ALPHA 0.1 <noscript>| <?php echo put("noscript", $l) ?></noscript> 

        <div id="footer-lang">
            
            
            <?php
            
            echo "Server: ".date("d M Y H:i:s")." | ";
            
            if ($l == "en") {
                $lang = "de";
            } else {
                $lang = "en";
            }
            echo '<form style="display:inline-block;" method="post" action="'.$_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'].'">
                    <input type="hidden" name="lang" value="'.$lang.'">
                    <input type="submit" name="submit" value="" class="langSubmit" style="background:url(img/'.$lang.'.png)">
                 </form>';
            
            ?>
        </div>

    </div>
</div>