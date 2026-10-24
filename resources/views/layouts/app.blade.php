<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Ctrl+R</title>
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
            /* Target Color System */
            --colors-canvas: #0D0C1D;
            --colors-primary: #161B33;
            --colors-card-alt: #1F2447;
            --colors-glass: rgba(71, 73, 115, 0.22);
            --colors-hairline: rgba(166, 156, 172, 0.22);
            --colors-accent: #F1DAC4;
            --colors-accent-hover: #ebd0b4;
            --colors-accent-text: #0D0C1D;
            --colors-on-primary: #FFFFFF;
            --colors-body: #FFFFFF;
            --colors-muted: #A69CAC;
            --colors-faint: #A69CAC;
            --colors-neutral-1: #A69CAC;
            --shadow-lift: rgba(0, 0, 0, 0.55) 0px 14px 36px 0px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--colors-canvas);
            background-image: 
                radial-gradient(circle at 12% 10%, rgba(71, 73, 115, 0.25) 0%, transparent 45%),
                radial-gradient(circle at 88% 25%, rgba(22, 27, 51, 0.5) 0%, transparent 50%),
                radial-gradient(circle at 50% 85%, rgba(71, 73, 115, 0.15) 0%, transparent 55%);
            background-attachment: fixed;
            color: var(--colors-body);
            min-height: 100vh;
            letter-spacing: 0px;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            color: #FFFFFF;
        }

        .top-hairline {
            height: 2px;
            background: linear-gradient(90deg, var(--colors-accent) 0%, rgba(71, 73, 115, 0.8) 50%, rgba(22, 27, 51, 0.3) 100%);
            position: sticky;
            top: 0;
            z-index: 1060;
        }

        /* Sidebar for Admin & Staff (Modern Glassmorphic #161B33) */
        .sidebar {
            width: var(--sidebar-width);
            height: calc(100vh - 2px);
            background: linear-gradient(180deg, rgba(22, 27, 51, 0.96) 0%, rgba(13, 12, 29, 0.98) 100%);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-right: 1px solid var(--colors-hairline);
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

        /* Collapsed Sidebar on Desktop */
        body.sidebar-collapsed .sidebar {
            margin-left: calc(-1 * var(--sidebar-width));
        }

        body.sidebar-collapsed .main-wrapper {
            margin-left: 0 !important;
        }

        .sidebar-user-footer {
            background: rgba(13, 12, 29, 0.65);
            border-top: 1px solid var(--colors-hairline);
        }

        .sidebar-profile-card {
            background: rgba(71, 73, 115, 0.2) !important;
            border: 1px solid rgba(166, 156, 172, 0.2) !important;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .sidebar-profile-card:hover,
        .sidebar-profile-card[aria-expanded="true"] {
            background: rgba(71, 73, 115, 0.4) !important;
            border-color: var(--colors-accent) !important;
            transform: translateY(-1px);
        }

        .sidebar .brand {
            padding: 1.35rem 1.4rem;
            border-bottom: 1px solid var(--colors-hairline);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--colors-accent);
        }

        .brand-dot {
            width: 10px;
            height: 10px;
            background: radial-gradient(circle, var(--colors-accent) 40%, rgba(241, 218, 196, 0.4) 100%);
            box-shadow: 0 0 10px var(--colors-accent);
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
            color: var(--colors-faint);
            padding: 0.65rem 1rem;
            margin: 3px 12px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
            border-left: 3px solid transparent;
        }

        .sidebar .nav-link:hover {
            color: #FFFFFF;
            background: rgba(71, 73, 115, 0.3);
            transform: translateX(3px);
        }

        .sidebar .nav-link.active {
            color: var(--colors-accent);
            background: linear-gradient(90deg, rgba(241, 218, 196, 0.18) 0%, rgba(71, 73, 115, 0.18) 100%);
            border-left: 3px solid var(--colors-accent);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
            font-weight: 600;
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

        /* Navigation Bar for Customer (Image 2 Modern E-commerce Layout) */
        .customer-top-navbar {
            background: rgba(22, 27, 51, 0.82);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(166, 156, 172, 0.2);
            position: sticky;
            top: 0;
            z-index: 1050;
            height: 70px;
            display: flex;
            align-items: center;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.35);
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
            color: #FFFFFF;
        }

        .cust-nav-link.active {
            color: #FFFFFF;
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
            box-shadow: 0 0 10px var(--colors-accent);
        }

        /* Search bar pill */
        .cust-search-pill {
            display: flex;
            align-items: center;
            background: rgba(13, 12, 29, 0.65);
            border: 1px solid rgba(166, 156, 172, 0.22);
            border-radius: 20px;
            padding: 0 14px;
            height: 40px;
            gap: 8px;
            width: 280px;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .cust-search-pill:focus-within {
            background: rgba(22, 27, 51, 0.95);
            border-color: var(--colors-accent);
            box-shadow: 0 0 16px rgba(241, 218, 196, 0.2);
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
            color: #FFFFFF !important;
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
            border-radius: 12px;
            background: rgba(71, 73, 115, 0.18);
            border: 1px solid rgba(166, 156, 172, 0.2);
            color: #FFFFFF;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            text-decoration: none;
            position: relative;
            transition: all 0.2s ease;
        }

        .cust-action-icon:hover {
            background: rgba(71, 73, 115, 0.35);
            border-color: var(--colors-accent);
            color: var(--colors-accent);
            transform: translateY(-1px);
        }

        .cust-action-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #EF4444;
            color: #FFFFFF;
            font-size: 0.65rem;
            font-weight: 700;
            min-width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--colors-primary);
        }

        /* Profile button */
        .cust-profile-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(71, 73, 115, 0.18);
            border: 1px solid rgba(166, 156, 172, 0.2);
            border-radius: 20px;
            padding: 4px 12px 4px 6px;
            color: #FFFFFF;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .cust-profile-btn:hover,
        .cust-profile-btn.active-profile,
        .cust-profile-btn[aria-expanded="true"] {
            background: rgba(71, 73, 115, 0.35);
            border-color: var(--colors-accent);
        }

        .cust-avatar-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--colors-accent) 0%, rgba(71, 73, 115, 0.8) 100%);
            color: var(--colors-accent-text);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.8rem;
            border: 1.5px solid rgba(255, 255, 255, 0.2);
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
            color: #FFFFFF;
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

        /* Top Navbar for Admin & Staff (#161B33) */
        .top-navbar {
            background: rgba(22, 27, 51, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--colors-hairline);
            padding: 0.75rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 2px;
            z-index: 1020;
            height: 64px;
        }

        .content-area {
            padding: 2rem;
            flex-grow: 1;
            background-color: transparent;
        }

        /* Cards & Containers (Rich Gradient Glassmorphism) */
        .card, .glass-card, .minimal-card, .kpi-card {
            background: linear-gradient(145deg, rgba(71, 73, 115, 0.22) 0%, rgba(22, 27, 51, 0.7) 100%) !important;
            border: 1px solid rgba(166, 156, 172, 0.22) !important;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 20px !important;
            box-shadow: 0 14px 35px -8px rgba(0, 0, 0, 0.5), inset 0 1px 0 0 rgba(255, 255, 255, 0.08) !important;
            color: var(--colors-body);
            padding: 24px;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .card:hover, .glass-card:hover {
            border-color: rgba(241, 218, 196, 0.35) !important;
            box-shadow: 0 18px 40px -8px rgba(0, 0, 0, 0.55), 0 0 24px -6px rgba(241, 218, 196, 0.15) !important;
        }

        .card-header {
            background-color: transparent !important;
            border-bottom: 1px solid var(--colors-hairline) !important;
            color: #FFFFFF !important;
            padding: 16px 24px;
            font-size: 1.05rem;
            font-weight: 700;
        }

        .card-footer {
            background-color: transparent !important;
            border-top: 1px solid var(--colors-hairline) !important;
            padding: 16px 24px;
        }

        /* Form Controls */
        .form-control, .form-select, .form-control-minimal, .form-select-minimal, .minimal-input {
            background-color: rgba(22, 27, 51, 0.85) !important;
            border: 1px solid var(--colors-hairline) !important;
            color: #FFFFFF !important;
            font-size: 15px;
            border-radius: 12px !important;
            height: 44px;
            padding: 0 16px;
            box-shadow: none !important;
            transition: all 0.2s ease;
        }

        .form-control::placeholder, .form-control-minimal::placeholder, .minimal-input::placeholder {
            color: var(--colors-muted) !important;
        }

        .form-control:focus, .form-select:focus, .form-control-minimal:focus, .minimal-input:focus {
            background-color: rgba(22, 27, 51, 0.95) !important;
            border-color: var(--colors-accent) !important;
            color: #FFFFFF !important;
            outline: 0px !important;
            box-shadow: 0 0 0 3px rgba(241, 218, 196, 0.22) !important;
        }

        .form-select option {
            background-color: var(--colors-primary) !important;
            color: #FFFFFF !important;
        }

        .input-group-text {
            background-color: var(--colors-primary) !important;
            border: 1px solid var(--colors-hairline) !important;
            color: var(--colors-faint) !important;
            border-radius: 12px 0 0 12px !important;
        }

        /* Buttons (Cream CTA #F1DAC4 with Gradient) */
        .btn-primary, .btn-rm-primary, .btn-blue-primary, .btn-accent-cta, .btn-minimal-red, .btn-minimal-blue {
            background: linear-gradient(135deg, var(--colors-accent) 0%, #ebd0b4 100%) !important;
            border: 1px solid var(--colors-accent) !important;
            color: var(--colors-accent-text) !important;
            font-weight: 700;
            border-radius: 12px !important;
            height: 42px;
            padding: 8px 20px;
            box-shadow: 0 4px 16px rgba(241, 218, 196, 0.25) !important;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-primary:hover, .btn-rm-primary:hover, .btn-blue-primary:hover, .btn-accent-cta:hover, .btn-minimal-red:hover, .btn-minimal-blue:hover {
            background: linear-gradient(135deg, #FFF0E0 0%, var(--colors-accent) 100%) !important;
            border-color: #FFF0E0 !important;
            color: var(--colors-accent-text) !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 22px rgba(241, 218, 196, 0.4) !important;
        }

        /* Secondary / Outline Button */
        .btn-secondary, .btn-default-dark {
            background: linear-gradient(135deg, rgba(22, 27, 51, 0.9) 0%, rgba(13, 12, 29, 0.95) 100%) !important;
            border: 1px solid var(--colors-hairline) !important;
            color: #FFFFFF !important;
            font-weight: 500;
            border-radius: 12px !important;
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
            background: rgba(71, 73, 115, 0.35) !important;
            border-color: var(--colors-accent) !important;
            color: var(--colors-accent) !important;
            transform: translateY(-1px);
        }

        .btn-outline-primary, .btn-outline-secondary, .btn-glass-outline, .btn-minimal-outline {
            background: rgba(71, 73, 115, 0.15) !important;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(166, 156, 172, 0.25) !important;
            color: #FFFFFF !important;
            font-weight: 500;
            border-radius: 12px !important;
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

        .btn-outline-primary:hover, .btn-outline-secondary:hover, .btn-glass-outline:hover, .btn-minimal-outline:hover {
            background: rgba(71, 73, 115, 0.35) !important;
            color: var(--colors-accent) !important;
            border-color: var(--colors-accent) !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25) !important;
        }

        /* Tables */
        .table {
            --bs-table-bg: transparent !important;
            --bs-table-color: var(--colors-body) !important;
            margin-bottom: 0;
        }

        .table th {
            color: var(--colors-muted) !important;
            border-bottom: 1px solid var(--colors-hairline) !important;
            font-size: 12px;
            font-weight: 700;
            padding: 14px 18px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .table td {
            border-bottom: 1px solid var(--colors-hairline) !important;
            padding: 14px 18px;
            color: #FFFFFF;
            font-size: 14px;
            vertical-align: middle;
        }

        .table tbody tr:hover td {
            background-color: rgba(71, 73, 115, 0.2) !important;
        }

        /* High Contrast Dark Mode Utility Overrides */
        .text-dark {
            color: #FFFFFF !important;
        }
        .text-muted, .text-secondary {
            color: var(--colors-muted) !important;
        }
        .bg-light {
            background-color: rgba(71, 73, 115, 0.25) !important;
            color: #FFFFFF !important;
        }
        .table-light {
            --bs-table-bg: rgba(22, 27, 51, 0.85) !important;
            --bs-table-color: #FFFFFF !important;
            color: #FFFFFF !important;
        }
        .bg-primary-subtle {
            background: linear-gradient(135deg, rgba(241, 218, 196, 0.2) 0%, rgba(71, 73, 115, 0.25) 100%) !important;
            color: var(--colors-accent) !important;
        }
        .bg-success-subtle {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2) 0%, rgba(5, 150, 105, 0.1) 100%) !important;
            color: #6EE7B7 !important;
        }
        .bg-warning-subtle {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.2) 0%, rgba(217, 119, 6, 0.1) 100%) !important;
            color: #FDE68A !important;
        }
        .bg-info-subtle {
            background: linear-gradient(135deg, rgba(56, 189, 248, 0.2) 0%, rgba(14, 165, 233, 0.1) 100%) !important;
            color: #7DD3FC !important;
        }
        .bg-danger-subtle {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.2) 0%, rgba(185, 28, 28, 0.1) 100%) !important;
            color: #FCA5A5 !important;
        }

        /* Dropdowns */
        .dropdown-menu {
            background: linear-gradient(160deg, rgba(22, 27, 51, 0.98) 0%, rgba(13, 12, 29, 0.98) 100%) !important;
            border: 1px solid var(--colors-hairline) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            box-shadow: var(--shadow-lift) !important;
            border-radius: 16px !important;
            padding: 8px;
        }

        .dropdown-item {
            color: var(--colors-body) !important;
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 14px;
            transition: all 0.15s ease;
        }

        .dropdown-item:hover {
            background-color: rgba(71, 73, 115, 0.35) !important;
            color: var(--colors-accent) !important;
        }

        /* Modern Gradient Status Badges */
        .badge {
            font-weight: 600;
            border-radius: 8px;
            padding: 6px 12px;
        }

        .badge-gradient-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.22) 0%, rgba(5, 150, 105, 0.1) 100%) !important;
            border: 1px solid rgba(16, 185, 129, 0.45) !important;
            color: #6EE7B7 !important;
        }

        .badge-gradient-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.22) 0%, rgba(185, 28, 28, 0.1) 100%) !important;
            border: 1px solid rgba(239, 68, 68, 0.45) !important;
            color: #FCA5A5 !important;
        }

        .badge-gradient-warning {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.22) 0%, rgba(217, 119, 6, 0.1) 100%) !important;
            border: 1px solid rgba(245, 158, 11, 0.45) !important;
            color: #FDE68A !important;
        }

        .badge-gradient-accent {
            background: linear-gradient(135deg, rgba(241, 218, 196, 0.2) 0%, rgba(71, 73, 115, 0.25) 100%) !important;
            border: 1px solid rgba(241, 218, 196, 0.45) !important;
            color: var(--colors-accent) !important;
        }

        .badge-gradient-info {
            background: linear-gradient(135deg, rgba(56, 189, 248, 0.22) 0%, rgba(14, 165, 233, 0.1) 100%) !important;
            border: 1px solid rgba(56, 189, 248, 0.45) !important;
            color: #7DD3FC !important;
        }

        /* Responsive Sidebar */
        @media (max-width: 991.98px) {
            .sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            .sidebar.show {
                margin-left: 0 !important;
                box-shadow: 12px 0 36px rgba(0, 0, 0, 0.7);
            }
            .main-wrapper {
                margin-left: 0 !important;
            }
            .content-area {
                padding: 1.25rem;
            }
        }
        /* Slide Page / Pagination Glassmorphic Styles */
        .pagination-glass-wrap {
            background: linear-gradient(145deg, rgba(71, 73, 115, 0.2) 0%, rgba(22, 27, 51, 0.75) 100%);
            border: 1px solid rgba(166, 156, 172, 0.22);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-radius: 18px;
            padding: 0.85rem 1.6rem;
            box-shadow: 0 14px 35px -8px rgba(0, 0, 0, 0.5), inset 0 1px 0 0 rgba(255, 255, 255, 0.08);
            width: 100%;
        }

        .pagination {
            margin-bottom: 0;
            gap: 6px;
            --bs-pagination-bg: transparent;
            --bs-pagination-border-color: transparent;
        }

        .page-item .page-link {
            background: rgba(71, 73, 115, 0.22) !important;
            border: 1px solid rgba(166, 156, 172, 0.22) !important;
            color: #FFFFFF !important;
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
            background: rgba(71, 73, 115, 0.45) !important;
            border-color: var(--colors-accent) !important;
            color: var(--colors-accent) !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.3) !important;
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, var(--colors-accent) 0%, var(--colors-accent-hover) 100%) !important;
            border-color: var(--colors-accent) !important;
            color: var(--colors-accent-text) !important;
            font-weight: 800 !important;
            box-shadow: 0 4px 16px rgba(241, 218, 196, 0.35) !important;
            transform: translateY(-1px);
        }

        .page-item.disabled .page-link {
            background: rgba(22, 27, 51, 0.35) !important;
            border-color: rgba(166, 156, 172, 0.12) !important;
            color: rgba(166, 156, 172, 0.35) !important;
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
            color: var(--colors-accent) !important;
            font-weight: 700 !important;
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
        <!-- Sidebar for Admin & Staff (#161B33) -->
        <aside class="sidebar d-flex flex-column" id="sidebar">
            <a href="{{ route('dashboard') }}" class="brand flex-shrink-0">
                <span class="brand-dot"></span>
                <div>
                    <div class="brand-title">Ctrl+R</div>
                    <small style="font-size: 0.7rem; color: var(--colors-muted); font-weight: 600;">
                        {{ $isAdmin ? 'ADMIN // OWNER' : 'STAFF // GUDANG' }}
                    </small>
                </div>
            </a>

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
                        <span class="badge" style="background: rgba(241, 218, 196, 0.2); color: var(--colors-accent); font-size: 11px;">{{ $lowStockBadgeCount }}</span>
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
                <a href="{{ route('customer.settings') }}" class="nav-link {{ request()->routeIs('customer.settings*') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i>
                    <span>Pengaturan Akun</span>
                </a>
            </div>

            <!-- Sidebar User & Account Profile Card (Bottom Sticky) -->
            <div class="sidebar-user-footer p-3 border-top flex-shrink-0">
                <div class="dropdown dropup">
                    <button class="btn w-100 d-flex align-items-center justify-content-between p-2 text-start border-0 text-white rounded-3 sidebar-profile-card"
                            type="button" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false"
                            title="Menu Akun & Pengaturan">
                        <div class="d-flex align-items-center gap-2 overflow-hidden">
                            @if(Auth::user()->profile && Auth::user()->profile->avatar && file_exists(public_path(Auth::user()->profile->avatar)))
                                <img src="{{ asset(Auth::user()->profile->avatar) }}" alt="Avatar" class="rounded-circle object-fit-cover flex-shrink-0" style="width: 34px; height: 34px; border: 1.5px solid var(--colors-accent);">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0" 
                                     style="width: 34px; height: 34px; background: linear-gradient(135deg, var(--colors-accent) 0%, #ebd0b4 100%); font-size: 0.85rem; color: #0D0C1D !important;">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="overflow-hidden" style="min-width: 0;">
                                <div class="fw-bold text-white text-truncate" style="font-size: 0.84rem; line-height: 1.2;">
                                    {{ Auth::user()->name }}
                                </div>
                                <div class="text-truncate" style="font-size: 0.72rem; color: var(--colors-accent); font-weight: 600;">
                                    {{ $isAdmin ? 'Administrator' : 'Staff Gudang' }}
                                </div>
                            </div>
                        </div>
                        <i class="bi bi-three-dots-vertical" style="color: var(--colors-muted); font-size: 0.9rem;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-dark w-100 shadow-lg border small mb-2" style="background: rgba(22, 27, 51, 0.98); border-color: var(--colors-hairline) !important; border-radius: 14px;">
                        <li class="px-3 py-2 border-bottom" style="border-color: var(--colors-hairline) !important;">
                            <div class="fw-semibold text-white text-truncate">{{ Auth::user()->name }}</div>
                            <div class="text-truncate" style="color: var(--colors-muted); font-size: 11px;">{{ Auth::user()->email }}</div>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="{{ route('customer.settings') }}">
                                <i class="bi bi-gear" style="color: var(--colors-accent) !important;"></i>
                                <span>Pengaturan Akun</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="{{ route('landing') }}" target="_blank">
                                <i class="bi bi-shop text-info"></i>
                                <span>Kunjungi Storefront</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1" style="border-color: var(--colors-hairline);"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger py-2 d-flex align-items-center gap-2" onclick="return confirm('Keluar dari sesi?')">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Keluar (Logout)</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>

        <!-- Mobile Sidebar Backdrop Overlay -->
        <div id="sidebarBackdrop" class="position-fixed top-0 start-0 w-100 h-100 d-none" style="background: rgba(0,0,0,0.55); z-index: 1035; backdrop-filter: blur(4px);"></div>
    @endif

    <div class="main-wrapper">
        @if($isCustomer)
            <!-- Top Navbar for Customer Layout (#161B33 / Image 2 Layout) -->
            <nav class="customer-top-navbar">
                <div class="container-fluid px-3 px-lg-4 d-flex align-items-center justify-content-between gap-3">
                    <!-- Left: Brand + Nav Links (Home, Catalog, Promo, My Orders) -->
                    <div class="d-flex align-items-center">
                        <a href="{{ route('dashboard') }}" class="brand-title">
                            Ctrl+R
                        </a>

                        <div class="d-none d-md-flex align-items-center cust-nav-links">
                            <a href="{{ route('dashboard') }}" class="cust-nav-link {{ request()->routeIs('dashboard') || request()->routeIs('customer.catalog*') ? 'active' : '' }}">
                                Beranda
                            </a>
                            <a href="{{ route('dashboard') }}#promo" class="cust-nav-link">
                                Promo
                            </a>
                            <a href="{{ route('customer.orders.index') }}" class="cust-nav-link {{ request()->routeIs('customer.orders*') ? 'active' : '' }}">
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

                        <!-- Shopping Cart Icon with Badge -->
                        <a href="{{ route('customer.orders.index') }}" class="cust-action-icon" title="Pesanan & Keranjang">
                            <i class="bi bi-cart3"></i>
                            @if($pendingOrdersCount > 0)
                                <span class="cust-action-badge">{{ $pendingOrdersCount }}</span>
                            @endif
                        </a>

                        <!-- Notification Bell -->
                        <div class="dropdown">
                            <button class="cust-action-icon border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi">
                                <i class="bi bi-bell"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border small">
                                <li class="px-3 py-2 border-bottom" style="border-color: var(--colors-hairline) !important;">
                                    <span class="fw-bold text-white">Notifikasi</span>
                                </li>
                                <li class="px-3 py-3 text-center text-muted small">
                                    <i class="bi bi-bell-slash fs-4 d-block mb-1"></i>
                                    Tidak ada notifikasi baru
                                </li>
                            </ul>
                        </div>

                        <!-- User Profile Direct Link to Account Settings (No Dropdown Menu) -->
                        <a href="{{ route('customer.settings') }}" class="cust-profile-btn border-0 text-decoration-none {{ request()->routeIs('customer.settings*') ? 'active-profile' : '' }}" title="Pengaturan Akun & Profil">
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
            <!-- Top Bar for Admin & Staff (#161B33) -->
            <header class="top-navbar">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-outline-secondary" id="sidebarToggle" type="button" 
                            title="Sembunyikan / Tampilkan Sidebar"
                            style="height: 38px; width: 38px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px;">
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
                    <a href="{{ route('landing') }}" target="_blank" class="btn btn-outline-secondary d-none d-sm-inline-flex align-items-center gap-2" style="height: 38px; padding: 0 14px; font-size: 13px; border-radius: 10px;" title="Lihat Toko Publik">
                        <i class="bi bi-box-arrow-up-right" style="color: var(--colors-accent);"></i>
                        <span>Storefront</span>
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
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarBackdrop = document.getElementById('sidebarBackdrop');

            // Restore desktop preference
            if (localStorage.getItem('admin_sidebar_collapsed') === 'true' && window.innerWidth >= 992) {
                document.body.classList.add('sidebar-collapsed');
            }

            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function () {
                    if (window.innerWidth < 992) {
                        const isOpen = sidebar.classList.toggle('show');
                        if (sidebarBackdrop) {
                            if (isOpen) {
                                sidebarBackdrop.classList.remove('d-none');
                            } else {
                                sidebarBackdrop.classList.add('d-none');
                            }
                        }
                    } else {
                        document.body.classList.toggle('sidebar-collapsed');
                        const isCollapsed = document.body.classList.contains('sidebar-collapsed');
                        localStorage.setItem('admin_sidebar_collapsed', isCollapsed ? 'true' : 'false');
                    }
                });
            }

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
    @stack('scripts')
</body>
</html>
