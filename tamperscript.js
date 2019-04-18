// ==UserScript==
// @name         Namegetter
// @namespace    http://tampermonkey.net/
// @version      0.1
// @description  try to take over the world!
// @author       You
// @match        https://blog.reedsy.com/character-name-generator/language/*
// @grant        none
// ==/UserScript==

function download(filename, text) {
  var element = document.createElement('a');
  element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
  element.setAttribute('download', filename);

  element.style.display = 'none';
  document.body.appendChild(element);

  element.click();

  document.body.removeChild(element);
}

(function() {
    'use strict';
var fullHtml = [];
    var text ="";
    var start = Date.now();

    $('h3').each(function(index) {
   //fullHtml.push( $(this).html() );
	text+= $(this).html()+"\n";
	if(index == 4)
	return false;
});

    download('names_'+start+'.txt', text);
})();