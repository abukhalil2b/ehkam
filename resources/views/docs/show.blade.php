<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header with Navigation -->
            <div class="mb-6">
                <a href="{{ route('docs.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-4">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    العودة إلى الفهرس
                </a>
                <h1 class="text-3xl font-bold text-gray-900">{{ $title }}</h1>
            </div>

            <div class="lg:grid lg:grid-cols-12 lg:gap-8">
                <!-- Sidebar with TOC -->
                <aside class="hidden lg:block lg:col-span-3">
                    <div class="sticky top-8">
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide mb-4">المحتويات</h3>
                            @if(count($toc) > 0)
                                <nav class="space-y-2">
                                    @foreach($toc as $item)
                                        <a href="#{{ $item['id'] }}"
                                            class="block text-sm hover:text-blue-600 transition-colors {{ $item['level'] === 1 ? 'font-medium text-gray-900' : ($item['level'] === 2 ? 'pr-4 text-gray-700' : 'pr-8 text-gray-600') }}">
                                            {{ $item['text'] }}
                                        </a>
                                    @endforeach
                                </nav>
                            @else
                                <p class="text-sm text-gray-500">لا توجد عناوين</p>
                            @endif

                            <!-- Other Docs -->
                            @if(count($allDocs) > 1)
                                <hr class="my-6">
                                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide mb-4">مستندات أخرى</h3>
                                <nav class="space-y-2">
                                    @foreach($allDocs as $doc)
                                        @if($doc['slug'] !== $slug)
                                            <a href="{{ route('docs.show', $doc['slug']) }}"
                                                class="block text-sm text-gray-700 hover:text-blue-600 transition-colors">
                                                {{ $doc['title'] }}
                                            </a>
                                        @endif
                                    @endforeach
                                </nav>
                            @endif
                        </div>
                    </div>
                </aside>

                <!-- Main Content -->
                <main class="lg:col-span-9">
                    <div class="bg-white rounded-lg shadow-sm">
                        <article class="prose prose-blue max-w-none p-8 documentation-content">
                            {!! $html !!}
                        </article>
                    </div>

                    <!-- Mobile TOC -->
                    <div class="lg:hidden mt-6 bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide mb-4">المحتويات</h3>
                        @if(count($toc) > 0)
                            <nav class="space-y-2">
                                @foreach($toc as $item)
                                    <a href="#{{ $item['id'] }}"
                                        class="block text-sm hover:text-blue-600 {{ $item['level'] === 1 ? 'font-medium' : 'pr-4' }}">
                                        {{ $item['text'] }}
                                    </a>
                                @endforeach
                            </nav>
                        @endif
                    </div>
                </main>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .documentation-content {
                direction: ltr;
                text-align: left;
            }

            .documentation-content h1,
            .documentation-content h2,
            .documentation-content h3,
            .documentation-content h4 {
                scroll-margin-top: 6rem;
            }

            .documentation-content h2 {
                border-bottom: 1px solid #e5e7eb;
                padding-bottom: 0.5rem;
                margin-top: 2rem;
            }

            .documentation-content pre {
                background-color: #1e293b;
                border-radius: 0.5rem;
                padding: 1rem;
                overflow-x: auto;
            }

            .documentation-content code {
                background-color: #f1f5f9;
                padding: 0.125rem 0.375rem;
                border-radius: 0.25rem;
                font-size: 0.875em;
            }

            .documentation-content pre code {
                background-color: transparent;
                padding: 0;
                color: #e2e8f0;
            }

            .documentation-content table {
                width: 100%;
                border-collapse: collapse;
                margin: 1.5rem 0;
            }

            .documentation-content table th,
            .documentation-content table td {
                border: 1px solid #e5e7eb;
                padding: 0.75rem;
                text-align: right;
            }

            .documentation-content table th {
                background-color: #f9fafb;
                font-weight: 600;
            }

            .documentation-content blockquote {
                border-right: 4px solid #3b82f6;
                padding-right: 1rem;
                font-style: italic;
                color: #64748b;
            }

            .documentation-content a {
                color: #3b82f6;
                text-decoration: underline;
            }

            .documentation-content a:hover {
                color: #2563eb;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">

        <script>
            // Initialize Mermaid
            mermaid.initialize({
                startOnLoad: true,
                theme: 'default',
                securityLevel: 'loose'
            });

            // Initialize syntax highlighting
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('pre code').forEach((block) => {
                    hljs.highlightElement(block);
                });

                // Add copy button to code blocks
                document.querySelectorAll('pre').forEach((pre) => {
                    const button = document.createElement('button');
                    button.className = 'absolute top-2 left-2 bg-gray-700 hover:bg-gray-600 text-white px-3 py-1 rounded text-xs';
                    button.textContent = 'Copy';

                    button.addEventListener('click', async () => {
                        const code = pre.querySelector('code').textContent;
                        await navigator.clipboard.writeText(code);
                        button.textContent = 'Copied!';
                        setTimeout(() => button.textContent = 'Copy', 2000);
                    });

                    pre.style.position = 'relative';
                    pre.appendChild(button);
                });

                // Auto-generate heading IDs for TOC links
                document.querySelectorAll('.documentation-content h2, .documentation-content h3, .documentation-content h4').forEach((heading) => {
                    const text = heading.textContent;
                    const id = text.toLowerCase().replace(/[^\w\s-]/g, '').replace(/\s+/g, '-');
                    heading.id = id;
                });
            });
        </script>
    @endpush
</x-app-layout>