<meta http-equiv="Content-Language" content="zh-tw" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
require('lib/helper.php');

require('lib/httpclient/httpclient.class.php');
$hc = new HttpClient();

require('lib/phpquery/phpQuery/phpQuery.php');
phpQuery::$debug = false;

// 神魔之塔: Tower of Saviors
$link = 'http://pad.skyozora.com/pets/';
if( !$hc->fetch($link) ) {
    echo "parser '{$link}' field <br />";
    continue;
}

$html = $hc->results;
$doc = phpQuery::newDocument($html);

$getInfo = function() use ($doc)
{
    $items = $doc->find('#container li');
    // debug
    //echo 'count is '. count($items); exit;

    $data = array();
    $i=0;
    foreach ( $items as $item ) {

        $row = array(
            'id'    => substr( pq($item)->find('a')->attr('href'), 5 ),
            'link'  => pq($item)->find('a')->attr('href'),
            'title' => pq($item)->find('a')->attr('title'),
            'img'   => pq($item)->find('img')->attr('data-original'),
        );

        // filter name
        $tmp = explode(' ', $row['title']);
        $tmp = explode('<br>', $tmp[2]);
        $row['title'] = trim($tmp[0]);

        // link filter
        $row['link'] = 'http://pad.skyozora.com/pets/' . $row['link'];

        // debug
        // echo '<pre>'; print_r($row); exit;

        // filter
        if ( !$row['id'] ) {
            continue;
        }
        if ( !$row['title'] ) {
            continue;
        }

        // 防呆
        if ( is_object($row['link']) ) {
            continue;
        }
        if ( is_object($row['title']) ) {
            continue;
        }
        if ( is_object($row['img']) ) {
            continue;
        }

        // success data
        if ( $row ) {
            $data[] = $row;
        }

    }

    return $data;
};

$info = $getInfo();

// debug
// print_r($info); exit;

// output
echo '<pre>';
foreach ( $info as $row ) {
    echo "        ";
    echo "case ";
    printf('% 6d', $row['id'] );
    echo ": ";
    echo 'return "'. $row['img'] . '";';
    echo '<br/>';
}
echo '</pre>';



