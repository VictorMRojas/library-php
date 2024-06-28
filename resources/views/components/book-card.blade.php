@props(['book'])

<li class="splide__slide">
    <div class="card flex flex-col justify-between h-full bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-lg" style="width: 20rem; margin: auto;">
            <img src="{{ $book->image_url }}" class="card-img-top rounded-t-lg" alt="{{ $book->title }}" 
            style="height: 400px; object-fit: cover; width: 100%;">
            <div class="card-body flex flex-col flex-grow">
                <h3 class="card-title text-lg font-bold">{{ $book->title }}</h3>
                <p class="card-subtitle mb-2 text-sm text-gray-500">Autor: {{ $book->author }}</p>
                <p class="card-text">
                    <span class="inline-block text-white text-xs font-semibold rounded-full px-2 py-1" style="background-color: #515A69;">
                        {{ $book->category }}
                    </span>
                </p>
                <p class="card-text">{{ $book->description }}</p>
            </div>
        <div class="mt-auto flex justify-center">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" 
            style="background-color: royalblue; margin-top: 5px;"
            onclick="openReservationModal('{{ $book->id }}', '{{ $book->title }}', '{{ $book->author }}', '{{ $book->category }}', '{{ $book->description }}', '{{ $book->image_url }}')">
            📚 Reservar
            </button>
        </div>
    </div>
</li>
