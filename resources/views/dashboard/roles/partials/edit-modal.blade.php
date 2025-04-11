<!-- Edit Role Modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-edit-role">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="role-title">Edit Role</h3>
                </div>
                <!-- Edit role form -->
                <form id="editRoleForm" class="row g-3">
                    @csrf
                    @method('PUT')
                    <div class="col-12 mb-4">
                        <label class="form-label" for="editRoleName">Role Name</label>
                        <input type="text" id="editRoleName" name="modalRoleName" class="form-control"
                            placeholder="Enter a role name" />
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Update</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                            aria-label="Close">
                            Cancel
                        </button>
                    </div>
                </form>
                <!--/ Edit role form -->
            </div>
        </div>
    </div>
</div>
