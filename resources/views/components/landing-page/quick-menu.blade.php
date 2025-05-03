<div class="container my-4">
    <div class="d-flex justify-content-between flex-wrap">
      @foreach(['Hiking', 'Activities', 'Booking', 'Events'] as $item)
        <a href="#" class="text-decoration-none text-dark me-3">{{ $item }}</a>
      @endforeach
    </div>
  </div>
  