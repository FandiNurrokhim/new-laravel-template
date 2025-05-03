@extends('layouts.home-layout')

@section('content')
    {{-- Hero Section --}}
    <x-landing-page.grave-locations :graveLocations="$graveLocations" />
@endsection