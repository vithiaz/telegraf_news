<?php


function translate_date($date, $format='l, j F Y') {
    $date_translate = Illuminate\Support\Carbon::parse($date)->locale(config('app.locale'));
    $date_translate->settings(['formatFunction' => 'translatedFormat']);
    return $date_translate->format($format);
}

function get_first_paragraph($body) {
    $dom = new \DOMDocument;
    $dom->loadHTML($body);
    return $dom->getElementsByTagName('p')->item(0)->nodeValue;
}