@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Gestión de Profesores</h5>
        <button class="btn btn-light btn-sm" onclick="abrirModal()">Nuevo Profesor</button>
    </div>
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th><th>Nombre</th><th>Apellido</th><th>Cédula</th><th>Asignatura Asignada</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-profesores">
                <tr><td colspan="6" class="text-center">Cargando datos...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="profesorModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="modalTitle">Nuevo Profesor</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="profesorForm">
            <input type="hidden" id="profesor_id">
            <div class="mb-3">
                <label>Nombre</label>
                <input type="text" id="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Apellido</label>
                <input type="text" id="apellido" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Cédula</label>
                <input type="text" id="cedula" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Asignatura</label>
                <select id="asignatura_id" class="form-select" required>
                    <option value="">Cargando asignaturas...</option>
                </select>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-info text-white" onclick="guardarProfesor()">Guardar</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
    let modalProfesor;

    document.addEventListener('DOMContentLoaded', () => {
        modalProfesor = new bootstrap.Modal(document.getElementById('profesorModal'));
        cargarProfesores();
        cargarOpcionesAsignaturas();
    });

    function cargarOpcionesAsignaturas() {
        fetch(`${API_URL}/asignaturas`)
            .then(res => res.json())
            .then(data => {
                let options = '<option value="">Seleccione una asignatura...</option>';
                data.forEach(a => { options += `<option value="${a.id}">${a.nombre}</option>`; });
                document.getElementById('asignatura_id').innerHTML = options;
            });
    }

    function cargarProfesores() {
        fetch(`${API_URL}/profesores`)
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('tabla-profesores');
                tbody.innerHTML = ''; 

                if(data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-center">No hay profesores registrados</td></tr>';
                    return;
                }

                data.forEach(p => {
                    const nombreAsignatura = p.asignatura ? p.asignatura.nombre : 'Sin asignar';
                    tbody.innerHTML += `
                        <tr>
                            <td>${p.id}</td><td>${p.nombre}</td><td>${p.apellido}</td><td>${p.cedula}</td>
                            <td><span class="badge bg-secondary">${nombreAsignatura}</span></td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick='editarProfesor(${JSON.stringify(p)})'>Editar</button>
                                <button class="btn btn-danger btn-sm" onclick="eliminarProfesor(${p.id})">Eliminar</button>
                            </td>
                        </tr>`;
                });
            });
    }

    function abrirModal() {
        document.getElementById('profesorForm').reset();
        document.getElementById('profesor_id').value = '';
        document.getElementById('modalTitle').innerText = 'Nuevo Profesor';
        modalProfesor.show();
    }

    function editarProfesor(profesor) {
        document.getElementById('profesor_id').value = profesor.id;
        document.getElementById('nombre').value = profesor.nombre;
        document.getElementById('apellido').value = profesor.apellido;
        document.getElementById('cedula').value = profesor.cedula;
        document.getElementById('asignatura_id').value = profesor.asignatura_id;
        
        document.getElementById('modalTitle').innerText = 'Editar Profesor';
        modalProfesor.show();
    }

    function guardarProfesor() {
        const id = document.getElementById('profesor_id').value;
        const metodo = id ? 'PUT' : 'POST';
        const url = id ? `${API_URL}/profesores/${id}` : `${API_URL}/profesores`;

        const data = {
            nombre: document.getElementById('nombre').value,
            apellido: document.getElementById('apellido').value,
            cedula: document.getElementById('cedula').value,
            asignatura_id: document.getElementById('asignatura_id').value
        };

        fetch(url, {
            method: metodo,
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(response => {
            if(response.errores) alert('Revisa los datos. Posible cédula duplicada.');
            else { modalProfesor.hide(); cargarProfesores(); }
        });
    }

    function eliminarProfesor(id) {
        if(confirm('¿Eliminar este profesor?')) {
            fetch(`${API_URL}/profesores/${id}`, { method: 'DELETE' }).then(() => cargarProfesores());
        }
    }
</script>
@endsection