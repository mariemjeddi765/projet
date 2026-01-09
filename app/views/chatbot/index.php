<?php require_once "../app/views/layout/header.php"; ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Assistant CastleCo</h5>
                <div class="messages" id="messages" style="height:300px;overflow-y:auto;border:1px solid #ddd;padding:10px;border-radius:4px;background:#f9f9f9;margin-bottom:10px"></div>
                <div class="input-group">
                    <input type="text" id="userInput" class="form-control" placeholder="Écrivez votre message...">
                    <button id="sendBtn" class="btn btn-primary">Envoyer</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "../app/views/layout/footer.php"; ?>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const messagesContainer = document.getElementById('messages');
    const inputField = document.getElementById('userInput');
    const sendButton = document.getElementById('sendBtn');

    function addMessage(content, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'message ' + sender;
        messageDiv.textContent = content;
        if (sender === 'user') messageDiv.style.textAlign = 'right';
        messagesContainer.appendChild(messageDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    async function sendMessage() {
        const userMessage = inputField.value.trim();
        if (!userMessage) return;
        addMessage(userMessage, 'user');
        inputField.value = '';

        try {
            const response = await fetch('index.php?page=chatbot&action=api', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ message: userMessage })
            });

            if (!response.ok) throw new Error('Erreur serveur');
            const data = await response.json();
            addMessage(data.response || 'Pas de réponse', 'bot');
        } catch (err) {
            console.error(err);
            addMessage('Erreur de communication avec le serveur.', 'bot');
        }
    }

    sendButton.addEventListener('click', sendMessage);
    inputField.addEventListener('keypress', (e) => { if (e.key === 'Enter') sendMessage(); });
});
</script>
