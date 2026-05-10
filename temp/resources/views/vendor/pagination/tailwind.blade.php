@if ($paginator->hasPages())
<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between mt-6">
    {{-- Mobile simple prev/next --}}
    <div class="flex justify-between flex-1 sm:hidden gap-2">
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-surface-400 bg-surface-100 dark:bg-surface-800/60 border border-surface-200/60 dark:border-surface-700/60 rounded-xl cursor-not-allowed">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                {{ __('Previous') }}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-surface-700 dark:text-surface-300 bg-surface-100 dark:bg-surface-800/60 border border-surface-200/60 dark:border-surface-700/60 rounded-xl hover:bg-surface-200 dark:hover:bg-surface-700 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                {{ __('Previous') }}
            </a>
        @endif
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-surface-700 dark:text-surface-300 bg-surface-100 dark:bg-surface-800/60 border border-surface-200/60 dark:border-surface-700/60 rounded-xl hover:bg-surface-200 dark:hover:bg-surface-700 transition-all">
                {{ __('Next') }}
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            </a>
        @else
            <span class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-surface-400 bg-surface-100 dark:bg-surface-800/60 border border-surface-200/60 dark:border-surface-700/60 rounded-xl cursor-not-allowed">
                {{ __('Next') }}
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            </span>
        @endif
    </div>

    {{-- Desktop full pagination --}}
    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
        <div>
            <p class="text-xs text-surface-500 dark:text-surface-400">
                {!! __('Showing') !!}
                <span class="font-semibold text-surface-700 dark:text-surface-300">{{ $paginator->firstItem() }}</span>
                {!! __('to') !!}
                <span class="font-semibold text-surface-700 dark:text-surface-300">{{ $paginator->lastItem() }}</span>
                {!! __('of') !!}
                <span class="font-semibold text-surface-700 dark:text-surface-300">{{ $paginator->total() }}</span>
                {!! __('results') !!}
            </p>
        </div>

        <div>
            <span class="relative z-0 inline-flex gap-1 rounded-xl shadow-sm">
                {{-- Previous --}}
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-surface-300 dark:text-surface-600 bg-surface-100/50 dark:bg-surface-800/30 border border-surface-200/40 dark:border-surface-700/40 rounded-xl cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-surface-600 dark:text-surface-400 bg-surface-100/60 dark:bg-surface-800/50 border border-surface-200/60 dark:border-surface-700/60 rounded-xl hover:bg-brand-500/10 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-500/30 transition-all duration-150">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                    </a>
                @endif

                {{-- Page Numbers --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-surface-400 dark:text-surface-600 bg-surface-100/40 dark:bg-surface-800/30 border border-surface-200/40 dark:border-surface-700/40 rounded-xl cursor-default">
                            {{ $element }}
                        </span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="inline-flex items-center justify-center w-9 h-9 text-sm font-bold text-white bg-gradient-to-br from-brand-500 to-brand-600 border border-brand-600/50 rounded-xl shadow-sm shadow-brand-500/20">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-surface-600 dark:text-surface-400 bg-surface-100/60 dark:bg-surface-800/50 border border-surface-200/60 dark:border-surface-700/60 rounded-xl hover:bg-brand-500/10 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-500/30 transition-all duration-150">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-surface-600 dark:text-surface-400 bg-surface-100/60 dark:bg-surface-800/50 border border-surface-200/60 dark:border-surface-700/60 rounded-xl hover:bg-brand-500/10 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-500/30 transition-all duration-150">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                    </a>
                @else
                    <span class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-surface-300 dark:text-surface-600 bg-surface-100/50 dark:bg-surface-800/30 border border-surface-200/40 dark:border-surface-700/40 rounded-xl cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                    </span>
                @endif
            </span>
        </div>
    </div>
</nav>
@endif
