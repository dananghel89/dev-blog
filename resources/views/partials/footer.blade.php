{{-- Footer based on the Blogy template by Untree.co (CC BY 3.0), modified. --}}
<footer class="site-footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="widget">
                    <h3 class="mb-4">About</h3>
                    <p>A personal blog documenting daily development challenges and solutions in PHP, MySQL, HTML, CSS, and JavaScript. Real problems, real solutions.</p>
                </div>
                <div class="widget">
                    <h3>Social</h3>
                    <ul class="list-unstyled social">
                        <li><a href="#" aria-label="GitHub"><span class="bi bi-github"></span></a></li>
                        <li><a href="#" aria-label="X (Twitter)"><span class="bi bi-twitter-x"></span></a></li>
                        <li><a href="#" aria-label="LinkedIn"><span class="bi bi-linkedin"></span></a></li>
                        <li><a href="#" aria-label="Stack Overflow"><span class="bi bi-stack-overflow"></span></a></li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 ps-lg-5">
                <div class="widget">
                    <h3 class="mb-4">Links</h3>
                    <ul class="list-unstyled float-start links">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('blog.index') }}">Blog</a></li>
                        <li><a href="{{ route('about') }}">About</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="widget">
                    <h3 class="mb-4">Recent Posts</h3>
                    <div class="post-entry-footer">
                        <ul>
                            @forelse ($footerPosts as $footerPost)
                                <li>
                                    <a href="{{ $footerPost->url }}">
                                        <img src="{{ $footerPost->imageUrl('square') }}" alt="" class="me-4 rounded">
                                        <div class="text">
                                            <h4>{{ $footerPost->title }}</h4>
                                            <div class="post-meta">
                                                <span class="mr-2">{{ $footerPost->published_at?->format('F j, Y') }}</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @empty
                                <li>No posts yet.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12 text-center">
                {{--
                    Required by the Blogy license (https://untree.co/license/):
                    do not remove the Untree.co credit link unless a license to remove it is bought.
                --}}
                <p>
                    Copyright &copy; {{ date('Y') }} {{ config('app.name', 'Dev Blog') }}. All Rights Reserved.
                    &mdash; Designed with love by <a href="https://untree.co">Untree.co</a>
                    Distributed by <a href="https://themewagon.com">ThemeWagon</a>
                </p>
            </div>
        </div>
    </div>
</footer>
