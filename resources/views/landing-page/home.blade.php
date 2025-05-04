@extends('layouts.home-layout')

@section('content')
    {{-- Hero Section --}}
    <x-landing-page.hero title="Welcome to Mürren" :images="[
        ['src' => 'img/backgrounds/1.jpg'],
        ['src' => 'img/backgrounds/2.jpg'],
        ['src' => 'img/backgrounds/3.jpg'],
    ]" buttonText="Learn More" buttonLink="/learn-more" />

    <div class="row container mx-auto">
    </div>
    {{-- Excursions Section --}}
    <div class="container py-5">
        <div class="row" data-aos="fade-up" data-aos-duration="1000">
            <x-landing-page.statistic-card icon="bi-people-fill" :count="$corpseCount" label="Jenazah Terdata" />
            <x-landing-page.statistic-card icon="bi-geo-alt-fill" :count="$graveUsedCount" label="Makam Terdaftar" />
            <x-landing-page.statistic-card icon="bi-journal-check" :count="$formRequestCount" label="Form Pengajuan" />
            <x-landing-page.statistic-card icon="bi-calendar-check-fill" :count="$formRequestThisMonthCount" label="Pengajuan Bulan Ini" />
        </div>
        <div class="row g-4 mt-5" data-aos="fade-up" data-aos-duration="1000">
            <x-landing-page.card-menu />
        </div>
    </div>
@endsection
