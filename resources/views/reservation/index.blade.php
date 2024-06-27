<!-- resources/views/reservation/index.blade.php -->

<x-app-layout>
    @if($reservations->isEmpty())
        <x-slot name="header">
            <h1 id="title_text" class="font-semibold text-center text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('No tienes reservas') }}
            </h1>
        </x-slot>
    @else
        <x-slot name="header">
            <h1 id="title_text" class="font-semibold text-center text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Todas las reservaciones') }}
            </h1>
        </x-slot>

        <div class="py-1">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl" style="color: white;">
                    <table class="table-auto w-full">
                        <thead>
                            <tr style="border: 2px solid white;">
                                <th class="px-2 py-2">Libro</th>
                                <th class="px-4 py-2">Fecha de reserva</th>
                                <th class="px-4 py-2">Días reservados</th>
                                <th class="px-4 py-2">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservations as $reservation)
                                <tr>
                                    <td class="border px-2 py-2 text-center">{{ $reservation->book->title }}</td>
                                    <td class="border px-4 py-2 text-center">{{ $reservation->reservation_date }}</td>
                                    <td class="border px-4 py-2 text-center">{{ $reservation->days_reserved }}</td>
                                    <td class="border px-4 py-2 text-center">
                                        <form action="{{ route('reservation.destroy', $reservation->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres borrar esta reserva?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded" style="background-color: red;">Borrar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    @endif
</x-app-layout>
