import { parkingAccess } from "../js/qr-scanner-redirection.js";
import QrScanner from "../lib/qr-scanner/qr-scanner.min.js";

QrScanner.WORKER_PATH = '../lib/qr-scanner/qr-scanner-worker.min.js';

document.getElementById('scan').addEventListener("click", scanQRCodeFromImage);

function scanQRCodeFromImage() {
    let imagePath = document.getElementById('qrCodeImage').src;

    srcToFile(imagePath, 'new.png', 'image/png')
        .then(function(file) {
            QrScanner.scanImage(file)
                .then(qrCodeContent => {
                    parkingAccess(qrCodeContent)
                })
                .catch(e => console.log(e));
        })
        .catch(console.error);
}

function srcToFile(src, fileName, mimeType) {
    return (fetch(src)
        .then(function(res) { return res.arrayBuffer(); })
        .then(function(buf) { return new File([buf], fileName, { type: mimeType }); })
    );
}