import AnimatedBot from './AnimatedBot';

const Message = ({ type, text, botState = 'idle' }) => {
    const isAI = type === 'ai';

    return (
        <div className={`flex ${isAI ? 'justify-start' : 'justify-end'} mb-4 animate-fade-in-up`}>
            <div className={`flex items-start gap-3 max-w-[80%] ${!isAI && 'flex-row-reverse'}`}>
                {isAI && (
                    <div className="flex-shrink-0 flex items-center justify-center pt-1">
                        <AnimatedBot state={botState} />
                    </div>
                )}
                <div className={`px-4 py-3 rounded-2xl ${isAI
                    ? 'message-gradient-ai'
                    : 'message-gradient-user'
                    }`}>
                    <p className="text-white text-sm leading-relaxed">{text}</p>
                </div>
            </div>
        </div>
    );
};

export default Message;
