<!-- resources/views/components/reservation-modal.blade.php -->

<div id="reservationModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="flex justify-end p-2">
                <button type="button" class="text-gray-400 hover:text-gray-500" onclick="closeModal()">
                    <span class="sr-only">Cerrar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="reservationForm" method="POST" action="{{ route('reservations.store') }}">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="sm:flex sm:flex-row justify-between w-full">
                            <div class="sm:w-1/2" style="width: 70%;">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">Reservar libro</h3>
                                <p class="mt-2 text-sm text-gray-500" id="modalAuthor"></p>
                                <p class="mt-2 text-sm text-gray-500" id="modalCategory"></p>
                                <p class="mt-2 text-sm text-gray-500" id="modalDescription"></p>
                            </div>
                            <div class="sm:w-1/2 sm:flex sm:justify-center sm:items-center">
                                <img id="modalImage" src="" alt="Portada del libro" class="max-w-full h-auto">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse items-center" style="gap: 20px;">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:ml-4">
                        <label for="days_reserved" class="block text-sm font-medium text-gray-700 sm:mr-2">Días</label>
                        <input type="number" name="days_reserved" id="days_reserved" class="mt-1 block w-full border-black border-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm sm:w-auto">
                    </div>
                    <button type="submit" id="submit_button" disabled class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm" style="background-color: black; margin-top: 25px;">
                        Reservar ahora
                    </button>
                </div>
                <input type="hidden" name="book_id" id="book_id">
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openModal(bookId, title, author, category, description, imageUrl) {
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalAuthor').textContent = `Autor: ${author}`;
        document.getElementById('modalCategory').textContent = `Categoría: ${category}`;
        document.getElementById('modalDescription').textContent = description;
        document.getElementById('modalImage').src = imageUrl;
        document.getElementById('book_id').value = bookId;
        document.getElementById('reservationModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('reservationModal').classList.add('hidden');
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const daysInput = document.getElementById('days_reserved');
        const submitButton = document.getElementById('submit_button');

        daysInput.addEventListener('input', function() {
            if (daysInput.value === '' || parseInt(daysInput.value) <= 0) {
                submitButton.disabled = true;
            } else {
                submitButton.disabled = false;
            }
        });
    });
</script>
@endpush
