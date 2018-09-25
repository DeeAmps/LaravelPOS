<div class="modal" id="create-product-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Create category</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id='create-product-form'>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Category:</label>
                        <select name="" id="create-product-category" class="form-control"></select>
                    </div>
                    <div class="form-group">
                        <label for="">Name:</label>
                        <input type="text" id="create-product-name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Manufacturer</label>
                        <select name="" id="create-product-manufacturer" class="form-control"></select>
                    </div>
                    <div class="form-group">
                        <label for="">Description:</label>
                        <textArea class="form-control" id="create-product-description" rows="5"></textArea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="create-product-modal-btn">Create category</button>
                </div>
        </form>
        </div>
    </div>
</div>



<div class="modal" id="edit-product-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id='edit-product-form'>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Category:</label>
                            <select name="" id="edit-product-category" class="form-control"></select>
                        </div>
                        <div class="form-group">
                            <label for="">Name:</label>
                            <input type="text" id="edit-product-name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Manufacturer</label>
                            <select name="" id="edit-product-manufacturer" class="form-control"></select>
                        </div>
                        <div class="form-group">
                            <label for="">Description:</label>
                            <textArea class="form-control" id="edit-product-description" rows="5"></textArea>
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