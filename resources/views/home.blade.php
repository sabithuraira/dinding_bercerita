<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DINDING BERCERITA</title>
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    
    {{-- Styles moved to public/css/home.css for easier maintenance --}}
</head>
<body>
    <header class="page-title-bar">DINDING BERCERITA</header>
    <div class="slideshow-container">
        <!-- Slide 1 - Curhat Anon (orange bg) -->
        <div class="slide slide-1 active" style="background: #FF9800;">
            <div class="slide-overlay"></div>
            <div class="curhats-container curhats-count-{{ isset($curhats) ? min($curhats->count(), 12) : 1 }}">
                @if(isset($curhats) && $curhats->count() > 0)
                    @php
                        $colors = ['color-1', 'color-2', 'color-3', 'color-4', 'color-5', 'color-6', 'color-7', 'color-8', 'color-9'];
                    @endphp
                    @foreach($curhats as $index => $curhat)
                        <div class="speech-bubble {{ $colors[$index % count($colors)] }} {{ strlen($curhat->content) > 250 ? 'bubble-tall' : '' }}">
                            <span class="quote-start">"</span>
                            <span class="quote-end">"</span>
                            <div class="bubble-content">{{ $curhat->content }}</div>
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
        
        @if(isset($birthday) && $birthday->isNotEmpty())
        <!-- Slide 2 - Happy Birthday (Congratulatory) -->
        <div class="slide slide-birthday">
            <div class="slide-overlay"></div>
            <div class="birthday-content">
                <h1 class="birthday-title">Selamat Ulang Tahun</h1>
                <p class="birthday-subtitle">Semoga panjang umur, sehat selalu, dan sukses selalu!</p>
                <div class="birthday-names">
                    @foreach($birthday as $user)
                        <div class="birthday-name-card">
                            {{ $user->name }}
                            <span class="nip">{{ $user->nip_baru }}</span>
                            @if(!empty($user->nmwil))
                                <span class="nmwil">{{ $user->nmwil }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        
        <!-- Slide 3 - Kata Motivasi (Quote from big figure) -->
        <div class="slide slide-quote">
            <div class="slide-overlay"></div>
            <div class="quote-content">
                <h2 class="quote-section-title">Kata Motivasi</h2>
                @if(isset($kataMotivasi) && $kataMotivasi)
                    <span class="quote-mark-open">"</span>
                    <p class="quote-text">{{ $kataMotivasi->kata_motivasi }}</p>
                    @if($kataMotivasi->dikutip_dari)
                        <p class="quote-source">{{ $kataMotivasi->dikutip_dari }}</p>
                    @endif
                    <span class="quote-mark-close">"</span>
                @else
                    <p class="quote-placeholder">Tidak ada kata motivasi untuk ditampilkan.</p>
                @endif
            </div>
        </div>
        
        @if(isset($spadaActiveToday) && $spadaActiveToday)
        <!-- Slide 4 - Spada (active question today + answers: sticky notes or word cloud) -->
        <div class="slide slide-spada">
            <div class="slide-overlay"></div>
            <div class="spada-container">
                <h1 class="spada-section-title">SPADA, Satu Pertanyaan Aspirasi dan Afirmasi</h1>
                <div class="spada-question-card">
                    <span class="spada-question-label">Pertanyaan</span>
                    <h2 class="spada-question-title">{{ $spadaActiveToday->question }}</h2>
                </div>
                @if($spadaActiveToday->type_question == 1)
                    {{-- Sticky note style --}}
                    @if($spadaActiveTodayAnswers->isNotEmpty())
                        <div class="spada-sticky-grid">
                            @php $stickyColors = ['sticky-1', 'sticky-2', 'sticky-3', 'sticky-4', 'sticky-5', 'sticky-6', 'sticky-7', 'sticky-8', 'sticky-9']; @endphp
                            @foreach($spadaActiveTodayAnswers as $idx => $ans)
                                <div class="spada-sticky-note {{ $stickyColors[$idx % count($stickyColors)] }}">{{ $ans->answer }}</div>
                            @endforeach
                        </div>
                    @else
                        <p class="spada-empty">Belum ada jawaban yang disetujui.</p>
                    @endif
                @else
                    {{-- Word cloud style (type_question = 2): unique words, size by count, colorful --}}
                    @if(isset($spadaWordCloud) && $spadaWordCloud->isNotEmpty())
                        <div class="spada-wordcloud">
                            @php
                                $wcColors = ['wc-color-1', 'wc-color-2', 'wc-color-3', 'wc-color-4', 'wc-color-5', 'wc-color-6', 'wc-color-7', 'wc-color-8', 'wc-color-9'];
                            @endphp
                            @foreach($spadaWordCloud as $idx => $item)
                                @php
                                    $c = $item->count;
                                    if ($c <= 2) { $sizeClass = 'size-1'; }
                                    elseif ($c <= 5) { $sizeClass = 'size-2'; }
                                    elseif ($c <= 10) { $sizeClass = 'size-3'; }
                                    elseif ($c <= 20) { $sizeClass = 'size-4'; }
                                    else { $sizeClass = 'size-5'; }
                                    $colorClass = $wcColors[$idx % count($wcColors)];
                                @endphp
                                <span class="spada-wordcloud-item {{ $sizeClass }} {{ $colorClass }}">{{ $item->answer }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="spada-empty">Belum ada jawaban yang disetujui.</p>
                    @endif
                @endif
            </div>
            <div class="scroll-hint" aria-hidden="true">
                <span class="scroll-hint-icon"></span>
                <span class="scroll-hint-text">Scroll Down</span>
            </div>
        </div>
        @endif
        
        <!-- Slide 5 - Curhat / Ide (yellow bg, black/gray text) -->
        <div class="slide" style="background: #F0B940;">
            <div class="slide-overlay"></div>
            <div class="slide-content text-center">
                <h2>Ada Ide? Yuk, Curhat!</h2>
                <p>Punya gagasan untuk meningkatkan kinerja, kesehatan, dan kebahagiaan pegawai? Kami tunggu curhat dan masukan Anda.</p>
            </div>
        </div>
        
        <!-- Navigation Arrows -->
        <button class="prev" onclick="changeSlide(-1)">&#10094;</button>
        <button class="next" onclick="changeSlide(1)">&#10095;</button>
        
        <!-- Dots Navigation (dot count matches visible slides) -->
        <div class="dots-container">
            <span class="dot active" onclick="currentSlide(1)"></span>
            @if(isset($birthday) && $birthday->isNotEmpty())
            <span class="dot" onclick="currentSlide(2)"></span>
            @endif
            <span class="dot" onclick="currentSlide(3)"></span>
            @if(isset($spadaActiveToday) && $spadaActiveToday)
            <span class="dot" onclick="currentSlide(4)"></span>
            @endif
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
            const currentSlideEl = slides[currentSlideIndex];
            const isSlide1 = currentSlideEl && currentSlideEl.querySelector('.curhats-container');
            const isSlide4 = currentSlideEl && currentSlideEl.classList.contains('slide-spada');
            const delayMs = (isSlide1 || isSlide4) ? 60 * 1000 : 5000; // 1 min for slide 1 & 4, 5 sec for others
            slideInterval = setInterval(() => {
                changeSlide(1);
            }, delayMs);
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
        
        // Auto-refresh page every 10 minutes
        const REFRESH_INTERVAL_MS = 10 * 60 * 1000; // 10 minutes
        setTimeout(() => {
            location.reload();
        }, REFRESH_INTERVAL_MS);
    </script>
</body>
</html>
