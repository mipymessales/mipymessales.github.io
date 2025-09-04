self.addEventListener("push", (event) => {
    const data = event.data.json();

    event.waitUntil(
        self.registration.showNotification(data.title, {
            body: data.body,
            icon: "images/logo_color.png",
            data: data.url,
            vibrate: [200, 100, 200] // vibración en móviles
        })
    );
});

self.addEventListener("notificationclick", (event) => {
    event.notification.close();
    event.waitUntil(
        clients.openWindow(event.notification.data)
    );
});
