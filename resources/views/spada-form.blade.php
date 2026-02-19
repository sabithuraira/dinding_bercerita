<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SPADA - Dinding Bercerita</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            background: #FFFFFF;
            color: #333333;
            min-height: 100vh;
            padding: 2rem 1rem;
        }
        .container {
            max-width: 640px;
            margin: 0 auto;
        }
        h1 {
            font-size: clamp(1.25rem, 3vw, 1.75rem);
            font-weight: 800;
            color: #333333;
            margin-bottom: 0.5rem;
        }
        .subtitle {
            font-size: 0.9rem;
            color: #333333;
            opacity: 0.85;
            margin-bottom: 1.5rem;
        }
        .question-card {
            background: #F0B940;
            border-radius: 1rem;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 5px solid #333333;
        }
        .question-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #333333;
            margin-bottom: 0.35rem;
        }
        .question-text {
            font-size: clamp(1rem, 2.2vw, 1.25rem);
            font-weight: 600;
            color: #333333;
            line-height: 1.4;
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-group label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            color: #333333;
            margin-bottom: 0.5rem;
        }
        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            font-family: inherit;
            border: 2px solid #C4C4C4;
            border-radius: 0.5rem;
            background: #FFFFFF;
            color: #333333;
        }
        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #F0B940;
        }
        .char-count {
            font-size: 0.8rem;
            color: #333333;
            opacity: 0.7;
            margin-top: 0.35rem;
        }
        .char-count.at-limit { color: #333333; font-weight: 600; }
        .btn {
            display: inline-block;
            padding: 0.75rem 1.75rem;
            font-size: 1rem;
            font-weight: 700;
            color: #FFFFFF;
            background: #333333;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            font-family: inherit;
        }
        .btn:hover { background: #333333; opacity: 0.9; }
        .alert {
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        .alert-success { background: #C4C4C4; color: #333333; }
        .alert-error { background: #C4C4C4; color: #333333; }
        .alert-info { background: #F0B940; color: #333333; }
        .back-link {
            display: inline-block;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: #333333;
            text-decoration: none;
        }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h1>SPADA</h1>
        <p class="subtitle">Satu Pertanyaan Aspirasi dan Afirmasi</p>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="question-card">
            <span class="question-label">Pertanyaan</span>
            <p class="question-text">{{ $question->question }}</p>
        </div>

        @if(!request()->secure())
            <p class="alert alert-info" style="margin-bottom: 1rem;">Anda mengakses halaman melalui HTTP. Pesan peramban tentang "not secure" muncul karena koneksi tidak terenkripsi. Untuk pengiriman yang aman, gunakan HTTPS. Anda tetap dapat mengirim jawaban dengan menekan kirim.</p>
        @endif

        <form action="https://mading.farifam.com/spada-form" method="POST">
            @csrf
            <input type="hidden" name="question_id" value="{{ $question->id }}">

            <div class="form-group">
                <label for="answer">Jawaban Anda (maks. {{ $maxLength }} karakter)</label>
                @if($maxLength <= 200)
                    <input type="text" name="answer" id="answer" maxlength="{{ $maxLength }}" value="{{ old('answer') }}" required placeholder="Ketik jawaban di sini...">
                @else
                    <textarea name="answer" id="answer" maxlength="{{ $maxLength }}" required placeholder="Ketik jawaban di sini...">{{ old('answer') }}</textarea>
                @endif
                <p class="char-count" id="char-count">0 / {{ $maxLength }}</p>
            </div>

            <button type="submit" class="btn">Kirim Jawaban</button>
        </form>

        <a href="{{ url('/') }}" class="back-link">← Kembali ke Dinding Bercerita</a>
    </div>

    <script>
        (function() {
            var el = document.getElementById('answer');
            var countEl = document.getElementById('char-count');
            var max = {{ $maxLength }};
            function update() {
                var len = (el && el.value) ? el.value.length : 0;
                countEl.textContent = len + ' / ' + max;
                countEl.classList.toggle('at-limit', len >= max);
            }
            if (el) {
                el.addEventListener('input', update);
                el.addEventListener('change', update);
                update();
            }
        })();
    </script>
</body>
</html>
