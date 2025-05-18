

<head>
    <meta charset="UTF-8">
    <title>FullCalendar with PHP</title>

    <!-- FullCalendar CSS -->
    <link href="assets/fullcalendar/css/fullcalendar.css" rel="stylesheet">

    <!-- FullCalendar CSS -->
    <!--<link href="https://unpkg.com/@fullcalendar/core@6.1.8/index.global.min.css" rel="stylesheet" />-->

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- FullCalendar JS -->
    <script src="https://unpkg.com/@fullcalendar/core@6.1.8/index.global.min.js"></script>
    <script src="https://unpkg.com/@fullcalendar/daygrid@6.1.8/index.global.min.js"></script>
    <script src="https://unpkg.com/@fullcalendar/timegrid@6.1.8/index.global.min.js"></script>
    <script src="https://unpkg.com/@fullcalendar/interaction@6.1.8/index.global.min.js"></script>
    <script src="https://unpkg.com/@fullcalendar/core@6.1.8/locales-all.global.min.js"></script>




</head>
<body>

<div id="calendar"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'es', // Calendario en español
            initialView: 'dayGridMonth', // Vista inicial (Mes)
            headerToolbar: {
                left: 'prev,next today', // Navegación
                center: 'title',         // Título del calendario
                right: 'dayGridMonth,timeGridWeek,timeGridDay' // Las vistas (Mes, Semana, Día)
            },
            views: {
                dayGridMonth: { // Vista de mes
                    titleFormat: { year: 'numeric', month: 'long' }
                },
                timeGridWeek: { // Vista de semana
                    titleFormat: { year: 'numeric', month: 'long', day: 'numeric' }
                },
                timeGridDay: { // Vista de día
                    titleFormat: { year: 'numeric', month: 'long', day: 'numeric' }
                }
            },
            editable: true,  // Permitir editar eventos
            selectable: true, // Permitir seleccionar fechas
            events: 'controllers/calendar/load.php', // Aquí cargas tus eventos desde la base de datos
            eventClick: function(info) {
                // Aquí puedes agregar tu función de edición para cuando se hace click en un evento
                Swal.fire({
                    title: 'Editar Evento',
                    input: 'text',
                    inputLabel: 'Nuevo Título',
                    inputValue: info.event.title,
                    showCancelButton: true,
                    confirmButtonText: 'Guardar',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Aquí actualizas el evento con el nuevo título
                        var updatedEvent = {
                            id: info.event.id,
                            title: result.value
                        };

                        fetch('controllers/calendar/edit.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(updatedEvent)
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    info.event.setProp('title', result.value); // Cambiar el título en la interfaz
                                    Swal.fire('Actualizado', 'El evento ha sido actualizado.', 'success');
                                } else {
                                    Swal.fire('Error', 'Hubo un error al actualizar el evento.', 'error');
                                }
                            });
                    }
                });
            },
            dateClick: function(info) {
                // Aquí puedes agregar tu lógica para crear eventos al hacer click en una fecha
                Swal.fire({
                    title: 'Nuevo Evento',
                    input: 'text',
                    inputLabel: 'Título del Evento',
                    showCancelButton: true,
                    confirmButtonText: 'Crear',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var newEvent = {
                            title: result.value,
                            start: info.dateStr
                        };

                        fetch('controllers/calendar/insert.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(newEvent)
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    calendar.refetchEvents(); // Recargar los eventos
                                    Swal.fire('Creado', 'El evento ha sido creado.', 'success');
                                } else {
                                    Swal.fire('Error', 'Hubo un error al crear el evento.', 'error');
                                }
                            });
                    }
                });
            }
        });

        calendar.render();
    });






</script>

</body>




