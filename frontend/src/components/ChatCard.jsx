import { useState, useRef, useEffect } from 'react';
import Message from './Message';
import ChatInput from './ChatInput';

const ChatCard = ({ messages, onSendMessage, isLoading }) => {
    const messagesEndRef = useRef(null);

    const scrollToBottom = () => {
        messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
    };

    useEffect(() => {
        scrollToBottom();
    }, [messages]);

    return (
        <div className="w-full max-w-3xl mx-auto glass-effect rounded-2xl shadow-glass overflow-hidden animate-float flex flex-col h-[70vh] max-h-[650px]">
            {/* Scrollable Chat Body */}
            <div className="flex-1 overflow-y-auto scrollbar-thin p-6">
                {messages.length === 0 ? (
                    <div className="flex flex-col items-center justify-center h-full text-center">
                        <div className="text-6xl mb-4">🎓</div>
                        <h2 className="text-2xl font-bold text-white mb-2">Welcome to EAPCET College Advisor!</h2>
                        <p className="text-gray-300 mb-6">Ask me anything about college admissions, cutoffs, and fees</p>

                        <div className="space-y-2 w-full max-w-md">
                            <p className="text-sm text-gray-400 mb-3">Try asking:</p>
                            <button
                                onClick={() => onSendMessage('Show CSE colleges under 50000 fee')}
                                className="w-full text-left px-4 py-2 bg-teal-deep/40 border border-cyan-glow/20 rounded-lg text-sm text-gray-300 hover:border-cyan-glow/40 hover:bg-teal-deep/60 transition-all"
                            >
                                Show CSE colleges under 50000 fee
                            </button>
                            <button
                                onClick={() => onSendMessage('Rank 60000 BC-D girl CSE chances')}
                                className="w-full text-left px-4 py-2 bg-teal-deep/40 border border-cyan-glow/20 rounded-lg text-sm text-gray-300 hover:border-cyan-glow/40 hover:bg-teal-deep/60 transition-all"
                            >
                                Rank 60000 BC-D girl CSE chances
                            </button>
                            <button
                                onClick={() => onSendMessage('Average fee for government colleges')}
                                className="w-full text-left px-4 py-2 bg-teal-deep/40 border border-cyan-glow/20 rounded-lg text-sm text-gray-300 hover:border-cyan-glow/40 hover:bg-teal-deep/60 transition-all"
                            >
                                Average fee for government colleges
                            </button>
                        </div>
                    </div>
                ) : (
                    <>
                        {messages.map((message) => (
                            <Message
                                key={message.id}
                                type={message.type}
                                text={message.text}
                                data={message.data}
                                botState={message.botState}
                            />
                        ))}
                        {isLoading && (
                            <div className="flex justify-start mb-4">
                                <div className="flex items-start gap-3">
                                    <div className="flex-shrink-0 flex items-center justify-center pt-1">
                                        <div className="ai-bot">
                                            <div className="bot-face">
                                                <div className="bot-eye"></div>
                                                <div className="bot-eye"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="px-4 py-3 rounded-2xl message-gradient-ai">
                                        <p className="text-white text-sm">Thinking...</p>
                                    </div>
                                </div>
                            </div>
                        )}
                        <div ref={messagesEndRef} />
                    </>
                )}
            </div>

            {/* Fixed Input Section */}
            <div className="flex-shrink-0">
                <ChatInput onSend={onSendMessage} disabled={isLoading} />
            </div>
        </div>
    );
};

export default ChatCard;
