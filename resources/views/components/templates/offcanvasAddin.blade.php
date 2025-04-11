<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddAddin" aria-labelledby="offcanvasAddAddinLabel">
    <div class="offcanvas-header">
        <h5 id="offcanvasAddAddinLabel" class="offcanvas-title">Add Addin</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close" id="addCloseTrigger"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0">
        <form id="addNewAddinForm" onsubmit="return false">
            @csrf

            <div class="mb-3">
                <label for="add_addin_type" class="form-label">Tipe Addin</label>
                <input type="text" name="title" class="form-control" value="Sub Category" required />
            </div>
            <div class="mb-3 group-all">
                <label for="title" class="form-label">Judul</label>
                <input type="text" name="title" class="form-control" required />
            </div>

            <div class="mb-3 group-icon">
                <label for="add_icon" class="form-label">Ikon</label>
                <select class="select2-icons form-select" name="icon" id="add_icon">
                    @foreach ($icons as $icon)
                        <option value="{{ $icon->class }}" data-icon="{{ $icon->class }}">
                            {{ $icon->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3 group-category">
                <label for="add_section" class="form-label">Section</label>
                <select name="section_id" class="form-select select2">
                    <option value="">-- Pilih Section --</option>
                    @foreach ($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3 group-subCategory">
                <label for="add_category" class="form-label">Kategori</label>
                <select name="category_id" class="form-select select2">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3 group-all">
                <label for="sort_number" class="form-label">Sort Order</label>
                <input type="number" min=0 name="sort_number" id="sort_number" class="form-control" />
            </div>

            <div class="mb-3 group-all">
                <label for="is_active" class="form-label">Status</label>
                <select name="is_active" id="is_active" class="form-select">
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditAddin" aria-labelledby="offcanvasEditAddinLabel">
    <div class="offcanvas-header">
        <h5 id="offcanvasEditAddinLabel" class="offcanvas-title">Edit Menu</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close" id="editCloseTrigger"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0">
        <form id="editAddinForm" onsubmit="return false">
            @csrf
            @method('PUT')
            <input type="hidden" name="addin_id" id="addin_id" />

            <div class="mb-3">
                <label for="add_addin_type" class="form-label">Tipe Addin</label>
                <input type="text" name="title" class="form-control" value="Sub Category" required />
            </div>
            <div class="mb-3 group-all">
                <label for="title" class="form-label">Judul</label>
                <input type="text" name="title" id="edit_title" class="form-control" required />
            </div>

            <div class="mb-3 group-icon">
                <label for="add_icon" class="form-label">Ikon</label>
                <select class="select2-icons form-select" name="icon" id="edit_icon">
                    @foreach ($icons as $icon)
                        <option value="{{ $icon->class }}" data-icon="{{ $icon->class }}">
                            {{ $icon->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3 group-category">
                <label for="add_section" class="form-label">Section</label>
                <select name="section_id" class="form-select select2">
                    <option value="">-- Pilih Section --</option>
                    @foreach ($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3 group-subCategory">
                <label for="add_category" class="form-label">Kategori</label>
                <select name="category_id" class="form-select select2">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3 group-all">
                <label for="sort_number" class="form-label">Sort Order</label>
                <input type="number" min=0 name="sort_number" id="edit_sort_number" class="form-control" />
            </div>

            <div class="mb-3 group-all">
                <label for="is_active" class="form-label">Status</label>
                <select name="is_active" id="edit_is_active" class="form-select">
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Perbarui</button>
        </form>
    </div>
</div>