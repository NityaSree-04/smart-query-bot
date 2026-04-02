import AnimatedBot from './AnimatedBot';

const Message = ({ type, text, botState = 'idle', data }) => {
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
                    <p className="text-white text-sm leading-relaxed whitespace-pre-wrap">{text}</p>

                    {/* Render table data if present */}
                    {data && data.length > 0 && (
                        <div className="mt-3 overflow-x-auto">
                            <table className="w-full text-xs border-collapse">
                                <thead>
                                    <tr className="border-b border-cyan-glow/20">
                                        {Object.keys(data[0]).map((key) => (
                                            <th key={key} className="px-2 py-1 text-left text-cyan-400 font-semibold">
                                                {key}
                                            </th>
                                        ))}
                                    </tr>
                                </thead>
                                <tbody>
                                    {data.map((row, idx) => (
                                        <tr key={idx} className="border-b border-cyan-glow/10">
                                            {Object.values(row).map((value, i) => (
                                                <td key={i} className="px-2 py-1 text-gray-200">
                                                    {value}
                                                </td>
                                            ))}
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
};

export default Message;
