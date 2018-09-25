<div class="modal" id="add-stock-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id='add-stock-form'>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Stock Unit</label>
                            <select name="stock-unit-select" class="form-control" id="add-stock-stock-unit-select"></select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="add-stock-submit-btn">Add sku to product</button>
                    </div>
            </form>
            </div>
        </div>
    </div>