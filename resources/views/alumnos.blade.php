@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Gestión de Alumnos</h5>
        <button class="btn btn-light btn-sm" onclick="abrirModal()">Nuevo Alumno</button>
    </div>
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th><th>Nombre</th><th>Apellido</th><th>Cédula</th><th>Nacimiento</th><th>Edad</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-alumnos">
                <tr><td colspan="7" class="text-center">Cargando datos...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="alumnoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalTitle">Nuevo Alumno</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="alumnoForm">
            <input type="hidden" id="alumno_id">
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
                <input type="number" id="cedula" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Fecha de Nacimiento</label>
                <input type="date" id="nacimiento" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Edad</label>
                <input type="number" id="edad" class="form-control" required>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="guardarAlumno()">Guardar</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
    let modalAlumno;
    
    document.addEventListener('DOMContentLoaded', function() {
        modalAlumno = new bootstrap.Modal(document.getElementById('alumnoModal'));
        cargarAlumnos();
    });

    function cargarAlumnos() {
        fetch(`${API_URL}/alumnos`)
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('tabla-alumnos');
                tbody.innerHTML = ''; 
                if(data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="7" class="text-center">No hay alumnos</td></tr>';
                    return;
                }
                data.forEach(a => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${a.id}</td><td>${a.nombre}</td><td>${a.apellido}</td>
                            <td>${a.cedula}</td><td>${a.nacimiento}</td><td>${a.edad}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick='editarAlumno(${JSON.stringify(a)})'>Editar</button>
                                <button class="btn btn-danger btn-sm" onclick="eliminarAlumno(${a.id})">Eliminar</button>
                            </td>
                        </tr>`;
                });
            });
    }

    function abrirModal() {
        document.getElementById('alumnoForm').reset();
        document.getElementById('alumno_id').value = '';
        document.getElementById('modalTitle').innerText = 'Nuevo Alumno';
        modalAlumno.show();
    }

    function editarAlumno(alumno) {
        document.getElementById('alumno_id').value = alumno.id;
        document.getElementById('nombre').value = alumno.nombre;
        document.getElementById('apellido').value = alumno.apellido;
        document.getElementById('cedula').value = alumno.cedula;
        document.getElementById('nacimiento').value = alumno.nacimiento;
        document.getElementById('edad').value = alumno.edad;
        
        document.getElementById('modalTitle').innerText = 'Editar Alumno';
        modalAlumno.show();
    }

    function guardarAlumno() {
        const id = document.getElementById('alumno_id').value;
        const metodo = id ? 'PUT' : 'POST';
        const url = id ? `${API_URL}/alumnos/${id}` : `${API_URL}/alumnos`;

        const data = {
            nombre: document.getElementById('nombre').value,
            apellido: document.getElementById('apellido').value,
            cedula: document.getElementById('cedula').value,
            nacimiento: document.getElementById('nacimiento').value,
            edad: document.getElementById('edad').value
        };

        fetch(url, {
            method: metodo,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(response => {
            if(response.errores) {
                alert('Revisa los datos, hay errores de validación.');
                console.log(response.errores);
            } else {
                modalAlumno.hide();
                cargarAlumnos();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function eliminarAlumno(id) {
        if(confirm('¿Eliminar este alumno?')) {
            fetch(`${API_URL}/alumnos/${id}`, { 
                method: 'DELETE',
                headers: { 'Accept': 'application/json' }
            })
            .then(() => cargarAlumnos());
        }
    }
</script>
@endsection