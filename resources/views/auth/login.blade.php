@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="max-w-md mx-auto mt-6" x-data="loginForm()" x-init="checkIfAlreadyLoggedIn()">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 sm:p-8">
        
        <!-- Encabezado -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Iniciar Sesión</h1>
            <p class="text-sm text-slate-500 mt-1">Accede para gestionar préstamos en Med Library</p>
        </div>

        <!-- Alerta de Error General -->
        <div x-show="errorMessage" x-cloak class="mb-4 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-lg text-sm">
            <span x-text="errorMessage"></span>
        </div>

        <!-- Formulario -->
        <form @submit.prevent="submitLogin()" class="space-y-4">
            
            <!-- Correo Electrónico -->
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">
                    Correo electrónico
                </label>
                <input 
                    id="email"
                    type="email" 
                    x-model="email"
                    required
                    autocomplete="email"
                    placeholder="usuario@medlibrary.test"
                    class="w-full px-3.5 py-2.5 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    :class="errors.email ? 'border-rose-400' : 'border-slate-300'"
                >
                <template x-if="errors.email">
                    <p class="mt-1 text-xs text-rose-600" x-text="errors.email[0]"></p>
                </template>
            </div>

            <!-- Contraseña -->
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">
                    Contraseña
                </label>
                <div class="relative">
                    <input 
                        id="password"
                        :type="showPassword ? 'text' : 'password'" 
                        x-model="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="w-full px-3.5 py-2.5 pr-10 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        :class="errors.password ? 'border-rose-400' : 'border-slate-300'"
                    >
                    <button 
                        type="button"
                        @click="showPassword = !showPassword"
                        class="absolute right-3 top-2.5 text-xs text-slate-400 hover:text-slate-600 font-medium"
                        x-text="showPassword ? 'Ocultar' : 'Ver'"
                    ></button>
                </div>
                <template x-if="errors.password">
                    <p class="mt-1 text-xs text-rose-600" x-text="errors.password[0]"></p>
                </template>
            </div>

            <!-- Botón Enviar -->
            <button 
                type="submit" 
                :disabled="loading"
                class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-medium text-sm rounded-lg transition flex items-center justify-center gap-2 mt-2"
            >
                <svg x-show="loading" x-cloak class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <span x-text="loading ? 'Verificando...' : 'Ingresar'"></span>
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function loginForm() {
    return {
        email: '',
        password: '',
        showPassword: false,
        loading: false,
        errorMessage: null,
        errors: {},

        checkIfAlreadyLoggedIn() {
            if (localStorage.getItem('api_token')) {
                window.location.href = '{{ route('books.catalog') }}';
            }
        },

        async submitLogin() {
            this.loading = true;
            this.errorMessage = null;
            this.errors = {};

            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        email: this.email,
                        password: this.password,
                        device_name: 'web-browser'
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    if (response.status === 422 && data.errors) {
                        this.errors = data.errors;
                        this.errorMessage = data.message || 'Verifica los datos ingresados.';
                    } else {
                        this.errorMessage = data.message || 'Credenciales incorrectas.';
                    }
                    return;
                }

                // Guardar token Sanctum y datos básicos del usuario
                localStorage.setItem('api_token', data.access_token);
                if (data.user?.name) {
                    localStorage.setItem('user_name', data.user.name);
                }

                // Redirigir al catálogo
                window.location.href = '{{ route('books.catalog') }}';

            } catch (err) {
                this.errorMessage = 'No se pudo conectar con el servidor. Intenta de nuevo.';
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endpush