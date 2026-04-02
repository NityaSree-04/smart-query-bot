// Aichatter - Chat Application logic
const API_URL = '/Ideathon/api/chat.php';

// DOM Elements
const chatForm = document.getElementById('chatForm');
const userInput = document.getElementById('userInput');
const chatMessages = document.getElementById('chatMessages');

// State
let isLoading = false;
let lastQuestion = ''; // Track last asked question for the viewer

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    chatForm.addEventListener('submit', (e) => {
        e.preventDefault();
        handleSubmit(e);
    });
    userInput.focus();
});

// Scroll to bottom
function scrollToBottom() {
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Set loading state
function setLoading(loading) {
    isLoading = loading;
    const btn = document.querySelector('.btn-send');
    if (btn) {
        btn.disabled = loading;
        btn.style.opacity = loading ? '0.5' : '1';
    }
}

// Handle form submission
async function handleSubmit(e) {
    if (e) e.preventDefault();

    const message = userInput.value.trim();
    if (!message || isLoading) return;

    await sendMessage(message);
}

// Send message to API
async function sendMessage(message) {
    // Hide welcome message on first interaction
    const welcome = document.getElementById('welcomeMessage');
    if (welcome) welcome.style.display = 'none';

    lastQuestion = message;
    // Add user message to UI
    addMessage(message, 'user');
    userInput.value = '';

    // Show loading indicator
    const typingId = showTypingIndicator();
    setLoading(true);

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ question: message }),
        });

        if (!response.ok) throw new Error(`HTTP ${response.status}`);

        const data = await response.json();

        // Remove typing indicator
        removeTypingIndicator(typingId);

        if (data.success) {
            addMessage(data.response, 'ai', data.data, data.sql);
        } else {
            addMessage(`Error: ${data.error || 'Something went wrong'}`, 'ai');
        }
    } catch (error) {
        removeTypingIndicator(typingId);
        addMessage(`Connection error: ${error.message}`, 'ai');
    } finally {
        setLoading(false);
        userInput.focus();
    }
}

// Add message to chat display
function addMessage(text, sender, fullData = null, sql = null) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${sender}`;

    // EXACT UPLOADED ICON
    const icon = sender === 'ai' ? `
        <img src="images/bot-icon.png" alt="AI Agent" style="width: 100%; height: 100%; object-fit: contain;">
    ` : `
        <img src="images/user-icon.png" alt="User" style="width: 100%; height: 100%; object-fit: contain;">
    `;

    messageDiv.innerHTML = `
        <div class="message-wrapper">
            <div class="avatar ${sender}-avatar">${icon}</div>
            <div class="bubble ${sender}-bubble">${text}</div>
        </div>
    `;

    // Add "View All" button for large results
    if (sender === 'ai' && fullData && fullData.length > 20) {
        const bubble = messageDiv.querySelector('.bubble');
        const viewAllBtn = document.createElement('button');
        viewAllBtn.className = 'view-all-btn';
        viewAllBtn.innerHTML = `
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;">
                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                <polyline points="15 3 21 3 21 9"></polyline>
                <line x1="10" y1="14" x2="21" y2="3"></line>
            </svg>
            View All ${fullData.length} Results
        `;
        viewAllBtn.onclick = () => {
            localStorage.setItem('fullChatResults', JSON.stringify(fullData));
            localStorage.setItem('lastQuery', lastQuestion);
            localStorage.setItem('lastSQL', sql || '');
            window.open('view_results.html', '_blank');
        };
        bubble.appendChild(viewAllBtn);
    }

    chatMessages.appendChild(messageDiv);
    scrollToBottom();
}

// Show typing indicator with moving dots
function showTypingIndicator() {
    const id = 'typing-' + Date.now();
    const indicator = document.createElement('div');
    indicator.id = id;
    indicator.className = 'message ai';
    indicator.innerHTML = `
        <div class="message-wrapper">
            <div class="avatar ai-avatar">
                <img src="images/bot-icon.png" alt="AI Agent" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <div class="bubble ai-bubble typing-bubble">
                <div class="typing-dots">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            </div>
        </div>
    `;
    chatMessages.appendChild(indicator);
    scrollToBottom();
    return id;
}

function removeTypingIndicator(id) {
    const el = document.getElementById(id);
    if (el) el.remove();
}

// Scroll to bottom
function scrollToBottom() {
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Handle clicking example questions
function askQuestion(question) {
    userInput.value = question;
    handleSubmit();
}

// Make globally accessible
window.askQuestion = askQuestion;

// Set loading state
function setLoading(loading) {
    isLoading = loading;
    const btn = document.querySelector('.btn-send');
    if (btn) {
        btn.disabled = loading;
        btn.style.opacity = loading ? '0.5' : '1';
    }
}
