import HeroSection from './components/HeroSection';
import ChatCard from './components/ChatCard';

function App() {
    return (
        <div className="h-screen overflow-hidden bg-gradient-dark flex items-center justify-center p-4">
            <div className="w-full max-w-5xl relative z-10">
                <HeroSection />
                <ChatCard />
            </div>
        </div>
    );
}

export default App;
