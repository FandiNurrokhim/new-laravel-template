<div class="container my-4">
    <div class="d-flex overflow-auto gap-3">
        @foreach ($images as $image)
            <div class="card" style="min-width: 200px;">
                <img src="{{ asset($image['src']) }}" class="card-img-top" alt="...">
                <div class="card-body p-2">
                    <p class="card-text small">{{ $image['title'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
