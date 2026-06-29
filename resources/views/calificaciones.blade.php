@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Gestión de Calificaciones</h5>
        <button class="btn btn-light btn-sm" onclick="abrirModal()">Cargar Calificación</button>
    </div>
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th><th>Alumno</th><th>Asignatura</th><th>Calificación</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-calificaciones">
                <tr><td colspan="5" class="text-center">Cargando datos...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="calificacionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="modalTitle">Cargar Calificación</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="calificacionForm">
            <input type="hidden" id="calificacion_id">
            <div class="mb-3">
                <label>Alumno</label>
                <select id="alumno_id" class="form-select" required>
                    <option value="">Cargando alumnos...</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Asignatura</label>
                <select id="asignatura_id" class="form-select" required>
                    <option value="">Cargando asignaturas...</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Calificación (0 a 20)</label>
                <input type="number" step="0.01" min="0" max="20" id="calificacion" class="form-control" required>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-dark" onclick="guardarCalificacion()">Guardar</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
    let modalCalificacion;

    document.addEventListener('DOMContentLoaded', () => {
        modalCalificacion = new bootstrap.Modal(document.getElementById('calificacionModal'));
        cargarCalificaciones();
        cargarOpciones();
    });

    function cargarOpciones() {
        // Cargar Alumnos
        fetch(`${API_URL}/alumnos`).then(res => res.json()).then(data => {
            let options = '<option value="">Seleccione un alumno...</option>';
            data.forEach(a => { options += `<option value="${a.id}">${a.nombre} ${a.apellido}</option>`; });
            document.getElementById('alumno_id').innerHTML = options;
        });
        
        // Cargar Asignaturas
        fetch(`${API_URL}/asignaturas`).then(res => res.json()).then(data => {
            let options = '<option value="">Seleccione una asignatura...</option>';
            data.forEach(a => { options += `<option value="${a.id}">${a.nombre}</option>`; });
            document.getElementById('asignatura_id').innerHTML = options;
        });
    }

    function cargarCalificaciones() {
        fetch(`${API_URL}/calificaciones`)
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('tabla-calificaciones');
                tbody.innerHTML = ''; 

                if(data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" class="text-center">No hay calificaciones</td></tr>';
                    return;
                }

                data.forEach(item => {
                    const nombreAlumno = item.alumno ? `${item.alumno.nombre} ${item.alumno.apellido}` : 'Desconocido';
                    const nombreAsignatura = item.asignatura ? item.asignatura.nombre : 'Desconocida';
                    const colorNota = item.calificacion < 10 ? 'text-danger fw-bold' : 'text-success fw-bold';

                    tbody.innerHTML += `
                        <tr>
                            <td>${item.id}</td>
                            <td>${nombreAlumno}</td>
                            <td>${nombreAsignatura}</td>
                            <td class="${colorNota}">${item.calificacion}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick='editarCalificacion(${JSON.stringify(item)})'>Editar</button>
                                <button class="btn btn-danger btn-sm" onclick="eliminarCalificacion(${item.id})">Eliminar</button>
                            </td>
                        </tr>`;
                });
            });
    }

    function abrirModal() {
        document.getElementById('calificacionForm').reset();
        document.getElementById('calificacion_id').value = '';
        document.getElementById('modalTitle').innerText = 'Cargar Calificación';
        modalCalificacion.show();
    }

    function editarCalificacion(item) {
        document.getElementById('calificacion_id').value = item.id;
        document.getElementById('alumno_id').value = item.alumno_id;
        document.getElementById('asignatura_id').value = item.asignatura_id;
        document.getElementById('calificacion').value = item.calificacion;
        
        document.getElementById('modalTitle').innerText = 'Editar Calificación';
        modalCalificacion.show();
    }

    function guardarCalificacion() {
        const id = document.getElementById('calificacion_id').value;
        const metodo = id ? 'PUT' : 'POST';
        const url = id ? `${API_URL}/calificaciones/${id}` : `${API_URL}/calificaciones`;

        const data = {
            alumno_id: document.getElementById('alumno_id').value,
            asignatura_id: document.getElementById('asignatura_id').value,
            calificacion: document.getElementById('calificacion').value
        };

        fetch(url, {
            method: metodo,
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(response => {
            if(response.errores) alert('Verifica que todos los campos estén llenos y la nota sea de 0 a 20.');
            else { modalCalificacion.hide(); cargarCalificaciones(); }
        });
    }

    function eliminarCalificacion(id) {
        if(confirm('¿Eliminar esta calificación?')) {
            fetch(`${API_URL}/calificaciones/${id}`, { method: 'DELETE' }).then(() => cargarCalificaciones());
        }
    }
</script>
@endsection