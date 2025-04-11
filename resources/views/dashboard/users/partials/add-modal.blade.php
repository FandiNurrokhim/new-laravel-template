<!-- Add Role Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="role-title">Add New User</h3>
                </div>
                <hr>
                <!-- Add role form -->
                <form id="addRoleForm" class="row g-3">
                    @csrf
                    <div class="col-12 mb-2">
                        <label class="form-label" for="modalEmail">Email
                            <span class="text-danger">*</span>
                        </label>
                        <input type="email" id="modalEmail" name="modalEmail" class="form-control" required
                            placeholder="Enter email" />
                    </div>
                    <div class="col-12 mb-2">
                        <label class="form-label" for="modalUsername">Username
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="modalUsername" name="modalUsername" class="form-control" required
                            placeholder="Enter username" />
                    </div>
                    <div class="col-12 mb-2">
                        <label class="form-label" for="modalPassword">Password
                            <span class="text-danger">*</span>
                        </label>
                        <input type="password" id="modalPassword" name="modalPassword" class="form-control" required
                            placeholder="Enter password" />
                    </div>
                    <div class="col-12 mb-2">
                        <label class="form-label" for="modalRole">Role
                            <span class="text-danger">*</span>
                        </label>
                        <select id="modalRole" name="modalRole" class="select2 form-select" required>
                            <option value="">Select a role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <hr>
                    <div class="col-12 mb-2">
                        <label class="form-label" for="modalStatus">Status
                            <span class="text-danger">*</span>
                        </label>
                        <select id="modalStatus" name="modalStatus" class="select2 form-select" required>
                            <option value="">Select a status</option>
                            <option value="ACTIVE">Active</option>
                            <option value="PENDING">Pending</option>
                            <option value="BLOCK">Block</option>
                            <option value="INACTIVE">Inactive</option>
                            <option value="SUSPENDED">Suspended</option>
                            <option value="DELETED">Deleted</option>
                            <option value="BANNED">Banned</option>
                            <option value="VERIFICATION_EXPIRED">Verification Expired</option>
                        </select>
                    </div>
                    <hr>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                            aria-label="Close">
                            Cancel
                        </button>
                    </div>
                </form>
                <!--/ Add role form -->
            </div>
        </div>
    </div>
</div>
