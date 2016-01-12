(function() {
	var $text1;

	// 動きを検知したかどうか
	var isDetect;

	$(function() {
		$text2 = $("#text-2");
		isDetect = false;

		// サーバーに接続
		var socket = io.connect(location.origin);

		// サーバーからセンサーの値を受信
		socket.on("sensor", function(dataFromServer) {
			var value = dataFromServer.value;
			// 近くに動くものを検知したかを判定
			isDetect = value > 50;
		});

		// 一定間隔で確認
		setInterval(function() {
			if(isDetect) { // 近くに動くものを検知
				$text2.css({ display: "table" });
			} else {
				$text2.css({ display: "none" });
			}
		}, 500)
	});
})();