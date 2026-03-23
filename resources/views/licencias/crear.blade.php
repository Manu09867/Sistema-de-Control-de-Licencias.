<x-app-layout>
    <style>
        .form-header {
            background: linear-gradient(135deg, #4a6fa5, #6b8cae);
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
            color: #4a6fa5;
            border-bottom-color: #6b8cae;
            background: white;
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
            background: #4a6fa5;
            color: white;
        }

        .step-content {
            padding: 30px;
        }

        .step-content.hidden {
            display: none;
        }

        .section-title {
            color: #4a6fa5;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #6b8cae;
        }

        .tipo-software-section {
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
            accent-color: #4a6fa5;
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

        .btn-primary {
            background: #4a6fa5;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background: #6b8cae;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(74,111,165,0.3);
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

        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e6ecf2;
        }

        /* Estilos para el resumen */
        .resumen-card {
            background: #f8fafc;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            border-left: 4px solid #6b8cae;
        }

        .resumen-titulo {
            color: #4a6fa5;
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
            color: #4a6fa5;
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
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .alert-success {
            background: #28a745;
            border-left: 5px solid #1e7e34;
        }

        .alert-error {
            background: #dc3545;
            border-left: 5px solid #bd2130;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
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
                <h1>🔑 Agregar Nueva Licencia</h1>
                <p style="opacity: 0.9; margin-top: 5px;">Completa los pasos para registrar una nueva licencia</p>
            </div>
            <div class="step-indicator">
                Paso <span id="paso-actual">1</span> de 7
            </div>
        </div>

        <div class="form-container">
            <!-- Indicador de pasos -->
            <div class="steps-container">
                <div class="step active" id="step-1">
                    <span class="step-number">1</span> Software
                </div>
                <div class="step" id="step-2">
                    <span class="step-number">2</span> Tipo Licitación
                </div>
                <div class="step" id="step-3">
                    <span class="step-number">3</span> Proveedor
                </div>
                <div class="step" id="step-4">
                    <span class="step-number">4</span> Licitación
                </div>
                <div class="step" id="step-5">
                    <span class="step-number">5</span> Detalles
                </div>
                <div class="step" id="step-6">
                    <span class="step-number">6</span> Licencia
                </div>
                <div class="step" id="step-7">
                    <span class="step-number">7</span> Resumen
                </div>
            </div>

            <form id="form-licencia" method="POST" action="{{ route('licencias.store') }}">
                @csrf
                <input type="hidden" id="datos-completos" name="datos_completos" value="">

                <!-- PASO 1: Tipo de Software -->
                <div class="step-content" id="content-1">
                    <h2 class="section-title">📦 Paso 1: Software</h2>
                    
                    <div class="tipo-software-section">
                        <h3 style="color: #4a6fa5; margin-bottom: 15px;">¿Cómo deseas agregar el software?</h3>
                        
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="software_opcion" value="existente" checked onchange="toggleSoftwareInput()">
                                <span>Seleccionar de la lista</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="software_opcion" value="nuevo" onchange="toggleSoftwareInput()">
                                <span>Agregar nuevo software</span>
                            </label>
                        </div>

                        <div id="software-existente">
                            <label for="software_id">Selecciona el software:</label>
                            <select name="software_id" id="software_id" class="select-tipo">
                                <option value="">-- Selecciona un software --</option>
                                @foreach($softwares as $software)
                                    <option value="{{ $software->idSoftware }}">{{ $software->Nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="software-nuevo" style="display: none;">
                            <label for="nuevo_software_nombre">Nuevo software:</label>
                            <div class="input-group">
                                <input type="text" 
                                       name="nuevo_software_nombre" 
                                       id="nuevo_software_nombre" 
                                       class="input-nuevo-tipo" 
                                       placeholder="Ej: Windows 11 Pro, Office 2024, Adobe Photoshop..."
                                       maxlength="45">
                                <button type="button" class="btn-add" onclick="agregarNuevoSoftware()" id="btn-agregar-software">
                                    <span>➕</span> Agregar
                                </button>
                            </div>
                            <p style="font-size: 12px; color: #666; margin-top: 5px;">
                                El nuevo software se agregará inmediatamente a la base de datos
                            </p>
                            <div id="mensaje-exito-software" style="display: none; margin-top: 10px; padding: 10px; background: #d4edda; color: #155724; border-radius: 8px;">
                                ✅ Software agregado correctamente
                            </div>
                        </div>
                    </div>

                    <div class="navigation-buttons">
                        <div></div>
                        <button type="button" class="btn-primary" onclick="siguientePaso(1)">Siguiente →</button>
                    </div>
                </div>

                <!-- PASO 2: Tipo de Licitación -->
                <div class="step-content hidden" id="content-2">
                    <h2 class="section-title">📄 Paso 2: Tipo de Licitación</h2>
                    
                    <div style="background: #eef2f6; padding: 15px; border-radius: 10px; margin-bottom: 25px;">
                        <div style="display: flex; flex-wrap: wrap; gap: 15px;">
                            <div style="flex: 1; min-width: 200px;">
                                <span style="background: #4a6fa5; color: white; padding: 3px 10px; border-radius: 20px; font-size: 12px;">Paso 1</span>
                                <span style="margin-left: 8px; color: #4a6fa5; font-weight: 500;">Software:</span>
                                <span id="resumen-software-paso2" style="color: #00587a; font-weight: 600;">(pendiente)</span>
                            </div>
                        </div>
                    </div>

                    <div class="tipo-software-section">
                        <h3 style="color: #4a6fa5; margin-bottom: 20px;">¿Qué tipo de licitación aplica?</h3>
                        
                        <div style="display: flex; flex-direction: column; gap: 20px;">
                            <!-- Opción Licitación Existente -->
                            <div id="opcion-licitacion-existente" style="border: 2px solid #e6ecf2; border-radius: 15px; padding: 20px;">
                                <label class="radio-option" style="margin-bottom: 15px;">
                                    <input type="radio" name="tipo_licitacion" value="existente" onchange="toggleLicitacionSelector()">
                                    <span style="font-weight: 600; color: #4a6fa5;">📋 Usar licitación existente</span>
                                </label>
                                <p style="font-size: 13px; color: #666; margin-bottom: 15px; margin-left: 30px;">
                                    La licencia pertenece a una licitación ya registrada en el sistema
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
                                    <span style="font-weight: 600; color: #4a6fa5;">➕ Crear nueva licitación</span>
                                </label>
                                <p style="font-size: 13px; color: #666; margin-top: 5px; margin-left: 30px;">
                                    Se creará una nueva licitación para esta licencia (requiere datos adicionales)
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="navigation-buttons">
                        <button type="button" class="btn-secondary" onclick="pasoAnterior(2)">← Anterior</button>
                        <button type="button" class="btn-primary" onclick="siguientePaso(2)">Siguiente →</button>
                    </div>
                </div>

                <!-- PASO 3: Proveedor (SOLO para licitación nueva) -->
                <div class="step-content hidden" id="content-3">
                    <h2 class="section-title">🏢 Paso 3: Proveedor</h2>
                    
                    <div style="background: #eef2f6; padding: 15px; border-radius: 10px; margin-bottom: 25px;">
                        <div style="display: flex; flex-wrap: wrap; gap: 15px;">
                            <div style="flex: 1; min-width: 200px;">
                                <span style="background: #4a6fa5; color: white; padding: 3px 10px; border-radius: 20px; font-size: 12px;">Paso 1</span>
                                <span style="margin-left: 8px; color: #4a6fa5; font-weight: 500;">Software:</span>
                                <span id="resumen-software-paso3" style="color: #00587a; font-weight: 600;">(pendiente)</span>
                            </div>
                        </div>
                    </div>

                    <div class="tipo-software-section">
                        <h3 style="color: #4a6fa5; margin-bottom: 15px;">¿Cómo deseas agregar el proveedor?</h3>
                        
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
                        <button type="button" class="btn-secondary" onclick="pasoAnterior(3)">← Anterior</button>
                        <button type="button" class="btn-primary" onclick="siguientePaso(3)">Siguiente →</button>
                    </div>
                </div>

                <!-- PASO 4: Licitación (SOLO para licitación nueva) -->
                <div class="step-content hidden" id="content-4">
                    <h2 class="section-title">📄 Paso 4: Nueva Licitación</h2>
                    
                    <div style="background: #eef2f6; padding: 15px; border-radius: 10px; margin-bottom: 25px;">
                        <div style="display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 10px;">
                            <div style="flex: 1; min-width: 200px;">
                                <span style="background: #4a6fa5; color: white; padding: 3px 10px; border-radius: 20px; font-size: 12px;">Paso 1</span>
                                <span style="margin-left: 8px; color: #4a6fa5; font-weight: 500;">Software:</span>
                                <span id="resumen-software-paso4" style="color: #00587a; font-weight: 600;">(pendiente)</span>
                            </div>
                            <div style="flex: 1; min-width: 200px;">
                                <span style="background: #4a6fa5; color: white; padding: 3px 10px; border-radius: 20px; font-size: 12px;">Paso 3</span>
                                <span style="margin-left: 8px; color: #4a6fa5; font-weight: 500;">Proveedor:</span>
                                <span id="resumen-proveedor-paso4" style="color: #00587a; font-weight: 600;">(pendiente)</span>
                            </div>
                        </div>
                    </div>

                    <div class="tipo-software-section">
                        <div style="margin-bottom: 20px;">
                            <label for="folio_licitacion">Folio de la licitación <span style="color: #dc3545;">*</span></label>
                            <input type="text" 
                                   id="folio_licitacion" 
                                   name="folio_licitacion" 
                                   class="input-nuevo-tipo" 
                                   maxlength="50"
                                   required>
                            <p style="font-size: 12px; color: #666; margin-top: 5px;">
                                Folio único que identifica la licitación
                            </p>
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label for="descripcion_licitacion">Descripción de la licitacion</label>
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
                                <label for="fecha_inicio_licitacion">Fecha de inicio de la Licitacion</label>
                                <input type="date" 
                                       id="fecha_inicio_licitacion" 
                                       name="fecha_inicio_licitacion" 
                                       class="input-nuevo-tipo">
                            </div>
                            <div>
                                <label for="fecha_fin_licitacion">Fecha de fin de la Licitacion</label>
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
                            <div>
                                <label for="recurso_licitacion">Recurso</label>
                                <input type="text" 
                                       id="recurso_licitacion" 
                                       name="recurso_licitacion" 
                                       class="input-nuevo-tipo" 
                                       placeholder="Ej: Federal, Estatal, Propio..."
                                       maxlength="45">
                            </div>
                        </div>

                        <div style="background: #fff3cd; padding: 12px; border-radius: 8px; margin-top: 15px;">
                            <p style="color: #856404; font-size: 13px; margin: 0;">
                                <span style="font-weight: 600;">ℹ️ Nota:</span> 
                                Solo el folio es obligatorio. El total se calculará automáticamente con los detalles de compra.
                            </p>
                        </div>
                    </div>

                    <div class="navigation-buttons">
                        <button type="button" class="btn-secondary" onclick="pasoAnterior(4)">← Anterior</button>
                        <button type="button" class="btn-primary" onclick="siguientePaso(4)">Siguiente →</button>
                    </div>
                </div>

                <!-- PASO 5: Detalles Licitación (CANTIDAD Y PRECIO) -->
                <div class="step-content hidden" id="content-5">
                    <h2 class="section-title">📋 Paso 5: Detalles de la Licitación</h2>
                    
                    <div style="background: #eef2f6; padding: 15px; border-radius: 10px; margin-bottom: 25px;">
                        <div style="display: flex; flex-wrap: wrap; gap: 15px;">
                            <div style="flex: 1; min-width: 200px;">
                                <span style="background: #4a6fa5; color: white; padding: 3px 10px; border-radius: 20px; font-size: 12px;">Paso 1</span>
                                <span style="margin-left: 8px; color: #4a6fa5; font-weight: 500;">Software:</span>
                                <span id="resumen-software-paso5" style="color: #00587a; font-weight: 600;">(pendiente)</span>
                            </div>
                            <div style="flex: 1; min-width: 200px;">
                                <span style="background: #4a6fa5; color: white; padding: 3px 10px; border-radius: 20px; font-size: 12px;">Licitación:</span>
                                <span id="resumen-licitacion-paso5" style="color: #00587a; font-weight: 600;">(pendiente)</span>
                            </div>
                        </div>
                    </div>

                    <div class="tipo-software-section">
                        <h3 style="color: #4a6fa5; margin-bottom: 20px;">Detalles de compra</h3>
                        
                        <div style="background: #f0f4f8; padding: 25px; border-radius: 12px; margin-bottom: 20px;">
                            <div style="margin-bottom: 20px;">
                                <label for="cantidad">Cantidad de licencias <span style="color: #dc3545;">*</span></label>
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
                                    Número de licencias idénticas
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
                                    Precio por licencia (sin IVA si aplica)
                                </p>
                            </div>

                            <div style="background: white; padding: 20px; border-radius: 10px; border: 2px solid #6b8cae;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span style="font-weight: 600; color: #4a6fa5;">Subtotal:</span>
                                    <span id="subtotal" style="font-size: 24px; font-weight: bold; color: #6b8cae;">$0.00</span>
                                </div>
                                <p style="font-size: 12px; color: #666; margin-top: 5px; text-align: right;">
                                    Cantidad × Precio unitario
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="navigation-buttons">
                        <button type="button" class="btn-secondary" onclick="pasoAnterior(5)">← Anterior</button>
                        <button type="button" class="btn-primary" onclick="siguientePaso(5)">Siguiente →</button>
                    </div>
                </div>

                <!-- PASO 6: Datos de la Licencia (MODIFICADO: se agregaron Descripción y Capacidad) -->
                <div class="step-content hidden" id="content-6">
                    <h2 class="section-title">🔑 Paso 6: Datos de la Licencia</h2>
                    
                    <div style="background: #eef2f6; padding: 15px; border-radius: 10px; margin-bottom: 25px;">
                        <div style="display: flex; flex-wrap: wrap; gap: 15px;">
                            <div style="flex: 1; min-width: 200px;">
                                <span style="background: #4a6fa5; color: white; padding: 3px 10px; border-radius: 20px; font-size: 12px;">Software:</span>
                                <span id="resumen-software-paso6" style="color: #00587a; font-weight: 600;">(pendiente)</span>
                            </div>
                        </div>
                    </div>

                    <div class="tipo-software-section">
                        <div style="margin-bottom: 20px;">
                            <label for="clave_licencia">Clave de licencia <span style="color: #dc3545;">*</span></label>
                            <input type="text" 
                                   id="clave_licencia" 
                                   name="clave_licencia" 
                                   class="input-nuevo-tipo" 
                                   placeholder="Ej: ABCDE-12345-FGHIJ-67890"
                                   maxlength="45"
                                   required>
                            <p style="font-size: 12px; color: #666; margin-top: 5px;">
                                Clave única que identifica la licencia
                            </p>
                        </div>

                        <!-- NUEVO CAMPO: Descripción de la licencia -->
                        <div style="margin-bottom: 20px;">
                            <label for="descripcion_licencia">Descripción de la licencia</label>
                            <textarea id="descripcion_licencia" 
                                      name="descripcion_licencia" 
                                      class="input-nuevo-tipo" 
                                      placeholder="Ej: Licencia perpetua, Suscripción anual, Incluye soporte técnico, etc."
                                      maxlength="255"
                                      rows="3"
                                      style="resize: vertical; min-height: 80px;"></textarea>
                            <p style="font-size: 12px; color: #666; margin-top: 5px;">
                                Información adicional sobre la licencia (opcional)
                            </p>
                        </div>

                        <!-- NUEVO CAMPO: Capacidad de la licencia -->
                        <div style="margin-bottom: 20px;">
                            <label for="capacidad_licencia">Capacidad de la licencia</label>
                            <input type="number" 
                                   id="capacidad_licencia" 
                                   name="capacidad_licencia" 
                                   class="input-nuevo-tipo" 
                                   placeholder="Ej: 5, 10, 50, 100 (número de usuarios/instalaciones)"
                                   min="1"
                                   step="1">
                            <p style="font-size: 12px; color: #666; margin-top: 5px;">
                                Número de usuarios o instalaciones permitidas (opcional)
                            </p>
                        </div>

                        <div class="grid-2" style="margin-bottom: 20px;">
                            <div>
                                <label for="fecha_compra">Fecha de compra <span style="color: #dc3545;">*</span></label>
                                <input type="date" 
                                       id="fecha_compra" 
                                       name="fecha_compra" 
                                       class="input-nuevo-tipo"
                                       required>
                            </div>
                            <div>
                                <label for="fecha_vencimiento">Fecha de vencimiento <span style="color: #dc3545;">*</span></label>
                                <input type="date" 
                                       id="fecha_vencimiento" 
                                       name="fecha_vencimiento" 
                                       class="input-nuevo-tipo"
                                       required>
                            </div>
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label for="estado_licencia">Estado</label>
                            <select id="estado_licencia" name="estado_licencia" class="select-tipo">
                                <option value="Activa">✅ Activa</option>
                                <option value="Inactiva">⛔ Inactiva</option>
                                <option value="Vencida">📅 Vencida</option>
                                <option value="Por vencer">⚠️ Por vencer</option>
                            </select>
                        </div>

                        <div style="background: #fff3cd; padding: 12px; border-radius: 8px;">
                            <p style="color: #856404; font-size: 13px; margin: 0;">
                                <span style="font-weight: 600;">📝 Nota:</span> 
                                Asegúrate de que la clave de licencia sea correcta y única.
                            </p>
                        </div>
                    </div>

                    <div class="navigation-buttons">
                        <button type="button" class="btn-secondary" onclick="pasoAnterior(6)">← Anterior</button>
                        <button type="button" class="btn-primary" onclick="siguientePaso(6)">Siguiente →</button>
                    </div>
                </div>

                <!-- PASO 7: RESUMEN -->
                <div class="step-content hidden" id="content-7">
                    <h2 class="section-title">✅ Paso 7: Resumen</h2>
                    
                    <div class="resumen-card">
                        <div class="resumen-titulo">
                            <span>📋</span> Datos a guardar
                        </div>
                        <div class="resumen-grid" id="resumen-datos">
                            <!-- Se llena con JavaScript -->
                        </div>
                    </div>

                    <div style="background: #eef2f6; padding: 20px; border-radius: 12px; margin-top: 20px;">
                        <p style="color: #4a6fa5; margin-bottom: 10px; font-weight: 600;">
                            ⚠️ Importante
                        </p>
                        <p style="color: #666; font-size: 14px;">
                            Revisa que todos los datos sean correctos antes de guardar.
                        </p>
                    </div>

                    <div class="navigation-buttons">
                        <button type="button" class="btn-secondary" onclick="pasoAnterior(7)">← Anterior</button>
                        <button type="submit" class="btn-primary" id="btn-guardar">💾 Guardar Licencia</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ALERTA FLOTANTE -->
    <div id="alerta" class="alert-message"></div>

    <script>
        let pasoActual = 1;
        
        // Objeto para almacenar datos
        const datosFormulario = {
            software: null,
            software_nuevo: false,
            tipo_licitacion: null,
            proveedor: null,
            licitacion: null,
            detalle: {
                cantidad: 0,
                precio_unitario: 0,
                subtotal: 0
            },
            licencia: null
        };

        function toggleSoftwareInput() {
            const opcion = document.querySelector('input[name="software_opcion"]:checked').value;
            const divExistente = document.getElementById('software-existente');
            const divNuevo = document.getElementById('software-nuevo');
            
            if (opcion === 'existente') {
                divExistente.style.display = 'block';
                divNuevo.style.display = 'none';
                document.getElementById('nuevo_software_nombre').value = '';
                datosFormulario.software_nuevo = false;
            } else {
                divExistente.style.display = 'none';
                divNuevo.style.display = 'block';
                document.getElementById('software_id').value = '';
                datosFormulario.software_nuevo = true;
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

        function calcularSubtotal() {
            const cantidad = parseFloat(document.getElementById('cantidad').value) || 0;
            const precio = parseFloat(document.getElementById('precio_unitario').value) || 0;
            const subtotal = cantidad * precio;
            document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
        }

        async function agregarNuevoSoftware() {
            const input = document.getElementById('nuevo_software_nombre');
            const nombre = input.value.trim();
            const btn = document.getElementById('btn-agregar-software');
            
            if (!nombre) {
                mostrarAlerta('error', 'Por favor ingresa un nombre para el software');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<span>⏳</span> Agregando...';

            try {
                const response = await fetch('/api/softwares', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ nombre: nombre, tipo: null })
                });

                const data = await response.json();

                if (data.success) {
                    document.getElementById('mensaje-exito-software').style.display = 'block';
                    setTimeout(() => {
                        document.getElementById('mensaje-exito-software').style.display = 'none';
                    }, 3000);

                    input.value = '';

                    const select = document.getElementById('software_id');
                    const option = document.createElement('option');
                    option.value = data.id;
                    option.text = nombre;
                    option.selected = true;
                    select.appendChild(option);

                    document.querySelector('input[value="existente"]').checked = true;
                    toggleSoftwareInput();

                    datosFormulario.software = {
                        id: data.id,
                        nombre: nombre,
                        nuevo: true
                    };

                    mostrarAlerta('success', '✅ Software agregado correctamente');
                } else {
                    mostrarAlerta('error', data.message || 'Error al agregar el software');
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarAlerta('error', 'Error de conexión al servidor');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<span>➕</span> Agregar';
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

                    mostrarAlerta('success', '✅ Proveedor agregado correctamente');
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

        function guardarDatosLicitacion() {
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

        // FUNCIÓN MODIFICADA: Ahora guarda también descripción y capacidad
        function guardarDatosLicencia() {
            datosFormulario.licencia = {
                clave: document.getElementById('clave_licencia').value.trim(),
                descripcion: document.getElementById('descripcion_licencia').value.trim() || null,
                capacidad: document.getElementById('capacidad_licencia').value || null,
                fecha_compra: document.getElementById('fecha_compra').value,
                fecha_vencimiento: document.getElementById('fecha_vencimiento').value,
                estado: document.getElementById('estado_licencia').value
            };
        }

        function mostrarPaso(paso) {
            for (let i = 1; i <= 7; i++) {
                document.getElementById(`content-${i}`).classList.add('hidden');
                document.getElementById(`step-${i}`).classList.remove('active');
            }
            
            document.getElementById(`content-${paso}`).classList.remove('hidden');
            document.getElementById(`step-${paso}`).classList.add('active');
            document.getElementById('paso-actual').textContent = paso;
            pasoActual = paso;

            // Actualizar resúmenes
            if (paso === 2 && datosFormulario.software) {
                document.getElementById('resumen-software-paso2').textContent = datosFormulario.software.nombre;
            }
            if (paso === 3 && datosFormulario.software) {
                document.getElementById('resumen-software-paso3').textContent = datosFormulario.software.nombre;
            }
            if (paso === 4 && datosFormulario.software) {
                document.getElementById('resumen-software-paso4').textContent = datosFormulario.software.nombre;
            }
            if (paso === 4 && datosFormulario.proveedor) {
                document.getElementById('resumen-proveedor-paso4').textContent = datosFormulario.proveedor.nombre;
            }
            if (paso === 5 && datosFormulario.software) {
                document.getElementById('resumen-software-paso5').textContent = datosFormulario.software.nombre;
            }
            if (paso === 5 && datosFormulario.licitacion) {
                document.getElementById('resumen-licitacion-paso5').textContent = datosFormulario.licitacion.folio;
            }
            if (paso === 6 && datosFormulario.software) {
                document.getElementById('resumen-software-paso6').textContent = datosFormulario.software.nombre;
            }
            if (paso === 7) {
                actualizarResumen();
            }
        }

        // FUNCIÓN MODIFICADA: Ahora muestra descripción y capacidad en el resumen
        function actualizarResumen() {
            const resumenDiv = document.getElementById('resumen-datos');
            let html = '';

            // Software
            if (datosFormulario.software) {
                html += `
                    <div class="resumen-item">
                        <div class="resumen-label">Software</div>
                        <div class="resumen-value ${datosFormulario.software_nuevo ? 'nuevo' : ''}">
                            ${datosFormulario.software.nombre}
                        </div>
                    </div>
                `;
            }

            // Proveedor
            if (datosFormulario.proveedor) {
                html += `
                    <div class="resumen-item">
                        <div class="resumen-label">Proveedor</div>
                        <div class="resumen-value ${datosFormulario.proveedor.nuevo ? 'nuevo' : ''}">
                            ${datosFormulario.proveedor.nombre}
                        </div>
                    </div>
                `;
            }

            // Licitación
            if (datosFormulario.licitacion) {
                html += `
                    <div class="resumen-item">
                        <div class="resumen-label">Licitación</div>
                        <div class="resumen-value">
                            ${datosFormulario.licitacion.folio}
                            ${datosFormulario.licitacion.nuevo ? '<span style="color: #28a745;"> (nueva)</span>' : ''}
                        </div>
                    </div>
                `;
            }

            // Detalles
            if (datosFormulario.detalle.cantidad) {
                html += `
                    <div class="resumen-item">
                        <div class="resumen-label">Cantidad</div>
                        <div class="resumen-value">${datosFormulario.detalle.cantidad} licencias</div>
                    </div>
                    <div class="resumen-item">
                        <div class="resumen-label">Precio unitario</div>
                        <div class="resumen-value">$${datosFormulario.detalle.precio_unitario.toFixed(2)}</div>
                    </div>
                    <div class="resumen-item">
                        <div class="resumen-label">Subtotal</div>
                        <div class="resumen-value" style="color: #28a745;">$${datosFormulario.detalle.subtotal.toFixed(2)}</div>
                    </div>
                `;
            }

            // Licencia (con descripción y capacidad)
            if (datosFormulario.licencia) {
                html += `
                    <div class="resumen-item">
                        <div class="resumen-label">Clave</div>
                        <div class="resumen-value">${datosFormulario.licencia.clave}</div>
                    </div>
                `;
                
                if (datosFormulario.licencia.descripcion) {
                    html += `
                        <div class="resumen-item">
                            <div class="resumen-label">Descripción</div>
                            <div class="resumen-value">${datosFormulario.licencia.descripcion}</div>
                        </div>
                    `;
                }
                
                if (datosFormulario.licencia.capacidad) {
                    html += `
                        <div class="resumen-item">
                            <div class="resumen-label">Capacidad</div>
                            <div class="resumen-value">${datosFormulario.licencia.capacidad} usuarios/instalaciones</div>
                        </div>
                    `;
                }
                
                html += `
                    <div class="resumen-item">
                        <div class="resumen-label">Fecha compra</div>
                        <div class="resumen-value">${datosFormulario.licencia.fecha_compra}</div>
                    </div>
                    <div class="resumen-item">
                        <div class="resumen-label">Fecha vencimiento</div>
                        <div class="resumen-value">${datosFormulario.licencia.fecha_vencimiento}</div>
                    </div>
                    <div class="resumen-item">
                        <div class="resumen-label">Estado</div>
                        <div class="resumen-value">${datosFormulario.licencia.estado}</div>
                    </div>
                `;
            }

            resumenDiv.innerHTML = html || '<p style="color: #999; text-align: center;">No hay datos registrados</p>';
        }

        function siguientePaso(paso) {
            if (paso === 1) {
                const opcion = document.querySelector('input[name="software_opcion"]:checked').value;
                
                if (opcion === 'existente') {
                    const softwareId = document.getElementById('software_id').value;
                    if (!softwareId) {
                        mostrarAlerta('error', 'Por favor selecciona un software');
                        return;
                    }
                    const select = document.getElementById('software_id');
                    const selectedOption = select.options[select.selectedIndex];
                    datosFormulario.software = {
                        id: softwareId,
                        nombre: selectedOption.text,
                        nuevo: false
                    };
                } else {
                    const nuevoSoftware = document.getElementById('nuevo_software_nombre').value.trim();
                    if (nuevoSoftware) {
                        mostrarAlerta('error', 'Por favor agrega el nuevo software usando el botón "Agregar"');
                        return;
                    }
                    if (!datosFormulario.software) {
                        mostrarAlerta('error', 'Por favor agrega un nuevo software');
                        return;
                    }
                }
            }
            
            if (paso === 2) {
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
                    
                    mostrarPaso(5);
                    return;
                }
            }
            
            if (paso === 3) {
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
            
            if (paso === 4) {
                const folio = document.getElementById('folio_licitacion').value.trim();
                if (!folio) {
                    mostrarAlerta('error', 'El folio de la licitación es requerido');
                    return;
                }
                guardarDatosLicitacion();
            }
            
            if (paso === 5) {
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
            
            if (paso === 6) {
                const clave = document.getElementById('clave_licencia').value.trim();
                const fechaCompra = document.getElementById('fecha_compra').value;
                const fechaVencimiento = document.getElementById('fecha_vencimiento').value;
                
                if (!clave) {
                    mostrarAlerta('error', 'La clave de licencia es requerida');
                    return;
                }
                if (!fechaCompra) {
                    mostrarAlerta('error', 'La fecha de compra es requerida');
                    return;
                }
                if (!fechaVencimiento) {
                    mostrarAlerta('error', 'La fecha de vencimiento es requerida');
                    return;
                }
                
                guardarDatosLicencia();
            }
            
            mostrarPaso(paso + 1);
        }

        function pasoAnterior(paso) {
            // Si estamos en paso 3 y la licitación es existente, volver al paso 2
            if (paso === 3 && datosFormulario.tipo_licitacion === 'existente') {
                mostrarPaso(2);
                return;
            }
            // Si estamos en paso 4 y la licitación es existente, volver al paso 2
            if (paso === 4 && datosFormulario.tipo_licitacion === 'existente') {
                mostrarPaso(2);
                return;
            }
            // Comportamiento normal
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

        document.getElementById('form-licencia').addEventListener('submit', function(e) {
            if (pasoActual < 7) {
                e.preventDefault();
                mostrarAlerta('error', 'Por favor revisa el resumen antes de guardar');
                mostrarPaso(7);
                return;
            }
            document.getElementById('datos-completos').value = JSON.stringify(datosFormulario);
        });
    </script>
</x-app-layout>