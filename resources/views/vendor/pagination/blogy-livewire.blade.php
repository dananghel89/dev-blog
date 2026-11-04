{{--
    Blogy "custom-pagination" markup for Livewire components (WithPagination + paginationView()).
    Links keep a real href (?page=N), so they work without JavaScript and stay crawlable;
    with Livewire running, the click is handled by gotoPage() instead and the list scrolls into view.
--}}
@php
    $scrollTo ??= '.search-result-wrap';
    $scrollIntoView = $scrollTo !== false
        ? "(\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}'))?.scrollIntoView({ behavior: 'smooth' })"
        : '';
@endphp

@if ($paginator->hasPages())
    <nav class="custom-pagination" aria-label="Pagination">
        @foreach ($elements as $element)
            @if (is_string($element))
                <span aria-disabled="true">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" wire:key="paginator-{{ $paginator->getPageName() }}-page-{{ $page }}">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                           wire:key="paginator-{{ $paginator->getPageName() }}-page-{{ $page }}"
                           wire:click.prevent="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                           x-on:click="{{ $scrollIntoView }}"
                           aria-label="Page {{ $page }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach
    </nav>
@endif
