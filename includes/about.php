<?php

require_once "app/lib/Parsedown.php";

function renderAboutPage()
{
    try {
        $Parsedown = new Parsedown();
        $filePath = dirname(__FILE__) . '/BACKERS.md';
        $strContent = file_get_contents($filePath);
        $sponsorsHtml = $Parsedown->text($strContent);
    
        echo renderTemplate("about", compact('sponsorsHtml'));
    } catch (Exception $e) {
        // handle the error here
    }
}
