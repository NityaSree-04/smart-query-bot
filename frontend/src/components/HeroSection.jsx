const HeroSection = () => {
    return (
        <div className="text-center mb-12 animate-fade-in-up">
            <h1 className="text-4xl md:text-5xl font-bold mb-4 text-white animate-title-glow">
                <span className="text-cyan-glow animate-pulse-glow">EAPCET College Advisor</span>
            </h1>
            <p className="text-lg text-gray-300 mb-6 animate-text-shimmer">
                Transform <span className="text-cyan-400 font-semibold">natural language</span> into powerful{' '}
                <span className="text-cyan-400 font-semibold">college insights</span> instantly
            </p>
        </div>
    );
};

export default HeroSection;
