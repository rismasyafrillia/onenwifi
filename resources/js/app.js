import './bootstrap';

if ("Notification" in window && "serviceWorker" in navigator) {
  Notification.requestPermission().then(permission => {
    if (permission === "granted") {
      console.log("Notifikasi diizinkan");
    } else {
      console.log("Notifikasi ditolak");
    }
  });
}
