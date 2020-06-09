function printQRCode() {
    let imgUrl = document.getElementById("qrCode").src;
    pwin = window.open(imgUrl);
    pwin.print();
}