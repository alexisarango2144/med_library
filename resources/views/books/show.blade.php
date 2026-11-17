@extends('layouts.app')

@section('title', 'Detalle del Libro')

@section('content')
<div class="max-w-4xl mx-auto" x-data="bookDetail('{{ $slug }}')" x-init="fetchBook()">

    <!-- Navegación Superior -->
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('books.catalog') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-600 hover:text-indigo-600 transition">
            <span>←</span>
            <span>Volver al catálogo</span>
        </a>

        <span x-show="!loading && book" x-cloak class="text-xs text-slate-400">
            ID #<span x-text="book?.id"></span>
        </span>
    </div>

    <!-- Estado de Carga (Skeleton) -->
    <div x-show="loading" class="bg-white rounded-xl border border-slate-200 p-6 sm:p-8 animate-pulse space-y-6">
        <div class="flex justify-between items-start gap-4">
            <div class="space-y-3 w-2/3">
                <div class="h-7 bg-slate-200 rounded w-3/4"></div>
                <div class="h-4 bg-slate-200 rounded w-1/2"></div>
            </div>
            <div class="h-7 bg-slate-200 rounded-full w-28"></div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 py-4 border-y border-slate-100">
            <div class="h-12 bg-slate-100 rounded"></div>
            <div class="h-12 bg-slate-100 rounded"></div>
            <div class="h-12 bg-slate-100 rounded"></div>
            <div class="h-12 bg-slate-100 rounded"></div>
        </div>
        <div class="space-y-2">
            <div class="h-4 bg-slate-200 rounded w-full"></div>
            <div class="h-4 bg-slate-200 rounded w-5/6"></div>
            <div class="h-4 bg-slate-200 rounded w-4/6"></div>
        </div>
    </div>

    <!-- Error / Libro No Encontrado -->
    <div x-show="!loading && error" x-cloak class="bg-white rounded-xl border border-rose-200 p-8 text-center">
        <p class="text-rose-600 font-semibold text-base" x-text="error"></p>
        <p class="text-sm text-slate-500 mt-1">Verifica que el enlace sea correcto o regresa al listado general.</p>
        <a href="{{ route('books.catalog') }}" class="inline-block mt-4 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
            Ir al catálogo
        </a>
    </div>

    <!-- Ficha Detallada del Libro -->
    <template x-if="!loading && book">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            
            <!-- Cabecera Principal -->
            <div class="p-6 sm:p-8 border-b border-slate-100">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <span 
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                                :class="book.is_available ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'"
                                x-text="book.is_available ? `Disponible (${book.available_stock} de ${book.stock})` : 'Sin ejemplares disponibles'"
                            ></span>
                            <span x-show="book.publication_year" class="text-xs font-medium bg-slate-100 text-slate-600 px-2.5 py-1 rounded-full">
                                Publicado en <span x-text="book.publication_year"></span>
                            </span>
                        </div>

                        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mt-3 leading-tight" x-text="book.title"></h1>

                        <!-- Lista de Autores -->
                        <div class="mt-2 flex flex-wrap items-center gap-2 text-sm">
                            <span class="text-slate-500">Por:</span>
                            <template x-if="book.authors && book.authors.length > 0">
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="author in book.authors" :key="author.id">
                                        <span class="inline-flex items-center gap-1 bg-indigo-50 text-indigo-700 font-medium px-2.5 py-0.5 rounded-md">
                                            <span x-text="`${author.name} ${author.last_name}`"></span>
                                            <span x-show="author.nationality" class="text-xs text-indigo-400" x-text="`(${author.nationality})`"></span>
                                        </span>
                                    </template>
                                </div>
                            </template>
                            <template x-if="!book.authors || book.authors.length === 0">
                                <span class="text-slate-400 italic">Autor no registrado</span>
                            </template>
                        </div>
                    </div>

                    <!-- Botón de Acción Principal (Prestar Libro) -->
                    <div class="shrink-0">
                        <template x-if="isAuthenticated && book.is_available">
                            <a 
                                :href="`{{ route('loans.create') }}?book_slug=${book.slug}`"
                                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition w-full sm:w-auto"
                            >
                                <span>Registrar Préstamo</span>
                                <span>→</span>
                            </a>
                        </template>

                        <template x-if="!isAuthenticated && book.is_available">
                            <a 
                                href="{{ route('login.view') }}"
                                class="inline-flex items-center justify-center px-4 py-2.5 border border-indigo-200 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-sm font-medium rounded-lg transition w-full sm:w-auto"
                            >
                                Inicia sesión para prestar
                            </a>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Metadatos Técnicos -->
            <div class="grid grid-cols-2 sm:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-slate-100 bg-slate-50/70 border-b border-slate-100">
                <div class="p-4 sm:px-6">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">ISBN</p>
                    <p class="text-sm font-mono font-medium text-slate-800 mt-1" x-text="book.isbn || 'No registrado'"></p>
                </div>
                <div class="p-4 sm:px-6">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Páginas</p>
                    <p class="text-sm font-medium text-slate-800 mt-1" x-text="book.page_count ? `${book.page_count} págs.` : 'N/D'"></p>
                </div>
                <div class="p-4 sm:px-6">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Inventario Total</p>
                    <p class="text-sm font-medium text-slate-800 mt-1" x-text="`${book.stock} ejemplares`"></p>
                </div>
                <div class="p-4 sm:px-6">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Disponibles</p>
                    <p class="text-sm font-semibold mt-1" 
                       :class="book.available_stock > 0 ? 'text-emerald-600' : 'text-rose-600'"
                       x-text="`${book.available_stock} ejemplares`">
                    </p>
                </div>
            </div>

            <!-- Sinopsis / Descripción -->
            <div class="p-6 sm:p-8">
                <h2 class="text-sm uppercase tracking-wider text-slate-400 font-semibold mb-3">Descripción / Sinopsis</h2>
                <p class="text-slate-700 text-sm sm:text-base leading-relaxed whitespace-pre-line" 
                   x-text="book.description || 'Este libro aún no cuenta con una descripción registrada en el catálogo.'">
                </p>
            </div>

            <!-- Sección Detallada de Autores (si tienen biografía) -->
            <template x-if="book.authors && book.authors.some(a => a.biography)">
                <div class="p-6 sm:p-8 bg-slate-50 border-t border-slate-100 space-y-4">
                    <h2 class="text-sm uppercase tracking-wider text-slate-400 font-semibold">Sobre los Autores</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <template x-for="author in book.authors" :key="author.id">
                            <div class="bg-white p-4 rounded-lg border border-slate-200">
                                <p class="font-bold text-slate-900 text-sm" x-text="`${author.name} ${author.last_name}`"></p>
                                <p class="text-xs text-slate-400 mt-0.5" x-show="author.nationality" x-text="author.nationality"></p>
                                <p class="text-xs text-slate-600 mt-2 leading-relaxed" x-text="author.biography || 'Sin biografía disponible.'"></p>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

        </div>
    </template>
</div>
@endsection

@push('scripts')
<script>
function bookDetail(slug) {
    return {
        slug: slug,
        book: null,
        loading: true,
        error: null,
        isAuthenticated: Boolean(localStorage.getItem('api_token')),

        async fetchBook() {
            this.loading = true;
            this.error = null;

            const token = localStorage.getItem('api_token');
            const headers = {
                'Accept': 'application/json'
            };

            if (token) {
                headers['Authorization'] = `Bearer ${token}`;
            }

            try {
                const response = await fetch(`/api/books/${encodeURIComponent(this.slug)}`, {
                    headers
                });

                if (response.status === 401) {
                    localStorage.removeItem('api_token');
                    localStorage.removeItem('user_name');
                    window.location.href = '{{ route('login.view') }}';
                    return;
                }

                if (response.status === 404) {
                    this.error = 'El libro solicitado no existe o fue retirado del catálogo.';
                    return;
                }

                if (!response.ok) {
                    throw new Error(`Error HTTP ${response.status} al cargar el libro.`);
                }

                const json = await response.json();
                this.book = json.data ?? json;
                document.title = `${this.book.title} — Med Library`;
            } catch (err) {
                this.error = err.message || 'No se pudo conectar con el servidor.';
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endpush