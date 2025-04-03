$(document).ready(function () {
    let isEditMode = false; 

    $(document).ready(function () {
        fetchCategoriesWithPagination(); 
    });

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

    function fetchCategoriesWithPagination(page = 1) {
        const limit = 7; 
    
        $.ajax({
            url: '../database/category_list.php',
            type: 'GET',
            data: { page: page, limit: limit }, 
            dataType: 'json', 
            success: function (response) {
    
                if (!Array.isArray(response.categories)) {
                    return;
                }
    
                if (response.categories.length === 0) {
                    $('#category').html('<tr><td colspan="4">Nav atrastas kategorijas.</td></tr>'); 
                    return;
                }
    
                displayCategories(response.categories);
                updateCategoryPagination(response.total, response.page, response.limit); 
            },
            error: function () {
                alert("Neizdevās ielādēt kategorijas!");
            }
        });
    }

    function updateCategoryPagination(total, currentPage, limit) {
        const totalPages = Math.ceil(total / limit);
        let paginationTemplate = "";
    
        for (let i = 1; i <= totalPages; i++) {
            paginationTemplate += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link category-page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `;
        }
    
        $('.category-pagination').html(`
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link category-page-link" href="#" data-page="${currentPage - 1}">Iepriekšējā</a>
            </li>
            ${paginationTemplate}
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link category-page-link" href="#" data-page="${currentPage + 1}">Nākamā</a>
            </li>
        `);
    }

    $(document).on('click', '.category-pagination .category-page-link', function (e) {
        e.preventDefault();
        const page = $(this).data('page');
        fetchCategoriesWithPagination(page);
    });

    

    

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
        const categoryName = row.find('td:nth-child(3)').text();
        const bigCategory = row.find('td:nth-child(2)').text();

        $('#categoryModalLabel').text("Rediģēt kategoriju");
        $('#categoryId').val(categoryId); 
        $('#categoryName').val(categoryName);
        $('#bigCategory').val(bigCategory);
        $('#categoryModal').modal('show');
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
    
        console.log("Submitting data:", formData); 
    
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
                console.log("Response from server:", response); 
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
        deleteCategoryId = $(e.currentTarget).closest('tr').attr("category_ID"); 
        $('#deleteModal').modal('show'); 
    });

    $('#confirmDelete').click(function () {
        if (deleteCategoryId) {
            $.post('../database/category_delete.php', { id: deleteCategoryId }, function (response) {
                console.log("Response from server:", response); 
                $('#deleteModal').modal('hide'); 
                fetchCategories(); 
            });
        }
    });








    fetchProducts();

    function fetchProducts(search = "") {
    
        $.ajax({
            url: '../database/product_list.php',
            type: 'GET',
            data: { search: search }, 
            success: function (response) {
                const products = JSON.parse(response);
    
                if (products.error) {
                    alert(products.error); 
                    return;
                }
    
                if (products.length === 0) {
                    $('#product').html('<tr><td colspan="7">Nav atrastas preces.</td></tr>'); 
                    return;
                }
    
                displayProducts(products);
            },
            error: function () {
                alert("Neizdevās ielādēt preces!");
            }
        });
    }

    function fetchProducts(search = "", page = 1) {
        const limit = 7; 
    
        $.ajax({
            url: '../database/product_list.php',
            type: 'GET',
            data: { search: search, page: page, limit: limit },
            dataType: 'json',
            success: function (response) {
    
                if (response.error) {
                    alert(response.error);
                    return;
                }
    
                if (response.products.length === 0) {
                    console.log("No products found for the search query.");
                    $('#product').html('<tr><td colspan="7">Nav atrastas preces.</td></tr>');
                    return;
                }
    
                displayProducts(response.products);
                updatePagination(response.total, response.page, response.limit); 
            },
            error: function () {
                alert("Neizdevās ielādēt preces!");
            }
        });
    }
    
    function updatePagination(total, currentPage, limit) {
    
        const totalPages = Math.ceil(total / limit);
        let paginationTemplate = "";
    
        for (let i = 1; i <= totalPages; i++) {
            paginationTemplate += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link product-page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `;
        }
    
        $('.product-pagination').html(`
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link product-page-link" href="#" data-page="${currentPage - 1}">Iepriekšējā</a>
            </li>
            ${paginationTemplate}
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link product-page-link" href="#" data-page="${currentPage + 1}">Nākamā</a>
            </li>
        `);
    }
    
    
    $(document).on('click', '.product-pagination .product-page-link', function (e) {
        e.preventDefault();
        const page = $(this).data('page');
        const searchQuery = $('#searchInput').val();
        fetchProducts(searchQuery, page);
    });
    
    
    $('#searchInput').on('input', function () {
        const searchQuery = $(this).val();
        fetchProducts(searchQuery); 
    });

    function displayProducts(products) {
        let template = "";
    
        products.forEach(product => {
            template += `
                <tr>
                    <td>${product.id}</td>
                    <td>${product.name}</td>
                    <td>${product.category_name || 'Nav kategorijas'}</td> <!-- Display category name -->
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

    let isProductEditMode = false; 

    
    $('#addProductModal').on('hidden.bs.modal', function () {
        console.log("Resetting modal state");
        $('#productForm')[0].reset(); 
        $('#addProductModalLabel').text("Pievienot preci"); 
        $('#productId').val(""); 
        populateCategoryDropdown(); 
        $('#imagePreview').attr('src', '').hide(); 
        isProductEditMode = false; 
    });

    
    $(document).on('click', '.product-edit', function (e) {
        e.preventDefault();
        isProductEditMode = true; 
        console.log("Opening Edit Product modal");
    
        const productId = $(this).data('id'); 
    
        
        $.ajax({
            url: '../database/product_get.php',
            type: 'GET',
            data: { id: productId },
            success: function (response) {
                const product = JSON.parse(response);
    
                if (product.error) {
                    alert(product.error); 
                    return;
                }
    
                
                $('#addProductModalLabel').text("Rediģēt preci"); 
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
    
                
                populateCategoryDropdown(product.ID_category);
    
                
                if (product.image) {
                    $('#imagePreview').attr('src', `../${product.image}`).show(); 
                } else {
                    $('#imagePreview').attr('src', '').hide(); 
                }
    
                
                $('#addProductModal').modal('show');
            },
            error: function () {
                alert("Neizdevās ielādēt preces datus!");
            }
        });
    });

    
    $('#addProductButton').click(function () {
        isProductEditMode = false; 
        console.log("Opening Add Product modal");
        $('#addProductModalLabel').text("Pievienot preci"); 
        $('#productForm')[0].reset(); 
        $('#productId').val(""); 
        populateCategoryDropdown(); 
        $('#addProductModal').modal('show'); 
    });

    function populateCategoryDropdown(selectedCategoryId = null) {
    
        $.ajax({
            url: '../database/category_list.php', 
            type: 'GET',
            success: function (response) {
                const categories = JSON.parse(response);
                const categoryDropdown = $('#category');
                categoryDropdown.empty(); 
    
                
                if (selectedCategoryId === null) {
                    
                    categoryDropdown.append('<option value="" selected>Izvēlieties kategoriju</option>');
                } else {
                    
                    categoryDropdown.append('<option value="">Izvēlieties kategoriju</option>');
                }
    
                
                categories.forEach(category => {
                    const isSelected = selectedCategoryId == category.id ? 'selected' : ''; 
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
                const result = JSON.parse(response);
    
                if (result.success) {
                    $('#addProductModal').modal('hide');
                    $('#productForm')[0].reset();
                    fetchProducts(); 
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
        const deleteProductId = $(e.currentTarget).closest('tr').find('.product-edit').data('id'); 
        $('#deleteModal').modal('show'); 
    
        
        $('#confirmDelete').off('click').on('click', function () {
            if (deleteProductId) {
                $.post('../database/product_delete.php', { id: deleteProductId }, function (response) {
                    console.log("Response from server:", response); 
                    $('#deleteModal').modal('hide'); 
                    fetchProducts(); 
                }).fail(function () {
                    alert("Neizdevās dzēst preci!"); 
                });
            }
        });
    });






        fetchGalleryWithPagination(); 


    function fetchGallery() {
        $.ajax({
            url: '../database/gallery_list.php',
            type: 'GET',
            dataType: 'json', 
            success: function (response) {
    
                if (response.error) {
                    alert(response.error); 
                    return;
                }
    
                displayGallery(response.gallery);
            },
            error: function () {
                alert("Neizdevās ielādēt galerijas datus!");
            }
        });
    }

    

    function fetchGalleryWithPagination(page = 1) {
        const limit = 3; 
        console.log("Fetching gallery for page:", page); 
    
        $.ajax({
            url: '../database/gallery_list.php',
            type: 'GET',
            data: { page: page, limit: limit }, 
            dataType: 'json', 
            success: function (response) {
                console.log("Response from gallery_list.php:", response); 
    
                if (!response || typeof response !== 'object') {
                    console.error("Invalid response format:", response); 
                    return;
                }
    
                if (!Array.isArray(response.gallery)) {
                    console.error("Gallery is not an array:", response.gallery); 
                    return;
                }
    
                if (response.gallery.length === 0) {
                    console.log("No gallery items found."); 
                    $('#gallery').html('<tr><td colspan="5">Nav atrastas galerijas bildes.</td></tr>'); 
                    return;
                }
    
                displayGallery(response.gallery);
    
                
                console.log("Calling updateGalleryPagination with:", {
                    total: response.total,
                    page: response.page,
                    limit: response.limit,
                });
    
                updateGalleryPagination(response.total, response.page, response.limit); 
            },
            error: function (xhr, status, error) {
                console.error("AJAX error:", status, error); 
                alert("Neizdevās ielādēt galerijas datus!");
            }
        });
    }

    function updateGalleryPagination(total, currentPage, limit) {
        console.log("Updating gallery pagination with total:", total, "currentPage:", currentPage, "limit:", limit); 
    
        const totalPages = Math.ceil(total / limit);
        console.log("Total pages:", totalPages); 
    
        if (totalPages <= 1) {
            $('.gallery-pagination').html(''); 
            return;
        }
    
        let paginationTemplate = "";
    
        for (let i = 1; i <= totalPages; i++) {
            paginationTemplate += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link gallery-page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `;
        }
    
        $('.gallery-pagination').html(`
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link gallery-page-link" href="#" data-page="${currentPage - 1}">Iepriekšējā</a>
            </li>
            ${paginationTemplate}
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link gallery-page-link" href="#" data-page="${currentPage + 1}">Nākamā</a>
            </li>
        `);
    
        console.log("Pagination HTML updated:", $('.gallery-pagination').html()); 
    }

    $(document).on('click', '.gallery-pagination .gallery-page-link', function (e) {
        e.preventDefault();
        const page = $(this).data('page');
        console.log("Gallery pagination clicked. Page:", page); 
        if (page > 0) {
            fetchGalleryWithPagination(page); 
        }
    });
    
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
    
        $('#gallery').html(template); 
    }
    
        
        fetchGallery();
    
        
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
                        fetchGallery(); 
                    } else {
                        alert(result.error);
                    }
                },
                error: function () {
                    alert("Neizdevās apstiprināt bildi!");
                }
            });
        });
    
        
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
                        fetchGallery(); 
                    } else {
                        alert(result.error);
                    }
                },
                error: function () {
                    alert("Neizdevās noraidīt bildi!");
                }
            });
        });


        
        


        
        $('#fairSearchInput').on('input', function () {
            const searchQuery = $(this).val().trim(); 
            fetchFairs(searchQuery); 
        });
        fetchFairs();

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
        }
    });

    
    $('#marketForm').submit(function (e) {
        e.preventDefault(); 
    
        const formData = new FormData(this);
        const url = isFairEditMode
            ? '../database/fair_edit.php' 
            : '../database/fair_add.php'; 
    
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false, 
            contentType: false, 
            success: function (response) {
                const result = JSON.parse(response);
    
                if (result.success) {
                    $('#addMarketModal').modal('hide');
                    $('#marketForm')[0].reset();
                    fetchFairs(); 
                } else {
                    alert(result.error || "Neizdevās saglabāt tirdziņu!");
                }
            },
            error: function () {
                alert("Neizdevās nosūtīt pieprasījumu!");
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
                    const result = JSON.parse(response);
    
                    if (result.success) {
                        $('#deleteModal').modal('hide'); 
                        fetchFairs(); 
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
                        const result = JSON.parse(response);
        
                        if (result.success) {
                            $('#deleteModal').modal('hide'); 
                            fetchFairs(); 
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





        fetchUsers();
    
        function fetchUsers() {
            $.ajax({
                url: '../database/user_list.php',
                type: 'GET',
                success: function (response) {
                    const users = JSON.parse(response);
                    displayUsers(users);
                },
                error: function () {
                    alert("Neizdevās ielādēt lietotājus!");
                }
            });
        }

        function displayUsers(users) {
            let template = "";
        
            users.forEach(user => {
                template += `
                    <tr>
                        <td>${user.id}</td>
                        <td>${user.address_id}</td>
                        <td>${user.name}</td>
                        <td>${user.surname}</td>
                        <td>${user.phone}</td>
                        <td>${user.email}</td>
                        <td>${user.role}</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-warning edit-user" data-id="${user.id}">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-danger delete-user" data-id="${user.id}">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                `;
            });
        
            $('#userTableBody').html(template);
        }

        let isUserEditMode = false; 

    
    fetchUsers();

    function fetchUsers(search = "") {
    
        $.ajax({
            url: '../database/user_list.php',
            type: 'GET',
            data: { search: search }, 
            success: function (response) {

                const users = JSON.parse(response);
    
                if (users.error) {
                    alert(users.error); 
                    return;
                }
    
                if (users.length === 0) {
                    $('#userTableBody').html('<tr><td colspan="8">Nav atrasti lietotāji.</td></tr>'); 
                    return;
                }
    
                displayUsers(users);
            },
            error: function () {
                alert("Neizdevās ielādēt lietotājus!");
            }
        });
    }
    
    function displayUsers(users) {
        let template = "";
    
        users.forEach(user => {
            const addressButton = user.address_id
                ? `<a href="#" class="btn btn-sm btn-info view-address" data-id="${user.address_id}">
                       Apskatīt
                   </a>`
                : ""; 
    
            template += `
                <tr>
                    <td>${user.id}</td>
                    <td>
                        ${addressButton} <!-- Render the button conditionally -->
                    </td>
                    <td>${user.name}</td>
                    <td>${user.surname}</td>
                    <td>${user.phone}</td>
                    <td>${user.email}</td>
                    <td>${user.role}</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-warning edit-user" data-id="${user.id}">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="#" class="btn btn-sm btn-danger delete-user" data-id="${user.id}">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
            `;
        });
    
        $('#userTableBody').html(template); 
    }
    
    
    $('#userSearchInput').on('input', function () {
        const searchQuery = $(this).val();
        fetchUsers(searchQuery); 
    });

    $('#addUserButton').click(function () {
        isUserEditMode = false; 
    
        $('#addUserModalLabel').text("Pievienot Lietotāju");
        $('#userForm')[0].reset(); 
        $('#userId').val(""); 
        $('#addUserModal').modal('show'); 
    });

    $('#addUserModal').on('hidden.bs.modal', function () {
        $('#userForm')[0].reset(); 
        $('#addUserModalLabel').text("Pievienot Lietotāju"); 
        $('#userId').val(""); 
        isUserEditMode = false; 
    });

    
    $(document).on('click', '.edit-user', function (e) {
        e.preventDefault();
        isUserEditMode = true; 
    
        const userId = $(this).data('id'); 
    
        
        $.ajax({
            url: '../database/user_get.php',
            type: 'GET',
            data: { id: userId },
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
                $('#userAddressId').val(user.ID_address); 
    
                
                $('#userRole').val(user.role); 
    
                
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
            id: $('#userId').val(),
            name: $('#userFirstName').val(),
            surname: $('#userLastName').val(),
            phone: $('#userPhone').val(),
            email: $('#userEmail').val(),
            role: $('#userRole').val(),
            address_id: null 
        };
    
        
        const password = $('#userPassword').val();
        if (password) {
            formData.password = password;
        }
    
        const url = isUserEditMode
            ? '../database/user_edit.php' 
            : '../database/user_add.php'; 
    
    
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
                const result = JSON.parse(response);
    
                if (result.success) {
                    $('#addUserModal').modal('hide');
                    $('#userForm')[0].reset();
                    fetchUsers(); 
                } else {
                    alert(result.error || "Neizdevās saglabāt lietotāju!");
                }
            },
            error: function () {
                alert("Neizdevās nosūtīt pieprasījumu!");
            }
        });
    });

    $(document).on('click', '.view-address', function (e) {
        e.preventDefault();
    
        const addressId = $(this).data('id'); 
    
        
        $.ajax({
            url: '../database/address_get.php',
            type: 'GET',
            data: { id: addressId },
            success: function (response) {
                const address = JSON.parse(response);
    
                if (address.error) {
                    alert(address.error);
                    return;
                }
    
                
                $('#addressModalLabel').text("Adrese");
                $('#addressDetails').html(`
                    <p><strong>Adrese ID:</strong> ${address.address_ID}</p>
                    <p><strong>Valsts:</strong> ${address.country}</p>
                    <p><strong>Pilsēta:</strong> ${address.city}</p>
                    <p><strong>Iela:</strong> ${address.street}</p>
                    <p><strong>Mājas numurs:</strong> ${address.house}</p>
                    <p><strong>Dzīvokļa numurs:</strong> ${address.apartment || 'Nav norādīts'}</p>
                    <p><strong>Pasta indekss:</strong> ${address.postal_code}</p>
                `);
                $('#addressModal').modal('show');
            },
            error: function () {
                alert("Neizdevās ielādēt adreses datus!");
            }
        });
    });

    $(document).on('click', '.delete-user', function (e) {
        e.preventDefault();
    
        const deleteUserId = $(this).data('id'); 
        $('#deleteModal').modal('show'); 
    
        
        $('#confirmDelete').off('click').on('click', function () {
            if (deleteUserId) {
                $.post('../database/user_delete.php', { id: deleteUserId }, function (response) {
                    console.log("Response from server:", response); 
                    const result = JSON.parse(response);
    
                    if (result.success) {
                        $('#deleteModal').modal('hide'); 
                        fetchUsers(); 
                    } else {
                        alert(result.error || "Neizdevās dzēst lietotāju!");
                    }
                }).fail(function () {
                    alert("Neizdevās nosūtīt pieprasījumu!");
                });
            }
        });
    });
    


});

