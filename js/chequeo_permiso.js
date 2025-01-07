function chequeo_permiso(modulo) {
    return fetch('api/chequeo_permiso.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ modulo })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.permiso) {
            window.location.href = 'desautorizado.php';
        }
    });
}