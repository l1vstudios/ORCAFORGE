
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CRUD BUILDER BRAINSOFT')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html, body {
            height: 100%;
            overflow: auto;
        }

        #app-container {
            display: flex;
            min-height: 100%;
            overflow: hidden;
        }

        #mainContent {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        #mainInner {
            flex: 1;
            overflow-y: auto;
            background-color: #f3f4f6;
        }
    </style>
</head>

<body class="text-gray-800">
    <div id="app-container">
        <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden hidden"></div>

        <div id="sidebarWrapper"
            class="fixed md:static inset-y-0 left-0 z-40 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
            @include('components.sidebar')
        </div>

        <div id="mainContent">
            <div class="md:hidden flex items-center justify-between p-4 bg-white shadow-sm border-b">
                <button id="mobileToggle" class="text-gray-600 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
                <span class="font-semibold text-gray-700">ORCAFORGE</span>
                <div class="w-6"></div>
            </div>

            <main id="mainInner" class="p-4 md:p-6">
                @yield('content')
            </main>
        </div>
    </div>

<script>
    const sidebarWrapper = document.getElementById('sidebarWrapper');
    const overlay = document.getElementById('overlay');
    const mobileToggle = document.getElementById('mobileToggle');
    const collapseBtn = document.getElementById('collapseBtn');
    const chevronIcon = document.getElementById('chevronIcon');
    const sidebar = document.getElementById('sidebar');

    function openMobileSidebar() {
        sidebarWrapper.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeMobileSidebar() {
        sidebarWrapper.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    mobileToggle?.addEventListener('click', () => {
        if (sidebarWrapper.classList.contains('-translate-x-full')) {
            openMobileSidebar();
        } else {
            closeMobileSidebar();
        }
    });

    overlay?.addEventListener('click', closeMobileSidebar);

    if (window.innerWidth < 768) {
        document.querySelectorAll('#sidebar a').forEach(link => {
            link.addEventListener('click', closeMobileSidebar);
        });
    }

    collapseBtn?.addEventListener('click', () => {
        if (window.innerWidth >= 768) {
            sidebar.classList.toggle('w-64');
            sidebar.classList.toggle('w-20');
            chevronIcon.classList.toggle('rotate-180');
            document.querySelectorAll('.sidebar-text').forEach(el => {
                el.classList.toggle('opacity-0');
                el.classList.toggle('invisible');
                el.classList.toggle('w-0');
            });
            requestAnimationFrame(() => {
                document.getElementById('mainInner').scrollTop += 1;
                document.getElementById('mainInner').scrollTop -= 1;
            });
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) {
            overlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        } else {
            closeMobileSidebar();
        }
    });
</script>
</body>

