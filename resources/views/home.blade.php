<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DINDING BERCERITA</title>
    
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
        
        /* Page palette: white, yellow, black, gray only */
        .page-title-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            padding: 0.75rem 1.5rem;
            background: #333333;
            color: #FFFFFF;
            text-align: center;
            font-size: clamp(1rem, 2.5vw, 1.5rem);
            font-weight: 700;
            letter-spacing: 0.15em;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }
        .slideshow-container {
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }
        
        .slide {
            position: absolute;
            top: 3.5rem; /* Start below header "DINDING BERCERITA" so content never hits it */
            left: 0;
            width: 100%;
            height: calc(100vh - 3.5rem);
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
            color: #333333;
            text-shadow: none;
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
            background: #333333;
            color: #FFFFFF;
            text-decoration: none;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: clamp(0.875rem, 2vw, 1.125rem);
            transition: all 0.3s ease;
            margin: 0.5rem;
        }
        
        .slide-content .btn:hover {
            background: #333333;
            color: #FFFFFF;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        
        .slide-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: transparent;
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
            background: #C4C4C4;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid #333333;
        }
        
        .dot.active {
            background: #333333;
            border-color: #333333;
            transform: scale(1.2);
        }
        
        .dot:hover {
            background: #333333;
        }
        
        /* Navigation Arrows - black/gray/white palette */
        .prev, .next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: #333333;
            color: #FFFFFF;
            border: none;
            padding: 1.5rem 1rem;
            cursor: pointer;
            font-size: 2rem;
            font-weight: bold;
            z-index: 10;
            transition: all 0.3s ease;
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
            background: #333333;
            color: #FFFFFF;
        }
        
        /* When Slide 1 (orange) is active: adapt dots and arrows to sit on orange */
        .slideshow-container:has(.slide-1.active) .dot {
            background: #FFFFFF;
            border-color: #333333;
        }
        .slideshow-container:has(.slide-1.active) .dot.active {
            background: #333333;
            border-color: #333333;
        }
        .slideshow-container:has(.slide-1.active) .dot:hover {
            background: #C4C4C4;
        }
        .slideshow-container:has(.slide-1.active) .prev,
        .slideshow-container:has(.slide-1.active) .next {
            background: #333333;
            color: #FFFFFF;
        }
        .slideshow-container:has(.slide-1.active) .prev:hover,
        .slideshow-container:has(.slide-1.active) .next:hover {
            background: #333333;
            color: #FFFFFF;
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
            color: #333333;
            text-shadow: none;
        }
        
        .slide-content.dark-text .btn {
            background: #333333;
            color: #FFFFFF;
        }
        
        .slide-content.dark-text .btn:hover {
            background: #333333;
            color: #FFFFFF;
        }
        
        /* Happy Birthday - yellow bg, white/black/gray text */
        .slide-birthday {
            background: #F0B940 !important;
        }
        .slide-birthday .slide-overlay {
            background: transparent;
        }
        .birthday-content {
            text-align: center;
            padding: 2rem;
            max-width: 900px;
        }
        .birthday-content .birthday-title {
            font-size: clamp(2rem, 6vw, 4rem);
            font-weight: 800;
            color: #333333;
            text-shadow: none;
            margin-bottom: 0.5rem;
            letter-spacing: 0.02em;
        }
        .birthday-content .birthday-subtitle {
            font-size: clamp(1.25rem, 3.5vw, 2rem);
            font-weight: 600;
            color: #333333;
            text-shadow: none;
            margin-bottom: 2rem;
        }
        .birthday-names {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem 2rem;
            margin-top: 1.5rem;
        }
        .birthday-name-card {
            background: #FFFFFF;
            padding: 1rem 1.75rem;
            border-radius: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            font-weight: 600;
            font-size: clamp(1rem, 2.5vw, 1.35rem);
            color: #333333;
            border: 2px solid #333333;
        }
        .birthday-name-card .nip {
            display: block;
            font-size: 0.85em;
            font-weight: 500;
            color: #333333;
            margin-top: 0.25rem;
        }
        .birthday-name-card .nmwil {
            display: block;
            font-size: 0.9em;
            font-weight: 500;
            color: #333333;
            margin-top: 0.35rem;
        }
        .birthday-empty {
            font-size: clamp(1rem, 2.5vw, 1.25rem);
            color: #333333;
            text-shadow: none;
        }
        
        /* Kata Motivasi - same as Slide 5: yellow bg, dark text */
        .slide-quote {
            background: #F0B940 !important;
        }
        .slide-quote .slide-overlay {
            background: transparent;
        }
        .quote-content {
            max-width: 85%;
            width: 100%;
            padding: 3rem 2rem;
            text-align: center;
            position: relative;
            z-index: 2;
        }
        .quote-content .quote-section-title {
            font-size: clamp(1.5rem, 4vw, 2.5rem);
            font-weight: 700;
            color: #333333;
            text-shadow: none;
            margin-bottom: 1.5rem;
            letter-spacing: 0.05em;
        }
        .quote-content .quote-mark-open {
            font-family: Georgia, 'Times New Roman', serif;
            font-size: clamp(4rem, 15vw, 12rem);
            font-weight: 700;
            color: rgba(51, 51, 51, 0.15);
            line-height: 1;
            position: absolute;
            top: -0.1em;
            left: 0;
        }
        .quote-content .quote-mark-close {
            font-family: Georgia, 'Times New Roman', serif;
            font-size: clamp(4rem, 15vw, 12rem);
            font-weight: 700;
            color: rgba(51, 51, 51, 0.15);
            line-height: 1;
            position: absolute;
            bottom: -0.3em;
            right: 0;
        }
        .quote-content .quote-text {
            font-family: Georgia, 'Times New Roman', serif;
            font-size: clamp(1.25rem, 3.5vw, 2.25rem);
            line-height: 1.6;
            color: #333333;
            text-shadow: none;
            margin: 1.5rem 0 2rem;
            padding: 0 1rem;
            font-style: italic;
        }
        .quote-content .quote-source {
            font-size: clamp(1rem, 2.2vw, 1.5rem);
            font-weight: 600;
            color: #333333;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-top: 1.5rem;
        }
        .quote-content .quote-source::before {
            content: '— ';
        }
        .quote-placeholder {
            font-size: clamp(1rem, 2.5vw, 1.35rem);
            color: #333333;
            font-style: italic;
        }
        
        /* Slide 4 - Spada: white bg, text yellow/black/gray only */
        .slide-spada {
            background: #FFFFFF !important;
        }
        .slide-spada .slide-overlay {
            background: transparent;
        }
        .spada-container {
            width: 96%;
            max-height: 96%;
            overflow: auto;
            padding: 1rem;
            text-align: center;
            z-index: 2;
        }
        .spada-section-title {
            font-size: clamp(1.75rem, 5vw, 2.75rem);
            font-weight: 800;
            color: #333333;
            margin-bottom: 0.75rem;
            line-height: 1.2;
            letter-spacing: 0.02em;
        }
        .spada-question-card {
            display: inline-block;
            max-width: 90%;
            padding: 1.5rem 2.25rem 1.5rem 2.25rem;
            margin-bottom: 1.5rem;
            background: #F0B940;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15), 0 0 0 3px rgba(51, 51, 51, 0.2);
            position: relative;
            border-left: 5px solid #333333;
            text-align: left;
        }
        .spada-question-card::before {
            content: '?';
            position: absolute;
            top: 50%;
            right: 1.25rem;
            transform: translateY(-50%);
            font-size: clamp(3rem, 8vw, 5rem);
            font-weight: 800;
            color: rgba(51, 51, 51, 0.12);
            line-height: 1;
            font-family: Georgia, serif;
        }
        .spada-question-label {
            display: block;
            font-size: clamp(0.7rem, 1.5vw, 0.85rem);
            font-weight: 700;
            color: #333333;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }
        .spada-question-title {
            font-size: clamp(1.1rem, 2.8vw, 1.6rem);
            font-weight: 700;
            color: #333333;
            margin: 0;
            line-height: 1.45;
            font-style: italic;
        }
        /* Sticky note style (type_question = 1) - white/yellow/gray only */
        .spada-sticky-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(min(360px, 100%), 1fr));
            gap: 1rem;
            justify-content: center;
            align-items: start;
        }
        .spada-sticky-note {
            padding: 1rem;
            border-radius: 2px;
            box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
            font-size: clamp(0.75rem, 1.5vw, 1rem);
            line-height: 1.45;
            color: #333333;
            text-align: left;
            min-height: 80px;
            transform: rotate(-1deg);
            border: 1px solid #C4C4C4;
        }
        .spada-sticky-note:nth-child(3n) { transform: rotate(1deg); }
        .spada-sticky-note:nth-child(3n+2) { transform: rotate(-0.5deg); }
        .spada-sticky-note.sticky-1 { background: #F0B940; }
        .spada-sticky-note.sticky-2 { background: #F0B940; }
        .spada-sticky-note.sticky-3 { background: #C4C4C4; }
        .spada-sticky-note.sticky-4 { background: #C4C4C4; }
        .spada-sticky-note.sticky-5 { background: #F0B940; }
        .spada-sticky-note.sticky-6 { background: #C4C4C4; }
        .spada-sticky-note.sticky-7 { background: #C4C4C4; color: #333333; }
        .spada-sticky-note.sticky-8 { background: #F0B940; }
        .spada-sticky-note.sticky-9 { background: #FFFFFF; }
        /* Word cloud style (type_question = 2) - dense packing, very close spacing */
        .spada-wordcloud {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            align-content: center;
            gap: 0.05rem 0.15rem;
            padding: 0.5rem 0.35rem;
            line-height: 1.1;
            max-width: 100%;
        }
        .spada-wordcloud-item {
            display: inline-block;
            padding: 0.04rem 0.18rem;
            font-weight: 600;
            opacity: 0.95;
            transition: transform 0.2s ease;
            line-height: 1.2;
            white-space: nowrap;
        }
        .spada-wordcloud-item:hover {
            transform: scale(1.05);
        }
        /* Word cloud sizes: each step ~80% bigger than previous for clear prominence */
        .spada-wordcloud .size-1 { font-size: clamp(0.7rem, 1.5vw, 0.9rem); }
        .spada-wordcloud .size-2 { font-size: clamp(1.25rem, 2.7vw, 1.6rem); }
        .spada-wordcloud .size-3 { font-size: clamp(2.2rem, 4.8vw, 2.9rem); }
        .spada-wordcloud .size-4 { font-size: clamp(3.2rem, 6.5vw, 4rem); }
        .spada-wordcloud .size-5 { font-size: clamp(4.5rem, 9vw, 5.5rem); }
        /* Word cloud colors: yellow, black, grays only (white bg) */
        .spada-wordcloud-item.wc-color-1 { color: #F0B940; }
        .spada-wordcloud-item.wc-color-2 { color: #333333; }
        .spada-wordcloud-item.wc-color-3 { color: #333333; }
        .spada-wordcloud-item.wc-color-4 { color: #333333; }
        .spada-wordcloud-item.wc-color-5 { color: #F0B940; }
        .spada-wordcloud-item.wc-color-6 { color: #333333; }
        .spada-wordcloud-item.wc-color-7 { color: #C4C4C4; }
        .spada-wordcloud-item.wc-color-8 { color: #333333; }
        .spada-wordcloud-item.wc-color-9 { color: #C4C4C4; }
        .spada-empty {
            font-size: clamp(0.95rem, 2vw, 1.15rem);
            color: #333333;
            margin-top: 1rem;
        }
        
        /* Speech Bubble Styles for Curhat Anon - frames fit content, each can have different size */
        .curhats-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-auto-rows: minmax(100px, auto);
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
            grid-auto-rows: minmax(100px, auto);
        }
        
        .curhats-container.curhats-count-2 {
            grid-template-columns: repeat(2, 1fr);
            grid-auto-rows: minmax(100px, auto);
        }
        
        .curhats-container.curhats-count-3 {
            grid-template-columns: repeat(3, 1fr);
            grid-auto-rows: minmax(100px, auto);
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
            grid-auto-rows: minmax(100px, auto);
        }
        
        /* Metro-style: long content spans 2 rows so columns have different counts per column */
        .speech-bubble.bubble-tall {
            grid-row: span 2;
        }
        
        .speech-bubble {
            position: relative;
            background: #FFFFFF;
            border: none;
            border-radius: 1vw;
            padding: 1rem;
            box-shadow: 0 0.3vw 0.8vw rgba(0, 0, 0, 0.1);
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
            color: #333333;
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
            color: #333333;
            line-height: 1;
            font-family: Georgia, serif;
            opacity: 0.4;
            z-index: 1;
        }
        
        .speech-bubble .bubble-heading {
            font-size: clamp(0.6rem, 1.8vw, 1.25rem);
            font-weight: 700;
            text-transform: uppercase;
            color: #333333;
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
            color: #333333;
            position: relative;
            z-index: 2;
            text-align: justify;
            flex: 0 1 auto;
        }
        
        /* Speech bubbles: white, yellow, gray only */
        .speech-bubble.color-1 {
            background: #F0B940;
        }
        
        .speech-bubble.color-2 {
            background: #FFFFFF;
        }
        
        .speech-bubble.color-3 {
            background: #C4C4C4;
        }
        
        .speech-bubble.color-4 {
            background: #F0B940;
        }
        
        .speech-bubble.color-5 {
            background: #C4C4C4;
        }
        
        .speech-bubble.color-6 {
            background: #C4C4C4;
        }
        
        .speech-bubble.color-7 {
            background: #F0B940;
        }
        
        .speech-bubble.color-8 {
            background: #F0B940;
        }
        
        .speech-bubble.color-9 {
            background: #C4C4C4;
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
        
        .speech-bubble.color-1::after { border-top-color: #F0B940; }
        .speech-bubble.color-2::after { border-top-color: #FFFFFF; }
        .speech-bubble.color-3::after { border-top-color: #C4C4C4; }
        .speech-bubble.color-4::after { border-top-color: #F0B940; }
        .speech-bubble.color-5::after { border-top-color: #C4C4C4; }
        .speech-bubble.color-6::after { border-top-color: #C4C4C4; }
        .speech-bubble.color-7::after { border-top-color: #F0B940; }
        .speech-bubble.color-8::after { border-top-color: #F0B940; }
        .speech-bubble.color-9::after { border-top-color: #C4C4C4; }
        
        /* Slide 1: adapt content for dark/orange background */
        .slide-1 .speech-bubble {
            box-shadow: 0 0.3vw 0.8vw rgba(0, 0, 0, 0.25);
        }
        .slide-1 .speech-bubble .bubble-content,
        .slide-1 .speech-bubble .bubble-heading {
            color: #333333;
        }
        .slide-1 .speech-bubble .quote-start,
        .slide-1 .speech-bubble .quote-end {
            color: #333333;
        }
        .slide-1 .speech-bubble.color-1::after { border-top-color: #F0B940; }
        .slide-1 .speech-bubble.color-2::after { border-top-color: #FFFFFF; }
        .slide-1 .speech-bubble.color-3::after { border-top-color: #C4C4C4; }
        .slide-1 .speech-bubble.color-4::after { border-top-color: #F0B940; }
        .slide-1 .speech-bubble.color-5::after { border-top-color: #C4C4C4; }
        .slide-1 .speech-bubble.color-6::after { border-top-color: #C4C4C4; }
        .slide-1 .speech-bubble.color-7::after { border-top-color: #F0B940; }
        .slide-1 .speech-bubble.color-8::after { border-top-color: #F0B940; }
        .slide-1 .speech-bubble.color-9::after { border-top-color: #C4C4C4; }
        
        /* Tail under card: no border (border removed) */
        .speech-bubble::before {
            content: '';
            position: absolute;
            bottom: -1.1vw;
            left: calc(15% - 0.1vw);
            width: 0;
            height: 0;
            border-left: 1.1vw solid transparent;
            border-right: 1.1vw solid transparent;
            border-top: 1.1vw solid transparent;
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
        
        /* Scroll hint icon for slides with scrollable content */
        .scroll-hint {
            position: absolute;
            bottom: 3rem;
            right: 1.5rem;
            z-index: 5;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.25rem;
            color: #333333;
            opacity: 0.85;
            pointer-events: none;
        }
        .scroll-hint-icon {
            width: 0;
            height: 0;
            border-left: 10px solid transparent;
            border-right: 10px solid transparent;
            border-top: 14px solid currentColor;
            animation: scroll-hint-bounce 2s ease-in-out infinite;
        }
        @keyframes scroll-hint-bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(6px); }
        }
        .scroll-hint-text {
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
    </style>
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
