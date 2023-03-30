const userElement = document.getElementById('user');
const emailElement = document.getElementById('email');
const userStatusElement = document.getElementById('statususer');
const emailStatusElement = document.getElementById('statusemail');
const formSignup = document.getElementById('formsignup');
const btnsignup = document.getElementById('signup');
const nospaces = /\s/;
const email_regex = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i
const status_obj = { mail: 0, user: 0 };
userElement.addEventListener('change', async (e) => {
    const text = e.target.value
    if (!isEmpty(text)) {
        const settings = {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                user: text.toLowerCase().trim()
            })
        }
        if (nospaces.test(text) == true) {
            userStatusElement.innerText = ""
            userElement.classList.remove("bg-exito")
            userElement.classList.add("bg-error");
            userStatusElement.classList.add("error-txt");
            userStatusElement.innerText = "No puedes ingresar espacios"
            status_obj.user = 0
        } else {
            try {
                const response = await fetch('/api/index.php?type=q&que=user_verify', settings);
                const data = await response.json();
                remove_addAll(data.status, data.msg, "user");
            } catch (error) {
                alert("Something wrong! :s");
            }
        }
    } else {
        userStatusElement.innerText = "";
        userStatusElement.classList.remove("error-txt", "exito-txt");
        userElement.classList.remove("bg-exito", "bg-error");
        status_obj.user = 0
    }

});
emailElement.addEventListener('change', async (e) => {
    const text = e.target.value
    if (!isEmpty(text)) {
        const settings = {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                email: text.toLowerCase().trim()
            })
        }
        if (email_regex.test(text) == false) {
            emailStatusElement.innerText = ""
            emailElement.classList.remove("bg-exito")
            emailElement.classList.add("bg-error");
            emailStatusElement.classList.add("error-txt");
            emailStatusElement.innerText = "Esto no es un correo"
            status_obj.mail = 0
        } else {
            try {
                const response = await fetch('/api/index.php?type=q&que=email_verify', settings);
                const data = await response.json();
                remove_addAll(data.status, data.msg, "email");
            } catch (error) {
                alert(error);
            }
        }
    } else {
        emailStatusElement.innerText = "";
        emailStatusElement.classList.remove("error-txt", "exito-txt");
        emailElement.classList.remove("bg-exito", "bg-error");
        status_obj.mail = 0
    }
});

formSignup.addEventListener('submit', signup);
async function signup(e) {
    e.preventDefault();
    btnsignup.disabled = true;
    btnsignup.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Cargando...';
    /*Swal.fire({
        title: 'Error!',
        text: 'Do you want to continue',
        icon: 'error',
        confirmButtonText: 'Cool'
    })*/
    const firstname = document.getElementById('firstname').value;
    const lastname = document.getElementById('lastname').value;
    const email = document.getElementById('email').value;
    const user = document.getElementById('user').value;
    const phone = document.getElementById('telephone').value;
    const cell = document.getElementById('cellphone').value;
    const password = document.getElementById('pwd').value;
    if (status_obj.mail == 0 || status_obj.user == 0) {
        btnsignup.innerHTML = "Registrarme";
        btnsignup.disabled = false;
        Swal.fire({
            title: 'Opss!',
            text: 'Ingresa un email y/o un usuario disponible',
            icon: 'warning',
            confirmButtonText: 'Aceptar'
        });
    } else {
        if (phone.length != 10 || cell.length != 10) {
            btnsignup.innerHTML = "Registrarme";
            btnsignup.disabled = false;
            Swal.fire({
                title: 'Opss!',
                text: 'El número de telefono o el celular debe ser de 10 digitos',
                icon: 'warning',
                confirmButtonText: 'Aceptar'
            });
        } else {
            const settings = {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    firstname,
                    lastname,
                    email,
                    user,
                    phone,
                    cell,
                    password,
                })
            };
            try {
                const q = await fetch('/api/index.php?type=register', settings);
                const res = await q.json();
                if(res.status == "EXITO"){
                    window.location.replace("http://192.168.100.102/home.php");
                }else{
                    btnsignup.innerHTML = "Registrarme";
                    btnsignup.disabled = false;
                    Swal.fire({
                        title: 'Opss!',
                        text: 'PROBLEMAAA',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });  
                }
            } catch (error) {
                btnsignup.innerHTML = "Registrarme";
                btnsignup.disabled = false;
                Swal.fire({
                    title: 'Opss!',
                    text: 'Ocurrio un error al registrarse',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        }
    }
}
function remove_addAll(status, msg, type) {
    if (type == "email") {
        var Elemento = emailElement;
        var StatusElement = emailStatusElement;
    } else {
        var Elemento = userElement;
        var StatusElement = userStatusElement;
    }
    if (status == "EXITO") {
        Elemento.classList.remove("bg-error");
        StatusElement.classList.remove("error-txt");
        Elemento.classList.add("bg-exito");
        StatusElement.classList.add("exito-txt");
        StatusElement.innerText = msg;
        if (type == "email") {
            status_obj.mail = 1
        } else {
            status_obj.user = 1
        }
    } else {
        Elemento.classList.remove("bg-exito");
        StatusElement.classList.remove("exito-txt");
        Elemento.classList.add("bg-error");
        StatusElement.classList.add("error-txt");
        StatusElement.innerText = msg;
        if (type == "email") {
            status_obj.mail = 0
        } else {
            status_obj.user = 0
        }
    }
}
function isEmpty(txt) {
    return txt.length == 0 ? true : false;
}
function allAvailable(obj) {
    for (let key in obj) {
        if (obj.hasOwnProperty(key) && obj[key] !== 1) {
            return false;
            break;
        }
    }
    return true;
}