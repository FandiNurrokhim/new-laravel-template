<div class="modal fade" id="addGraveLocationModal" tabindex="-1" aria-labelledby="addGraveLocationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="addGraveLocationForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addGraveLocationModalLabel">Add Grave Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="grave_group_id" class="form-label">Grave Group</label>
                        <select class="select2 form-select" id="grave_group_id" name="grave_group_id" required>
                            <option value="" disabled selected>Select a group</option>
                            @foreach ($graveGroups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
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