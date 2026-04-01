<x-app-layout>
    <style>
        .welcome-header {
            background: linear-gradient(135deg, #4a6fa5, #6b8cae);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin: 20px 20px 0 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .welcome-header h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }

        .badge-admin {
            background: #ffd700;
            color: #0f3057;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 12px;
            display: inline-block;
        }

        .proveedores-container {
            margin: 20px auto;
            max-width: 95%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border: 1px solid #e6ecf2;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: calc(100vh - 250px);
        }

        .container-header {
            background: linear-gradient(135deg, #4a6fa5, #6b8cae);
            color: white;
            padding: 8px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .filters-wrapper {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
            flex: 1;
        }

        .search-wrapper {
            flex: 0 0 200px;
        }

        .search-container {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50px;
            padding: 2px 2px 2px 12px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .search-icon {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            margin-right: 6px;
        }

        .search-input {
            flex: 1;
            border: none;
            background: transparent;
            padding: 4px 0;
            color: white;
            font-size: 12px;
            outline: none;
        }

        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
            font-style: italic;
        }

        .filter-select {
            background: white;
            color: #0f3057;
            border: 1px solid #ffd700;
            border-radius: 50px;
            padding: 6px 12px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            min-width: 130px;
        }

        .date-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 12px;
            white-space: nowrap;
        }

        .container-content {
            padding: 20px;
            overflow-y: auto;
            height: 100%;
            background: #fafcfc;
        }

        .section-title {
            color: #0f3057;
            margin: 0 0 15px 0;
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title span {
            background: #e6ecf2;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 12px;
            color: #4a6fa5;
        }

        .table-container {
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid #e6ecf2;
            margin-top: 15px;
        }

        .proveedores-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .proveedores-table th {
            background: #4a6fa5;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
        }

        .proveedores-table td {
            padding: 12px;
            border-bottom: 1px solid #e6ecf2;
            color: #333;
            font-size: 13px;
        }

        .proveedores-table tr:hover td {
            background: #f8fafc;
        }

        .info-btn {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #4a6fa5;
            color: white;
            border: none;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .info-btn:hover {
            background: #ffd700;
            color: #4a6fa5;
            transform: scale(1.1);
        }

        .nombre-container {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .no-data {
            text-align: center;
            padding: 50px 20px;
            color: #666;
            font-size: 15px;
        }

        @media (max-width: 768px) {
            .welcome-header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .container-header {
                flex-direction: column;
            }

            .filters-wrapper {
                width: 100%;
            }

            .search-wrapper {
                max-width: 100%;
                width: 100%;
            }

            .proveedores-table th,
            .proveedores-table td {
                padding: 8px;
                font-size: 12px;
            }
        }
    </style>

    <div>
        <div class="welcome-header">
            <div>
                <h1>Proveedores</h1>
                <p style="opacity: 0.9; margin: 5px 0;">Gestión de proveedores del sistema</p>
            </div>

            <div class="date-badge" style="background: rgba(255,255,255,0.2); padding: 8px 20px; border-radius: 50px;">
                {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>

        <div class="proveedores-container">
            <div class="container-header">
                <div class="filters-wrapper">
                    <div class="search-wrapper">
                        <div class="search-container">
                            
                            <input type="text" id="buscar-proveedor" class="search-input"
                                placeholder="Buscar por nombre, RFC...">
                        </div>
                    </div>

                </div>

                <div class="date-badge">
                    Total: {{ count($proveedores) }}
                </div>
            </div>

            <div class="container-content">
                <div class="section-title">
                    📋 Listado de Proveedores
                    <span>registros</span>
                </div>

                <div class="table-container">
                    <table class="proveedores-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>RFC</th>
                                <th>Teléfono</th>
                                <th>Correo</th>
                                <th>Licitaciones</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="tabla-proveedores">
                            @forelse($proveedores as $prov)
                                <tr>
                                    <td>{{ $prov->idProveedor }}</td>

                                    <td>
                                        <div class="nombre-container">
                                            <strong>{{ $prov->Nombre }}</strong>
                                        </div>
                                    </td>

                                    <td>{{ $prov->RFC ?? '-' }}</td>
                                    <td>{{ $prov->Telefono ?? '-' }}</td>
                                    <td>{{ $prov->correo ?? '-' }}</td>
                                    <td>{{ $prov->total_licitaciones ?? 0 }}</td>

                                    <td>
                                        <button onclick="verDetalle({{ $prov->idProveedor }})" class="info-btn"
                                            title="Ver detalles">
                                            !
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="no-data">
                                        📭 No hay proveedores registrados
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="alerta" class="alert-message"></div>

    <script>
        function verDetalle(id) {
            window.open(`/proveedores/${id}`, '_blank');
        }

        const inputBusqueda = document.getElementById('buscar-proveedor');
        const btnLimpiar = document.getElementById('btn-limpiar');
        const tablaBody = document.getElementById('tabla-proveedores');

        function aplicarFiltros() {
            const term = inputBusqueda?.value.toLowerCase() || '';

            const filas = tablaBody.querySelectorAll('tr');
            let visibles = 0;

            filas.forEach(fila => {
                if (fila.querySelector('.no-data')) return;

                const nombre = fila.cells[1]?.innerText.toLowerCase() || '';
                const rfc = fila.cells[2]?.innerText.toLowerCase() || '';

                let coincide = true;

                if (term && !nombre.includes(term) && !rfc.includes(term)) {
                    coincide = false;
                }

                fila.style.display = coincide ? '' : 'none';
                if (coincide) visibles++;
            });

            const totalSpan = document.querySelector('.date-badge:last-child');
            if (totalSpan) {
                totalSpan.innerHTML = `Mostrando: ${visibles}`;
            }
        }

        inputBusqueda?.addEventListener('input', aplicarFiltros);

        btnLimpiar?.addEventListener('click', function () {
            if (inputBusqueda) inputBusqueda.value = '';
            aplicarFiltros();
        });

        function mostrarAlerta(tipo, mensaje) {
            const alerta = document.getElementById('alerta');
            alerta.className = `alert-message alert-${tipo}`;
            alerta.textContent = mensaje;
            alerta.style.display = 'block';
            setTimeout(() => alerta.style.display = 'none', 3000);
        }
    </script>
</x-app-layout>