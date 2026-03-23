// public/js/articulo-detalle.js

// Función para activar modo edición
function activarEdicion() {
    // Ocultar textos y mostrar inputs
    document.getElementById('serie-text').style.display = 'none';
    document.getElementById('serie-input').style.display = 'block';
    
    document.getElementById('estado-text').style.display = 'none';
    document.getElementById('estado-input').style.display = 'block';
    
    document.getElementById('area-text').style.display = 'none';
    document.getElementById('area-input').style.display = 'block';
    
    document.getElementById('grupo-text').style.display = 'none';
    document.getElementById('grupo-input').style.display = 'block';
    
    document.getElementById('tipo-grupo-text').style.display = 'none';
    document.getElementById('tipo-grupo-container').style.display = 'flex';
    
    // Si hay sección de red, activar sus inputs
    if (document.getElementById('mac-text')) {
        document.getElementById('mac-text').style.display = 'none';
        document.getElementById('mac-input').style.display = 'block';
        
        document.getElementById('ip-text').style.display = 'none';
        document.getElementById('ip-input').style.display = 'block';
        
        document.getElementById('obs-text').style.display = 'none';
        document.getElementById('obs-input').style.display = 'block';
    }
    
    // Cambiar botones
    document.getElementById('btnEditar').style.display = 'none';
    document.getElementById('btnGuardar').style.display = 'inline-flex';
    document.getElementById('btnCancelar').style.display = 'inline-flex';
}

// Función para cancelar edición
function cancelarEdicion() {
    // Restaurar textos y ocultar inputs
    document.getElementById('serie-text').style.display = 'block';
    document.getElementById('serie-input').style.display = 'none';
    
    document.getElementById('estado-text').style.display = 'block';
    document.getElementById('estado-input').style.display = 'none';
    
    document.getElementById('area-text').style.display = 'block';
    document.getElementById('area-input').style.display = 'none';
    
    document.getElementById('grupo-text').style.display = 'block';
    document.getElementById('grupo-input').style.display = 'none';
    
    document.getElementById('tipo-grupo-text').style.display = 'block';
    document.getElementById('tipo-grupo-container').style.display = 'none';
    
    // Si hay sección de red, restaurar sus textos
    if (document.getElementById('mac-text')) {
        document.getElementById('mac-text').style.display = 'block';
        document.getElementById('mac-input').style.display = 'none';
        
        document.getElementById('ip-text').style.display = 'block';
        document.getElementById('ip-input').style.display = 'none';
        
        document.getElementById('obs-text').style.display = 'block';
        document.getElementById('obs-input').style.display = 'none';
    }
    
    // Restaurar botones
    document.getElementById('btnEditar').style.display = 'inline-flex';
    document.getElementById('btnGuardar').style.display = 'none';
    document.getElementById('btnCancelar').style.display = 'none';
}

// Función para abrir modal de nuevo tipo
function abrirModalTipo() {
    document.getElementById('overlay').classList.add('active');
    document.getElementById('modal-tipo').classList.add('active');
}

function cerrarModalTipo() {
    document.getElementById('overlay').classList.remove('active');
    document.getElementById('modal-tipo').classList.remove('active');
    document.getElementById('nuevo_tipo_nombre').value = '';
}

// Función para guardar nuevo tipo de grupo
function guardarNuevoTipoGrupo() {
    const nombre = document.getElementById('nuevo_tipo_nombre').value.trim().toUpperCase();
    
    if (!nombre) {
        mostrarAlerta('error', 'El nombre del tipo es requerido');
        return;
    }

    const select = document.getElementById('tipo-grupo-input');
    const option = document.createElement('option');
    option.value = nombre;
    option.text = nombre;
    option.selected = true;
    select.appendChild(option);
    
    mostrarAlerta('success', '✅ Tipo agregado correctamente');
    cerrarModalTipo();
}

// Función para guardar cambios
async function guardarCambios() {
    const serie = document.getElementById('serie-input').value;
    const estado = document.getElementById('estado-input').value;
    const idArea = document.getElementById('area-input').value;
    const nombreGrupo = document.getElementById('grupo-input').value;
    const tipoGrupo = document.getElementById('tipo-grupo-input').value;

    // Datos de red (si existen)
    let datosRed = {};
    if (document.getElementById('mac-input')) {
        datosRed = {
            tipoRed: document.getElementById('tipo-red').value,
            mac: document.getElementById('mac-input').value,
            ip: document.getElementById('ip-input').value,
            observacion: document.getElementById('obs-input').value
        };
    }

    // Validar campos requeridos
    if (!serie) {
        mostrarAlerta('error', 'La serie es requerida');
        return;
    }

    const btn = document.getElementById('btnGuardar');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span>⏳</span> Guardando...';

    try {
        const response = await fetch(`/articulo/${articuloId}/actualizar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                serie: serie,
                estado: estado,
                idArea: idArea || null,
                nombreGrupo: nombreGrupo || null,
                tipoGrupo: tipoGrupo || null,
                ...datosRed
            })
        });

        const data = await response.json();

        if (data.success) {
            mostrarAlerta('success', '✅ Artículo actualizado correctamente');
            
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

// Función para mostrar alertas
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