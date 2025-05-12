@extends('layouts.home-layout')

@section('content')
    <!-- Hero Section -->
    <!-- Hero 6 - Bootstrap Brain Component -->
    <section class=" bg-white">
        <x-landing-page.hero title="Welcome to Mürren" :images="[
            ['src' => 'img/backgrounds/1.jpg'],
            ['src' => 'img/backgrounds/2.jpg'],
            ['src' => 'img/backgrounds/3.jpg'],
        ]" buttonText="Learn More" buttonLink="/learn-more" />
    </section>

    <x-landing-page.statistic-card />
    <x-landing-page.services />
    <x-landing-page.faq />
    
    <div class="p-5 text-center bg-image"
    style="
            background-image: url('https://mdbcdn.b-cdn.net/img/new/slides/041.webp');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 400px;
            margin-top: 58px;
            position: relative;
            ">
        <div class="mask"
        style="
                background-color: rgba(0, 0, 0, 0.6);
                position: absolute;
                top: 0; left: 0; right: 0; bottom: 0;
                width: 100%; height: 100%;
                display: flex;
                justify-content: center;
                align-items: center;
                ">
            <div class="text-white">
                <h1 class="mb-3 text-white">Heading</h1>
                <h4 class="mb-3 text-white">Subheading</h4>
                <a class="btn btn-outline-light text-white btn-lg" href="#" role="button">Call to action</a>
            </div>
        </div>
    </div>
    
    <x-landing-page.information />
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        const bounds = [
            [-7.637818917807315, 111.35510355386626], // latBottom, lngLeft
            [-7.637016800266681, 111.35617863694723] // latTop, lngRight
        ];
        // Inisialisasi peta
        const map = L.map('map', {
            center: [(-7.637016800266681 + -7.637818917807315) / 2, (111.35510355386626 + 111.35617863694723) / 2],
            zoom: 18,
            minZoom: 18,
            maxZoom: 18,
            maxBounds: bounds,
            dragging: false,
            scrollWheelZoom: false,
            doubleClickZoom: false,
            boxZoom: false,
            keyboard: false,
            touchZoom: false,
            zoomControl: false,
            attributionControl: false
        });

        const graves = @json($graves);

        // Menambahkan layer peta
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        // GeoJSON data
        const geojsonData = {
            "type": "FeatureCollection",
            "features": [{
                    "type": "Feature",
                    "properties": {},
                    "geometry": {
                        "coordinates": [
                            111.35547343330103,
                            -7.63740765349759
                        ],
                        "type": "Point"
                    }
                },
                {
                    "type": "Feature",
                    "properties": {},
                    "geometry": {
                        "coordinates": [
                            [
                                111.35516925330285,
                                -7.637021524847
                            ],
                            [
                                111.35521722013391,
                                -7.637730006474683
                            ],
                            [
                                111.35579165218968,
                                -7.637669710211242
                            ],
                            [
                                111.35596129098445,
                                -7.637481864106661
                            ],
                            [
                                111.35594608198824,
                                -7.6371745848068
                            ],
                            [
                                111.35516808338082,
                                -7.637018046210187
                            ]
                        ],
                        "type": "LineString"
                    }
                }
            ]
        };

        // Menambahkan GeoJSON ke peta
        L.geoJSON(geojsonData, {
            pointToLayer: function(feature, latlng) {
                return L.circleMarker(latlng, {
                    radius: 8,
                    fillColor: "blue",
                    color: "blue",
                    weight: 1,
                    opacity: 1,
                    fillOpacity: 0.5
                });
            },
            onEachFeature: function(feature, layer) {
                layer.bindPopup("Titik atau Garis");
            }
        }).addTo(map);

        L.rectangle(bounds, {
            color: "#0000FF",
            weight: 1,
            fillOpacity: 0.5
        }).addTo(map);

        if (typeof graves !== 'undefined') {
            graves.forEach(function(grave) {
                if (grave.lat && grave.lng) {
                    L.marker([grave.lat, grave.lng])
                        .addTo(map)
                        .bindPopup(`<b>${grave.name}</b><br>Status: ${grave.status}`);
                }
            });
        }
    </script>
@endpush
