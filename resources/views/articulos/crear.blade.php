<x-app-layout>
    <style>
        .form-header {
            background: linear-gradient(135deg, #0f3057, #00587a);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin: 20px 20px 0 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .form-header h1 {
            font-size: 32px;
            margin-bottom: 5px;
        }

        .step-indicator {
            background: rgba(255,255,255,0.15);
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 14px;
            border: 1px solid rgba(255,255,255,0.3);
        }

        /* Contenedor principal */
        .form-container {
            margin: 20px auto;
            max-width: 1000px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            border: 1px solid #e6ecf2;
            overflow: hidden;
        }

        .steps-container {
            display: flex;
            padding: 0;
            background: #f8fafc;
            border-bottom: 2px solid #e6ecf2;
            flex-wrap: wrap;
        }

        .step {
            flex: 1;
            padding: 15px 8px;
            text-align: center;
            font-weight: 600;
            color: #666;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
            font-size: 12px;
            min-width: 90px;
        }

        .step.active {
            color: #0f3057;
            border-bottom-color: #00587a;
            background: white;
        }

        .step.completed {
            color: #28a745;
        }

        .step-number {
            display: inline-block;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #e6ecf2;
            color: #666;
            text-align: center;
            line-height: 22px;
            margin-right: 4px;
            font-size: 11px;
        }

        .step.active .step-number {
            background: #0f3057;
            color: white;
        }

        .step-content {
            padding: 30px;
        }

        .step-content.hidden {
            display: none;
        }

        .section-title {
            color: #0f3057;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #00587a;
        }

        .tipo-producto-section {
            background: #f8fafc;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .radio-group {
            display: flex;
            gap: 30px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .radio-option input[type="radio"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #0f3057;
        }

        .select-tipo {
            width: 100%;
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #ccd6dd;
            font-size: 14px;
            margin-top: 10px;
        }

        .input-nuevo-tipo {
            flex: 1;
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #ccd6dd;
            font-size: 14px;
        }

        .input-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .btn-add {
            background: #28a745;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-add:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40,167,69,0.3);
        }

        .btn-add:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-primary {
            background: #0f3057;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background: #00587a;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,88,122,0.3);
        }

        .btn-primary:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e6ecf2;
        }

        /* Grid para 2 columnas */
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        /* Estilos para artículos individuales */
        .articulo-card {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #00587a;
            position: relative;
        }

        .articulo-number {
            position: absolute;
            top: -10px;
            left: 20px;
            background: #0f3057;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .articulo-fields {
            margin-top: 15px;
        }

        /* Estilos para el resumen */
        .resumen-card {
            background: #f8fafc;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            border-left: 4px solid #00587a;
        }

        .resumen-titulo {
            color: #0f3057;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .resumen-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .resumen-item {
            padding: 10px;
            background: white;
            border-radius: 8px;
            border: 1px solid #e6ecf2;
        }

        .resumen-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 4px;
        }

        .resumen-value {
            font-weight: 600;
            color: #0f3057;
            font-size: 14px;
        }

        .resumen-value.nuevo {
            color: #28a745;
        }

        .resumen-value.nuevo::before {
            content: "✨ ";
            font-size: 12px;
        }

        .alert-message {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            z-index: 1100;
            display: none;
            animation: slideIn 0.3s;
        }

        .alert-success {
            background: #28a745;
        }

        .alert-error {
            background: #dc3545;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @media (max-width: 768px) {
            .form-header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            
            .radio-group {
                flex-direction: column;
                gap: 15px;
            }
            
            .input-group {
                flex-direction: column;
            }
            
            .btn-add {
                width: 100%;
                justify-content: center;
            }
            
            .resumen-grid,
            .grid-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div>
        <div class="form-header">
            <div>
                <h1>📦 Agregar Nuevo Artículo</h1>
                <p style="opacity: 0.9; margin-top: 5px;">Completa los pasos para registrar un nuevo artículo</p>
            </div>
            <div class="step-indicator">
                Paso <span id="paso-actual">1</span> de 8
            </div>
        </div>

        <div class="form-container">
            <!-- Indicador de pasos -->
            <div class="steps-container">
                <div class="step active" id="step-1">
                    <span class="step-number">1</span> Tipo Producto
                </div>
                <div class="step" id="step-2">
                    <span class="step-number">2</span> Producto
                </div>
                <div class="step" id="step-3">
                    <span class="step-number">3</span> Tipo Licitación
                </div>
                <div class="step" id="step-4">
                    <span class="step-number">4</span> Proveedor
                </div>
                <div class="step" id="step-5">
                    <span class="step-number">5</span> Licitación
                </div>
                <div class="step" id="step-6">
                    <span class="step-number">6</span> Detalles
                </div>
                <div class="step" id="step-7">
                    <span class="step-number">7</span> Artículos
                </div>
                <div class="step" id="step-8">
                    <span class="step-number">8</span> Resumen
                </div>
            </div>

            <!-- Contenido de los pasos -->
            <form id="form-articulo" method="POST" action="{{ route('articulos.store') }}">
                @csrf
                <input type="hidden" id="datos-completos" name="datos_completos" value="">

                <!-- PASO 1: Tipo de Producto -->
                <div class="step-content" id="content-1">
                    <h2 class="section-title">📋 Paso 1: Tipo de Producto</h2>
                    
                    <div class="tipo-producto-section">
                        <h3 style="color: #0f3057; margin-bottom: 15px;">¿Cómo deseas agregar el tipo de producto?</h3>
                        
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="tipo_producto_opcion" value="existente" checked onchange="toggleTipoProductoInput()">
                                <span>Seleccionar de la lista</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="tipo_producto_opcion" value="nuevo" onchange="toggleTipoProductoInput()">
                                <span>Agregar nuevo tipo</span>
                            </label>
                        </div>

                        <div id="tipo-existente">
                            <label for="tipo_producto_id">Selecciona el tipo de producto:</label>
                            <select name="tipo_producto_id" id="tipo_producto_id" class="select-tipo">
                                <option value="">-- Selecciona un tipo --</option>
                                @foreach($tiposProducto as $tipo)
                                    <option value="{{ $tipo->idTipo_Producto }}">{{ $tipo->NombreTP }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="tipo-nuevo" style="display: none;">
                            <label for="nuevo_tipo_producto">Nuevo tipo de producto:</label>
                            <div class="input-group">
                                <input type="text" 
                                       name="nuevo_tipo_producto" 
                                       id="nuevo_tipo_producto" 
                                       class="input-nuevo-tipo" 
                                       placeholder="Ej: Periférico, Accesorio, Consumible..."
                                       maxlength="45">
                                <button type="button" class="btn-add" onclick="agregarNuevoTipo()" id="btn-agregar-tipo">
                                    <span>➕</span> Agregar
                                </button>
                            </div>
                            <p style="font-size: 12px; color: #666; margin-top: 5px;">
                                El nuevo tipo se agregará inmediatamente a la base de datos
                            </p>
                            <div id="mensaje-exito" style="display: none; margin-top: 10px; padding: 10px; background: #d4edda; color: #155724; border-radius: 8px;">
                                ✅ Tipo de producto agregado correctamente
                            </div>
                        </div>
                    </div>

                    <div class="navigation-buttons">
                        <div></div>
                        <button type="button" class="btn-primary" onclick="siguientePaso(1)">Siguiente →</button>
                    </div>
                </div>

                <!-- PASO 2: Producto -->
                <div class="step-content hidden" id="content-2">
                    <h2 class="section-title">🏷️ Paso 2: Producto</h2>
                    
                    <div style="background: #eef2f6; padding: 15px; border-radius: 10px; margin-bottom: 25px;">
                        <p style="color: #0f3057; font-weight: 500;">
                            <span style="background: #0f3057; color: white; padding: 3px 10px; border-radius: 20px; margin-right: 10px;">Paso 1</span>
                            Tipo de producto seleccionado: 
                            <strong id="resumen-tipo-producto" style="color: #00587a;">(pendiente)</strong>
                        </p>
                    </div>

                    <div class="tipo-producto-section">
                        <div style="margin-bottom: 20px;">
                            <label for="nombre_producto">Nombre del producto <span style="color: #dc3545;">*</span></label>
                            <input type="text" 
                                   id="nombre_producto" 
                                   name="nombre_producto" 
                                   class="input-nuevo-tipo" 
                                   placeholder="Ej: Laptop, Monitor, Teclado..."
                                   maxlength="45"
                                   required>
                            <p style="font-size: 12px; color: #666; margin-top: 5px;">
                                Nombre comercial o descriptivo del producto
                            </p>
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label for="marca_producto">Marca</label>
                            <input type="text" 
                                   id="marca_producto" 
                                   name="marca_producto" 
                                   class="input-nuevo-tipo" 
                                   placeholder="Ej: HP, Dell, Cisco, Microsoft..."
                                   maxlength="45">
                            <p style="font-size: 12px; color: #666; margin-top: 5px;">
                                Marca del producto (opcional)
                            </p>
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label for="modelo_producto">Modelo</label>
                            <input type="text" 
                                   id="modelo_producto" 
                                   name="modelo_producto" 
                                   class="input-nuevo-tipo" 
                                   placeholder="Ej: EliteBook 840, ProLiant DL360, Latitude 5420..."
                                   maxlength="45">
                            <p style="font-size: 12px; color: #666; margin-top: 5px;">
                                Modelo específico del producto (opcional)
                            </p>
                        </div>
                    </div>

                    <div class="navigation-buttons">
                        <button type="button" class="btn-secondary" onclick="pasoAnterior(2)">← Anterior</button>
                        <button type="button" class="btn-primary" onclick="siguientePaso(2)">Siguiente →</button>
                    </div>
                </div>

                <!-- PASO 3: Tipo de Licitación -->
                <div class="step-content hidden" id="content-3">
                    <h2 class="section-title">📄 Paso 3: Tipo de Licitación</h2>
                    
                    <div style="background: #eef2f6; padding: 15px; border-radius: 10px; margin-bottom: 25px;">
                        <p style="color: #0f3057; font-weight: 500;">
                            <span style="background: #0f3057; color: white; padding: 3px 10px; border-radius: 20px; margin-right: 10px;">Paso 1</span>
                            Tipo: <span id="resumen-tipo-paso3" style="color: #00587a; font-weight: 600;">(pendiente)</span>
                        </p>
                        <p style="color: #0f3057; font-weight: 500; margin-top: 5px;">
                            <span style="background: #0f3057; color: white; padding: 3px 10px; border-radius: 20px; margin-right: 10px;">Paso 2</span>
                            Producto: <span id="resumen-producto-paso3" style="color: #00587a; font-weight: 600;">(pendiente)</span>
                        </p>
                    </div>

                    <div class="tipo-producto-section">
                        <h3 style="color: #0f3057; margin-bottom: 20px;">¿Qué tipo de licitación aplica?</h3>
                        
                        <div style="display: flex; flex-direction: column; gap: 20px;">
                            <!-- Opción Licitación Existente -->
                            <div id="opcion-licitacion-existente" style="border: 2px solid #e6ecf2; border-radius: 15px; padding: 20px;">
                                <label class="radio-option" style="margin-bottom: 15px;">
                                    <input type="radio" name="tipo_licitacion" value="existente" onchange="toggleLicitacionSelector()">
                                    <span style="font-weight: 600; color: #0f3057;">📋 Usar licitación existente</span>
                                </label>
                                <p style="font-size: 13px; color: #666; margin-bottom: 15px; margin-left: 30px;">
                                    El artículo pertenece a una licitación ya registrada en el sistema y requiere un administrador para modificarlo
                                </p>
                                
                                <!-- Selector de licitaciones existentes -->
                                <div id="selector-licitaciones" style="display: none; margin-top: 15px; margin-left: 30px;">
                                    <label for="licitacion_id">Selecciona la licitación:</label>
                                    <select name="licitacion_id" id="licitacion_id" class="select-tipo">
                                        <option value="">-- Selecciona una licitación --</option>
                                        @foreach($licitaciones as $licitacion)
                                            <option value="{{ $licitacion->idLicitacion }}">
                                                {{ $licitacion->folio }} - {{ $licitacion->proveedor_nombre ?? 'Sin proveedor' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p style="font-size: 12px; color: #666; margin-top: 5px;">
                                        Selecciona una licitación ya registrada
                                    </p>
                                </div>
                            </div>

                            <!-- Opción Licitación Nueva -->
                            <div style="border: 2px solid #e6ecf2; border-radius: 15px; padding: 20px;">
                                <label class="radio-option">
                                    <input type="radio" name="tipo_licitacion" value="nueva" onchange="toggleLicitacionSelector()">
                                    <span style="font-weight: 600; color: #0f3057;">➕ Crear nueva licitación</span>
                                </label>
                                <p style="font-size: 13px; color: #666; margin-top: 5px; margin-left: 30px;">
                                    Se creará una nueva licitación para este artículo (requiere datos adicionales)
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="navigation-buttons">
                        <button type="button" class="btn-secondary" onclick="pasoAnterior(3)">← Anterior</button>
                        <button type="button" class="btn-primary" onclick="siguientePaso(3)">Siguiente →</button>
                    </div>
                </div>

                <!-- PASO 4: Proveedor -->
                <div class="step-content hidden" id="content-4">
                    <h2 class="section-title">🏢 Paso 4: Proveedor</h2>
                    
                    <div style="background: #eef2f6; padding: 15px; border-radius: 10px; margin-bottom: 25px; display: flex; flex-wrap: wrap; gap: 15px;">
                        <div style="flex: 1; min-width: 200px;">
                            <span style="background: #0f3057; color: white; padding: 3px 10px; border-radius: 20px; font-size: 12px;">Paso 1</span>
                            <span style="margin-left: 8px; color: #0f3057; font-weight: 500;">Tipo:</span>
                            <span id="resumen-tipo-paso4" style="color: #00587a; font-weight: 600;">(pendiente)</span>
                        </div>
                        <div style="flex: 1; min-width: 200px;">
                            <span style="background: #0f3057; color: white; padding: 3px 10px; border-radius: 20px; font-size: 12px;">Paso 2</span>
                            <span style="margin-left: 8px; color: #0f3057; font-weight: 500;">Producto:</span>
                            <span id="resumen-producto-paso4" style="color: #00587a; font-weight: 600;">(pendiente)</span>
                        </div>
                    </div>

                    <div class="tipo-producto-section">
                        <h3 style="color: #0f3057; margin-bottom: 15px;">¿Cómo deseas agregar el proveedor?</h3>
                        
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="proveedor_opcion" value="existente" checked onchange="toggleProveedorInput()">
                                <span>Seleccionar de la lista</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="proveedor_opcion" value="nuevo" onchange="toggleProveedorInput()">
                                <span>Agregar nuevo proveedor</span>
                            </label>
                        </div>

                        <div id="proveedor-existente">
                            <label for="proveedor_id">Selecciona el proveedor:</label>
                            <select name="proveedor_id" id="proveedor_id" class="select-tipo">
                                <option value="">-- Selecciona un proveedor --</option>
                                @foreach($proveedores as $proveedor)
                                    <option value="{{ $proveedor->idProveedor }}">{{ $proveedor->Nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="proveedor-nuevo" style="display: none;">
                            <div style="margin-bottom: 15px;">
                                <label for="nuevo_proveedor_nombre">Nombre del proveedor <span style="color: #dc3545;">*</span></label>
                                <input type="text" 
                                       name="nuevo_proveedor_nombre" 
                                       id="nuevo_proveedor_nombre" 
                                       class="input-nuevo-tipo" 
                                       placeholder="Ej: Tecnología SA, Suministros LP..."
                                       maxlength="45">
                            </div>
                            
                            <div style="margin-bottom: 15px;">
                                <label for="nuevo_proveedor_rfc">RFC</label>
                                <input type="text" 
                                       name="nuevo_proveedor_rfc" 
                                       id="nuevo_proveedor_rfc" 
                                       class="input-nuevo-tipo" 
                                       placeholder="RFC (opcional)"
                                       maxlength="20">
                            </div>
                            
                            <div style="margin-bottom: 15px;">
                                <label for="nuevo_proveedor_telefono">Teléfono</label>
                                <input type="text" 
                                       name="nuevo_proveedor_telefono" 
                                       id="nuevo_proveedor_telefono" 
                                       class="input-nuevo-tipo" 
                                       placeholder="Teléfono (opcional)"
                                       maxlength="20">
                            </div>
                            
                            <div style="margin-bottom: 15px;">
                                <label for="nuevo_proveedor_direccion">Dirección</label>
                                <input type="text" 
                                       name="nuevo_proveedor_direccion" 
                                       id="nuevo_proveedor_direccion" 
                                       class="input-nuevo-tipo" 
                                       placeholder="Dirección (opcional)"
                                       maxlength="900">
                            </div>
                            
                            <div style="margin-bottom: 15px;">
                                <label for="nuevo_proveedor_correo">Correo electrónico</label>
                                <input type="email" 
                                       name="nuevo_proveedor_correo" 
                                       id="nuevo_proveedor_correo" 
                                       class="input-nuevo-tipo" 
                                       placeholder="correo@ejemplo.com (opcional)"
                                       maxlength="50">
                            </div>
                            
                            <div class="input-group" style="margin-top: 20px;">
                                <button type="button" class="btn-add" onclick="agregarNuevoProveedor()" id="btn-agregar-proveedor">
                                    <span>➕</span> Agregar Proveedor
                                </button>
                            </div>
                            <p style="font-size: 12px; color: #666; margin-top: 5px;">
                                El nuevo proveedor se agregará inmediatamente a la base de datos
                            </p>
                            <div id="mensaje-exito-proveedor" style="display: none; margin-top: 10px; padding: 10px; background: #d4edda; color: #155724; border-radius: 8px;">
                                ✅ Proveedor agregado correctamente
                            </div>
                        </div>
                    </div>

                    <div class="navigation-buttons">
                        <button type="button" class="btn-secondary" onclick="pasoAnterior(4)">← Anterior</button>
                        <button type="button" class="btn-primary" onclick="siguientePaso(4)">Siguiente →</button>
                    </div>
                </div>

                <!-- PASO 5: Licitación (SOLO para licitación nueva) -->
                <div class="step-content hidden" id="content-5">
                    <h2 class="section-title">📄 Paso 5: Nueva Licitación</h2>
                    
                    <!-- Resumen de pasos anteriores -->
                    <div style="background: #eef2f6; padding: 15px; border-radius: 10px; margin-bottom: 25px;">
                        <div style="display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 10px;">
                            <div style="flex: 1; min-width: 200px;">
                                <span style="background: #0f3057; color: white; padding: 3px 10px; border-radius: 20px; font-size: 12px;">Paso 1</span>
                                <span style="margin-left: 8px; color: #0f3057; font-weight: 500;">Tipo:</span>
                                <span id="resumen-tipo-paso5" style="color: #00587a; font-weight: 600;">(pendiente)</span>
                            </div>
                            <div style="flex: 1; min-width: 200px;">
                                <span style="background: #0f3057; color: white; padding: 3px 10px; border-radius: 20px; font-size: 12px;">Paso 2</span>
                                <span style="margin-left: 8px; color: #0f3057; font-weight: 500;">Producto:</span>
                                <span id="resumen-producto-paso5" style="color: #00587a; font-weight: 600;">(pendiente)</span>
                            </div>
                        </div>
                        <div>
                            <span style="background: #0f3057; color: white; padding: 3px 10px; border-radius: 20px; font-size: 12px;">Paso 4</span>
                            <span style="margin-left: 8px; color: #0f3057; font-weight: 500;">Proveedor:</span>
                            <span id="resumen-proveedor-paso5" style="color: #00587a; font-weight: 600;">(pendiente)</span>
                        </div>
                    </div>

                    <div class="tipo-producto-section">
                        <div style="margin-bottom: 20px;">
                            <label for="folio_licitacion">Folio de la licitación <span style="color: #dc3545;">*</span></label>
                            <input type="text" 
                                   id="folio_licitacion" 
                                   name="folio_licitacion" 
                                   class="input-nuevo-tipo" 
                                   maxlength="50"
                                   >
                            <p style="font-size: 12px; color: #666; margin-top: 5px;">
                                Folio único que identifica la licitación
                            </p>
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label for="descripcion_licitacion">Descripción</label>
                            <textarea id="descripcion_licitacion" 
                                      name="descripcion_licitacion" 
                                      class="input-nuevo-tipo" 
                                      placeholder="Descripción de la licitación (opcional)"
                                      maxlength="255"
                                      rows="3"
                                      style="resize: vertical; min-height: 80px;"></textarea>
                        </div>

                        <div class="grid-2" style="margin-bottom: 20px;">
                            <div>
                                <label for="fecha_inicio_licitacion">Fecha de inicio</label>
                                <input type="date" 
                                       id="fecha_inicio_licitacion" 
                                       name="fecha_inicio_licitacion" 
                                       class="input-nuevo-tipo">
                            </div>
                            <div>
                                <label for="fecha_fin_licitacion">Fecha de fin</label>
                                <input type="date" 
                                       id="fecha_fin_licitacion" 
                                       name="fecha_fin_licitacion" 
                                       class="input-nuevo-tipo">
                            </div>
                        </div>

                        <div class="grid-2" style="margin-bottom: 20px;">
                            <div>
                                <label for="estado_licitacion">Estado</label>
                                <select id="estado_licitacion" name="estado_licitacion" class="select-tipo">
                                    <option value="">-- Selecciona un estado --</option>
                                    <option value="Abierta">📂 Abierta</option>
                                    <option value="En proceso">⚙️ En proceso</option>
                                    <option value="Adjudicada">✅ Adjudicada</option>
                                    <option value="Cerrada">🔒 Cerrada</option>
                                    <option value="Cancelada">❌ Cancelada</option>
                                </select>
                            </div>
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label for="recurso_licitacion">Recurso</label>
                            <input type="text" 
                                   id="recurso_licitacion" 
                                   name="recurso_licitacion" 
                                   class="input-nuevo-tipo" 
                                   placeholder="Ej: Federal, Estatal, Propio..."
                                   maxlength="45">
                        </div>

                        <div style="background: #fff3cd; padding: 12px; border-radius: 8px; margin-top: 15px;">
                            <p style="color: #856404; font-size: 13px; margin: 0;">
                                <span style="font-weight: 600;">ℹ️ Nota:</span> 
                                Solo el folio es obligatorio. El total se calculará automáticamente con los detalles de compra.
                            </p>
                        </div>
                    </div>

                    <div class="navigation-buttons">
                        <button type="button" class="btn-secondary" onclick="pasoAnterior(5)">← Anterior</button>
                        <button type="button" class="btn-primary" onclick="siguientePaso(5)">Siguiente →</button>
                    </div>
                </div>

                <!-- PASO 6: Detalles de la Licitación (CANTIDAD Y PRECIO) -->
                <div class="step-content hidden" id="content-6">
                    <h2 class="section-title">📋 Paso 6: Detalles de la Licitación</h2>
                    
                    <!-- Resumen de pasos anteriores -->
                    <div style="background: #eef2f6; padding: 15px; border-radius: 10px; margin-bottom: 25px;">
                        <div style="display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 10px;">
                            <div style="flex: 1; min-width: 200px;">
                                <span style="background: #0f3057; color: white; padding: 3px 10px; border-radius: 20px; font-size: 12px;">Paso 1</span>
                                <span style="margin-left: 8px; color: #0f3057; font-weight: 500;">Tipo:</span>
                                <span id="resumen-tipo-paso6" style="color: #00587a; font-weight: 600;">(pendiente)</span>
                            </div>
                            <div style="flex: 1; min-width: 200px;">
                                <span style="background: #0f3057; color: white; padding: 3px 10px; border-radius: 20px; font-size: 12px;">Paso 2</span>
                                <span style="margin-left: 8px; color: #0f3057; font-weight: 500;">Producto:</span>
                                <span id="resumen-producto-paso6" style="color: #00587a; font-weight: 600;">(pendiente)</span>
                            </div>
                        </div>
                        <div style="display: flex; flex-wrap: wrap; gap: 15px;">
                            <div style="flex: 1; min-width: 200px;">
                                <span style="background: #0f3057; color: white; padding: 3px 10px; border-radius: 20px; font-size: 12px;">Paso 3</span>
                                <span style="margin-left: 8px; color: #0f3057; font-weight: 500;">Licitación:</span>
                                <span id="resumen-licitacion-paso6" style="color: #00587a; font-weight: 600;">(pendiente)</span>
                            </div>
                        </div>
                    </div>

                    <div class="tipo-producto-section">
                        <h3 style="color: #0f3057; margin-bottom: 20px;">Detalles de compra</h3>
                        
                        <div style="background: #f0f4f8; padding: 25px; border-radius: 12px; margin-bottom: 20px;">
                            <div style="margin-bottom: 20px;">
                                <label for="cantidad">Cantidad de unidades <span style="color: #dc3545;">*</span></label>
                                <input type="number" 
                                       id="cantidad" 
                                       name="cantidad" 
                                       class="input-nuevo-tipo" 
                                       placeholder="Ej: 5, 10, 15..."
                                       min="1"
                                       value="1"
                                       required
                                       onchange="calcularSubtotal()">
                                <p style="font-size: 12px; color: #666; margin-top: 5px;">
                                    Número de artículos idénticos que se compraron
                                </p>
                            </div>

                            <div style="margin-bottom: 20px;">
                                <label for="precio_unitario">Precio unitario <span style="color: #dc3545;">*</span></label>
                                <input type="number" 
                                       id="precio_unitario" 
                                       name="precio_unitario" 
                                       class="input-nuevo-tipo" 
                                       placeholder="0.00"
                                       step="0.01"
                                       min="0"
                                       required
                                       onchange="calcularSubtotal()">
                                <p style="font-size: 12px; color: #666; margin-top: 5px;">
                                    Precio por unidad (sin IVA si aplica)
                                </p>
                            </div>

                            <div style="background: white; padding: 20px; border-radius: 10px; border: 2px solid #00587a;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span style="font-weight: 600; color: #0f3057;">Subtotal:</span>
                                    <span id="subtotal" style="font-size: 24px; font-weight: bold; color: #00587a;">$0.00</span>
                                </div>
                                <p style="font-size: 12px; color: #666; margin-top: 5px; text-align: right;">
                                    Cantidad × Precio unitario
                                </p>
                            </div>
                        </div>

                        <div style="background: #fff3cd; padding: 12px; border-radius: 8px;">
                            <p style="color: #856404; font-size: 13px; margin: 0;">
                                <span style="font-weight: 600;">📝 Nota:</span> 
                                En el siguiente paso podrás ingresar los datos individuales de cada artículo (serie, RP, etc.)
                            </p>
                        </div>
                    </div>

                    <div class="navigation-buttons">
                        <button type="button" class="btn-secondary" onclick="pasoAnterior(6)">← Anterior</button>
                        <button type="button" class="btn-primary" onclick="siguientePaso(6)">Siguiente →</button>
                    </div>
                </div>

                <!-- PASO 7: Datos individuales de los Artículos -->
                <div class="step-content hidden" id="content-7">
                    <h2 class="section-title">📦 Paso 7: Datos de los Artículos</h2>
                    
                    <!-- Resumen de lo anterior -->
                    <div style="background: #eef2f6; padding: 15px; border-radius: 10px; margin-bottom: 25px;">
                        <p style="color: #0f3057; font-weight: 500;">
                            <span style="background: #28a745; color: white; padding: 3px 10px; border-radius: 20px; margin-right: 10px;">Paso 6</span>
                            <strong id="resumen-cantidad-paso7">0</strong> unidades de 
                            <strong id="resumen-producto-paso7">(producto)</strong> a 
                            <strong id="resumen-precio-paso7">$0.00</strong> c/u
                        </p>
                    </div>

                    <div id="articulos-container">
                        <!-- Los artículos se generarán dinámicamente con JavaScript -->
                    </div>

                    <div class="navigation-buttons">
                        <button type="button" class="btn-secondary" onclick="pasoAnterior(7)">← Anterior</button>
                        <button type="button" class="btn-primary" onclick="siguientePaso(7)">Siguiente →</button>
                    </div>
                </div>

                <!-- PASO 8: RESUMEN -->
                <div class="step-content hidden" id="content-8">
                    <h2 class="section-title">✅ Paso 8: Resumen</h2>
                    
                    <div class="resumen-card">
                        <div class="resumen-titulo">
                            <span>📋</span> Datos a guardar
                        </div>
                        <div class="resumen-grid" id="resumen-datos">
                            <!-- Se llena con JavaScript -->
                        </div>
                    </div>

                    <div class="resumen-card" style="margin-top: 20px;">
                        <div class="resumen-titulo">
                            <span>📦</span> Artículos a registrar
                        </div>
                        <div id="resumen-articulos" style="max-height: 200px; overflow-y: auto;">
                            <!-- Se llena con JavaScript -->
                        </div>
                    </div>

                    <div style="background: #eef2f6; padding: 20px; border-radius: 12px; margin-top: 20px;">
                        <p style="color: #0f3057; margin-bottom: 10px; font-weight: 600;">
                            ⚠️ Importante
                        </p>
                        <p style="color: #666; font-size: 14px;">
                            Revisa que todos los datos sean correctos antes de guardar. 
                            Una vez guardado, se crearán todos los artículos en el sistema.
                        </p>
                    </div>

                    <div class="navigation-buttons">
                        <button type="button" class="btn-secondary" onclick="pasoAnterior(8)">← Anterior</button>
                        <button type="submit" class="btn-primary" id="btn-guardar">💾 Guardar Artículos</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ALERTA FLOTANTE -->
    <div id="alerta" class="alert-message"></div>

    <script>
        let pasoActual = 1;
        // Objeto para almacenar todos los datos del formulario
        const datosFormulario = {
            tipo_producto: null,
            tipo_producto_nuevo: false,
            producto: null,
            tipo_licitacion: null,
            proveedor: null,
            licitacion: null,
            detalle: {
                cantidad: 0,
                precio_unitario: 0,
                subtotal: 0
            },
            articulos: [] // Array para almacenar los datos de cada artículo
        };

        function toggleTipoProductoInput() {
            const opcion = document.querySelector('input[name="tipo_producto_opcion"]:checked').value;
            const divExistente = document.getElementById('tipo-existente');
            const divNuevo = document.getElementById('tipo-nuevo');
            
            if (opcion === 'existente') {
                divExistente.style.display = 'block';
                divNuevo.style.display = 'none';
                document.getElementById('nuevo_tipo_producto').value = '';
                datosFormulario.tipo_producto_nuevo = false;
            } else {
                divExistente.style.display = 'none';
                divNuevo.style.display = 'block';
                document.getElementById('tipo_producto_id').value = '';
                datosFormulario.tipo_producto_nuevo = true;
            }
        }

        function toggleLicitacionSelector() {
            const tipo = document.querySelector('input[name="tipo_licitacion"]:checked')?.value;
            const selector = document.getElementById('selector-licitaciones');
            
            if (tipo === 'existente') {
                selector.style.display = 'block';
            } else {
                selector.style.display = 'none';
            }
        }

        function calcularSubtotal() {
            const cantidad = parseFloat(document.getElementById('cantidad').value) || 0;
            const precio = parseFloat(document.getElementById('precio_unitario').value) || 0;
            const subtotal = cantidad * precio;
            document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
        }

        function generarArticulos() {
            const cantidad = datosFormulario.detalle.cantidad;
            const container = document.getElementById('articulos-container');
            let html = '';

            for (let i = 1; i <= cantidad; i++) {
                html += `
                    <div class="articulo-card">
                        <div class="articulo-number">Artículo #${i}</div>
                        <div class="articulo-fields">
                            <div style="margin-bottom: 15px;">
                                <label for="serie_${i}">Número de serie</label>
                                <input type="text" 
                                       id="serie_${i}" 
                                       name="articulos[${i}][serie]" 
                                       class="input-nuevo-tipo" 
                                       placeholder="Ej: SN-${String(i).padStart(3, '0')}"
                                       maxlength="45"
                                       required>
                                <p style="font-size: 11px; color: #666; margin-top: 3px;">
                                    Serie única del artículo
                                </p>
                            </div>

                            <div style="margin-bottom: 15px;">
                                <label for="rp_${i}">Registro Patrimonial (Número de Control)</label>
                                <input type="text" 
                                       id="rp_${i}" 
                                       name="articulos[${i}][rp]" 
                                       class="input-nuevo-tipo" 
                                       placeholder="Ej: RP-2024-${String(i).padStart(3, '0')}"
                                       maxlength="45"
                                       required>
                                <p style="font-size: 11px; color: #666; margin-top: 3px;">
                                    Número de inventario interno
                                </p>
                            </div>

                            <div>
                                <label for="estado_${i}">Estado inicial</label>
                                <select id="estado_${i}" name="articulos[${i}][estado]" class="select-tipo">
                                    <option value="Activo">✅ Activo</option>
                                    <option value="Mantenimiento">🔧 Mantenimiento</option>
                                    <option value="Almacén">📦 Almacén</option>
                                    <option value="Inactivo">❌ Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                `;
            }

            container.innerHTML = html;
        }

        function guardarArticulos() {
            const cantidad = datosFormulario.detalle.cantidad;
            datosFormulario.articulos = [];

            for (let i = 1; i <= cantidad; i++) {
                const serie = document.getElementById(`serie_${i}`).value.trim();
                const rp = document.getElementById(`rp_${i}`).value.trim();
                const estado = document.getElementById(`estado_${i}`).value;

                if (!serie || !rp) {
                    mostrarAlerta('error', `El artículo #${i} debe tener serie y RP`);
                    return false;
                }

                datosFormulario.articulos.push({
                    serie: serie,
                    rp: rp,
                    estado: estado
                });
            }
            return true;
        }

        async function agregarNuevoTipo() {
            const input = document.getElementById('nuevo_tipo_producto');
            const nombre = input.value.trim();
            const btn = document.getElementById('btn-agregar-tipo');
            
            if (!nombre) {
                mostrarAlerta('error', 'Por favor ingresa un nombre para el tipo de producto');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<span>⏳</span> Agregando...';

            try {
                const response = await fetch('/api/tipos-producto', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ nombre: nombre })
                });

                const data = await response.json();

                if (data.success) {
                    document.getElementById('mensaje-exito').style.display = 'block';
                    setTimeout(() => {
                        document.getElementById('mensaje-exito').style.display = 'none';
                    }, 3000);

                    input.value = '';

                    const select = document.getElementById('tipo_producto_id');
                    const option = document.createElement('option');
                    option.value = data.id;
                    option.text = nombre;
                    option.selected = true;
                    select.appendChild(option);

                    document.querySelector('input[value="existente"]').checked = true;
                    toggleTipoProductoInput();

                    datosFormulario.tipo_producto = {
                        id: data.id,
                        nombre: nombre,
                        nuevo: true
                    };
                    
                    actualizarTipoProductoEnPaso2();
                    actualizarResumenesPaso3();
                    actualizarResumenesPaso4();
                    actualizarResumenesPaso5();
                    actualizarResumenesPaso6();

                    mostrarAlerta('success', 'Tipo de producto agregado correctamente');
                } else {
                    mostrarAlerta('error', data.message || 'Error al agregar el tipo de producto');
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarAlerta('error', 'Error de conexión al servidor');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<span>➕</span> Agregar';
            }
        }

        function actualizarTipoProductoEnPaso2() {
            const elemento = document.getElementById('resumen-tipo-producto');
            if (datosFormulario.tipo_producto) {
                elemento.textContent = datosFormulario.tipo_producto.nombre;
                elemento.style.color = '#00587a';
            } else {
                elemento.textContent = '(pendiente)';
            }
        }

        function guardarDatosProducto() {
            const nombre = document.getElementById('nombre_producto').value.trim();
            const marca = document.getElementById('marca_producto').value.trim();
            const modelo = document.getElementById('modelo_producto').value.trim();
            
            datosFormulario.producto = {
                nombre: nombre,
                marca: marca || null,
                modelo: modelo || null
            };
        }

        function guardarDatosLicitacion() {
            if (datosFormulario.tipo_licitacion === 'existente') {
                const select = document.getElementById('licitacion_id');
                const selectedOption = select.options[select.selectedIndex];
                const [folio] = selectedOption.text.split(' - ');
                
                datosFormulario.licitacion = {
                    id: select.value,
                    folio: folio,
                    existente: true
                };
            } else {
                datosFormulario.licitacion = {
                    folio: document.getElementById('folio_licitacion').value.trim(),
                    descripcion: document.getElementById('descripcion_licitacion').value.trim() || null,
                    fecha_inicio: document.getElementById('fecha_inicio_licitacion').value || null,
                    fecha_fin: document.getElementById('fecha_fin_licitacion').value || null,
                    estado: document.getElementById('estado_licitacion').value || null,
                    recurso: document.getElementById('recurso_licitacion').value.trim() || null,
                    nuevo: true
                };
            }
        }

        function mostrarPaso(paso) {
            for (let i = 1; i <= 8; i++) {
                document.getElementById(`content-${i}`).classList.add('hidden');
                document.getElementById(`step-${i}`).classList.remove('active');
            }
            
            document.getElementById(`content-${paso}`).classList.remove('hidden');
            document.getElementById(`step-${paso}`).classList.add('active');
            document.getElementById('paso-actual').textContent = paso;
            pasoActual = paso;

            if (paso === 2) actualizarTipoProductoEnPaso2();
            if (paso === 3) actualizarResumenesPaso3();
            if (paso === 4) actualizarResumenesPaso4();
            if (paso === 5) actualizarResumenesPaso5();
            if (paso === 6) actualizarResumenesPaso6();
            if (paso === 7) prepararPaso7();
            if (paso === 8) actualizarResumen();
        }

        function actualizarResumenesPaso3() {
            if (datosFormulario.tipo_producto) {
                document.getElementById('resumen-tipo-paso3').textContent = datosFormulario.tipo_producto.nombre;
            }
            if (datosFormulario.producto) {
                document.getElementById('resumen-producto-paso3').textContent = datosFormulario.producto.nombre;
            }
        }

        function actualizarResumenesPaso4() {
            if (datosFormulario.tipo_producto) {
                document.getElementById('resumen-tipo-paso4').textContent = datosFormulario.tipo_producto.nombre;
            }
            if (datosFormulario.producto) {
                document.getElementById('resumen-producto-paso4').textContent = datosFormulario.producto.nombre;
            }
        }

        function actualizarResumenesPaso5() {
            if (datosFormulario.tipo_producto) {
                document.getElementById('resumen-tipo-paso5').textContent = datosFormulario.tipo_producto.nombre;
            }
            if (datosFormulario.producto) {
                document.getElementById('resumen-producto-paso5').textContent = datosFormulario.producto.nombre;
            }
            if (datosFormulario.proveedor) {
                document.getElementById('resumen-proveedor-paso5').textContent = datosFormulario.proveedor.nombre;
            }
        }

        function actualizarResumenesPaso6() {
            if (datosFormulario.tipo_producto) {
                document.getElementById('resumen-tipo-paso6').textContent = datosFormulario.tipo_producto.nombre;
            }
            if (datosFormulario.producto) {
                document.getElementById('resumen-producto-paso6').textContent = datosFormulario.producto.nombre;
            }
            if (datosFormulario.licitacion) {
                document.getElementById('resumen-licitacion-paso6').textContent = datosFormulario.licitacion.folio;
            }
        }

        function prepararPaso7() {
            document.getElementById('resumen-cantidad-paso7').textContent = datosFormulario.detalle.cantidad;
            document.getElementById('resumen-producto-paso7').textContent = datosFormulario.producto.nombre;
            document.getElementById('resumen-precio-paso7').textContent = `$${datosFormulario.detalle.precio_unitario.toFixed(2)}`;
            generarArticulos();
        }

        function actualizarResumen() {
            const resumenDiv = document.getElementById('resumen-datos');
            const resumenArts = document.getElementById('resumen-articulos');
            let html = '';
            let artsHtml = '';

            if (datosFormulario.tipo_producto) {
                html += `<div class="resumen-item"><div class="resumen-label">Tipo</div><div class="resumen-value">${datosFormulario.tipo_producto.nombre}</div></div>`;
            }

            if (datosFormulario.producto) {
                html += `<div class="resumen-item"><div class="resumen-label">Producto</div><div class="resumen-value">${datosFormulario.producto.nombre}</div></div>`;
                if (datosFormulario.producto.marca) {
                    html += `<div class="resumen-item"><div class="resumen-label">Marca</div><div class="resumen-value">${datosFormulario.producto.marca}</div></div>`;
                }
            }

            if (datosFormulario.proveedor) {
                html += `<div class="resumen-item"><div class="resumen-label">Proveedor</div><div class="resumen-value">${datosFormulario.proveedor.nombre}</div></div>`;
            }

            if (datosFormulario.licitacion) {
                html += `<div class="resumen-item"><div class="resumen-label">Licitación</div><div class="resumen-value">${datosFormulario.licitacion.folio}</div></div>`;
            }

            html += `<div class="resumen-item"><div class="resumen-label">Cantidad</div><div class="resumen-value">${datosFormulario.detalle.cantidad} unidades</div></div>`;
            html += `<div class="resumen-item"><div class="resumen-label">Precio unitario</div><div class="resumen-value">$${datosFormulario.detalle.precio_unitario.toFixed(2)}</div></div>`;
            html += `<div class="resumen-item"><div class="resumen-label">Subtotal</div><div class="resumen-value" style="color: #28a745;">$${datosFormulario.detalle.subtotal.toFixed(2)}</div></div>`;

            datosFormulario.articulos.forEach((art, index) => {
                artsHtml += `
                    <div style="background: #f8fafc; padding: 8px; margin-bottom: 5px; border-radius: 6px; border-left: 3px solid #00587a;">
                        <strong>Artículo #${index + 1}:</strong> ${art.serie} | RP: ${art.rp} | Estado: ${art.estado}
                    </div>
                `;
            });

            resumenDiv.innerHTML = html;
            resumenArts.innerHTML = artsHtml || '<p style="color: #999;">No hay artículos registrados</p>';
        }

        function siguientePaso(paso) {
            if (paso === 1) {
                const opcion = document.querySelector('input[name="tipo_producto_opcion"]:checked').value;
                
                if (opcion === 'existente') {
                    const tipoId = document.getElementById('tipo_producto_id').value;
                    if (!tipoId) {
                        mostrarAlerta('error', 'Por favor selecciona un tipo de producto');
                        return;
                    }
                    const select = document.getElementById('tipo_producto_id');
                    const selectedOption = select.options[select.selectedIndex];
                    datosFormulario.tipo_producto = {
                        id: tipoId,
                        nombre: selectedOption.text,
                        nuevo: false
                    };
                } else {
                    const nuevoTipo = document.getElementById('nuevo_tipo_producto').value.trim();
                    if (nuevoTipo) {
                        mostrarAlerta('error', 'Por favor agrega el nuevo tipo usando el botón "Agregar"');
                        return;
                    }
                    if (!datosFormulario.tipo_producto) {
                        mostrarAlerta('error', 'Por favor agrega un nuevo tipo de producto');
                        return;
                    }
                }
            }
            
            if (paso === 2) {
                const nombre = document.getElementById('nombre_producto').value.trim();
                if (!nombre) {
                    mostrarAlerta('error', 'El nombre del producto es requerido');
                    return;
                }
                guardarDatosProducto();
            }
            
            if (paso === 3) {
                const tipo = document.querySelector('input[name="tipo_licitacion"]:checked');
                if (!tipo) {
                    mostrarAlerta('error', 'Por favor selecciona el tipo de licitación');
                    return;
                }
                
                datosFormulario.tipo_licitacion = tipo.value;
                
                if (tipo.value === 'existente') {
                    const licitacionId = document.getElementById('licitacion_id').value;
                    if (!licitacionId) {
                        mostrarAlerta('error', 'Por favor selecciona una licitación');
                        return;
                    }
                    
                    const select = document.getElementById('licitacion_id');
                    const selectedOption = select.options[select.selectedIndex];
                    const [folio] = selectedOption.text.split(' - ');
                    
                    datosFormulario.licitacion = {
                        id: select.value,
                        folio: folio,
                        existente: true
                    };
                    
                    mostrarPaso(6);
                    return;
                }
            }
            
            if (paso === 4) {
                const opcion = document.querySelector('input[name="proveedor_opcion"]:checked').value;
                
                if (opcion === 'existente') {
                    const proveedorId = document.getElementById('proveedor_id').value;
                    if (!proveedorId) {
                        mostrarAlerta('error', 'Por favor selecciona un proveedor');
                        return;
                    }
                    const select = document.getElementById('proveedor_id');
                    const selectedOption = select.options[select.selectedIndex];
                    datosFormulario.proveedor = {
                        id: proveedorId,
                        nombre: selectedOption.text,
                        nuevo: false
                    };
                } else {
                    const nuevoProveedor = document.getElementById('nuevo_proveedor_nombre').value.trim();
                    if (nuevoProveedor) {
                        mostrarAlerta('error', 'Por favor agrega el nuevo proveedor usando el botón "Agregar Proveedor"');
                        return;
                    }
                    if (!datosFormulario.proveedor) {
                        mostrarAlerta('error', 'Por favor agrega un nuevo proveedor');
                        return;
                    }
                }
            }
            
            if (paso === 5) {
                const folio = document.getElementById('folio_licitacion').value.trim();
                if (!folio) {
                    mostrarAlerta('error', 'El folio de la licitación es requerido');
                    return;
                }
                guardarDatosLicitacion();
            }

            if (paso === 6) {
                const cantidad = parseInt(document.getElementById('cantidad').value);
                const precio = parseFloat(document.getElementById('precio_unitario').value);
                
                if (!cantidad || cantidad < 1) {
                    mostrarAlerta('error', 'La cantidad debe ser al menos 1');
                    return;
                }
                
                if (!precio || precio < 0) {
                    mostrarAlerta('error', 'El precio unitario es requerido');
                    return;
                }

                datosFormulario.detalle = {
                    cantidad: cantidad,
                    precio_unitario: precio,
                    subtotal: cantidad * precio
                };
            }

            if (paso === 7) {
                if (!guardarArticulos()) {
                    return;
                }
            }
            
            mostrarPaso(paso + 1);
        }

        function pasoAnterior(paso) {
            if (datosFormulario.tipo_licitacion === 'existente') {
                if (paso === 4 || paso === 5 || paso === 6 || paso === 7) {
                    mostrarPaso(3);
                    return;
                }
            }
            mostrarPaso(paso - 1);
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

        document.getElementById('form-articulo').addEventListener('submit', function(e) {
            if (pasoActual < 8) {
                e.preventDefault();
                mostrarAlerta('error', 'Por favor revisa el resumen antes de guardar');
                mostrarPaso(8);
                return;
            }
            document.getElementById('datos-completos').value = JSON.stringify(datosFormulario);
        });

        // Variables para proveedor
        function toggleProveedorInput() {
            const opcion = document.querySelector('input[name="proveedor_opcion"]:checked').value;
            const divExistente = document.getElementById('proveedor-existente');
            const divNuevo = document.getElementById('proveedor-nuevo');
            
            if (opcion === 'existente') {
                divExistente.style.display = 'block';
                divNuevo.style.display = 'none';
                document.getElementById('nuevo_proveedor_nombre').value = '';
                document.getElementById('nuevo_proveedor_rfc').value = '';
                document.getElementById('nuevo_proveedor_telefono').value = '';
                document.getElementById('nuevo_proveedor_direccion').value = '';
                document.getElementById('nuevo_proveedor_correo').value = '';
            } else {
                divExistente.style.display = 'none';
                divNuevo.style.display = 'block';
                document.getElementById('proveedor_id').value = '';
            }
        }

        async function agregarNuevoProveedor() {
            const nombre = document.getElementById('nuevo_proveedor_nombre').value.trim();
            const btn = document.getElementById('btn-agregar-proveedor');
            
            if (!nombre) {
                mostrarAlerta('error', 'El nombre del proveedor es requerido');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<span>⏳</span> Agregando...';

            try {
                const response = await fetch('/api/proveedores', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        nombre: nombre,
                        rfc: document.getElementById('nuevo_proveedor_rfc').value.trim() || null,
                        telefono: document.getElementById('nuevo_proveedor_telefono').value.trim() || null,
                        direccion: document.getElementById('nuevo_proveedor_direccion').value.trim() || null,
                        correo: document.getElementById('nuevo_proveedor_correo').value.trim() || null
                    })
                });

                const data = await response.json();

                if (data.success) {
                    document.getElementById('mensaje-exito-proveedor').style.display = 'block';
                    setTimeout(() => {
                        document.getElementById('mensaje-exito-proveedor').style.display = 'none';
                    }, 3000);

                    document.getElementById('nuevo_proveedor_nombre').value = '';
                    document.getElementById('nuevo_proveedor_rfc').value = '';
                    document.getElementById('nuevo_proveedor_telefono').value = '';
                    document.getElementById('nuevo_proveedor_direccion').value = '';
                    document.getElementById('nuevo_proveedor_correo').value = '';

                    const select = document.getElementById('proveedor_id');
                    const option = document.createElement('option');
                    option.value = data.id;
                    option.text = nombre;
                    option.selected = true;
                    select.appendChild(option);

                    document.querySelector('input[name="proveedor_opcion"][value="existente"]').checked = true;
                    toggleProveedorInput();

                    datosFormulario.proveedor = {
                        id: data.id,
                        nombre: nombre,
                        nuevo: true
                    };

                    mostrarAlerta('success', 'Proveedor agregado correctamente');
                } else {
                    mostrarAlerta('error', data.message || 'Error al agregar el proveedor');
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarAlerta('error', 'Error de conexión al servidor');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<span>➕</span> Agregar Proveedor';
            }
        }
    </script>
</x-app-layout>