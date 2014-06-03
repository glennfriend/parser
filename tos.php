<meta http-equiv="Content-Language" content="zh-tw" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
require('lib/helper.php');

require('lib/httpclient/httpclient.class.php');
$hc = new HttpClient();

require('lib/phpquery/phpQuery/phpQuery.php');
phpQuery::$debug = false;

// 神魔之塔: Tower of Saviors
$link = 'http://zh.tos.wikia.com/wiki/Category:%E5%9C%96%E9%91%92';
if( !$hc->fetch($link) ) {
    echo "parser '{$link}' field <br />";
    continue;
}

$html = $hc->results;
$doc = phpQuery::newDocument($html);

$getInfo = function() use ($doc)
{
    $items = $doc->find('#mw-content-text table tr td');
    // $count = count($items);

    $data = array();
    $i=0;
    foreach ( $items as $item ) {
    
        $row = array(
            'link'  => pq($item)->find('a')->attr('href'),
            'id'    => null,
            'title' => pq($item)->find('a')->attr('title'),
            'img'   => pq($item)->find('img:eq(1)')->attr('src'),
        );


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

        // 防呆
        if ( 'No.'!==substr($row['title'], 0, 3) ) {
            continue;
        }

        // id, title filter
        $tmp = substr( $row['title'], 3 );
        $tmp = explode( ' ', $tmp );
        $row['id'] = (int) $tmp[0];
        unset($tmp[0]);
        $row['title'] = join(' ', $tmp );

        // link filter
        $row['link'] = 'http://zh.tos.wikia.com' . $row['link'];


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
echo '// 前面 1,2,3 不見了<br/>';
foreach ( $info as $item ) {
    echo "case ";
    printf('% 5d', $item['id'] );
    echo ": ";
    echo 'return "'. $item['title'] . '";';
    echo '<br/>';
}
echo '</pre>';




echo '<br/><br/><br/><br/><br/><br/>';
echo '<pre>';
foreach ( $info as $item ) {
    echo "case ";
    printf('% 5d', $item['id'] );
    echo ": ";
    echo 'return "'. $item['link'] . '";';
    echo '<br/>';
}
echo '</pre>';




echo '<br/><br/><br/><br/><br/><br/>';
echo '<pre>';
foreach ( $info as $item ) {
    echo "case ";
    printf('% 5d', $item['id'] );
    echo ": ";
    echo 'return "'. $item['img'] . '";';
    echo '<br/>';
}
echo '</pre>';



