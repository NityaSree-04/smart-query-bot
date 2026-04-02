import { SendIcon } from '../assets/icons/Icons';

const ChatInput = ({ onSend, disabled }) => {
    const handleSubmit = (e) => {
        e.preventDefault();
        const input = e.target.elements.message;
        const message = input.value.trim();
        if (message && onSend) {
            onSend(message);
            input.value = '';
        }
    };

    return (
        <div className="border-t border-cyan-glow/10 p-4 bg-teal-deep/20">
            <form onSubmit={handleSubmit} className="flex items-center gap-3">
                <input
                    type="text"
                    name="message"
                    placeholder="Ask about colleges, cutoffs, fees..."
                    disabled={disabled}
                    className="flex-1 bg-teal-deep/40 border border-cyan-glow/20 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:border-cyan-glow focus:shadow-glow-cyan transition-all duration-300 disabled:opacity-50"
                />
                <button
                    type="submit"
                    disabled={disabled}
                    className="p-3 rounded-lg bg-cyan-glow/20 border border-cyan-glow/30 text-cyan-glow hover:bg-cyan-glow/30 hover:shadow-glow-cyan transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <SendIcon />
                </button>
            </form>
        </div>
    );
};

export default ChatInput;
