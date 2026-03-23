// public/js/dashboard-user.js

// Función para toggle del menú Agregar
function toggleAddMenu() {
    const menu = document.getElementById('addMenu');
    menu.classList.toggle('show');
}

// Cerrar menú al hacer click fuera
document.addEventListener('click', function(event) {
    const menu = document.getElementById('addMenu');
    const button = document.querySelector('.add-button');
    
    if (button && !button.contains(event.target) && !menu.contains(event.target)) {
        menu.classList.remove('show');
    }
});

// ===== FUNCIONES PARA MODAL DE ÁREA =====
function abrirModalArea() {
    document.getElementById('modal-area').style.display = 'flex';
    document.getElementById('nombre_area').focus();
}

function cerrarModalArea() {
    document.getElementById('modal-area').style.display = 'none';
    document.getElementById('nombre_area').value = '';
}

// Cerrar modal haciendo clic fuera
window.onclick = function(event) {
    const modal = document.getElementById('modal-area');
    if (event.target === modal) {
        cerrarModalArea();
    }
}

// Guardar nueva área
async function guardarArea(event) {
    event.preventDefault();
    
    const nombre = document.getElementById('nombre_area').value.trim();
    const btn = event.target.querySelector('button[type="submit"]');
    
    if (!nombre) {
        mostrarAlerta('error', '❌ El nombre del área es requerido');
        return;
    }

    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span>⏳</span> Guardando...';

    try {
        const response = await fetch('/api/areas', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ nombre: nombre })
        });

        const data = await response.json();

        if (data.success) {
            mostrarAlerta('success', '✅ Área agregada correctamente');
            cerrarModalArea();
            
            const selectAreas = document.getElementById('filtro-area');
            if (selectAreas) {
                const option = document.createElement('option');
                option.value = data.id;
                option.text = nombre;
                selectAreas.appendChild(option);
            }
        } else {
            mostrarAlerta('error', data.message || '❌ Error al agregar el área');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarAlerta('error', '❌ Error de conexión al servidor');
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
}

// Función para mostrar alertas
function mostrarAlerta(tipo, mensaje) {
    const alerta = document.getElementById('alerta');
    if (!alerta) return;
    
    alerta.className = `alert-message alert-${tipo}`;
    alerta.textContent = mensaje;
    alerta.style.display = 'block';
    
    setTimeout(() => {
        alerta.style.display = 'none';
    }, 3000);
}

// Variables para búsqueda y filtros
let timeoutId;
const searchInput = document.querySelector('.search-input');
const tableBody = document.getElementById('tabla-body');
const tablaTitulo = document.getElementById('tabla-titulo');
const filtroTipo = document.getElementById('filtro-tipo');
const filtroArea = document.getElementById('filtro-area');
const soloLicencias = document.getElementById('solo-licencias');

// Datos iniciales del controlador
const articulosIniciales = window.articulosIniciales || [];
const licenciasIniciales = window.licenciasIniciales || [];

// Mostrar datos al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    mostrarDatosIniciales();
});

function mostrarDatosIniciales() {
    let html = '';
    let totalItems = 0;
    
    if (articulosIniciales.length > 0) {
        html += `
            <tr style="background: #e6ecf2;">
                <td colspan="7" style="padding: 8px 12px; font-weight: bold; color: #0f3057;">
                    📦 ARTÍCULOS RECIENTES (${articulosIniciales.length})
                  </td
              </tr>
        `;
        
        articulosIniciales.forEach(item => {
            let estadoClass = 'estado-inactivo';
            if (item.estado === 'Activo') estadoClass = 'estado-activo';
            if (item.estado === 'Mantenimiento') estadoClass = 'estado-mantenimiento';
            
            html += `
                <tr>
                    <td><strong>${item.serie || 'N/A'}</strong></td>
                    <td><span class="estado-badge ${estadoClass}">${item.estado || 'N/A'}</span></td>
                    <td>
                        <div class="rp-container">
                            ${item.RP || 'N/A'}
                            <button onclick="window.open('/articulo/${item.RP}', '_blank')" class="info-btn" title="Ver detalles">!</button>
                        </div>
                    </td>
                    <td>${item.producto || 'N/A'}</td>
                    <td>${item.marca || 'N/A'}</td>
                    <td>${item.tipo_producto || 'N/A'}</td>
                    <td>${item.area || 'Sin área'}</td>
                </tr>
            `;
        });
        totalItems += articulosIniciales.length;
    }
    
    if (licenciasIniciales.length > 0) {
        html += `
            <tr style="background: #e6ecf2;">
                <td colspan="7" style="padding: 8px 12px; font-weight: bold; color: #0f3057;">
                    🔑 LICENCIAS RECIENTES (${licenciasIniciales.length})
                  </td
              </tr>
        `;
        
        licenciasIniciales.forEach(item => {
            let estadoClass = 'estado-inactivo';
            if (item.licencia_estado === 'Activa') estadoClass = 'estado-activo';
            
            let vencimiento = item.licencia_vencimiento ? new Date(item.licencia_vencimiento) : null;
            let fechaStr = vencimiento ? vencimiento.toLocaleDateString() : 'N/A';
            const totalEquipos = item.total_articulos_asignados || 0;
            
            html += `
                <tr style="background: #f8fafc;">
                    <td>
                        <div style="display: flex; align-items: center; gap: 5px;">
                            <strong>🔑 Licencia</strong>
                            <button onclick="window.open('/licencia/${item.licencia_clave}', '_blank')" class="info-btn" style="width: 22px; height: 22px;" title="Ver detalles">!</button>
                        </div>
                    </td>
                    <td><span class="estado-badge ${estadoClass}">${item.licencia_estado || 'N/A'}</span></td>
                    <td colspan="2">
                        <div style="display: flex; flex-direction: column;">
                            <span style="font-weight: 600; color: #0f3057;">${item.software_nombre || 'Software'}</span>
                            <span style="font-size: 11px; color: #666;">${item.licencia_clave || 'N/A'}</span>
                        </div>
                    </td>
                    <td colspan="2"><span style="font-size: 12px;">Vence: ${fechaStr}</span></td>
                    <td>
                        ${totalEquipos > 0 ? 
                            `<span class="contador-equipos">📊 ${totalEquipos} equipo${totalEquipos !== 1 ? 's' : ''} asignado${totalEquipos !== 1 ? 's' : ''}</span>` : 
                            '<span style="color: #999;">Sin asignar</span>'}
                    </td>
                </tr>
            `;
        });
        totalItems += licenciasIniciales.length;
    }
    
    if (totalItems === 0) {
        html = `
            <tr>
                <td colspan="7" class="no-data">
                    📭 No hay artículos ni licencias registrados
                </td>
            </tr>
        `;
    }
    
    tableBody.innerHTML = html;
    tablaTitulo.innerHTML = `📋 Artículos y Licencias <span>${totalItems} recientes</span>`;
}

// Event listeners para búsqueda y filtros
if (searchInput) {
    searchInput.addEventListener('input', function(e) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(aplicarFiltros, 300);
    });
}

[filtroTipo, filtroArea, soloLicencias].forEach(filter => {
    if (filter) {
        filter.addEventListener('change', aplicarFiltros);
    }
});

function aplicarFiltros() {
    const term = searchInput ? searchInput.value : '';
    const tipo = filtroTipo ? filtroTipo.value : '';
    const area = filtroArea ? filtroArea.value : '';
    const soloLic = soloLicencias ? soloLicencias.checked : false;
    
    let url = `/buscar-articulos?q=${encodeURIComponent(term)}`;
    if (tipo) url += `&tipo=${tipo}`;
    if (area) url += `&area=${area}`;
    if (soloLic) url += `&solo_licencias=1`;
    
    if (tableBody) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px;">
                    <div style="display: inline-block; width: 40px; height: 40px; border: 3px solid #f3f3f3; border-top: 3px solid #4a6fa5; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                    <p style="margin-top: 10px; color: #666;">Aplicando filtros...</p>
                </td>
            </tr>
        `;
    }
    
    fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => updateTable(data))
    .catch(error => {
        console.error('Error:', error);
        if (tableBody) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: #dc3545;">
                        ❌ Error al aplicar filtros
                    </td>
                </tr>
            `;
        }
    });
}

function updateTable(resultados) {
    if (!tableBody) return;
    
    if (resultados.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="7" class="no-data">
                    📭 No se encontraron resultados
                </td>
            </tr>
        `;
        if (tablaTitulo) tablaTitulo.innerHTML = `📋 Resultados <span>0</span>`;
        return;
    }
    
    let html = '';
    let totalItems = resultados.length;
    
    const articulos = resultados.filter(r => r.tipo === 'articulo');
    const licencias = resultados.filter(r => r.tipo === 'licencia');
    
    if (articulos.length > 0) {
        html += `
            <tr style="background: #e6ecf2;">
                <td colspan="7" style="padding: 8px 12px; font-weight: bold; color: #0f3057;">
                    📦 ARTÍCULOS (${articulos.length})
                </td>
            </tr>
        `;
        
        articulos.forEach(item => {
            let estadoClass = 'estado-inactivo';
            if (item.estado === 'Activo') estadoClass = 'estado-activo';
            if (item.estado === 'Mantenimiento') estadoClass = 'estado-mantenimiento';
            
            html += `
                <tr>
                    <td><strong>${item.serie || 'N/A'}</strong></td>
                    <td><span class="estado-badge ${estadoClass}">${item.estado || 'N/A'}</span></td>
                    <td>
                        <div class="rp-container">
                            ${item.RP || 'N/A'}
                            <button onclick="window.open('/articulo/${item.RP}', '_blank')" class="info-btn" title="Ver detalles">!</button>
                        </div>
                    </td>
                    <td>${item.producto || 'N/A'}</td>
                    <td>${item.marca || 'N/A'}</td>
                    <td>${item.tipo_producto || 'N/A'}</td>
                    <td>${item.area || 'Sin área'}</td>
                </tr>
            `;
        });
    }
    
    if (licencias.length > 0) {
        html += `
            <tr style="background: #e6ecf2;">
                <td colspan="7" style="padding: 8px 12px; font-weight: bold; color: #0f3057;">
                    🔑 LICENCIAS (${licencias.length})
                </td>
            </tr>
        `;
        
        licencias.forEach(item => {
            let estadoClass = 'estado-inactivo';
            if (item.licencia_estado === 'Activa') estadoClass = 'estado-activo';
            
            let vencimiento = item.licencia_vencimiento ? new Date(item.licencia_vencimiento) : null;
            let fechaStr = vencimiento ? vencimiento.toLocaleDateString() : 'N/A';
            const totalEquipos = item.total_articulos_asignados || 0;
            
            html += `
                <tr style="background: #f8fafc;">
                    <td>
                        <div style="display: flex; align-items: center; gap: 5px;">
                            <strong>🔑 Licencia</strong>
                            <button onclick="window.open('/licencia/${item.licencia_clave}', '_blank')" class="info-btn" style="width: 22px; height: 22px;" title="Ver detalles">!</button>
                        </div>
                    </td>
                    <td><span class="estado-badge ${estadoClass}">${item.licencia_estado || 'N/A'}</span></td>
                    <td colspan="2">
                        <div style="display: flex; flex-direction: column;">
                            <span style="font-weight: 600; color: #0f3057;">${item.software_nombre || 'Software'}</span>
                            <span style="font-size: 11px; color: #666;">${item.licencia_clave || 'N/A'}</span>
                        </div>
                    </td>
                    <td colspan="2"><span style="font-size: 12px;">Vence: ${fechaStr}</span></td>
                    <td>
                        ${totalEquipos > 0 ? 
                            `<span class="contador-equipos">📊 ${totalEquipos} equipo${totalEquipos !== 1 ? 's' : ''} asignado${totalEquipos !== 1 ? 's' : ''}</span>` : 
                            '<span style="color: #999;">Sin asignar</span>'}
                    </td>
                </tr>
            `;
        });
    }
    
    tableBody.innerHTML = html;
    if (tablaTitulo) tablaTitulo.innerHTML = `📋 Resultados de búsqueda <span>${totalItems}</span>`;
}

// Cerrar alerta con Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const alerta = document.getElementById('alerta');
        if (alerta && alerta.style.display === 'block') {
            alerta.style.display = 'none';
        }
    }
});