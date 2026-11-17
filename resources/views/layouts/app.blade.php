<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Med Library') — Sistema de Biblioteca</title>

    {{-- CDN directo para desarrollo rápido --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col" x-data="authNavbar()">
    <!-- Header y Barra de Navegación -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex justify-between items-center h-16">

                <!-- Logo + Enlaces Principales -->
                <div class="flex items-center gap-8">
                    <a href="{{ route('books.catalog') }}"
                        class="flex items-center gap-2 text-indigo-600 font-bold text-lg tracking-tight">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6"  viewBox="0 0 24 24"
                            fill="none" transform="rotate(0 0 0)">
                            <path
                                d="M11.5 5.33167C11.0506 4.98086 10.4591 4.6066 9.71726 4.30836C8.98063 4.01221 7.8392 3.91963 6.98579 3.88659C6.53797 3.86925 6.13022 3.86753 5.83471 3.87009C5.68668 3.87137 5.56613 3.87374 5.48198 3.87581C5.43989 3.87684 5.40686 3.8778 5.38401 3.87852L5.3502 3.87964L5.34703 3.87975C4.94438 3.89478 4.62564 4.22524 4.625 4.62802V15.6309C4.62515 15.7324 4.64591 15.8328 4.68602 15.9261C4.72613 16.0194 4.78476 16.1035 4.85835 16.1735C4.93195 16.2434 5.01897 16.2977 5.11416 16.333C5.20934 16.3683 5.31071 16.3839 5.41211 16.3789C6.37623 16.3325 7.50232 16.3369 8.4082 16.7011C9.86494 17.2868 11.418 19.1035 11.418 19.1035C11.4431 19.1344 11.4705 19.1631 11.5 19.1895V5.33167Z"
                                fill="currentColor"></path>
                            <path
                                d="M12.5 5.33167C12.9494 4.98086 13.5409 4.6066 14.2827 4.30836C15.0194 4.01221 16.1608 3.91963 17.0142 3.88659C17.462 3.86925 17.8698 3.86753 18.1653 3.87009C18.3133 3.87137 18.4339 3.87374 18.518 3.87581C18.5601 3.87684 18.5931 3.8778 18.616 3.87852L18.6498 3.87964L18.653 3.87975C19.0556 3.89478 19.3744 4.22524 19.375 4.62802V15.6309C19.3748 15.7324 19.3541 15.8328 19.314 15.9261C19.2739 16.0194 19.2152 16.1035 19.1416 16.1735C19.0681 16.2434 18.981 16.2977 18.8858 16.333C18.7907 16.3683 18.6893 16.3839 18.5879 16.3789C17.6238 16.3325 16.4977 16.3369 15.5918 16.7011C14.1351 17.2868 12.582 19.1035 12.582 19.1035C12.5569 19.1344 12.5295 19.1631 12.5 19.1895V5.33167Z"
                                fill="currentColor"></path>
                            <g opacity="0.4">
                                <path
                                    d="M3.625 6.61133C3.33223 6.6117 3.03055 6.61827 2.71884 6.63124C2.31709 6.64794 2 6.9785 2 7.38059V18.6306C2 18.8639 2.10857 19.0839 2.29374 19.2258C2.47891 19.3678 2.71958 19.4155 2.94487 19.3548C5.55651 18.6521 7.9332 18.5626 10.4357 19.5081C10.4316 19.5037 10.4275 19.4992 10.4232 19.4947C9.74621 18.773 8.96377 18.0023 8.03519 17.629C7.25601 17.3158 6.28882 17.3582 5.46013 17.3778C5.22399 17.3893 4.98796 17.3528 4.7663 17.2706C4.54422 17.1882 4.34117 17.0616 4.16946 16.8984C3.99776 16.7352 3.86097 16.5388 3.76738 16.3212C3.67379 16.1036 3.62535 15.8693 3.625 15.6324L3.625 6.61133Z"
                                    fill="currentColor"></path>
                                <path
                                    d="M20.375 6.61133C20.6678 6.6117 20.9695 6.61827 21.2812 6.63124C21.6829 6.64794 22 6.9785 22 7.38059V18.6306C22 18.8639 21.8914 19.0839 21.7063 19.2258C21.5211 19.3678 21.2804 19.4155 21.0551 19.3548C18.4435 18.6521 16.0668 18.5626 13.5643 19.5081C13.5684 19.5037 13.5725 19.4992 13.5768 19.4947C14.2538 18.773 15.0362 18.0023 15.9648 17.629C16.744 17.3158 17.7112 17.3582 18.5399 17.3778C18.776 17.3893 19.012 17.3528 19.2337 17.2706C19.4558 17.1882 19.6588 17.0616 19.8305 16.8984C20.0022 16.7352 20.139 16.5388 20.2326 16.3212C20.3262 16.1036 20.3746 15.8693 20.375 15.6324L20.375 6.61133Z"
                                    fill="currentColor"></path>
                            </g>
                        </svg>
                        <span>Med Library</span>
                    </a>

                    <nav class="hidden md:flex items-center gap-1">
                        <!-- Catálogo (Público) -->
                        <a href="{{ route('books.catalog') }}"
                            class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('books.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            Catálogo
                        </a>

                        <!-- Autores (Público) -->
                        <a href="{{ route('authors.index') }}"
                            class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('authors.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            Autores
                        </a>

                        <!-- Préstamos (Solo si está autenticado por token Sanctum) -->
                        <a href="{{ route('loans.index') }}" x-show="isAuthenticated" x-cloak
                            class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('loans.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            Préstamos
                        </a>
                    </nav>
                </div>

                <!-- Estado de Sesión (Derecha) -->
                <div class="hidden md:flex items-center gap-3">
                    <template x-if="!isAuthenticated">
                        <a href="{{ route('login.view') }}"
                            class="text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-lg transition">
                            Iniciar sesión
                        </a>
                    </template>

                    <template x-if="isAuthenticated">
                        <div class="flex items-center gap-3">
                            <span class="text-sm text-slate-600" x-text="userName"></span>
                            <button @click="logout()"
                                class="text-sm font-medium text-rose-600 hover:bg-rose-50 px-3 py-1.5 rounded-lg border border-rose-200 transition">
                                Salir
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenido Dinámico -->
    <main class="flex-1 max-w-6xl w-full mx-auto px-4 sm:px-6 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-4 text-center text-xs text-slate-400">
        Med Library &copy; {{ date('Y') }}
    </footer>

    <script>
        function authNavbar() {
            return {
                // Detecta autenticación por token Sanctum en localStorage
                isAuthenticated: Boolean(localStorage.getItem('api_token')),
                userName: localStorage.getItem('user_name') || 'Usuario',

                async logout() {
                    const token = localStorage.getItem('api_token');
                    if (token) {
                        await fetch('/api/logout', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Authorization': `Bearer ${token}`
                            }
                        }).catch(() => {});
                    }
                    localStorage.removeItem('api_token');
                    localStorage.removeItem('user_name');
                    this.isAuthenticated = false;
                    window.location.href = '{{ route('books.catalog') }}';
                }
            }
        }
    </script>
    @stack('scripts')
</body>

</html>
