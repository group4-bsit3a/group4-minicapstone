<!--<!DOCTYPE html>
<html>
<head>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
    <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script type="text/javascript" src="https://cdn.rawgit.com/serratus/quaggaJS/0.12.1/dist/quagga.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>-->




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR code Generator</title>
    

</head>
<body>
    <div class="container">
        <div class="header">
            <h1>QR code Generator</h1>
            <p>Type a text to generate QR code</p>
        </div>
        <div class="input-form">
            <input type="text" class="qr-input" placeholder="Enter text">
            <button class="generate-btn">Generate QR Code</button>
        </div>
        <div class="qr-code">
            <img src="https://i.ibb.co/0jC43YG/qrcode-chrome.png" class="qr-image">
        </div>
    </div>

    <script>
        var container = document.querySelector(".container");
        var generateBtn = document.querySelector(".generate-btn");
        var qrInput = document.querySelector(".qr-input");
        var qrImg = document.querySelector(".qr-image");

        generateBtn.onclick = function(){
            if(qrInput.value.length > 0){
                generateBtn.innerText = "Generating QR CODE ......";
                qrImg.src = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" + qrInput.value;
                qrImg.onload = function(){
                    container.classList.add("active"); 
                    generateBtn.innerText = "Generate QR Code";
                    saveQRCode();
                };
            }
        };

        function saveQRCode() {
            var link = document.createElement('a');
            link.href = qrImg.src;
            link.download = 'qrcodes/qrcode.png'; // Save in the "qrcodes" folder
            link.click();
        }
    </script>
</body>
</html>