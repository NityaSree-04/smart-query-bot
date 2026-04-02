const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || '/api';

/**
 * Send a message to the chatbot API
 * @param {string} message - The user's message
 * @returns {Promise<Object>} - The API response
 */
export async function sendMessage(message) {
    try {
        const response = await fetch(`${API_BASE_URL}/chat.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                question: message,  // PHP backend expects 'question' not 'message'
            }),
            // 10 minute timeout for Ollama first load (model loading into memory)
            signal: AbortSignal.timeout(600000),
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        return data;
    } catch (error) {
        if (error.name === 'TimeoutError') {
            throw new Error('The AI is taking longer than expected. This usually happens on first use when the model is loading. Please try again - it will be much faster next time!');
        }
        throw error;
    }
}

