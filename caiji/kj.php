<?
$api = 'http://www-k123456.com/index.php?c=api&a=updateinfo&cp=bjpk10&uptime=1488049275&chtime=33005&catid=2&modelid=9';
$resource = file_get_contents( $api );
$data = json_decode( $resource , 1 );
$ct = $data['c_t'];

$cd = $data['c_d'];

$cr = $data['l_r'];

$json = array();
$json['expect'] = $ct;
$json['opencode'] = $cr;
$json['opentime'] = $cd;

//var_dump($json);
echo '"open":'.'['.json_encode($json).']';
// header('Content-Type: text/xml;charset=utf8');
// $limit=strlen($ct)-2;

// $ct=substr($ct,0,$limit).''.substr($ct,$limit,$limit+2);
// //print_r($data);

// echo '<xml>
// <row expect="'.$ct.'" opencode="'.$cr.'" opentime="'.str_replace('/','-',$cd).'"/>
// </xml>';