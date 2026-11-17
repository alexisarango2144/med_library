@extends('layouts.app')

@section('title', 'Catálogo de Libros')

@section('content')
<div x-data="booksCatalog()" x-init="fetchBooks()">
    
    <!-- Encabezado -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Catálogo de Libros</h1>
            <p class="text-sm text-slate-500">Busca por título, ISBN o autor en tiempo real</p>
        </div>
        <div class="text-sm text-slate-600" x-show="!loading">
            Mostrando <span class="font-semibold" x-text="books.length"></span> de 
            <span class="font-semibold" x-text="meta.total ?? 0"></span> resultados
        </div>
    </div>

    <!-- Barra de Búsqueda y Filtros -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 mb-6 grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
        
        <!-- Input de búsqueda en tiempo real -->
        <div class="md:col-span-6 relative">
            <input 
                type="text" 
                x-model="search"
                @input.debounce.350ms="resetAndFetch()"
                placeholder="Buscar título, ISBN o autor (ej. García Márquez)..."
                class="w-full pl-10 pr-10 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
            >
            <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
            </svg>
            <button 
                x-show="search.length > 0" 
                x-cloak
                @click="search = ''; resetAndFetch()" 
                class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 text-sm font-bold"
                title="Limpiar búsqueda"
            >
                ✕
            </button>
        </div>

        <!-- Ordenamiento -->
        <div class="md:col-span-3">
            <select 
                x-model="sortBy" 
                @change="resetAndFetch()"
                class="w-full py-2.5 px-3 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500"
            >
                <option value="title">Ordenar por: Título (A-Z)</option>
                <option value="publication_year">Año de publicación</option>
                <option value="available_stock">Stock disponible</option>
                <option value="created_at">Más recientes</option>
            </select>
        </div>

        <!-- Checkbox Solo Disponibles -->
        <div class="md:col-span-3 flex items-center justify-start md:justify-end">
            <label class="inline-flex items-center cursor-pointer select-none">
                <input 
                    type="checkbox" 
                    x-model="onlyAvailable" 
                    @change="resetAndFetch()"
                    class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4"
                >
                <span class="ml-2 text-sm text-slate-700 font-medium">Solo disponibles</span>
            </label>
        </div>
    </div>

    <!-- Mensaje de Error -->
    <div x-show="error" x-cloak class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
        <span x-text="error"></span>
    </div>

    <!-- Estado de Carga (Skeleton) -->
    <div x-show="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        <template x-for="i in 6" :key="i">
            <div class="bg-white p-5 rounded-xl border border-slate-200 animate-pulse space-y-3">
                <div class="h-5 bg-slate-200 rounded w-3/4"></div>
                <div class="h-4 bg-slate-200 rounded w-1/2"></div>
                <div class="h-16 bg-slate-100 rounded"></div>
                <div class="flex justify-between pt-2">
                    <div class="h-6 bg-slate-200 rounded w-24"></div>
                    <div class="h-6 bg-slate-200 rounded w-16"></div>
                </div>
            </div>
        </template>
    </div>

    <!-- Sin Resultados -->
    <div x-show="!loading && books.length === 0 && !error" x-cloak class="bg-white rounded-xl border border-slate-200 p-12 text-center">
        <p class="text-slate-600 font-medium">No se encontraron libros que coincidan con tu búsqueda.</p>
        <button @click="search = ''; onlyAvailable = false; resetAndFetch()" class="mt-3 text-sm text-indigo-600 hover:underline">
            Restablecer filtros
        </button>
    </div>

    <!-- Grid de Libros -->
    <div x-show="!loading && books.length > 0" x-cloak class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        <template x-for="book in books" :key="book.id">
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <!-- Título y Año -->
                    <div class="flex items-start justify-between gap-2">
                        <h2 class="font-bold text-lg text-slate-900 leading-snug">
                            <a :href="`/catalogo/${book.slug}`" class="hover:text-indigo-600 transition" x-text="book.title"></a>
                        </h2>
                        <span class="text-xs font-medium bg-slate-100 text-slate-600 px-2 py-1 rounded shrink-0" x-text="book.publication_year ?? 'S/F'"></span>
                    </div>

                    <!-- Autores -->
                    <p class="text-sm text-indigo-600 font-medium mt-1">
                        <template x-if="book.authors && book.authors.length > 0">
                            <span x-text="book.authors.map(a => `${a.name} ${a.last_name}`).join(', ')"></span>
                        </template>
                        <template x-if="!book.authors || book.authors.length === 0">
                            <span class="text-slate-400 italic">Autor desconocido</span>
                        </template>
                    </p>

                    <!-- ISBN -->
                    <p class="text-xs text-slate-400 mt-1" x-show="book.isbn">
                        ISBN: <span x-text="book.isbn"></span>
                    </p>

                    <!-- Descripción -->
                    <p class="text-sm text-slate-600 mt-3 line-clamp-3" x-text="book.description || 'Sin descripción disponible.'"></p>
                </div>

                <!-- Footer de Tarjeta: Disponibilidad -->
                <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span 
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold"
                        :class="book.is_available ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'"
                        x-text="book.is_available ? `Disponible (${book.available_stock}/${book.stock})` : 'Agotado'"
                    ></span>

                    <a :href="`{{ route('loans.create') }}?book_slug=${book.slug}`" target="_self" class="text-xs font-medium text-slate-500 hover:text-indigo-600">
                        Registrar préstamo
                    </a>
                </div>
            </div>
        </template>
    </div>

    <!-- Controles de Paginación -->
    <div x-show="!loading && meta.last_page > 1" x-cloak class="mt-8 flex items-center justify-between bg-white px-4 py-3 rounded-xl border border-slate-200">
        <button 
            @click="changePage(meta.current_page - 1)" 
            :disabled="meta.current_page <= 1"
            class="px-4 py-2 text-sm font-medium rounded-lg border border-slate-300 disabled:opacity-40 hover:bg-slate-50"
        >
            ← Anterior
        </button>

        <span class="text-sm text-slate-600">
            Página <strong x-text="meta.current_page"></strong> de <strong x-text="meta.last_page"></strong>
        </span>

        <button 
            @click="changePage(meta.current_page + 1)" 
            :disabled="meta.current_page >= meta.last_page"
            class="px-4 py-2 text-sm font-medium rounded-lg border border-slate-300 disabled:opacity-40 hover:bg-slate-50"
        >
            Siguiente →
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
function booksCatalog() {
    return {
        books: [],
        meta: {},
        search: '',
        sortBy: 'title',
        onlyAvailable: false,
        page: 1,
        loading: false,
        error: null,
        controller: null,

        resetAndFetch() {
            this.page = 1;
            this.fetchBooks();
        },

        changePage(newPage) {
            if (newPage < 1 || newPage > this.meta.last_page) return;
            this.page = newPage;
            this.fetchBooks();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        async fetchBooks() {
            if (this.controller) {
                this.controller.abort();
            }
            this.controller = new AbortController();

            this.loading = true;
            this.error = null;

            const params = new URLSearchParams({
                page: this.page,
                sort_by: this.sortBy,
                direction: this.sortBy === 'created_at' || this.sortBy === 'available_stock' ? 'desc' : 'asc'
            });

            if (this.search.trim() !== '') {
                params.append('search', this.search.trim());
            }
            if (this.onlyAvailable) {
                params.append('available', '1');
            }

            const token = localStorage.getItem('api_token');
            const headers = {
                'Accept': 'application/json'
            };

            if (token) {
                headers['Authorization'] = `Bearer ${token}`;
            }

            try {
                const response = await fetch(`/api/books?${params.toString()}`, {
                    headers,
                    signal: this.controller.signal
                });

                if (response.status === 401) {
                    localStorage.removeItem('api_token');
                    localStorage.removeItem('user_name');
                    window.location.href = '{{ route('login.view') }}';
                    return;
                }

                if (!response.ok) {
                    throw new Error(`Error HTTP ${response.status} al consultar el catálogo.`);
                }

                const json = await response.json();
                this.books = json.data;
                this.meta = json.meta;
            } catch (err) {
                if (err.name !== 'AbortError') {
                    this.error = err.message || 'No se pudo conectar con la API.';
                }
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endpush