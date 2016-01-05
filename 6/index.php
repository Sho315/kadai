<?php

//Facebook公式SDK(開発セット)を読み込む
require './facebook-php-sdk/src/facebook.php';

//AppIDとAppSecretをFacebook Developer Centerにて取得して下さい。
//　https://developers.facebook.com/apps/
//AppIDとAppSecretを設定してください。
$facebook = new Facebook(array(
    'appId'  => '1653171968288211',
    'secret' => '27a31601364da3c254182dd9e6a5afc5',
));

//ログイン状態を取得する
$user = $facebook->getUser();

if ($user) {
    try {
        //ログインしていたら、自分のユーザプロファイルを取得
        $user_profile = $facebook->api('/me');

    } catch (FacebookApiException $e) {
        //ユーザプロファイル取得失敗 = ログインしていない
        error_log($e);
        $user = null;
    }
    
    try {
        //ログインしていたら、自分の友達一覧を取得
        $user_friends = $facebook->api('/me/friends');
    } catch (FacebookApiException $e) {
        //友達一覧取得に失敗 = ログインしていない
        error_log($e);
        $user = null;
    } 
}

if ($user) {
    //ログインしていたら、ログアウトURLを取得。
    $params = array( 'next' => 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'] );
    $logoutUrl = $facebook->getLogoutUrl($params);
    //セッションのクリア
    $facebook->destroySession();

} else {
    //ログインして無いなら、ログインURLを取得。
    $loginUrl = $facebook->getLoginUrl();
}

//HTMLヘッダを表示
echo <<<_HEADER_
<html prefix="og: http://ogp.me/ns#">
    <head>
        <meta content='text/html; charset=utf-8' http-equiv='content-type'>
        <meta property="og:image" content="img/ai_2.png" />
        <link rel="stylesheet" href="http://bootswatch.com/cerulean/bootstrap.min.css">
    </head>
    <body>
    <div id = "container">
        <div id="login">
    
    <title>人工知能による顔年齢判定アプリ</title>
    <style>
    #container{
        width:500px;
        margin: 0 auto;
    }
    #login{
        text-align: center;
        margin: 70px auto;
    }
    .btn{
        background: #3b5998;
        color: #fff;
        width: 200px;
        padding: 5px;
        text-decoration: none;
        display: inline-block;
    }
    .btn:hover{
        opacity: 0.8;
    }
    
    
    
    </style>
    
_HEADER_;

//==========================================================================
echo '<h1>人工知能があなたの顔年齢を判定します</h1>';
echo '<hr />'."\n";
//ログインボタン、ログアウトボタンを表示
if ($user) {
    echo '<a href="'. $logoutUrl .'" class="btn">Logout</a>'."\n";
} else {
    echo '<div><a href="'. $loginUrl .'" class="btn">Facebook Login</a></div>'."\n";
}

//==========================================================================
echo '<hr />'."\n";

//ログインしていたら、ログインしている人の情報を取得する
if ($user) {
    echo '<h3>あなたのFacebookプロフィール画像</h3>'."\n";
    echo '<img src="https://graph.facebook.com/'. $user .'/picture?type=large">'."\n";
    echo '<p> </p>';
    echo '<pre>'."\nあなたのFacebookユーザー名：";
    echo $user_profile['name'];
    echo "\nあなたのFacebookユーザーID：";
    echo $user_profile['id'].'</pre>';
   
    
 //   $user_profile2 = json_decode( $user_profile );
 //   $name = $user_profile->name;
//    echo $name;
//    $array = json_decode( $user_profile , true ) ;
//$name = $array[name][0];
    
     
    
//    echo '<h3>ログインしている人の友達リスト</h3>';
//    //友達リストからユーザ情報だけ取得
//    $user_friends_data = $user_friends['data'];
//    echo '<h4>友達の数：'. count($user_friends_data) . ' 人</h4>'."\n";
//    $i=0;
//    foreach ($user_friends_data as $fkey=>$fvalue) {
//        $i++;
//        echo '<a href="http://www.facebook.com/profile.php?id='.$fvalue[id].'"><img src="https://graph.facebook.com/' . $fvalue[id] . '/picture" border="0" title="' . $fvalue[name].'"/></a>';
//        if ($i % 5 == 0) {
//            echo '<br><br>';
//        }
//    }
    
    
    
    
// API Key
$key = 'a7caa67de5297a83d74fd3c634ab0b10e4375bf1';

// 結果
$json = 'No url parameter.';

// エンドポイント
$api = 'http://access.alchemyapi.com/calls/url/URLGetRankedImageFaceTags?apikey=' . $key
 . '&outputMode=json&knowledgeGraph=1&url=';

// パラメータ
$url = "https://graph.facebook.com/$user/picture?type=large";    
if( isset( $url ) ){
//  $url = $_GET['url'];
  if( $url ){
    $api .= urlencode( $url );
    $json = file_get_contents( $api );
  }
}
$json2 = json_decode( $json );

//$array = json_decode( $json , true ) ;
//echo( $json );    
    
//$trans2 = $json2->totalTransactions; 
$imageFaces = $json2->imageFaces; 
    if( count( $imageFaces ) ){
for( $i = 0; $i < count( $imageFaces ); $i ++ )
{ $imageFace = $imageFaces[$i];
 $positionX = $imageFace->positionX; 
 $positionY = $imageFace->positionY; 
 $width = $imageFace->width; 
 $height = $imageFace->height; 
 $ageO = $imageFace->age; 
 $ageRange = $ageO->ageRange; 
 $ageScore = $ageO->score;$genderO = $imageFace->gender; 
 $gender = $genderO->gender; 
 $genderScore = $genderO->score;

//echo '<h3>人工知能による顔年齢/性別 判定結果</h3>'."\n"; 
echo "<table border='1'>";
echo '<tr><th></th><th>　　結果　</th><th>　可能性(％)　</th></tr>'; 
echo '<tr><td>　人工知能が判定したあなたの顔年齢　</td><td>'."　".'<b>'.$ageRange."歳".'</b>'."　".'</td><td>'."　".$ageScore."　".'</td></tr>';
echo '<tr><td>　人工知能が判定したあなたの性別　</td><td>'."　".'<b>'.$gender.'</b>'."　".'</td><td>'."　".$genderScore."　".'</td></tr>';
echo '</table>'; 
echo '<p> </p>';
echo '<p> </p>';
echo '※Facebookのプロフィールの顔写真を基に年齢と性別を判定しています';
echo '<br>';
echo '※Facebookのプロフィール画像の顔が正面を向いていなければ判定できません';
echo '<br>';
echo '※人工知能はIBMのAlchemyAPIを使用しています';  
 
}
}
}else {
    echo '<strong><em>ログインして下さい</em></strong>'."\n";
}

echo<<<_FOOTER_
</body>
</html>
_FOOTER_;
