<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIJALA | Konseling Jaga Lansia</title>
    <link rel="icon" href="{{ url('image/logo.png') }}">
    
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>
        /* ===================================================
           DESIGN TOKENS & SYSTEM VARIABLES
        =================================================== */
        :root {
            --color-primary: #d97706;
            --color-primary-hover: #b45309;
            --color-primary-dark: #78350f;
            --color-primary-light: #fef3c7;
            --color-primary-subtle: #fffbeb;
            
            --color-dark: #0f172a;
            --color-text-main: #1e293b;
            --color-text-muted: #475569;
            --color-text-subtle: #64748b;
            
            --bg-body: #faf9f6;
            --bg-surface: #ffffff;
            --bg-surface-alt: #f8fafc;
            --border-color: #e2e8f0;
            --border-subtle: #f1f5f9;

            --youtube-red: #dc2626;

            --font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;

            --radius-sm: 8px;
            --radius-md: 14px;
            --radius-lg: 20px;
            --radius-full: 9999px;

            --shadow-xs: 0 1px 2px rgba(15, 23, 42, 0.04);
            --shadow-sm: 0 2px 8px rgba(15, 23, 42, 0.05);
            --shadow-md: 0 6px 20px -2px rgba(15, 23, 42, 0.06);

            --transition-base: all 0.25s ease;
        }

        /* Base Resets */
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 80px;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--bg-body);
            color: var(--color-text-main);
            line-height: 1.7;
            font-size: 15px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
            transition: var(--transition-base);
        }

        img {
            max-width: 100%;
            height: auto;
            display: block;
        }

        button {
            font-family: inherit;
            border: none;
            outline: none;
            background: none;
            cursor: pointer;
        }

        :focus-visible {
            outline: 2px solid var(--color-primary);
            outline-offset: 3px;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* Navbar */
        .navbar {
            position: sticky;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            transition: var(--transition-base);
        }

        .navbar-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 72px;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo-wrap {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-full);
            background: var(--bg-surface);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2px;
            border: 1.5px solid var(--color-primary-light);
            box-shadow: var(--shadow-xs);
        }

        .brand-logo-wrap img {
            width: 100%;
            height: 100%;
            border-radius: var(--radius-full);
            object-fit: contain;
        }

        .brand-text-wrap {
            display: flex;
            flex-direction: column;
        }

        .brand-name {
            font-size: 19px;
            font-weight: 700;
            color: var(--color-dark);
            letter-spacing: -0.3px;
            line-height: 1.1;
        }

        .brand-tagline {
            font-size: 11px;
            font-weight: 600;
            color: var(--color-primary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 6px;
            list-style: none;
        }

        .nav-link {
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 500;
            color: var(--color-text-muted);
            border-radius: var(--radius-sm);
            transition: var(--transition-base);
        }

        .nav-link:hover {
            color: var(--color-primary);
            background-color: var(--color-primary-subtle);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-login-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--color-dark);
            background: var(--bg-surface-alt);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-full);
            transition: var(--transition-base);
        }

        .nav-login-btn:hover {
            background: var(--color-primary-subtle);
            color: var(--color-primary);
            border-color: rgba(217, 119, 6, 0.3);
        }

        .menu-toggle-btn {
            display: none;
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm);
            background: var(--bg-surface-alt);
            color: var(--color-dark);
            font-size: 22px;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border-color);
        }

        /* Hero Section */
        .hero-section {
            padding: 64px 0 56px;
            background: linear-gradient(180deg, #fffcf6 0%, var(--bg-body) 100%);
            border-bottom: 1px solid var(--border-subtle);
        }

        .hero-inner {
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #ffffff;
            color: var(--color-primary-dark);
            border: 1px solid rgba(217, 119, 6, 0.25);
            box-shadow: var(--shadow-xs);
            padding: 6px 16px;
            border-radius: var(--radius-full);
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .hero-badge i {
            color: var(--color-primary);
            font-size: 14px;
        }

        .hero-title {
            font-size: clamp(32px, 4.5vw, 48px);
            font-weight: 700;
            color: var(--color-dark);
            line-height: 1.2;
            margin-bottom: 18px;
            letter-spacing: -0.8px;
        }

        .hero-title .text-highlight {
            color: var(--color-primary);
        }

        .hero-description {
            font-size: clamp(15px, 1.8vw, 17.5px);
            color: var(--color-text-muted);
            line-height: 1.8;
            max-width: 700px;
            margin: 0 auto 32px;
        }

        .hero-cta-group {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 48px;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 12px 26px;
            border-radius: var(--radius-full);
            font-size: 15px;
            font-weight: 600;
            transition: var(--transition-base);
            cursor: pointer;
            text-align: center;
            min-height: 48px;
        }

        .btn-primary {
            background: var(--color-primary);
            color: #ffffff;
            border: 1px solid var(--color-primary);
            box-shadow: 0 4px 12px rgba(217, 119, 6, 0.25);
        }

        .btn-primary:hover {
            background: var(--color-primary-hover);
            border-color: var(--color-primary-hover);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(217, 119, 6, 0.35);
        }

        .btn-outline {
            background: #ffffff;
            color: var(--color-dark);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-xs);
        }

        .btn-outline i.youtube-icon {
            color: var(--youtube-red);
            font-size: 17px;
        }

        .btn-outline:hover {
            border-color: rgba(220, 38, 38, 0.3);
            background: #fffafa;
            color: var(--youtube-red);
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        /* Trust Bar */
        .hero-trust-bar {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            padding: 16px 20px;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-xs);
            text-align: left;
        }

        .trust-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 4px 8px;
        }

        .trust-item:not(:last-child) {
            border-right: 1px solid var(--border-subtle);
        }

        .trust-icon {
            width: 36px;
            height: 36px;
            border-radius: var(--radius-sm);
            background: var(--color-primary-light);
            color: var(--color-primary-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            flex-shrink: 0;
        }

        .trust-text h4 {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--color-dark);
            margin-bottom: 2px;
        }

        .trust-text p {
            font-size: 12px;
            color: var(--color-text-subtle);
            line-height: 1.4;
        }

        /* Section Wrappers */
        .section-wrapper {
            padding: 64px 0;
        }

        .section-header {
            text-align: center;
            max-width: 640px;
            margin: 0 auto 40px;
        }

        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: var(--color-primary);
            background: var(--color-primary-light);
            padding: 4px 12px;
            border-radius: var(--radius-full);
            margin-bottom: 12px;
        }

        .section-title {
            font-size: clamp(24px, 3vw, 32px);
            font-weight: 700;
            color: var(--color-dark);
            letter-spacing: -0.4px;
            line-height: 1.3;
            margin-bottom: 10px;
        }

        .section-subtitle {
            font-size: 15px;
            color: var(--color-text-muted);
            line-height: 1.7;
        }

        /* Video Section */
        .video-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .video-card {
            background: var(--bg-surface);
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
            overflow: hidden;
            box-shadow: var(--shadow-xs);
            transition: var(--transition-base);
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .video-card:hover {
            border-color: rgba(217, 119, 6, 0.35);
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .video-player-box {
            position: relative;
            width: 100%;
            aspect-ratio: 16 / 9;
            background: #0f172a;
            overflow: hidden;
        }

        .video-player-box iframe {
            width: 100%;
            height: 100%;
            border: none;
            display: block;
        }

        .video-body {
            padding: 18px 20px 20px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .video-meta {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 8px;
        }

        .video-tag {
            font-size: 11.5px;
            font-weight: 600;
            color: var(--color-primary);
            background: var(--color-primary-subtle);
            padding: 2px 8px;
            border-radius: var(--radius-sm);
            border: 1px solid rgba(217, 119, 6, 0.15);
        }

        .video-body h3 {
            font-size: 16px;
            font-weight: 600;
            color: var(--color-dark);
            line-height: 1.5;
            margin-bottom: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 48px;
        }

        .video-body p {
            font-size: 13.5px;
            color: var(--color-text-muted);
            line-height: 1.6;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-top: auto;
        }

        /* Poster Section */
        .poster-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .poster-card {
            background: var(--bg-surface);
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
            overflow: hidden;
            box-shadow: var(--shadow-xs);
            transition: var(--transition-base);
            display: flex;
            flex-direction: column;
            height: 100%;
            cursor: pointer;
        }

        .poster-card:hover {
            border-color: rgba(217, 119, 6, 0.35);
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .poster-image-box {
            position: relative;
            background: var(--bg-surface-alt);
            padding: 16px;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            border-bottom: 1px solid var(--border-subtle);
        }

        .poster-image-box img {
            width: 100%;
            height: 340px;
            object-fit: contain;
            border-radius: var(--radius-sm);
            background: #ffffff;
            transition: transform 0.3s ease;
        }

        .poster-card:hover .poster-image-box img {
            transform: scale(1.02);
        }

        .poster-zoom-badge {
            position: absolute;
            top: 22px;
            right: 22px;
            width: 34px;
            height: 34px;
            border-radius: var(--radius-full);
            background: rgba(255, 255, 255, 0.9);
            color: var(--color-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            box-shadow: var(--shadow-xs);
            opacity: 0;
            transform: scale(0.85);
            transition: var(--transition-base);
        }

        .poster-card:hover .poster-zoom-badge {
            opacity: 1;
            transform: scale(1);
        }

        .poster-body {
            padding: 18px 20px 20px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .poster-body h3 {
            font-size: 16px;
            font-weight: 600;
            color: var(--color-dark);
            line-height: 1.5;
            margin-bottom: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .poster-body p {
            font-size: 13.5px;
            color: var(--color-text-muted);
            line-height: 1.6;
            margin-bottom: 16px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .poster-footer {
            margin-top: auto;
            padding-top: 12px;
            border-top: 1px solid var(--border-subtle);
        }

        .poster-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--color-primary);
            background: var(--color-primary-subtle);
            padding: 7px 16px;
            border-radius: var(--radius-full);
            border: 1px solid rgba(217, 119, 6, 0.2);
            transition: var(--transition-base);
        }

        .poster-card:hover .poster-action-btn {
            background: var(--color-primary);
            color: #ffffff;
            border-color: var(--color-primary);
        }

        /* Empty State */
        .empty-state {
            grid-column: 1 / -1;
            background: var(--bg-surface);
            border-radius: var(--radius-md);
            border: 1px dashed var(--border-color);
            padding: 48px 24px;
            text-align: center;
        }

        .empty-icon {
            width: 56px;
            height: 56px;
            border-radius: var(--radius-full);
            background: var(--color-primary-light);
            color: var(--color-primary);
            font-size: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
        }

        .empty-state h3 {
            font-size: 16.5px;
            font-weight: 600;
            color: var(--color-dark);
            margin-bottom: 4px;
        }

        .empty-state p {
            font-size: 13.5px;
            color: var(--color-text-subtle);
        }

        /* APK CTA Section */
        .apk-card {
            background: #ffffff;
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-color);
            padding: 44px 48px;
            box-shadow: var(--shadow-sm);
            margin-top: 40px;
        }

        .apk-grid {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 36px;
            align-items: center;
        }

        .apk-content {
            max-width: 660px;
        }

        .apk-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: var(--color-primary);
            background: var(--color-primary-light);
            padding: 4px 12px;
            border-radius: var(--radius-full);
            margin-bottom: 14px;
        }

        .apk-content h2 {
            font-size: clamp(22px, 2.5vw, 28px);
            font-weight: 700;
            color: var(--color-dark);
            margin-bottom: 12px;
            letter-spacing: -0.3px;
        }

        .apk-content p {
            font-size: 15px;
            color: var(--color-text-muted);
            line-height: 1.7;
            margin-bottom: 22px;
        }

        .apk-feature-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 26px;
        }

        .apk-pill {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            font-weight: 500;
            color: var(--color-text-main);
            background: var(--bg-surface-alt);
            padding: 6px 14px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-color);
        }

        .apk-pill i {
            color: var(--color-primary);
            font-size: 14px;
        }

        .apk-visual {
            background: var(--color-primary-subtle);
            border-radius: var(--radius-md);
            padding: 24px 28px;
            border: 1px solid rgba(217, 119, 6, 0.2);
            text-align: center;
            min-width: 200px;
        }

        .apk-visual-icon {
            width: 58px;
            height: 58px;
            border-radius: var(--radius-full);
            background: #ffffff;
            color: var(--color-primary);
            font-size: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            box-shadow: var(--shadow-xs);
        }

        .apk-visual h4 {
            font-size: 14.5px;
            font-weight: 600;
            color: var(--color-dark);
            margin-bottom: 2px;
        }

        .apk-visual span {
            font-size: 12px;
            color: var(--color-text-subtle);
        }

        /* Admin Card */
        .admin-card {
            background: var(--bg-surface-alt);
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
            padding: 28px 32px;
            margin-top: 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        .admin-left {
            display: flex;
            align-items: center;
            gap: 16px;
            max-width: 720px;
        }

        .admin-icon {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-sm);
            background: #ffffff;
            color: var(--color-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            border: 1px solid var(--border-color);
            flex-shrink: 0;
        }

        .admin-text h3 {
            font-size: 16px;
            font-weight: 600;
            color: var(--color-dark);
            margin-bottom: 3px;
        }

        .admin-text p {
            font-size: 13.5px;
            color: var(--color-text-muted);
            line-height: 1.5;
        }

        .admin-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--color-dark);
            color: #ffffff;
            padding: 10px 22px;
            border-radius: var(--radius-full);
            font-size: 14px;
            font-weight: 600;
            white-space: nowrap;
            transition: var(--transition-base);
        }

        .admin-action-btn:hover {
            background: #334155;
            color: #ffffff;
            transform: translateY(-1px);
        }

        /* Modal Lightbox */
        .poster-modal {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s ease, visibility 0.25s ease;
        }

        .poster-modal.active {
            opacity: 1;
            visibility: visible;
        }

        .poster-modal-dialog {
            position: relative;
            max-width: 90vw;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transform: scale(0.95);
            transition: transform 0.25s ease;
        }

        .poster-modal.active .poster-modal-dialog {
            transform: scale(1);
        }

        .poster-modal-img {
            max-width: 100%;
            max-height: 82vh;
            object-fit: contain;
            background: #ffffff;
            border-radius: var(--radius-sm);
            padding: 8px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .poster-close {
            position: absolute;
            top: -42px;
            right: 0;
            width: 36px;
            height: 36px;
            border-radius: var(--radius-full);
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            font-size: 18px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: var(--transition-base);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .poster-close:hover {
            background: #ffffff;
            color: var(--color-dark);
        }

        .modal-hint {
            color: rgba(255, 255, 255, 0.75);
            font-size: 12px;
            margin-top: 8px;
            text-align: center;
        }

        /* Footer */
        footer {
            margin-top: 64px;
            background: var(--color-dark);
            color: #ffffff;
            padding: 56px 0 28px;
            border-top: 1px solid #1e293b;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
        }

        .footer-brand .brand-logo-wrap {
            width: 40px;
            height: 40px;
            background: #ffffff;
            border-color: rgba(255, 255, 255, 0.2);
        }

        .footer-brand .brand-name {
            color: #ffffff;
            font-size: 20px;
        }

        .footer-brand .brand-tagline {
            color: var(--color-primary);
        }

        .footer-desc {
            color: #94a3b8;
            font-size: 13.5px;
            line-height: 1.7;
            max-width: 360px;
        }

        .footer-col h4 {
            font-size: 15px;
            font-weight: 600;
            color: #f8fafc;
            margin-bottom: 16px;
        }

        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .footer-links a {
            color: #94a3b8;
            font-size: 13.5px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: var(--transition-base);
        }

        .footer-links a:hover {
            color: var(--color-primary);
            transform: translateX(3px);
        }

        .footer-youtube-link {
            color: #f87171;
            font-weight: 500;
        }

        .footer-youtube-link:hover {
            color: #ef4444;
        }

        .footer-bottom {
            padding-top: 24px;
            border-top: 1px solid #1e293b;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #64748b;
            font-size: 13px;
            flex-wrap: wrap;
            gap: 12px;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .video-grid,
            .poster-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }

            .hero-trust-bar {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .trust-item:not(:last-child) {
                border-right: none;
                border-bottom: 1px solid var(--border-subtle);
                padding-bottom: 10px;
            }

            .apk-grid {
                grid-template-columns: 1fr;
                gap: 28px;
                text-align: center;
            }

            .apk-feature-pills {
                justify-content: center;
            }

            .apk-visual {
                display: inline-block;
                margin: 0 auto;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 32px;
            }
        }

        @media (max-width: 768px) {
            .navbar-content {
                height: 64px;
            }

            .menu-toggle-btn {
                display: flex;
            }

            .nav-menu {
                position: absolute;
                top: 64px;
                left: 0;
                width: 100%;
                background: #ffffff;
                flex-direction: column;
                padding: 16px 20px;
                border-bottom: 1px solid var(--border-color);
                box-shadow: var(--shadow-md);
                display: none;
                gap: 6px;
            }

            .nav-menu.active {
                display: flex;
            }

            .nav-link {
                width: 100%;
                padding: 10px 14px;
                font-size: 14.5px;
            }

            .nav-actions {
                display: none;
            }

            .hero-section {
                padding: 44px 0 40px;
            }

            .hero-title {
                font-size: 28px;
            }

            .hero-description {
                font-size: 15px;
            }

            .hero-cta-group {
                flex-direction: column;
                width: 100%;
                gap: 10px;
            }

            .btn {
                width: 100%;
                max-width: 320px;
            }

            .video-grid,
            .poster-grid {
                grid-template-columns: 1fr;
            }

            .poster-image-box img {
                height: 280px;
            }

            .apk-card {
                padding: 32px 20px;
                border-radius: var(--radius-md);
            }

            .admin-card {
                flex-direction: column;
                text-align: center;
                padding: 24px 20px;
            }

            .admin-left {
                flex-direction: column;
                align-items: center;
            }

            .admin-action-btn {
                width: 100%;
                justify-content: center;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 28px;
            }

            .footer-bottom {
                flex-direction: column;
                text-align: center;
                gap: 12px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }
    </style>
</head>
<body>

    <!-- ===================================================
         NAVIGATION BAR
    =================================================== -->
    <header class="navbar">
        <div class="container">
            <div class="navbar-content">
                <a href="#hero" class="navbar-brand" aria-label="SIJALA Beranda">
                    <div class="brand-logo-wrap">
                        <img src="{{ url('image/logo.png') }}" alt="Logo SIJALA">
                    </div>
                    <div class="brand-text-wrap">
                        <span class="brand-name">SIJALA</span>
                        <span class="brand-tagline">Konseling Jaga Lansia</span>
                    </div>
                </a>

                <nav>
                    <ul class="nav-menu" id="navMenu">
                        <li><a href="#hero" class="nav-link">Beranda</a></li>
                        <li><a href="#video-section" class="nav-link">Video Edukasi</a></li>
                        <li><a href="#poster-section" class="nav-link">Poster Edukasi</a></li>
                        <li><a href="#apk-section" class="nav-link">Aplikasi Mobile</a></li>
                    </ul>
                </nav>

                <div class="nav-actions">
                    <a href="{{ route('login') }}" class="nav-login-btn">Login Admin </a>
                    <button type="button" class="menu-toggle-btn" id="menuToggle" aria-label="Buka navigasi">
                        <i class="bi bi-list" id="menuIcon"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- ===================================================
         HERO SECTION
    =================================================== -->
    <section class="hero-section" id="hero">
        <div class="container">
            <div class="hero-inner">
                <div class="hero-badge">
                    <i class="bi bi-shield-check"></i>
                    Layanan Edukasi & Pendampingan Lansia
                </div>

                <h1 class="hero-title">
                    Konseling <span class="text-highlight">Jaga Lansia</span>
                </h1>

                <p class="hero-description">
                    Konseling Jaga Lansia menyediakan layanan edukasi, konsultasi, dan pendampingan
                    kesehatan lansia untuk membantu meningkatkan kesejahteraan lansia dan keluarga.
                </p>

                <div class="hero-cta-group">
                    <a href="https://drive.google.com/uc?export=download&id=10ts9iD1I0yJB2chVBq9aq-paygxd1JKK"
                        class="btn btn-primary">
                        <i class="bi bi-download"></i>
                        Unduh Aplikasi
                    </a>

                    <a href="https://www.youtube.com/@jagalansia" target="_blank" rel="noopener noreferrer"
                        class="btn btn-outline">
                        <i class="bi bi-youtube youtube-icon"></i>
                        Channel YouTube
                    </a>
                </div>

                <!-- Trust Points Bar -->
                <div class="hero-trust-bar">
                    <div class="trust-item">
                        <div class="trust-icon">
                            <i class="bi bi-patch-check"></i>
                        </div>
                        <div class="trust-text">
                            <h4>100% Edukatif & Gratis</h4>
                            <p>Akses informasi kesehatan secara mudah & terbuka.</p>
                        </div>
                    </div>

                    <div class="trust-item">
                        <div class="trust-icon">
                            <i class="bi bi-heart-pulse"></i>
                        </div>
                        <div class="trust-text">
                            <h4>Materi Terpercaya</h4>
                            <p>Disusun sesuai panduan kesehatan & skrining lansia.</p>
                        </div>
                    </div>

                    <div class="trust-item">
                        <div class="trust-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="trust-text">
                            <h4>Dukungan Keluarga</h4>
                            <p>Mendampingi keluarga merawat lansia tersayang.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Container -->
    <main class="container">

        <!-- ===================================================
             VIDEO EDUKASI SECTION
        =================================================== -->
        <section class="section-wrapper" id="video-section">
            <div class="section-header">
                <span class="section-badge">
                    <i class="bi bi-play-circle"></i> Video Edukasi
                </span>
                <h2 class="section-title">Video Edukasi Lansia</h2>
                <p class="section-subtitle">
                    Koleksi video panduan praktis, senam kebugaran, dan tips perawatan kesehatan yang mudah dipahami oleh lansia serta keluarga.
                </p>
            </div>

            <div class="video-grid">
                @forelse ($videos as $video)
                    @php
                        if (is_array($video)) {
                            // Data dari YouTube API
                            $videoId = data_get($video, 'id.videoId');
                            $link = $videoId ? "https://www.youtube.com/embed/{$videoId}" : '';
                            $title = data_get($video, 'snippet.title', 'Video Edukasi');
                            $description = data_get($video, 'snippet.description', '');
                        } else {
                            // Data dari Database
                            $link = $video->file_path;
                            $title = $video->title;
                            $description = $video->description;

                            if (!empty($link)) {
                                // watch?v=
                                if (preg_match('/[?&]v=([^&]+)/', $link, $matches)) {
                                    $link = 'https://www.youtube.com/embed/' . $matches[1];
                                }

                                // youtu.be/
                                elseif (preg_match('/youtu\.be\/([^?]+)/', $link, $matches)) {
                                    $link = 'https://www.youtube.com/embed/' . $matches[1];
                                }

                                // shorts/
                                elseif (preg_match('/shorts\/([^?]+)/', $link, $matches)) {
                                    $link = 'https://www.youtube.com/embed/' . $matches[1];
                                }
                            }
                        }
                    @endphp
                    <div class="video-card">
                        <div class="video-player-box">
                            <iframe src="{{ $link }}" title="{{ $title }}" allowfullscreen loading="lazy">
                            </iframe>
                        </div>
                        <div class="video-body">
                            <div class="video-meta">
                                <span class="video-tag">Panduan Lansia</span>
                            </div>
                            <h3>{{ $title }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit($description, 200) }}</p>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bi bi-camera-video-off"></i>
                        </div>
                        <h3>Tidak ada video tersedia</h3>
                        <p>Video edukasi lansia akan segera ditampilkan di sini.</p>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- ===================================================
             POSTER EDUKASI SECTION
        =================================================== -->
        <section class="section-wrapper" id="poster-section">
            <div class="section-header">
                <span class="section-badge">
                    <i class="bi bi-images"></i> Media Visual
                </span>
                <h2 class="section-title">Poster Edukasi Lansia</h2>
                <p class="section-subtitle">
                    Infografis visual yang memuat panduan nutrisi, pencegahan risiko jatuh, dan pemeliharaan kesehatan lansia.
                </p>
            </div>

            <div class="poster-grid">
                @forelse($posters as $poster)
                    <div class="poster-card" data-src="{{ url('image/' . $poster->file_path) }}" role="button" tabindex="0" aria-label="Lihat poster {{ $poster->title }}">
                        <div class="poster-image-box">
                            <img src="{{ url('image/' . $poster->file_path) }}" alt="{{ $poster->title }}" loading="lazy">
                            <div class="poster-zoom-badge" aria-hidden="true">
                                <i class="bi bi-zoom-in"></i>
                            </div>
                        </div>

                        <div class="poster-body">
                            <h3>{{ $poster->title }}</h3>

                            @if ($poster->description)
                                <p>{{ \Illuminate\Support\Str::limit($poster->description, 100) }}</p>
                            @endif

                            <div class="poster-footer">
                                <span class="poster-action-btn">
                                    <i class="bi bi-arrows-fullscreen"></i>
                                    Lihat Poster
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bi bi-image"></i>
                        </div>
                        <h3>Tidak ada poster tersedia</h3>
                        <p>Poster edukasi akan segera ditampilkan di sini.</p>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- ===================================================
             DOWNLOAD APLIKASI SECTION
        =================================================== -->
        <section class="apk-card" id="apk-section">
            <div class="apk-grid">
                <div class="apk-content">
                    <div class="apk-badge">
                        <i class="bi bi-phone"></i> Aplikasi Mobile
                    </div>

                    <h2>Aplikasi Jaga Lansia</h2>

                    <p>
                        Unduh aplikasi Jaga Lansia untuk mendapatkan akses informasi kesehatan,
                        edukasi, skrining, pemantauan, serta layanan pendampingan lansia dalam
                        satu aplikasi yang mudah digunakan.
                    </p>

                    <div class="apk-feature-pills">
                        <div class="apk-pill">
                            <i class="bi bi-check-circle-fill"></i> Skrining Risiko Jatuh
                        </div>
                        <div class="apk-pill">
                            <i class="bi bi-check-circle-fill"></i> Edukasi & Panduan Harian
                        </div>
                        <div class="apk-pill">
                            <i class="bi bi-check-circle-fill"></i> Pendampingan Terpadu
                        </div>
                    </div>

                    <a href="https://drive.google.com/uc?export=download&id=10ts9iD1I0yJB2chVBq9aq-paygxd1JKK" class="btn btn-primary">
                        <i class="bi bi-android2"></i>
                        Download APK Sekarang
                    </a>
                </div>

                <div class="apk-visual">
                    <div class="apk-visual-icon">
                        <i class="bi bi-phone"></i>
                    </div>
                    <h4>SIJALA Mobile</h4>
                    <span>Versi Android APK</span>
                </div>
            </div>
        </section>

        <!-- ===================================================
             LOGIN ADMINISTRATOR SECTION (SUBTLE)
        =================================================== -->
        <section class="admin-card" id="admin-section">
            <div class="admin-left">
                <div class="admin-icon">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <div class="admin-text">
                    <h3>Login Administrator</h3>
                    <p>
                        Halaman ini digunakan oleh administrator, konselor, dan petugas
                        untuk mengelola data, layanan konseling, edukasi, serta monitoring
                        pada sistem Jaga Lansia.
                    </p>
                </div>
            </div>

            <a href="{{ route('login') }}" class="admin-action-btn">
                <i class="bi bi-box-arrow-in-right"></i>
                Login Admin
            </a>
        </section>

    </main>

    <!-- ===================================================
         POSTER LIGHTBOX MODAL
    =================================================== -->
    <div class="poster-modal" id="posterModal" role="dialog" aria-modal="true" aria-label="Preview Poster Edukasi">
        <div class="poster-modal-dialog">
            <button type="button" class="poster-close" id="posterModalClose" aria-label="Tutup preview poster">
                <i class="bi bi-x-lg"></i>
            </button>
            <img id="posterPreview" class="poster-modal-img" src="" alt="Preview Poster Edukasi Lansia">
            <div class="modal-hint">
                <i class="bi bi-info-circle"></i> Klik di luar poster atau tekan tombol <strong>ESC</strong> untuk menutup
            </div>
        </div>
    </div>

    <!-- ===================================================
         FOOTER
    =================================================== -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div>
                    <div class="footer-brand">
                        <div class="brand-logo-wrap">
                            <img src="{{ url('image/logo.png') }}" alt="Logo SIJALA">
                        </div>
                        <div>
                            <div class="brand-name">SIJALA</div>
                            <div class="brand-tagline">Konseling Jaga Lansia</div>
                        </div>
                    </div>
                    <p class="footer-desc">
                        Sistem informasi dan edukasi terpadu untuk pendampingan, pemantauan kesehatan, dan peningkatan kualitas hidup lansia serta keluarga.
                    </p>
                </div>

                <div class="footer-col">
                    <h4>Navigasi Halaman</h4>
                    <ul class="footer-links">
                        <li><a href="#hero"><i class="bi bi-chevron-right"></i> Beranda</a></li>
                        <li><a href="#video-section"><i class="bi bi-chevron-right"></i> Video Edukasi</a></li>
                        <li><a href="#poster-section"><i class="bi bi-chevron-right"></i> Poster Edukasi</a></li>
                        <li><a href="#apk-section"><i class="bi bi-chevron-right"></i> Unduh Aplikasi</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Layanan & Akses</h4>
                    <ul class="footer-links">
                        <li>
                            <a href="{{ route('login') }}"><i class="bi bi-shield-lock"></i> Portal Petugas & Admin</a>
                        </li>
                        <li>
                            <a href="https://www.youtube.com/@jagalansia" target="_blank" rel="noopener noreferrer" class="footer-youtube-link">
                                <i class="bi bi-youtube"></i> Channel YouTube Resmi
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <div>
                    © {{ date('Y') }} Jaga Lansia Official. All Rights Reserved.
                </div>
                <div>
                    Platform Edukasi & Pendampingan Lansia
                </div>
            </div>
        </div>
    </footer>

    <!-- ===================================================
         JAVASCRIPT (VANILLA & ACCESSIBLE)
    =================================================== -->
    <script>
        // Modal Poster Handlers (Global functions preserved for safety)
        function showPoster(src) {
            const modal = document.getElementById('posterModal');
            const preview = document.getElementById('posterPreview');
            if (preview && modal && src) {
                preview.src = src;
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        function closePoster() {
            const modal = document.getElementById('posterModal');
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        }

        // Poster Card Click & Keyboard Event Listener (Single unified handler, zero duplicate firing)
        document.querySelectorAll('.poster-card').forEach(card => {
            const src = card.getAttribute('data-src');
            
            // Mouse Click
            card.addEventListener('click', function(e) {
                if (src) {
                    showPoster(src);
                }
            });

            // Keyboard Accessibility (Enter & Space)
            card.addEventListener('keydown', function(e) {
                if ((e.key === 'Enter' || e.key === ' ') && src) {
                    e.preventDefault();
                    showPoster(src);
                }
            });
        });

        // Close Button Event
        const closeBtn = document.getElementById('posterModalClose');
        if (closeBtn) {
            closeBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                closePoster();
            });
        }

        // Click outside backdrop to close
        const posterModal = document.getElementById('posterModal');
        if (posterModal) {
            posterModal.addEventListener('click', function(e) {
                if (e.target === this || e.target.classList.contains('poster-modal-dialog')) {
                    closePoster();
                }
            });
        }

        // ESC key to close modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePoster();
            }
        });

        // Mobile Menu Toggle
        const menuToggle = document.getElementById('menuToggle');
        const navMenu = document.getElementById('navMenu');
        const menuIcon = document.getElementById('menuIcon');

        if (menuToggle && navMenu) {
            menuToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                navMenu.classList.toggle('active');
                if (menuIcon) {
                    if (navMenu.classList.contains('active')) {
                        menuIcon.classList.remove('bi-list');
                        menuIcon.classList.add('bi-x-lg');
                    } else {
                        menuIcon.classList.remove('bi-x-lg');
                        menuIcon.classList.add('bi-list');
                    }
                }
            });

            // Close mobile menu when a nav link is clicked
            document.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', () => {
                    navMenu.classList.remove('active');
                    if (menuIcon) {
                        menuIcon.classList.remove('bi-x-lg');
                        menuIcon.classList.add('bi-list');
                    }
                });
            });

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!navMenu.contains(e.target) && !menuToggle.contains(e.target) && navMenu.classList.contains('active')) {
                    navMenu.classList.remove('active');
                    if (menuIcon) {
                        menuIcon.classList.remove('bi-x-lg');
                        menuIcon.classList.add('bi-list');
                    }
                }
            });
        }
    </script>
</body>

</html>