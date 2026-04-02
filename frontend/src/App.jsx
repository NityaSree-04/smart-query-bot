import { useState } from 'react';
import HeroSection from './components/HeroSection';
import ChatCard from './components/ChatCard';
import { sendMessage } from './services/api';
import './index.css';

function App() {
    const [messages, setMessages] = useState([]);
    const [isLoading, setIsLoading] = useState(false);

    const handleSendMessage = async (userMessage) => {
        // Add user message to chat
        const userMsg = {
            id: Date.now(),
            type: 'user',
            text: userMessage,
        };
        setMessages(prev => [...prev, userMsg]);
        setIsLoading(true);

        try {
            // Send message to API
            const response = await sendMessage(userMessage);

            // Add AI response to chat
            const aiMsg = {
                id: Date.now() + 1,
                type: 'ai',
                text: response.response || response.message || 'No response received',
                data: response.data || null,
                botState: 'responding',
            };
            setMessages(prev => [...prev, aiMsg]);
        } catch (error) {
            console.error('Error sending message:', error);

            // Add error message to chat
            const errorMsg = {
                id: Date.now() + 1,
                type: 'ai',
                text: `Error: ${error.message || 'Failed to get response. Please try again.'}`,
                botState: 'idle',
            };
            setMessages(prev => [...prev, errorMsg]);
        } finally {
            setIsLoading(false);
        }
    };

    return (
        <div className="h-screen overflow-hidden bg-gradient-dark flex items-center justify-center p-4">
            <div className="w-full max-w-5xl relative z-10">
                <HeroSection />
                <ChatCard
                    messages={messages}
                    onSendMessage={handleSendMessage}
                    isLoading={isLoading}
                />
            </div>
        </div>
    );
}

export default App;
