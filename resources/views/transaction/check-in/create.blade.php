@extends('template.master')
@section('title', 'Registrar Nuevo Huésped')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border">
                <div class="card-header p-3">
                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                            <div class="card-title">
                                <h4><i class="fas fa-user-plus me-2"></i>{{ __('messages.register_new_guest') }}</h4>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 text-end">
                            <a href="{{ route('transaction.check-in.index') }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>{{ __('messages.back') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('transaction.check-in.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">{{ __('messages.name') }} *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label">Dirección *</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" required>
                                @error('address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="job" class="form-label">Ocupación *</label>
                                <input type="text" class="form-control @error('job') is-invalid @enderror" id="job" name="job" value="{{ old('job') }}" required>
                                @error('job')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="birthdate" class="form-label">Fecha de Nacimiento *</label>
                                <input type="date" class="form-control @error('birthdate') is-invalid @enderror" id="birthdate" name="birthdate" value="{{ old('birthdate') }}" required>
                                @error('birthdate')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Género *</label>
                                <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                    <option value="" selected disabled>Seleccionar género</option>
                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Masculino</option>
                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Femenino</option>
                                </select>
                                @error('gender')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>{{ __('messages.save') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection