function test_connection(btn) 
{
    const websocketType = btn.getAttribute('websocket-type');
    const websocketIp = btn.getAttribute('websocket-ip');
    const websocketPort = btn.getAttribute('websocket-port');

    if (!websocketType || !websocketIp || !websocketPort) {
        console.error('Missing websocket-type or data-value attribute');
        return;
    }

    var xhr = new XMLHttpRequest();

    var url = '../services/checksocket?ip=' + websocketIp + '&port=' + websocketPort;

    xhr.open('GET', url, true);

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                if(xhr.responseText == "OK") {
                    btn.className = 'btn btn-success text-white';
                    btn.innerHTML = "<i class='bi bi-check2-all'></i> Connected";
                    btn.disabled = true;
                    Swal.fire({
                        title: "Success",
                        html: "Connection established!",
                        icon: "success",
                        didOpen: function () {
                            var audioplay = new Audio("../assets/mp3/pop.mp3");
                            audioplay.play();
                        }
                    });
                }
                else
                {
                    btn.className = 'btn btn-danger text-white';
                    btn.innerHTML = "<i class='bi bi-clock-history'></i> Failed to connect";
                    Swal.fire({
                        title: 'Oops error',
                        text: "Can't reach to the websocket...",
                        icon: 'error',
                        didOpen: () => {
                            var audioplay = new Audio("../assets/mp3/pop.mp3");
                            audioplay.play();
                        }
                    });
                }
            } 
            else 
            {
                // Hubo un error al hacer la solicitud
                btn.className = 'btn btn-danger text-white';
                btn.innerHTML = "<i class='bi bi-clock-history'></i> Failed to connect";
                Swal.fire({
                    title: 'Oops error',
                    text: "Can't reach to the websocket...",
                    icon: 'error',
                    didOpen: () => {
                        var audioplay = new Audio("../assets/mp3/pop.mp3");
                        audioplay.play();
                    }
                });
            }
        }
    };

    // Enviar la solicitud
    xhr.send();

    // Old websocket checker
    /*try {
        const socket = new WebSocket(websocketType + '://' + dataValue);

        socket.addEventListener('open', function (event) {
            btn.classList.className = 'btn btn-success';
            btn.innerHTML = "<i class='bi bi-check2-all'></i> Connected";
            btn.disabled = true;
            Swal.fire({
                title: "Success",
                html: "Connection established!",
                icon: "success",
                onOpen: function () {
                    var audplay = new Audio("../mp3/pop.mp3")
                    audplay.play();
                }
            });

            // Send message after connection is established
            socket.send('¡ñam!');
        });

        socket.addEventListener('message', function (event) {
            console.log('Received message:', event.data);
        });

        socket.addEventListener('close', function (event) {
            console.log('Websocket connection closed');
            // Update button state to indicate connection closed
            btn.classList.className = 'btn btn-warning';
            btn.innerHTML = "<i class='bi bi-exclamation-triangle'></i> Connection closed";
            btn.disabled = false;
        });

    } catch (e) {
        btn.className = 'btn btn-danger text-white';
        btn.innerHTML = "<i class='bi bi-clock-history'></i> Failed to connect";
        Swal.fire({
            title: 'Oops error, check dev console for more information',
            text: e.message,
            icon: 'error',
            didOpen: () => {
                var audioplay = new Audio("../assets/mp3/pop.mp3");
                audioplay.play();
            }
        });
    }*/
}

function resend(btn) 
{
    const product_id = btn.getAttribute('product_id');
    const user_id = btn.getAttribute('user_id');
    const payment_id = btn.getAttribute('payment_id');

    if (!user_id || !product_id) {
        console.error('Missing data-values attribute');
        return;
    }

    var xhr = new XMLHttpRequest();

    var url = '../services/resendsocket?user_id=' + user_id + '&product_id=' + user_id + '&payment_id=' + payment_id;

    xhr.open('GET', url, true);

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                btn.className = 'btn btn-success text-white';
                btn.innerHTML = "<i class='bi bi-check2-all'></i> Resended";
                btn.disabled = true;
                Swal.fire({
                    title: "Success",
                    html: "Product has been resend",
                    icon: "success",
                    didOpen: function () {
                        var audioplay = new Audio("../assets/mp3/pop.mp3");
                        audioplay.play();
                    }
                });
            } 
        }
    };

    // Enviar la solicitud
    xhr.send();
}
