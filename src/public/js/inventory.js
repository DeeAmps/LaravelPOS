(function($){
    let createCategoryModal = $('#create-category-modal');
    let editCategoryModal = $('#edit-category-modal');

    let createProductModal = $('#create-product-modal');
    let editProductModal = $('#edit-product-modal');

    let createProductTypeModal = $('#create-product-type-modal');
    let editProductTypeModal = $('#edit-product_type-modal');

    let createManufacturerModal = $('#create-manufacturer-modal');
    let editManufacturerModal = $('#edit-manufacturer-modal');

    $(document).ready(()=> {
        initInventoryTable(products.data);

        $(document).on('click', '.add-quantity', (event)=> {
            let entry = $(event.target).closest('tr');
            let data = {};
            data.quantity = entry.find('.stock-quantity-input').val();
            data.product = entry.find('.product-input-id').text();
            data.stock = entry.find('.stock-unit-input-id').text();
            updateStockQuantityFor(data, (result)=> {
                Notifier.success(result.message || '');
                location.reload(true);
            })
        });

        $(document).on('click', '.subtract-quantity', (event)=> {
            let entry = $(event.target).closest('tr');
            let data = {};
            data.quantity = -(entry.find('.stock-quantity-input').val());
            data.product = entry.find('.product-input-id').text();
            data.stock = entry.find('.stock-unit-input-id').text();
            updateStockQuantityFor(data, (result)=> {
                Notifier.success(result.message || '');
                location.reload(true);
            })
        });


        $(document).on('click', '.inventory-entry', (event)=> {
            let $entry = $(event.target).closest('tr');
            highlightEntry($entry);
            let productData = $entry.data('inventory_entry_data') || {};
            handleAppendToInventoryDetail(productData)
        })

    });


    function initInventoryTable(product_list) {
        let products = product_list;
        products.forEach((product)=> {
            appendToInventoryTable(product)
        });
    }


    function appendToInventoryTable(product_item) {
        let base = $('#inventory-products-tbody');
        let product = product_item;
        let category = (product.category || {}).name || '';
        let defaultStock = (product.stock_units || []).filter((item)=> {
                                                        return item.id == 1;
                                                    })[0] || {};                                        
        let pieceSku = defaultStock || {};                                                                                  
        let $row = $(`<tr></tr>`).html(`<td>${product.name || ''}</td>
                                    <td>${category}</td>
                                    <td>${(pieceSku.stock || {}).stock_quantity}</td>
                                    <td>${(((pieceSku.stock || {}).cost_price)/100).toFixed(2)}</td>
                                    <td>${(((pieceSku.stock || {}).selling_price)/100).toFixed(2)}</td>`).addClass('inventory-entry');
        $row.data('inventory_entry_data', product);                            
        base.append($row)                                            
    }


    function handleAppendToInventoryDetail(data) {
        let productData = data || {};
        let stockUnits = data.stock_units || [];
        $('#inventory-detail-tbody').empty();
        $('#detail-product-name').text(productData.name||'');
        let defaultSku = stockUnits.filter((stock)=> {
                                            return stock.id === 1;
                                        })[0] || {};
        let skuQuantity = (defaultSku.stock||{}).stock_quantity || 0.00;                                
        stockUnits.forEach((stock)=> {
            appendToInventoryDetailTable(stock, skuQuantity);
        });
    }


    function appendToInventoryDetailTable(data, default_sku_quantity) {
        let stock = data || {};
        let sku = stock.label || '';
        let skuCP = (((stock.stock || {}).cost_price)/100) || 0.00;
        let skuSP = (((stock.stock || {}).selling_price)/100).toFixed(2) || 0.00;
        let rsku = stock.relative_sku_to_sku || 1;
        let quantity = parseInt((default_sku_quantity/rsku));
        let base = $('#inventory-detail-tbody');
        let $row = $(`<tr></tr>`).html(`<td>${sku}</td>
                                        <td>${quantity}</td>
                                        <td>${skuCP}</td>
                                        <td>${skuSP}</td>`)
        base.append($row);
    }


    $(document).on('click', '.create-stock-btn', (event)=> {
        getStockUnits((result)=> {
            if(result.code == 0) {
                let stocks = (result.stock_units) || [];
                $('#add-stock-modal').modal('show');
                appendOptionsToSelect($('#add-stock-stock-unit-select'), stocks);
            }
        })
    });


    $(document).on('change mouseup keyup', '.stock-unit-cost-price, .stock-unit-profit', (event)=>{
        let $entry = $(event.target).closest('tr');
        let cp = Number($entry.find('.stock-unit-cost-price').val());
        let profit = Number($entry.find('.stock-unit-profit').val());
        let profitValue = 0;
        if(profit) {
            profitValue = ((profit/100) * cp).toFixed(2);
        }
        let sp = (Number(cp)+Number(profitValue)).toFixed(2);
        $entry.find('.stock-unit-selling-price').val(sp);
    });


    $(document).on('click', '#add-stock-submit-btn', (event)=> {
        let stockId = $('#add-stock-stock-unit-select').val();
        let data = {stock_unit_id:stockId};
        console.log(data)
        addStockUnitToProduct(_product.id, data, (result)=> {
            Notifier.success('stock added succesfully');
            location.reload(true);
        });
    });


    $(document).on('click', '.remove-sku-from-product', handleRemoveSkuFromProduct);


    function handleRemoveSkuFromProduct(event)  {
        let skuEntry = $(event.target).closest('tr');
        let skuId = skuEntry.find('.sku-id').text();
        diassociateSkuFromProduct(skuId, (result)=> {
            if(result.code ==0) {
                Notifier.success('sku removed successfuly');
                location.reload(true);
            }
        })
    }


    function diassociateSkuFromProduct(sku_id, then) {
        let productId = _product.id;
        let url = `${App.ROOT_URL}/inventory/product/${productId}/stock-units/${sku_id}/remove`;
        $.ajax({
            url:url,
            type:'get',
            dataType:'json',
            contentType:'application/json; charset=utf-8',
            headers: {
                'CSRF-TOKEN': App.token,
                'X-CSRF-TOKEN': App.token,
                'XSRF-TOKEN': App.token
            }
        })
        .done((result)=> {
            then(result);
        })
        .fail(App.handleRequestFailure);
    }


    function appendOptionsToSelect($selector, data_list) {
        let options = data_list || [];
        options.forEach((option)=> {
            let $option = `<option value=${option.id}>${option.label}</option>`;
            $selector.append($option)
        })
    }



    function addStockUnitToProduct(productId, data, then) {
        let url = `${App.ROOT_URL}/inventory/product/${productId}/stock-units/add`;
        $.ajax({
            url:url,
            type:'post',
            dataType:'json',
            data: JSON.stringify(data),
            contentType:'application/json; charset=utf-8',
            headers: {
                'CSRF-TOKEN': App.token,
                'X-CSRF-TOKEN': App.token,
                'XSRF-TOKEN': App.token
            }
        })
        .done((result)=> {
            then(result);
        })
        .fail(App.handleRequestFailure);
    }




    $(document).on('click', '.add-new-stock-row', (event)=>{
        getStockUnits((result)=>{
            let stockUnits = result.stock_units;
            console.log(stockUnits);
            handleAddNewStockRow(stockUnits);
        });
    });


    function handleAddNewStockRow(stock_units, then) {
        let suSelect = createDropdown(stock_units);
        console.log(suSelect);
        let $tableBody = $('#stock-unit-price-tbody')
        let $row = $('<tr></tr>').html(`<td class='stock-units'></td>
                                        <td><input type='text' class='cost-price form-control'/></td>
                                        <td><input type='text' class='profit-markup form-control'/></td>
                                        <td><input type='text' class='selling-price form-control'/></td>
                                        <td><button class='btn btn-danger'>X</button></td>`);
        $row.find('.stock-units').append(suSelect);
        $tableBody.append($row);

    }



    function createDropdown(data_list) {
        let options = data_list || [];
        let $select = $('<select></select>').addClass('stock-unit-select');
        options.forEach((option)=> {
            $option = `<option value=${option.id}>${option.label}</option>`;
            $select.append($option);
        })
        return $select;
     }
    


    $(document).on('click', '.product-view', (event)=> {
        event.preventDefault();
        let $productRow = $(event.target).closest('tr');
        let productId = $productRow.find('.product-id-field').text();
        let barcodeId = $productRow.find('.barcode-id-field').text();
        let url = `${App.ROOT_URL}/inventory/product/${productId}/barcode/${barcodeId}`;
        location.href = url;
    })



    $(document).on('click', '.create-category-link', (event)=> {
        console.log('create category')
        openModal(createCategoryModal);
    })

    $(document).on('click', '.edit-category-link', (event)=> {
        console.log('edit')
        let id = $(event.target).closest('tr').find('.category-id').text();
        let categories = getCategoryFor(id, (result)=> {
            console.log(result);
            openModal(editCategoryModal);
            handleInitEditCategoryForm(result);
        });   
    })


    $(document).on('click', '.create-manufacturer-link', (event)=> {
        openModal(createManufacturerModal);
    })

    $(document).on('click', '.edit-manufacturer-link', (event)=> {
        openModal(editManufacturerModal);
    })


    $(document).on('click', '.create-product-link', (event)=> {
        let url = `${App.ROOT_URL}/inventory`;
        $.ajax({
            url:url,
            type:'get',
            dataType:'json',
            headers: {
                'CSRF-TOKEN': App.token,
                'X-CSRF-TOKEN': App.token,
                'XSRF-TOKEN': App.token
            }
        })
        .done((result)=> {
            openModal(createProductModal);
            handleInitCreateProductForm(result);
        })
        .fail(App.handleRequestFailure);
    })

    $(document).on('click', '.edit-product-link', (event)=> {
        openModal(editProductModal);
    })


    $(document).on('click', '#create-category-modal-btn', (event)=> {
        let data = getCreateCategoryDataFromForm();
        console.log(data);
        createCategory(data, (result)=> {
            console.log(result);
            Notifier.success('category created successfully');
            //createCategoryModal.modal('hide');
            location.reload(true);
        })
    })


    $(document).on('click', '#edit-category-modal-btn', (event)=> {
        console.log('hey')
        console.log($('#edit-category-name').val());
        let data = getEditCategoryDataFromForm() || {};
        console.log(data);
        let id = 
        updateCategory(data, data.id, (result)=> {
            if(result.code == 0) {
                Notifier.success('successful')
                location.reload(true);
            }
        })
    })

    $(document).on('click', '.category-delete', (event)=> {
        let $entry = $(event.target).closest('tr')
        let categoryId = $entry.find('.category-id').text();
        deleteCategoryFor(categoryId, (result)=> {
            if(result.code == 0) {
                Notifier.success('delete successful');
                $entry.remove();
            } else {
                Notifier.error('delete failed!');
            }
        });
    })


    function getCreateCategoryDataFromForm() {
        let name = $('#create-category-name').val();
        let description = $('#create-category-description').val();
        let data = {name:name, description:description};
        return data;
    }

    function getEditCategoryDataFromForm() {
        let id = $('#edit-category-id').val();
        let name = $('#edit-category-name').val();
        let description = $('#edit-category-description').val();
        let data = {name:name, description:description, id:id};
        return data;
    }


    function getCreateProductDataFromForm() {
        let productCategory = $('#create-product-category').val();
        let name = $('#create-product-name').val();
        let categoryText = $('#create-product-category option:selected').text();
        let productName = `${name} ${(categoryText || '')}`
        let productManufacturer = $('create-product-manufacturer');
        let data= {name:productName, category_id:productCategory, manufacturer_id:productManufacturer};
        return data;
    }
    function getEditProductDataFromForm() {
        let productCategory = $('#edit-product-category').val();
        let name = $('#edit-product-name').val();
        let categoryText = $('#edit-product-category option:selected').text();
        let productName = `${name} ${(categoryText || '')}`
        let productManufacturer = $('edit-product-manufacturer');
        let data= {name:productName, category_id:productCategory, manufacturer_id:productManufacturer};
        return data;
    }


    function getProductTypeDataFromForm() {
        let product = $('#product').val();
        let name = $('#product_type-name').val();
        let productManufacturer = $('product_type');
        let data= {name:name, category_id:productCategory, manufacturer_id:productManufacturer};
        return data;
    }

    function getManufacturerDataFromForm() {
        let name = $('#product-name').val();
        let address = $('manufacturer_address');
        let email = $('#manufacturer_email').val();
        let data= {name:name, address:address, email:email};
        return data;
    }



    function createProductType(data, then) {
        let url = `${App.ROOT_URL}/inventory/product_type`;
        $.ajax({
            url:url,
            type:'post',
            dataType:'json',
            data: JSON.stringify(data),
            contentType:'application/json; charset=utf-8',
            headers: {
                'CSRF-TOKEN': App.token,
                'X-CSRF-TOKEN': App.token,
                'XSRF-TOKEN': App.token
            }
        })
        .done((result)=> {
            then(result);
        })
        .fail(App.handleRequestFailure);
    }


    function createProduct(data, then) {
        let url = `${App.ROOT_URL}/inventory/product`;
        $.ajax({
            url:url,
            type:'post',
            dataType:'json',
            data: JSON.stringify(data),
            contentType:'application/json; charset=utf-8',
            headers: {
                'CSRF-TOKEN': App.token,
                'X-CSRF-TOKEN': App.token,
                'XSRF-TOKEN': App.token
            }
        })
        .done((result)=> {
            then(result);
        })
        .fail(App.handleRequestFailure);
    }



    function createCategory(data, then) {
        let url = `${App.ROOT_URL}/inventory/category`;
        $.ajax({
            url:url,
            type:'post',
            dataType:'json',
            data: JSON.stringify(data),
            contentType:'application/json; charset=utf-8',
            headers: {
                'CSRF-TOKEN': App.token,
                'X-CSRF-TOKEN': App.token,
                'XSRF-TOKEN': App.token
            }
        })
        .done((result)=> {
            then(result);
        })
        .fail(App.handleRequestFailure);
    }

    function updateCategory(data, id, then) {
        console.log(id);
        let url = `${App.ROOT_URL}/inventory/category/${id}`;
        console.log(url);
        $.ajax({
            url:url,
            type:'put',
            dataType:'json',
            data: JSON.stringify(data),
            contentType:'application/json; charset=utf-8',
            headers: {
                'CSRF-TOKEN': App.token,
                'X-CSRF-TOKEN': App.token,
                'XSRF-TOKEN': App.token
            }
        })
        .done((result)=> {
            then(result);
        })
        .fail(App.handleRequestFailure);
    }

    function getCategoryFor(id, then) {
        let url = `${App.ROOT_URL}/inventory/category/${id}/edit`;
        $.ajax({
            url:url,
            type:'get',
            dataType:'json',
            headers: {
                'CSRF-TOKEN': App.token,
                'X-CSRF-TOKEN': App.token,
                'XSRF-TOKEN': App.token
            }
        })
        .done((result)=> {
            then(result);
        })
        .fail(App.handleRequestFailure);
    }


    function deleteCategoryFor(id, then) {
        let url = `${App.ROOT_URL}/inventory/category/${id}`;
        $.ajax({
            url:url,
            type:'delete',
            dataType:'json',
            headers: {
                'CSRF-TOKEN': App.token,
                'X-CSRF-TOKEN': App.token,
                'XSRF-TOKEN': App.token
            }
        })
        .done((result)=> {
            then(result);
        })
        .fail(App.handleRequestFailure);
    }


    function openModal($selector) {
        $selector.modal('show');
    }
    function closeModal($selector) {
        $selector.modal('hide')
    }

    function handleInitEditCategoryForm(category) {
        $('#edit-category-id').val(category.id);
        $('#edit-category-name').val(category.name)
        $('#edit-category-description').val(category.description);
    }

    function buildSelect($selector, list, defaultId) {
        let options = list||[];
        options.forEach((option)=> {
            let $row = `<option value=${option.id}>${option.name}</option>`
            $selector.append($row);
        });
        if(defaultId) {
            $selector.val(defaultId);
        }
    }

    function handleInitCreateProductForm(result) {
        let categories = (result.categories) || [];
        let manufacturers = (result.manufacturers);
    }




    function getStockUnits(then) {
        let url = `${App.ROOT_URL}/inventory/stock-units`;
        $.ajax({
            url:url,
            type:'get',
            dataType:'json',
            headers: {
                'CSRF-TOKEN': App.token,
                'X-CSRF-TOKEN': App.token,
                'XSRF-TOKEN': App.token
            }
        })
        .done((result)=> {
            then(result);
        })
        .fail(App.handleRequestFailure);
    }

    function updateStockQuantityFor(entry_data, then) {
        let data = entry_data || {};
        let productId = data.product || '';
        let stockUnitId = data.stock || '';
        let url = `${App.ROOT_URL}/inventory/product/${productId}/stock-unit/${stockUnitId}/update-stock-quantity`;
        $.ajax({
            url:url,
            data: JSON.stringify(data),
            contentType:'application/json; charset=utf-8',
            type:'put',
            dataType:'json',
            headers: {
                'CSRF-TOKEN': App.token,
                'X-CSRF-TOKEN': App.token,
                'XSRF-TOKEN': App.token
            }
        })
        .done((result)=> {
            then(result);
        })
        .fail(App.handleRequestFailure);
    }

    function inventorySearch(filter, then) {
        let url = `${App.ROOT_URL}/inventory/inventory-product-search?filter=${filter}`;
        $.ajax({
            url:url,
            contentType:'application/json; charset=utf-8',
            type:'get',
            headers: {
                'CSRF-TOKEN': App.token,
                'X-CSRF-TOKEN': App.token,
                'XSRF-TOKEN': App.token
            }
        })
        .done((result)=> {
            then(result);
        })
        .fail(App.handleRequestFailure);
    }

    function highlightEntry($selected_entry) {
        $('.inventory-entry').each(function() {
            let $entry = $(this);
            $entry.removeClass('selected-inventory-entry');
        });
        $selected_entry.addClass('selected-inventory-entry');
    }
})(jQuery);