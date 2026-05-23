<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>e-Tendering - SIMADA</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind Configuration -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        solarized: {
                            base3: '#fdf6e3', // background
                            base2: '#eee8d5', // highlights/cards
                            base1: '#93a1a1', // comments/secondary content
                            base0: '#839496', // body text
                            base00: '#657b83', // darker text
                            base01: '#586e75', // darkest text/headings
                            yellow: '#b58900',
                            orange: '#cb4b16',
                            red: '#dc322f',
                            magenta: '#d33682',
                            violet: '#6c71c4',
                            blue: '#268bd2',
                            cyan: '#2aa198',
                            green: '#859900',
                        }
                    },
                    boxShadow: {
                        'soft': '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)',
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            background-color: #fdf6e3; /* solarized base3 */
            color: #657b83; /* solarized base00 */
        }
        
        .solar-card {
            background-color: #eee8d5; /* solarized base2 */
            border: 1px solid #e5e0c8;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .solar-nav {
            background-color: #eee8d5;
            border-bottom: 1px solid #e5e0c8;
        }
        
        .btn-primary {
            background-color: #268bd2;
            color: #fdf6e3;
            transition: all 0.2s ease;
        }
        .btn-primary:hover {
            background-color: #1f77b4;
            transform: translateY(-1px);
        }
        
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #fdf6e3; }
        ::-webkit-scrollbar-thumb { background: #93a1a1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #657b83; }
    </style>

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="font-sans min-h-screen flex flex-col text-lg">
    <nav class="solar-nav sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex items-center space-x-3 mr-8">
                        <div class="w-8 h-8 rounded bg-solarized-blue flex items-center justify-center">
                            <svg class="w-5 h-5 text-solarized-base3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <a href="{{ route('dashboard') }}" class="text-xl font-bold text-solarized-base01 tracking-tight">
                            e-Tendering SIMADA
                        </a>
                    </div>
                    
                    @if(auth()->check())
                    <div class="hidden sm:flex sm:space-x-4 sm:items-center">
                        <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-md text-base font-medium text-solarized-base00 hover:text-solarized-blue hover:bg-solarized-base3 transition-colors {{ request()->routeIs('dashboard') ? 'bg-solarized-base3 text-solarized-blue' : '' }}">Beranda Dashboard</a>
                        
                        @if(auth()->user()->role == 'Admin' || auth()->user()->role == 'PPK')
                        <a href="{{ route('integrasi-spse.index') }}" class="px-3 py-2 rounded-md text-base font-medium text-solarized-base00 hover:text-solarized-blue hover:bg-solarized-base3 transition-colors {{ request()->routeIs('integrasi-spse.*') ? 'bg-solarized-base3 text-solarized-blue' : '' }}">Tarik Data SPSE (API)</a>
                        @endif

                        <a href="{{ route('paket-pengadaan.index') }}" class="px-3 py-2 rounded-md text-base font-medium text-solarized-base00 hover:text-solarized-blue hover:bg-solarized-base3 transition-colors {{ request()->routeIs('paket-pengadaan.*') ? 'bg-solarized-base3 text-solarized-blue' : '' }}">Daftar Paket SPSE</a>
                        
                        @if(auth()->user()->role == 'Admin')
                        <a href="{{ route('personil.index') }}" class="px-3 py-2 rounded-md text-base font-medium text-solarized-base00 hover:text-solarized-blue hover:bg-solarized-base3 transition-colors {{ request()->routeIs('personil.*') ? 'bg-solarized-base3 text-solarized-blue' : '' }}">Daftar Pokja / Pejabat Pengadaan</a>
                        <a href="{{ route('pengguna.index') }}" class="px-3 py-2 rounded-md text-base font-medium text-solarized-base00 hover:text-solarized-blue hover:bg-solarized-base3 transition-colors {{ request()->routeIs('pengguna.*') ? 'bg-solarized-base3 text-solarized-blue' : '' }}">Manajemen Akun Pengguna</a>
                        @endif
                    </div>
                    @endif
                </div>
                
                <div class="flex items-center space-x-4">
                    @if(auth()->check())
                        <!-- Desktop Profile & Logout -->
                        <div class="hidden sm:flex items-center space-x-2 text-base font-medium text-solarized-base00 bg-solarized-base3 px-4 py-2 rounded border border-solarized-base2">
                            <span class="w-2.5 h-2.5 rounded-full {{ auth()->user()->role === 'Admin' ? 'bg-solarized-green' : 'bg-solarized-blue' }}"></span>
                            <span>{{ auth()->user()->username }} ({{ auth()->user()->role }})</span>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="hidden sm:inline">
                            @csrf
                            <button type="submit" class="text-base font-medium text-solarized-red hover:text-solarized-orange transition-colors">Logout</button>
                        </form>
                        
                        <!-- Mobile Hamburger Button -->
                        <div class="flex items-center sm:hidden">
                            <button type="button" id="mobile-menu-button" class="inline-flex items-center justify-center p-2 rounded-md text-solarized-base00 hover:text-solarized-blue hover:bg-solarized-base2 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-solarized-blue" aria-controls="mobile-menu" aria-expanded="false">
                                <span class="sr-only">Buka menu utama</span>
                                <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true" id="icon-menu">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                                <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true" id="icon-close">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Mobile Menu Drawer -->
        @if(auth()->check())
        <div class="sm:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 bg-solarized-base2 border-t border-solarized-base1 shadow-inner">
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-solarized-base00 hover:text-solarized-blue hover:bg-solarized-base3 {{ request()->routeIs('dashboard') ? 'bg-solarized-base3 text-solarized-blue' : '' }}">Beranda Dashboard</a>
                
                @if(auth()->user()->role == 'Admin' || auth()->user()->role == 'PPK')
                <a href="{{ route('integrasi-spse.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-solarized-base00 hover:text-solarized-blue hover:bg-solarized-base3 {{ request()->routeIs('integrasi-spse.*') ? 'bg-solarized-base3 text-solarized-blue' : '' }}">Tarik Data SPSE (API)</a>
                @endif

                <a href="{{ route('paket-pengadaan.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-solarized-base00 hover:text-solarized-blue hover:bg-solarized-base3 {{ request()->routeIs('paket-pengadaan.*') ? 'bg-solarized-base3 text-solarized-blue' : '' }}">Daftar Paket SPSE</a>
                
                @if(auth()->user()->role == 'Admin')
                <a href="{{ route('personil.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-solarized-base00 hover:text-solarized-blue hover:bg-solarized-base3 {{ request()->routeIs('personil.*') ? 'bg-solarized-base3 text-solarized-blue' : '' }}">Daftar Pokja / Pejabat Pengadaan</a>
                <a href="{{ route('pengguna.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-solarized-base00 hover:text-solarized-blue hover:bg-solarized-base3 {{ request()->routeIs('pengguna.*') ? 'bg-solarized-base3 text-solarized-blue' : '' }}">Manajemen Akun Pengguna</a>
                @endif
                
                <div class="border-t border-solarized-base1 pt-4 pb-1 mt-2">
                    <div class="flex items-center px-3 mb-2">
                        <span class="w-2.5 h-2.5 rounded-full mr-2 {{ auth()->user()->role === 'Admin' ? 'bg-solarized-green' : 'bg-solarized-blue' }}"></span>
                        <div class="text-base font-medium text-solarized-base01">{{ auth()->user()->username }} ({{ auth()->user()->role }})</div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-solarized-red hover:bg-solarized-base3">Logout</button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </nav>

    <!-- Mobile Menu JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('mobile-menu-button');
            const menu = document.getElementById('mobile-menu');
            const iconMenu = document.getElementById('icon-menu');
            const iconClose = document.getElementById('icon-close');

            if (btn && menu) {
                btn.addEventListener('click', () => {
                    menu.classList.toggle('hidden');
                    iconMenu.classList.toggle('hidden');
                    iconClose.classList.toggle('hidden');
                });
            }
        });
    </script>
    <main class="flex-grow max-w-7xl mx-auto py-8 sm:px-6 lg:px-8 w-full relative z-10">
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>
