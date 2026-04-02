let ws = null;
let reconnectAttempts = 0;
const maxReconnectAttempts = 5;
const reconnectDelay = 2000;

// DOM Elements
const messagesContainer = document.getElementById('messagesContainer');
const chatForm = document.getElementById('chatForm');
const messageInput = document.getElementById('messageInput');
const sendButton = document.getElementById('sendButton');
const connectionStatus = document.getElementById('connectionStatus');

// Initialize WebSocket connection
function connect() {
    const protocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:';
    const wsUrl = `${protocol}//${window.location.host}/ws`;
    
    ws = new WebSocket(wsUrl);
    
    ws.onopen = () => {
        console.log('WebSocket connected');
        updateConnectionStatus('connected');
        reconnectAttempts = 0;
    };
    
    ws.onmessage = (event) => {
        const message = JSON.parse(event.data);
        handleIncomingMessage(message);
    };
    
    ws.onerror = (error) => {
        console.error('WebSocket error:', error);
        updateConnectionStatus('error');
    };
    
    ws.onclose = () => {
        console.log('WebSocket disconnected');
        updateConnectionStatus('disconnected');
        attemptReconnect();
    };
}

// Reconnection logic
function attemptReconnect() {
    if (reconnectAttempts < maxReconnectAttempts) {
        reconnectAttempts++;
        console.log(`Attempting to reconnect (${reconnectAttempts}/${maxReconnectAttempts})...`);
        setTimeout(connect, reconnectDelay);
    } else {
        console.error('Max reconnection attempts reached');
        updateConnectionStatus('failed');
    }
}

// Update connection status UI
function updateConnectionStatus(status) {
    const statusDot = connectionStatus.querySelector('.status-dot');
    const statusText = connectionStatus.querySelector('.status-text');
    
    statusDot.className = 'status-dot';
    
    switch (status) {
        case 'connected':
            statusDot.classList.add('connected');
            statusText.textContent = 'Connected';
            break;
        case 'disconnected':
            statusDot.classList.add('disconnected');
            statusText.textContent = 'Disconnected';
            break;
        case 'error':
            statusDot.classList.add('disconnected');
            statusText.textContent = 'Connection Error';
            break;
        case 'failed':
            statusDot.classList.add('disconnected');
            statusText.textContent = 'Connection Failed';
            break;
        default:
            statusText.textContent = 'Connecting...';
    }
}

// Handle incoming messages
function handleIncomingMessage(message) {
    // Remove welcome message if it exists
    const welcomeMessage = document.querySelector('.welcome-message');
    if (welcomeMessage) {
        welcomeMessage.remove();
    }
    
    // Remove typing indicator if it exists
    const typingIndicator = document.querySelector('.typing-indicator');
    if (typingIndicator) {
        typingIndicator.closest('.message').remove();
    }
    
    if (message.type === 'assistant' || message.type === 'error') {
        addMessage(message.type, message.message, message.sql);
    }
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
    messageText.textContent = text;
    
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

// Add typing indicator
function addTypingIndicator() {
    const messageDiv = document.createElement('div');
    messageDiv.className = 'message assistant';
    
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

// Send message
function sendMessage(text) {
    if (!text.trim() || !ws || ws.readyState !== WebSocket.OPEN) {
        return;
    }
    
    // Add user message to chat
    addMessage('user', text);
    
    // Add typing indicator
    addTypingIndicator();
    
    // Send to server
    const message = {
        type: 'user',
        message: text
    };
    
    ws.send(JSON.stringify(message));
    
    // Clear input
    messageInput.value = '';
    messageInput.style.height = 'auto';
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

// Initialize connection on page load
connect();
