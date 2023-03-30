const formLogin = document.getElementById('formlogin');
const btnlogin = document.getElementById('btnlogin');
formLogin.addEventListener('submit', login);
async function login(e) {
    e.preventDefault();
    const email = document.getElementById('email').value;
    const password = document.getElementById('pwd').value;
    if (!isEmpty(email) && !isEmpty(password)) {
        btnlogin.disabled = true;
        btnlogin.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Cargando...';
        const settings = {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                email,
                password
            })
        };
        try {
            const q = await fetch('/api/index.php?type=login', settings);
            const res = await q.json();
            if (res.status == "EXITO") {
                window.location.replace("http://192.168.100.102/home.php");
            } else {
                Swal.fire({
                    title: 'Opss!',
                    text: 'Email/Usuario o contraseña incorrecta',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
                btnlogin.innerHTML = "Iniciar sesión";
                btnlogin.disabled = false;
            }
            
        } catch (error) {
            btnlogin.innerHTML = "Iniciar sesión";
            btnlogin.disabled = false;
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
