$(document).ready(function () {

fetchOrders();

    // Handle search input
    $('#orderSearchInput').on('input', function () {
        const searchQuery = $(this).val().trim();
        const statusFilter = $('#statusFilter').val();
        fetchOrders(searchQuery, statusFilter);
    });

    // Handle status filter change
    $('#statusFilter').on('change', function () {
        const statusFilter = $(this).val();
        const searchQuery = $('#orderSearchInput').val().trim();
        fetchOrders(searchQuery, statusFilter);
    });

    function fetchOrders(searchQuery = '', statusFilter = '') {
        $.ajax({
            url: '../database/order_list.php',
            type: 'GET',
            data: { search: searchQuery, status: statusFilter },
            success: function (response) {
                const orders = JSON.parse(response);
                displayOrders(orders);
            },
            error: function () {
                showAlert('Neizdevās ielādēt pasūtījumu datus!', 'danger');
            }
        });
    }

$(document).on('click', '.edit-order', function () {
    const orderId = $(this).data('id');

    // Clear the delivery_number field before opening the modal
    $('#deliveryNumber').val('');

    $.ajax({
        url: '../database/order_get.php',
        type: 'GET',
        data: { id: orderId },
        success: function (response) {
            const result = JSON.parse(response);

            if (result.success) {
                const order = result.order;

                // Populate modal fields
                $('#orderId').val(order.order_ID);
                $('#customerName').val(`${order.name} ${order.surname}`);
                $('#customerEmail').val(order.email);
                $('#customerPhone').val(order.phone);
                $('#deliveryMethod').val(order.delivery);
                $('#totalAmount').val(`€${order.total_amount}`);

                // Populate shipping details
                if (order.pickup_address) {
                    $('#shippingDetails').val(`Pakomāts: ${order.pickup_address}`);
                } else {
                    $('#shippingDetails').val(
                        `Valsts: ${order.country}\nPilsēta: ${order.city}\nIela: ${order.street}\nMājas numurs: ${order.house}\nDzīvokļa numurs: ${order.apartment || 'Nav norādīts'}\nPasta indekss: ${order.postal_code}`
                    );
                }

                // Populate order items
                let itemsTemplate = '';
                order.items.forEach(item => {
                    itemsTemplate += `
                        <tr>
                            <td>${item.ID_product}</td>
                            <td>${item.product_name}</td>
                            <td>${item.quantity}</td>
                            <td>€${item.price}</td>
                        </tr>
                    `;
                });
                $('#orderItemsTable tbody').html(itemsTemplate);

                // Populate status dropdown
                const statusOptions = ['Jauns', 'Pieņemts', 'Nosūtīts', 'Neapmaksāts'];
                let statusTemplate = `<option value="${order.status}" selected>${order.status}</option>`;
                statusOptions.forEach(status => {
                    if (status !== order.status) {
                        statusTemplate += `<option value="${status}">${status}</option>`;
                    }
                });
                $('#orderStatus').html(statusTemplate);

                // Populate delivery number field
                $('#deliveryNumber').val(order.delivery_number || '');

                // Show the modal
                $('#editOrderModal').modal('show');
            } else {
                showAlert(result.error, 'danger');
            }
        },
        error: function () {
            showAlert('Neizdevās ielādēt pasūtījuma datus!', 'danger');
        }
    });
});

$('#saveOrderChanges').on('click', function () {
    const orderId = $('#orderId').val();
    const orderStatus = $('#orderStatus').val();
    const deliveryNumber = $('#deliveryNumber').val(); // Get the delivery number

    $.ajax({
        url: '../database/order_update.php',
        type: 'POST',
        data: { id: orderId, status: orderStatus, delivery_number: deliveryNumber }, // Include delivery_number
        success: function (response) {
            const result = JSON.parse(response);

            if (result.success) {
                showAlert('Pasūtījuma izmaiņas veiksmīgi saglabātas!', 'success');
                $('#editOrderModal').modal('hide');
                fetchOrders(); // Refresh the orders table
            } else {
                showAlert(result.error || 'Neizdevās saglabāt izmaiņas.', 'danger');
            }
        },
        error: function () {
            showAlert('Neizdevās nosūtīt pieprasījumu!', 'danger');
        }
    });
});

function showAlert(message, type = 'success', duration = 5000) {
    const alertId = `alert-${Date.now()}`;
    const alertHTML = `
        <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    $('#alertContainer').append(alertHTML);
    setTimeout(() => {
        $(`#${alertId}`).alert('close');
    }, duration);
}

function displayOrders(orders) {
let template = '';

if (orders.length === 0) {
    template = '<tr><td colspan="9" class="text-center">Nav pieejamu pasūtījumu</td></tr>';
} else {
    orders.forEach(order => {
        template += `
            <tr>
                <td>${order.order_ID}</td>
                <td>${order.name} ${order.surname}</td>
                <td>${order.email}</td>
                <td>${order.phone}</td>
                <td>€${order.total_amount}</td>
                <td>${order.delivery}</td>
                <td>${order.status}</td>
                <td>${order.created_at}</td>
                <td>
                    <button class="btn btn-sm btn-warning edit-order" data-id="${order.order_ID}" title="Rediģēt">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-order" data-id="${order.order_ID}" title="Dzēst">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
}

$('#orderTableBody').html(template);
}


let deleteOrderId = null; // Store the ID of the order to be deleted

$(document).on('click', '.delete-order', function (e) {
    e.preventDefault();
    deleteOrderId = $(this).data('id'); // Get the order ID from the button
    $('#deleteOrderModal').modal('show'); // Show the delete confirmation modal
});

$('#confirmDeleteOrder').on('click', function () {
    if (deleteOrderId) {
        $.post('../database/order_delete.php', { id: deleteOrderId }, function (response) {
            try {
                const result = JSON.parse(response);
                if (result.success) {
                    showAlert('Pasūtījums veiksmīgi dzēsts!', 'success');
                    $('#deleteOrderModal').modal('hide'); // Hide the modal
                    fetchOrders(); // Refresh the orders table
                } else {
                    showAlert(result.error || 'Neizdevās dzēst pasūtījumu.', 'danger');
                }
            } catch (e) {
                console.error('Error parsing response:', e, response);
                showAlert('Neizdevās apstrādāt servera atbildi.', 'danger');
            }
        }).fail(function () {
            showAlert('Neizdevās nosūtīt pieprasījumu.', 'danger');
        });
    }
});




























    let isEditMode = false; 

    fetchCategories();

    function fetchCategories() {
        $.ajax({
            url: '../database/category_list.php',
            type: 'GET',
            success: function (response) {
                const categories = JSON.parse(response);
                displayCategories(categories);
            },
            error: function () {
                alert("Neizdevās ielādēt kategorijas!");
            }
        });
    }

    function displayCategories(categories) {
        let template = "";
    
        if (categories.length === 0) {
            
            template = '<tr><td colspan="4" class="text-left">Nav pieejamu kategoriju</td></tr>';
        } else {
            categories.forEach(category => {
                template += `
                    <tr category_ID="${category.id}">
                        <td>${category.id}</td>
                        <td>${category.big_category}</td>
                        <td>${category.name}</td>
                        <td>
                            <a class="category-edit btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a class="category-delete btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                `;
            });
        }
    
        
        $('#categoryTableBody').html(template);
    }

    
    $('#addCategoryButton').click(function () {
        isEditMode = false; 
        $('#categoryModalLabel').text("Pievienot kategoriju");
        $('#categoryForm')[0].reset(); 
        $('#categoryId').val(""); 
        $('#categoryModal').modal('show');
    });

    
    $(document).on('click', '.category-edit', function (e) {
        e.preventDefault();
        isEditMode = true;
    
        const row = $(e.currentTarget).closest('tr');
        const categoryId = row.attr("category_ID"); 
    
        console.log('Category ID:', categoryId); 
    
        
        $.ajax({
            url: '../database/category_get.php',
            type: 'GET',
            data: { id: categoryId },
            success: function (response) {
                try {
                    const category = JSON.parse(response);
    
                    if (category.error) {
                        alert(category.error);
                        return;
                    }
    
                    
                    $('#categoryModalLabel').text("Rediģēt kategoriju");
                    $('#categoryId').val(category.category_ID);
                    $('#categoryName').val(category.name);
                    $('#bigCategory').val(category.big_category);
    
                    
                    $('#categoryCreatedAtText').text(`Izveidots: ${category.created_at || 'Nav pieejams'}`).show();
                    $('#categoryEditedAtText').text(`Pēdējo reizi rediģēts: ${category.edited || 'Nav rediģēts'}`).show();
    
                    $('#categoryModal').modal('show');
                } catch (e) {
                    console.error("Error parsing response:", e);
                    alert("Neizdevās apstrādāt kategorijas datus!");
                }
            },
            error: function () {
                alert("Neizdevās ielādēt kategorijas datus!");
            }
        });
    });

    
    $('#categoryForm').submit(function (e) {
    e.preventDefault();

    const formData = {
        id: $('#categoryId').val(),
        name: $('#categoryName').val(),
        big_category: $('#bigCategory').val()
    };

    const url = isEditMode
        ? '../database/category_edit.php' 
        : '../database/category_add.php'; 

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        success: function (response) {
            $('#categoryModal').modal('hide');
            $('#categoryForm')[0].reset();
            fetchCategories();

            // Show success notification
            if (isEditMode) {
                showAlert('Veiksmīgi rediģēta kategorija', 'success');
            } else {
                showAlert('Veiksmīgi pievienota kategorija', 'success');
            }
        },
        error: function () {
            alert(isEditMode ? "Neizdevās rediģēt kategoriju!" : "Neizdevās pievienot kategoriju!");
        }
    });
});

    $(document).on('click', '.category-delete', function (e) {
        e.preventDefault();
        console.log("Delete button clicked.");
        deleteCategoryId = $(e.currentTarget).closest('tr').attr("category_ID");
        console.log("Delete Category ID set to:", deleteCategoryId);
        $('#deleteModal').modal('show');
        console.log("Deletion modal opened.");
    });

    // Handle confirm delete button click
   $(document).on('click', '#confirmDelete', function () {
    if (deleteCategoryId) {
        $.post('../database/category_delete.php', { id: deleteCategoryId }, function (response) {
            try {
                const result = JSON.parse(response);
                if (result.success) {
                    $('#deleteModal').modal('hide');
                    fetchCategories();

                    // Show success notification
                    showAlert('Veiksmīgi dzēsta kategorija', 'success');
                } else {
                    showAlert(result.error || 'Neizdevās dzēst kategoriju.', 'danger');
                }
            } catch (e) {
                showAlert('Neizdevās apstrādāt servera atbildi.', 'danger');
            }
        }).fail(function () {
            showAlert('Neizdevās nosūtīt pieprasījumu.', 'danger');
        });
    }
});

    $('#deleteModal').on('shown.bs.modal', function () {
    console.log('Delete modal shown');
    console.log('Confirm Delete button exists:', $('#confirmDelete').length > 0);
});

    function searchCategories() {
        const searchQuery = $('#searchInput').val().trim(); 
        const selectedFilter = $('#categoryFilter').val(); 
    
        $.ajax({
            url: '../database/category_list.php',
            type: 'GET',
            data: {
                search: searchQuery,
                filter: selectedFilter === 'Visi' ? '' : selectedFilter 
            },
            success: function (response) {
                try {
                    const categories = JSON.parse(response);
                    displayCategories(categories); 
                } catch (e) {
                    alert("Neizdevās apstrādāt kategoriju datus!");
                }
            },
            error: function (xhr, status, error) {
                alert("Neizdevās ielādēt kategorijas!");
            }
        });
    }
    
    
    $('#searchInput').on('input', function () {
        searchCategories();
    });

    $('#categoryFilter').on('change', function () {
        const selectedFilter = $(this).val(); 
    
        $.ajax({
            url: '../database/category_list.php',
            type: 'GET',
            data: { filter: selectedFilter === 'Visi' ? '' : selectedFilter }, 
            success: function (response) {
                try {
                    const categories = JSON.parse(response);
                    displayCategories(categories); 
                } catch (e) {
                    alert("Neizdevās apstrādāt kategoriju datus!");
                }
            },
            error: function (xhr, status, error) {
                alert("Neizdevās ielādēt kategorijas!");
            }
        });
    });

    
    $('#categoryFilter').on('change', function () {
        searchCategories();
    });

  




    let isProductEditMode = false; 


    fetchProducts();

    function fetchProducts() {
        $.ajax({
            url: '../database/product_list.php',
            type: 'GET',
            success: function (response) {
                const products = JSON.parse(response);
                displayProducts(products);
            },
            error: function () {
                alert("Neizdevās ielādēt preces!");
            }
        });
    }

    function displayProducts(products) {
        let template = "";
    
        products.forEach(product => {
            template += `
                <tr>
                    <td>${product.id}</td>
                    <td>${product.name}</td>
                    <td>${product.category_name || 'N/A'}</td> <!-- Display category name -->
                    <td>${product.short_description}</td>
                    <td>${product.price} €</td>
                    <td>${product.stock_quantity}</td>
                    <td>
                        <a class="product-edit btn btn-sm btn-warning" data-id="${product.id}">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a class="product-delete btn btn-sm btn-danger" data-id="${product.id}">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
            `;
        });
    
        $('#product').html(template);
    }

    function searchProducts() {
        const searchQuery = $('#searchInput').val().trim(); 
        const selectedCategory = $('#filterCategory').val(); 
        const selectedSort = $('#sortOptions').val(); 
    
        $.ajax({
            url: '../database/product_list.php',
            type: 'GET',
            data: {
                search: searchQuery,
                category: selectedCategory,
                sort: selectedSort
            },
            success: function (response) {
                try {
                    const products = JSON.parse(response);
                    displayProducts(products); 
                } catch (e) {
                    alert("Neizdevās apstrādāt preču datus!");
                }
            },
            error: function (xhr, status, error) {
                alert("Neizdevās ielādēt preces!");
            }
        });
    }
    
    
    $('#searchInput').on('input', function () {
        searchProducts();
    });
   
    function populateCategoryDropdown(selectedCategoryId = null, dropdownId = 'category') {
    return new Promise((resolve, reject) => {
        const categoryDropdown = $(`#${dropdownId}`);
        categoryDropdown.empty();

        if (dropdownId === 'filterCategory') {
            categoryDropdown.append('<option value="">Visas kategorijas</option>');
        } else {
            categoryDropdown.append('<option value="" selected>Izvēlies kategoriju</option>');
        }

        $.ajax({
            url: '../database/category_list.php',
            type: 'GET',
            success: function (response) {
                try {
                    const categories = JSON.parse(response);

                    categories.forEach(category => {
                        const optionText = `${category.name} (${category.big_category})`; // Pievieno big_category blakus nosaukumam
                        const isSelected = selectedCategoryId && selectedCategoryId == category.id ? 'selected' : '';
                        categoryDropdown.append(`<option value="${category.id}" ${isSelected}>${optionText}</option>`);
                    });

                    resolve();
                } catch (e) {
                    console.error("Error parsing categories:", e);
                    reject();
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching categories:", error);
                reject();
            }
        });
    });
}

    $('#filterCategory').on('change', function () {
        const selectedCategory = $(this).val(); 
    
        
        $.ajax({
            url: '../database/product_list.php',
            type: 'GET',
            data: { category: selectedCategory },
            success: function (response) {
                try {
                    const products = JSON.parse(response);
                    displayProducts(products); 
                } catch (e) {
                    alert("Neizdevās apstrādāt preču datus!");
                }
            },
            error: function (xhr, status, error) {
                alert("Neizdevās ielādēt preces!");
            }
        });
    });

    function populateCategoryDropdownWithBigCategory(dropdownId = 'filterCategory') {
        return new Promise((resolve, reject) => {
            const categoryDropdown = $(`#${dropdownId}`);
            categoryDropdown.empty(); 
    
            
            categoryDropdown.append('<option value="">Visas kategorijas</option>');
    
            $.ajax({
                url: '../database/category_list.php',
                type: 'GET',
                success: function (response) {
                    try {
                        const categories = JSON.parse(response);
    
                        
                        categories.forEach(category => {
                            categoryDropdown.append(
                                `<option value="${category.id}">${category.name} ${category.big_category}</option>`
                            );
                        });
                        resolve(); 
                    } catch (e) {
                        reject();
                    }
                },
                error: function (xhr, status, error) {
                    reject();
                }
            });
        });
    }

    populateCategoryDropdownWithBigCategory('filterCategory').then(() => {
    }).catch(() => {
        alert("Neizdevās ielādēt kategorijas filtram!");
    });

    $('#sortOptions').on('change', function () {
        const selectedSort = $(this).val(); 
    
        
        $.ajax({
            url: '../database/product_list.php',
            type: 'GET',
            data: { sort: selectedSort },
            success: function (response) {
                try {
                    const products = JSON.parse(response);
                    displayProducts(products); 
                } catch (e) {
                    alert("Neizdevās apstrādāt preču datus!");
                }
            },
            error: function (xhr, status, error) {
                alert("Neizdevās ielādēt preces!");
            }
        });
    });

    
    function fetchFilteredAndSortedProducts() {
        const selectedCategory = $('#filterCategory').val(); 
        const selectedSort = $('#sortOptions').val(); 

        
        $.ajax({
            url: '../database/product_list.php',
            type: 'GET',
            data: {
                category: selectedCategory,
                sort: selectedSort
            },
            success: function (response) {
                try {
                    const products = JSON.parse(response);
                    displayProducts(products); 
                } catch (e) {
                    alert("Neizdevās apstrādāt preču datus!");
                }
            },
            error: function (xhr, status, error) {
                alert("Neizdevās ielādēt preces!");
            }
        });
    }

    
    $('#filterCategory').on('change', function () {
        fetchFilteredAndSortedProducts();
    });

    
    $('#sortOptions').on('change', function () {
        fetchFilteredAndSortedProducts();
    });

    

    $('#image').on('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#imagePreview')
                    .attr('src', e.target.result)
                    .show();
                $('#imagePath').val(''); 
            };
            reader.readAsDataURL(file);
        } else {
            $('#imagePreview')
                .attr('src', '')
                .hide();
            $('#imagePath').val(''); 
        }
    });

    


    
    $(document).on('click', '#addProductButton', function () {
    isProductEditMode = false; // Ensure this is set to false

    $('#productForm')[0].reset();
    $('#productId').val("");
    $('#imagePreview').hide();
    $('#imagePreview').attr('src', '');
    $('#imagePath').val('');

    $('#addProductModalLabel').text("Pievienot preci");

    populateCategoryDropdown()
        .then(() => {
            $('#addProductModal').modal('show');
        })
        .catch(() => {
            alert("Neizdevās ielādēt kategorijas!");
        });
});

    $('#addProductModal').on('hidden.bs.modal', function () {
    // Do not reset isProductEditMode here
    $('#productForm')[0].reset();
    $('#productId').val("");
    $('#imagePreview').hide();
    $('#imagePreview').attr('src', '');
    $('#addProductModalLabel').text("Pievienot preci");
    $('#addProductModal').find(':focus').blur();
});


    
    $(document).on('click', '.product-edit', function (e) {
    e.preventDefault();
    isProductEditMode = true; // Ensure this is set to true
    const productId = $(this).data('id');

    $.ajax({
        url: '../database/product_get.php',
        type: 'GET',
        data: { id: productId },
        success: function (response) {
            console.log('Server response:', response); // Debugging log

            try {
                const product = JSON.parse(response); // Parse the response

                // Populate the form fields with product data
                $('#productId').val(product.product_ID);
                $('#productName').val(product.name);
                $('#shortDescription').val(product.short_description);
                $('#longDescription').val(product.long_description || '');
                $('#material').val(product.material || '');
                $('#size').val(product.size || '');
                $('#color').val(product.color || '');
                $('#care').val(product.care || '');
                $('#price').val(product.price);
                $('#quantity').val(product.stock_quantity);
                $('#imagePath').val(product.image || '');

                // Populate the category dropdown
                populateCategoryDropdown(product.ID_category)
                    .then(() => {
                        $('#addProductModalLabel').text("Rediģēt preci");
                        $('#addProductModal').modal('show');
                    })
                    .catch(() => {
                        alert("Neizdevās ielādēt kategorijas!");
                    });

                // Show the product image preview
                if (product.image) {
                    const imagePath = product.image.startsWith('images/') ? `../${product.image}` : product.image;
                    $('#imagePreview').attr('src', imagePath).show();
                } else {
                    $('#imagePreview').hide();
                }
            } catch (e) {
                console.error('Error parsing server response:', e, response); // Debugging log
                showAlert('Neizdevās apstrādāt servera atbildi!', 'danger');
            }
        },
        error: function () {
            showAlert('Neizdevās nosūtīt pieprasījumu!', 'danger');
        }
    });
});
    
    $('#productForm').submit(function (e) {
    e.preventDefault();

    const formData = new FormData();
    formData.append('id', $('#productId').val());
    formData.append('name', $('#productName').val());
    formData.append('short_description', $('#shortDescription').val());
    formData.append('long_description', $('#longDescription').val());
    formData.append('material', $('#material').val());
    formData.append('size', $('#size').val());
    formData.append('color', $('#color').val());
    formData.append('care', $('#care').val());
    formData.append('price', $('#price').val());
    formData.append('stock_quantity', $('#quantity').val());
    formData.append('category_id', $('#category').val());

    const imageFile = $('#image')[0].files[0];
    if (imageFile) {
        formData.append('image', imageFile);
    } else {
        formData.append('current_image', $('#imagePath').val());
    }

    const url = isProductEditMode
        ? '../database/product_edit.php'
        : '../database/product_add.php';

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
    console.log('Server response:', response); // Debugging log
    try {
        const result = typeof response === 'string' ? JSON.parse(response) : response;

        if (result.success) {
    console.log('isProductEditMode:', isProductEditMode); // Debugging log
    $('#addProductModal').modal('hide');
    $('#productForm')[0].reset();
    fetchProducts();

    // Show success notification
    if (isProductEditMode) {
        showAlert('Veiksmīgi rediģēts produkts!', 'success');
    } else {
        showAlert('Veiksmīgi pievienots produkts!', 'success');
    }
}
    } catch (e) {
        console.error('Error parsing server response:', e, response); // Debugging log
        showAlert('Neizdevās apstrādāt servera atbildi!', 'danger');
    }
}
    });
});



    $(document).on('click', '.product-delete', function (e) {
    e.preventDefault();
    const deleteProductId = $(e.currentTarget).data('id'); // Directly get the ID
    $('#deleteModal').modal('show');

    $('#confirmDelete').off('click').on('click', function () {
        $.post('../database/product_delete.php', { id: deleteProductId }, function (response) {
            try {
                const result = JSON.parse(response);
                if (result.success) {
                    $('#deleteModal').modal('hide');
                    fetchProducts();
                    showAlert('Veiksmīgi dzēsts produkts!', 'success');
                } else {
                    showAlert(result.error || 'Neizdevās dzēst produktu!', 'danger');
                }
            } catch (e) {
                console.error('Invalid JSON response:', response);
                showAlert('Neizdevās apstrādāt servera atbildi!', 'danger');
            }
        }).fail(function () {
            showAlert('Neizdevās nosūtīt pieprasījumu!', 'danger');
        });
    });
});










        function fetchGallery() {
        $.ajax({
            url: '../database/gallery_list.php',
            type: 'GET',
            success: function (response) {
                try {
                    const gallery = JSON.parse(response);
                    displayGallery(gallery);
                } catch (e) {
                    console.error('Invalid JSON response:', response);
                    alert("Neizdevās apstrādāt galerijas datus!");
                }
            },
            error: function () {
                alert("Neizdevās ielādēt galerijas datus!");
            }
        });
    }

    // Define the displayGallery function
    function displayGallery(gallery) {
        let template = "";

        gallery.forEach(item => {
            let statusText = "";
            switch (item.status) {
                case 'approved':
                    statusText = 'Apstiprināts';
                    break;
                case 'denied':
                    statusText = 'Noraidīts';
                    break;
                case 'onhold':
                    statusText = 'Gaida apstiprinājumu';
                    break;
                default:
                    statusText = 'Nezināms';
            }

            let actionButtons = "";
            if (item.status === 'onhold') {
                actionButtons = `
                    <a href="#" class="btn btn-sm btn-success approve-image" data-id="${item.id}">
                        <i class="fas fa-check"></i>
                    </a>
                    <a href="#" class="btn btn-sm btn-danger decline-image" data-id="${item.id}">
                        <i class="fas fa-times"></i>
                    </a>
                `;
            }

            template += `
                <tr>
                    <td>${item.id}</td>
                    <td><img src="../${item.image_path}" style="max-width: 100px; height: auto;"></td>
                    <td>${item.posted_by}</td>
                    <td>${item.review ? `"${item.review}"` : 'Nav atsauksmes'}</td> <!-- Add review here -->
                    <td>${statusText}</td>
                    <td>${actionButtons}</td>
                </tr>
            `;
        });

        $('#gallery').html(template);
    }

    // Call the fetchGallery function
    fetchGallery();
    
        

    $(document).on('submit', '#addImageForm', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    $.ajax({
        url: '../database/gallery_add.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            try {
                const result = JSON.parse(response);
                if (result.success) {
                    $('#addImageModal').modal('hide');
                    $('#addImageForm')[0].reset();
                    fetchGallery();

                    // Show success notification
                    showAlert('Veiksmīgi pievienota bilde!', 'success');
                } else {
                    showAlert(result.error || 'Neizdevās pievienot bildi.', 'danger');
                }
            } catch (e) {
                console.error('Invalid JSON response:', response);
                showAlert('Neizdevās apstrādāt servera atbildi!', 'danger');
            }
        },
        error: function () {
            showAlert('Neizdevās nosūtīt pieprasījumu!', 'danger');
        }
    });
});

    $(document).on('click', '.approve-image, .decline-image', function (e) {
        e.preventDefault();

        const button = $(this);
        const galleryId = button.data('id');
        const status = button.hasClass('approve-image') ? 'approved' : 'denied'; // Updated "declined" to "denied"

        console.log(`Sending request to update status: gallery_id=${galleryId}, status=${status}`);

        $.ajax({
            url: '../database/gallery_update_status.php',
            type: 'POST',
            data: { gallery_id: galleryId, status: status },
            success: function (response) {
                console.log('Server response:', response);
                try {
                    const result = JSON.parse(response);
                    if (result.success) {
                        fetchGallery();

                        // Show success notification
                        if (status === 'approved') {
                            showAlert('Veiksmīgi apstiprināta bilde!', 'success');
                        } else {
                            showAlert('Veiksmīgi noraidīta bilde!', 'success');
                        }
                    } else {
                        console.error('Error updating status:', result.error);
                        showAlert(result.error || 'Neizdevās atjaunināt statusu.', 'danger');
                    }
                } catch (e) {
                    console.error('Invalid JSON response:', response);
                    showAlert('Neizdevās apstrādāt servera atbildi!', 'danger');
                }
            },
            error: function () {
                console.error('AJAX request failed.');
                showAlert('Neizdevās nosūtīt pieprasījumu!', 'danger');
            }
        });
    });

        $('#statusFilter').on('change', function () {
            const selectedStatus = $(this).val(); 
        
            
            $.ajax({
                url: '../database/gallery_list.php', 
                type: 'GET',
                data: { status: selectedStatus }, 
                success: function (response) {
                    try {
                        const gallery = JSON.parse(response);
                        displayGallery(gallery); 
                    } catch (e) {
                        alert("Neizdevās apstrādāt galerijas datus!");
                    }
                },
                error: function () {
                    alert("Neizdevās ielādēt galerijas datus!");
                }
            });
        });

        $(document).on('click', '#gallery img', function () {
            const imageUrl = $(this).attr('src'); 
            $('#modalImage').attr('src', imageUrl); 
            $('#imagePreviewModal').modal('show'); 
        });







        fetchFairs();

        function fetchFairs() {
            $.ajax({
                url: '../database/fair_list.php',
                type: 'GET',
                success: function (response) {
                    const fairs = JSON.parse(response);
                    displayFairs(fairs);
                },
                error: function () {
                    alert("Neizdevās ielādēt tirdziņu datus!");
                }
            });
        }
    
        function displayFairs(fairs) {
            let template = "";
        
            fairs.forEach(fair => {
                
                let statusText = "";
                switch (fair.status) {
                    case "upcoming":
                        statusText = "Gaidāms";
                        break;
                    case "late":
                        statusText = "Bijis";
                        break;
                    default:
                        statusText = "Nezināms";
                }
        
                template += `
                    <tr>
                        <td>${fair.id}</td>
                        <td>${fair.name}</td>
                        <td>${fair.description}</td>
                        <td><a href="${fair.link}" target="_blank">${fair.link}</a></td>
                        <td>${statusText}</td>
                        <td>
                            <img src="../${fair.image}" alt="${fair.name}" style="max-width: 100px; height: auto;">
                        </td>
                        <td>
                            <a href="#" class="btn btn-sm btn-warning edit-fair" data-id="${fair.id}" data-toggle="modal" data-target="#addMarketModal">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-danger delete-fair" data-id="${fair.id}" data-toggle="modal" data-target="#deleteModal">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                `;
            });
        
            $('#fairTableBody').html(template);
        }
    
        let isFairEditMode = false; 
    
        
        $(document).on('click', '[data-target="#addMarketModal"]', function (e) {
            const triggerElement = $(e.currentTarget);
            const isEditTrigger = triggerElement.hasClass('edit-fair');
        
            if (isEditTrigger) {
                isFairEditMode = true;
                const fairId = triggerElement.data('id');
        
                $.ajax({
                    url: '../database/fair_get.php',
                    type: 'GET',
                    data: { id: fairId },
                    success: function (response) {
                        const fair = JSON.parse(response);
        
                        if (fair.error) {
                            alert(fair.error);
                            return;
                        }
        
                        $('#addMarketModalLabel').text("Rediģēt Tirdziņu");
                        $('#fairId').val(fair.fair_ID); 
                        $('#marketName').val(fair.name);
                        $('#marketDescription').val(fair.description);
                        $('#marketLink').val(fair.link);
        
                        const dateParts = fair.date.split('-');
                        const formattedDate = `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`;
                        $('#marketDate').val(formattedDate);
        
                        $('#addMarketModal').modal('show');
                    },
                    error: function () {
                        alert("Neizdevās ielādēt tirdziņa datus!");
                    }
                });
            } else {
                isFairEditMode = false;
                $('#addMarketModalLabel').text("Pievienot Tirdziņu");
                $('#marketForm')[0].reset();
                $('#fairId').val("");
                $('#imagePreview').hide().attr('src', ''); 
                $('#currentImagePath').val('');
            }
        });
        
        $(document).on('click', '.edit-fair', function (e) {
            e.preventDefault();
            isFairEditMode = true;
            const fairId = $(this).data('id');
        
            $.ajax({
                url: '../database/fair_get.php',
                type: 'GET',
                data: { id: fairId },
                success: function (response) {
                    const fair = JSON.parse(response);
        
                    if (fair.error) {
                        alert(fair.error);
                        return;
                    }
        
                    
                    $('#fairId').val(fair.fair_ID);
                    $('#marketName').val(fair.name);
                    $('#marketDescription').val(fair.description);
                    $('#marketLink').val(fair.link);
        
                    const dateParts = fair.date.split('-');
                    const formattedDate = `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`;
                    $('#marketDate').val(formattedDate);
        
                    
                    $('#fairCreatedAtText').text(`Izveidots: ${fair.created_at || 'Nav pieejams'}`).show();
                    $('#fairEditedAtText').text(`Pēdējo reizi rediģēts: ${fair.edited || 'Nav rediģēts'}`).show();
        
                    if (fair.image) {
                        const imagePath = fair.image.startsWith('images/') ? `../${fair.image}` : fair.image;
                        $('#imagePreview').attr('src', imagePath).show();
                        $('#currentImagePath').val(fair.image);
                    } else {
                        $('#imagePreview').hide();
                        $('#currentImagePath').val('');
                    }
        
                    $('#addMarketModal').modal('show');
                },
                error: function () {
                    alert("Neizdevās ielādēt tirdziņa datus!");
                }
            });
        });
        
        $('#marketForm').off('submit').on('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    const dateInput = $('#marketDate').val();
    const dateParts = dateInput.split('/');
    const formattedDate = `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`;
    formData.set('date', formattedDate);

    const fairId = $('#fairId').val();
    formData.set('id', fairId);

    const imageFile = $('#marketImage')[0].files[0];
    if (imageFile) {
        formData.set('image', imageFile);
    } else {
        formData.set('current_image', $('#currentImagePath').val());
    }

    $.ajax({
        url: isFairEditMode ? '../database/fair_edit.php' : '../database/fair_add.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            try {
                const result = JSON.parse(response);
                if (result.success) {
                    $('#addMarketModal').modal('hide');
                    $('#marketForm')[0].reset();
                    fetchFairs();

                    // Show success notification
                    if (isFairEditMode) {
                        showAlert('Veiksmīgi rediģēts tirdziņš!', 'success');
                    } else {
                        showAlert('Veiksmīgi pievienots tirdziņš!', 'success');
                    }
                } else {
                    showAlert(result.error || 'Neizdevās saglabāt tirdziņu!', 'danger');
                }
            } catch (e) {
                console.error('Invalid JSON response:', response);
                showAlert('Neizdevās apstrādāt servera atbildi!', 'danger');
            }
        },
        error: function () {
            showAlert('Neizdevās nosūtīt pieprasījumu!', 'danger');
        }
    });
});
    
        
        $(document).on('click', '.delete-fair', function (e) {
    e.preventDefault();
    const fairId = $(this).data('id');

    $('#deleteModal').modal('show');

    $('#confirmDelete').off('click').on('click', function () {
        $.ajax({
            url: '../database/fair_delete.php',
            type: 'POST',
            data: { id: fairId },
            success: function (response) {
                try {
                    const result = JSON.parse(response);
                    if (result.success) {
                        $('#deleteModal').modal('hide');
                        fetchFairs();

                        // Show success notification
                        showAlert('Veiksmīgi dzēsts tirdziņš!', 'success');
                    } else {
                        showAlert(result.error || 'Neizdevās dzēst tirdziņu!', 'danger');
                    }
                } catch (e) {
                    console.error('Invalid JSON response:', response);
                    showAlert('Neizdevās apstrādāt servera atbildi!', 'danger');
                }
            },
            error: function () {
                showAlert('Neizdevās nosūtīt pieprasījumu!', 'danger');
            }
        });
    });
});
    
        $('#fairSearchInput').on('input', function () {
            const searchQuery = $(this).val().trim(); 
        
            
            $.ajax({
                url: '../database/fair_list.php',
                type: 'GET',
                data: { search: searchQuery }, 
                success: function (response) {
                    try {
                        const fairs = JSON.parse(response);
                        displayFairs(fairs); 
                    } catch (e) {
                        alert("Neizdevās apstrādāt tirdziņu datus!");
                    }
                },
                error: function () {
                    alert("Neizdevās ielādēt tirdziņu datus!");
                }
            });
        });
    
        $('#FairStatusFilter').on('change', function () {
            const selectedStatus = $(this).val(); 
        
            
            $.ajax({
                url: '../database/fair_list.php', 
                type: 'GET',
                data: { status: selectedStatus }, 
                success: function (response) {
                    try {
                        const fairs = JSON.parse(response);
                        displayFairs(fairs); 
                    } catch (e) {
                        console.error('Error parsing response:', e);
                        alert("Neizdevās apstrādāt tirdziņu datus!");
                    }
                },
                error: function () {
                    alert("Neizdevās ielādēt tirdziņu datus!");
                }
            });
        });

        $('#marketImage').on('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    $('#imagePreview')
                        .attr('src', e.target.result)
                        .show(); 
                };
                reader.readAsDataURL(file);
            } else {
                $('#imagePreview')
                    .attr('src', '')
                    .hide(); 
            }
        });
    


    

    let isUserEditMode = false; 
    let editingUserId = null; 

    fetchUsers();

    
    function fetchUsers(searchQuery = '') {
        $.ajax({
            url: '../database/user_list.php',
            type: 'GET',
            data: { search: searchQuery }, 
            dataType: 'json', 
            success: function (response) {
                displayUsers(response.users);
            },
            error: function (xhr, status, error) {
                console.error('AJAX error:', status, error); 
                alert('Neizdevās ielādēt lietotāju datus!');
            }
        });
    }

    
    function displayUsers(users) {
        let template = '';
    
        if (users.length === 0) {
            template = '<tr><td colspan="8" class="text-center">Nav pieejamu lietotāju</td></tr>';
        } else {
            users.forEach(user => {
                const addressButton = user.address_id
                    ? `<button class="btn btn-sm btn-info view-address" data-id="${user.address_id}" title="Apskatīt">
                            Apskatīt
                       </button>`
                    : 'Nav norādīts';
    
                template += `
                    <tr>
                        <td>${user.id}</td>
                        <td>${addressButton}</td>
                        <td>${user.name}</td>
                        <td>${user.surname}</td>
                        <td>${user.phone}</td>
                        <td>${user.email}</td>
                        <td>${user.role}</td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-user" data-id="${user.id}" title="Rediģēt">
                                <i class="fas fa-edit"></i> <!-- Edit icon -->
                            </button>
                            <button class="btn btn-sm btn-danger delete-user" data-id="${user.id}" title="Dzēst">
                                <i class="fas fa-trash"></i> <!-- Delete icon -->
                            </button>
                        </td>
                    </tr>
                `;
            });
        }
    
        $('#userTableBody').html(template);
    
        
        $('.view-address').off('click').on('click', function () {
            const addressId = $(this).data('id');
            fetchAddressDetails(addressId);
        });
    }
    
    
    function fetchAddressDetails(addressId) {
        $.ajax({
            url: '../database/address_get.php',
            type: 'GET',
            data: { id: addressId },
            success: function (response) {
                try {
                    const address = JSON.parse(response);
                    if (address.error) {
                        alert(address.error);
                    } else {
                        const addressDetails = `
                            <p><strong>Valsts:</strong> ${address.country}</p>
                            <p><strong>Pilsēta:</strong> ${address.city}</p>
                            <p><strong>Iela:</strong> ${address.street}</p>
                            <p><strong>Mājas numurs:</strong> ${address.house}</p>
                            <p><strong>Dzīvokļa numurs:</strong> ${address.apartment || 'Nav norādīts'}</p>
                            <p><strong>Pasta indekss:</strong> ${address.postal_code}</p>
                        `;
                        $('#addressDetails').html(addressDetails);
                        $('#addressModal').modal('show');
                    }
                } catch (e) {
                    console.error('Invalid JSON response:', response);
                    alert('Neizdevās apstrādāt servera atbildi!');
                }
            },
            error: function () {
                alert('Neizdevās ielādēt adreses datus!');
            }
        });
    }

    $('#roleFilter').on('change', function () {
        const selectedRole = $(this).val(); 
    
        
        fetchUsersByRole(selectedRole);
    });
    
    
    function fetchUsersByRole(role) {
        $.ajax({
            url: '../database/user_list.php',
            type: 'GET',
            data: { role: role }, 
            dataType: 'json', 
            success: function (response) { 
                displayUsers(response.users); 
            },
            error: function (xhr, status, error) {
                console.error('AJAX error:', status, error); 
                alert('Neizdevās ielādēt lietotāju datus!');
            }
        });
    }

    
    $('#addUserButton').click(function () {
        isUserEditMode = false; 
        editingUserId = null; 
        $('#addUserModalLabel').text("Pievienot Lietotāju");
        $('#userForm')[0].reset(); 
        $('#userPassword').prop('required', true); 
        $('#addUserModal').modal('show');
    });

    
    $(document).on('click', '.edit-user', function () {
        isUserEditMode = true;
        editingUserId = $(this).data('id');
    
        $.ajax({
            url: '../database/user_get.php',
            type: 'GET',
            data: { id: editingUserId },
            success: function (response) {
                const user = JSON.parse(response);
    
                if (user.error) {
                    alert(user.error);
                    return;
                }
    
                
                $('#addUserModalLabel').text("Rediģēt Lietotāju");
                $('#userId').val(user.user_ID);
                $('#userFirstName').val(user.name);
                $('#userLastName').val(user.surname);
                $('#userPhone').val(user.phone);
                $('#userEmail').val(user.email);
                $('#userRole').val(user.role);
    
                
                $('#userCreatedAtText').text(`Izveidots: ${user.created_at || 'Nav pieejams'}`).show();
                $('#userEditedAtText').text(`Pēdējo reizi rediģēts: ${user.edited || 'Nav rediģēts'}`).show();
    
                $('#userPassword').prop('required', false);
                $('#addUserModal').modal('show');
            },
            error: function () {
                alert("Neizdevās ielādēt lietotāja datus!");
            }
        });
    });
    
    $('#userForm').submit(function (e) {
    e.preventDefault();

    const formData = {
        id: editingUserId,
        name: $('#userFirstName').val(),
        surname: $('#userLastName').val(),
        phone: $('#userPhone').val(),
        email: $('#userEmail').val(),
        role: $('#userRole').val(),
        password: $('#userPassword').val()
    };

    const url = isUserEditMode
        ? '../database/user_edit.php'
        : '../database/user_add.php';

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        success: function (response) {
            try {
                const result = JSON.parse(response);
                if (result.success) {
                    $('#addUserModal').modal('hide');
                    $('#userForm')[0].reset();
                    fetchUsers();

                    // Show success notification
                    if (isUserEditMode) {
                        showAlert('Veiksmīgi rediģēts lietotājs!', 'success');
                    } else {
                        showAlert('Veiksmīgi pievienots lietotājs!', 'success');
                    }
                } else {
                    showAlert(result.error || 'Neizdevās saglabāt lietotāju!', 'danger');
                }
            } catch (e) {
                console.error('Invalid JSON response:', response);
                showAlert('Neizdevās apstrādāt servera atbildi!', 'danger');
            }
        },
        error: function () {
            showAlert('Neizdevās nosūtīt pieprasījumu!', 'danger');
        }
    });
});

    let deleteUserId = null; 

    
    $(document).on('click', '.delete-user', function (e) {
    e.preventDefault();
    deleteUserId = $(this).data('id');
    $('#deleteModal').modal('show');
});

$('#confirmDelete').off('click').on('click', function () {
    if (deleteUserId) {
        $.ajax({
            url: '../database/user_delete.php',
            type: 'POST',
            data: { id: deleteUserId },
            success: function (response) {
                try {
                    const result = JSON.parse(response);
                    if (result.success) {
                        $('#deleteModal').modal('hide');
                        fetchUsers();

                        // Show success notification
                        showAlert('Veiksmīgi dzēsts lietotājs!', 'success');
                    } else {
                        showAlert(result.error || 'Neizdevās dzēst lietotāju!', 'danger');
                    }
                } catch (e) {
                    console.error('Invalid JSON response:', response);
                    showAlert('Neizdevās apstrādāt servera atbildi!', 'danger');
                }
            },
            error: function () {
                showAlert('Neizdevās nosūtīt pieprasījumu!', 'danger');
            }
        });
    }
});

    
    $('#userSearchInput').on('input', function () {
        const searchQuery = $(this).val().trim();
        fetchUsers(searchQuery);
    });



















    

});

function showAlert(message, type = 'success', duration = 5000) {
    
    const alertId = `alert-${Date.now()}`;

    
    const alertHTML = `
        <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;

    
    $('#alertContainer').append(alertHTML);

    
    setTimeout(() => {
        $(`#${alertId}`).alert('close');
    }, duration);
}

fetchSets();

function fetchSets() {
    $.ajax({
        url: '../database/sets_list.php',
        type: 'GET',
        success: function (response) {
            try {
                const sets = JSON.parse(response);
                displaySets(sets); 
            } catch (e) {
                console.error("Invalid JSON response:", response);
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", xhr.responseText);
        }
    });
}

function displaySets(sets) {
    let template = "";

    sets.forEach(set => {
        template += `
            <div class="col-md-4 mb-4 position-relative">
                <div class="card h-100 shadow-sm set-card" onclick="showSetDetails(${set.set_id})">
                    <img src="../${set.products[0]?.product_image || 'images/default.png'}" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title">${set.set_name}</h5>
                        <p class="card-text">${set.set_description}</p>
                    </div>
                </div>
                <button class="btn btn-danger btn-sm delete-set-btn position-absolute" 
                        style="top: -5px; right: 5px; width: 30px; height: 30px; border-radius: 50%; padding: 0;" 
                        onclick="deleteSet(${set.set_id}, event)">
                    <i class="fas fa-times" style="line-height: 30px;"></i>
                </button>
            </div>
        `;
    });

    if (template === "") {
        template = '<p class="text-center no-sets-message">Nav pieejamu komplektu.</p>';
    }

    $('#setsContainer').html(template);
}

$('#setForm').submit(function (e) {
    e.preventDefault();

    const formData = {
        name: $('#setName').val(),
        description: $('#setDescription').val()
    };


    $.ajax({
        url: '../database/set_add.php',
        type: 'POST',
        data: formData,
        success: function (response) {
            try {
                const result = JSON.parse(response);
                if (result.success) {
                    showAlert('Komplekts veiksmīgi pievienots!', 'success');
                    $('#addSetModal').modal('hide');
                    $('#setForm')[0].reset();
                    fetchSets(); 
                } else {
                    showAlert(result.error || 'Neizdevās pievienot komplektu.', 'danger');
                }
            } catch (e) {
                showAlert("Neizdevās apstrādāt servera atbildi!", "danger");
            }
        },
        error: function () {
            showAlert("Neizdevās nosūtīt pieprasījumu!", "danger");
        }
    });
});


function searchSets() {
    const searchQuery = $('#searchInput').val().trim(); 

    $.ajax({
        url: `../database/sets_list.php?search=${encodeURIComponent(searchQuery)}`,
        type: 'GET',
        success: function (response) {
            try {
                const sets = JSON.parse(response);
                displaySets(sets); 
            } catch (e) {
            }
        },
        error: function (xhr, status, error) {
        }
    });
}

$('#searchInput').on('input', function () {
    searchSets();
});




function showSetDetails(setId) {
    $('#setDetailsModal').data('set-id', setId); 
    $.ajax({
        url: '../database/sets_list.php',
        type: 'GET',
        success: function (response) {
            const sets = JSON.parse(response);
            const set = sets.find(s => s.set_id == setId);

            if (!set) {
                showAlert("Komplekts nav atrasts!", "danger");
                return;
            }

            
            $('#modalSetName').text(set.set_name);
            $('#modalSetTitle').text(set.set_name);
            $('#modalSetDescription').text(set.set_description);

            
            let productTemplate = '';
            set.products.forEach(product => {
                productTemplate += `
                    <div class="col-md-4 position-relative">
                        <div class="product-display">
                            <img src="../${product.product_image}" class="img-fluid" alt="${product.product_name}">
                            <p class="mt-2">ID: ${product.product_id}</p>
                            <p>Produkta nosaukums: ${product.product_name}</p>
                        </div>
                        <button class="btn btn-danger btn-sm delete-product-btn position-absolute" 
                                style="top: 5px; right: 5px; width: 30px; height: 30px; border-radius: 50%; padding: 0;" 
                                onclick="deleteProductFromSet(${product.product_id}, ${setId})">
                            <i class="fas fa-times" style="line-height: 30px;"></i>
                        </button>
                    </div>
                `;
            });

            
            if (productTemplate === '' && set.products.length === 0) {
                productTemplate = '<p class="text-center no-sets-message">Šim komplektam nav pievienotu produktu.</p>';
            }

            $('#modalProductsContainer').html(productTemplate);

            
            fetchCategories();

            
            $('#setDetailsModal').modal('show');
        },
        error: function () {
            showAlert("Neizdevās ielādēt komplekta detaļas!", "danger");
        }
    });
}

function deleteProductFromSet(productId, setId) {

    $.ajax({
        url: '../database/product_set_delete.php', 
        type: 'POST',
        data: { id_product: productId, id_set: setId },
        success: function (response) {
            try {
                const result = JSON.parse(response);
                if (result.success) {
                    
                    showSetDetails(setId);
                } else {
                }
            } catch (e) {
                console.error("Invalid JSON response:", response);
                showAlert("Neizdevās apstrādāt servera atbildi!", "danger");
            }
        },
        error: function () {
            showAlert("Neizdevās nosūtīt pieprasījumu!", "danger");
        }
    });
}

function fetchCategories() {
    $.ajax({
        url: '../database/category_list.php',
        type: 'GET',
        success: function (response) {
            const categories = JSON.parse(response);
            displayCategories(categories);
        },
        error: function () {
            alert("Neizdevās ielādēt kategorijas!");
        }
    });
}

function displayCategories(categories) {
    const groupedCategories = {
        "Bērniem": [],
        "Sievietēm": [],
        "Vīriešiem": []
    };

    
    categories.forEach(category => {
        if (groupedCategories[category.big_category]) {
            groupedCategories[category.big_category].push(category);
        }
    });

    let template = "";

    Object.keys(groupedCategories).forEach(bigCategory => {
        const categories = groupedCategories[bigCategory];
        const categoryId = bigCategory.replace(/\s+/g, '-').toLowerCase(); 

        template += `
            <div class="category-block mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5>${bigCategory}</h5>
                    <button class="btn btn-sm btn-outline-primary" onclick="toggleCategory('${categoryId}')">></button>
                </div>
                <div id="${categoryId}" class="subcategory-list mt-2" style="display: none;">
                    ${categories.map(category => {
                        const subCategoryId = `${categoryId}-${category.name.replace(/\s+/g, '-').toLowerCase()}`;
                        return `
                            <div class="subcategory-block mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>${category.name}</span>
                                    <button class="btn btn-sm btn-outline-primary" onclick="toggleSubCategory('${bigCategory}', '${subCategoryId}')">></button>
                                </div>
                                <div id="${subCategoryId}" class="product-list mt-2" style="display: none;" data-subcategory="${category.name}">
                                    <!-- Products will be dynamically loaded here -->
                                </div>
                            </div>
                        `;
                    }).join('')}
                </div>
            </div>
        `;
    });

    $('#categoriesContainer').html(template);
}

function addProductToSet(productId, setId) {
    $.ajax({
        url: '../database/product_set_add.php',
        type: 'POST',
        data: { id_product: productId, id_set: setId },
        success: function (response) {
            try {
                const result = JSON.parse(response);
                if (result.success) {
                    alert('Produkts veiksmīgi pievienots komplektam!');
                    updateModalProductsContainer(productId); 
                } else {
                    alert('Neizdevās pievienot produktu komplektam: ' + result.error);
                }
            } catch (e) {
                alert("Neizdevās apstrādāt servera atbildi!");
            }
        },
        error: function (xhr, status, error) {
            alert("Neizdevās pievienot produktu komplektam!");
        }
    });
}

function updateModalProductsContainer(productId) {
    $.ajax({
        url: '../database/product_list.php',
        type: 'GET',
        success: function (response) {
            try {
                const products = JSON.parse(response);
                const product = products.find(p => p.id == productId);
                if (product) {

                    
                    $('#modalProductsContainer p.text-center').remove();

                    const productHTML = `
                        <div class="col-md-4">
                            <div class="product-display">
                                <img src="../${product.product_image}" class="img-fluid" alt="${product.name}">
                                <p class="mt-2">ID: ${product.id}</p>
                                <p>Produkta nosaukums: ${product.name}</p>
                            </div>
                        </div>
                    `;
                    $('#modalProductsContainer').append(productHTML);
                } else {
                }
            } catch (e) {
                alert("Neizdevās apstrādāt servera atbildi!");
            }
        },
        error: function (xhr, status, error) {
            alert("Neizdevās ielādēt produktu!");
        }
    });
}

function toggleCategory(id) {
    const elem = document.getElementById(id);
    elem.style.display = elem.style.display === 'none' ? 'block' : 'none';
}

function fetchProductsByCategory(bigCategory, subCategory, setId) {
    $.ajax({
        url: '../database/product_list.php',
        type: 'GET',
        success: function (response) {
            const products = JSON.parse(response);

            // Fetch the products already in the set
            const setProducts = $('#modalProductsContainer .product-display').map(function () {
                return $(this).find('p:first').text().replace('ID: ', '').trim(); // Extract product ID
            }).get();

            // Filter products that are not already in the set
            const filteredProducts = products.filter(product => 
                product.big_category === bigCategory &&
                product.category_name === subCategory &&
                !setProducts.includes(product.id.toString())
            );

            let productTemplate = '';
            filteredProducts.forEach(product => {
                productTemplate += `
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center">
                            <img src="../${product.product_image}" alt="${product.name}" style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                            <span>${product.name}</span>
                        </div>
                        <button class="btn btn-sm btn-outline-success add-to-set" data-product-id="${product.id}">+</button>
                    </div>
                `;
            });

            if (productTemplate === '') {
                productTemplate = '<p class="text-center">Nav pieejamu produktu šajā kategorijā.</p>';
            }

            const sanitizedId = `${bigCategory.replace(/\s+/g, '-').toLowerCase()}-${subCategory.replace(/\s+/g, '-').toLowerCase()}`;
            $(`#${sanitizedId}`).html(productTemplate).show();

            // Add click event for adding products to the set
            $(`#${sanitizedId} .add-to-set`).off('click').on('click', function () {
                const productId = $(this).data('product-id');
                addProductToSet(productId, setId);
            });
        },
        error: function (xhr, status, error) {
            alert("Neizdevās ielādēt produktus!");
        }
    });
}

function toggleSubCategory(bigCategory, subCategoryId) {
    const elem = document.getElementById(subCategoryId);
    if (elem.style.display === 'none') {
        elem.style.display = 'block';
        const subCategoryName = $(`#${subCategoryId}`).data('subcategory');
        const setId = $('#setDetailsModal').data('set-id'); // Get the current set ID
        fetchProductsByCategory(bigCategory, subCategoryName, setId);
    } else {
        elem.style.display = 'none';
    }
}

function deleteSet(setId, event) {
    event.stopPropagation(); 

    
    $('#deleteModal').modal('show');

    
    $('#confirmDelete').off('click').on('click', function () {
        $(this).blur(); 
        $.ajax({
            url: '../database/set_delete.php',
            type: 'POST',
            data: { id: setId },
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    showAlert("Komplekts veiksmīgi dzēsts!", "success");
                    $('#deleteModal').modal('hide'); 
                    fetchSets(); 
                } else {
                    showAlert(result.error || "Neizdevās dzēst komplektu.", "danger");
                }
            },
            error: function () {
                showAlert("Neizdevās nosūtīt pieprasījumu!", "danger");
            }
        });
    });
}