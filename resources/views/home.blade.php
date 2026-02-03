<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Home</title>
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            overflow: hidden;
            height: 100vh;
            width: 100vw;
        }
        
        .slideshow-container {
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }
        
        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        
        .slide.active {
            opacity: 1;
            z-index: 1;
        }
        
        .slide-content {
            max-width: 90%;
            width: 100%;
            padding: 2rem;
            text-align: center;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            z-index: 2;
        }
        
        .slide-content h1 {
            font-size: clamp(2rem, 8vw, 6rem);
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.2;
        }
        
        .slide-content h2 {
            font-size: clamp(1.5rem, 5vw, 3rem);
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .slide-content p {
            font-size: clamp(1rem, 3vw, 1.5rem);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }
        
        .slide-content .btn {
            display: inline-block;
            padding: 1rem 2rem;
            background: rgba(255, 255, 255, 0.9);
            color: #1b1b18;
            text-decoration: none;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: clamp(0.875rem, 2vw, 1.125rem);
            transition: all 0.3s ease;
            margin: 0.5rem;
        }
        
        .slide-content .btn:hover {
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }
        
        .slide-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
            z-index: 1;
        }
        
        /* Navigation Dots */
        .dots-container {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 0.75rem;
            z-index: 10;
        }
        
        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid rgba(255, 255, 255, 0.8);
        }
        
        .dot.active {
            background: white;
            transform: scale(1.2);
        }
        
        .dot:hover {
            background: rgba(255, 255, 255, 0.8);
        }
        
        /* Navigation Arrows */
        .prev, .next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 1.5rem 1rem;
            cursor: pointer;
            font-size: 2rem;
            font-weight: bold;
            z-index: 10;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .prev {
            left: 1rem;
            border-radius: 0 0.5rem 0.5rem 0;
        }
        
        .next {
            right: 1rem;
            border-radius: 0.5rem 0 0 0.5rem;
        }
        
        .prev:hover, .next:hover {
            background: rgba(255, 255, 255, 0.4);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .prev, .next {
                padding: 1rem 0.75rem;
                font-size: 1.5rem;
            }
            
            .prev {
                left: 0.5rem;
            }
            
            .next {
                right: 0.5rem;
            }
            
            .dots-container {
                bottom: 1rem;
            }
        }
        
        /* Content styles for different slide types */
        .slide-content.text-center {
            text-align: center;
        }
        
        .slide-content.text-left {
            text-align: left;
            max-width: 1200px;
        }
        
        .slide-content.text-right {
            text-align: right;
            max-width: 1200px;
        }
        
        .slide-content.dark-text {
            color: #1b1b18;
            text-shadow: 2px 2px 4px rgba(255, 255, 255, 0.5);
        }
        
        .slide-content.dark-text .btn {
            background: #1b1b18;
            color: white;
        }
        
        .slide-content.dark-text .btn:hover {
            background: #000;
        }
        
        /* Speech Bubble Styles for Curhat Anon - frames fit content, each can have different size */
        .curhats-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-auto-rows: auto;
            gap: 1rem;
            width: 96%;
            max-width: 96%;
            max-height: 96%;
            padding: 1%;
            z-index: 2;
            overflow: auto;
            align-content: start;
            align-items: start;
            justify-content: center;
        }
        
        /* Column count by item count: 1–2 items = same cols, 3 = 3 cols, 4–12 = 4 cols */
        .curhats-container.curhats-count-1 {
            grid-template-columns: 1fr;
            grid-auto-rows: auto;
        }
        
        .curhats-container.curhats-count-2 {
            grid-template-columns: repeat(2, 1fr);
            grid-auto-rows: auto;
        }
        
        .curhats-container.curhats-count-3 {
            grid-template-columns: repeat(3, 1fr);
            grid-auto-rows: auto;
        }
        
        .curhats-container.curhats-count-4,
        .curhats-container.curhats-count-5,
        .curhats-container.curhats-count-6,
        .curhats-container.curhats-count-7,
        .curhats-container.curhats-count-8,
        .curhats-container.curhats-count-9,
        .curhats-container.curhats-count-10,
        .curhats-container.curhats-count-11,
        .curhats-container.curhats-count-12 {
            grid-template-columns: repeat(4, 1fr);
            grid-auto-rows: auto;
        }
        
        .speech-bubble {
            position: relative;
            background: white;
            border: 2px solid #1b1b18;
            border-radius: 1vw;
            padding: 1rem;
            box-shadow: 0 0.3vw 0.8vw rgba(0, 0, 0, 0.15);
            display: flex;
            flex-direction: column;
            min-height: fit-content;
            height: auto;
        }
        
        .speech-bubble .quote-start {
            position: absolute;
            top: 2%;
            left: 3%;
            font-size: 4vw;
            font-weight: 900;
            color: #1b1b18;
            line-height: 1;
            font-family: Georgia, serif;
            opacity: 0.4;
            z-index: 1;
        }
        
        .speech-bubble .quote-end {
            position: absolute;
            bottom: 2%;
            right: 3%;
            font-size: 4vw;
            font-weight: 900;
            color: #1b1b18;
            line-height: 1;
            font-family: Georgia, serif;
            opacity: 0.4;
            z-index: 1;
        }
        
        .speech-bubble .bubble-heading {
            font-size: clamp(0.6rem, 1.8vw, 1.25rem);
            font-weight: 700;
            text-transform: uppercase;
            color: #1b1b18;
            margin-bottom: 0.5rem;
            letter-spacing: 0.05em;
            position: relative;
            z-index: 2;
            text-align: center;
            flex-shrink: 0;
        }
        
        .speech-bubble .bubble-content {
            font-size: clamp(0.5rem, 1.2vw, 1rem);
            line-height: 1.5;
            color: #1b1b18;
            position: relative;
            z-index: 2;
            text-align: justify;
            flex: 0 1 auto;
        }
        
        /* Color variations for speech bubbles - matching image colors */
        .speech-bubble.color-1 {
            background: #FFE066;
            border-color: #1b1b18;
        }
        
        .speech-bubble.color-2 {
            background: #A8E6CF;
            border-color: #1b1b18;
        }
        
        .speech-bubble.color-3 {
            background: #FFD3B6;
            border-color: #1b1b18;
        }
        
        .speech-bubble.color-4 {
            background: #C7CEEA;
            border-color: #1b1b18;
        }
        
        .speech-bubble.color-5 {
            background: #FFAAA5;
            border-color: #1b1b18;
        }
        
        .speech-bubble.color-6 {
            background: #FFB6C1;
            border-color: #1b1b18;
        }
        
        .speech-bubble.color-7 {
            background: #B5EAD7;
            border-color: #1b1b18;
        }
        
        .speech-bubble.color-8 {
            background: #FFD93D;
            border-color: #1b1b18;
        }
        
        .speech-bubble.color-9 {
            background: #95E1D3;
            border-color: #1b1b18;
        }
        
        /* Add tail/pointer to speech bubble - percentage-based */
        .speech-bubble::after {
            content: '';
            position: absolute;
            bottom: -1vw;
            left: 15%;
            width: 0;
            height: 0;
            border-left: 1vw solid transparent;
            border-right: 1vw solid transparent;
            border-top: 1vw solid;
            z-index: 0;
        }
        
        .speech-bubble.color-1::after { border-top-color: #FFE066; }
        .speech-bubble.color-2::after { border-top-color: #A8E6CF; }
        .speech-bubble.color-3::after { border-top-color: #FFD3B6; }
        .speech-bubble.color-4::after { border-top-color: #C7CEEA; }
        .speech-bubble.color-5::after { border-top-color: #FFAAA5; }
        .speech-bubble.color-6::after { border-top-color: #FFB6C1; }
        .speech-bubble.color-7::after { border-top-color: #B5EAD7; }
        .speech-bubble.color-8::after { border-top-color: #FFD93D; }
        .speech-bubble.color-9::after { border-top-color: #95E1D3; }
        
        /* Border for tail */
        .speech-bubble::before {
            content: '';
            position: absolute;
            bottom: -1.1vw;
            left: calc(15% - 0.1vw);
            width: 0;
            height: 0;
            border-left: 1.1vw solid transparent;
            border-right: 1.1vw solid transparent;
            border-top: 1.1vw solid #1b1b18;
            z-index: -1;
        }
        
        @media (max-width: 768px) {
            .curhats-container {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 1.5%;
                padding: 1.5%;
            }
            
            .curhats-container:has(.speech-bubble:nth-child(1):nth-last-child(1)) {
                grid-template-columns: 1fr !important;
            }
            
            .speech-bubble .quote-start,
            .speech-bubble .quote-end {
                font-size: 6vw;
            }
            
            .speech-bubble .bubble-heading {
                font-size: clamp(0.55rem, 2.5vw, 0.9rem);
            }
            
            .speech-bubble .bubble-content {
                font-size: clamp(0.45rem, 2vw, 0.85rem);
            }
        }
    </style>
</head>
<body>
    <div class="slideshow-container">
        <!-- Slide 1 - Curhat Anon -->
        <div class="slide active" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
            <div class="slide-overlay" style="background: rgba(0, 0, 0, 0.05);"></div>
            <div class="curhats-container curhats-count-{{ isset($curhats) ? min($curhats->count(), 12) : 1 }}">
                @if(isset($curhats) && $curhats->count() > 0)
                    @php
                        $colors = ['color-1', 'color-2', 'color-3', 'color-4', 'color-5', 'color-6', 'color-7', 'color-8', 'color-9'];
                    @endphp
                    @foreach($curhats as $index => $curhat)
                        <div class="speech-bubble {{ $colors[$index % count($colors)] }}">
                            <span class="quote-start">"</span>
                            <span class="quote-end">"</span>
                            <div class="bubble-content">{{ Str::limit($curhat->content, 200) }}</div>
                        </div>
                    @endforeach
                @else
                    <div class="speech-bubble color-1">
                        <span class="quote-start">"</span>
                        <span class="quote-end">"</span>
                        <div class="bubble-heading">CURHAT</div>
                        <div class="bubble-content">Belum ada curhat yang disetujui untuk ditampilkan.</div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Slide 2 -->
        <div class="slide" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="slide-overlay"></div>
            <div class="slide-content text-center">
                <h2>Innovation at Its Best</h2>
                <p>Experience cutting-edge technology and solutions</p>
                <a href="#" class="btn">Learn More</a>
            </div>
        </div>
        
        <!-- Slide 3 -->
        <div class="slide" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="slide-overlay"></div>
            <div class="slide-content text-center">
                <h2>Your Success is Our Mission</h2>
                <p>Join thousands of satisfied customers</p>
                <a href="#" class="btn">Contact Us</a>
            </div>
        </div>
        
        <!-- Slide 4 -->
        <div class="slide" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <div class="slide-overlay"></div>
            <div class="slide-content text-center dark-text">
                <h2>Quality You Can Trust</h2>
                <p>Premium services for premium results</p>
                <a href="#" class="btn">Explore</a>
            </div>
        </div>
        
        <!-- Slide 5 -->
        <div class="slide" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
            <div class="slide-overlay"></div>
            <div class="slide-content text-center">
                <h2>Start Your Journey Today</h2>
                <p>Transform your ideas into reality</p>
                <a href="#" class="btn">Sign Up</a>
            </div>
        </div>
        
        <!-- Navigation Arrows -->
        <button class="prev" onclick="changeSlide(-1)">&#10094;</button>
        <button class="next" onclick="changeSlide(1)">&#10095;</button>
        
        <!-- Dots Navigation -->
        <div class="dots-container">
            <span class="dot active" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
            <span class="dot" onclick="currentSlide(3)"></span>
            <span class="dot" onclick="currentSlide(4)"></span>
            <span class="dot" onclick="currentSlide(5)"></span>
        </div>
    </div>
    
    <script>
        let currentSlideIndex = 0;
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.dot');
        let slideInterval;
        
        function showSlide(index) {
            // Remove active class from all slides and dots
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            
            // Handle wrap-around
            if (index >= slides.length) {
                currentSlideIndex = 0;
            } else if (index < 0) {
                currentSlideIndex = slides.length - 1;
            } else {
                currentSlideIndex = index;
            }
            
            // Add active class to current slide and dot
            slides[currentSlideIndex].classList.add('active');
            dots[currentSlideIndex].classList.add('active');
        }
        
        function changeSlide(direction) {
            showSlide(currentSlideIndex + direction);
            resetInterval();
        }
        
        function currentSlide(index) {
            showSlide(index - 1);
            resetInterval();
        }
        
        function resetInterval() {
            clearInterval(slideInterval);
            slideInterval = setInterval(() => {
                changeSlide(1);
            }, 5000); // Change slide every 5 seconds
        }
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') {
                changeSlide(-1);
            } else if (e.key === 'ArrowRight') {
                changeSlide(1);
            }
        });
        
        // Touch/swipe support for mobile
        let touchStartX = 0;
        let touchEndX = 0;
        
        document.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });
        
        document.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });
        
        function handleSwipe() {
            if (touchEndX < touchStartX - 50) {
                changeSlide(1); // Swipe left - next slide
            }
            if (touchEndX > touchStartX + 50) {
                changeSlide(-1); // Swipe right - previous slide
            }
        }
        
        // Start auto-play
        resetInterval();
        
        // Pause on hover (optional)
        const slideshowContainer = document.querySelector('.slideshow-container');
        slideshowContainer.addEventListener('mouseenter', () => {
            clearInterval(slideInterval);
        });
        
        slideshowContainer.addEventListener('mouseleave', () => {
            resetInterval();
        });
    </script>
</body>
</html>
