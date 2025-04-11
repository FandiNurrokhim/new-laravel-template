<!-- Edit Role Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-edit-role">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="role-title">Edit Role</h3>
                </div>
                <hr>
                <!-- Edit role form -->
                <form id="editUserForm" class="row g-3">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editUserId" name="editUserId" />
                    <div class="col-12 mb-2">
                        <label class="form-label" for="editEmail">Email</label>
                        <input type="email" id="editEmail" name="editEmail" class="form-control"
                            placeholder="Enter email" />
                    </div>
                    <div class="col-12 mb-2">
                        <label class="form-label" for="editUsername">Username</label>
                        <input type="text" id="editUsername" name="editUsername" class="form-control"
                            placeholder="Enter username" />
                    </div>
                    <div class="col-12 mb-2">
                        <label class="form-label" for="editPassword">Password</label>
                        <input type="password" id="editPassword" name="editPassword" class="form-control"
                            placeholder="Enter password" />
                        <small class="text-muted">Leave empty if you don't want to change the password</small>
                    </div>
                    <div class="col-12 mb-2">
                        <label class="form-label" for="editRole">Role</label>
                        <select id="editRole" name="editRole" class="select2 form-select">
                            <option value="">Select a role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <hr>
                    <div class="col-12 mb-2">
                        <label class="form-label" for="editStatus">Status</label>
                        <select id="editStatus" name="editStatus" class="select2 form-select">
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
