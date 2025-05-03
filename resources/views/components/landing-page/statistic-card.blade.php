@props(['icon', 'count', 'label'])

<div class="col-md-3 mb-4">
    <div class="card shadow-sm text-center h-100 border-0">
        <div class="card-body">
            <div class="mb-2">
                <i class='bx {{ $icon }} fs-1 text-primary'></i>
            </div>
            <h5 class="card-title fw-bold">
                <span class="counter" data-target="{{ $count }}">0</span>
            </h5>
            <p class="card-text text-muted">{{ $label }}</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const counters = document.querySelectorAll(".counter");

        counters.forEach(counter => {
            const updateCount = () => {
                const target = +counter.getAttribute("data-target");
                const count = +counter.innerText;

                const increment = target / 100;

                if (count < target) {
                    counter.innerText = Math.ceil(count + increment);
                    setTimeout(updateCount, 15);
                } else {
                    counter.innerText = target;
                }
            };

            updateCount();
        });
    });
</script>
