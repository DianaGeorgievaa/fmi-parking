function printQRCode() {
    let imgUrl = document.getElementById("qrCodeImage").src;
    pwin = window.open(imgUrl);
    pwin.print();
}