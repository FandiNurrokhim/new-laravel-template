@props(['title', 'images' => [], 'buttonText' => null, 'buttonLink' => '#'])

<section class="hero bg-dark text-white text-center position-relative">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach ($images as $index => $image)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <div class="d-flex paralax justify-content-center align-items-center h-100"
                        style="background-image: url('{{ asset($image['src']) }}'); background-size: cover; background-position: center; height: 75vh;">
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
