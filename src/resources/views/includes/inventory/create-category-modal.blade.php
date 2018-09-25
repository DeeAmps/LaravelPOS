<div class="modal" id="create-category-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Create category</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id='create-category-form'>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Name:</label>
                        <input type="text" id="create-category-name" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="create-category-modal-btn">Create category</button>
                </div>
        </form>
        </div>
    </div>
</div>


<div class="modal" id="edit-category-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Edit category</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id='edit-category-form'>
                <input type="text" class="hidden" id="edit-category-id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Name:</label>
                        <input type="text" id="edit-category-name" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="edit-category-modal-btn">Update category</button>
                </div>
        </form>
        </div>
    </div>
</div>