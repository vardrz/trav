<?php
date_default_timezone_set('Asia/Jakarta');
$date = date('d-M-Y_H-i-s');
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Document</title>
   <style>
      body {
         background-color: black;
      }

      .container {
         position: relative;
      }

      #my_camera {
         position: fixed;
         margin-top: -80px;
         right: 0;
         width: 400px;
         height: 540px;
         /* zoom: 130%; */
         /* width: 100% !important;
         height: auto !important;
         min-width: 320px;
         min-height: 240px; */
      }

      input {
         border-style: none;
      }

      .take {
         position: fixed;
         bottom: 0;
         margin-bottom: 40px;
         right: 35%;
         width: 30%;
         /* background-color: black; */
         color: white;
      }
   </style>
</head>

<body>
   <div class="container">
      <div id="my_camera"></div>
      <br />
      <input class="take" type="image" src="cam.png" width="100" onClick="take_snapshot()">
   </div>
</body>

<!-- load webcam.js -->
<script type="text/javascript" src="webcam.min.js"></script>

<!-- konfigurasi kamera -->
<script language="JavaScript">
   Webcam.set('constraints', {
      facingMode: "environment",
      width: 320,
      height: 320,
      // dest_width: 480,
      // dest_height: 480,
      image_format: 'jpeg',
      jpeg_quality: 90,
   });
   Webcam.attach('#my_camera');

   function take_snapshot() {
      // ambil gambar + proses data
      Webcam.snap(function(data_uri) {
         Webcam.upload(data_uri, "proses.php?t=<?= $date; ?>");
         window.location.href = "hasil_ocrspace.php?t=<?= $date; ?>";
      });
   }
</script>

</html>