@if ($paginator->hasPages())
<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between mt-6">
    <div>
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-surface-300 dark:text-surface-600 bg-surface-100/50 dark:bg-surface-800/30 border border-surface-200/40 dark:border-surface-700/40 rounded-xl cursor-not-allowed">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                {{ __('Previous') }}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-surface-600 dark:text-surface-400 bg-surface-100/60 dark:bg-surface-800/50 border border-surface-200/60 dark:border-surface-700/60 rounded-xl hover:bg-brand-500/10 hover:text-brand-600 dark:hover:text-brand-400 transition-all duration-150">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                {{ __('Previous') }}
            </a>
        @endif
    </div>

    <div>
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-surface-600 dark:text-surface-400 bg-surface-100/60 dark:bg-surface-800/50 border border-surface-200/60 dark:border-surface-700/60 rounded-xl hover:bg-brand-500/10 hover:text-brand-600 dark:hover:text-brand-400 transition-all duration-150">
                {{ __('Next') }}
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            </a>
        @else
            <span class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-surface-300 dark:text-surface-600 bg-surface-100/50 dark:bg-surface-800/30 border border-surface-200/40 dark:border-surface-700/40 rounded-xl cursor-not-allowed">
                {{ __('Next') }}
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            </span>
        @endif
    </div>
</nav>
@endif
