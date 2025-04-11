<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddItem" aria-labelledby="offcanvasAddItem"
    data-bs-scroll="true" style="max-height: 100vh;">
    <div class="offcanvas-header">
        <h5 id="offcanvasAddItem" class="offcanvas-title">Add Items</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body" style="overflow-y: auto;">
        <form id="addItemForm">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="products" class="form-label">Products <span class="text-danger">*</span></label>
                <select name="products[]" id="products" class="select2 form-select" multiple disabled required>
                    <!-- Options will be populated dynamically -->
                </select>
            </div>

            <div class="mb-3">
                <label for="sections" class="form-label">Sections <span class="text-danger">*</span></label>
                <select name="sections[]" id="sections" class="select2 form-select" multiple disabled required>
                    <!-- Options will be populated dynamically -->
                </select>
            </div>

            <div class="mb-3">
                <label for="categories" class="form-label">Categories <span class="text-danger">*</span></label>
                <select name="categories[]" id="categories" class="select2 form-select" multiple disabled required>
                    <!-- Options will be populated dynamically -->
                </select>
            </div>

            <div class="mb-3">
                <label for="sub_categories" class="form-label">Sub Categories <span class="text-danger">*</span></label>
                <select name="sub_categories[]" id="sub_categories" class="select2 form-select" multiple disabled required>
                    <!-- Options will be populated dynamically -->
                </select>
            </div>

            <div class="mb-3">
                <label for="file_format_id" class="form-label">File Format <span class="text-danger">*</span></label>
                <select name="file_format_id" id="file_format_id" class="select2 form-select" required>
                    @foreach ($fileFormats as $format)
                        <option value="{{ $format->id }}">{{ $format->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="thumbnail" class="form-label">Thumbnail (URL LINK)</label>
                <textarea name="thumbnail" id="thumbnail" class="form-control" rows="2"></textarea>
            </div>

            <div class="mb-3">
                <label for="tag" class="form-label">Tags</label>
                <input name="tag" id="tag" class="form-control" placeholder="Add tags...">
            </div>

            <div class="mb-3">
                <label for="file_name" class="form-label">File Name <span class="text-danger">*</span></label>
                <input type="text" name="file_name" id="file_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                <select name="type" id="type" class="form-select" required>
                    <option value="FREE">Free</option>
                    <option value="PREMIUM">Premium</option>
                </select>
            </div>

            <div class="mb-3" id="price-group">
                <label for="price" class="form-label">Price</label>
                <input type="number" name="price" id="price" class="form-control" min="0">
            </div>

            <div class="mb-3">
                <label for="order_number" class="form-label">Order Number</label>
                <input type="number" name="order_number" id="order_number" class="form-control" min="0">
            </div>

            <div class="mb-3">
                <label for="description-editor" class="form-label">Description</label>
                <div id="description_add_quill" style="min-height: 150px;"></div>
                <input type="hidden" name="description" id="description_add_hidden" />
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="select2 form-select" required>
                    <option value="" disabled selected>Select status</option>
                    <option value="DRAFT">Draft</option>
                    <option value="PENDING">Pending</option>
                    <option value="PUBLISHED">Published</option>
                    <option value="INACTIVE">Inactive</option>
                    <option value="ARCHIVED">Archived</option>
                    <option value="DELETED">Deleted</option>
                </select>
            </div>

            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="offcanvas">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const priceInput = document.getElementById('price');
        const priceGroup = document.getElementById('price-group');

        function togglePriceField() {
            if (typeSelect.value === 'FREE') {
                priceGroup.style.display = 'none';
                priceInput.value = 0;
            } else {
                priceGroup.style.display = 'block';
            }
        }

        typeSelect.addEventListener('change', togglePriceField);
        togglePriceField();

        new Tagify(document.getElementById('tag'));
    });

    document.addEventListener('DOMContentLoaded', function() {
        const productsSelect = $('#products');
        const sectionsSelect = $('#sections');
        const categoriesSelect = $('#categories');
        const subCategoriesSelect = $('#sub_categories');

        // Initialize Select2
        productsSelect.select2({
            placeholder: 'Select products'
        });
        sectionsSelect.select2({
            placeholder: 'Select sections'
        });
        categoriesSelect.select2({
            placeholder: 'Select categories'
        });
        subCategoriesSelect.select2({
            placeholder: 'Select sub-categories'
        });

        // Fetch products on page load
        fetchProducts();

        // Enable sections when products are selected
        productsSelect.on('change', function() {
            const productIds = $(this).val();
            if (productIds.length > 0) {
                sectionsSelect.prop('disabled', false);
                fetchSections(productIds);
            } else {
                sectionsSelect.prop('disabled', true).empty();
                categoriesSelect.prop('disabled', true).empty();
                subCategoriesSelect.prop('disabled', true).empty();
            }
        });

        // Enable categories when sections are selected
        sectionsSelect.on('change', function() {
            const sectionIds = $(this).val();
            if (sectionIds.length > 0) {
                categoriesSelect.prop('disabled', false);
                fetchCategories(sectionIds);
            } else {
                categoriesSelect.prop('disabled', true).empty();
                subCategoriesSelect.prop('disabled', true).empty();
            }
        });

        // Enable sub-categories when categories are selected
        categoriesSelect.on('change', function() {
            const categoryIds = $(this).val();
            if (categoryIds.length > 0) {
                subCategoriesSelect.prop('disabled', false);
                fetchSubCategories(categoryIds);
            } else {
                subCategoriesSelect.prop('disabled', true).empty();
            }
        });

        // Fetch products
        function fetchProducts() {
            $.ajax({
                url: '/dashboard/item/products', // Endpoint untuk mendapatkan data products
                type: 'GET',
                success: function(response) {
                    productsSelect.empty();
                    response.forEach(product => {
                        productsSelect.append(new Option(product.title, product
                            .id));
                    });
                    productsSelect.prop('disabled', false); // Enable products select
                },
                error: function() {
                    alert('Failed to fetch products.');
                }
            });
        }

        // Fetch sections by product IDs
        function fetchSections(productIds) {
            $.ajax({
                url: '/dashboard/item/sections',
                type: 'GET',
                data: {
                    product_ids: productIds
                },
                success: function(response) {
                    sectionsSelect.empty();
                    response.forEach(section => {
                        sectionsSelect.append(new Option(section.title, section.id));
                    });
                },
                error: function() {
                    alert('Failed to fetch sections.');
                }
            });
        }

        // Fetch categories by section IDs
        function fetchCategories(sectionIds) {
            $.ajax({
                url: '/dashboard/item/categories',
                type: 'GET',
                data: {
                    section_ids: sectionIds
                },
                success: function(response) {
                    categoriesSelect.empty();
                    response.forEach(category => {
                        categoriesSelect.append(new Option(category.title, category.id));
                    });
                },
                error: function() {
                    alert('Failed to fetch categories.');
                }
            });
        }

        // Fetch sub-categories by category IDs
        function fetchSubCategories(categoryIds) {
            $.ajax({
                url: '/dashboard/item/sub-categories',
                type: 'GET',
                data: {
                    category_ids: categoryIds
                },
                success: function(response) {
                    subCategoriesSelect.empty();
                    response.forEach(subCategory => {
                        subCategoriesSelect.append(new Option(subCategory.title, subCategory
                            .id));
                    });
                },
                error: function() {
                    alert('Failed to fetch sub-categories.');
                }
            });
        }
    });
</script>
