<?php

//調査期間の設定************************************************************************************

if( !empty($_POST[ "startyear" ]) ){ $startyear = $_POST[ "startyear" ]; }else{ $startyear = 2017; };
if( !empty($_POST[ "startmonth" ]) ){ $startmonth = $_POST[ "startmonth" ]; }else{ $startmonth = 11; };
if( !empty($_POST[ "startdate" ]) ){ $startdate = $_POST[ "startdate" ]; }else{ $startdate = 20; };

if( !empty($_POST[ "endyear" ]) ){ $endyear = $_POST[ "endyear" ]; }else{ $endyear = 2017; };
if( !empty($_POST[ "endmonth" ]) ){ $endmonth = $_POST[ "endmonth" ]; }else{ $endmonth = 11; };
if( !empty($_POST[ "enddate" ]) ){ $enddate = $_POST[ "enddate" ]; }else{ $enddate = 30; };

$start = date( 'y-m-d', mktime( 0, 0, 0, $startmonth, $startdate, $startyear ) );
$end = date( 'y-m-d', mktime( 0, 0, 0, $endmonth, $enddate, $endyear ) );

echo "調査期間".$start."～".$end;

//初期値設定****************************************************************************************

$access_No = 0;

$hosts[1] = "null";

for( $i=0 ; $i <= 23 ; $i++ ){ $count[$i] = 0;};

//ファイルを探して表示する**************************************************************************


foreach(glob("../apache/logs/access.log*") as $file) {
    $result[] = $file;
};

$the_number_of_files = count( $result );

echo("</br>");
print("見つかったアクセスログファイルの総数:{$the_number_of_files}");
echo("</br>");

for( $i=0 ; $i<$the_number_of_files ; $i++ )
   {
$filesize[$i] = filesize( $result[$i] );

echo("</br>");
print("{$result[$i]} ; {$filesize[$i]} bytes");
echo("</br>");
    };

echo("</br>");

//見つけたファイルをそれぞれ読み込む****************************************************************

for( $file_No=0 ; $file_No<$the_number_of_files ; $file_No++ )
   {                                                                  //ファイルを順番に読み込む ; ループ１
print("{$result[ $file_No ]}についての集計結果______________________________________________________");
echo("</br>");

//ファイルから一行ずつ読み込んで一行ずつ処理していく************************************************

//それぞれアクセスデータを$dataに格納*******************************************

//ファイルを変数に格納する
$fp = fopen( $result[ $file_No ] , 'r');

while ($fp && !feof($fp)) {          // whileで行末までループ処理して一行ずつ処理 ; ループ1-1

// fgetsでファイルを読み込み、変数に格納
$data = fgets($fp);

if( $data == "" ){ break; };            //アクセスログファイルの最終行が改行で終わってるため、スキップ

//$dataから必要な情報（リモートホスト名とアクセス日時）を抽出*******************

//まずはアクセスデータを二つに分割
$split = explode( " [", $data);

//リモートホスト名を抽出
$remote_host = explode( " ", $split[0] );

//アクセス時刻を抽出
$str = explode( "] ", $split[1] );
$time = strtotime( $str[0] );

//得られたデータから指定した期間だけのデータを選択**************************************************

if( strtotime( $start ) <= strtotime( date( 'y-m-d', $time ) ) && strtotime( date( 'y-m-d', $time ) ) <= strtotime( $end ) )
  {                                     //20171127から201711128までのデータのみ抽出 ; ループ1-1-1

//リモートホストごとにデータを集計******************************************************************

if( !array_search($remote_host[0], $hosts) )
  {                                     //配列$hostsに既にあるホスト名は$timesに１加算し、なければ新しい$hosts要素として追加 ; ループ1-1-1-1

  $access_No = $access_No + 1;
  $hosts[$access_No] = $remote_host[0];
  $times[$access_No] = 1;

  }else{

  $i = array_search($remote_host[0],$hosts);
  $times[$i] = $times[$i] + 1;
  };//ループ1-1-1-1終わり

//時間帯ごとにデータを集計**************************************************************************

if( strtotime('00:00:00') <= strtotime(date( 'H:i:s', $time )) && strtotime(date( 'H:i:s', $time )) < strtotime('01:00:00') ){ $count[0] = $count[0] + 1; };
if( strtotime('01:00:00') <= strtotime(date( 'H:i:s', $time )) && strtotime(date( 'H:i:s', $time )) < strtotime('02:00:00') ){ $count[1] = $count[1] + 1; };
if( strtotime('02:00:00') <= strtotime(date( 'H:i:s', $time )) && strtotime(date( 'H:i:s', $time )) < strtotime('03:00:00') ){ $count[2] = $count[2] + 1; };
if( strtotime('03:00:00') <= strtotime(date( 'H:i:s', $time )) && strtotime(date( 'H:i:s', $time )) < strtotime('04:00:00') ){ $count[3] = $count[3] + 1; };
if( strtotime('04:00:00') <= strtotime(date( 'H:i:s', $time )) && strtotime(date( 'H:i:s', $time )) < strtotime('05:00:00') ){ $count[4] = $count[4] + 1; };
if( strtotime('05:00:00') <= strtotime(date( 'H:i:s', $time )) && strtotime(date( 'H:i:s', $time )) < strtotime('06:00:00') ){ $count[5] = $count[5] + 1; };
if( strtotime('06:00:00') <= strtotime(date( 'H:i:s', $time )) && strtotime(date( 'H:i:s', $time )) < strtotime('07:00:00') ){ $count[6] = $count[6] + 1; };
if( strtotime('07:00:00') <= strtotime(date( 'H:i:s', $time )) && strtotime(date( 'H:i:s', $time )) < strtotime('08:00:00') ){ $count[7] = $count[7] + 1; };
if( strtotime('08:00:00') <= strtotime(date( 'H:i:s', $time )) && strtotime(date( 'H:i:s', $time )) < strtotime('09:00:00') ){ $count[8] = $count[8] + 1; };
if( strtotime('09:00:00') <= strtotime(date( 'H:i:s', $time )) && strtotime(date( 'H:i:s', $time )) < strtotime('10:00:00') ){ $count[9] = $count[9] + 1; };
if( strtotime('10:00:00') <= strtotime(date( 'H:i:s', $time )) && strtotime(date( 'H:i:s', $time )) < strtotime('11:00:00') ){ $count[10] = $count[10] + 1; };
if( strtotime('11:00:00') <= strtotime(date( 'H:i:s', $time )) && strtotime(date( 'H:i:s', $time )) < strtotime('12:00:00') ){ $count[11] = $count[11] + 1; };
if( strtotime('12:00:00') <= strtotime(date( 'H:i:s', $time )) && strtotime(date( 'H:i:s', $time )) < strtotime('13:00:00') ){ $count[12] = $count[12] + 1; };
if( strtotime('13:00:00') <= strtotime(date( 'H:i:s', $time )) && strtotime(date( 'H:i:s', $time )) < strtotime('14:00:00') ){ $count[13] = $count[13] + 1; };
if( strtotime('14:00:00') <= strtotime(date( 'H:i:s', $time )) && strtotime(date( 'H:i:s', $time )) < strtotime('15:00:00') ){ $count[14] = $count[14] + 1; };
if( strtotime('15:00:00') <= strtotime(date( 'H:i:s', $time )) && strtotime(date( 'H:i:s', $time )) < strtotime('16:00:00') ){ $count[15] = $count[15] + 1; };
if( strtotime('16:00:00') <= strtotime(date( 'H:i:s', $time )) && strtotime(date( 'H:i:s', $time )) < strtotime('17:00:00') ){ $count[16] = $count[16] + 1; };
if( strtotime('17:00:00') <= strtotime(date( 'H:i:s', $time )) && strtotime(date( 'H:i:s', $time )) < strtotime('18:00:00') ){ $count[17] = $count[17] + 1; };
if( strtotime('18:00:00') <= strtotime(date( 'H:i:s', $time )) && strtotime(date( 'H:i:s', $time )) < strtotime('19:00:00') ){ $count[18] = $count[18] + 1; };
if( strtotime('19:00:00') <= strtotime(date( 'H:i:s', $time )) && strtotime(date( 'H:i:s', $time )) < strtotime('20:00:00') ){ $count[19] = $count[19] + 1; };
if( strtotime('20:00:00') <= strtotime(date( 'H:i:s', $time )) && strtotime(date( 'H:i:s', $time )) < strtotime('21:00:00') ){ $count[20] = $count[20] + 1; };
if( strtotime('21:00:00') <= strtotime(date( 'H:i:s', $time )) && strtotime(date( 'H:i:s', $time )) < strtotime('22:00:00') ){ $count[21] = $count[21] + 1; };
if( strtotime('22:00:00') <= strtotime(date( 'H:i:s', $time )) && strtotime(date( 'H:i:s', $time )) < strtotime('23:00:00') ){ $count[22] = $count[22] + 1; };
if( strtotime('23:00:00') <= strtotime(date( 'H:i:s', $time )) && strtotime(date( 'H:i:s', $time )) < strtotime('24:00:00') ){ $count[23] = $count[23] + 1; };

  };//ループ1-1-1終わり




};//ループ1-1終わり

   };//ループ１終わり

//結果の出力****************************************************************************************

//リモートホスト別アクセス数****************************************************
echo("</br>");
echo "リモートホスト別アクセスランキング";
echo("</br>");

asort($times);
foreach ($times as $i => $val) {
    echo "$hosts[$i] : $val"."件</br>";
};
echo("</br>");
//時間帯ごとのアクセス数********************************************************
echo "時間帯ごとのアクセス数</br>";
for( $i=0 ; $i < 24 ; $i++ ){ echo $i."時 : ".$count[$i]."件</br>"; };

?>


<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8N">
<title>課題</title>
</head>
<body>
<br />
<br />
<br />
<label>期間の指定</label>
<br />
<form method="POST" action="access.php">
__開始___________________________終了<br />
年<input type="number" name="startyear" />～<input type="number" name="endyear" /><br />
月<input type="number" name="startmonth" />～<input type="number" name="endmonth" /><br />
日<input type="number" name="startdate" />～<input type="number" name="enddate" /><br />
<input type="submit" value="送信" />
</form>
</body>
</html>