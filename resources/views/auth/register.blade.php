@extends('template.auth')
@section('title', 'Register - Hotel Management System')

@section('content')
    <div class="auth-container">
        <!-- Animated Background -->
        <div class="auth-bg-animation">
            <div class="floating-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
                <div class="shape shape-4"></div>
                <div class="shape shape-5"></div>
            </div>
        </div>

        <!-- Glassmorphism Card -->
        <div class="auth-card">
            <div class="auth-card-body">
                <!-- Logo Section -->
                <div class="auth-logo-section">
                    <div class="auth-logo">
                        <i class="fas fa-user-plus text-success"></i>
                    </div>
                    <h1 class="auth-title">Crear Cuenta</h1>
                    <p class="auth-subtitle">Únete a nuestro sistema de gestión hotelera</p>
                </div>

                <!-- Registration Form -->
                <form method="POST" action="{{ route('register') }}" class="auth-form" id="registerForm">
                    @csrf

                    <!-- Name Field -->
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-user me-2"></i>Nombre Completo
                        </label>
                        <div class="input-wrapper">
                            <input
                                id="name"
                                type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autocomplete="name"
                                autofocus
                                placeholder="Ingresa tu nombre completo"
                            >
                            @error('name')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-2"></i>Dirección de Correo
                        </label>
                        <div class="input-wrapper">
                            <input
                                id="email"
                                type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                                placeholder="Ingresa tu dirección de correo"
                            >
                            @error('email')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-2"></i>Contraseña
                        </label>
                        <div class="input-wrapper">
                            <input
                                id="password"
                                type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                name="password"
                                required
                                autocomplete="new-password"
                                placeholder="Crea una contraseña"
                                minlength="8"
                            >
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <i class="fas fa-eye" id="password-toggle-icon"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <!-- Password Strength Indicator -->
                        <div class="password-strength" id="password-strength">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strength-fill"></div>
                            </div>
                            <div class="strength-text" id="strength-text">Fortaleza de contraseña</div>
                        </div>
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="form-group">
                        <label for="password-confirm" class="form-label">
                            <i class="fas fa-shield-alt me-2"></i>Confirmar Contraseña
                        </label>
                        <div class="input-wrapper">
                            <input
                                id="password-confirm"
                                type="password"
                                class="form-control"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Confirma tu contraseña"
                            >
                            <button type="button" class="password-toggle" onclick="togglePassword('password-confirm')">
                                <i class="fas fa-eye" id="password-confirm-toggle-icon"></i>
                            </button>
                        </div>
                        <!-- Password Match Indicator -->
                        <div class="password-match" id="password-match" style="display: none;">
                            <i class="fas fa-check-circle text-success me-1"></i>
                            <span>Las contraseñas coinciden</span>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="form-group">
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="terms"
                                name="terms"
                                required
                            >
                            <label class="form-check-label" for="terms">
                                Acepto los <a href="#" class="text-primary">Términos de Servicio</a> y la
                                <a href="#" class="text-primary">Política de Privacidad</a>
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-success auth-submit-btn" id="submitBtn">
                        <span class="btn-text">
                            <i class="fas fa-user-plus me-2"></i>
                            Crear Cuenta
                        </span>
                        <span class="btn-loading d-none">
                            <i class="fas fa-spinner fa-spin me-2"></i>
                            Creando Cuenta...
                        </span>
                    </button>

                    <!-- Login Link -->
                    <div class="auth-links">
                        <span class="text-muted">¿Ya tienes una cuenta?</span>
                        <a href="{{ route('login') }}" class="auth-link">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Iniciar Sesión
                        </a>
                    </div>
                </form>

                <!-- Password Requirements -->
                <div class="auth-help">
                    <h6 class="text-white mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Requisitos de Contraseña
                    </h6>
                    <div class="requirement-list">
                        <div class="requirement-item" id="req-length">
                            <i class="fas fa-circle requirement-icon"></i>
                            <span>Al menos 8 caracteres</span>
                        </div>
                        <div class="requirement-item" id="req-uppercase">
                            <i class="fas fa-circle requirement-icon"></i>
                            <span>Una letra mayúscula</span>
                        </div>
                        <div class="requirement-item" id="req-lowercase">
                            <i class="fas fa-circle requirement-icon"></i>
                            <span>Una letra minúscula</span>
                        </div>
                        <div class="requirement-item" id="req-number">
                            <i class="fas fa-circle requirement-icon"></i>
                            <span>Un número</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .auth-submit-btn.btn-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
        }

        .auth-submit-btn.btn-success:hover {
            background: linear-gradient(135deg, #218838, #1ea581);
            transform: translateY(-2px);
        }

        .form-check {
            margin-bottom: 1rem;
        }

        .form-check-input {
            width: 1.125rem;
            height: 1.125rem;
            margin-top: 0.125rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            background-color: transparent;
        }

        .form-check-input:checked {
            background-color: #28a745;
            border-color: #28a745;
        }

        .form-check-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.875rem;
            margin-left: 0.5rem;
        }

        .form-check-label a {
            color: #0d6efd;
            text-decoration: none;
        }

        .form-check-label a:hover {
            color: #0a58ca;
            text-decoration: underline;
        }

        .password-strength {
            margin-top: 0.5rem;
        }

        .strength-bar {
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }

        .strength-fill {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 2px;
            width: 0%;
        }

        .strength-text {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .password-match {
            font-size: 0.875rem;
            color: #28a745;
            margin-top: 0.5rem;
        }

        .requirement-list {
            display: grid;
            gap: 0.5rem;
        }

        .requirement-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.7);
            transition: all 0.3s ease;
        }

        .requirement-item.met {
            color: #28a745;
        }

        .requirement-icon {
            font-size: 0.5rem;
            transition: all 0.3s ease;
        }

        .requirement-item.met .requirement-icon {
            color: #28a745;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            padding: 0;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: rgba(255, 255, 255, 0.8);
        }

        .input-wrapper {
            position: relative;
        }
    </style>

    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const toggleIcon = document.getElementById(fieldId + '-toggle-icon');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.className = 'fas fa-eye-slash';
            } else {
                passwordField.type = 'password';
                toggleIcon.className = 'fas fa-eye';
            }
        }

        // Password strength checker
        function checkPasswordStrength(password) {
            let score = 0;
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /\d/.test(password)
            };

            // Update requirement indicators
            Object.keys(requirements).forEach(req => {
                const element = document.getElementById(`req-${req}`);
                if (requirements[req]) {
                    element.classList.add('met');
                    score++;
                } else {
                    element.classList.remove('met');
                }
            });

            return { score, total: 4 };
        }

        // Update password strength indicator
        function updatePasswordStrength(password) {
            const { score, total } = checkPasswordStrength(password);
            const strengthFill = document.getElementById('strength-fill');
            const strengthText = document.getElementById('strength-text');

            const percentage = (score / total) * 100;
            strengthFill.style.width = percentage + '%';

            if (score === 0) {
                strengthFill.style.background = 'transparent';
                strengthText.textContent = 'Fortaleza de contraseña';
                strengthText.style.color = 'rgba(255, 255, 255, 0.7)';
            } else if (score < 2) {
                strengthFill.style.background = '#dc3545';
                strengthText.textContent = 'Contraseña débil';
                strengthText.style.color = '#dc3545';
            } else if (score < 3) {
                strengthFill.style.background = '#ffc107';
                strengthText.textContent = 'Contraseña regular';
                strengthText.style.color = '#ffc107';
            } else if (score < 4) {
                strengthFill.style.background = '#fd7e14';
                strengthText.textContent = 'Buena contraseña';
                strengthText.style.color = '#fd7e14';
            } else {
                strengthFill.style.background = '#28a745';
                strengthText.textContent = 'Contraseña fuerte';
                strengthText.style.color = '#28a745';
            }
        }

        // Check if passwords match
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password-confirm').value;
            const matchIndicator = document.getElementById('password-match');

            if (confirmPassword.length > 0) {
                if (password === confirmPassword) {
                    matchIndicator.style.display = 'block';
                    matchIndicator.innerHTML = '<i class="fas fa-check-circle text-success me-1"></i><span>Las contraseñas coinciden</span>';
                    matchIndicator.style.color = '#28a745';
                } else {
                    matchIndicator.style.display = 'block';
                    matchIndicator.innerHTML = '<i class="fas fa-times-circle text-danger me-1"></i><span>Las contraseñas no coinciden</span>';
                    matchIndicator.style.color = '#dc3545';
                }
            } else {
                matchIndicator.style.display = 'none';
            }
        }

        // Event listeners
        document.getElementById('password').addEventListener('input', function() {
            updatePasswordStrength(this.value);
            checkPasswordMatch();
        });

        document.getElementById('password-confirm').addEventListener('input', checkPasswordMatch);

        // Form submission
        document.getElementById('registerForm').addEventListener('submit', function() {
            const submitBtn = document.getElementById('submitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');

            // Show loading state
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
            submitBtn.disabled = true;
            submitBtn.classList.add('btn-loading-active');
        });

        // Add smooth focus animations
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('input-focused');
            });

            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('input-focused');
            });
        });
    </script>
