<!-- resources/views/reservation/index.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reservations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">User</th>
                            <th class="px-4 py-2">Book</th>
                            <th class="px-4 py-2">Reservation Date</th>
                            <th class="px-4 py-2">Days Reserved</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservations as $reservation)
                            <tr>
                                <td class="border px-4 py-2">{{ $reservation->user->name }}</td>
                                <td class="border px-4 py-2">{{ $reservation->book->title }}</td>
                                <td class="border px-4 py-2">{{ $reservation->reservation_date }}</td>
                                <td class="border px-4 py-2">{{ $reservation->days_reserved }}</td>
                                <td class="border px-4 py-2">
                                    <form action="{{ route('reservation.destroy', $reservation->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this reservation?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
