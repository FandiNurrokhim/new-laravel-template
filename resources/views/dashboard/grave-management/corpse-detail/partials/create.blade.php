<div class="modal fade" id="addCorpseDetailModal" tabindex="-1" aria-labelledby="addCorpseDetailModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form id="addCorpseDetailForm" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCorpseDetailModalLabel">Add Corpse Detail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="group_id" class="form-label">Grave Group</label>
                        <select class="select2 form-select" id="group_id" name="group_id" required>
                            <option value="" disabled selected>Select a group</option>
                            @foreach ($graveGroups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="grave_location_id" class="form-label">Grave Location</label>
                        <div class="d-flex flex-wrap grave-box-container">
                            <!-- Grave boxes will be dynamically populated here -->
                        </div>
                        <input type="hidden" id="grave_location_id" name="grave_location_id" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Photo</label>
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="birth_date" class="form-label">Birth Date</label>
                        <input type="date" class="form-control" id="birth_date" name="birth_date"  max="{{ date('Y-m-d') }}"> 
                    </div>
                    <div class="mb-3">
                        <label for="birth_place" class="form-label">Birth Place</label>
                        <input type="text" class="form-control" id="birth_place" name="birth_place">
                    </div>
                    <div class="mb-3">
                        <label for="death_date" class="form-label">Death Date</label>
                        <input type="date" class="form-control" id="death_date" name="death_date" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label for="javanese_day" class="form-label">Javanese Day</label>
                        <select class="form-select" id="javanese_day" name="javanese_day">
                            <option value="" disabled selected>Select a Javanese Day</option>
                            <option value="Legi">Legi</option>
                            <option value="Pahing">Pahing</option>
                            <option value="Pon">Pon</option>
                            <option value="Wage">Wage</option>
                            <option value="Kliwon">Kliwon</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
