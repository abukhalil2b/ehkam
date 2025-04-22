
<nav class="-mx-3 flex flex-1 justify-end">
    @auth
        <a href="{{ url('/dashboard') }}"
            class="rounded-md px-3 py-2 !text-black ring-1 ring-transparent transition hover:!black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:!white dark:hover:!white/80 dark:focus-visible:ring-white">
            لوحة القيادة
        </a>
    @else
        <a href="{{ route('login') }}"
            class="rounded-md px-3 py-2 !black ring-1 ring-transparent transition hover:!black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:!white dark:hover:!white/80 dark:focus-visible:ring-white">
            تسجيل الدخول
        </a>
    @endauth
</nav>
