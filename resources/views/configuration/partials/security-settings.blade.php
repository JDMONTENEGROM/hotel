<div class="p-4">
    <h5 class="mb-4">
        <i class="fas fa-shield-alt text-primary mr-2"></i>
        Configuración de Seguridad
    </h5>
    
    <form id="securitySettingsForm">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <h6 class="text-muted mb-3">
                    <i class="fas fa-key mr-1"></i>
                    Política de Contraseñas
                </h6>
                
                <div class="form-group">
                    <label for="min_password_length">
                        Longitud Mínima de Contraseña
                    </label>
                    <input type="number" class="form-control" id="min_password_length" name="min_password_length" 
                           value="{{ $securitySettings->min_password_length }}" min="6" max="20" required>
                </div>
                
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="require_numbers" name="require_numbers" 
                               {{ $securitySettings->require_numbers ? 'checked' : '' }}>
                        <label class="form-check-label" for="require_numbers">
                            Requerir números
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="require_symbols" name="require_symbols" 
                               {{ $securitySettings->require_symbols ? 'checked' : '' }}>
                        <label class="form-check-label" for="require_symbols">
                            Requerir símbolos especiales
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="allow_password_change" name="allow_password_change" 
                               {{ $securitySettings->allow_password_change ? 'checked' : '' }}>
                        <label class="form-check-label" for="allow_password_change">
                            Permitir cambio de contraseña por parte del usuario
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <h6 class="text-muted mb-3">
                    <i class="fas fa-lock mr-1"></i>
                    Control de Acceso
                </h6>
                
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="two_factor_auth" name="two_factor_auth" 
                               {{ $securitySettings->two_factor_auth ? 'checked' : '' }}>
                        <label class="form-check-label" for="two_factor_auth">
                            Activar autenticación en dos pasos (2FA)
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="max_login_attempts">
                        Máximo de Intentos de Login
                    </label>
                    <input type="number" class="form-control" id="max_login_attempts" name="max_login_attempts" 
                           value="{{ $securitySettings->max_login_attempts }}" min="3" max="10" required>
                </div>
                
                <div class="form-group">
                    <label for="lockout_duration">
                        Duración del Bloqueo (minutos)
                    </label>
                    <input type="number" class="form-control" id="lockout_duration" name="lockout_duration" 
                           value="{{ $securitySettings->lockout_duration }}" min="5" max="120" required>
                </div>
                
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="log_activity" name="log_activity" 
                               {{ $securitySettings->log_activity ? 'checked' : '' }}>
                        <label class="form-check-label" for="log_activity">
                            Registrar actividad del sistema
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Registro de Actividad:</strong> 
                    Muestra último inicio de sesión, dirección IP, fecha y hora de cada usuario.
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i>
                    Guardar Configuración de Seguridad
                </button>
            </div>
        </div>
    </form>
</div>
