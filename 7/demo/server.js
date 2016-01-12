var http = require("http");
var fs = require("fs");
var path = require("path");
var server = http.createServer(requestListener);

exports.init = function() {
    // httpサーバーを起動する。
    server.listen((process.env.PORT || 5000), function() {
        console.log((process.env.PORT || 5000) + "でサーバーが起動しました");
    });
}

/*
サーバーにリクエストがあった時に実行される関数
*/
function requestListener(request, response) {
    // リクエストがあったファイル
    var requestURL = request.url;
    // リクエストのあったファイルの拡張子を取得
    var extensionName = path.extname(requestURL);
    // ファイルの拡張子に応じてルーティング処理
    switch(extensionName)
    {
        case ".html":
            readFileHandler(requestURL, "text/html", false, response);
            break;
        case ".css":
            readFileHandler(requestURL, "text/css", false, response);
            break;
        case ".js":
        case ".ts":
            readFileHandler(requestURL, "text/javascript", false, response);
            break;
        case ".png":
            readFileHandler(requestURL, "image/png", true, response);
            break;
        case ".jpg":
            readFileHandler(requestURL, "image/jpeg", true, response);
            break;
        case ".gif":
            readFileHandler(requestURL, "image/gif", true, response);
            break;
        default:
            readFileHandler("/demo1.html", "text/html", false, response);
            break;
    }
}

/**
 * ファイルの読み込み
 */
function readFileHandler(fileName, contentType, isBinary, response) {
    // エンコードの設定
    var encoding = !isBinary ? "utf8" : "binary";
    var filePath = __dirname + fileName;

    fs.exists(filePath, function(exits) {
        if(exits)
        {
            fs.readFile(filePath, {encoding: encoding}, function (error, data) {
                if (error) {
                    response.statusCode = 500;
                    response.end("Internal Server Error");
                } else {
                    response.statusCode = 200;
                    response.setHeader("Content-Type", contentType);
                    if(!isBinary)
                    {
                        response.end(data);
                    }
                    else
                    {
                        response.end(data, "binary");
                    }
                }
            });
        }
        else
        {
            // ファイルが存在しない場合は400エラーを返す。
            response.statusCode = 400;
            response.end("400 Error");
        }
    });
}

/*
    Socket.IO
*/

// socket.ioの読み込み
var socketIO = require("socket.io");
// サーバーでSocket.IOを使える状態にする
var io = socketIO.listen(server);
exports.io = io;