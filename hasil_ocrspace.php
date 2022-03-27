<?php

require_once 'vendor/autoload.php';
$imageName = "images/" . $_GET['t'] . ".jpg";
$fileData = fopen($imageName, 'r');
$client = new GuzzleHttp\Client();
try {
    $r = $client->request('POST', 'https://api.ocr.space/parse/image', [
        'headers' => ['apiKey' => 'K87082898388957'],
        'multipart' => [
            [
                'name' => 'file',
                'contents' => $fileData,
                'isOverlayRequired' => true,
                'language' => 'eng',
                'scale' => true
            ]
        ]
    ], ['file' => $fileData]);
    $response =  json_decode($r->getBody(), true);
    // var_dump($response);
    foreach ($response['ParsedResults'] as $pareValue) {
        if ($pareValue['ParsedText'] == "") {
            $text = "Gagal Membaca Teks, Silahkan ulangi lagi.";
        } else {
            $text = $pareValue['ParsedText'];
        }
    }
} catch (Exception $err) {
    header('HTTP/1.0 403 Forbidden');
    echo $err->getMessage();
    die();
}


$txt = rawurlencode($text);
$source = file_get_contents('https://translate.google.com/translate_tts?ie=UTF-8&client=gtx&q=' . $txt . '&tl=id-ID');
// $play = "<audio controls='controls' autoplay><source src='data:audio/mpeg;base64," . base64_encode($source) . "'></audio>";

$data = [
    'text' => preg_replace("/\r\n|\r|\n/", '<br/>', $text),
    'poto' => $imageName,
    'audio' => $source
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Result</title>
</head>

<body>
    <a href="javascript:replay();" style="text-decoration: none; color:black;">
        <center>
            <img src="<?= $data['poto']; ?>" width="50%" class="img-fluid mt-3">
        </center>

        <div class="card m-3">
            <div class="card-header">
                OCR Result :
            </div>
            <div class="card-body">
                <p class="card-text"><?= $data['text']; ?></p>
            </div>
        </div>
        <audio id="sound" autoplay src="data:audio/mpeg;base64,<?= base64_encode($data['audio']); ?>">
            <p>Perangkat tidak support Text to Speech! </p>
        </audio>
    </a>
</body>

<script type="text/javascript">
    function replay() {
        document.getElementById('sound').play();
    }
</script>

</html>