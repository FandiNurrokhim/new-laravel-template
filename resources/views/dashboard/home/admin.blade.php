@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                    <div class="col-sm-12">
                        <div class="card-body">
                            <h2 class="card-title fw-bold text-primary">
                                Hello, {{ Auth::user()->name ?? '' }}! 🎉
                            </h2>
                            <p class="m-0">
                                You are logged in as an <b>{{ Auth::user()->roles[0]->name ?? '' }}</b>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
