import Message from './Message';
import ChatInput from './ChatInput';

const ChatCard = () => {
    // Sample messages with different bot states to showcase animations
    const messages = [
        { id: 1, type: 'ai', text: 'Hello! How can I help you today?', botState: 'idle' },
        { id: 2, type: 'user', text: 'Give me a information of this product?' },
        { id: 3, type: 'ai', text: 'OpenAI is an American artificial intelligence (AI) research organization founded in December 2015 and headquartered in San Francisco, California. OpenAI\'s stated mission is to develop "safe and beneficial" artificial general intelligence, which it defines as "highly autonomous systems that outperform humans at most economically valuable work". As a leading organization in the ongoing AI boom, OpenAI is known for the GPT family of large language models, the DALL-E series of text-to-image models, and a text-to-video model named Sora. Its release of ChatGPT in November 2022 has been credited with catalyzing widespread interest in generative AI. The organization consists of the non-profit OpenAI, Inc., registered in Delaware, and its for-profit subsidiary OpenAI Global, LLC. In 2023, it was valued at $29 billion. Microsoft owns roughly 49% of OpenAI\'s equity, having invested $13 billion. It also provides computing resources to OpenAI through its cloud service, Microsoft Azure. In 2024, OpenAI announced a partnership with Broadcom and TSMC to build custom AI chips and plans to establish a network of factories, known as "AI foundries", for chip production in collaboration with TSMC.', botState: 'responding' },
        { id: 4, type: 'user', text: 'How does it\'s work?' }
    ];

    return (
        <div className="w-full max-w-3xl mx-auto glass-effect rounded-2xl shadow-glass overflow-hidden animate-float flex flex-col h-[70vh] max-h-[650px]">
            {/* Scrollable Chat Body */}
            <div className="flex-1 overflow-y-auto scrollbar-thin p-6">
                {messages.map((message) => (
                    <Message
                        key={message.id}
                        type={message.type}
                        text={message.text}
                        botState={message.botState}
                    />
                ))}
            </div>

            {/* Fixed Input Section */}
            <div className="flex-shrink-0">
                <ChatInput />
            </div>
        </div>
    );
};

export default ChatCard;
