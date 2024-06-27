@if($books->isEmpty())
    <p>No se encontraron libros.</p>
@else
    <x-carousel :books="$books"/>
@endif