@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Gestión de Asignaturas</h5>
        <button class="btn btn-light btn-sm" onclick="abrirModal()">Nueva Asignatura</button>
    </div>
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th><th>Nombre</th><th>Descripción</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-asignaturas">
                <tr><td colspan="4" class="text-center">Cargando datos...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="asignaturaModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalTitle">Nueva Asignatura</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="asignaturaForm">
            <input type="hidden" id="asignatura_id">
            <div class="mb-3">
                <label>Nombre</label>
                <input type="text" id="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Descripción</label>
                <textarea id="descripcion" class="form-control" rows="3" required></textarea>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success" onclick="guardarAsignatura()">Guardar</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
    let modalAsignatura;

    document.addEventListener('DOMContentLoaded', () => {
        modalAsignatura = new bootstrap.Modal(document.getElementById('asignaturaModal'));
        cargarAsignaturas();
    });

    function cargarAsignaturas() {
        fetch(`${API_URL}/asignaturas`)
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('tabla-asignaturas');
                tbody.innerHTML = ''; 

                if(data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center">No hay asignaturas registradas</td></tr>';
                    return;
                }

                data.forEach(a => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${a.id}</td>
                            <td>${a.nombre}</td>
                            <td>${a.descripcion}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick='editarAsignatura(${JSON.stringify(a)})'>Editar</button>
                                <button class="btn btn-danger btn-sm" onclick="eliminarAsignatura(${a.id})">Eliminar</button>
                            </td>
                        </tr>`;
                });
            })
            .catch(error => console.error('Error:', error));
    }

    function abrirModal() {
        document.getElementById('asignaturaForm').reset();
        document.getElementById('asignatura_id').value = '';
        document.getElementById('modalTitle').innerText = 'Nueva Asignatura';
        modalAsignatura.show();
    }

    function editarAsignatura(asignatura) {
        document.getElementById('asignatura_id').value = asignatura.id;
        document.getElementById('nombre').value = asignatura.nombre;
        document.getElementById('descripcion').value = asignatura.descripcion;
        
        document.getElementById('modalTitle').innerText = 'Editar Asignatura';
        modalAsignatura.show();
    }

    function guardarAsignatura() {
        const id = document.getElementById('asignatura_id').value;
        const metodo = id ? 'PUT' : 'POST';
        const url = id ? `${API_URL}/asignaturas/${id}` : `${API_URL}/asignaturas`;

        const data = {
            nombre: document.getElementById('nombre').value,
            descripcion: document.getElementById('descripcion').value
        };

        fetch(url, {
            method: metodo,
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(response => {
            if(response.errores) {
                alert('Error en los datos.');
            } else {
                modalAsignatura.hide();
                cargarAsignaturas();
            }
        });
    }

    function eliminarAsignatura(id) {
        if(confirm('¿Eliminar esta asignatura?')) {
            fetch(`${API_URL}/asignaturas/${id}`, { method: 'DELETE' })
            .then(() => cargarAsignaturas());
        }
    }
</script>
@endsection