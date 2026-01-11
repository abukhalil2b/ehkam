<header class="z-40" :class="{ 'dark': $store.app.semidark && $store.app.menu === 'horizontal' }">
    <div class="shadow-sm">

        <div class="relative bg-white flex w-full items-center px-5 py-2 dark:bg-[#0e1726]">
            <div class="horizontal-logo flex lg:hidden justify-between items-center ltr:mr-2 rtl:ml-2">
                <a href="/" class="main-logo flex items-center shrink-0">
                    <img class="w-8 ltr:-ml-1 rtl:-mr-1 inline" src="/assets/images/logo.png" alt="image" />
                    <span
                        class="text-[8px] ltr:ml-1.5 rtl:mr-1.5  text-center hidden md:inline dark:text-white-light transition-all duration-300">

                        متابعة المشاريع والمؤشرات
                    </span>
                </a>

                <a href="javascript:;"
                    class="collapse-icon flex-none dark:text-[#d0d2d6] hover:text-primary dark:hover:text-primary flex lg:hidden ltr:ml-2 rtl:mr-2 p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:bg-white-light/90 dark:hover:bg-dark/60"
                    @click="$store.app.toggleSidebar()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 7L4 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path opacity="0.5" d="M20 12L4 12" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" />
                        <path d="M20 17L4 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                </a>
            </div>

            <div x-data="header"
                class="sm:flex-1 ltr:sm:ml-0 ltr:ml-auto sm:rtl:mr-0 rtl:mr-auto flex items-center space-x-1.5 lg:space-x-2 rtl:space-x-reverse dark:text-[#d0d2d6]">
                @inject('sidebarService', 'App\Services\SidebarService')

                <div class="sm:ltr:mr-auto sm:rtl:ml-auto" x-data="{ 
                    search: false,
                    query: '',
                    links: @js($sidebarService->getSearchableLinks()),
                    get filteredLinks() {
                        if (this.query === '') return [];
                        const q = this.query.toLowerCase();
                        return this.links.filter(link => 
                            link.label.toLowerCase().includes(q) || 
                            link.category.toLowerCase().includes(q) ||
                            (link.keywords && link.keywords.toLowerCase().includes(q))
                        );
                    }
                }" @click.outside="search = false">
                    <form
                        class="sm:relative absolute inset-x-0 sm:top-0 top-1/2 sm:translate-y-0 -translate-y-1/2 sm:mx-0 mx-4 z-10 sm:block hidden"
                        :class="{ '!block': search }" @submit.prevent>
                        <div class="relative">
                            <input type="text" x-model="query"
                                class="form-input font-Helvetica ltr:pl-9 rtl:pr-9 ltr:sm:pr-4 rtl:sm:pl-4 ltr:pr-9 rtl:pl-9 peer sm:bg-transparent bg-gray-100 placeholder:tracking-widest h-10"
                                placeholder="بحث عن صفحة..." @focus="search = true" />
                            <button type="button"
                                class="absolute w-9 h-9 inset-0 ltr:right-auto rtl:left-auto appearance-none peer-focus:text-primary">
                                <svg class="mx-auto" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5"
                                        opacity="0.5" />
                                    <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" />
                                </svg>
                            </button>
                            <button type="button"
                                class="hover:opacity-80 sm:hidden block absolute top-1/2 -translate-y-1/2 ltr:right-2 rtl:left-2"
                                @click="search = false">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <circle opacity="0.5" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="1.5" />
                                    <path d="M14.5 9.50002L9.5 14.5M9.49998 9.5L14.5 14.5" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" />
                                </svg>
                            </button>

                            <!-- Search Results Dropdown -->
                            <div x-show="query.length > 0 && search" x-transition
                                class="absolute top-11 left-0 w-full bg-white dark:bg-gray-800 shadow-lg rounded-md border border-gray-100 dark:border-gray-700 z-50 max-h-60 overflow-y-auto custom-scrollbar">
                                <template x-for="link in filteredLinks" :key="link.url">
                                    <a :href="link.url"
                                        class="block px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-gray-100 dark:border-gray-700 last:border-0">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-medium text-gray-800 dark:text-gray-200"
                                                x-text="link.label"></span>
                                            <span
                                                class="text-xs text-gray-500 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full"
                                                x-text="link.category"></span>
                                        </div>
                                    </a>
                                </template>
                                <div x-show="filteredLinks.length === 0"
                                    class="px-4 py-3 text-sm text-gray-500 text-center">
                                    لا توجد نتائج مطابقة
                                </div>
                            </div>

                        </div>
                    </form>
                    <button type="button"
                        class="search_btn sm:hidden p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:bg-white-light/90 dark:hover:bg-dark/60"
                        @click="search = ! search">
                        <svg class="w-4.5 h-4.5 mx-auto dark:text-[#d0d2d6]" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5"
                                opacity="0.5" />
                            <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                        </svg>
                    </button>
                </div>

                {{-- Notification Dropdown --}}
                <div class="dropdown flex-shrink-0" x-data="dropdown" @click.outside="open = false">
                    <a href="javascript:;" class="relative group" @click="toggle()">
                        <span
                            class="flex items-center justify-center w-9 h-9 rounded-full bg-white-light/40 dark:bg-dark/40 hover:bg-white-light/90 dark:hover:bg-dark/60">
                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-300 group-hover:text-primary transition-colors duration-300"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                :class="{'animate-[swing_1s_ease-in-out_infinite]': {{ auth()->user()->unreadNotifications->count() > 0 ? 'true' : 'false' }} }">
                                <path
                                    d="M12 2C10.3431 2 9 3.34315 9 5V6.15174C6.67897 7.07005 5.09335 9.3249 5.06941 12H5C5 14.7614 5 16.1421 4.14214 17H19.8579C19 16.1421 19 14.7614 19 12C19 9.17157 16.7614 6.87868 14 6.15174V5C14 3.34315 12.6569 2 11 2H12Z"
                                    stroke="currentColor" stroke-width="1.5" />
                                <path opacity="0.5" d="M10 20C10 21.1046 10.8954 22 12 22C13.1046 22 14 21.1046 14 20"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute -top-0.5 -right-0.5 flex h-3 w-3">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                                </span>
                            @endif
                        </span>
                    </a>
                    <ul x-cloak x-show="open" x-transition x-transition.duration.300ms
                        class="ltr:right-0 rtl:left-0 text-dark dark:text-white-dark top-11 !py-0 w-[320px] font-semibold dark:text-white-light/90 shadow-lg rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700">
                        <li
                            class="bg-gray-50/50 dark:bg-[#1b2e4b] p-4 border-b border-gray-100 dark:border-gray-600 flex justify-between items-center backdrop-blur-sm">
                            <h4 class="font-bold text-lg">التنبيهات <span
                                    class="text-xs font-normal text-gray-500">({{ auth()->user()->unreadNotifications->count() }}
                                    جديد)</span></h4>
                            @if(auth()->user()->notifications->count() > 0)
                                <form action="{{ route('notifications.delete_all') }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="text-xs text-red-500 hover:text-red-600 bg-red-50 px-2 py-1 rounded hover:bg-red-100 transition-colors">حذف
                                        الكل</button>
                                </form>
                            @endif
                        </li>
                        <div class="max-h-[350px] overflow-y-auto custom-scrollbar bg-white dark:bg-[#0e1726]">
                            @forelse(auth()->user()->notifications->take(10) as $notification)
                                <li
                                    class="border-b border-gray-100 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-[#20304c] transition group">
                                    <a href="{{ route('notifications.readAndRedirect', $notification->id) }}"
                                        class="block p-4" @click="open = false">
                                        <div class="flex items-start gap-4">
                                            <div
                                                class="w-10 h-10 rounded-full bg-{{ $notification->data['bg_color'] ?? 'primary' }}/10 text-{{ $notification->data['bg_color'] ?? 'primary' }} flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex justify-between items-start">
                                                    <p
                                                        class="text-sm font-bold {{ $notification->read_at ? 'text-gray-500' : 'text-gray-900 dark:text-white' }} group-hover:text-primary transition-colors">
                                                        {{ $notification->data['title'] ?? 'تنبيه' }}
                                                    </p>
                                                    @if(!$notification->read_at)
                                                        <span class="w-2 h-2 rounded-full bg-primary mt-1.5"></span>
                                                    @endif
                                                </div>
                                                <p class="text-xs text-gray-500 mt-1 line-clamp-2 leading-relaxed">
                                                    {{ Str::limit($notification->data['message'] ?? '', 60) }}
                                                </p>
                                                <p class="text-[10px] text-gray-400 mt-2 font-mono flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @empty
                                <li class="py-12 flex flex-col items-center justify-center text-gray-400 gap-3">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <span class="text-sm">لا توجد تنبيهات جديدة</span>
                                </li>
                            @endforelse
                        </div>
                        <li
                            class="p-3 text-center border-t border-gray-100 dark:border-gray-600 bg-gray-50/50 backdrop-blur-sm">
                            <a href="{{ route('notifications.readAll') }}"
                                class="text-xs font-bold text-primary hover:text-primary-dark block py-1 transition-colors">تحديد
                                الكل كمقروء</a>
                        </li>
                    </ul>
                </div>
                {{-- End Notification Dropdown --}}

                <div>
                    <a href="javascript:;" x-cloak x-show="$store.app.theme === 'light'" href="javascript:;"
                        class="flex items-center p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:text-primary hover:bg-white-light/90 dark:hover:bg-dark/60"
                        @click="$store.app.toggleTheme('dark')">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="5" stroke="currentColor" stroke-width="1.5" />
                            <path d="M12 2V4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M12 20V22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M4 12L2 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M22 12L20 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path opacity="0.5" d="M19.7778 4.22266L17.5558 6.25424" stroke="currentColor"
                                stroke-width="1.5" stroke-linecap="round" />
                            <path opacity="0.5" d="M4.22217 4.22266L6.44418 6.25424" stroke="currentColor"
                                stroke-width="1.5" stroke-linecap="round" />
                            <path opacity="0.5" d="M6.44434 17.5557L4.22211 19.7779" stroke="currentColor"
                                stroke-width="1.5" stroke-linecap="round" />
                            <path opacity="0.5" d="M19.7778 19.7773L17.5558 17.5551" stroke="currentColor"
                                stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </a>
                    <a href="javascript:;" x-cloak x-show="$store.app.theme === 'dark'" href="javascript:;"
                        class="flex items-center p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:text-primary hover:bg-white-light/90 dark:hover:bg-dark/60"
                        @click="$store.app.toggleTheme('system')">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M21.0672 11.8568L20.4253 11.469L21.0672 11.8568ZM12.1432 2.93276L11.7553 2.29085V2.29085L12.1432 2.93276ZM21.25 12C21.25 17.1086 17.1086 21.25 12 21.25V22.75C17.9371 22.75 22.75 17.9371 22.75 12H21.25ZM12 21.25C6.89137 21.25 2.75 17.1086 2.75 12H1.25C1.25 17.9371 6.06294 22.75 12 22.75V21.25ZM2.75 12C2.75 6.89137 6.89137 2.75 12 2.75V1.25C6.06294 1.25 1.25 6.06294 1.25 12H2.75ZM15.5 14.25C12.3244 14.25 9.75 11.6756 9.75 8.5H8.25C8.25 12.5041 11.4959 15.75 15.5 15.75V14.25ZM20.4253 11.469C19.4172 13.1373 17.5882 14.25 15.5 14.25V15.75C18.1349 15.75 20.4407 14.3439 21.7092 12.2447L20.4253 11.469ZM9.75 8.5C9.75 6.41182 10.8627 4.5828 12.531 3.57467L11.7553 2.29085C9.65609 3.5593 8.25 5.86509 8.25 8.5H9.75ZM12 2.75C11.9115 2.75 11.8077 2.71008 11.7324 2.63168C11.6686 2.56527 11.6538 2.50244 11.6503 2.47703C11.6461 2.44587 11.6482 2.35557 11.7553 2.29085L12.531 3.57467C13.0342 3.27065 13.196 2.71398 13.1368 2.27627C13.0754 1.82126 12.7166 1.25 12 1.25V2.75ZM21.7092 12.2447C21.6444 12.3518 21.5541 12.3539 21.523 12.3497C21.4976 12.3462 21.4347 12.3314 21.3683 12.2676C21.2899 12.1923 21.25 12.0885 21.25 12H22.75C22.75 11.2834 22.1787 10.9246 21.7237 10.8632C21.286 10.804 20.7293 10.9658 20.4253 11.469L21.7092 12.2447Z"
                                fill="currentColor" />
                        </svg>
                    </a>
                    <a href="javascript:;" x-cloak x-show="$store.app.theme === 'system'" href="javascript:;"
                        class="flex items-center p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:text-primary hover:bg-white-light/90 dark:hover:bg-dark/60"
                        @click="$store.app.toggleTheme('light')">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M3 9C3 6.17157 3 4.75736 3.87868 3.87868C4.75736 3 6.17157 3 9 3H15C17.8284 3 19.2426 3 20.1213 3.87868C21 4.75736 21 6.17157 21 9V14C21 15.8856 21 16.8284 20.4142 17.4142C19.8284 18 18.8856 18 17 18H7C5.11438 18 4.17157 18 3.58579 17.4142C3 16.8284 3 15.8856 3 14V9Z"
                                stroke="currentColor" stroke-width="1.5" />
                            <path opacity="0.5" d="M22 21H2" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                            <path opacity="0.5" d="M15 15H9" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                        </svg>
                    </a>
                </div>

                <div class="dropdown" x-data="dropdown" @click.outside="open = false">
                    <a href="{{ route('notifications.index') }}" class="block p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:text-primary hover:bg-white-light/90 dark:hover:bg-dark/60 relative">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.0001 6.3998C12.0001 5.30932 12.0001 4.76408 11.9168 4.6062C11.6444 4.09015 11.097 3.75781 10.4901 3.75781C9.44445 3.75781 7.69612 3.75781 6.99008 3.75781C5.10522 3.75781 4.16279 3.75781 3.57731 4.3433C2.99182 4.92878 2.99182 5.87121 2.99182 7.75607V15.7533C2.99182 17.6381 2.99182 18.5806 3.57731 19.1661C4.16279 19.7515 5.10522 19.7515 6.99008 19.7515V19.7515C7.29008 19.7516 7.44009 19.7516 7.55621 19.8249C7.81882 19.9906 7.9406 20.29 7.89299 20.6125C7.87193 20.7551 7.76997 20.8911 7.56605 21.163L6.96025 21.9707C6.42582 22.6833 6.1586 23.0396 6.27315 23.3614C6.38769 23.6832 6.81231 23.7716 7.66155 23.9485L7.92558 24.0035C10.7029 24.5822 13.5939 23.3283 15.0298 20.9221C15.1582 20.7067 15.2225 20.5991 15.2225 20.4862C15.2225 20.2526 15.2225 19.9862 15.2225 19.7515C17.1074 19.7515 18.0498 19.7515 18.6353 19.1661C19.2208 18.5806 19.2208 17.6381 19.2208 15.7533V12.3986" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <path opacity="0.5" d="M12.0001 6.3998C12.0001 5.30932 12.0001 4.76408 11.9168 4.6062C11.6444 4.09015 11.097 3.75781 10.4901 3.75781C9.44445 3.75781 7.69612 3.75781 6.99008 3.75781C5.10522 3.75781 4.16279 3.75781 3.57731 4.3433C2.99182 4.92878 2.99182 5.87121 2.99182 7.75607V15.7533C2.99182 17.6381 2.99182 18.5806 3.57731 19.1661C4.16279 19.7515 5.10522 19.7515 6.99008 19.7515V19.7515C7.29008 19.7516 7.44009 19.7516 7.55621 19.8249C7.81882 19.9906 7.9406 20.29 7.89299 20.6125C7.87193 20.7551 7.76997 20.8911 7.56605 21.163L6.96025 21.9707C6.42582 22.6833 6.1586 23.0396 6.27315 23.3614C6.38769 23.6832 6.81231 23.7716 7.66155 23.9485L7.92558 24.0035C10.7029 24.5822 13.5939 23.3283 15.0298 20.9221C15.1582 20.7067 15.2225 20.5991 15.2225 20.4862C15.2225 20.2526 15.2225 19.9862 15.2225 19.7515C17.1074 19.7515 18.0498 19.7515 18.6353 19.1661C19.2208 18.5806 19.2208 17.6381 19.2208 15.7533V12.3986" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M19 8C20.6569 8 22 6.65685 22 5C22 3.34315 20.6569 2 19 2C17.3431 2 16 3.34315 16 5C16 6.65685 17.3431 8 19 8Z" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                        
                        @php
                            $unread = auth()->check() ? auth()->user()->unreadNotifications->count() : 0;
                        @endphp
                        @if($unread > 0)
                            <span class="absolute top-0 right-0 w-3 h-3 bg-red-500 rounded-full"></span>
                        @endif
                    </a>
                </div>

                <div class="dropdown flex-shrink-0" x-data="dropdown" @click.outside="open = false">
                    <a href="javascript:;" class="relative group" @click="toggle()">
                        <span>
                            @if (Auth::user()->gender == 'male')
                                <img class="w-9 h-9 rounded-full object-cover saturate-50 group-hover:saturate-100"
                                    src="/assets/images/avatar/avatar.png" alt="image" />
                            @else
                                <img class="w-9 h-9 rounded-full object-cover saturate-50 group-hover:saturate-100"
                                    src="/assets/images/avatar/avatar-female.png" alt="image" />
                            @endif
                        </span>
                    </a>
                    <ul x-cloak x-show="open" x-transition x-transition.duration.300ms
                        class="ltr:right-0 rtl:left-0 text-dark dark:text-white-dark top-11 !py-0 w-[230px] font-semibold dark:text-white-light/90">
                        <li>
                            <div class="flex items-center px-4 py-4">
                                <div class="flex-none">
                                    @if (Auth::user()->gender == 'male')
                                        <img class="rounded-md w-10 h-10 object-cover"
                                            src="/assets/images/avatar/avatar.png" alt="image" />
                                    @else
                                        <img class="rounded-md w-10 h-10 object-cover"
                                            src="/assets/images/avatar/avatar-female.png" alt="image" />
                                    @endif
                                </div>
                                <div class="ltr:pl-4 rtl:pr-4 truncate">
                                    <div class="text-xs">
                                        {{ Auth::user()->name }}
                                    </div>
                                    <div class="text-xs text-center bg-success-light rounded text-success p-1">
                                        {{ __(Auth::user()->user_type) }}
                                    </div>
                                </div>
                            </div>
                        </li>

                        <!-- Profile Switcher -->
                        <li class="border-b border-gray-100 dark:border-gray-600">
                            <div class="px-4 py-2">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">تغيير الصلاحية (Profile)</span>
                                <div class="mt-2 space-y-1">
                                    <a href="{{ route('profile.reset') }}" 
                                       class="flex items-center justify-between px-2 py-1.5 rounded text-sm {{ !session('active_profile_id') ? 'bg-primary/10 text-primary' : 'hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                        <span>جميع الصلاحيات</span>
                                        @if(!session('active_profile_id'))
                                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        @endif
                                    </a>
                                    
                                    @foreach(Auth::user()->profiles as $profile)
                                        <a href="{{ route('profile.switch', $profile->id) }}" 
                                           class="flex items-center justify-between px-2 py-1.5 rounded text-sm {{ session('active_profile_id') == $profile->id ? 'bg-primary/10 text-primary' : 'hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                            <span>{{ $profile->title }}</span>
                                            @if(session('active_profile_id') == $profile->id)
                                                <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </li>

                        <li>
                            <a href="{{ route('profile.edit') }}" class="dark:hover:text-white" @click="toggle">
                                <svg class="w-4.5 h-4.5 ltr:mr-2 rtl:ml-2 shrink-0" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="6" r="4" stroke="currentColor" stroke-width="1.5" />
                                    <path opacity="0.5"
                                        d="M20 17.5C20 19.9853 20 22 12 22C4 22 4 19.9853 4 17.5C4 15.0147 7.58172 13 12 13C16.4183 13 20 15.0147 20 17.5Z"
                                        stroke="currentColor" stroke-width="1.5" />
                                </svg>
                                الملف الشخصي
                            </a>
                        </li>

                        <li class="border-t border-white-light dark:border-white-light/10">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();this.closest('form').submit();"
                                    class="text-red-400 text-xs flex gap-1 dark:hover:text-white p-4">
                                    <svg class="w-4.5 h-4.5 ltr:mr-2 rtl:ml-2 shrink-0 rotate-90" width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5"
                                            d="M17 9.00195C19.175 9.01406 20.3529 9.11051 21.1213 9.8789C22 10.7576 22 12.1718 22 15.0002V16.0002C22 18.8286 22 20.2429 21.1213 21.1215C20.2426 22.0002 18.8284 22.0002 16 22.0002H8C5.17157 22.0002 3.75736 22.0002 2.87868 21.1215C2 20.2429 2 18.8286 2 16.0002L2 15.0002C2 12.1718 2 10.7576 2.87868 9.87889C3.64706 9.11051 4.82497 9.01406 7 9.00195"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                        <path d="M12 15L12 2M12 2L15 5.5M12 2L9 5.5" stroke="currentColor"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>

                                    تسجيل الخروج
                                </a>
                            </form>
                        </li>
                        @php
                            $user = Auth::user();
                        @endphp



                    </ul>
                </div>
            </div>
        </div>

    </div>
</header>
<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("header", () => ({
            init() {
                const selector = document.querySelector('ul.horizontal-menu a[href="' + window
                    .location.pathname + '"]');
                if (selector) {
                    selector.classList.add('active');
                    const ul = selector.closest('ul.sub-menu');
                    if (ul) {
                        let ele = ul.closest('li.menu').querySelectorAll('.nav-link');
                        if (ele) {
                            ele = ele[0];
                            setTimeout(() => {
                                ele.classList.add('active');
                            });
                        }
                    }
                }
            },

        }));
    });
</script>