const formAddress = document.getElementById('formaddress');
const btnaccept = document.getElementById('btnaccept');
formAddress.addEventListener('submit', insertAddress);
async function insertAddress(e) {
    e.preventDefault();
    const uid = document.getElementById('uidhash').value;
    const tk = document.getElementById('tkhash').value;
    const street = document.getElementById('street').value;
    const city = document.getElementById('city').value;
    const colony = document.getElementById('colony').value;
    const zipcode = document.getElementById('zipcode').value;

    if (!isEmpty(uid) && !isEmpty(tk) && !isEmpty(street) && !isEmpty(city) && !isEmpty(colony) && !isEmpty(zipcode)) {
        btnaccept.disabled = true;
        btnaccept.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Cargando...';
        const settings = {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                uid,
                tk,
                street,
                city,
                colony,
                zipcode
            })
        };
        try {
            const q = await fetch('/api/index.php?type=insertar&que=direccion', settings);
            const res = await q.json();
            if (res.status == "EXITO") {
                Swal.fire({
                    title: 'Éxito!',
                    text: 'Se ha registrado la dirección',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(function() {
                    window.location.replace("https://2104-187-191-42-160.ngrok-free.app/home.php");
                })
            } else {
                Swal.fire({
                    title: 'Opss!',
                    text: 'Ocurrio un error',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
                btnaccept.innerHTML = "Aceptar";
                btnaccept.disabled = false;
            }
            
        } catch (error) {
            btnaccept.innerHTML = "Aceptar";
            btnaccept.disabled = false;
            Swal.fire({
                title: 'Opss!',
                text: 'Ocurrio un error en el servidor',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    } else {
        Swal.fire({
            title: 'Opss!',
            text: 'Debes rellenar todos los campos',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
    }
}
function isEmpty(txt) {
    return txt.length == 0 ? true : false;
}