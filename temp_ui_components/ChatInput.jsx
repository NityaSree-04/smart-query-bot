import { SendIcon } from '../assets/icons/Icons';

const ChatInput = () => {
    return (
        <div className="border-t border-cyan-glow/10 p-4 bg-teal-deep/20">
            <div className="flex items-center gap-3">
                <input
                    type="text"
                    placeholder="Enter Your Message..."
                    className="flex-1 bg-teal-deep/40 border border-cyan-glow/20 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:border-cyan-glow focus:shadow-glow-cyan transition-all duration-300"
                />
                <button className="p-3 rounded-lg bg-cyan-glow/20 border border-cyan-glow/30 text-cyan-glow hover:bg-cyan-glow/30 hover:shadow-glow-cyan transition-all duration-300">
                    <SendIcon />
                </button>
            </div>
        </div>
    );
};

export default ChatInput;
