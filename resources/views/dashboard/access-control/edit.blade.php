@extends('layouts.app')

@section('title', 'Assign Permissions')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 pb-0 mb-2">
            Assign Permissions to {{ $role->name }}
        </h4>
        <p class="mb-4">
            This page is used to assign permissions to the role. You can assign multiple permissions to the role.
        </p>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('access-control.index') }}" class="btn btn-md btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back
                    </a>
                </div>
                <div>
                    <button type="button" class="btn btn-md btn-secondary" id="btnSelectAll">Select All</button>
                    <button type="button" class="btn btn-md btn-warning" id="btnClearAll">Clear All</button>
                </div>
            </div>

            <hr class="mt-0">

            <div class="card-body">
                <form action="{{ route('access-control.assign', $role->id) }}" method="POST">
                    @csrf

                    <div class="row">
                        @foreach ($permissions as $permission)
                            <div class="col-md-3 mb-3">
                                {{-- Toggle Switch --}}
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" name="permissions[]"
                                        value="{{ $permission->id }}" id="perm-{{ $permission->id }}"
                                        {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="perm-{{ $permission->id }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-primary mt-3 w-100">
                        Assign Permissions
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnSelectAll = document.getElementById('btnSelectAll');
            const btnClearAll = document.getElementById('btnClearAll');
            const checkboxes = document.querySelectorAll('input[name="permissions[]"]');

            btnSelectAll.addEventListener('click', () => {
                checkboxes.forEach((cb) => {
                    cb.checked = true;
                });
            });

            btnClearAll.addEventListener('click', () => {
                checkboxes.forEach((cb) => {
                    cb.checked = false;
                });
            });
        });
    </script>
@endpush
