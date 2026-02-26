<footer class="footer bg-black text-light py-5 mt-auto border-top border-secondary">
    <div class="container">
        <!-- Main Footer Content -->
        <div class="row g-4">
            <!-- About Section -->
            <div class="col-lg-4 col-md-6">
                <h5 class="fw-bold mb-3">
                    Dev Blog
                </h5>
                <p class="text-secondary">
                    A personal blog documenting daily development challenges and solutions 
                    in PHP, MySQL, HTML, CSS, and JavaScript. Real problems, real solutions.
                </p>
                <div class="mt-3">
                    <p class="mb-1"><i class="bi bi-envelope-fill text-primary me-2"></i> hello@example.com</p>
                    <p><i class="bi bi-github text-primary me-2"></i> github.com/example</p>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h5 class="fw-bold mb-3">Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('home') }}" class="text-secondary text-decoration-none hover-text-primary">
                            <i class="bi bi-chevron-right small me-1"></i> Home
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('about') }}" class="text-secondary text-decoration-none hover-text-primary">
                            <i class="bi bi-chevron-right small me-1"></i> About
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('blog.index') }}" class="text-secondary text-decoration-none hover-text-primary">
                            <i class="bi bi-chevron-right small me-1"></i> Blog
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('contact') }}" class="text-secondary text-decoration-none hover-text-primary">
                            <i class="bi bi-chevron-right small me-1"></i> Contact
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('privacy') }}" class="text-secondary text-decoration-none hover-text-primary">
                            <i class="bi bi-chevron-right small me-1"></i> Privacy
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Categories -->
            <div class="col-lg-3 col-md-6">
                <h5 class="fw-bold mb-3">Popular Categories</h5>
                <div class="d-flex flex-wrap gap-2">
                    @php
                        $categories = \App\Models\Category::latest()->take(5)->get();
                    @endphp
                    @foreach($categories as $category)
                        <a href="{{ route('blog.category', $category->slug) }}" 
                           class="badge px-3 py-2 text-decoration-none" 
                           style="background-color: {{ $category->color }}; color: {{ $category->name === 'JavaScript' ? '#000' : '#fff' }};">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>
            
            <!-- Social Links -->
            <div class="col-lg-3 col-md-6">
                <h5 class="fw-bold mb-3">Connect</h5>
                <div class="d-flex gap-3">
                    <a href="#" class="text-secondary fs-4 hover-text-primary">
                        <i class="bi bi-github"></i>
                    </a>
                    <a href="#" class="text-secondary fs-4 hover-text-primary">
                        <i class="bi bi-twitter-x"></i>
                    </a>
                    <a href="#" class="text-secondary fs-4 hover-text-primary">
                        <i class="bi bi-linkedin"></i>
                    </a>
                    <a href="#" class="text-secondary fs-4 hover-text-primary">
                        <i class="bi bi-stack-overflow"></i>
                    </a>
                    <a href="#" class="text-secondary fs-4 hover-text-primary">
                        <i class="bi bi-rss-fill"></i>
                    </a>
                </div>
                
                <p class="text-secondary small mt-4">
                    <i class="bi bi-c-circle me-1"></i>
                    {{ date('Y') }} Dev Blog. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</footer>

<style>
    .hover-text-primary:hover {
        color: #6366f1 !important;
        transition: color 0.3s ease;
    }
    
    /* Light mode styles */
    body.light-mode .bg-black {
        background-color: #f8f9fa !important;
    }
    
    body.light-mode .text-secondary {
        color: #6c757d !important;
    }
    
    body.light-mode .border-secondary {
        border-color: #dee2e6 !important;
    }
</style>