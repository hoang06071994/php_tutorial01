<?php
function getLinkService ($id, $slug) {
    return 'dich-vu'.$slug.'html';
}

function getPrefixLinkService ($module = '') {
    if ($module == 'services') {
        return 'service';
    }
    return false;
}