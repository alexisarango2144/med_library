@extends('layouts.app')

@section('title', 'Nuevo Préstamo')

@section('content')
<div class="max-w-2xl mx-auto" x-data="createLoanForm()" x-init="initForm()">
    
    <!-- Encabezado y Volver -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Registrar Nuevo Préstamo</h1>
            <p class="text-sm text-slate-500 mt-1">Selecciona el usuario, el libro disponible y la fecha de devolución</p>
        </div>
        <a href="{{ route('books.catalog') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600">
            ← Volver al catálogo
        </a>
    </div>

    <!-- Alerta de Error de Reglas de Negocio (ej. Límite de 3 préstamos o sin stock) -->
    <div x-show="errorMessage" x-cloak class="mb-5 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm flex items-start gap-2">
        <span class="font-bold">!</span>
        <span x-text="errorMessage"></span>
    </div>

    <!-- Alerta de Éxito -->
    <div x-show="successMessage" x-cloak class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm">
        <p class="font-semibold" x-text="successMessage"></p>
        <p class="text-xs mt-1">Redirigiendo a tus préstamos...</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 sm:p-8">
        <form @submit.prevent="submitLoan()" class="space-y-6">

            <!-- 1. Selector de Usuario al que se presta -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Usuario / Lector <span class="text-rose-500">*</span>
                </label>

                <!-- Buscador de Usuarios -->
                <div x-show="!selectedUser" class="space-y-2">
                    <div class="relative">
                        <input 
                            type="text" 
                            x-model="userSearch"
                            @input.debounce.300ms="searchUsers()"
                            placeholder="Buscar usuario por nombre o correo electrónico..."
                            class="w-full px-3.5 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        >
                        <span x-show="searchingUsers" x-cloak class="absolute right-3 top-2.5 text-xs text-slate-400">
                            Buscando...
                        </span>
                    </div>

                    <!-- Lista de sugerencias de usuarios -->
                    <div x-show="usersList.length > 0" x-cloak class="border border-slate-200 rounded-lg divide-y divide-slate-100 max-h-48 overflow-y-auto bg-slate-50">
                        <template x-for="user in usersList" :key="user.id">
                            <button 
                                type="button"
                                @click="selectUser(user)"
                                class="w-full text-left px-4 py-2.5 hover:bg-indigo-50 transition flex items-center justify-between gap-3"
                            >
                                <div>
                                    <p class="text-sm font-semibold text-slate-900" x-text="user.name"></p>
                                    <p class="text-xs text-slate-500" x-text="user.email"></p>
                                </div>
                                <span class="text-xs text-slate-400" x-text="`ID #${user.id}`"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Tarjeta del Usuario Seleccionado -->
                <template x-if="selectedUser">
                    <div class="border border-slate-300 bg-slate-50 rounded-xl p-3.5 flex items-center justify-between gap-4">
                        <div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Lector asignado</span>
                            <p class="font-bold text-slate-900 text-sm mt-0.5" x-text="selectedUser.name"></p>
                            <p class="text-xs text-slate-500" x-text="selectedUser.email"></p>
                        </div>

                        <button 
                            type="button" 
                            @click="clearSelectedUser()"
                            class="text-xs font-medium text-slate-500 hover:text-rose-600 underline shrink-0"
                        >
                            Cambiar usuario
                        </button>
                    </div>
                </template>

                <template x-if="errors.user_id">
                    <p class="mt-1 text-xs text-rose-600" x-text="errors.user_id[0]"></p>
                </template>
            </div>
            
            <!-- 2. Buscador / Selector de Libro -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Libro a prestar <span class="text-rose-500">*</span>
                </label>

                <div x-show="!selectedBook" class="space-y-2">
                    <div class="relative">
                        <input 
                            type="text" 
                            x-model="bookSearch"
                            @input.debounce.300ms="searchBooks()"
                            placeholder="Escribe el título, ISBN o autor para buscar..."
                            class="w-full px-3.5 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        >
                        <span x-show="searchingBooks" x-cloak class="absolute right-3 top-2.5 text-xs text-slate-400">
                            Buscando...
                        </span>
                    </div>

                    <div x-show="availableBooks.length > 0" x-cloak class="border border-slate-200 rounded-lg divide-y divide-slate-100 max-h-56 overflow-y-auto bg-slate-50">
                        <template x-for="book in availableBooks" :key="book.id">
                            <button 
                                type="button"
                                @click="selectBook(book)"
                                class="w-full text-left px-4 py-2.5 hover:bg-indigo-50 transition flex items-center justify-between gap-3"
                            >
                                <div>
                                    <p class="text-sm font-semibold text-slate-900" x-text="book.title"></p>
                                    <p class="text-xs text-slate-500" x-text="book.authors?.map(a => `${a.name} ${a.last_name}`).join(', ') || 'Sin autor'"></p>
                                </div>
                                <span class="text-xs font-medium bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full shrink-0"
                                      x-text="`${book.available_stock} disp.`">
                                </span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Tarjeta del Libro Seleccionado -->
                <template x-if="selectedBook">
                    <div class="border border-indigo-200 bg-indigo-50/50 rounded-xl p-4 flex items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-semibold uppercase tracking-wider text-indigo-600">Libro seleccionado</span>
                                <span 
                                    class="text-xs font-semibold px-2 py-0.5 rounded-full"
                                    :class="selectedBook.available_stock > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'"
                                    x-text="selectedBook.available_stock > 0 ? `${selectedBook.available_stock} disponibles` : 'Agotado'"
                                ></span>
                            </div>
                            <h3 class="font-bold text-slate-900 text-base mt-1" x-text="selectedBook.title"></h3>
                            <p class="text-xs text-slate-600 mt-0.5" x-show="selectedBook.authors?.length">
                                Autor(es): <span x-text="selectedBook.authors.map(a => `${a.name} ${a.last_name}`).join(', ')"></span>
                            </p>
                            <p class="text-xs text-slate-400 mt-0.5" x-show="selectedBook.isbn">
                                ISBN: <span x-text="selectedBook.isbn"></span>
                            </p>
                        </div>

                        <button 
                            type="button" 
                            @click="clearSelectedBook()"
                            class="text-xs font-medium text-slate-500 hover:text-rose-600 underline shrink-0"
                        >
                            Cambiar libro
                        </button>
                    </div>
                </template>

                <template x-if="errors.book_id">
                    <p class="mt-1 text-xs text-rose-600" x-text="errors.book_id[0]"></p>
                </template>
            </div>

            <!-- 3. Fechas del Préstamo -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Fecha de préstamo
                    </label>
                    <input 
                        type="date" 
                        x-model="borrowedAt"
                        readonly
                        class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-100 text-slate-600 rounded-lg text-sm cursor-not-allowed"
                    >
                </div>

                <div>
                    <label for="estimated_return_date" class="block text-sm font-medium text-slate-700 mb-1">
                        Fecha estimada de devolución <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        id="estimated_return_date"
                        type="date" 
                        x-model="estimatedReturnDate"
                        :min="borrowedAt"
                        :max="maxReturnDate"
                        required
                        class="w-full px-3.5 py-2.5 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        :class="errors.estimated_return_date ? 'border-rose-400' : 'border-slate-300'"
                    >
                    <p class="mt-1 text-xs text-slate-400">Plazo estándar: máximo 15 días calendario.</p>
                    <template x-if="errors.estimated_return_date">
                        <p class="mt-1 text-xs text-rose-600" x-text="errors.estimated_return_date[0]"></p>
                    </template>
                </div>
            </div>

            <!-- Resumen de Reglas -->
            <div class="bg-slate-50 border border-slate-200 rounded-lg p-3.5 text-xs text-slate-600 space-y-1">
                <p class="font-semibold text-slate-700">Condiciones del préstamo:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    <li>Cada usuario puede tener hasta <strong>3 préstamos activos</strong> simultáneamente.</li>
                    <li>Después de 15 días sin devolución, el préstamo pasará automáticamente a estado <strong>vencido</strong>.</li>
                </ul>
            </div>

            <!-- Botones de Acción -->
            <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
                <a href="{{ route('books.catalog') }}" class="px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-lg transition">
                    Cancelar
                </a>
                <button 
                    type="submit" 
                    :disabled="loading || !selectedUser || !selectedBook || selectedBook.available_stock <= 0"
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-medium text-sm rounded-lg transition flex items-center gap-2"
                >
                    <svg x-show="loading" x-cloak class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    <span x-text="loading ? 'Procesando...' : 'Confirmar Préstamo'"></span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function createLoanForm() {
    return {
        userSearch: '',
        usersList: [],
        selectedUser: null,
        searchingUsers: false,

        bookSearch: '',
        availableBooks: [],
        selectedBook: null,
        searchingBooks: false,
        
        borrowedAt: '',
        estimatedReturnDate: '',
        maxReturnDate: '',

        loading: false,
        errorMessage: null,
        successMessage: null,
        errors: {},

        async initForm() {
            const token = localStorage.getItem('api_token');
            if (!token) {
                window.location.href = '{{ route('login.view') }}';
                return;
            }

            const today = new Date();
            const in15Days = new Date();
            in15Days.setDate(today.getDate() + 15);

            this.borrowedAt = today.toISOString().split('T')[0];
            this.estimatedReturnDate = in15Days.toISOString().split('T')[0];
            this.maxReturnDate = this.estimatedReturnDate;

            // Cargar lista inicial de usuarios
            await this.searchUsers();

            // Revisar si viene un libro preseleccionado en la URL
            const params = new URLSearchParams(window.location.search);
            const bookSlug = params.get('book_slug');
            const bookId = params.get('book_id');

            if (bookSlug) {
                await this.loadPreselectedBook(`/api/books/${encodeURIComponent(bookSlug)}`);
            } else if (bookId) {
                await this.loadPreselectedBook(`/api/books/${encodeURIComponent(bookId)}`);
            } else {
                await this.searchBooks();
            }
        },

        async searchUsers() {
            this.searchingUsers = true;
            const token = localStorage.getItem('api_token');
            const params = new URLSearchParams();

            if (this.userSearch.trim() !== '') {
                params.append('search', this.userSearch.trim());
            }

            try {
                const response = await fetch(`/api/users?${params.toString()}`, {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });
                if (response.ok) {
                    const json = await response.json();
                    this.usersList = json.data ?? json;
                }
            } finally {
                this.searchingUsers = false;
            }
        },

        selectUser(user) {
            this.selectedUser = user;
            this.usersList = [];
            this.userSearch = '';
            this.errorMessage = null;
        },

        clearSelectedUser() {
            this.selectedUser = null;
            this.errorMessage = null;
            this.searchUsers();
        },

        async loadPreselectedBook(endpoint) {
            try {
                const response = await fetch(endpoint, {
                    headers: { 'Accept': 'application/json' }
                });
                if (response.ok) {
                    const json = await response.json();
                    this.selectedBook = json.data ?? json;
                }
            } catch (e) {
                console.error('No se pudo cargar el libro preseleccionado:', e);
            }
        },

        async searchBooks() {
            this.searchingBooks = true;
            const params = new URLSearchParams({
                available: '1',
                sort_by: 'title',
                direction: 'asc'
            });

            if (this.bookSearch.trim() !== '') {
                params.append('search', this.bookSearch.trim());
            }

            try {
                const response = await fetch(`/api/books?${params.toString()}`, {
                    headers: { 'Accept': 'application/json' }
                });
                if (response.ok) {
                    const json = await response.json();
                    this.availableBooks = json.data;
                }
            } finally {
                this.searchingBooks = false;
            }
        },

        selectBook(book) {
            this.selectedBook = book;
            this.availableBooks = [];
            this.bookSearch = '';
            this.errorMessage = null;
        },

        clearSelectedBook() {
            this.selectedBook = null;
            this.errorMessage = null;
            window.history.replaceState({}, '', '{{ route('loans.create') }}');
            this.searchBooks();
        },

        async submitLoan() {
            if (!this.selectedUser || !this.selectedBook) return;

            this.loading = true;
            this.errorMessage = null;
            this.successMessage = null;
            this.errors = {};

            const token = localStorage.getItem('api_token');

            try {
                const response = await fetch('/api/loans', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({
                        user_id: this.selectedUser.id,
                        book_id: this.selectedBook.id,
                        borrowed_at: this.borrowedAt,
                        estimated_return_date: this.estimatedReturnDate
                    })
                });

                if (response.status === 401) {
                    localStorage.removeItem('api_token');
                    localStorage.removeItem('user_name');
                    window.location.href = '{{ route('login.view') }}';
                    return;
                }

                const data = await response.json();

                if (!response.ok) {
                    if (response.status === 422 && data.errors) {
                        this.errors = data.errors;
                    }
                    this.errorMessage = data.message || 'No se pudo registrar el préstamo.';
                    return;
                }

                this.successMessage = `Préstamo de "${this.selectedBook.title}" registrado para ${this.selectedUser.name}.`;
                
                setTimeout(() => {
                    window.location.href = '{{ route('loans.index') }}';
                }, 1400);

            } catch (err) {
                this.errorMessage = 'Error de conexión con el servidor.';
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endpush