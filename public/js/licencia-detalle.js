// resources/js/licencia-detalle.js

// Variables para el modo edición
let modoEdicion = false;

function activarEdicion() {
    modoEdicion = true;
    
    // Ocultar textos y mostrar inputs
    document.getElementById('clave-text').style.display = 'none';
    document.getElementById('clave-input').style.display = 'block';
    
    document.getElementById('estado-text').style.display = 'none';
    document.getElementById('estado-input').style.display = 'block';
    
    document.getElementById('software-text').style.display = 'none';
    document.getElementById('software-input').style.display = 'block';
    
    document.getElementById('capacidad-text').style.display = 'none';
    document.getElementById('capacidad-input').style.display = 'block';
    
    document.getElementById('descripcion-text').style.display = 'none';
    document.getElementById('descripcion-input').style.display = 'block';
    
    // Fechas editables
    document.getElementById('fecha-activacion-text').style.display = 'none';
    document.getElementById('fecha-activacion-input').style.display = 'block';
    
    document.getElementById('fecha-vencimiento-text').style.display = 'none';
    document.getElementById('fecha-vencimiento-input').style.display = 'block';
    
    // Cambiar botones
    document.getElementById('btnEditar').style.display = 'none';
    document.getElementById('btnGuardar').style.display = 'inline-flex';
    document.getElementById('btnCancelar').style.display = 'inline-flex';
}

function cancelarEdicion() {
    modoEdicion = false;
    
    // Restaurar textos y ocultar inputs
    document.getElementById('clave-text').style.display = 'block';
    document.getElementById('clave-input').style.display = 'none';
    
    document.getElementById('estado-text').style.display = 'block';
    document.getElementById('estado-input').style.display = 'none';
    
    document.getElementById('software-text').style.display = 'block';
    document.getElementById('software-input').style.display = 'none';
    
    document.getElementById('capacidad-text').style.display = 'block';
    document.getElementById('capacidad-input').style.display = 'none';
    
    document.getElementById('descripcion-text').style.display = 'block';
    document.getElementById('descripcion-input').style.display = 'none';
    
    // Fechas
    document.getElementById('fecha-activacion-text').style.display = 'block';
    document.getElementById('fecha-activacion-input').style.display = 'none';
    
    document.getElementById('fecha-vencimiento-text').style.display = 'block';
    document.getElementById('fecha-vencimiento-input').style.display = 'none';
    
    // Restaurar botones
    document.getElementById('btnEditar').style.display = 'inline-flex';
    document.getElementById('btnGuardar').style.display = 'none';
    document.getElementById('btnCancelar').style.display = 'none';
}

async function guardarCambios() {
    const clave = document.getElementById('clave-input').value.trim();
    const estado = document.getElementById('estado-input').value;
    const idSoftware = document.getElementById('software-input').value;
    const capacidad = document.getElementById('capacidad-input').value;
    const descripcion = document.getElementById('descripcion-input').value.trim();
    const fechaActivacion = document.getElementById('fecha-activacion-input').value;
    const fechaVencimiento = document.getElementById('fecha-vencimiento-input').value;

    if (!clave) {
        mostrarAlerta('error', 'La clave de licencia es requerida');
        return;
    }

    if (!idSoftware) {
        mostrarAlerta('error', 'Debes seleccionar un software');
        return;
    }

    if (!fechaActivacion) {
        mostrarAlerta('error', 'La fecha de activación es requerida');
        return;
    }

    if (!fechaVencimiento) {
        mostrarAlerta('error', 'La fecha de vencimiento es requerida');
        return;
    }

    const btn = document.getElementById('btnGuardar');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span>⏳</span> Guardando...';

    try {
        const response = await fetch(`/licencia/${licenciaId}/actualizar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                clave: clave,
                estado: estado,
                idSoftware: idSoftware,
                capacidad: capacidad || null,
                descripcion: descripcion || null,
                fecha_activacion: fechaActivacion,
                fecha_vencimiento: fechaVencimiento
            })
        });

        const data = await response.json();

        if (data.success) {
            mostrarAlerta('success', '✅ Licencia actualizada correctamente');
            
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            mostrarAlerta('error', data.message || 'Error al guardar');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarAlerta('error', 'Error de conexión al servidor');
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
}

function mostrarAlerta(tipo, mensaje) {
    const alerta = document.getElementById('alerta');
    alerta.className = `alert-message alert-${tipo}`;
    alerta.textContent = mensaje;
    alerta.style.display = 'block';
    
    setTimeout(() => {
        alerta.style.display = 'none';
    }, 3000);
}

// Cerrar alerta con Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const alerta = document.getElementById('alerta');
        if (alerta.style.display === 'block') {
            alerta.style.display = 'none';
        }
    }
});