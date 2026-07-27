<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ Str::limit(strip_tags($berita->konten), 160) }}">
    <title>Preview: {{ $berita->judul }} — SIMBA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Merriweather:ital,wght@0,300;0,400;0,700;1,300;1,400&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --teal: #148F9A;
            --teal-dark: #0d7a84;
            --teal-light: #e6f7f8;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            color: #1e293b;
            min-height: 100vh;
        }

        /* ── Portal Header ── */
        .portal-header {
            background: white;
            border-bottom: 3px solid #e5e7eb;
            padding: 0.75rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }
        .portal-logo {
            display: flex;
            align-items: baseline;
            gap: 0;
            text-decoration: none;
            line-height: 1;
        }
        .portal-logo-bakorwil {
            font-size: 1.25rem;
            font-weight: 800;
            color: #1a73e8;
            letter-spacing: 0.01em;
            font-family: 'Inter', sans-serif;
        }
        .portal-logo-separator {
            font-size: 1.25rem;
            font-weight: 400;
            color: #9ca3af;
            margin: 0 0.35rem;
        }
        .portal-logo-madiun {
            font-size: 1.25rem;
            font-weight: 500;
            color: #374151;
            letter-spacing: 0.04em;
            font-family: 'Inter', sans-serif;
        }
        .portal-header-actions {
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }
        .btn-banner-back {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: white;
            border: 1px solid #d1d5db;
            color: #374151;
            padding: 0.4rem 0.875rem;
            border-radius: 0.5rem;
            font-size: 0.8rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-banner-back:hover {
            background: #f9fafb;
            border-color: #9ca3af;
        }
        .btn-banner-edit {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: linear-gradient(135deg, var(--teal), var(--teal-dark));
            color: white;
            padding: 0.4rem 0.875rem;
            border-radius: 0.5rem;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-banner-edit:hover {
            box-shadow: 0 4px 12px rgba(20,143,154,0.4);
            transform: translateY(-1px);
        }

        /* ── Article Layout ── */
        .article-wrapper {
            max-width: 780px;
            margin: 2.5rem auto 4rem;
            padding: 0 1.5rem;
            animation: fadeUp 0.5s ease;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── Breadcrumb ── */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.78rem;
            color: #94a3b8;
            margin-bottom: 1.75rem;
        }
        .breadcrumb a { color: var(--teal); text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }
        .breadcrumb svg { width: 12px; height: 12px; color: #cbd5e1; }

        /* ── Category / Tag ── */
        .article-category {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: var(--teal-light);
            color: var(--teal);
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.3rem 0.75rem;
            border-radius: 999px;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        /* ── Title ── */
        .article-title {
            font-family: 'Merriweather', Georgia, serif;
            font-size: 2rem;
            font-weight: 700;
            line-height: 1.35;
            color: #0f172a;
            margin-bottom: 1.25rem;
        }
        @media (max-width: 640px) {
            .article-title { font-size: 1.5rem; }
        }

        /* ── Meta ── */
        .article-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.875rem 0;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 1.75rem;
            flex-wrap: wrap;
        }
        .meta-author {
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }
        .meta-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--teal), var(--teal-dark));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            font-weight: 700;
            flex-shrink: 0;
        }
        .meta-author-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: #1e293b;
        }
        .meta-author-role {
            font-size: 0.75rem;
            color: #94a3b8;
        }
        .meta-divider {
            width: 1px;
            height: 24px;
            background: #e2e8f0;
        }
        .meta-info {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.8rem;
            color: #64748b;
        }
        .meta-info svg { width: 14px; height: 14px; }

        /* ── Hero Image ── */
        .article-image {
            width: 100%;
            border-radius: 1rem;
            overflow: hidden;
            margin-bottom: 2rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            position: relative;
        }
        .article-image img {
            width: 100%;
            height: 420px;
            object-fit: cover;
            display: block;
            transition: transform 0.6s ease;
        }
        .article-image:hover img {
            transform: scale(1.02);
        }
        .article-image-caption {
            background: #f1f5f9;
            padding: 0.5rem 0.875rem;
            font-size: 0.75rem;
            color: #64748b;
            font-style: italic;
        }
        .no-image-placeholder {
            width: 100%;
            height: 320px;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            border-radius: 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 2rem;
            color: #94a3b8;
        }
        .no-image-placeholder svg { width: 48px; height: 48px; }
        .no-image-placeholder p { font-size: 0.875rem; }

        /* ── Article Body ── */
        .article-body {
            font-family: 'Merriweather', Georgia, serif;
            font-size: 1.05rem;
            line-height: 1.9;
            color: #374151;
        }
        .article-body p {
            margin-bottom: 1.5rem;
        }
        .article-body p:last-child {
            margin-bottom: 0;
        }

        /* ── Footer / Source ── */
        .article-footer {
            margin-top: 3rem;
            padding-top: 1.5rem;
            border-top: 2px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: gap;
            gap: 1rem;
        }
        .article-footer-source {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: #94a3b8;
        }
        .article-footer-source strong {
            color: var(--teal);
            font-weight: 700;
        }
        .share-label {
            font-size: 0.8rem;
            color: #94a3b8;
        }

        /* ── Sidebar-like info card ── */
        .info-card {
            background: white;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .info-card-icon {
            width: 40px;
            height: 40px;
            background: #fff7ed;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .info-card-icon svg { width: 20px; height: 20px; color: #f59e0b; }
        .info-card p {
            font-size: 0.8rem;
            color: #64748b;
            line-height: 1.5;
        }
        .info-card strong { color: #f59e0b; }

        /* ── Back to top ── */
        .back-to-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--teal), var(--teal-dark));
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 16px rgba(20,143,154,0.35);
            transition: all 0.3s;
            opacity: 0;
            transform: translateY(10px);
            border: none;
        }
        .back-to-top.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .back-to-top:hover {
            box-shadow: 0 8px 24px rgba(20,143,154,0.5);
            transform: translateY(-2px);
        }
        .back-to-top svg { width: 18px; height: 18px; }

        /* ── Progress bar ── */
        .reading-progress {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--teal), #34d399);
            z-index: 200;
            transition: width 0.1s;
            width: 0%;
        }


    </style>
</head>
<body>

{{-- Reading Progress Bar --}}
<div class="reading-progress" id="readingProgress"></div>

{{-- Portal Header: Bakorwil I Madiun --}}
<header class="portal-header">
    <a href="{{ route('berita.index') }}" class="portal-logo">
        <span class="portal-logo-bakorwil">BAKORWIL</span>
        <span class="portal-logo-separator">I</span>
        <span class="portal-logo-madiun">MADIUN</span>
    </a>
    <div class="portal-header-actions">
        <a href="{{ route('berita.index') }}" class="btn-banner-back">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:13px;height:13px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
        <a href="{{ route('berita.edit', $berita) }}" class="btn-banner-edit">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:13px;height:13px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Berita
        </a>
    </div>
</header>

{{-- Article --}}
<article class="article-wrapper">

    {{-- Breadcrumb --}}
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('berita.index') }}">Kelola Berita</a>
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span>Preview</span>
    </nav>

    {{-- Category Tag --}}
    <div class="article-category">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:12px;height:12px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
        </svg>
        Berita Resmi
    </div>

    {{-- Title --}}
    <h1 class="article-title">{{ $berita->judul }}</h1>

    {{-- Meta --}}
    <div class="article-meta">
        <div class="meta-author">
            <div class="meta-avatar">{{ strtoupper(substr($berita->user->name ?? 'U', 0, 1)) }}</div>
            <div>
                <div class="meta-author-name">{{ $berita->user->name ?? 'Penulis' }}</div>
                <div class="meta-author-role">
                    @php
                        $roleNames = ['super_admin' => 'Super Administrator', 'admin' => 'Administrator', 'operator' => 'Operator'];
                        echo $roleNames[$berita->user->role ?? ''] ?? 'Penulis';
                    @endphp
                </div>
            </div>
        </div>

        <div class="meta-divider"></div>

        <div class="meta-info">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            {{ $berita->created_at->translatedFormat('d F Y') }}
        </div>

        <div class="meta-info">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ $berita->created_at->format('H:i') }} WIB
        </div>

        @php
            $words = str_word_count(strip_tags($berita->konten));
            $readTime = max(1, ceil($words / 200));
        @endphp
        <div class="meta-info">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            {{ $readTime }} menit baca
        </div>
    </div>

    {{-- Hero Image --}}
    @if($berita->gambar)
        <figure class="article-image">
            <img src="{{ asset('storage/' . $berita->gambar) }}" alt="{{ $berita->judul }}" loading="lazy">
            <figcaption class="article-image-caption">
                Foto: {{ $berita->user->name ?? 'SIMBA' }} / Bakorwil Madiun
            </figcaption>
        </figure>
    @else
        <div class="no-image-placeholder">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p>Tidak ada gambar untuk berita ini</p>
        </div>
    @endif

    {{-- Article Body --}}
    <div class="article-body" id="articleBody">
        @foreach(explode("\n", $berita->konten) as $paragraph)
            @if(trim($paragraph) !== '')
                <p>{{ $paragraph }}</p>
            @endif
        @endforeach
    </div>

    {{-- Article Footer --}}
    <footer class="article-footer">
        <div class="article-footer-source">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:14px;height:14px;color:#94a3b8;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Sumber: <strong>SIMBA</strong> — Sistem Informasi Manajemen Bakorwil
        </div>
        <span class="share-label">Dipublikasikan {{ $berita->created_at->diffForHumans() }}</span>
    </footer>

</article>

{{-- Back to Top Button --}}
<button class="back-to-top" id="backToTop" onclick="window.scrollTo({top:0,behavior:'smooth'})" title="Kembali ke atas">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/>
    </svg>
</button>

<script>
    // Reading Progress
    const progressBar = document.getElementById('readingProgress');
    const backToTop = document.getElementById('backToTop');

    window.addEventListener('scroll', () => {
        const scrollTop = window.scrollY;
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        const progress = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
        progressBar.style.width = progress + '%';

        if (scrollTop > 300) {
            backToTop.classList.add('visible');
        } else {
            backToTop.classList.remove('visible');
        }
    });
</script>
</body>
</html>
