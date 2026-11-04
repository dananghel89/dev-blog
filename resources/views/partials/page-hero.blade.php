{{-- Inner-page hero (Blogy "hero overlay inner-page"). Params: $title, optional $subtitle. --}}
<div class="hero overlay inner-page bg-primary py-5">
    <div class="container">
        <div class="row align-items-center justify-content-center text-center pt-5">
            <div class="col-lg-8">
                <h1 class="heading text-white mb-3" data-aos="fade-up">{{ $title }}</h1>
                @isset($subtitle)
                    <p class="text-white-50 mb-0" data-aos="fade-up" data-aos-delay="100">{{ $subtitle }}</p>
                @endisset
            </div>
        </div>
    </div>
</div>
