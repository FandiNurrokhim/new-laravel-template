<div class="modal fade" id="editGraveLocationModal" tabindex="-1" aria-labelledby="editGraveLocationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editGraveLocationForm">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editGraveLocationModalLabel">Edit Grave Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label for="edit_grave_group_id" class="form-label">Grave Group</label>
                        <select class="select2 form-select" id="edit_grave_group_id" name="grave_group_id" required>
                            <option value="" disabled selected>Select a group</option>
                            @foreach ($graveGroups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </input>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>