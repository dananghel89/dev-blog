{{--
    The networks configured in config('project.social'). Unset networks render nothing, and when none
    is set the whole block (wrapper and heading included) is left out.
    Optional: $wrapper (class of the wrapping div), $heading (h3 text), $class (class of the <ul>).
--}}
@php
    $networks = [
        'github' => ['GitHub', 'bi-github'],
        'linkedin' => ['LinkedIn', 'bi-linkedin'],
        'x' => ['X (Twitter)', 'bi-twitter-x'],
        'stackoverflow' => ['Stack Overflow', 'bi-stack-overflow'],
    ];
    $socialLinks = collect(config('project.social', []))
        ->filter(fn ($url, $network) => filled($url) && isset($networks[$network]));
@endphp
@if ($socialLinks->isNotEmpty())
    <div class="{{ $wrapper ?? '' }}">
        @isset($heading)
            <h3>{{ $heading }}</h3>
        @endisset
        <ul class="{{ $class ?? 'list-unstyled social' }}">
            @foreach ($socialLinks as $network => $url)
                <li>
                    <a href="{{ $url }}" target="_blank" rel="me noopener" aria-label="{{ $networks[$network][0] }}">
                        <span class="bi {{ $networks[$network][1] }}" aria-hidden="true"></span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif
