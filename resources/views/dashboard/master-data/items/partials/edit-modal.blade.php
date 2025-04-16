<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditItem" aria-labelledby="offcanvasEditItem"
    data-bs-scroll="true" style="max-height: 100vh;">
    <div class="offcanvas-header">
        <h5 id="offcanvasEditItem" class="offcanvas-title">Edit Items</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body" style="overflow-y: auto;">
        <form id="editItemForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="edit_id">
            <div class="mb-3">
                <label for="edit_title" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" id="edit_title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="products" class="form-label">Products <span class="text-danger">*</span></label>
                <select name="products[]" id="edit_products" class="select2 form-select" multiple disabled required>
                    <!-- Options will be populated dynamically -->
                </select>
            </div>

            <div class="mb-3">
                <label for="sections" class="form-label">Sections <span class="text-danger">*</span></label>
                <select name="sections[]" id="edit_sections" class="select2 form-select" multiple disabled required>
                    <!-- Options will be populated dynamically -->
                </select>
            </div>

            <div class="mb-3">
                <label for="categories" class="form-label">Categories <span class="text-danger">*</span></label>
                <select name="categories[]" id="edit_categories" class="select2 form-select" multiple disabled required>
                    <!-- Options will be populated dynamically -->
                </select>
            </div>

            <div class="mb-3">
                <label for="sub_categories" class="form-label">Sub Categories <span class="text-danger">*</span></label>
                <select name="sub_categories[]" id="edit_sub_categories" class="select2 form-select" multiple disabled
                    required>
                    <!-- Options will be populated dynamically -->
                </select>
            </div>

            <div class="mb-3">
                <label for="edit_file_format_id" class="form-label">File Format <span
                        class="text-danger">*</span></label>
                <select name="file_format_id" id="edit_file_format_id" class="select2 form-select" required>
                    @foreach ($fileFormats as $format)
                        <option value="{{ $format->id }}">{{ $format->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="edit_thumbnail" class="form-label">Thumbnail (URL LINK)</label>
                <textarea name="thumbnail" id="edit_thumbnail" class="form-control" rows="2"></textarea>
            </div>

            <div class="mb-3">
                <label for="edit_tag" class="form-label">Tags</label>
                <input name="tag" id="edit_tag" class="form-control" placeholder="Add tags...">
            </div>

            <div class="mb-3">
                <label for="edit_file_name" class="form-label">File Name <span class="text-danger">*</span></label>
                <input type="text" name="file_name" id="edit_file_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="edit_type" class="form-label">Type <span class="text-danger">*</span></label>
                <select name="type" id="edit_type" class="form-select" required>
                    <option value="FREE">Free</option>
                    <option value="PREMIUM">Premium</option>
                </select>
            </div>

            <div class="mb-3" id="edit_price_group">
                <label for="edit_price" class="form-label">Price</label>
                <input type="number" name="price" id="edit_price" class="form-control" min="0">
            </div>

            <div class="mb-3">
                <label for="edit_order_number" class="form-label">Order Number</label>
                <input type="number" name="order_number" id="edit_order_number" class="form-control"
                    min="0">
            </div>

            <div class="mb-3">
                <label for="edit-description-editor" class="form-label">Description</label>
                <div id="description_edit_quill" style="min-height: 150px;"></div>
                <input type="hidden" name="description" id="description_edit_hidden" />
            </div>

            <div class="mb-3">
                <label for="edit_status" class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" id="edit_status" class="select2 form-select" required>
                    <option value="DRAFT">Draft</option>
                    <option value="PENDING">Pending</option>
                    <option value="PUBLISHED">Published</option>
                    <option value="INACTIVE">Inactive</option>
                    <option value="ARCHIVED">Archived</option>
                    <option value="DELETED">Deleted</option>
                </select>


                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="offcanvas">Kembali</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
@push('scripts')
    <script>
        // Fungsi global
        function fetchProducts() {
            return $.ajax({
                url: '/dashboard/item/products',
                type: 'GET',
                success: function(response) {
                    const productsEditSelect = $('#edit_products');
                    productsEditSelect.empty();
                    response.forEach(product => {
                        productsEditSelect.append(new Option(product.title, product.id));
                    });
                    productsEditSelect.prop('disabled', false);
                }
            });
        }

        function fetchSections(productIds) {
            return $.ajax({
                url: '/dashboard/item/sections',
                type: 'GET',
                data: {
                    product_ids: productIds
                }
            }).then(response => {
                const $select = $('#edit_sections');
                const oldSelected = $select.val() || [];
                const validIds = response.map(section => section.id.toString());

                $select.empty();
                response.forEach(section => {
                    $select.append(new Option(section.title, section.id));
                });

                // Pilih ulang data yang masih valid
                const newSelected = oldSelected.filter(val => validIds.includes(val));
                $select.val(newSelected).trigger('change');
                $select.prop('disabled', false);

                return response;
            });
        }

        function fetchCategories(sectionIds) {
            return $.ajax({
                url: '/dashboard/item/categories',
                type: 'GET',
                data: {
                    section_ids: sectionIds
                }
            }).then(response => {
                const $select = $('#edit_categories');
                const oldSelected = $select.val() || [];
                const validIds = response.map(c => c.id.toString());

                $select.empty();
                response.forEach(c => {
                    $select.append(new Option(c.title, c.id));
                });

                const newSelected = oldSelected.filter(val => validIds.includes(val));
                $select.val(newSelected).trigger('change');
                $select.prop('disabled', false);

                return response;
            });
        }

        function fetchSubCategories(categoryIds) {
            return $.ajax({
                url: '/dashboard/item/sub-categories',
                type: 'GET',
                data: {
                    category_ids: categoryIds
                }
            }).then(response => {
                const $select = $('#edit_sub_categories');
                const oldSelected = $select.val() || [];
                const validIds = response.map(s => s.id.toString());

                $select.empty();
                response.forEach(s => {
                    $select.append(new Option(s.title, s.id));
                });

                const newSelected = oldSelected.filter(val => validIds.includes(val));
                $select.val(newSelected).trigger('change');
                $select.prop('disabled', false);

                return response;
            });
        }

        // Event binding
        $(document).ready(function() {
            const typeSelect = document.getElementById('edit_type');
            const priceInput = document.getElementById('edit_price');
            const priceGroup = document.getElementById('edit_price_group');

            function togglePriceField() {
                if (typeSelect.value === 'FREE') {
                    priceGroup.style.display = 'none';
                    priceInput.value = 0;
                } else {
                    priceGroup.style.display = 'block';
                }
            }

            if (typeSelect) {
                typeSelect.addEventListener('change', togglePriceField);
                togglePriceField(); // Call on load
            }

            function initFormPlugins() {
                if (document.getElementById('edit_tag')) {
                    new Tagify(document.getElementById('edit_tag'));
                }

                const selectOptions = {
                    placeholder: 'Select options',
                    dropdownParent: $('#offcanvasEditItem'),
                };

                $('#edit_products').select2({
                    ...selectOptions,
                    placeholder: 'Select products'
                });
                $('#edit_sections').select2({
                    ...selectOptions,
                    placeholder: 'Select sections'
                });
                $('#edit_categories').select2({
                    ...selectOptions,
                    placeholder: 'Select categories'
                });
                $('#edit_sub_categories').select2({
                    ...selectOptions,
                    placeholder: 'Select sub-categories'
                });
            }

            initFormPlugins();


            function filterValidSelected($select, validOptions) {
                let currentSelected = $select.val() || [];

                let newSelected = currentSelected.filter(val => validOptions.includes(val));

                if (JSON.stringify(newSelected) !== JSON.stringify(currentSelected)) {
                    $select.val(newSelected).trigger('change');
                }

                return newSelected;
            }


            const productsEditSelect = $('#edit_products');
            const sectionsEditSelect = $('#edit_sections');
            const categoriesEditSelect = $('#edit_categories');
            const subCategoriesEditSelect = $('#edit_sub_categories');

            let prevProductIds = [];
            let prevSectionIds = [];
            let prevCategoryIds = [];

            productsEditSelect.on('change', function() {
                const selectedProducts = $(this).val() || [];

                if (selectedProducts.length === 0) {
                    resetSelect(sectionsEditSelect);
                    resetSelect(categoriesEditSelect);
                    resetSelect(subCategoriesEditSelect);
                } else {
                    sectionsEditSelect.prop('disabled', false);

                    fetchSections(selectedProducts).then((availableSections) => {
                        const validSectionIds = availableSections.map(s => s.id.toString());
                        const newSelectedSections = filterValidSelected(sectionsEditSelect,
                            validSectionIds);

                        if (newSelectedSections.length === 0) {
                            resetSelect(categoriesEditSelect);
                            resetSelect(subCategoriesEditSelect);
                        }
                    });
                }
            });

            sectionsEditSelect.on('change', function() {
                const selectedSections = $(this).val() || [];

                if (selectedSections.length === 0) {
                    resetSelect(categoriesEditSelect);
                    resetSelect(subCategoriesEditSelect);
                } else {
                    categoriesEditSelect.prop('disabled', false);

                    fetchCategories(selectedSections).then((availableCategories) => {
                        const validCategoryIds = availableCategories.map(c => c.id.toString());
                        const newSelectedCategories = filterValidSelected(categoriesEditSelect,
                            validCategoryIds);

                        if (newSelectedCategories.length === 0) {
                            resetSelect(subCategoriesEditSelect);
                        }
                    });
                }
            });

            categoriesEditSelect.on('change', function() {
                const selectedCategories = $(this).val() || [];

                if (selectedCategories.length === 0) {
                    resetSelect(subCategoriesEditSelect);
                } else {
                    subCategoriesEditSelect.prop('disabled', false);

                    fetchSubCategories(selectedCategories).then((availableSubs) => {
                        const validSubIds = availableSubs.map(s => s.id.toString());
                        filterValidSelected(subCategoriesEditSelect, validSubIds);
                    });
                }
            });

            // === Utility functions ===

            function disableBelow(select) {
                if (select.is(sectionsEditSelect)) {
                    sectionsEditSelect.prop('disabled', true).val(null).trigger('change').empty();
                    categoriesEditSelect.prop('disabled', true).val(null).trigger('change').empty();
                    subCategoriesEditSelect.prop('disabled', true).val(null).trigger('change').empty();
                } else if (select.is(categoriesEditSelect)) {
                    categoriesEditSelect.prop('disabled', true).val(null).trigger('change').empty();
                    subCategoriesEditSelect.prop('disabled', true).val(null).trigger('change').empty();
                } else if (select.is(subCategoriesEditSelect)) {
                    subCategoriesEditSelect.prop('disabled', true).val(null).trigger('change').empty();
                }
            }

            function resetSelect($select) {
                $select.val(null).trigger('change');
                $select.empty().prop('disabled', true);
            }


            function checkEditDependencies() {
                const productIds = productsEditSelect.val() || [];
                const sectionIds = sectionsEditSelect.val() || [];
                const categoryIds = categoriesEditSelect.val() || [];

                if (productIds.length === 0) {
                    resetSelect(sectionsEditSelect);
                    resetSelect(categoriesEditSelect);
                    resetSelect(subCategoriesEditSelect);
                    return;
                }

                if (sectionIds.length === 0) {
                    resetSelect(categoriesEditSelect);
                    resetSelect(subCategoriesEditSelect);
                    return;
                }

                if (categoryIds.length === 0) {
                    resetSelect(subCategoriesEditSelect);
                    return;
                }

                sectionsEditSelect.prop('disabled', false);
                categoriesEditSelect.prop('disabled', false);
                subCategoriesEditSelect.prop('disabled', false);
            }


            // Expose to global scope
            window.checkEditDependencies = checkEditDependencies;
        });
    </script>
@endpush
