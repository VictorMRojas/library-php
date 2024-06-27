@props(['books'])

<div class="carousel-container">
    <section class="splide">
        <div class="splide__track">
            <ul class="splide__list">
                @foreach($books as $book)
                    <x-book-card :book="$book"/>
                @endforeach
            </ul>
        </div>
    </section>
</div>