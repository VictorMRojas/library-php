<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('¡Descubre nuevas historias!') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($books->isEmpty())
                        <p>No books available at the moment.</p>
                    @else
                        <div id="bookCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($books as $book)
                                    <div class="carousel-item @if($loop->first) active @endif">
                                        <div class="card" style="width: 18rem; margin: auto;">
                                            <img src="{{ $book->image_url }}" class="card-img-top" alt="{{ $book->title }}">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $book->title }}</h5>
                                                <h6 class="card-subtitle mb-2 text-muted">{{ $book->author }}</h6>
                                                <p class="card-text">{{ $book->description }}</p>
                                                <p class="card-text"><strong>Category: </strong>{{ $book->category }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#bookCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#bookCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
