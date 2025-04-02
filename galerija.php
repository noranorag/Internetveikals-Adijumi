<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerija</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="announcement" id="announcement"></div>

    <?php include 'files/navbar.php'; ?>

    <div class="container mt-5 pt-5">
        <div class="heading-container">
            <h1 class="mb-2">Galerija</h1>
        </div>
        <div class="heading-with-lines">
            <div class="line"></div>
            <p class="page-heading">Pircēju uzņemtās bildes ar produktiem</p>
            <div class="line"></div>
        </div>
        <div class="gallery">
            <div class="gallery-item" data-toggle="modal" data-target="#imageModal" data-name="John Doe" data-product="Produkts 1">
                <img src="images/berniem.png" alt="Gallery Image">
                <div class="overlay">John Doe<br>Produkts 1</div>
            </div>
            <div class="gallery-item" data-toggle="modal" data-target="#imageModal" data-name="Jane Smith" data-product="Produkts 2">
                <img src="images/berniem.png" alt="Gallery Image">
                <div class="overlay">Jane Smith<br>Produkts 2</div>
            </div>
            <div class="gallery-item" data-toggle="modal" data-target="#imageModal" data-name="Alice Johnson" data-product="Produkts 3">
                <img src="images/berniem.png" alt="Gallery Image">
                <div class="overlay">Alice Johnson<br>Produkts 3</div>
            </div>
            <div class="gallery-item" data-toggle="modal" data-target="#imageModal" data-name="Bob Brown" data-product="Produkts 4">
                <img src="images/berniem.png" alt="Gallery Image">
                <div class="overlay">Bob Brown<br>Produkts 4</div>
            </div>
            <div class="gallery-item" data-toggle="modal" data-target="#imageModal" data-name="Charlie Davis" data-product="Produkts 5">
                <img src="images/berniem.png" alt="Gallery Image">
                <div class="overlay">Charlie Davis<br>Produkts 5</div>
            </div>
            <div class="gallery-item" data-toggle="modal" data-target="#imageModal" data-name="Diana Evans" data-product="Produkts 6">
                <img src="images/berniem.png" alt="Gallery Image">
                <div class="overlay">Diana Evans<br>Produkts 6</div>
            </div>
            <div class="gallery-item" data-toggle="modal" data-target="#imageModal" data-name="Eve Foster" data-product="Produkts 7">
                <img src="images/berniem.png" alt="Gallery Image">
                <div class="overlay">Eve Foster<br>Produkts 7</div>
            </div>
            <div class="gallery-item" data-toggle="modal" data-target="#imageModal" data-name="Frank Green" data-product="Produkts 8">
                <img src="images/berniem.png" alt="Gallery Image">
                <div class="overlay">Frank Green<br>Produkts 8</div>
            </div>
            <div class="gallery-item" data-toggle="modal" data-target="#imageModal" data-name="Grace Hill" data-product="Produkts 9">
                <img src="images/berniem.png" alt="Gallery Image">
                <div class="overlay">Grace Hill<br>Produkts 9</div>
            </div>
            <div class="gallery-item" data-toggle="modal" data-target="#imageModal" data-name="Henry Irving" data-product="Produkts 10">
                <img src="images/berniem.png" alt="Gallery Image">
                <div class="overlay">Henry Irving<br>Produkts 10</div>
            </div>
            <div class="gallery-item" data-toggle="modal" data-target="#imageModal" data-name="Ivy Jackson" data-product="Produkts 11">
                <img src="images/berniem.png" alt="Gallery Image">
                <div class="overlay">Ivy Jackson<br>Produkts 11</div>
            </div>
            <div class="gallery-item" data-toggle="modal" data-target="#imageModal" data-name="Jack King" data-product="Produkts 12">
                <img src="images/berniem.png" alt="Gallery Image">
                <div class="overlay">Jack King<br>Produkts 12</div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Image Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="" id="modalImage" class="img-fluid" alt="Gallery Image">
                    <p id="modalProduct"></p>
                </div>
            </div>
        </div>
    </div>

    <?php include 'files/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  
    <script src="scripts.js"></script>
    <script>
        $('#imageModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var name = button.data('name');
            var product = button.data('product');
            var imageSrc = button.find('img').attr('src');

            var modal = $(this);
            modal.find('.modal-title').text(name);
            modal.find('#modalImage').attr('src', imageSrc);
            modal.find('#modalName').text(name);
            modal.find('#modalProduct').text(product);
        });
    </script>
</body>
</html>