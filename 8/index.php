<!--
【課題内容】
Webカメラが顔を認識して四角形の枠で顔を囲む＋IBM Alchemy(人工知能)で顔から年齢/性別を判定して顔の横に表示させる。
※本当は動画からリアルタイムに画像を生成してIBM Alchemy(人工知能)にデータを送り、年齢/性別を判定して顔の横に表示させ、数秒ごとに値を更新させるようにしたかったのですが、今回は間に合いませんでしたので別途用意した顔画像データをIBM Alchemy(人工知能)に送り、年齢と性別の値を顔の横に表示させるようにしています。
アウトプットイメージは以下のURLで確認できます。
https://youtu.be/ui4gyVnU1dw
-->



<?php

//HTMLヘッダを表示
echo <<<_HEADER_
<html prefix="og: http://ogp.me/ns#">
    
    
<head>
		<title>facetracking</title>
		<meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
		<meta charset="utf-8">
		<style>
			body {
				background-color: #f0f0f0;
				margin-left: 10%;
				margin-right: 10%;
				margin-top: 5%;
				width: 40%;
				overflow: hidden;
				font-family: "Helvetica", Arial, Serif;
				position: relative;
			}
		</style>
		<script>
			// getUserMedia only works over https in Chrome 47+, so we redirect to https. Also notify user if running from file.
//			if (window.location.protocol == "file:") {
//				alert("You seem to be running this example directly from a file. Note that these examples only work when served from a server or localhost due to canvas cross-domain restrictions.");
//			} else if (window.location.hostname !== "localhost" && window.location.protocol !== "https:"){
//				window.location.protocol = "https";
//			}
		</script>
		
	</head>
	<body>
		<script src="js/headtrackr.js"></script>
		
		<canvas id="compare" width="600" height="500" style="display:none"></canvas>
		<video id="vid" autoplay loop width="600" height="500"></video>
		<canvas id="overlay" width="600" height="500"></canvas>
		<canvas id="debug" width="600" height="500"></canvas>
        <canvas id="test" width="600" height="500"></canvas>
		
		<p id='gUMMessage'></p>
		<p>Status : <span id='headtrackerMessage'></span></p>
		<p><input type="button" onclick="htracker.stop();htracker.start();" value="reinitiate facedetection"></input>
		<br/><br/>
		<input type="checkbox" onclick="showProbabilityCanvas()" value="asdfasd"></input>Show probability-map</p>
		
		<script>
		  // set up video and canvas elements needed
		
			var videoInput = document.getElementById('vid');
			var canvasInput = document.getElementById('compare');
			var canvasOverlay = document.getElementById('overlay')
			var debugOverlay = document.getElementById('debug');
			var overlayContext = canvasOverlay.getContext('2d');
			canvasOverlay.style.position = "absolute";
			canvasOverlay.style.top = '0px';
			canvasOverlay.style.zIndex = '100001';
			canvasOverlay.style.display = 'block';
			debugOverlay.style.position = "absolute";
			debugOverlay.style.top = '0px';
			debugOverlay.style.zIndex = '100002';
			debugOverlay.style.display = 'none';
            
            
//            //動画から画像を生成する処理を追加
//            var cEle = document.getElementById('test');
//            var cCtx = cEle.getContext('2d');
//            var vEle = document.getElementById('vid');
//
//            cEle.width = vEle.videoWidth;
//            cEle.height = vEle.videoHeight;
//
//            cCtx.drawImage(vEle, 0, 0); // canvasに関数実行時の動画のフレームを描画
//            
			
			// add some custom messaging
			
			statusMessages = {
				"whitebalance" : "checking for stability of camera whitebalance",
				"detecting" : "Detecting face",
				"hints" : "Hmm. Detecting the face is taking a long time",
				"redetecting" : "Lost track of face, redetecting",
				"lost" : "Lost track of face",
				"found" : "Tracking face"
			};
			
			supportMessages = {
				"no getUserMedia" : "Unfortunately, <a href='http://dev.w3.org/2011/webrtc/editor/getusermedia.html'>getUserMedia</a> is not supported in your browser. Try <a href='http://www.opera.com/browser/'>downloading Opera 12</a> or <a href='http://caniuse.com/stream'>another browser that supports getUserMedia</a>. Now using fallback video for facedetection.",
				"no camera" : "No camera found. Using fallback video for facedetection."
			};
			
			document.addEventListener("headtrackrStatus", function(event) {
				if (event.status in supportMessages) {
					var messagep = document.getElementById('gUMMessage');
					messagep.innerHTML = supportMessages[event.status];
				} else if (event.status in statusMessages) {
					var messagep = document.getElementById('headtrackerMessage');
					messagep.innerHTML = statusMessages[event.status];
				}
			}, true);
			
			// the face tracking setup
			
			var htracker = new headtrackr.Tracker({altVideo : {ogv : "./media/capture5.ogv", mp4 : "./media/capture5.mp4"}, calcAngles : true, ui : false, headPosition : false, debug : debugOverlay});
			htracker.init(videoInput, canvasInput);
			htracker.start();
			
			// for each facetracking event received draw rectangle around tracked face on canvas
            
            
			
			
			
			// turn off or on the canvas showing probability
			function showProbabilityCanvas() {
				var debugCanvas = document.getElementById('debug');
				if (debugCanvas.style.display == 'none') {
					debugCanvas.style.display = 'block';
				} else {
					debugCanvas.style.display = 'none';
				}
			}
            
            
            
  
		</script>




    
    
    
_HEADER_;

//==========================================================================

    
    
    
// API Key
$key = 'a7caa67de5297a83d74fd3c634ab0b10e4375bf1';

// 結果
$json = 'No url parameter.';

// エンドポイント
$api = 'http://access.alchemyapi.com/calls/url/URLGetRankedImageFaceTags?apikey=' . $key
 . '&outputMode=json&knowledgeGraph=1&url=';

// パラメータ
$url = "https://graph.facebook.com/898267290281497/picture?type=large";    
if( isset( $url ) ){
//  $url = $_GET['url'];
  if( $url ){
    $api .= urlencode( $url );
    $json = file_get_contents( $api );
  }
}
$json2 = json_decode( $json );

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


echo '<script>'; 
 
echo 'document.addEventListener("facetrackingEvent", function( event ) {
				// clear canvas
				overlayContext.clearRect(0,0,600,500);
                
				// once we have stable tracking, draw rectangle
				if (event.detection == "CS") {
					overlayContext.translate(event.x, event.y)
					overlayContext.rotate(event.angle-(Math.PI/2));
					overlayContext.strokeStyle = "#00CC00";
 //                   overlayContext.fillStyle = "#00CC00";
					overlayContext.strokeRect((-(event.width/2)) >> 0, (-(event.height/2)) >> 0, event.width, event.height);
					overlayContext.rotate((Math.PI/2)-event.angle);
					overlayContext.translate(-event.x, -event.y);
                    overlayContext.font = "15pt normal";
overlayContext.fillText("'.$ageRange."歳".'",event.x+80, event.y+10);
overlayContext.fillText("'.$gender.'",event.x+80, event.y-10);     overlayContext.fillText("戦闘力：5",event.x+80, event.y-30);             
                    
                
				}});'; 
 
//echo "overlayContext.strokeText('test2',event.x, event.y);"; 
echo '</script>';
 
}
}
    
    
echo<<<_FOOTER_
</body>
</html>
_FOOTER_;
