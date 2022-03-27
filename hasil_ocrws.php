<?php
$imageName = "images/" . $_GET['t'] . ".jpg";


$license_code = "6D334000-E0A4-485E-B3D0-82F9FA83918F";
$username =  "HWIDEZ";

$url = 'http://www.ocrwebservice.com/restservices/processDocument?language=indonesian&tobw=true&gettext=true';
$filePath = $imageName;

$fp = fopen($filePath, 'r');
$session = curl_init();

curl_setopt($session, CURLOPT_URL, $url);
curl_setopt($session, CURLOPT_USERPWD, "$username:$license_code");

curl_setopt($session, CURLOPT_UPLOAD, true);
curl_setopt($session, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($session, CURLOPT_TIMEOUT, 200);
curl_setopt($session, CURLOPT_HEADER, false);

// For SSL using
curl_setopt($session, CURLOPT_SSL_VERIFYPEER, true);

// Specify Response format to JSON or XML (application/json or application/xml)
curl_setopt($session, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

curl_setopt($session, CURLOPT_INFILE, $fp);
curl_setopt($session, CURLOPT_INFILESIZE, filesize($filePath));

$result = curl_exec($session);

$httpCode = curl_getinfo($session, CURLINFO_HTTP_CODE);
curl_close($session);
fclose($fp);

if ($httpCode == 401) {
    // Please provide valid username and license code
    die('Unauthorized request');
}

// Output response
$data = json_decode($result);

if ($httpCode != 200) {
    // OCR error
    die($data->ErrorMessage);
}

// Extracted text
$text2 = $data->OCRText[0][0];

$txt = rawurlencode($text2);
$source = file_get_contents('https://translate.google.com/translate_tts?ie=UTF-8&client=gtx&q=' . $txt . '&tl=id-ID');
// $play = "<audio controls='controls' autoplay><source src='data:audio/mpeg;base64," . base64_encode($source) . "'></audio>";

$data = [
    'text' => preg_replace("/\r\n|\r|\n/", '<br/>', $text2),
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