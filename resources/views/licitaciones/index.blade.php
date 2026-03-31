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

        .licitaciones-container {
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
            flex: 0 0 250px;
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
            padding: 6px 0;
            color: white;
            font-size: 13px;
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

        .licitaciones-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .licitaciones-table th {
            background: #4a6fa5;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
        }

        .licitaciones-table td {
            padding: 12px;
            border-bottom: 1px solid #e6ecf2;
            color: #333;
            font-size: 13px;
        }

        .licitaciones-table tr:hover td {
            background: #f8fafc;
        }

        .estado-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .estado-abierta {
            background: #d4edda;
            color: #155724;
        }

        .estado-proceso {
            background: #fff3cd;
            color: #856404;
        }

        .estado-adjudicada {
            background: #cce5ff;
            color: #004085;
        }

        .estado-cerrada {
            background: #e9ecef;
            color: #495057;
        }

        .estado-cancelada {
            background: #f8d7da;
            color: #721c24;
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

        .folio-container {
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

        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
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

            .licitaciones-table th,
            .licitaciones-table td {
                padding: 8px;
                font-size: 12px;
            }
        }
    </style>

    <div>
        <div class="welcome-header">
            <div>
                <h1>Licitaciones</h1>
                <p style="opacity: 0.9; margin: 5px 0;">Gestión de licitaciones del sistema</p>
            </div>

            <div class="date-badge" style="background: rgba(255,255,255,0.2); padding: 8px 20px; border-radius: 50px;">
                {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>

        <div class="licitaciones-container">
            <div class="container-header">
                <div class="filters-wrapper">
                    <div class="search-wrapper">
                        <div class="search-container">
                            <span class="search-icon">🔍</span>
                            <input type="text" id="buscar-licitacion" class="search-input"
                                placeholder="Buscar por folio, proveedor...">
                        </div>
                    </div>

                    <select id="filtro-estado" class="filter-select">
                        <option value="">📋 Todos los estados</option>
                        <option value="Abierta">📂 Abierta</option>
                        <option value="En proceso">⚙️ En proceso</option>
                        <option value="Adjudicada">✅ Adjudicada</option>
                        <option value="Cerrada">🔒 Cerrada</option>
                        <option value="Cancelada">❌ Cancelada</option>
                    </select>

                </div>

                <div class="date-badge">
                    Total: {{ count($licitaciones) }}
                </div>
            </div>

            <div class="container-content">
                <div class="section-title">
                    📋 Listado de Licitaciones
                </div>

                <div class="table-container">
                    <table class="licitaciones-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Folio</th>
                                <th>Proveedor</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Estado</th>
                                <th>Total</th>
                                <th>Detalles</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="tabla-licitaciones">
                            @forelse($licitaciones as $lic)
                                <tr>
                                    <td>{{ $lic->idLicitacion }}</td>

                                    <td>
                                        <div class="folio-container">
                                            <strong>{{ $lic->folio }}</strong>
                                        </div>
                                    </td>

                                    <td>{{ $lic->proveedor_nombre ?? 'Sin proveedor' }}</td>

                                    <td>
                                        {{ $lic->fecha_inicio ? date('d/m/Y', strtotime($lic->fecha_inicio)) : '-' }}
                                    </td>

                                    <td>
                                        {{ $lic->fecha_fin ? date('d/m/Y', strtotime($lic->fecha_fin)) : '-' }}
                                    </td>

                                    <td>
                                        @php
                                            $estadoClass = match ($lic->estado) {
                                                'Abierta' => 'estado-abierta',
                                                'En proceso' => 'estado-proceso',
                                                'Adjudicada' => 'estado-adjudicada',
                                                'Cerrada' => 'estado-cerrada',
                                                'Cancelada' => 'estado-cancelada',
                                                default => ''
                                            };
                                        @endphp
                                        <span class="estado-badge {{ $estadoClass }}">
                                            {{ $lic->estado ?? '-' }}
                                        </span>
                                    </td>

                                    <td class="total-cell">
                                        ${{ number_format($lic->Total ?? 0, 2) }}
                                    </td>

                                    <td class="detalles-cell">
                                        {{ $lic->total_detalles ?? 0 }} item(s)
                                    </td>

                                    <td>
                                        <button onclick="verDetalle({{ $lic->idLicitacion }})" class="info-btn"
                                            title="Ver detalles">🔍</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="no-data">
                                        📭 No hay licitaciones registradas
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
            window.open(`/licitaciones/${id}`, '_blank');
        }

        // Filtros
        const inputBusqueda = document.getElementById('buscar-licitacion');
        const filtroEstado = document.getElementById('filtro-estado');
        const btnLimpiar = document.getElementById('btn-limpiar');
        const tablaBody = document.getElementById('tabla-licitaciones');

        function aplicarFiltros() {
            const term = inputBusqueda?.value.toLowerCase() || '';
            const estado = filtroEstado?.value || '';

            const filas = tablaBody.querySelectorAll('tr');
            let visibles = 0;

            filas.forEach(fila => {
                if (fila.querySelector('.no-data')) return;

                const folio = fila.cells[1]?.innerText.toLowerCase() || '';
                const proveedor = fila.cells[2]?.innerText.toLowerCase() || '';
                const estadoFila = fila.cells[5]?.innerText || '';

                let coincide = true;

                if (term && !folio.includes(term) && !proveedor.includes(term)) {
                    coincide = false;
                }

                if (estado && estadoFila !== estado) {
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

        // 🔥 Búsqueda instantánea mientras escribes
        inputBusqueda?.addEventListener('input', function() {
            aplicarFiltros();
        });

        // Filtro por estado
        filtroEstado?.addEventListener('change', aplicarFiltros);

        // Botón limpiar
        btnLimpiar?.addEventListener('click', function () {
            if (inputBusqueda) inputBusqueda.value = '';
            if (filtroEstado) filtroEstado.value = '';
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