$(document).ready(function () {
    let isEditMode = false; // Track whether the modal is in edit mode

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

        $('#category').html(template);
    }

    // Open modal for adding a category
    $('#addCategoryButton').click(function () {
        isEditMode = false; // Set to add mode
        $('#categoryModalLabel').text("Pievienot kategoriju");
        $('#categoryForm')[0].reset(); // Reset the form
        $('#categoryId').val(""); // Clear the hidden ID field
        $('#categoryModal').modal('show');
    });

    // Open modal for editing a category
    $(document).on('click', '.category-edit', function (e) {
        e.preventDefault();
        isEditMode = true; // Set to edit mode

        const row = $(e.currentTarget).closest('tr');
        const categoryId = row.attr("category_ID");
        const categoryName = row.find('td:nth-child(3)').text();
        const bigCategory = row.find('td:nth-child(2)').text();

        $('#categoryModalLabel').text("Rediģēt kategoriju");
        $('#categoryId').val(categoryId); // Set the hidden ID field
        $('#categoryName').val(categoryName);
        $('#bigCategory').val(bigCategory);
        $('#categoryModal').modal('show');
    });

    // Handle form submission for adding or editing a category
    $('#categoryForm').submit(function (e) {
        e.preventDefault();
    
        const formData = {
            id: $('#categoryId').val(),
            name: $('#categoryName').val(),
            big_category: $('#bigCategory').val()
        };
    
        const url = isEditMode
            ? '../database/category_edit.php' // URL for editing
            : '../database/category_add.php'; // URL for adding
    
        console.log("Submitting data:", formData); // Debug: Log the data being sent
    
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
                console.log("Response from server:", response); // Debug: Log the server response
                $('#categoryModal').modal('hide');
                $('#categoryForm')[0].reset();
                fetchCategories();
            },
            error: function () {
                alert(isEditMode ? "Neizdevās rediģēt kategoriju!" : "Neizdevās pievienot kategoriju!");
            }
        });
    });

    $(document).on('click', '.category-delete', function (e) {
        e.preventDefault();
        deleteCategoryId = $(e.currentTarget).closest('tr').attr("category_ID"); // Get the category ID
        $('#deleteModal').modal('show'); // Show the Bootstrap modal
    });

    $('#confirmDelete').click(function () {
        if (deleteCategoryId) {
            $.post('../database/category_delete.php', { id: deleteCategoryId }, function (response) {
                console.log("Response from server:", response); // Debug: Log the server response
                $('#deleteModal').modal('hide'); // Hide the modal after deletion
                fetchCategories(); // Refresh the category list after deletion
            });
        }
    });








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
                    <td>${product.category_id}</td>
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

    let isProductEditMode = false; // Track whether the modal is in edit mode

    // Open modal for adding a product
    $('#addProductModal').on('hidden.bs.modal', function () {
        console.log("Resetting modal state");
        $('#productForm')[0].reset(); // Reset the form
        $('#addProductModalLabel').text("Pievienot preci"); // Reset the title
        $('#productId').val(""); // Clear the hidden product ID field
        populateCategoryDropdown(); // Reset the dropdown
        isProductEditMode = false; // Reset the mode
    });

    // Open modal for editing a product
    $(document).on('click', '.product-edit', function (e) {
        e.preventDefault();
        isProductEditMode = true; // Set to edit mode
        console.log("Opening Edit Product modal");
    
        const productId = $(this).data('id'); // Get the product ID from the button's data attribute
    
        // Fetch product details using product_get.php
        $.ajax({
            url: '../database/product_get.php',
            type: 'GET',
            data: { id: productId },
            success: function (response) {
                const product = JSON.parse(response);
    
                if (product.error) {
                    alert(product.error); // Show error if product not found
                    return;
                }
    
                // Populate the modal fields with the product details
                $('#addProductModalLabel').text("Rediģēt preci"); // Set modal title to "Edit Product"
                $('#productId').val(product.product_ID); // Hidden input for product ID
                $('#productName').val(product.name);
                $('#shortDescription').val(product.short_description);
                $('#longDescription').val(product.long_description || ''); // Optional field
                $('#material').val(product.material || '');
                $('#size').val(product.size || '');
                $('#color').val(product.color || '');
                $('#care').val(product.care || '');
                $('#price').val(product.price);
                $('#quantity').val(product.stock_quantity);
    
                // Populate the category dropdown and set the selected value
                populateCategoryDropdown(product.ID_category);
    
                // Show the current image in the preview
                if (product.image) {
                    $('#imagePreview').attr('src', product.image).show(); // Set the image source and make it visible
                } else {
                    $('#imagePreview').hide(); // Hide the preview if no image exists
                }
    
                // Show the modal
                $('#addProductModal').modal('show');
            },
            error: function () {
                alert("Neizdevās ielādēt preces datus!");
            }
        });
    });

    // Reset modal state when it is hidden
    $('#addProductButton').click(function () {
        isProductEditMode = false; // Set to add mode
        console.log("Opening Add Product modal");
        $('#addProductModalLabel').text("Pievienot preci"); // Set modal title to "Add Product"
        $('#productForm')[0].reset(); // Reset the form
        $('#productId').val(""); // Clear the hidden product ID field
        populateCategoryDropdown(); // Populate the dropdown without a selected value
        $('#addProductModal').modal('show'); // Show the modal
    });

    function populateCategoryDropdown(selectedCategoryId = null) {
        console.log("Populating category dropdown. Selected category ID:", selectedCategoryId); // Debugging
    
        $.ajax({
            url: '../database/category_list.php', // Use the existing category_list.php
            type: 'GET',
            success: function (response) {
                const categories = JSON.parse(response);
                const categoryDropdown = $('#category');
                categoryDropdown.empty(); // Clear existing options
    
                // Add a default option
                if (selectedCategoryId === null) {
                    // For adding a product, show "Izvēlieties kategoriju"
                    categoryDropdown.append('<option value="" selected>Izvēlieties kategoriju</option>');
                } else {
                    // For editing a product, allow no default selection
                    categoryDropdown.append('<option value="">Izvēlieties kategoriju</option>');
                }
    
                // Populate the dropdown with category names
                categories.forEach(category => {
                    const isSelected = selectedCategoryId == category.id ? 'selected' : ''; // Check if this is the selected category
                    console.log(`Category ID: ${category.id}, Name: ${category.name}, Selected: ${isSelected}`); // Debugging
                    categoryDropdown.append(
                        `<option value="${category.id}" ${isSelected}>${category.name}</option>`
                    );
                });
            },
            error: function () {
                alert("Neizdevās ielādēt kategorijas!");
            }
        });
    }

    $('#productForm').submit(function (e) {
        e.preventDefault(); // Prevent default form submission
    
        const formData = new FormData();
        formData.append('id', $('#productId').val()); // Include the product ID
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
    
        // Add the image file to the form data
        const imageFile = $('#image')[0].files[0];
        if (imageFile) {
            formData.append('image', imageFile);
        }
    
        const url = isProductEditMode
            ? '../database/product_edit.php' // URL for editing
            : '../database/product_add.php'; // URL for adding
    
        console.log("Submitting data:", formData); // Debugging: Log the data being sent
    
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false, // Prevent jQuery from automatically transforming the data
            contentType: false, // Prevent jQuery from setting the content type
            success: function (response) {
                console.log("Response from server:", response); // Debugging: Log the server response
                const result = JSON.parse(response);
    
                if (result.success) {
                    $('#addProductModal').modal('hide');
                    $('#productForm')[0].reset();
                    fetchProducts(); // Refresh the product list
                } else {
                    alert(result.error || "Neizdevās saglabāt preci!");
                }
            },
            error: function () {
                alert("Neizdevās nosūtīt pieprasījumu!");
            }
        });
    });


    $(document).on('click', '.product-delete', function (e) {
        e.preventDefault();
        const deleteProductId = $(e.currentTarget).closest('tr').find('.product-edit').data('id'); // Get the product ID
        $('#deleteModal').modal('show'); // Show the Bootstrap modal
    
        // Confirm delete action
        $('#confirmDelete').off('click').on('click', function () {
            if (deleteProductId) {
                $.post('../database/product_delete.php', { id: deleteProductId }, function (response) {
                    console.log("Response from server:", response); // Debug: Log the server response
                    $('#deleteModal').modal('hide'); // Hide the modal after deletion
                    fetchProducts(); // Refresh the product list after deletion
                }).fail(function () {
                    alert("Neizdevās dzēst preci!"); // Show error if the request fails
                });
            }
        });
    });












        function fetchGallery() {
            $.ajax({
                url: '../database/gallery_list.php',
                type: 'GET',
                success: function (response) {
                    const gallery = JSON.parse(response);
                    displayGallery(gallery);
                },
                error: function () {
                    alert("Neizdevās ielādēt galerijas datus!");
                }
            });
        }
    
        function displayGallery(gallery) {
            let template = "";
    
            gallery.forEach(item => {
                let statusText = "";
                switch (item.status) {
                    case 'approved':
                        statusText = 'Apstiprināts';
                        break;
                    case 'declined':
                        statusText = 'Noraidīts';
                        break;
                    case 'onhold':
                        statusText = 'Gaida apstiprinājumu';
                        break;
                    default:
                        statusText = 'Nezināms';
                }
    
                // Construct the action buttons based on the status
                let actionButtons = "";
                if (item.status === 'onhold') {
                    actionButtons = `
                        <a href="#" class="btn btn-sm btn-success approve-image" data-id="${item.id}">
                            <i class="fas fa-check"></i> <!-- Accept icon -->
                        </a>
                        <a href="#" class="btn btn-sm btn-danger decline-image" data-id="${item.id}">
                            <i class="fas fa-times"></i> <!-- Decline icon -->
                        </a>
                    `;
                }
    
                template += `
                    <tr>
                        <td>${item.id}</td>
                        <td><img src="../${item.image_path}" alt="Galerijas bilde" style="max-width: 100px; height: auto;"></td>
                        <td>${item.posted_by}</td>
                        <td>${statusText}</td>
                        <td>${actionButtons}</td>
                    </tr>
                `;
            });
    
            $('#gallery').html(template); // Update the table body
        }
    
        // Fetch the gallery on page load
        fetchGallery();
    
        // Approve image
        $(document).on('click', '.approve-image', function (e) {
            e.preventDefault();
            const imageId = $(this).data('id');
    
            $.ajax({
                url: '../database/gallery_update_status.php',
                type: 'POST',
                data: { id: imageId, status: 'approved' },
                success: function (response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        fetchGallery(); // Refresh the gallery
                    } else {
                        alert(result.error);
                    }
                },
                error: function () {
                    alert("Neizdevās apstiprināt bildi!");
                }
            });
        });
    
        // Decline image
        $(document).on('click', '.decline-image', function (e) {
            e.preventDefault();
            const imageId = $(this).data('id');
    
            $.ajax({
                url: '../database/gallery_update_status.php',
                type: 'POST',
                data: { id: imageId, status: 'declined' },
                success: function (response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        fetchGallery(); // Refresh the gallery
                    } else {
                        alert(result.error);
                    }
                },
                error: function () {
                    alert("Neizdevās noraidīt bildi!");
                }
            });
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
            template += `
                <tr>
                    <td>${fair.id}</td>
                    <td>${fair.name}</td>
                    <td>${fair.description}</td>
                    <td><a href="${fair.link}" target="_blank">${fair.link}</a></td>
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

    let isFairEditMode = false; // Track whether the modal is in edit mode

    // Open modal for adding a fair
    $(document).on('click', '[data-target="#addMarketModal"]', function (e) {
        const triggerElement = $(e.currentTarget); // Get the element that triggered the modal
        const isEditTrigger = triggerElement.hasClass('edit-fair'); // Check if it's the edit button

        if (isEditTrigger) {
            isFairEditMode = true; // Set to edit mode
            const fairId = triggerElement.data('id'); // Get the fair ID from the button

            // Fetch fair details for editing
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

                    // Populate the modal fields with the fair details
                    $('#addMarketModalLabel').text("Rediģēt Tirdziņu");
                    $('#fairId').val(fair.fair_ID);
                    $('#marketName').val(fair.name);
                    $('#marketDescription').val(fair.description);
                    $('#marketLink').val(fair.link);

                    // Show the modal
                    $('#addMarketModal').modal('show');
                },
                error: function () {
                    alert("Neizdevās ielādēt tirdziņa datus!");
                }
            });
        } else {
            isFairEditMode = false; // Set to add mode
            $('#addMarketModalLabel').text("Pievienot Tirdziņu");
            $('#marketForm')[0].reset(); // Reset the form
            $('#fairId').val(""); // Clear the hidden ID field
        }
    });

    // Handle form submission for adding or editing a fair
    $('#marketForm').submit(function (e) {
        e.preventDefault(); // Prevent default form submission
    
        const formData = new FormData(this);
        const url = isFairEditMode
            ? '../database/fair_edit.php' // URL for editing
            : '../database/fair_add.php'; // URL for adding
    
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false, // Prevent jQuery from automatically transforming the data
            contentType: false, // Prevent jQuery from setting the content type
            success: function (response) {
                const result = JSON.parse(response);
    
                if (result.success) {
                    $('#addMarketModal').modal('hide');
                    $('#marketForm')[0].reset();
                    fetchFairs(); // Refresh the fair list
                } else {
                    alert(result.error || "Neizdevās saglabāt tirdziņu!");
                }
            },
            error: function () {
                alert("Neizdevās nosūtīt pieprasījumu!");
            }
        });
    });

    // Fetch and display fairs on page load
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

    
    $(document).on('click', '.delete-fair', function (e) {
        e.preventDefault();
        const fairId = $(this).data('id'); // Get the fair ID from the button's data attribute
    
        // Show the delete modal
        $('#deleteModal').modal('show');
    
        // Set the confirm delete button to handle the deletion
        $('#confirmDelete').off('click').on('click', function () {
            $.ajax({
                url: '../database/fair_delete.php',
                type: 'POST',
                data: { id: fairId },
                success: function (response) {
                    const result = JSON.parse(response);
    
                    if (result.success) {
                        $('#deleteModal').modal('hide'); // Hide the modal after deletion
                        fetchFairs(); // Refresh the fair list
                    } else {
                        alert(result.error || "Neizdevās dzēst tirdziņu!");
                    }
                },
                error: function () {
                    alert("Neizdevās nosūtīt pieprasījumu!");
                }
            });
        });
    });


    


});

