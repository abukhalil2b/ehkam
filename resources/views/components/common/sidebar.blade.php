@inject('sidebarService', 'App\Services\SidebarService')
@php
    $userPermissions = auth()->user()->getPermissions();
    $sidebarSections = $sidebarService->getSidebarSections();
@endphp

<div :class="{ 'dark text-white-dark': $store.app.semidark }">
    <nav x-data="sidebar"
        class="sidebar fixed min-h-screen h-full top-0 bottom-0 w-[260px] shadow-[5px_0_25px_0_rgba(94,92,154,0.1)] z-50 transition-all duration-300">
        <div class="bg-white dark:bg-[#0e1726] h-full overflow-y-scroll">
            <div class="flex justify-between items-center px-4 py-3">
                <a href="{{ route('dashboard') }}" class="main-logo flex items-center shrink-0">
                <span class="text-[10px]">
                    متابعة المشاريع والمؤشرات
                </span>
                 </a>
                <div class="flex items-center gap-2">
                     <a href="javascript:;"
                        class="collapse-icon w-8 h-8 rounded-full flex items-center hover:bg-gray-500/10 dark:hover:bg-dark-light/10 dark:text-white-light transition duration-300 rtl:rotate-180"
                        @click="$dispatch('toggle-sidebar-sections', true)"
                        title="Expand All">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                             <path d="M12 6V12M12 12V18M12 12H18M12 12H6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                    <a href="javascript:;"
                        class="collapse-icon w-8 h-8 rounded-full flex items-center hover:bg-gray-500/10 dark:hover:bg-dark-light/10 dark:text-white-light transition duration-300 rtl:rotate-180"
                        @click="$dispatch('toggle-sidebar-sections', false)"
                        title="Collapse All">
                         <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 12L18 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                         </svg>
                    </a>
                    <a href="javascript:;"
                        class="collapse-icon w-8 h-8 rounded-full flex items-center hover:bg-gray-500/10 dark:hover:bg-dark-light/10 dark:text-white-light transition duration-300 rtl:rotate-180"
                        @click="$store.app.toggleSidebar()">
                        <svg class="w-5 h-5 m-auto" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5" stroke="currentColor"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                </div>
            </div>

            <ul>
                @foreach($sidebarSections as $section)
                    @php
                        // check if user has at least one permission for this section
                        $showSection = false;
                        foreach ($section['links'] as $link) {
                            if (!$link['permission'] || in_array($link['permission'], $userPermissions) || auth()->id() == 1) {
                                $showSection = true;
                                break;
                            }
                        }
                    @endphp

                    @if($showSection)
                        {{-- Collapsible Component --}}
                        <li class="menu nav-item" x-data="{ open: false }" @toggle-sidebar-sections.window="open = $event.detail">

                            {{-- Clickable Header --}}
                            <button type="button" class="nav-link group w-full" :class="{ 'active': open }"
                                @click="open = !open">
                                <div class="flex items-center">
                                    {{-- Optional: Icon for the Category (Can be customized per section via SidebarService if
                                    needed, using general icon for now) --}}
                                    <svg class="group-hover:!text-primary shrink-0" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                        <path d="M10 9H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                        <path d="M10 12H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                        <path d="M10 15H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    </svg>
                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark font-bold">
                                        {{ $section['title'] }}
                                    </span>
                                </div>
                                <div :class="{ 'rtl:rotate-90 -rotate-90': !open }">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>

                            {{-- Collapsible Links --}}
                            <ul x-show="open" x-collapse style="display: none;" class="sub-menu text-gray-500">
                                @foreach($section['links'] as $link)
                                    @if(!$link['permission'] || in_array($link['permission'], $userPermissions) || auth()->id() == 1)
                                        <li>
                                            <a href="{{ isset($link['params']) ? route($link['route'], $link['params']) : route($link['route']) }}"
                                                class="block p-2 hover:text-primary">
                                                {{ $link['label'] }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @endif
                @endforeach
                <li>
                    <div class="py-16">

                    </div>
                </li>
            </ul>
        </div>
    </nav>
</div>

<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("sidebar", () => ({
            init() {
                const selector = document.querySelector('.sidebar ul a[href="' + window.location.pathname + '"]');
                if (selector) {
                    selector.classList.add('active');
                    const ul = selector.closest('ul.sub-menu');
                    if (ul) {
                        let ele = ul.closest('li.menu').querySelectorAll('.nav-link');
                        if (ele) {
                            ele = ele[0];
                            setTimeout(() => {
                                ele.click();
                            });
                        }
                    }
                }
            },
        }));
    });
</script>