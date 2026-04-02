const API_BASE = 'api/';

// DOM Elements
const messagesContainer = document.getElementById('messagesContainer');
const chatForm = document.getElementById('chatForm');
const messageInput = document.getElementById('messageInput');
const sendButton = document.getElementById('sendButton');
const connectionStatus = document.getElementById('connectionStatus');

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    checkHealth();
});

// Check API health
async function checkHealth() {
    try {
        const response = await fetch(API_BASE + 'health.php');
        const data = await response.json();

        if (data.success) {
            updateConnectionStatus('connected', 'Ready');
        } else {
            updateConnectionStatus('error', 'API Error');
        }
    } catch (error) {
        updateConnectionStatus('error', 'Connection Failed');
        console.error('Health check failed:', error);
    }
}

// Update connection status UI
function updateConnectionStatus(status, text) {
    const statusDot = connectionStatus.querySelector('.status-dot');
    const statusText = connectionStatus.querySelector('.status-text');

    statusDot.className = 'status-dot';

    switch (status) {
        case 'connected':
            statusDot.classList.add('connected');
            break;
        case 'error':
        case 'disconnected':
            statusDot.classList.add('disconnected');
            break;
    }

    statusText.textContent = text;
}

// Send message
async function sendMessage(text) {
    if (!text.trim()) {
        return;
    }

    // Remove welcome message if it exists
    const welcomeMessage = document.querySelector('.welcome-message');
    if (welcomeMessage) {
        welcomeMessage.remove();
    }

    // Add user message to chat
    addMessage('user', text);

    // Add typing indicator
    addTypingIndicator();

    // Disable input
    messageInput.disabled = true;
    sendButton.disabled = true;

    try {
        // Create abort controller with 3-minute timeout
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 180000); // 3 minutes

        const response = await fetch(API_BASE + 'process.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                question: text
            }),
            signal: controller.signal
        });

        clearTimeout(timeoutId);
        const data = await response.json();

        // Remove typing indicator
        removeTypingIndicator();

        if (data.success) {
            // Handle both 'response' (new) and 'answer' (old) fields
            let messageText = data.response || data.answer;

            addMessage('assistant', messageText, data.sql);

            // Add "View All" button if available
            if (data.has_more && data.view_all_link) {
                addViewAllButton(data.row_count, data.view_all_link);
            }
        } else {
            addMessage('error', data.error || 'An error occurred', data.sql);
        }
    } catch (error) {
        removeTypingIndicator();

        if (error.name === 'AbortError') {
            addMessage('error', '⏱️ Query took too long (>3 minutes). This might mean:<br>• Ollama is not responding<br>• The query is too complex<br>• Try restarting Ollama: <code>taskkill /F /IM ollama.exe</code> then <code>ollama serve</code>');
        } else if (error.message.includes('JSON')) {
            addMessage('error', '❌ Server returned invalid response. This usually means a PHP error occurred.<br>• Check if Ollama is running: <code>curl http://localhost:11434/api/tags</code><br>• Try the test page: <a href="test_api_direct.html" target="_blank">test_api_direct.html</a>');
        } else {
            addMessage('error', 'Failed to communicate with server: ' + error.message);
        }
        console.error('Error:', error);
    } finally {
        // Re-enable input
        messageInput.disabled = false;
        sendButton.disabled = false;
        messageInput.focus();
    }

    // Clear input
    messageInput.value = '';
    messageInput.style.height = 'auto';
}

// Add message to chat
function addMessage(type, text, sql = null) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${type}`;

    const avatar = document.createElement('div');
    avatar.className = 'message-avatar';
    avatar.textContent = type === 'user' ? '👤' : type === 'error' ? '⚠️' : '🤖';

    const content = document.createElement('div');
    content.className = 'message-content';

    const messageText = document.createElement('div');
    messageText.className = 'message-text';
    messageText.innerHTML = text;

    content.appendChild(messageText);

    // Add SQL query if present
    if (sql) {
        const sqlDiv = document.createElement('div');
        sqlDiv.className = 'message-sql';

        const sqlLabel = document.createElement('div');
        sqlLabel.className = 'sql-label';
        sqlLabel.textContent = 'SQL Query:';

        const sqlCode = document.createElement('code');
        sqlCode.textContent = sql;

        sqlDiv.appendChild(sqlLabel);
        sqlDiv.appendChild(sqlCode);
        content.appendChild(sqlDiv);
    }

    messageDiv.appendChild(avatar);
    messageDiv.appendChild(content);

    messagesContainer.appendChild(messageDiv);
    scrollToBottom();
}

// Add "View All Results" button
function addViewAllButton(rowCount, link) {
    const buttonDiv = document.createElement('div');
    buttonDiv.className = 'message assistant';
    buttonDiv.style.marginTop = '10px';

    const avatar = document.createElement('div');
    avatar.className = 'message-avatar';
    avatar.textContent = '🤖';

    const content = document.createElement('div');
    content.className = 'message-content';

    const button = document.createElement('a');
    button.href = link;
    button.target = '_blank';
    button.className = 'view-all-button';
    button.textContent = `📊 View All ${rowCount} Results in Interactive Table`;
    button.style.cssText = 'display: inline-block; padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; border-radius: 8px; font-weight: 600; margin-top: 10px; transition: transform 0.2s;';
    button.onmouseover = function () { this.style.transform = 'scale(1.05)'; };
    button.onmouseout = function () { this.style.transform = 'scale(1)'; };

    content.appendChild(button);
    buttonDiv.appendChild(avatar);
    buttonDiv.appendChild(content);

    messagesContainer.appendChild(buttonDiv);
    scrollToBottom();
}

// Add typing indicator
function addTypingIndicator() {
    const messageDiv = document.createElement('div');
    messageDiv.className = 'message assistant typing-message';
    messageDiv.id = 'typingIndicator';

    const avatar = document.createElement('div');
    avatar.className = 'message-avatar';
    avatar.textContent = '🤖';

    const content = document.createElement('div');
    content.className = 'message-content';

    const typingDiv = document.createElement('div');
    typingDiv.className = 'typing-indicator';
    typingDiv.innerHTML = '<div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div>';

    content.appendChild(typingDiv);
    messageDiv.appendChild(avatar);
    messageDiv.appendChild(content);

    messagesContainer.appendChild(messageDiv);
    scrollToBottom();
}

// Remove typing indicator
function removeTypingIndicator() {
    const typingIndicator = document.getElementById('typingIndicator');
    if (typingIndicator) {
        typingIndicator.remove();
    }
}

// Scroll to bottom
function scrollToBottom() {
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

// Auto-resize textarea
messageInput.addEventListener('input', () => {
    messageInput.style.height = 'auto';
    messageInput.style.height = messageInput.scrollHeight + 'px';
});

// Handle form submission
chatForm.addEventListener('submit', (e) => {
    e.preventDefault();
    sendMessage(messageInput.value);
});

// Handle Enter key (Shift+Enter for new line)
messageInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        chatForm.dispatchEvent(new Event('submit'));
    }
});

// Send example query
function sendExample(text) {
    messageInput.value = text;
    sendMessage(text);
}
