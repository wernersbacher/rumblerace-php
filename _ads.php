<?php

function getBannerAd() {
    if(getPlayerAds())
        return '<SCRIPT charset="utf-8" type="text/javascript" src="http://ws-eu.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&MarketPlace=DE&ID=V20070822%2FDE%2Fracinc-21%2F8009%2F32c98e3d-da75-4832-8527-40773540a2e2&Operation=GetScriptTemplate"> </SCRIPT> <NOSCRIPT><A rel="nofollow" HREF="http://ws-eu.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&MarketPlace=DE&ID=V20070822%2FDE%2Fracinc-21%2F8009%2F32c98e3d-da75-4832-8527-40773540a2e2&Operation=NoScript">Amazon.de Widgets</A></NOSCRIPT>';
}

function getScrapperAd() {
    if(getPlayerAds())
        return '<SCRIPT charset="utf-8" type="text/javascript" src="http://ws-eu.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&MarketPlace=DE&ID=V20070822%2FDE%2Fracinc-21%2F8009%2Fa827518e-8a22-4781-9817-1f0dea5eaaa5&Operation=GetScriptTemplate"> </SCRIPT> <NOSCRIPT><A rel="nofollow" HREF="http://ws-eu.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&MarketPlace=DE&ID=V20070822%2FDE%2Fracinc-21%2F8009%2Fa827518e-8a22-4781-9817-1f0dea5eaaa5&Operation=NoScript">Amazon.de Widgets</A></NOSCRIPT>';
}