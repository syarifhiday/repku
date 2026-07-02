<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Repku')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'gf-yellow': '#FFD500',
                        'gf-black': '#0A0A0A',
                        'gf-dark': '#161616',
                        'gf-gray': '#2A2A2A',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; }
        .btn-primary {
            display: inline-block;
            background: #FFD500; color: #0A0A0A;
            font-weight: 800; text-transform: uppercase;
            letter-spacing: 0.05em; padding: 0.65rem 1.25rem;
            border: 3px solid #0A0A0A; cursor: pointer;
            text-align: center; text-decoration: none;
            transition: background 0.1s, color 0.1s;
        }
        .btn-primary:hover { background: #0A0A0A; color: #FFD500; }
        .btn-outline {
            display: inline-block;
            background: transparent; color: #FFD500;
            font-weight: 800; text-transform: uppercase;
            letter-spacing: 0.05em; padding: 0.65rem 1.25rem;
            border: 3px solid #FFD500; cursor: pointer;
            text-align: center; text-decoration: none;
            transition: background 0.1s, color 0.1s;
        }
        .btn-outline:hover { background: #FFD500; color: #0A0A0A; }
        .card-flat { background: #161616; border: 3px solid #2A2A2A; }
        .card-flat:hover { border-color: #FFD500; }
        .input-flat {
            background: #0A0A0A; border: 2px solid #2A2A2A;
            color: #fff; padding: 0.65rem 1rem; width: 100%;
        }
        .input-flat:focus { outline: none; border-color: #FFD500; }
        select.input-flat option { background: #161616; }
    </style>
</head>
<body class="bg-gf-black text-white min-h-screen">

@auth
<nav style="background:#0A0A0A; border-bottom:3px solid #FFD500;">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-center justify-between" style="height:56px;">

            {{-- Logo --}}
            <a href="{{ route('dashboard') }}" class="text-xl font-black tracking-tight flex-shrink-0"
               style="color:#FFD500; letter-spacing:-0.02em;">
                REP<span style="color:#fff;">KU</span>
            </a>

            {{-- Desktop menu --}}
            <div class="hidden md:flex items-center gap-5 text-xs font-black uppercase tracking-wide">
                <a href="{{ route('dashboard') }}"
                   class="{{ request()->routeIs('dashboard') ? 'text-yellow-400' : 'text-gray-300 hover:text-white' }}">
                   Dashboard
                </a>
                <a href="{{ route('programs.index') }}"
                   class="{{ request()->routeIs('programs.*') ? 'text-yellow-400' : 'text-gray-300 hover:text-white' }}">
                   Program
                </a>
                <a href="{{ route('workout.create') }}"
                   class="{{ request()->routeIs('workout.create') ? 'text-yellow-400' : 'text-gray-300 hover:text-white' }}">
                   Latihan
                </a>
                <a href="{{ route('workout.history') }}"
                   class="{{ request()->routeIs('workout.history') ? 'text-yellow-400' : 'text-gray-300 hover:text-white' }}">
                   Riwayat
                </a>
                <a href="{{ route('profile.edit') }}"
                   class="{{ request()->routeIs('profile.*') ? 'text-yellow-400' : 'text-gray-300 hover:text-white' }}">
                   Profil
                </a>
                @if(auth()->user()->isAdmin())
                <span style="color:#2A2A2A; font-weight:100;">|</span>
                <a href="{{ route('admin.exercises.index') }}"
                   class="{{ request()->routeIs('admin.exercises.*') ? 'text-yellow-400' : 'text-gray-300 hover:text-white' }}">
                   Gerakan
                </a>
                <a href="{{ route('admin.programs.index') }}"
                   class="{{ request()->routeIs('admin.programs.*') ? 'text-yellow-400' : 'text-gray-300 hover:text-white' }}">
                   Program (Admin)
                </a>
                @endif
            </div>

            {{-- Desktop logout + Hamburger --}}
            <div class="flex items-center gap-3">
                {{-- Logout (desktop only) --}}
                <form action="{{ route('logout') }}" method="POST" class="hidden md:block">
                    @csrf
                    <button class="btn-outline text-xs" style="padding:0.4rem 0.75rem;">Logout</button>
                </form>

                {{-- Hamburger (mobile only) --}}
                <button id="navToggle" class="md:hidden flex flex-col gap-1 p-2" onclick="toggleNav()"
                        aria-label="Menu">
                    <span class="block w-5 h-0.5 bg-white"></span>
                    <span class="block w-5 h-0.5 bg-white"></span>
                    <span class="block w-5 h-0.5 bg-white"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile dropdown --}}
    <div id="mobileNav" class="hidden md:hidden" style="border-top:2px solid #2A2A2A;">
        <div class="px-4 py-3 flex flex-col gap-1">
            <a href="{{ route('dashboard') }}"
               class="block py-2 px-3 text-sm font-bold uppercase
               {{ request()->routeIs('dashboard') ? 'text-yellow-400' : 'text-gray-300' }}">
               Dashboard
            </a>
            <a href="{{ route('programs.index') }}"
               class="block py-2 px-3 text-sm font-bold uppercase
               {{ request()->routeIs('programs.*') ? 'text-yellow-400' : 'text-gray-300' }}">
               Program
            </a>
            <a href="{{ route('workout.create') }}"
               class="block py-2 px-3 text-sm font-bold uppercase
               {{ request()->routeIs('workout.create') ? 'text-yellow-400' : 'text-gray-300' }}">
               Latihan
            </a>
            <a href="{{ route('workout.history') }}"
               class="block py-2 px-3 text-sm font-bold uppercase
               {{ request()->routeIs('workout.history') ? 'text-yellow-400' : 'text-gray-300' }}">
               Riwayat
            </a>
            <a href="{{ route('profile.edit') }}"
               class="block py-2 px-3 text-sm font-bold uppercase
               {{ request()->routeIs('profile.*') ? 'text-yellow-400' : 'text-gray-300' }}">
               Profil
            </a>

            @if(auth()->user()->isAdmin())
            <div class="my-1" style="border-top:1px solid #2A2A2A;"></div>
            <p class="px-3 text-xs text-gray-600 uppercase font-bold">Admin</p>
            <a href="{{ route('admin.exercises.index') }}"
               class="block py-2 px-3 text-sm font-bold uppercase
               {{ request()->routeIs('admin.exercises.*') ? 'text-yellow-400' : 'text-gray-300' }}">
               Kelola Gerakan
            </a>
            <a href="{{ route('admin.programs.index') }}"
               class="block py-2 px-3 text-sm font-bold uppercase
               {{ request()->routeIs('admin.programs.*') ? 'text-yellow-400' : 'text-gray-300' }}">
               Kelola Program
            </a>
            @endif

            <div class="mt-2 pt-2" style="border-top:1px solid #2A2A2A;">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn-outline w-full text-sm">Logout</button>
                </form>
            </div>
        </div>
    </div>
</nav>
@endauth

<main class="max-w-6xl mx-auto px-4 py-8">
    @if(session('success'))
        <div class="p-4 mb-6 font-bold text-sm" style="background:#161616; border:3px solid #FFD500;">
            ✓ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 mb-6 font-bold text-sm" style="background:#161616; border:3px solid #ef4444;">
            ✗ {{ session('error') }}
        </div>
    @endif

    @yield('content')
</main>

<footer style="border-top:2px solid #2A2A2A;" class="mt-20 py-6 text-center">
    <p class="text-xs text-gray-600 uppercase tracking-widest font-bold">
        Repku &copy; {{ date('Y') }} — Train Hard, Track Harder.
    </p>
</footer>

<script>
function toggleNav() {
    const nav = document.getElementById('mobileNav');
    nav.classList.toggle('hidden');
}
// Tutup menu kalau klik di luar
document.addEventListener('click', function(e) {
    const nav = document.getElementById('mobileNav');
    const toggle = document.getElementById('navToggle');
    if (nav && toggle && !nav.contains(e.target) && !toggle.contains(e.target)) {
        nav.classList.add('hidden');
    }
});
</script>

</body>
</html>
