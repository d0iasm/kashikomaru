<?php
$accessToken = getenv('LINE_CHANNEL_ACCESS_TOKEN');

//ユーザーからのメッセージ取得
$json_string = file_get_contents('php://input');
$jsonObj = json_decode($json_string);

$type = $jsonObj->{"events"}[0]->{"message"}->{"type"};

//ReplyToken取得
$replyToken = $jsonObj->{"events"}[0]->{"replyToken"};

//メッセージ以外のときは何も返さず終了
if($type != "text"){
	exit;
}

//メッセージ取得
// $text = "アイウエオ";
$text = $jsonObj->{"events"}->{"message"}->{"text"};

$response_format_text = [
  "type" => "text",
  "text" => $text + "でございます"
];

//返信データ作成
// if ($text == 'はい') {
//   $response_format_text = [
//     "type" => "text",
//     "text" => $text + "でございます"
//   ];
// } else if ($text == 'いいえ') {
//   exit;
// } else if ($text == 'やめる') {
//   exit;
// } else {
//   $response_format_text = [
//     "type" => "template",
//     "altText" => "丁寧に言い直しますか？（はい／いいえ）",
//     "template" => [
//       "type" => "confirm",
//       "text" => "丁寧に言い直しますか？",
//       "actions" => [
//           [
//             "type" => "message",
//             "label" => "はい",
//             "text" => "はい"
//           ],
//           [
//             "type" => "message",
//             "label" => "いいえ",
//             "text" => "いいえ"
//           ]
//       ]
//     ]
//   ];
// }

$post_data = [
	"replyToken" => $replyToken,
	"messages" => [$response_format_text]
	];

$ch = curl_init("https://api.line.me/v2/bot/message/reply");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json; charser=UTF-8',
    'Authorization: Bearer ' . $accessToken
    ));
$result = curl_exec($ch);
curl_close($ch);
