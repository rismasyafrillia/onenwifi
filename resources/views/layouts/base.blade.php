<head>
<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#0d6efd">
</head>

<script>
if ("serviceWorker" in navigator) {
 navigator.serviceWorker.register("/service-worker.js")
.then(reg => {
    reg.update();
    console.log("SW updated");
});;
}
</script>
