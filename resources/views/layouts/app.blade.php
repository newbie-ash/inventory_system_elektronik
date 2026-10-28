<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Ctrl+R</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Chart.js 4.4 -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 250px;
            /* Warm Paper Palette (Cream & Black Charcoal) */
            --colors-canvas: #F6F3EC;
            --colors-primary: rgba(255, 255, 255, 0.42);
            --colors-card-alt: rgba(242, 239, 233, 0.45);
            --colors-glass: rgba(255, 255, 255, 0.42);
            --colors-hairline: rgba(229, 224, 213, 0.7);
            --colors-border: rgba(229, 224, 213, 0.7);
            --colors-accent: #1A1A1C;
            --colors-accent-hover: #2C2C2E;
            --colors-accent-text: #FFFFFF;
            --colors-on-primary: #1A1A1C;
            --colors-body: #1A1A1C;
            --colors-muted: #706E6B;
            --colors-faint: #8E8A83;
            --colors-neutral-1: #706E6B;
            --shadow-lift: 0 10px 30px rgba(28, 26, 24, 0.08);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--colors-canvas);
            background-image: 
                radial-gradient(circle at 12% 15%, rgba(222, 213, 196, 0.65) 0%, transparent 45%),
                radial-gradient(circle at 88% 12%, rgba(208, 198, 180, 0.55) 0%, transparent 48%),
                radial-gradient(circle at 50% 55%, rgba(228, 220, 204, 0.55) 0%, transparent 55%),
                radial-gradient(circle at 18% 85%, rgba(218, 208, 192, 0.6) 0%, transparent 45%),
                radial-gradient(circle at 82% 80%, rgba(226, 216, 200, 0.5) 0%, transparent 50%);
            background-attachment: fixed;
            color: var(--colors-body);
            min-height: 100vh;
            letter-spacing: 0px;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            color: #1A1A1C;
        }

        .top-hairline {
            height: 2px;
            background: linear-gradient(90deg, #1A1A1C 0%, #D8D2C4 50%, #F8F5EE 100%);
            position: sticky;
            top: 0;
            z-index: 1060;
        }

        /* Sidebar for Admin & Staff (Warm Paper Glassmorphism) */
        .sidebar {
            width: var(--sidebar-width);
            height: calc(100vh - 2px);
            background: rgba(255, 255, 255, 0.42) !important;
            backdrop-filter: blur(24px) saturate(190%) !important;
            -webkit-backdrop-filter: blur(24px) saturate(190%) !important;
            border: none !important;
            border-right: none !important;
            box-shadow: 4px 0 30px rgba(28, 26, 24, 0.04) !important;
            color: var(--colors-faint);
            position: fixed;
            top: 2px;
            left: 0;
            bottom: 0;
            z-index: 1040;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transition: all 0.28s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .sidebar-nav-body {
            flex-grow: 1;
            overflow-y: auto;
        }

        /* Collapsed Sidebar on Desktop (Compact Rail) */
        body.sidebar-collapsed .sidebar {
            width: 70px !important;
        }

        body.sidebar-collapsed .main-wrapper {
            margin-left: 70px !important;
        }

        body.sidebar-collapsed .sidebar .brand-title,
        body.sidebar-collapsed .sidebar .brand small,
        body.sidebar-collapsed .sidebar .brand-dot,
        body.sidebar-collapsed .sidebar .section-heading,
        body.sidebar-collapsed .sidebar .nav-link span,
        body.sidebar-collapsed .sidebar .badge {
            display: none !important;
        }

        body.sidebar-collapsed .sidebar .sidebar-brand-wrap {
            padding: 0 !important;
            justify-content: center !important;
        }

        body.sidebar-collapsed .sidebar .brand {
            display: none !important;
        }

        body.sidebar-collapsed .sidebar .nav-link {
            justify-content: center !important;
            padding: 0.75rem 0 !important;
            text-align: center;
        }

        body.sidebar-collapsed .sidebar .nav-link i {
            margin-right: 0 !important;
            font-size: 1.25rem;
        }

        .admin-nav-profile-btn {
            background: #F2EFE9 !important;
            border: 1px solid var(--colors-hairline) !important;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            color: #1A1A1C !important;
        }

        .admin-nav-profile-btn:hover,
        .admin-nav-profile-btn.active-profile {
            background: #E5E0D5 !important;
            border-color: var(--colors-accent) !important;
            transform: translateY(-1px);
        }

        .admin-nav-profile-btn .nav-profile-name,
        .admin-nav-profile-btn span.fw-bold {
            color: #1A1A1C !important;
        }

        .admin-nav-profile-btn .nav-profile-role {
            color: #706E6B !important;
        }

        .sidebar-brand-wrap {
            height: 70px;
            padding: 0 1.25rem 0 1.4rem;
            box-sizing: border-box;
            background: transparent !important;
            border: none !important;
            border-bottom: none !important;
            box-shadow: none !important;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar .brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--colors-accent);
        }

        .sidebar-toggle-btn,
        .sidebar-toggle-top {
            background: #F2EFE9 !important;
            border: 1px solid #E5E0D5 !important;
            color: #1A1A1C !important;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .sidebar-toggle-btn:hover,
        .sidebar-toggle-top:hover {
            background: #E5E0D5 !important;
            border-color: var(--colors-accent) !important;
            color: var(--colors-accent) !important;
            transform: scale(1.05);
        }

        .brand-dot {
            width: 10px;
            height: 10px;
            background: #1A1A1C;
            box-shadow: 0 0 8px rgba(26, 26, 28, 0.35);
            border-radius: 50%;
            display: inline-block;
        }

        .brand-title {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--colors-accent);
            letter-spacing: -0.5px;
        }

        .sidebar .nav-link {
            color: var(--colors-muted);
            padding: 0.65rem 1rem;
            margin: 3px 12px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
            border: 1px solid transparent;
            border-left: 3px solid transparent;
        }

        .sidebar .nav-link:hover {
            color: #1A1A1C;
            background: #F2EFE9;
            border-color: #E5E0D5;
            transform: translateX(3px);
        }

        .sidebar .nav-link.active {
            color: #1A1A1C;
            background: #F2EFE9;
            border: 1px solid #E5E0D5;
            border-left: 3px solid var(--colors-accent);
            box-shadow: 0 2px 8px rgba(28, 26, 24, 0.04);
            font-weight: 700;
        }

        .sidebar .section-heading {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.75px;
            color: var(--colors-muted);
            padding: 1.25rem 1.4rem 0.35rem;
            font-weight: 700;
        }

        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: transparent;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* Customer Layout (No Sidebar) */
        body.customer-layout .main-wrapper {
            margin-left: 0 !important;
        }

        /* Navigation Bar for Customer (Warm Paper Glassmorphism) */
        .customer-top-navbar {
            background: rgba(255, 255, 255, 0.42) !important;
            backdrop-filter: blur(24px) saturate(190%) !important;
            -webkit-backdrop-filter: blur(24px) saturate(190%) !important;
            border-bottom: 1px solid rgba(229, 224, 213, 0.6) !important;
            box-shadow: 0 4px 24px rgba(28, 26, 24, 0.04) !important;
            position: sticky;
            top: 0;
            z-index: 1050;
            height: 70px;
            display: flex;
            align-items: center;
        }

        .customer-top-navbar .brand-title {
            font-size: 1.55rem;
            font-weight: 800;
            color: var(--colors-accent);
            letter-spacing: -0.5px;
            text-decoration: none;
            transition: transform 0.2s ease;
        }

        .customer-top-navbar .brand-title:hover {
            color: var(--colors-accent-hover);
            transform: translateY(-1px);
        }

        /* Nav links: Home, Catalog, Promo, My Orders */
        .cust-nav-links {
            display: flex;
            align-items: center;
            gap: 1.75rem;
            margin-left: 2rem;
        }

        .cust-nav-link {
            color: var(--colors-muted);
            font-size: 0.92rem;
            font-weight: 500;
            text-decoration: none;
            position: relative;
            padding: 8px 0;
            transition: color 0.2s ease;
            white-space: nowrap;
        }

        .cust-nav-link:hover {
            color: #1A1A1C;
        }

        .cust-nav-link.active {
            color: #1A1A1C;
            font-weight: 700;
        }

        .cust-nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 0;
            right: 0;
            height: 2.5px;
            background: var(--colors-accent);
            border-radius: 4px;
            box-shadow: 0 0 8px rgba(26, 26, 28, 0.3);
        }

        /* Search bar pill */
        .cust-search-pill {
            display: flex;
            align-items: center;
            background: #FFFFFF;
            border: 1px solid #E5E0D5;
            border-radius: 12px;
            padding: 0 14px;
            height: 40px;
            gap: 8px;
            width: 280px;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .cust-search-pill:focus-within {
            background: #FFFFFF;
            border-color: var(--colors-accent);
            box-shadow: 0 0 0 3px rgba(26, 26, 28, 0.08);
            width: 320px;
        }

        .cust-search-btn {
            background: transparent;
            border: none;
            padding: 0;
            color: var(--colors-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: color 0.15s ease;
        }

        .cust-search-pill:focus-within .cust-search-btn,
        .cust-search-btn:hover {
            color: var(--colors-accent);
        }

        .cust-search-input {
            background: transparent !important;
            border: none !important;
            outline: none !important;
            color: #1A1A1C !important;
            font-size: 0.85rem;
            padding: 0;
            width: 100%;
        }

        .cust-search-input::placeholder {
            color: var(--colors-muted);
            font-size: 0.82rem;
        }

        /* Icon action buttons: Cart & Bell */
        .cust-action-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #FFFFFF;
            border: 1px solid #E5E0D5;
            color: #1A1A1C;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            text-decoration: none;
            position: relative;
            transition: all 0.2s ease;
        }

        .cust-action-icon:hover {
            background: #F2EFE9;
            border-color: var(--colors-accent);
            color: var(--colors-accent);
            transform: translateY(-1px);
        }

        .cust-action-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #DC2626;
            color: #FFFFFF;
            font-size: 0.65rem;
            font-weight: 700;
            min-width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #FFFFFF;
        }

        /* Profile button */
        .cust-profile-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #FFFFFF;
            border: 1px solid #E5E0D5;
            border-radius: 12px;
            padding: 4px 12px 4px 6px;
            color: #1A1A1C;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .cust-profile-btn:hover,
        .cust-profile-btn.active-profile,
        .cust-profile-btn[aria-expanded="true"] {
            background: #F2EFE9;
            border-color: var(--colors-accent);
        }

        .cust-avatar-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #1A1A1C;
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.8rem;
            border: 1.5px solid #E5E0D5;
        }

        .cust-profile-text {
            display: flex;
            flex-direction: column;
            text-align: left;
            line-height: 1.1;
        }

        .cust-profile-greeting {
            font-size: 0.72rem;
            color: var(--colors-muted);
        }

        .cust-profile-name {
            font-size: 0.82rem;
            font-weight: 700;
            color: #1A1A1C;
            max-width: 110px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Top Navbar for Admin & Staff (Warm Paper Glassmorphism) */
        .top-navbar {
            background: rgba(255, 255, 255, 0.42) !important;
            backdrop-filter: blur(24px) saturate(190%) !important;
            -webkit-backdrop-filter: blur(24px) saturate(190%) !important;
            border-bottom: 1px solid rgba(229, 224, 213, 0.6) !important;
            box-shadow: 0 4px 24px rgba(28, 26, 24, 0.04) !important;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 2px;
            z-index: 1020;
            height: 70px;
            box-sizing: border-box;
        }

        .content-area {
            padding: 2rem;
            flex-grow: 1;
            background-color: transparent;
        }

        /* Cards & Containers (Glassmorphism Frosted Surface) */
        .card, .glass-card, .minimal-card, .kpi-card {
            background: rgba(255, 255, 255, 0.45) !important;
            backdrop-filter: blur(24px) saturate(190%) !important;
            -webkit-backdrop-filter: blur(24px) saturate(190%) !important;
            border: 1px solid rgba(255, 255, 255, 0.65) !important;
            border-radius: 16px !important;
            box-shadow: 0 10px 32px 0 rgba(28, 26, 24, 0.04), inset 0 1px 1px 0 rgba(255, 255, 255, 0.95) !important;
            color: var(--colors-body);
            padding: 24px;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .card:hover, .glass-card:hover {
            border-color: rgba(26, 26, 28, 0.25) !important;
            box-shadow: 0 14px 40px -4px rgba(28, 26, 24, 0.08), inset 0 1px 1px 0 rgba(255, 255, 255, 0.95) !important;
        }

        .card-header {
            background-color: transparent !important;
            border-bottom: 1px solid rgba(229, 224, 213, 0.5) !important;
            color: #1A1A1C !important;
            padding: 16px 24px;
            font-size: 1.05rem;
            font-weight: 700;
        }

        .card-footer {
            background-color: transparent !important;
            border-top: 1px solid rgba(229, 224, 213, 0.5) !important;
            padding: 16px 24px;
        }

        /* KPI Cards Theme & Layout */
        .kpi-card {
            padding: 20px !important;
            position: relative;
            overflow: hidden;
        }
        .kpi-card:hover {
            transform: translateY(-3px);
            border-color: #1A1A1C !important;
            box-shadow: 0 12px 28px -6px rgba(28, 26, 24, 0.1) !important;
        }
        .kpi-card::after {
            content: '';
            position: absolute;
            top: -35px;
            right: -35px;
            width: 110px;
            height: 110px;
            border-radius: 50%;
            pointer-events: none;
            filter: blur(28px);
            opacity: 0.35;
            transition: opacity 0.25s ease;
        }
        .kpi-card:hover::after {
            opacity: 0.65;
        }

        .kpi-card.theme-accent::after { background: rgba(26, 26, 28, 0.15); }
        .kpi-card.theme-emerald::after { background: rgba(22, 163, 74, 0.18); }
        .kpi-card.theme-blue::after { background: rgba(2, 132, 199, 0.18); }
        .kpi-card.theme-purple::after { background: rgba(147, 51, 234, 0.18); }

        .kpi-icon-squircle {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            box-shadow: 0 4px 12px rgba(28, 26, 24, 0.08);
            transition: transform 0.25s ease;
        }
        .kpi-card:hover .kpi-icon-squircle {
            transform: scale(1.08) rotate(2deg);
        }

        .icon-accent {
            background: rgba(26, 26, 28, 0.08);
            border: 1px solid rgba(26, 26, 28, 0.2);
            color: #1A1A1C;
        }
        .icon-emerald {
            background: rgba(22, 163, 74, 0.1);
            border: 1px solid rgba(22, 163, 74, 0.25);
            color: #15803D;
        }
        .icon-blue {
            background: rgba(2, 132, 199, 0.1);
            border: 1px solid rgba(2, 132, 199, 0.25);
            color: #0284C7;
        }
        .icon-purple {
            background: rgba(147, 51, 234, 0.1);
            border: 1px solid rgba(147, 51, 234, 0.25);
            color: #7E22CE;
        }

        .kpi-value {
            font-size: clamp(1.1rem, 1.35vw, 1.45rem);
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1.2;
            color: #1A1A1C;
            white-space: nowrap;
        }

        .card-footer .slide-pagination-wrap {
            margin: 0.35rem 0 !important;
        }

        /* Form Controls (Warm Paper Elevated) */
        .form-control, .form-select, .form-control-minimal, .form-select-minimal, .minimal-input {
            background-color: #FFFFFF !important;
            border: 1px solid #D8D2C4 !important;
            color: #1A1A1C !important;
            font-size: 15px;
            border-radius: 10px;
            height: 44px;
            padding: 0 16px;
            box-shadow: none !important;
            transition: all 0.2s ease;
        }

        .form-select, .form-select-minimal {
            padding: 0 36px 0 14px !important;
            background-position: right 12px center !important;
            background-size: 14px 10px !important;
        }

        textarea.form-control {
            height: auto !important;
            min-height: 80px;
            padding: 10px 16px;
        }

        .form-control::placeholder, .form-control-minimal::placeholder, .minimal-input::placeholder {
            color: #8E8A83 !important;
        }

        .form-control:focus, .form-select:focus, .form-control-minimal:focus, .minimal-input:focus {
            background-color: #FFFFFF !important;
            border-color: #1A1A1C !important;
            color: #1A1A1C !important;
            outline: 0px !important;
            box-shadow: 0 0 0 3px rgba(26, 26, 28, 0.1) !important;
        }

        .form-select option {
            background-color: #FFFFFF !important;
            color: #1A1A1C !important;
        }

        /* Input Groups: Precision Radius & Border Collapsing */
        .input-group {
            display: flex;
            align-items: stretch;
            width: 100%;
        }

        .input-group > .form-control,
        .input-group > .form-select {
            position: relative;
            flex: 1 1 auto;
            width: 1%;
            min-width: 0;
        }

        .input-group > :first-child {
            border-top-left-radius: 12px !important;
            border-bottom-left-radius: 12px !important;
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }

        .input-group > :last-child {
            border-top-right-radius: 12px !important;
            border-bottom-right-radius: 12px !important;
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
            margin-left: -1px !important;
        }

        .input-group > :not(:first-child):not(:last-child) {
            border-radius: 0 !important;
            margin-left: -1px !important;
        }

        .input-group > .btn,
        .input-group > .btn-glass-outline,
        .input-group > .btn-outline-primary,
        .input-group > .btn-outline-secondary {
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .input-group-sm > .btn,
        .input-group-sm > .form-control,
        .input-group-sm > .input-group-text {
            height: 38px !important;
        }

        .input-group-text {
            background-color: #F2EFE9 !important;
            border: 1px solid #D8D2C4 !important;
            color: #706E6B !important;
            height: 44px;
            padding: 0 16px;
            display: flex;
            align-items: center;
            font-size: 14px;
            font-weight: 600;
        }

        /* Buttons (Black Charcoal Primary #1A1A1C) */
        .btn-primary, .btn-rm-primary, .btn-blue-primary, .btn-accent-cta, .btn-minimal-red, .btn-minimal-blue {
            background: #1A1A1C !important;
            border: 1px solid #1A1A1C !important;
            color: #FFFFFF !important;
            font-weight: 600;
            border-radius: 10px !important;
            height: 42px;
            padding: 8px 20px;
            box-shadow: 0 2px 8px rgba(28, 26, 24, 0.18) !important;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .btn-primary:hover, .btn-rm-primary:hover, .btn-blue-primary:hover, .btn-accent-cta:hover, .btn-minimal-red:hover, .btn-minimal-blue:hover {
            background: #2C2C2E !important;
            border-color: #2C2C2E !important;
            color: #FFFFFF !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(28, 26, 24, 0.25) !important;
        }

        /* Secondary / Outline Button */
        .btn-secondary, .btn-default-dark {
            background: #F2EFE9 !important;
            border: 1px solid #D8D2C4 !important;
            color: #1A1A1C !important;
            font-weight: 500;
            border-radius: 10px !important;
            height: 42px;
            padding: 8px 18px;
            box-shadow: none !important;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .btn-secondary:hover, .btn-default-dark:hover {
            background: #E5E0D5 !important;
            border-color: #1A1A1C !important;
            color: #1A1A1C !important;
            transform: translateY(-1px);
        }

        .btn-outline-primary, .btn-outline-secondary, .btn-glass-outline, .btn-outline-glass, .btn-minimal-outline {
            background: transparent !important;
            border: 1.5px solid #1A1A1C !important;
            color: #1A1A1C !important;
            font-weight: 600;
            border-radius: 10px !important;
            height: 42px;
            padding: 8px 18px;
            box-shadow: none !important;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .btn-outline-primary:hover, .btn-outline-secondary:hover, .btn-glass-outline:hover, .btn-outline-glass:hover, .btn-minimal-outline:hover {
            background: #1A1A1C !important;
            color: #FFFFFF !important;
            border-color: #1A1A1C !important;
            transform: translateY(-1px);
        }

        /* Unified Table Action Buttons (Squircle & Consistent Gaps) */
        .btn-action {
            width: 32px;
            height: 32px;
            padding: 0 !important;
            border-radius: 8px !important;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none !important;
            box-shadow: none !important;
        }
        .btn-action:hover {
            transform: translateY(-2px);
        }
        .btn-action + .btn-action,
        .btn-action + form,
        form + .btn-action,
        form + form {
            margin-left: 8px !important;
        }
        .gap-1\.5 {
            gap: 8px !important;
        }
        .btn-action-view {
            background: rgba(26, 26, 28, 0.08) !important;
            border: 1px solid rgba(26, 26, 28, 0.2) !important;
            color: #1A1A1C !important;
        }
        .btn-action-view:hover {
            background: #1A1A1C !important;
            color: #FFFFFF !important;
            box-shadow: 0 4px 12px rgba(26, 26, 28, 0.25) !important;
        }
        .btn-action-move {
            background: rgba(22, 163, 74, 0.1) !important;
            border: 1px solid rgba(22, 163, 74, 0.25) !important;
            color: #15803D !important;
        }
        .btn-action-move:hover {
            background: #15803D !important;
            color: #FFFFFF !important;
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.25) !important;
        }
        .btn-action-edit {
            background: rgba(217, 119, 6, 0.1) !important;
            border: 1px solid rgba(217, 119, 6, 0.25) !important;
            color: #D97706 !important;
        }
        .btn-action-edit:hover {
            background: #D97706 !important;
            color: #FFFFFF !important;
            box-shadow: 0 4px 12px rgba(217, 119, 6, 0.25) !important;
        }
        .btn-action-delete {
            background: rgba(220, 38, 38, 0.1) !important;
            border: 1px solid rgba(220, 38, 38, 0.25) !important;
            color: #DC2626 !important;
        }
        .btn-action-delete:hover {
            background: #DC2626 !important;
            color: #FFFFFF !important;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25) !important;
        }

        /* Standardized Order & Logistics Action Containers */
        .order-action-btn {
            height: 38px !important;
            min-height: 38px !important;
            padding: 0 16px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            font-size: 0.82rem !important;
            font-weight: 600 !important;
            border-radius: 10px !important;
            white-space: nowrap !important;
            text-decoration: none !important;
            box-sizing: border-box !important;
            line-height: 1 !important;
            margin: 0 !important;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .order-action-btn i {
            font-size: 0.95rem;
            line-height: 1;
        }
        .order-action-outline {
            background: #FFFFFF !important;
            border: 1px solid var(--colors-border) !important;
            color: #1A1A1C !important;
        }
        .order-action-outline:hover {
            background: #F2EFE9 !important;
            border-color: var(--colors-accent) !important;
            color: var(--colors-accent) !important;
            transform: translateY(-1px);
        }
        .order-action-waiting {
            background: #FFFFFF !important;
            border: 1px solid var(--colors-border) !important;
            color: var(--colors-muted) !important;
            cursor: default;
        }
        .order-action-success {
            background: #15803D !important;
            border: 1px solid #15803D !important;
            color: #FFFFFF !important;
            box-shadow: 0 4px 14px rgba(22, 163, 74, 0.25) !important;
            cursor: pointer;
        }
        .order-action-success:hover {
            background: #166534 !important;
            border-color: #166534 !important;
            transform: translateY(-1px);
        }
        .order-action-primary {
            background: #1A1A1C !important;
            border: 1px solid #1A1A1C !important;
            color: #FFFFFF !important;
            font-weight: 700 !important;
            box-shadow: 0 4px 14px rgba(28, 26, 24, 0.2) !important;
            cursor: pointer;
        }
        .order-action-primary:hover {
            background: #2C2C2E !important;
            border-color: #2C2C2E !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(28, 26, 24, 0.25) !important;
        }

        /* Radio / Segmented Button Toggles */
        .btn-check:checked + .btn-toggle-in {
            background: rgba(22, 163, 74, 0.15) !important;
            border-color: #15803D !important;
            color: #15803D !important;
            box-shadow: 0 0 12px rgba(22, 163, 74, 0.2) !important;
        }
        .btn-check:checked + .btn-toggle-out {
            background: rgba(220, 38, 38, 0.15) !important;
            border-color: #DC2626 !important;
            color: #DC2626 !important;
            box-shadow: 0 0 12px rgba(220, 38, 38, 0.2) !important;
        }

        /* Tables */
        .table {
            --bs-table-bg: transparent !important;
            --bs-table-color: var(--colors-body) !important;
            margin-bottom: 0;
        }

        .table th {
            color: var(--colors-muted) !important;
            border-bottom: 1px solid #E5E0D5 !important;
            font-size: 12px;
            font-weight: 700;
            padding: 14px 18px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .table td {
            border-bottom: 1px solid #E5E0D5 !important;
            padding: 14px 18px;
            color: #1A1A1C;
            font-size: 14px;
            vertical-align: middle;
        }

        .table tbody tr:hover td {
            background-color: #F8F5EE !important;
        }

        /* Utility Overrides for Warm Paper */
        .text-dark {
            color: #1A1A1C !important;
        }
        .text-muted, .text-secondary {
            color: var(--colors-muted) !important;
        }
        .bg-light {
            background-color: #F2EFE9 !important;
            color: #1A1A1C !important;
        }
        .table-light {
            --bs-table-bg: #FFFFFF !important;
            --bs-table-color: #1A1A1C !important;
            color: #1A1A1C !important;
        }
        .bg-primary-subtle {
            background: rgba(26, 26, 28, 0.08) !important;
            border-color: rgba(26, 26, 28, 0.2) !important;
            color: #1A1A1C !important;
        }
        .bg-success-subtle {
            background: rgba(22, 163, 74, 0.1) !important;
            border-color: rgba(22, 163, 74, 0.25) !important;
            color: #15803D !important;
        }
        .bg-warning-subtle {
            background: rgba(217, 119, 6, 0.1) !important;
            border-color: rgba(217, 119, 6, 0.25) !important;
            color: #D97706 !important;
        }
        .bg-info-subtle {
            background: rgba(2, 132, 199, 0.1) !important;
            border-color: rgba(2, 132, 199, 0.25) !important;
            color: #0284C7 !important;
        }
        .bg-danger-subtle {
            background: rgba(220, 38, 38, 0.1) !important;
            border-color: rgba(220, 38, 38, 0.25) !important;
            color: #DC2626 !important;
        }
        .bg-secondary-subtle {
            background: #F2EFE9 !important;
            border-color: #E5E0D5 !important;
            color: #706E6B !important;
        }

        /* Dropdowns & Modals Glassmorphism */
        .dropdown-menu, .modal-content {
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(16px) saturate(180%) !important;
            -webkit-backdrop-filter: blur(16px) saturate(180%) !important;
            border: 1px solid rgba(229, 224, 213, 0.7) !important;
            box-shadow: 0 16px 40px rgba(28, 26, 24, 0.1), inset 0 1px 0 0 rgba(255, 255, 255, 0.9) !important;
            border-radius: 14px !important;
            padding: 8px;
        }

        .dropdown-item {
            color: var(--colors-body) !important;
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 14px;
            transition: all 0.15s ease;
        }

        .notification-item-btn {
            transition: background 0.15s ease;
        }

        .notification-item-btn:hover {
            background: #F2EFE9 !important;
        }

        .dropdown-item:hover {
            background-color: #F2EFE9 !important;
            color: #1A1A1C !important;
        }

        /* Warm Paper Alert Styling */
        .alert-success,
        .alert-success * {
            background-color: rgba(22, 163, 74, 0.08) !important;
            border-color: rgba(22, 163, 74, 0.25) !important;
            color: #15803D !important;
        }

        .alert-danger,
        .alert-danger * {
            background-color: rgba(220, 38, 38, 0.08) !important;
            border-color: rgba(220, 38, 38, 0.25) !important;
            color: #DC2626 !important;
        }

        .alert-warning,
        .alert-warning * {
            background-color: rgba(217, 119, 6, 0.08) !important;
            border-color: rgba(217, 119, 6, 0.25) !important;
            color: #D97706 !important;
        }

        /* Modern Status Badges */
        .badge {
            font-weight: 600;
            border-radius: 6px;
            padding: 5px 10px;
        }

        .badge-gradient-success {
            background: rgba(22, 163, 74, 0.1) !important;
            border: 1px solid rgba(22, 163, 74, 0.25) !important;
            color: #15803D !important;
        }

        .badge-gradient-danger {
            background: rgba(220, 38, 38, 0.1) !important;
            border: 1px solid rgba(220, 38, 38, 0.25) !important;
            color: #DC2626 !important;
        }

        .badge-gradient-warning {
            background: rgba(217, 119, 6, 0.1) !important;
            border: 1px solid rgba(217, 119, 6, 0.25) !important;
            color: #D97706 !important;
        }

        .badge-gradient-accent {
            background: rgba(26, 26, 28, 0.08) !important;
            border: 1px solid rgba(26, 26, 28, 0.2) !important;
            color: #1A1A1C !important;
        }

        .badge-gradient-info {
            background: #F2EFE9 !important;
            border: 1px solid var(--colors-hairline) !important;
            color: #1A1A1C !important;
        }

        .badge-gradient-neutral {
            background: #F2EFE9 !important;
            border: 1px solid #E5E0D5 !important;
            color: #1A1A1C !important;
        }

        /* Settings Sub-Navigation Items */
        .settings-nav-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 13px 18px;
            min-height: 52px;
            border-radius: 12px;
            background: #FFFFFF;
            border: 1px solid #E5E0D5;
            color: #1A1A1C;
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .settings-nav-item i {
            font-size: 1.25rem;
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
        }
        .settings-nav-item:hover {
            background: #F2EFE9;
            border-color: #1A1A1C;
            color: #1A1A1C;
            transform: translateX(3px);
        }
        .settings-nav-item.active {
            background: #1A1A1C !important;
            border-color: #1A1A1C !important;
            color: #FFFFFF !important;
            font-weight: 700;
            box-shadow: 0 4px 14px rgba(28, 26, 24, 0.25);
        }
        .settings-nav-item.active i {
            color: #FFFFFF !important;
        }

        /* Responsive Sidebar */
        @media (max-width: 991.98px) {
            .sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            .sidebar.show {
                margin-left: 0 !important;
                box-shadow: 12px 0 36px rgba(28, 26, 24, 0.15);
            }
            .main-wrapper {
                margin-left: 0 !important;
            }
            .content-area {
                padding: 1.25rem;
            }
        }

        /* Slide Page / Pagination Styles */
        .pagination-glass-wrap {
            background: #FFFFFF;
            border: 1px solid #E5E0D5;
            border-radius: 18px;
            padding: 0.85rem 1.6rem;
            box-shadow: 0 4px 20px -2px rgba(28, 26, 24, 0.05);
            width: 100%;
        }

        .pagination {
            margin-bottom: 0;
            gap: 6px;
            --bs-pagination-bg: transparent;
            --bs-pagination-border-color: transparent;
        }

        .page-item .page-link {
            background: #FFFFFF !important;
            border: 1px solid #E5E0D5 !important;
            color: #1A1A1C !important;
            border-radius: 10px !important;
            min-width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.88rem;
            padding: 0 12px;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: none !important;
        }

        .page-item .page-link:hover {
            background: #F2EFE9 !important;
            border-color: #1A1A1C !important;
            color: #1A1A1C !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 14px rgba(28, 26, 24, 0.08) !important;
        }

        .page-item.active .page-link {
            background: #1A1A1C !important;
            border-color: #1A1A1C !important;
            color: #FFFFFF !important;
            font-weight: 800 !important;
            box-shadow: 0 4px 16px rgba(28, 26, 24, 0.2) !important;
            transform: translateY(-1px);
        }

        .page-item.disabled .page-link {
            background: rgba(242, 239, 233, 0.5) !important;
            border-color: rgba(229, 224, 213, 0.6) !important;
            color: rgba(112, 110, 107, 0.4) !important;
            pointer-events: none;
            cursor: not-allowed;
        }

        .pagination-glass-wrap .small,
        .pagination-glass-wrap .text-muted,
        .pagination-glass-wrap p,
        .card-footer .small,
        .card-footer .text-muted,
        .card-footer p {
            color: var(--colors-muted) !important;
            font-size: 0.88rem !important;
            margin-bottom: 0;
            font-weight: 500;
        }

        .pagination-glass-wrap .fw-semibold,
        .card-footer .fw-semibold {
            color: #1A1A1C !important;
            font-weight: 700 !important;
        }

        /* Buyer Slide Pagination (Universal) */
        .slide-pagination-wrap {
            margin: 2.5rem 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .slide-nav-btn {
            background: #FFFFFF !important;
            border: 1px solid #E5E0D5 !important;
            color: #1A1A1C !important;
            font-size: 0.95rem;
            font-weight: 600;
            width: 38px;
            height: 38px;
            padding: 0 !important;
            border-radius: 12px !important;
            text-decoration: none !important;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(28, 26, 24, 0.05);
        }

        .slide-nav-btn:hover:not(.disabled) {
            background: #F2EFE9 !important;
            border-color: #1A1A1C !important;
            color: #1A1A1C !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(28, 26, 24, 0.08);
        }

        .slide-nav-btn.disabled {
            color: rgba(112, 110, 107, 0.4) !important;
            border-color: rgba(229, 224, 213, 0.6) !important;
            background: rgba(242, 239, 233, 0.5) !important;
            cursor: not-allowed;
            pointer-events: none;
            box-shadow: none !important;
        }

        .slide-nav-page {
            min-width: 38px;
            height: 38px;
            padding: 0 10px;
            border-radius: 12px !important;
            background: #FFFFFF !important;
            border: 1px solid #E5E0D5 !important;
            color: #1A1A1C !important;
            font-size: 0.88rem;
            font-weight: 600;
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 2px 8px rgba(28, 26, 24, 0.05);
        }

        .slide-nav-page:hover:not(.active) {
            background: #F2EFE9 !important;
            border-color: #1A1A1C !important;
            color: #1A1A1C !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(28, 26, 24, 0.08);
        }

        .slide-nav-page.active {
            background: #1A1A1C !important;
            border-color: #1A1A1C !important;
            color: #FFFFFF !important;
            font-weight: 800 !important;
            box-shadow: 0 4px 14px rgba(28, 26, 24, 0.2) !important;
            transform: translateY(-1px);
        }

        .slide-nav-dots {
            color: var(--colors-muted);
            padding: 0 6px;
            font-weight: 700;
            user-select: none;
        }

        /* Warm Paper Global Text Normalization */
        .text-white {
            color: #1A1A1C !important;
        }
        .btn .text-white,
        .btn-primary .text-white,
        .btn-primary,
        .btn-primary *,
        .badge-discount-tag,
        .badge-discount-tag *,
        .badge-dark,
        .badge-dark *,
        .page-item.active .page-link,
        .settings-nav-item.active,
        .settings-nav-item.active *,
        .btn-action-view:hover,
        .btn-action-move:hover,
        .btn-action-edit:hover,
        .btn-action-delete:hover,
        .order-action-primary,
        .order-action-success,
        .cust-avatar-circle,
        .cust-action-badge,
        .btn-action-view:hover i,
        .btn-action-move:hover i,
        .btn-action-edit:hover i,
        .btn-action-delete:hover i,
        [style*="background: #1A1A1C"] .text-white,
        [style*="background:#1A1A1C"] .text-white,
        [style*="background: #DC2626"] .text-white,
        [style*="background: #15803D"] .text-white,
        [style*="background:#DC2626"] .text-white,
        [style*="background:#15803D"] .text-white {
            color: #FFFFFF !important;
        }
    </style>
    @stack('styles')
</head>
@php
    $isCustomer = Auth::check() && Auth::user()->isPembeli();
    $isAdmin = Auth::check() && Auth::user()->isAdmin();
    $isStaff = Auth::check() && Auth::user()->isStaff();
    $pendingOrdersCount = $isCustomer ? \App\Models\Sale::where('buyer_id', Auth::id())->where('payment_status', 'pending')->count() : 0;
@endphp
<body class="{{ $isCustomer ? 'customer-layout' : '' }}">
    <div class="top-hairline"></div>

    @if(!$isCustomer)
        <!-- Sidebar for Admin & Staff (Premium Dark Matte) -->
        <aside class="sidebar d-flex flex-column" id="sidebar">
            <div class="sidebar-brand-wrap flex-shrink-0">
                <a href="{{ route('dashboard') }}" class="brand">
                    <img src="{{ asset('images/logo.png') }}" alt="Ctrl+R" style="width: 36px; height: 36px; object-fit: contain; border-radius: 8px; flex-shrink: 0;">
                    <div>
                        <div class="brand-title">Ctrl+R</div>
                        <small style="font-size: 0.7rem; color: var(--colors-muted); font-weight: 600;">
                            {{ $isAdmin ? 'ADMIN // OWNER' : 'STAFF // GUDANG' }}
                        </small>
                    </div>
                </a>
                <button class="btn btn-outline-secondary sidebar-toggle-btn" 
                        type="button" 
                        id="sidebarToggle"
                        title="Sembunyikan / Tampilkan Sidebar"
                        style="height: 36px; width: 36px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px;">
                    <i class="bi bi-layout-sidebar-inset" style="font-size: 1.15rem;"></i>
                </button>
            </div>

            <div class="sidebar-nav-body py-2 no-scrollbar">
                <div class="section-heading">Navigasi Utama</div>
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2"></i>
                    <span>Dashboard Operasional</span>
                </a>

                @if($isAdmin)
                    <div class="section-heading">Master Data</div>
                    <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.index') || request()->routeIs('products.create') || request()->routeIs('products.edit') || request()->routeIs('products.show') ? 'active' : '' }}">
                        <i class="bi bi-boxes"></i>
                        <span>Kelola Produk</span>
                    </a>
                    <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        <i class="bi bi-tags"></i>
                        <span>Kategori Produk</span>
                    </a>
                    <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                        <i class="bi bi-truck"></i>
                        <span>Pemasok (Suppliers)</span>
                    </a>
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span>Kelola Pengguna</span>
                    </a>
                    <a href="{{ route('banners.index') }}" class="nav-link {{ request()->routeIs('banners.*') ? 'active' : '' }}">
                        <i class="bi bi-card-image"></i>
                        <span>Banner Promo</span>
                    </a>
                @endif

                @if($isStaff)
                    <div class="section-heading">Katalog Produk</div>
                    <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.index') || request()->routeIs('products.show') ? 'active' : '' }}">
                        <i class="bi bi-boxes"></i>
                        <span>Daftar Produk (Read-Only)</span>
                    </a>
                @endif

                <div class="section-heading">Transaksi Inventaris</div>
                <a href="{{ route('purchases.index') }}" class="nav-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
                    <i class="bi bi-box-arrow-in-down"></i>
                    <span>Pembelian (Stock-In)</span>
                </a>
                <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                    <i class="bi bi-cart-check"></i>
                    <span>Penjualan (Stock-Out)</span>
                </a>

                @php
                    $lowStockBadgeCount = \App\Models\Product::lowStock()->count();
                @endphp
                <a href="{{ route('products.low-stock') }}" class="nav-link d-flex align-items-center justify-content-between {{ request()->routeIs('products.low-stock') ? 'active' : '' }}">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-triangle" style="color: var(--colors-accent);"></i>
                        <span>Stok Kritis</span>
                    </div>
                    @if($lowStockBadgeCount > 0)
                        <span class="badge" style="background: rgba(220, 38, 38, 0.08); color: #DC2626; border: 1px solid rgba(220, 38, 38, 0.25); font-size: 11px;">{{ $lowStockBadgeCount }}</span>
                    @endif
                </a>

                <a href="{{ route('stock-movements.index') }}" class="nav-link {{ request()->routeIs('stock-movements.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-left-right"></i>
                    <span>Mutasi Stok</span>
                </a>

                @if($isAdmin)
                    <div class="section-heading">Laporan & Audit</div>
                    <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <i class="bi bi-graph-up-arrow"></i>
                        <span>Laporan Bisnis</span>
                    </a>
                @endif

                <div class="section-heading">Akun & Sistem</div>
                <a href="{{ route('customer.settings') }}" class="nav-link {{ request()->routeIs('customer.settings*', 'settings.*') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i>
                    <span>Pengaturan Akun</span>
                </a>
                <form action="{{ route('logout') }}" method="POST" class="m-0 mt-1">
                    @csrf
                    <button type="submit" class="nav-link border-0 w-100 text-start bg-transparent d-flex align-items-center gap-2" style="cursor: pointer; color: #ff8599 !important;">
                        <i class="bi bi-box-arrow-right text-danger"></i>
                        <span>Keluar Akun</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Mobile Sidebar Backdrop Overlay -->
        <div id="sidebarBackdrop" class="position-fixed top-0 start-0 w-100 h-100 d-none" style="background: rgba(0,0,0,0.55); z-index: 1035; backdrop-filter: blur(4px);"></div>
    @endif

    <div class="main-wrapper">
        @if($isCustomer)
            <!-- Top Navbar for Customer Layout (Premium Dark Matte) -->
            <nav class="customer-top-navbar">
                <div class="container-fluid px-3 px-lg-4 d-flex align-items-center justify-content-between gap-3">
                    <!-- Left: Brand + Nav Links (Home, Catalog, Promo, My Orders) -->
                    <div class="d-flex align-items-center">
                        <a href="{{ route('dashboard') }}" class="brand-title d-flex align-items-center gap-2">
                            <img src="{{ asset('images/logo.png') }}" alt="Ctrl+R" style="width: 36px; height: 36px; object-fit: contain; border-radius: 8px; flex-shrink: 0;">
                            <span>Ctrl+R</span>
                        </a>

                        <div class="d-none d-md-flex align-items-center cust-nav-links">
                            <a href="{{ route('dashboard') }}" class="cust-nav-link {{ request()->routeIs('dashboard') || request()->routeIs('customer.catalog*') ? 'active' : '' }}">
                                Beranda
                            </a>
                            <a href="{{ route('customer.promo') }}" class="cust-nav-link {{ request()->routeIs('customer.promo*') ? 'active' : '' }}">
                                Promo
                            </a>
                            <a href="{{ route('customer.orders.index') }}" class="cust-nav-link {{ request()->routeIs('customer.orders*') ? 'active' : '' }}">
                                Pesanan Saya
                                @if($pendingOrdersCount > 0)
                                    <span class="badge badge-gradient-warning font-monospace ms-1" style="font-size: 0.65rem; padding: 2px 6px;">{{ $pendingOrdersCount }}</span>
                                @endif
                            </a>
                            <a href="{{ route('customer.activity') }}" class="cust-nav-link {{ request()->routeIs('customer.activity*') ? 'active' : '' }}">
                                Riwayat Aktivitas
                            </a>
                        </div>
                    </div>

                    <!-- Right: Search Bar + Cart + Notification + Profile -->
                    <div class="d-flex align-items-center gap-2 gap-sm-3">
                        <!-- Search Bar Pill -->
                        <form action="{{ route('customer.catalog') }}" method="GET" class="cust-search-pill d-none d-sm-flex">
                            <button type="submit" class="cust-search-btn" title="Cari Hardware">
                                <i class="bi bi-search"></i>
                            </button>
                            <input type="text" 
                                   name="search" 
                                   id="navbarSearchInput"
                                   class="cust-search-input" 
                                   placeholder="Cari laptop, smartphone, komponen..." 
                                   value="{{ request('search') }}"
                                   autocomplete="off">
                            @if(request('search'))
                                <a href="{{ route('customer.catalog') }}" class="cust-search-btn" title="Hapus Pencarian">
                                    <i class="bi bi-x-lg" style="font-size: 0.75rem;"></i>
                                </a>
                            @endif
                        </form>

                        <!-- Shopping Cart Icon with Dedicated Cart Page -->
                        @php
                            $cartCount = array_sum(session('cart', []));
                        @endphp
                        <a href="{{ route('customer.cart') }}" class="cust-action-icon {{ request()->routeIs('customer.cart*') ? 'active-profile' : '' }}" title="Keranjang Belanja">
                            <i class="bi bi-cart3"></i>
                            @if($cartCount > 0)
                                <span class="cust-action-badge">{{ $cartCount }}</span>
                            @endif
                        </a>

                        <!-- Notification Bell -->
                        @include('layouts.notification_bell', [
                            'buttonClass' => 'cust-action-icon',
                            'isCustomer' => true
                        ])

                        <!-- User Profile Direct Link to Account Settings (No Dropdown Menu) -->
                        <a href="{{ route('customer.settings') }}" class="cust-profile-btn border-0 text-decoration-none {{ request()->routeIs('customer.settings*', 'settings.*') ? 'active-profile' : '' }}" title="Pengaturan Akun & Profil">
                            @if(Auth::user()->profile && Auth::user()->profile->avatar && file_exists(public_path(Auth::user()->profile->avatar)))
                                <img src="{{ asset(Auth::user()->profile->avatar) }}" alt="Avatar" class="cust-avatar-circle object-fit-cover" style="object-fit: cover;">
                            @else
                                <div class="cust-avatar-circle">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="cust-profile-text d-none d-sm-flex">
                                <span class="cust-profile-greeting">Halo,</span>
                                <span class="cust-profile-name">{{ Auth::user()->name }}</span>
                            </div>
                        </a>
                    </div>
                </div>
            </nav>
        @else
            <!-- Top Bar for Admin & Staff (Premium Dark Matte) -->
            <header class="top-navbar">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-outline-secondary sidebar-toggle-top d-inline-flex d-lg-none" 
                            id="sidebarToggleTop" 
                            type="button" 
                            title="Buka Sidebar"
                            style="height: 36px; width: 36px; padding: 0; align-items: center; justify-content: center; border-radius: 10px;">
                        <i class="bi bi-layout-sidebar-inset" style="font-size: 1.15rem;"></i>
                    </button>
                    <div>
                        <h6 class="mb-0 text-white fw-bold">@yield('page_heading', 'Dashboard Operasional')</h6>
                        <small style="color: var(--colors-muted); font-size: 12px;">
                            {{ now()->translatedFormat('l, d F Y') }}
                        </small>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <!-- Notification Bell for Admin & Staff -->
                    @include('layouts.notification_bell', [
                        'buttonClass' => 'btn btn-outline-secondary d-flex align-items-center justify-content-center',
                        'buttonStyle' => 'width: 38px; height: 38px; border-radius: 10px; background: #FFFFFF; border: 1px solid var(--colors-border); color: var(--colors-text);',
                        'isCustomer' => false
                    ])

                    <a href="{{ route('customer.settings') }}" 
                       class="btn p-1 ps-2 pe-3 d-flex align-items-center gap-2 border-0 rounded-pill admin-nav-profile-btn text-decoration-none {{ request()->routeIs('customer.settings*', 'settings.*') ? 'active-profile' : '' }}" 
                       title="Pengaturan Akun & Profil">
                        @if(Auth::user()->profile && Auth::user()->profile->avatar && file_exists(public_path(Auth::user()->profile->avatar)))
                            <img src="{{ asset(Auth::user()->profile->avatar) }}" alt="Avatar" class="rounded-circle object-fit-cover" style="width: 36px; height: 36px; border: 1.5px solid var(--colors-accent);">
                        @else
                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" 
                                 style="width: 36px; height: 36px; background: var(--colors-accent); font-size: 0.88rem; color: #FFFFFF !important;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="d-none d-sm-flex flex-column text-start" style="line-height: 1.2;">
                            <span class="fw-bold text-truncate nav-profile-name" style="font-size: 0.84rem; max-width: 140px; color: #1A1A1C !important;">{{ Auth::user()->name }}</span>
                            <span class="nav-profile-role" style="font-size: 0.72rem; color: #706E6B !important; font-weight: 600;">{{ $isAdmin ? 'Administrator' : 'Staff Gudang' }}</span>
                        </div>
                    </a>
                </div>
            </header>
        @endif

        <main class="content-area">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const sidebarBackdrop = document.getElementById('sidebarBackdrop');

            // Restore desktop preference
            if (localStorage.getItem('admin_sidebar_collapsed') === 'true' && window.innerWidth >= 992) {
                document.body.classList.add('sidebar-collapsed');
            }

            function handleToggle() {
                if (window.innerWidth < 992) {
                    if (sidebar) {
                        const isOpen = sidebar.classList.toggle('show');
                        if (sidebarBackdrop) {
                            if (isOpen) {
                                sidebarBackdrop.classList.remove('d-none');
                            } else {
                                sidebarBackdrop.classList.add('d-none');
                            }
                        }
                    }
                } else {
                    document.body.classList.toggle('sidebar-collapsed');
                    const isCollapsed = document.body.classList.contains('sidebar-collapsed');
                    localStorage.setItem('admin_sidebar_collapsed', isCollapsed ? 'true' : 'false');
                }
            }

            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarToggleTop = document.getElementById('sidebarToggleTop');
            if (sidebarToggle) sidebarToggle.addEventListener('click', handleToggle);
            if (sidebarToggleTop) sidebarToggleTop.addEventListener('click', handleToggle);

            if (sidebarBackdrop) {
                sidebarBackdrop.addEventListener('click', function () {
                    if (sidebar) {
                        sidebar.classList.remove('show');
                    }
                    sidebarBackdrop.classList.add('d-none');
                });
            }
        });
    </script>
    @include('components.confirm-modal')
    @stack('scripts')
</body>
</html>
