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
    
        if (categories.length === 0) {
            // Display message if no categories are found
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
    
        // Update the category table body
        $('#categoryTableBody').html(template);
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

    function searchCategories() {
        const searchQuery = $('#searchInput').val().trim(); // Get the search input value
        const selectedFilter = $('#categoryFilter').val(); // Get the selected filter value
        console.log(`Searching categories with query: ${searchQuery} and filter: ${selectedFilter}`); // Debug log
    
        $.ajax({
            url: '../database/category_list.php',
            type: 'GET',
            data: {
                search: searchQuery,
                filter: selectedFilter === 'Visi' ? '' : selectedFilter // Send the filter value only if it's not "Visi"
            },
            success: function (response) {
                try {
                    const categories = JSON.parse(response);
                    console.log("Search results:", categories); // Debug log
                    displayCategories(categories); // Update the category table
                } catch (e) {
                    console.error("Invalid JSON response:", response); // Debug log
                    alert("Neizdevās apstrādāt kategoriju datus!");
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", xhr.responseText); // Debug log
                alert("Neizdevās ielādēt kategorijas!");
            }
        });
    }
    
    // Trigger search on input
    $('#searchInput').on('input', function () {
        searchCategories();
    });

    $('#categoryFilter').on('change', function () {
        const selectedFilter = $(this).val(); // Get the selected filter value
        console.log(`Filtering categories by: ${selectedFilter}`); // Debug log
    
        $.ajax({
            url: '../database/category_list.php',
            type: 'GET',
            data: { filter: selectedFilter === 'Visi' ? '' : selectedFilter }, // Send the filter value to the server
            success: function (response) {
                try {
                    const categories = JSON.parse(response);
                    console.log("Filtered categories:", categories); // Debug log
                    displayCategories(categories); // Update the category table
                } catch (e) {
                    console.error("Invalid JSON response:", response); // Debug log
                    alert("Neizdevās apstrādāt kategoriju datus!");
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", xhr.responseText); // Debug log
                alert("Neizdevās ielādēt kategorijas!");
            }
        });
    });

    // Trigger search when the filter changes
    $('#categoryFilter').on('change', function () {
        searchCategories();
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
        const searchQuery = $('#searchInput').val().trim(); // Get the search input value
        const selectedCategory = $('#filterCategory').val(); // Get the selected category value
        const selectedSort = $('#sortOptions').val(); // Get the selected sorting option
    
        console.log(`Searching products with query: ${searchQuery}, category: ${selectedCategory}, and sort: ${selectedSort}`); // Debug log
    
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
                    console.log("Search results:", products); // Debug log
                    displayProducts(products); // Update the product table
                } catch (e) {
                    console.error("Invalid JSON response:", response); // Debug log
                    alert("Neizdevās apstrādāt preču datus!");
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", xhr.responseText); // Debug log
                alert("Neizdevās ielādēt preces!");
            }
        });
    }
    
    // Trigger search on input
    $('#searchInput').on('input', function () {
        searchProducts();
    });
   
    function populateCategoryDropdown(selectedCategoryId = null, dropdownId = 'category') {
        return new Promise((resolve, reject) => {
            console.log("Fetching categories..."); // Debug log
            const categoryDropdown = $(`#${dropdownId}`);
            categoryDropdown.empty(); // Clear existing options
    
            // Add the default "Visas kategorijas" option for filters
            if (dropdownId === 'filterCategory') {
                categoryDropdown.append('<option value="">Visas kategorijas</option>');
            } else {
                categoryDropdown.append('<option value="" selected>Izvēlies kategoriju</option>');
            }
    
            $.ajax({
                url: '../database/category_list.php',
                type: 'GET',
                success: function (response) {
                    console.log("Server response for categories:", response); // Debug log
                    try {
                        const categories = JSON.parse(response);
    
                        // Populate the dropdown with category names
                        categories.forEach(category => {
                            const isSelected = selectedCategoryId == category.id ? 'selected' : ''; // Check if this is the selected category
                            categoryDropdown.append(
                                `<option value="${category.id}" ${isSelected}>${category.name}</option>`
                            );
                        });
    
                        console.log("Dropdown content after population:", categoryDropdown.html()); // Debug log
                        resolve(); // Resolve the promise after the dropdown is populated
                    } catch (e) {
                        console.error("Error parsing category data:", e); // Debug log
                        reject();
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching categories:", xhr.responseText); // Debug log
                    reject();
                }
            });
        });
    }

    $('#filterCategory').on('change', function () {
        const selectedCategory = $(this).val(); // Get the selected category ID
        console.log(`Filtering products by category: ${selectedCategory}`); // Debug log
    
        // Fetch filtered products
        $.ajax({
            url: '../database/product_list.php',
            type: 'GET',
            data: { category: selectedCategory },
            success: function (response) {
                try {
                    const products = JSON.parse(response);
                    console.log("Filtered products:", products); // Debug log
                    displayProducts(products); // Update the product table
                } catch (e) {
                    console.error("Error parsing product data:", e); // Debug log
                    alert("Neizdevās apstrādāt preču datus!");
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching filtered products:", xhr.responseText); // Debug log
                alert("Neizdevās ielādēt preces!");
            }
        });
    });

    function populateCategoryDropdownWithBigCategory(dropdownId = 'filterCategory') {
        return new Promise((resolve, reject) => {
            console.log("Fetching categories with big_category..."); // Debug log
            const categoryDropdown = $(`#${dropdownId}`);
            categoryDropdown.empty(); // Clear existing options
    
            // Add the default "Visas kategorijas" option
            categoryDropdown.append('<option value="">Visas kategorijas</option>');
    
            $.ajax({
                url: '../database/category_list.php',
                type: 'GET',
                success: function (response) {
                    console.log("Server response for categories:", response); // Debug log
                    try {
                        const categories = JSON.parse(response);
    
                        // Populate the dropdown with category names and big categories
                        categories.forEach(category => {
                            categoryDropdown.append(
                                `<option value="${category.id}">${category.name} ${category.big_category}</option>`
                            );
                        });
    
                        console.log("Dropdown content after population:", categoryDropdown.html()); // Debug log
                        resolve(); // Resolve the promise after the dropdown is populated
                    } catch (e) {
                        console.error("Error parsing category data:", e); // Debug log
                        reject();
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching categories:", xhr.responseText); // Debug log
                    reject();
                }
            });
        });
    }

    populateCategoryDropdownWithBigCategory('filterCategory').then(() => {
        console.log("Filter category dropdown with big_category populated successfully.");
    }).catch(() => {
        alert("Neizdevās ielādēt kategorijas filtram!");
    });

    $('#sortOptions').on('change', function () {
        const selectedSort = $(this).val(); // Get the selected sorting option
        console.log(`Sorting products by: ${selectedSort}`); // Debug log
    
        // Fetch sorted products
        $.ajax({
            url: '../database/product_list.php',
            type: 'GET',
            data: { sort: selectedSort },
            success: function (response) {
                try {
                    const products = JSON.parse(response);
                    console.log("Sorted products:", products); // Debug log
                    displayProducts(products); // Update the product table
                } catch (e) {
                    console.error("Error parsing product data:", e); // Debug log
                    alert("Neizdevās apstrādāt preču datus!");
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching sorted products:", xhr.responseText); // Debug log
                alert("Neizdevās ielādēt preces!");
            }
        });
    });

    // Function to fetch and display filtered and sorted products
    function fetchFilteredAndSortedProducts() {
        const selectedCategory = $('#filterCategory').val(); // Get the selected category ID
        const selectedSort = $('#sortOptions').val(); // Get the selected sorting option

        console.log(`Filtering by category: ${selectedCategory}, Sorting by: ${selectedSort}`); // Debug log

        // Fetch filtered and sorted products
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
                    console.log("Filtered and sorted products:", products); // Debug log
                    displayProducts(products); // Update the product table
                } catch (e) {
                    console.error("Error parsing product data:", e); // Debug log
                    alert("Neizdevās apstrādāt preču datus!");
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching filtered and sorted products:", xhr.responseText); // Debug log
                alert("Neizdevās ielādēt preces!");
            }
        });
    }

    // Trigger filtering and sorting when the category dropdown changes
    $('#filterCategory').on('change', function () {
        fetchFilteredAndSortedProducts();
    });

    // Trigger filtering and sorting when the sort options dropdown changes
    $('#sortOptions').on('change', function () {
        fetchFilteredAndSortedProducts();
    });

    

    $('#image').on('change', function () {
        const file = this.files[0]; // Get the selected file
        if (file) {
            const reader = new FileReader(); // Create a FileReader to read the file
            reader.onload = function (e) {
                $('#imagePreview') // Update the src of the image preview
                    .attr('src', e.target.result) // Set the preview to the file content
                    .show(); // Make the preview visible
                $('#imagePath').val(''); // Clear the current image path only if a new file is selected
            };
            reader.readAsDataURL(file); // Read the file as a data URL
        } else {
            $('#imagePreview') // If no file is selected, hide the preview
                .attr('src', '')
                .hide();
            // Do not clear the current image path here
        }
    });

    

    let isProductEditMode = false; // Track whether the modal is in edit mode

    // Open modal for adding a product
    $('#addProductButton').click(function () {
        isProductEditMode = false; // Ensure the modal is in add mode
        console.log("Opening Add Product modal");
    
        // Reset the form and clear all fields
        $('#productForm')[0].reset(); // Reset the form
        $('#productId').val(""); // Clear the hidden product ID field
        $('#imagePreview').hide(); // Hide the image preview
        $('#imagePreview').attr('src', ''); // Clear the image preview source
    
        // Set the modal title to "Pievienot preci"
        $('#addProductModalLabel').text("Pievienot preci");
    
        // Populate the category dropdown and show the modal only after it's populated
        populateCategoryDropdown().then(() => {
            console.log("Category dropdown populated successfully for Add Product.");
            $('#addProductModal').modal('show'); // Show the modal
        }).catch(() => {
            alert("Neizdevās ielādēt kategorijas!"); // Handle errors
        });
    });

    $('#addProductModal').on('hidden.bs.modal', function () {
        console.log("Resetting modal state");
        isProductEditMode = false; // Reset the edit mode flag
        $('#productForm')[0].reset(); // Reset the form
        $('#productId').val(""); // Clear the hidden product ID field
        $('#imagePreview').hide(); // Hide the image preview
        $('#imagePreview').attr('src', ''); // Clear the image preview source
    
        // Reset the modal title to the default "Pievienot preci"
        $('#addProductModalLabel').text("Pievienot preci");
    
        // Remove focus from any element inside the modal
        $('#addProductModal').find(':focus').blur();
    });


    // Open modal for editing a product
    $(document).on('click', '.product-edit', function (e) {
        e.preventDefault();
        isProductEditMode = true; // Set edit mode to true
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
                $('#productId').val(product.product_ID); // Hidden input for product ID
                $('#productName').val(product.name);
                $('#shortDescription').val(product.short_description);
                $('#longDescription').val(product.long_description || '');
                $('#material').val(product.material || '');
                $('#size').val(product.size || '');
                $('#color').val(product.color || '');
                $('#care').val(product.care || '');
                $('#price').val(product.price);
                $('#quantity').val(product.stock_quantity);
                $('#imagePath').val(product.image || ''); // Set the current image path in a hidden input
    
                // Populate the category dropdown and set the selected value
                populateCategoryDropdown(product.ID_category).then(() => {
                    console.log("Category dropdown populated successfully for Edit Product.");
                    $('#addProductModalLabel').text("Rediģēt preci"); // Set the modal title
                    $('#addProductModal').modal('show'); // Show the modal
                });
    
                if (product.image) {
                    const imagePath = product.image.startsWith('images/') ? `../${product.image}` : product.image;
                    $('#imagePreview').attr('src', imagePath).show();
                } else {
                    $('#imagePreview').hide();
                }
            },
            error: function () {
                alert("Neizdevās ielādēt preces datus!");
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
            formData.append('current_image', $('#imagePath').val()); // Append the current image path
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
                try {
                    const result = typeof response === 'string' ? JSON.parse(response) : response;
    
                    if (result.success) {
                        $('#addProductModal').modal('hide'); // Hide the modal
                        $('#productForm')[0].reset(); // Reset the form
                        fetchProducts(); // Refresh the product list
                    } else {
                        alert(result.error || "Neizdevās saglabāt preci!");
                    }
                } catch (e) {
                    console.error("Invalid JSON response:", response);
                    alert("Neizdevās apstrādāt servera atbildi!");
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
                        <td><img src="../${item.image_path}" style="max-width: 100px; height: auto;"></td>
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

function showAlert(message, type = 'success', duration = 5000) {
    // Create a unique ID for the alert
    const alertId = `alert-${Date.now()}`;

    // Define the alert HTML
    const alertHTML = `
        <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;

    // Append the alert to the alert container
    $('#alertContainer').append(alertHTML);

    // Automatically remove the alert after the specified duration
    setTimeout(() => {
        $(`#${alertId}`).alert('close');
    }, duration);
}

fetchSets();

function fetchSets() {
    console.log("Fetching sets..."); // Debug: Check if this function is called
    $.ajax({
        url: '../database/sets_list.php',
        type: 'GET',
        success: function (response) {
            try {
                const sets = JSON.parse(response);
                console.log("Sets fetched:", sets); // Debug: Log the fetched sets
                displaySets(sets); // Update the UI with the fetched sets
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

    console.log("Updating sets container..."); // Debug log
    $('#setsContainer').html(template);
}

$('#setForm').submit(function (e) {
    e.preventDefault();

    const formData = {
        name: $('#setName').val(),
        description: $('#setDescription').val()
    };

    console.log("Submitting set data:", formData); // Debug log

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
                    fetchSets(); // Refresh the sets list
                } else {
                    showAlert(result.error || 'Neizdevās pievienot komplektu.', 'danger');
                }
            } catch (e) {
                console.error("Invalid JSON response:", response); // Debug log
                showAlert("Neizdevās apstrādāt servera atbildi!", "danger");
            }
        },
        error: function () {
            showAlert("Neizdevās nosūtīt pieprasījumu!", "danger");
        }
    });
});


function searchSets() {
    const searchQuery = $('#searchInput').val().trim(); // Get the search input value
    console.log(`Searching sets with query: ${searchQuery}`); // Debug log

    $.ajax({
        url: `../database/sets_list.php?search=${encodeURIComponent(searchQuery)}`,
        type: 'GET',
        success: function (response) {
            try {
                const sets = JSON.parse(response);
                console.log("Search results:", sets); // Debug log
                displaySets(sets); // Update the sets display
            } catch (e) {
                console.error("Invalid JSON response:", response); // Debug log
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", xhr.responseText); // Debug log
        }
    });
}

$('#searchInput').on('input', function () {
    searchSets();
});




function showSetDetails(setId) {
    $('#setDetailsModal').data('set-id', setId); // Store the set ID in the modal
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

            // Populate modal title, name, and description
            $('#modalSetName').text(set.set_name);
            $('#modalSetTitle').text(set.set_name);
            $('#modalSetDescription').text(set.set_description);

            // Populate products in the set
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

            // Only show the message if the set exists but has no products
            if (productTemplate === '' && set.products.length === 0) {
                productTemplate = '<p class="text-center no-sets-message">Šim komplektam nav pievienotu produktu.</p>';
            }

            $('#modalProductsContainer').html(productTemplate);

            // Fetch and display categories
            fetchCategories();

            // Show the modal
            $('#setDetailsModal').modal('show');
        },
        error: function () {
            showAlert("Neizdevās ielādēt komplekta detaļas!", "danger");
        }
    });
}

function deleteProductFromSet(productId, setId) {
    console.log(`Deleting product ${productId} from set ${setId}`); // Debug log

    $.ajax({
        url: '../database/product_set_delete.php', // Backend script to handle deletion
        type: 'POST',
        data: { id_product: productId, id_set: setId },
        success: function (response) {
            try {
                const result = JSON.parse(response);
                if (result.success) {
                    // Refresh the modal products container
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

    // Group categories by big_category
    categories.forEach(category => {
        if (groupedCategories[category.big_category]) {
            groupedCategories[category.big_category].push(category);
        }
    });

    let template = "";

    Object.keys(groupedCategories).forEach(bigCategory => {
        const categories = groupedCategories[bigCategory];
        const categoryId = bigCategory.replace(/\s+/g, '-').toLowerCase(); // Create a unique ID

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
    console.log(`Adding product ${productId} to set ${setId}`); // Debug log
    $.ajax({
        url: '../database/product_set_add.php',
        type: 'POST',
        data: { id_product: productId, id_set: setId },
        success: function (response) {
            try {
                const result = JSON.parse(response);
                if (result.success) {
                    alert('Produkts veiksmīgi pievienots komplektam!');
                    updateModalProductsContainer(productId); // Ensure this is called
                } else {
                    alert('Neizdevās pievienot produktu komplektam: ' + result.error);
                }
            } catch (e) {
                console.error("Invalid JSON response:", response); // Debug log
                alert("Neizdevās apstrādāt servera atbildi!");
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", xhr.responseText); // Debug log
            alert("Neizdevās pievienot produktu komplektam!");
        }
    });
}

function updateModalProductsContainer(productId) {
    console.log(`Updating modal with product ID: ${productId}`); // Debug log
    $.ajax({
        url: '../database/product_list.php',
        type: 'GET',
        success: function (response) {
            try {
                const products = JSON.parse(response);
                const product = products.find(p => p.id == productId);
                if (product) {
                    console.log("Product found:", product); // Debug log

                    // Remove the "no products" message if it exists
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
                    console.error("Product not found in response:", response); // Debug log
                }
            } catch (e) {
                console.error("Invalid JSON response:", response); // Debug log
                alert("Neizdevās apstrādāt servera atbildi!");
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", xhr.responseText); // Debug log
            alert("Neizdevās ielādēt produktu!");
        }
    });
}

function toggleCategory(id) {
    const elem = document.getElementById(id);
    elem.style.display = elem.style.display === 'none' ? 'block' : 'none';
}

function fetchProductsByCategory(bigCategory, subCategory) {
    console.log(`Fetching products for Big Category: ${bigCategory}, Subcategory: ${subCategory}`); // Debug log
    $.ajax({
        url: '../database/product_list.php',
        type: 'GET',
        success: function (response) {
            console.log("Response from server:", response); // Debug log
            const products = JSON.parse(response);
            const filteredProducts = products.filter(product => 
                product.big_category === bigCategory && product.category_name === subCategory
            );

            console.log("Filtered Products:", filteredProducts); // Debug log

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

            // Add click event listener for the + button
            $(`#${sanitizedId} .add-to-set`).off('click').on('click', function () {
                const productId = $(this).data('product-id');
                const setId = $('#setDetailsModal').data('set-id'); // Assuming the modal has the set ID stored in a data attribute
                addProductToSet(productId, setId);
            });
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", xhr.responseText); // Debug log
            alert("Neizdevās ielādēt produktus!");
        }
    });
}

function toggleSubCategory(bigCategory, subCategoryId) {
    console.log(`Toggling Subcategory: ${subCategoryId} under Big Category: ${bigCategory}`); // Debug log
    const elem = document.getElementById(subCategoryId);
    if (elem.style.display === 'none') {
        elem.style.display = 'block';
        const subCategoryName = $(`#${subCategoryId}`).data('subcategory');
        fetchProductsByCategory(bigCategory, subCategoryName);
    } else {
        elem.style.display = 'none';
    }
}

function deleteSet(setId, event) {
    event.stopPropagation(); // Prevent triggering the card click event

    // Show the delete modal
    $('#deleteModal').modal('show');

    // Set the confirm delete button to handle the deletion
    $('#confirmDelete').off('click').on('click', function () {
        $(this).blur(); // Remove focus from the button
        $.ajax({
            url: '../database/set_delete.php',
            type: 'POST',
            data: { id: setId },
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    showAlert("Komplekts veiksmīgi dzēsts!", "success");
                    $('#deleteModal').modal('hide'); // Hide the modal after deletion
                    console.log("Fetching sets after deletion..."); // Debug log
                    fetchSets(); // Refresh the sets list
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
