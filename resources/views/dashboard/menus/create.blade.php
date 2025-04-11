@extends('layouts.app')

@section('title', 'Tambah Menu')

@section('content')
    <div class="container">
        <h3>Tambah Menu</h3>
        <form method="POST" action="{{ route('menus.store') }}">
            @csrf
            <div class="mb-3">
                <label for="type" class="form-label">Tipe Menu</label>
                <select name="type" class="form-control" required>
                    <option value="HEADER">HEADER</option>
                    <option value="PARENT">PARENT</option>
                    <option value="SUB PARENT">SUB PARENT</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Judul Menu</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="icon" class="form-label">Ikon</label>
                <select class="form-control" name="icon" id="icon-select" onchange="updateIconPreview()">
                    @foreach ($icons as $icon)
                        <option value="{{ $icon->class }}" data-icon="{{ $icon->class }}">{{ $icon->name }}</option>
                    @endforeach
                </select>
                <div class="mt-2">
                    <i id="icon-preview" class="bx bx-home" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="mb-3">
                <label for="route" class="form-label">Route</label>
                <input type="text" name="route" class="form-control">
            </div>
            <div class="mb-3">
                <label for="header" class="form-label">Header (Untuk tipe PARENT)</label>
                <select name="header" class="form-control">
                    <option value="">-- Pilih Header --</option>
                    @foreach ($headers as $header)
                        <option value="{{ $header->title }}">{{ $header->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="parent_id" class="form-label">Parent (Untuk tipe SUB PARENT)</label>
                <select name="parent_id" class="form-control">
                    <option value="">-- Tidak Ada --</option>
                    @foreach ($parents as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="permission_title" class="form-label">Permission Title</label>
                <input type="text" name="permission_title" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
    <script>
        function updateIconPreview() {
            var select = document.getElementById("icon-select");
            var iconPreview = document.getElementById("icon-preview");
            var selectedOption = select.options[select.selectedIndex];
            iconPreview.className = selectedOption.getAttribute("data-icon");
        }
    </script>
@endsection
