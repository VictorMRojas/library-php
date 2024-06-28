<div id="chatModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="flex justify-end p-2">
                <button type="button" class="text-gray-400 hover:text-gray-500" onclick="closeChatModal()">
                    <span class="sr-only">Cerrar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-4" style="padding-top: 5px;">
                <span class="text-gray-500">🤖 Asistente de libros</span>

                <div id="messages" class="messages overflow-y-auto h-64 border rounded p-2 mb-4"></div>
                <div class="flex">
                    <input type="text" id="messageInput" class="flex-grow border rounded p-2" placeholder="Escribe un mensaje">
                    <button id="sendMessageButton" class="bg-blue-500 text-white p-2 ml-2 rounded" style="background: black;">Enviar</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let conversation = [
        {
            "role": "user",
            "content": "Finge que eres un asistente virtual de una biblioteca que reserva libros para los usuarios, solo puedes entregar la información del libro, no puedes reservarle el libro al usuario, solo entregar la información, y posees la siguiente base de datos: "
        },
        {
            "role": "assistant",
            "content": "Bienvenido, soy un asistente virtual. Te puedo ayudar a encontrar el libro que buscas. ¿Qué tienes en mente?"
        }
    ];

    async function fetchBooks() {
        try {
            const response = await fetch('/api/books');
            const books = await response.json();
            const booksInfo = books.map(book => `Título: ${book.title}, Autor: ${book.author}, Año: ${book.year}`).join('; ');
            conversation[0].content += booksInfo;
        } catch (error) {
            console.error('Error fetching books:', error);
        }
    }

    function openChatModal() {
        const chatModal = document.getElementById('chatModal');
        const messages = document.getElementById('messages');
        
        chatModal.classList.remove('hidden');

        if (messages.children.length === 0) {
            appendMessage('received', conversation[1].content);
        }

        if (conversation.length === 2) {
            fetchBooks();
        }
    }

    function closeChatModal() {
        document.getElementById('chatModal').classList.add('hidden');
    }

    async function sendMessage() {
        const messageInput = document.getElementById('messageInput');
        const messages = document.getElementById('messages');

        if (messageInput.value === '') {
            return;
        }

        const userMessage = messageInput.value;
        appendMessage('sent', userMessage);
        conversation.push({
            "role": "user",
            "content": userMessage
        });

        messageInput.value = '';

        try {
            const response = await fetch('https://nexra.aryahcr.cc/api/chat/gpt', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    "messages": conversation,
                    "prompt": userMessage,
                    "model": "GPT-4",
                    "markdown": false
                })
            });

            const data = await response.json();
            const assistantMessage = data.gpt;

            appendMessage('received', assistantMessage);
            conversation.push({
                "role": "assistant",
                "content": assistantMessage
            });

        } catch (error) {
            console.error('Error:', error);
            appendMessage('received', 'Lo siento, ocurrió un error al obtener la respuesta.');
        }
    }

    function appendMessage(type, message) {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message', type);

        const contentDiv = document.createElement('div');
        contentDiv.classList.add('content');
        contentDiv.textContent = message;

        messageDiv.appendChild(contentDiv);
        document.getElementById('messages').appendChild(messageDiv);
        document.getElementById('messages').scrollTop = document.getElementById('messages').scrollHeight;
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('sendMessageButton').addEventListener('click', sendMessage);
        document.getElementById('messageInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    });
</script>
@endpush