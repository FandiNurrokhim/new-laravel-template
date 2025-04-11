@extends('layouts.app')

@section('title', 'Edit Menu')

@section('content')
    <div class="container">
        <h3>Edit Menu</h3>
        <form method="POST" action="{{ route('menus.update', $menu->id) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label for="type" class="form-label">Tipe Menu</label>
                <select name="type" class="form-control" required>
                    <option value="HEADER" {{ $menu->type == 'HEADER' ? 'selected' : '' }}>HEADER</option>
                    <option value="PARENT" {{ $menu->type == 'PARENT' ? 'selected' : '' }}>PARENT</option>
                    <option value="SUB PARENT" {{ $menu->type == 'SUB PARENT' ? 'selected' : '' }}>SUB PARENT</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Judul Menu</label>
                <input type="text" name="title" class="form-control" value="{{ $menu->title }}" required>
            </div>
            <div class="mb-3">
                <label for="icon" class="form-label">Ikon</label>
                <select class="form-control" name="icon" id="icon-select" onchange="updateIconPreview()">
                    @foreach ($icons as $icon)
                        <option value="{{ $icon->class }}" data-icon="{{ $icon->class }}"
                            {{ $menu->icon == $icon->class ? 'selected' : '' }}>
                            {{ $icon->name }}
                        </option>
                    @endforeach
                </select>
                <div class="mt-2">
                    <i id="icon-preview" class="{{ $menu->icon }}" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="mb-3">
                <label for="route" class="form-label">Route</label>
                <input type="text" name="route" class="form-control" value="{{ $menu->route }}">
            </div>
            <div class="mb-3">
                <label for="header" class="form-label">Header (Untuk tipe PARENT)</label>
                <select name="header" class="form-control">
                    <option value="">-- Pilih Header --</option>
                    @foreach ($headers as $header)
                        <option value="{{ $header->title }}" {{ $menu->header == $header->title ? 'selected' : '' }}>
                            {{ $header->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="parent_id" class="form-label">Parent (Untuk tipe SUB PARENT)</label>
                <select name="parent_id" class="form-control">
                    <option value="">-- Tidak Ada --</option>
                    @foreach ($parents as $parent)
                        <option value="{{ $parent->id }}" {{ $menu->parent_id == $parent->id ? 'selected' : '' }}>
                            {{ $parent->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Perbarui</button>
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
