<?php
require('phpQuery/phpQuery.php');
phpQuery::$debug = false;
$baseUrl    = 'http://images.google.com.tw/';
$home       = 'images?q=%E7%BE%8E%E5%A5%B3&oe=utf-8&hl=zh-TW&um=1&ie=UTF-8&sa=N&tab=wi';
$pageVar    = 'page';
$pageStart  = 1;
$pageEnd    = 5;

//小圖完整列表
//for( $page=$pageStart; $page<=$pageEnd; $page++ ) {
//    echo "========== page/{$page} ======================================================================<br />\n";
    $html   = file_get_contents($baseUrl.$home);
    echo $html;
    $doc    = phpQuery::newDocument($html);
/*
    $doc->find('#ad_square>tr>td>a>img');

//echo "\n".'$pq->getDocumentID() = '. phpQuery::getDocument( pq('<div/>')->getDocumentID() );
  echo "\n".'test = '.                 phpQuery::getDocument( pq('td.tDataText0')->getDocument() );

echo "\n";
print_r($doc);
*/