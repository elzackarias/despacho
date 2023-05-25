<?php
session_start();
include 'components/navbar.php';
include 'components/footer.php';
include 'lib/funcs.php';
if (!isset($_SESSION['uid'])) {
    header("Location: login.php?prev=home");
}
if (!verify($_SESSION['uid'])) {
    header("Location: onboard.php");
}
$data = getData($_SESSION['uid']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Realizar cita - Despacho Contable</title>
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/vendor/sweetalert2/sweetalert2.css">
    <link href="assets/css/style.css" rel="stylesheet">
    <script src='assets/vendor/fullcalendar/main.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const datos = <?= getAppointments(); ?>;
            const hours = [{
                hour: 12,
                taken: false,
                className: 'btn-success'
            }, {
                hour: 14,
                taken: false,
                className: 'btn-success'
            }, {
                hour: 16,
                taken: false,
                className: 'btn-success'
            }, {
                hour: 18,
                taken: false,
                className: 'btn-success'
            }];
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                validRange: {
                    start: new Date() // Set the minimum date to the current date (start of the day)
                },
                selectLongPressDelay: 1,
                // eventClick: function(info) {
                //     console.log(info.event)
                // },
                selectable: true,
                select: function(info) {
                    //var selectedDay = info.start.day();
                    // Check if the selected day is a non-working day (e.g., Saturday or Sunday)
                    if (isNonWorkingDay(info.start)) {
                        calendar.unselect()
                        return false; // Return false to prevent selection of non-working days
                    } else {
                        hours[0].taken = hours[1].taken = hours[2].taken = hours[3].taken = false;
                        hours[0].className = hours[1].className = hours[2].className = hours[3].className = 'btn-success';
                        Swal.fire({
                            title: 'Cargando...',
                            allowOutsideClick: false,
                            showCancelButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                                Swal.getHtmlContainer().style.pointerEvents = 'none';
                            }
                        });
                        const url = 'https://2104-187-191-42-160.ngrok-free.app/api/index.php?type=q&que=appointments_available&dia=' + info.startStr;
                        fetch(url)
                            .then(function(response) {
                                if (response.ok) {
                                    return response.json();
                                } else {
                                    throw new Error('Error en la petición.');
                                }
                            })
                            .then(function(data) {
                                Swal.close();
                                if (data.data.length > 0) {
                                    data.data.map(function(date) {
                                        switch (date.hour) {
                                            case '12':
                                                hours[0].taken = true;
                                                hours[0].className = "btn-danger"
                                                break;
                                            case '14':
                                                hours[1].taken = true;
                                                hours[1].className = "btn-danger"
                                                break;
                                            case '16':
                                                hours[2].taken = true;
                                                hours[2].className = "btn-danger"
                                                break;
                                            case '18':
                                                hours[3].taken = true;
                                                hours[3].className = "btn-danger"
                                                break;
                                            default:
                                                break;
                                        }
                                    })
                                } else {
                                    hours[0].taken = hours[1].taken = hours[2].taken = hours[3].taken = false;
                                    hours[0].className = hours[1].className = hours[2].className = hours[3].className = 'btn-success';
                                }
                                for (var i = 0; i < hours.length; i++) {
                                    if (hours[i].hour <= data.local && info.startStr === data.date) {
                                        hours[i].taken = true;
                                        hours[i].className = "btn-danger";
                                    }
                                }
                                Swal.fire({
                                    title: 'Seleccione la hora para su cita:',
                                    html: `<button type='button' onclick="selectedHour(12,'${info.startStr}')" class='btn ${hours[0].className} mt-2' ${hours[0].taken == true ? 'disabled' : ''} ><b>12:00 PM</b></button>
                                    <button type='button' onclick="selectedHour(14,'${info.startStr}')" class='btn ${hours[1].className} ms-2 mt-2' ${hours[1].taken == true ? 'disabled' : ''} ><b>14:00 PM</b></button>
                                    <button type='button' onclick="selectedHour(16,'${info.startStr}')" class='btn ${hours[2].className} ms-2 mt-2' ${hours[2].taken == true ? 'disabled' : ''} ><b>16:00 PM</b></button>
                                    <button type='button' onclick="selectedHour(18,'${info.startStr}')" class='btn ${hours[3].className} ms-2 mt-2' ${hours[3].taken == true ? 'disabled' : ''} ><b>18:00 PM</b></button>
                                    <br><div class="green-square"></div><span style="margin-right: 12px;"> Disponibles</span><div class="green-square" style="background-color: #e9818c;"></div><span> No Disponibles</span>
                                    `,
                                    confirmButtonText: 'Cerrar',
                                    allowOutsideClick: () => !Swal.isLoading()
                                });
                            })
                            .catch(function(error) {
                                alert(error);
                                // Manejar el error
                            });

                        calendar.unselect(); // deselecciona la fecha y hora seleccionada
                    }
                },
                initialView: 'dayGridMonth',
                firstDay: 1,
                locale: 'es',
                businessHours: {
                    daysOfWeek: [1, 2, 3, 4, 5], // Monday - Friday
                },
                events: datos,
            });
            calendar.render();
        });

        function isNonWorkingDay(date) {
            var day = date.getDay();
            return (day === 0 || day === 6);
        }

        function selectedHour(hour, date) {
            Swal.close();
            Swal.fire({
                title: 'Detalles para su cita:',
                html: `<form method="post" id="formAppoint" autocomplete="off" onsubmit="addAppoint(event)" >
                    <input type="hidden" id="uid" value="<?= base64_encode($_SESSION['uid']) ?>">
                    <input type="hidden" id="tk" value="<?= base64_encode($_SESSION['tk']) ?>">
                    <input type="hidden" id="date" value="${date} ${hour}:00:00">
                    <textarea class="form-control" rows="3" id="subject" placeholder="Asunto de la cita..." required></textarea>
                    <p class="mt-3 mb-0" style="font-weight:700;">Modalidad:</p>
                    <div class="form-check" style="display: inline-flex;margin-right: 8px;">
                        <input class="form-check-input" style="margin-right: 5px;" type="radio" name="modality" id="onsite" value="onsite" checked>
                        <label class="form-check-label" for="onsite">
                            Presencial
                        </label>
                    </div>
                    <div class="form-check" style="display: inline-flex;">
                        <input class="form-check-input" style="margin-right: 5px;" type="radio" name="modality" id="online" value="online">
                        <label class="form-check-label" for="online">
                            En Línea
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-form mt-2" name="btnAdd" id="btnAdd">Realizar cita</button>
                    <button type="submit" onclick="Swal.close();" class="btn btn-secondary btn-form mt-2" id="cancelar">Cancelar</button>
                </form>`,
                allowOutsideClick: false,
                showConfirmButton: false,
            })
        }

        async function addAppoint(e) {
            e.preventDefault();
            const btnAdd = document.getElementById('btnAdd');
            const cancelar = document.getElementById('cancelar');
            const subject = document.getElementById('subject').value;
            const date = document.getElementById('date').value;
            const uid = document.getElementById('uid').value;
            const tk = document.getElementById('tk').value;
            const modality = document.querySelector('input[name="modality"]:checked ').value;
            if (!isEmpty(subject)) {
                btnAdd.disabled = true;
                cancelar.disabled = true;
                btnAdd.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Cargando...';
                const settings = {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        subject,
                        uid,
                        tk,
                        modality,
                        date
                    })
                };
                try {
                    const q = await fetch('/api/index.php?type=insertar&que=appointment', settings);
                    const res = await q.json();
                    if (res.status == "EXITO") {
                        Swal.fire({
                            title: 'Exito!',
                            text: res.msg,
                            icon: 'success',
                            allowOutsideClick: false,
                            timer: 2000, // Duración de la alerta en milisegundos (2 segundos)
                            showConfirmButton: false
                            }).then(function() {
                                window.location.replace("https://2104-187-191-42-160.ngrok-free.app/addAppointment.php");
                        });
                    } else {
                        alert(res.msg)
                        btnAdd.innerHTML = "Realizar cita";
                        btnAdd.disabled = false;
                        cancelar.disabled = false;
                    }

                } catch (error) {
                    btnAdd.innerHTML = "Realizar cita";
                    btnAdd.disabled = false;
                    cancelar.disabled = false;
                    Swal.fire({
                        title: 'Opss!',
                        text: 'Ocurrió un error en el servidor',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            } else {
                alert('Debes rellenar todos los campos');
            }
        }

        function isEmpty(txt) {
            return txt.length == 0 ? true : false;
        }

    </script>
</head>

<body>
    <?= navbar_logged(); ?>
    <div id="loader"></div>
    <section id="contact" class="contact section-bg">
        <div class="container">
            <div class="section-title mt-2 pb-0">
                <h4 style="text-transform:none !important;font-weight:500;">Haga clic en la fecha deseada para su cita</h4>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </section>
    <?= footer(); ?>
    <div id="preloader"></div>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/sweetalert2/sweetalert2.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>