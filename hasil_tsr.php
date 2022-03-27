<?php
$imageName = $_GET['t'] . ".jpg";

shell_exec('"C:\\Program Files\\Tesseract-OCR\\tesseract" "C:\\xampp\\htdocs\\trav\\images\\' . $imageName . '" out -l ind --dpi 300');

$myfile = fopen("out.txt", "r") or die("Unable to open file!");
$text1 = fread($myfile, filesize("out.txt"));
$text2 = preg_replace("/\r\n|\r|\n/", ",", $text1);
fclose($myfile);

$txt = rawurlencode($text2);
$source = file_get_contents('https://translate.google.com/translate_tts?ie=UTF-8&client=gtx&q=' . $txt . '&tl=id-ID');
// $play = "<audio controls='controls' autoplay><source src='data:audio/mpeg;base64," . base64_encode($source) . "'></audio>";

$data = [
    'text' => preg_replace("/\r\n|\r|\n/", '<br/>', $text1),
    'poto' => 'images/' . $imageName,
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