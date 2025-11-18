<div class="p-4">
    <h5 class="mb-4">
        <i class="fas fa-hotel text-primary mr-2"></i>
        Datos del Hotel
    </h5>
    
    <form id="hotelSettingsForm" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="hotel_name">
                        <i class="fas fa-building mr-1"></i>
                        Nombre del Hotel
                    </label>
                    <input type="text" class="form-control" id="hotel_name" name="hotel_name" 
                           value="{{ $hotelSettings->hotel_name }}" required>
                </div>
                
                <div class="form-group">
                    <label for="nit_ruc">
                        <i class="fas fa-id-card mr-1"></i>
                        NIT / RUC
                    </label>
                    <input type="text" class="form-control" id="nit_ruc" name="nit_ruc" 
                           value="{{ $hotelSettings->nit_ruc }}">
                </div>
                
                <div class="form-group">
                    <label for="phone">
                        <i class="fas fa-phone mr-1"></i>
                        Teléfono de Contacto
                    </label>
                    <input type="text" class="form-control" id="phone" name="phone" 
                           value="{{ $hotelSettings->phone }}">
                </div>
                
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope mr-1"></i>
                        Correo Electrónico
                    </label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="{{ $hotelSettings->email }}">
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="address">
                        <i class="fas fa-map-marker-alt mr-1"></i>
                        Dirección
                    </label>
                    <textarea class="form-control" id="address" name="address" rows="3">{{ $hotelSettings->address }}</textarea>
                </div>
                
                <div class="form-group">
                    <label for="city">
                        <i class="fas fa-city mr-1"></i>
                        Ciudad
                    </label>
                    <input type="text" class="form-control" id="city" name="city" 
                           value="{{ $hotelSettings->city }}">
                </div>
                
                <div class="form-group">
                    <label for="country">
                        <i class="fas fa-flag mr-1"></i>
                        País
                    </label>
                    <input type="text" class="form-control" id="country" name="country" 
                           value="{{ $hotelSettings->country }}">
                </div>
                
                <div class="form-group">
                    <label for="logo">
                        <i class="fas fa-image mr-1"></i>
                        Logo del Hotel
                    </label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="logo" name="logo" accept="image/*">
                        <label class="custom-file-label" for="logo">Seleccionar archivo</label>
                    </div>
                    @if($hotelSettings->logo_path)
                    <div class="mt-2">
                        <img src="{{ $hotelSettings->logo_url }}" alt="Logo actual" class="img-thumbnail" style="max-width: 150px;">
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i>
                    Guardar Cambios
                </button>
            </div>
        </div>
    </form>
</div>



