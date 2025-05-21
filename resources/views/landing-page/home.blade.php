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

    <x-landing-page.statistic-card :corpse-count="$corpseCount" :grave-used-count="$graveUsedCount" :form-request-count="$formRequestCount" :form-request-this-month-count="$formRequestThisMonthCount" />
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
                <h1 class="mb-3 text-white">Sistem Informasi Pengelolaan Makam</h1>
            </div>
        </div>
    </div>

    <x-landing-page.information />
@endsection
