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
            'id'    => null,
            'link'  => pq($item)->find('a')->attr('href'),
            'title' => pq($item)->find('a')->attr('title'),
            'img'   => pq($item)->find('img:eq(1)')->attr('src'),
        );

        // id, title filter
        if ( substr($row['title'],0,3) == 'No.' ) {
            $row['title'] = trim(substr( $row['title'], 3 ));
        }
        $tmp = explode( ' ', $row['title'] );
        $row['id'] = strtolower(trim($tmp[0]));
        if ( preg_match('/^[0-9]+$/', $row['id'] ) ) {
            // id filter to int or not convert
            $row['id'] = (int) $row['id'];
        }
        unset($tmp[0]);
        $row['title'] = join(' ', $tmp );
        unset($tmp);

        // link filter
        $row['link'] = 'http://zh.tos.wikia.com' . $row['link'];

        // debug
        //echo '<pre>'; print_r($row); exit;

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
echo '// 前面 1,2,3 不見了<br/>';
foreach ( $info as $item ) {
    echo "        ";
    echo "case ";
    if ( is_int($item['id']) ) {
        printf('% 6d', $item['id'] );
    }
    else {
        printf('% 6s', '"'.$item['id'].'"' );
    }
    echo ": ";
    echo 'return "'. $item['title'] . '";';
    echo '<br/>';
}
echo '</pre>';




echo '<br/><br/><br/><br/><br/><br/>';
echo '<pre>';
foreach ( $info as $item ) {
    echo "        ";
    echo "case ";
    if ( is_int($item['id']) ) {
        printf('% 6d', $item['id'] );
    }
    else {
        printf('% 6s', '"'.$item['id'].'"' );
    }
    echo ": ";
    echo 'return "'. $item['link'] . '";';
    echo '<br/>';
}
echo '</pre>';




echo '<br/><br/><br/><br/><br/><br/>';
echo '<pre>';
foreach ( $info as $item ) {
    echo "        ";
    echo "case ";
    if ( is_int($item['id']) ) {
        printf('% 6d', $item['id'] );
    }
    else {
        printf('% 6s', '"'.$item['id'].'"' );
    }
    echo ": ";
    echo 'return "'. $item['img'] . '";';
    echo '<br/>';
}
echo '</pre>';



