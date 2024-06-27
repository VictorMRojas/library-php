<x-app-layout>
    <x-slot name="header">
        <h1 id="title_text" class="font-semibold text-center text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('¡Descubre nuevas historias!') }}
        </h1>
    </x-slot>

    <div class="py-1">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-visible shadow-sm sm:rounded-lg" style="position: relative;">                
                <label for="searchInput" style="position: absolute;top: -28px;/* left: 10px; */right: 155px;color: white;">Buscador:</label>
                <input type="text" id="searchInput" placeholder="Título, autor, categoría..." style="position: absolute;right: 0px;top: -32px;font-size: small;padding: 3px;padding-left: 6px;padding-right: 6px;border-radius: 6px;background-color: #111827;color: white;">
                
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div id="carouselContainer">
                        @include('partials.book-cards', ['books' => $books])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-reservation-modal />

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var splide = new Splide('.splide', {
            perPage: 3,
            gap: 10,
            width: "auto",
            breakpoints: {
                1120: {
                    perPage: 2
                },
                800: {
                    perPage: 1
                }
            }
        });
        splide.mount();

        var searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('input', function() {
            var searchText = this.value.toLowerCase();

            fetch(`/available-books?query=${encodeURIComponent(searchText)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                var carouselContainer = document.getElementById('carouselContainer');
                carouselContainer.innerHTML = html;
                var newSplide = new Splide('.splide', {
                    perPage: 3,
                    gap: 10,
                    width: "auto",
                    breakpoints: {
                        1120: {
                            perPage: 2
                        },
                        800: {
                            perPage: 1
                        }
                    }
                });
                newSplide.mount();
            })
            .catch(error => {
                console.error('Error fetching books:', error);
            });
        });
    });
    </script>
    @endpush
</x-app-layout>
