<?php

function getBannerAd() {
    if(getPlayerAds())
        return "<script data-cfasync='false' type='text/javascript' src='//clksite.com/adServe/banners?tid=98406_291921_0'></script>";
}

function getScrapperAd() {
    if(getPlayerAds())
        return "<script data-cfasync='false' type='text/javascript' src='//clksite.com/adServe/banners?tid=98406_291921_1'></script>";
}