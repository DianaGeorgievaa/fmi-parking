export function parkingAccess(qrCodeContent) {
    window.location.href = '../controllers/parking-access.php' + '?userQrCode=' + qrCodeContent;
}