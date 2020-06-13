import { parkingAccess } from "../js/qr-scanner-redirection.js";
import QrScanner from "../lib/qr-scanner/qr-scanner.min.js";

QrScanner.WORKER_PATH = '../lib/qr-scanner/qr-scanner-worker.min.js';

if (document.querySelector('.scan-code-wrapper')) {
    const video = document.getElementById('qr-video');
    const deviceHasCamera = document.getElementById('device-has-camera');

    QrScanner.hasCamera().then(hasCamera => deviceHasCamera.textContent = hasCamera);

    const scanner = new QrScanner(video, qrCodeContent => {
        parkingAccess(qrCodeContent)
    });
    scanner.start();

    document.getElementById('inversion-mode-select').addEventListener('change', event => {
        scanner.setInversionMode(event.target.value);
    });
}