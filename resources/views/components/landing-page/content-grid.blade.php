<div class="container my-4">
    <div class="row row-cols-1 row-cols-md-3 g-4">
      @foreach($items as $item)
        <div class="col">
          <div class="card h-100">
            <img src="{{ asset($item['src']) }}" class="card-img-top" alt="{{ $item['title'] }}">
            <div class="card-body">
              <h5 class="card-title">{{ $item['title'] }}</h5>
              <p class="card-text"><small>{{ $item['date'] ?? '' }}</small></p>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
  