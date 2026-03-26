// resources/js/licencia-detalle.js

// Variables para el modo edición
let modoEdicion = false;

function activarEdicion() {
    modoEdicion = true;
    
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
    document.getElementById('fecha-activacion-text').style.display = 'none';
    document.getElementById('fecha-activacion-input').style.display = 'block';
    document.getElementById('fecha-vencimiento-text').style.display = 'none';
    document.getElementById('fecha-vencimiento-input').style.display = 'block';
    
    document.getElementById('btnEditar').style.display = 'none';
    document.getElementById('btnGuardar').style.display = 'inline-flex';
    document.getElementById('btnCancelar').style.display = 'inline-flex';
}

function cancelarEdicion() {
    modoEdicion = false;
    
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
    document.getElementById('fecha-activacion-text').style.display = 'block';
    document.getElementById('fecha-activacion-input').style.display = 'none';
    document.getElementById('fecha-vencimiento-text').style.display = 'block';
    document.getElementById('fecha-vencimiento-input').style.display = 'none';
    
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

    if (!clave) { mostrarAlerta('error', 'La clave de licencia es requerida'); return; }
    if (!idSoftware) { mostrarAlerta('error', 'Debes seleccionar un software'); return; }
    if (!fechaActivacion) { mostrarAlerta('error', 'La fecha de activación es requerida'); return; }
    if (!fechaVencimiento) { mostrarAlerta('error', 'La fecha de vencimiento es requerida'); return; }

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
                clave, estado, idSoftware,
                capacidad: capacidad || null,
                descripcion: descripcion || null,
                fecha_activacion: fechaActivacion,
                fecha_vencimiento: fechaVencimiento
            })
        });

        const data = await response.json();

        if (data.success) {
            mostrarAlerta('success', '✅ Licencia actualizada correctamente');
            setTimeout(() => location.reload(), 1500);
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
    setTimeout(() => alerta.style.display = 'none', 3000);
}

// ===== FUNCIONES PARA ASIGNAR ARTÍCULO =====
let articuloSeleccionado = null;
let observacionActual = '';

function abrirModalArticulos() {
    // Verificar capacidad antes de abrir el modal
    const capacidadTexto = document.getElementById('capacidad-text')?.innerText || '';
    const capacidadMatch = capacidadTexto.match(/\d+/);
    const capacidad = capacidadMatch ? parseInt(capacidadMatch[0]) : 0;
    const totalTexto = document.querySelector('.info-value span:first-child')?.innerText || '0';
    const totalAsignados = parseInt(totalTexto) || 0;
    
    if (capacidad > 0 && totalAsignados >= capacidad) {
        mostrarAlerta('error', '⚠️ Capacidad máxima alcanzada. No se pueden agregar más artículos.');
        return;
    }
    
    document.getElementById('modalArticulos').style.display = 'flex';
    cargarArticulos();
}

function cerrarModalArticulos() {
    document.getElementById('modalArticulos').style.display = 'none';
}

function cerrarModalConfirmar() {
    document.getElementById('modalConfirmar').style.display = 'none';
    articuloSeleccionado = null;
    observacionActual = '';
}

function actualizarContadorObservacion() {
    const input = document.getElementById('observacionInput');
    const counter = document.getElementById('charCounter');
    if (input && counter) {
        const length = input.value.length;
        counter.textContent = `${length}/85`;
        if (length > 70) {
            counter.classList.add('warning');
        } else {
            counter.classList.remove('warning');
        }
    }
}

async function cargarArticulos() {
    const tbody = document.getElementById('tablaArticulos');
    tbody.innerHTML = ' <tr<td colspan="5" class="no-data">Cargando artículos...</td></tr>';
    
    try {
        const response = await fetch(`/articulos/lista?licencia_id=${licenciaId}`);
        const data = await response.json();
        renderArticulos(data);
    } catch (error) {
        console.error('Error:', error);
        tbody.innerHTML = '<tr><td colspan="5" class="no-data">Error al cargar artículos</td></tr>';
    }
}

function escapeHtml(text) {
    if (!text) return text;
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function renderArticulos(articulos) {
    const tbody = document.getElementById('tablaArticulos');
    tbody.innerHTML = '';
    
    if (articulos.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="no-data">No hay artículos disponibles</td></tr>';
        return;
    }
    
    articulos.forEach(art => {
        const tr = document.createElement('tr');
        
        if (art.ya_asignado) {
            tr.className = 'fila-asignada';
            tr.onclick = (e) => {
    e.stopPropagation();
    mostrarMensajeDuplicado(art);
};
        } else {
            tr.className = 'fila-disponible';
            tr.onclick = (e) => {
    e.stopPropagation(); // 🔥 ESTO ES CLAVE
    seleccionarArticulo(art);
};
        }
        
        tr.innerHTML = `
            <td><strong>${escapeHtml(art.serie) || 'N/A'}</strong> ${art.ya_asignado ? '<span style="color:#28a745;">✓</span>' : ''}</td>
            <td>${escapeHtml(art.RP) || 'N/A'}</td>
            <td>${escapeHtml(art.producto) || 'N/A'}</td>
            <td>${escapeHtml(art.marca) || 'N/A'}</td>
            <td style="text-align: center;">
                ${art.ya_asignado ? 
                    '<span class="badge-asignado">Ya asignada</span>' : 
                    '<span class="badge-disponible">Disponible</span>'}
            </td>
        `;
        tbody.appendChild(tr);
    });
}

function mostrarMensajeDuplicado(art) {
    mostrarAlerta('error', `⚠️ El artículo ${art.serie} (${art.RP}) ya tiene esta licencia asignada`);
}

async function buscarArticulos() {
    const query = document.getElementById('buscarArticulo').value.trim();
    const tbody = document.getElementById('tablaArticulos');
    
    tbody.innerHTML = '<tr><td colspan="5" class="no-data">Buscando...</td></tr>';
    
    try {
        const response = await fetch(`/articulos/lista?q=${encodeURIComponent(query)}&licencia_id=${licenciaId}`);
        const data = await response.json();
        renderArticulos(data);
    } catch (error) {
        console.error('Error:', error);
        tbody.innerHTML = '<tr><td colspan="5" class="no-data">Error al buscar</td></tr>';
    }
}

function seleccionarArticulo(art) {
    articuloSeleccionado = art;
    observacionActual = '';
    
    document.getElementById('infoArticulo').innerHTML = `
        <div style="background: #f8fafc; border-radius: 12px; padding: 15px; margin-bottom: 20px;">
            <p><strong>📦 Serie:</strong> ${escapeHtml(art.serie) || 'N/A'}</p>
            <p><strong>🔖 RP:</strong> ${escapeHtml(art.RP) || 'N/A'}</p>
            <p><strong>🏷️ Producto:</strong> ${escapeHtml(art.producto) || 'N/A'}</p>
            <p><strong>🏭 Marca:</strong> ${escapeHtml(art.marca) || 'N/A'}</p>
        </div>
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f3057;">📝 Observación (opcional, máx. 85 caracteres):</label>
            <textarea id="observacionInput" class="campo-observacion" rows="2" maxlength="85" placeholder="Ej: Licencia asignada para equipo de desarrollo..."></textarea>
            <div id="charCounter" class="char-counter">0/85</div>
        </div>
        <p style="color: #666; font-size: 14px;">¿Deseas asignar esta licencia al artículo?</p>
    `;
    
    const observacionInput = document.getElementById('observacionInput');
    if (observacionInput) {
        observacionInput.addEventListener('input', function() {
            const length = this.value.length;
            const counter = document.getElementById('charCounter');
            counter.textContent = `${length}/85`;
            if (length > 70) {
                counter.classList.add('warning');
            } else {
                counter.classList.remove('warning');
            }
            observacionActual = this.value;
        });
    }
    
    //cerrarModalArticulos();
    document.getElementById('modalConfirmar').style.display = 'flex';
}

async function confirmarAsignacion() {
    if (!articuloSeleccionado) {
        mostrarAlerta('error', 'No hay artículo seleccionado');
        return;
    }
    
    const observacion = document.getElementById('observacionInput')?.value || '';
    
    const btn = document.querySelector('#modalConfirmar .btn-save');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '⏳ Asignando...';
    
    try {
        const response = await fetch('/licencia/asignar-articulo', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                idLicencia: licenciaId,
                idArticulo: articuloSeleccionado.idArticulo,
                observacion: observacion
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            mostrarAlerta('success', '✅ Licencia asignada correctamente');
            cerrarModalConfirmar();
            setTimeout(() => location.reload(), 1000);
        } else {
            mostrarAlerta('error', data.message || 'Error al asignar');
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

// ===== FUNCIÓN PARA ELIMINAR ASIGNACIÓN =====
async function eliminarAsignacion(idAsignacion, serie, rp) {
    // Confirmar con el usuario
    const confirmar = confirm(`⚠️ ¿Estás seguro de eliminar la asignación?\n\nArtículo: ${serie} (${rp})\n\nEsta acción no se puede deshacer.`);
    
    if (!confirmar) return;
    
    // Buscar el botón y deshabilitarlo
    const buttons = document.querySelectorAll('.btn-delete');
    let btn = null;
    for (let b of buttons) {
        if (b.getAttribute('onclick')?.includes(`eliminarAsignacion(${idAsignacion}`)) {
            btn = b;
            break;
        }
    }
    
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '⏳';
    }
    
    try {
        const response = await fetch(`/licencia/asignacion/${idAsignacion}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            mostrarAlerta('success', '✅ Asignación eliminada correctamente');
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            mostrarAlerta('error', data.message || 'Error al eliminar');
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '🗑️';
            }
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarAlerta('error', 'Error de conexión al servidor');
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '🗑️';
        }
    }
}

// Cerrar modales con Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const alerta = document.getElementById('alerta');
        if (alerta && alerta.style.display === 'block') alerta.style.display = 'none';
        //cerrarModalArticulos();
        cerrarModalConfirmar();
    }
});