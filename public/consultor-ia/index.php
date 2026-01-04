<?php
/**
 * AI Consultant Page
 */

$page_title = 'Consultor IA';
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="flex h-screen bg-slate-950">
    <?php require_once __DIR__ . '/../../includes/components/sidebar.php'; ?>

    <main class="flex-1 flex flex-col h-screen bg-slate-950">
        <!-- Header -->
        <div class="border-b border-slate-800 bg-slate-950/50 backdrop-blur px-8 py-4">
            <div class="flex items-center space-x-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600/20">
                    <svg class="h-6 w-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white">Consultor IA</h1>
                    <p class="text-xs text-slate-400">Experto en sistematización y automatización</p>
                </div>
            </div>
        </div>

        <!-- Chat Area -->
        <div id="chat-container" class="flex-1 overflow-y-auto p-4 space-y-4">
            <!-- Welcome Message -->
            <div class="flex justify-start">
                <div
                    class="max-w-[80%] rounded-2xl rounded-tl-none bg-indigo-600/10 px-4 py-3 text-sm text-slate-200 border border-indigo-500/20">
                    <p>Hola, soy tu Consultor de Sistematización. ¿En qué proceso de tu negocio te gustaría enfocarte
                        hoy para automatizarlo?</p>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="border-t border-slate-800 px-4 py-4 bg-slate-900/50">
            <form id="chat-form" class="relative mx-auto max-w-4xl">
                <input type="text" id="user-input" placeholder="Escribe tu consulta aquí..."
                    class="w-full rounded-xl border border-slate-700 bg-slate-800 py-3 pl-4 pr-12 text-white placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                    autocomplete="off">
                <button type="submit" id="send-btn"
                    class="absolute right-2 top-1/2 -translate-y-1/2 rounded-lg bg-indigo-600 p-1.5 text-white transition hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </form>
            <p class="mt-2 text-center text-xs text-slate-500">
                El consultor puede cometer errores. Verifica la información importante.
            </p>
        </div>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const chatContainer = document.getElementById('chat-container');
        const chatForm = document.getElementById('chat-form');
        const userInput = document.getElementById('user-input');
        const sendBtn = document.getElementById('send-btn');

        let messages = [];

        // Auto-scroll to bottom
        function scrollToBottom() {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        // Add message to UI
        function appendMessage(role, content) {
            const div = document.createElement('div');
            div.className = `flex ${role === 'user' ? 'justify-end' : 'justify-start'}`;

            const bubble = document.createElement('div');
            bubble.className = role === 'user'
                ? 'max-w-[80%] rounded-2xl rounded-tr-none bg-indigo-600 px-4 py-3 text-sm text-white'
                : 'max-w-[80%] rounded-2xl rounded-tl-none bg-indigo-600/10 px-4 py-3 text-sm text-slate-200 border border-indigo-500/20';

            // Simple markdown parsing (bold and newlines)
            let formattedContent = content
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\n/g, '<br>');

            bubble.innerHTML = formattedContent;

            div.appendChild(bubble);
            chatContainer.appendChild(div);
            scrollToBottom();
        }

        // Handle submit
        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const text = userInput.value.trim();
            if (!text) return;

            // UI Updates
            userInput.value = '';
            userInput.disabled = true;
            sendBtn.disabled = true;

            // Add user message
            appendMessage('user', text);
            messages.push({ role: 'user', content: text });

            // Add loading indicator
            const loadingDiv = document.createElement('div');
            loadingDiv.className = 'flex justify-start';
            loadingDiv.id = 'loading-indicator';
            loadingDiv.innerHTML = `
            <div class="max-w-[80%] rounded-2xl rounded-tl-none bg-slate-800 px-4 py-3 text-sm text-slate-400">
                <div class="flex space-x-1">
                    <div class="h-2 w-2 animate-bounce rounded-full bg-slate-500"></div>
                    <div class="h-2 w-2 animate-bounce rounded-full bg-slate-500 delay-75"></div>
                    <div class="h-2 w-2 animate-bounce rounded-full bg-slate-500 delay-150"></div>
                </div>
            </div>
        `;
            chatContainer.appendChild(loadingDiv);
            scrollToBottom();

            try {
                const response = await fetch('/api/chat.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ messages })
                });

                if (!response.ok) throw new Error('API Error');

                const data = await response.json();

                // Remove loading
                document.getElementById('loading-indicator').remove();

                // Add AI response
                appendMessage('assistant', data.content);
                messages.push({ role: 'assistant', content: data.content });

            } catch (error) {
                console.error(error);
                document.getElementById('loading-indicator').remove();
                appendMessage('assistant', 'Lo siento, hubo un error al procesar tu mensaje. Por favor intenta de nuevo.');
            } finally {
                userInput.disabled = false;
                sendBtn.disabled = false;
                userInput.focus();
            }
        });
    });
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>