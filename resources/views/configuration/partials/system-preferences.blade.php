<div class="p-4">
    <h5 class="mb-4">
        <i class="fas fa-sliders-h text-primary mr-2"></i>
        Preferencias del Sistema
    </h5>
    
    <form id="systemPreferencesForm">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="language">
                        <i class="fas fa-language mr-1"></i>
                        Idioma del Sistema
                    </label>
                    <select class="form-control" id="language" name="language" required>
                        @foreach(\App\Models\SystemPreference::getAvailableLanguages() as $code => $name)
                        <option value="{{ $code }}" {{ $systemPreferences->language == $code ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="timezone">
                        <i class="fas fa-clock mr-1"></i>
                        Zona Horaria
                    </label>
                    <select class="form-control" id="timezone" name="timezone" required>
                        @foreach(\App\Models\SystemPreference::getAvailableTimezones() as $code => $name)
                        <option value="{{ $code }}" {{ $systemPreferences->timezone == $code ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="currency">
                        <i class="fas fa-dollar-sign mr-1"></i>
                        Moneda Predeterminada
                    </label>
                    <select class="form-control" id="currency" name="currency" required>
                        @foreach(\App\Models\SystemPreference::getAvailableCurrencies() as $code => $name)
                        <option value="{{ $code }}" {{ $systemPreferences->currency == $code ? 'selected' : '' }}>
                            {{ $name }} ({{ $code }})
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="date_format">
                        <i class="fas fa-calendar mr-1"></i>
                        Formato de Fecha
                    </label>
                    <select class="form-control" id="date_format" name="date_format" required>
                        @foreach(\App\Models\SystemPreference::getAvailableDateFormats() as $format => $description)
                        <option value="{{ $format }}" {{ $systemPreferences->date_format == $format ? 'selected' : '' }}>
                            {{ $description }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="time_format">
                        <i class="fas fa-clock mr-1"></i>
                        Formato de Hora
                    </label>
                    <select class="form-control" id="time_format" name="time_format" required>
                        @foreach(\App\Models\SystemPreference::getAvailableTimeFormats() as $format => $description)
                        <option value="{{ $format }}" {{ $systemPreferences->time_format == $format ? 'selected' : '' }}>
                            {{ $description }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="tax_percentage">
                        <i class="fas fa-percentage mr-1"></i>
                        Porcentaje de Impuestos / IVA
                    </label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="tax_percentage" name="tax_percentage" 
                               value="{{ $systemPreferences->tax_percentage }}" min="0" max="100" step="0.01" required>
                        <div class="input-group-append">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i>
                    Guardar Configuración Global
                </button>
            </div>
        </div>
    </form>
</div>
