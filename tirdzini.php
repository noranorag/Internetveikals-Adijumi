<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tirdziņi</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Announcement Bar -->
    <div class="announcement" id="announcement"></div>

    <?php include 'files/navbar.php'; ?>

    <div class="container mt-5 pt-5">
        <div class="heading-container">
            <h1 class="mb-2">Tirdziņi</h1>
            <p class="mb-0" style="font-size: 1.2rem;">Tirdziņi, kuros mani satikt</p>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="market-item">
                    <img src="images/tirgus.jpg" alt="Tirgus Image" class="market-image" data-toggle="modal" data-target="#imageModal" data-src="images/tirgus.jpg">
                    <div>
                        <h3>Tirgus 1</h3>
                        <p>Short description of Tirgus 1.</p>
                        <a href="https://example.com" class="market-link">Visit Tirgus 1</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="market-item">
                    <img src="images/tirgus2.jpg" alt="Tirgus Image" class="market-image" data-toggle="modal" data-target="#imageModal" data-src="images/tirgus2.jpg">
                    <div>
                        <h3>Tirgus 2</h3>
                        <p>Short description of Tirgus 2.</p>
                        <a href="https://example.com" class="market-link">Visit Tirgus 2</a>
                    </div>
                </div>
            </div>
        </div>

        <p class="mb-4 larger-text">Bijušie Tirdziņi</p>
        <div class="row">
            <div class="col-md-6">
                <div class="market-item dimmed small-market-item">
                    <img src="images/tirgus.jpg" alt="Tirgus Image" class="market-image" data-toggle="modal" data-target="#imageModal" data-src="images/tirgus.jpg">
                    <div>
                        <h4>Tirgus 3</h4>
                        <p>Short description of Tirgus 3.</p>
                        <a href="https://example.com" class="market-link">Visit Tirgus 3</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="market-item dimmed small-market-item">
                    <img src="images/tirgus2.jpg" alt="Tirgus Image" class="market-image" data-toggle="modal" data-target="#imageModal" data-src="images/tirgus2.jpg">
                    <div>
                        <h4>Tirgus 4</h4>
                        <p>Short description of Tirgus 4.</p>
                        <a href="https://example.com" class="market-link">Visit Tirgus 4</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="" id="modalImage" class="img-fluid" alt="Market Image">
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Custom JS -->
    <script src="scripts.js"></script>
    <script>
        $('#imageModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var imageSrc = button.data('src');
            var modal = $(this);
            modal.find('#modalImage').attr('src', imageSrc);
        });
    </script>
</body>
</html>