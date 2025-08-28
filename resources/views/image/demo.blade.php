<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Image Picker Modal</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .image-thumb {
            width: 120px;
            height: 120px;
            object-fit: cover;
            cursor: pointer;
            border: 3px solid transparent;
            transition: 0.2s;
        }

        .image-thumb.active {
            border-color: #0d6efd;
        }

        #preview-wrapper {
            display: none;
            max-width: 200px;
        }

        #drop-area {
            border: 2px dashed #0d6efd;
            padding: 30px;
            text-align: center;
            cursor: pointer;
        }

        #loading-overlay {
            background-color: rgba(255, 255, 255, 0.6);
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body class="p-5">

    <form action="{{ route('imageses.store') }}" method="POST">
    @csrf

    <div class="container">

        <!-- Product Image -->
        <label for="product-image-input">Product Image:</label>
        <div class="input-group mb-3 imagegroup" data-target="product">
            <input type="text" id="product-image-input" class="form-control" readonly>
            <button class="btn btn-primary open-image-modal" type="button" data-bs-toggle="modal" data-bs-target="#imageModal">Choose</button>
        </div>
        <div class="mt-3 position-relative preview-wrapper" id="product-preview-wrapper" style="display: none;">
            <img id="product-preview" src="" class="img-fluid rounded">
            <button type="button" class="btn-close position-absolute top-0 end-0 remove-preview" style="background-color:red;" data-target="product"></button>
        </div>

        <!-- User Image -->
        <label for="user-image-input">User Image:</label>
        <div class="input-group mb-3 imagegroup" data-target="user">
            <input type="text" id="user-image-input" class="form-control" readonly>
            <button class="btn btn-primary open-image-modal" type="button" data-bs-toggle="modal" data-bs-target="#imageModal">Choose</button>
        </div>
        <div class="mt-3 position-relative preview-wrapper" id="user-preview-wrapper" style="display: none;">
            <img id="user-preview" src="" class="img-fluid rounded">
            <button type="button" class="btn-close position-absolute top-0 end-0 remove-preview" style="background-color:red;" data-target="user"></button>
        </div>

        <!-- Nominee Image -->
        <label for="nominee-image-input">Nominee Image:</label>
        <div class="input-group mb-3 imagegroup" data-target="nominee">
            <input type="text" id="nominee-image-input" class="form-control" readonly>
            <button class="btn btn-primary open-image-modal" type="button" data-bs-toggle="modal" data-bs-target="#imageModal">Choose</button>
        </div>
        <div class="mt-3 position-relative preview-wrapper" id="nominee-preview-wrapper" style="display: none;">
            <img id="nominee-preview" src="" class="img-fluid rounded">
            <button type="button" class="btn-close position-absolute top-0 end-0 remove-preview" style="background-color:red;" data-target="nominee"></button>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-success">Submit</button>
        </div>

    </div>
</form>


    <!-- Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Media Manager</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#library">Media Library</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#upload">Upload New</button>
                        </li>
                    </ul>

                    <div class="tab-content mt-3" style="height: 400px; overflow-y: auto;">

                        <!-- ✅ Sort Option MOVED inside Media Library tab -->
                        <div class="tab-pane fade show active" id="library">
                            <div class="d-flex justify-content-between align-items-center mb-2 gap-3 flex-wrap">
                                <!-- Search Input (left) -->
                                <input type="text" id="image-search" class="form-control w-auto" placeholder="Search by name..." style="min-width: 200px;">

                                <!-- Sort Selector (right) -->
                                <div class="d-flex align-items-center gap-2">
                                    <label for="sort-select" class="form-label mb-0">Sort By:</label>
                                    <select id="sort-select" class="form-select w-auto">
                                        <option value="newest" selected>Newest</option>
                                        <option value="oldest">Oldest</option>
                                        <option value="largest">Largest</option>
                                        <option value="smallest">Smallest</option>
                                    </select>
                                </div>
                            </div>
                            <div class="tab-pane fade show active h-100" id="library" role="tabpanel">
                                <div class="d-flex flex-wrap justify-content-start" id="image-gallery"></div>
                            </div>
                        </div>

                        <!-- Upload Tab -->
                        <div class="tab-pane fade" id="upload">
                            <div id="drop-area" class="position-relative d-flex flex-column align-items-center justify-content-center h-100">
                                <div id="preview-container" class="position-relative d-none">
                                    <img id="upload-preview" src="" class="img-thumbnail" style="max-height: 200px;">
                                    <div id="loading-overlay" class="position-absolute top-50 start-50 translate-middle d-none">
                                        <div class="spinner-border text-primary"></div>
                                    </div>
                                </div>
                                <div id="drop-message" class="text-muted">Drag & Drop or Click to Upload</div>
                            </div>
                            <input type="file" id="fileElem" style="display: none;">
                        </div>

                    </div>
                </div>


                <div class="modal-footer">
                    <button id="addImageBtn" type="button" class="btn btn-success" disabled>Add</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <script>



    let selectedImage = null;
    let currentTarget = null; // will be 'product', 'user', or 'nominee'

    // When "Choose" is clicked, store which input is being targeted
    $('.open-image-modal').on('click', function () {
        currentTarget = $(this).closest('.imagegroup').data('target');
    });

    $('#addImageBtn').on('click', function () {
        if (selectedImage && currentTarget) {
            const imagePath = '/storage/uploads/' + selectedImage.name;

            // Set preview
            $('#' + currentTarget + '-preview').attr('src', imagePath);
            $('#' + currentTarget + '-preview-wrapper').show();
            $('#' + currentTarget + '-image-input').val(selectedImage.original_name);

            // Hidden field for ID
            const hiddenIdInput = `<input type="hidden" id="${currentTarget}-image-hidden-id" name="${currentTarget}_image_id" value="${selectedImage.id}">`;
            const group = $(`.imagegroup[data-target="${currentTarget}"]`);
            if ($(`#${currentTarget}-image-hidden-id`).length === 0) {
                group.append(hiddenIdInput);
            } else {
                $(`#${currentTarget}-image-hidden-id`).val(selectedImage.id);
            }

            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('imageModal'));
            modal.hide();
        }
    });

    // Remove preview button
    $('.remove-preview').on('click', function () {
        const target = $(this).data('target');
        $('#' + target + '-preview-wrapper').hide();
        $('#' + target + '-preview').attr('src', '');
        $('#' + target + '-image-input').val('');
        $('#' + target + '-image-hidden-id').remove();
    });

    // Keep rest of your JS as-is (image loading, uploading, sorting, etc.)

        



        function formatFileSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            else if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            else return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        }


        function loadImages(sort = 'newest') {
            const searchQuery = $('#image-search').val().toLowerCase(); // 🔍

            $.get("{{ route('images.view') }}", {
                sort: sort
            }, function(data) {
                let gallery = $('#image-gallery');
                gallery.empty();
                selectedImage = null;
                $('#addImageBtn').prop('disabled', true);

                // Filter by search
                const filtered = data.filter(img =>
                    img.original_name.toLowerCase().includes(searchQuery)
                );

                filtered.forEach(img => {
                    const imgPath = '/storage/uploads/' + img.name;
                    const card = $(`
                <div class="card m-2 image-card" style="width: 140px; cursor: pointer;" data-id="${img.id}">
                    <div class="image-wrapper position-relative">
                        <img src="${imgPath}" class="card-img-top image-thumb">
                    </div>
                    <div class="card-body p-2 text-center">
                        <p class="card-text mb-1" style="font-size: 14px;">${img.original_name}</p>
                        <small class="text-muted position-absolute bottom-0 end-0">${formatFileSize(img.size)}</small>
                    </div>
                </div>
            `);

                    card.on('click', function() {
                        $('.image-thumb').removeClass('active');
                        card.find('.image-thumb').addClass('active');
                        selectedImage = img;
                        $('#addImageBtn').prop('disabled', false);
                    });

                    gallery.append(card);
                });
            });
        }

        $('#image-search').on('input', function() {
            loadImages($('#sort-select').val());
        });












        $('#sort-select').on('change', function() {
            const selectedSort = $(this).val();
            loadImages(selectedSort);
        });

        $('#imageModal').on('shown.bs.modal', function() {
            const currentSort = $('#sort-select').val();
            loadImages(currentSort);
        });

        $('#remove-preview').on('click', function() {
            $('#preview-wrapper').hide();
            $('#preview').attr('src', '');
            $('#image-input').val('');
            $('#image-hidden-id').remove();
        });

        $('#addImageBtn').on('click', function() {
            if (selectedImage) {
                const imagePath = '/storage/uploads/' + selectedImage.name;
                $('#preview').attr('src', imagePath);
                $('#preview-wrapper').show();
                $('#image-input').val(selectedImage.original_name);

                if (!$('#image-hidden-id').length) {
                    $('<input>').attr({
                        type: 'hidden',
                        id: 'image-hidden-id',
                        name: 'image_id',
                        value: selectedImage.id
                    }).appendTo('.imagegroup');
                } else {
                    $('#image-hidden-id').val(selectedImage.id);
                }

                const modal = bootstrap.Modal.getInstance(document.getElementById('imageModal'));
                modal.hide();
            }
        });

        // Upload handling
        const dropArea = document.getElementById('drop-area');

        dropArea.addEventListener('click', () => document.getElementById('fileElem').click());
        dropArea.addEventListener('dragover', e => e.preventDefault());
        dropArea.addEventListener('drop', e => {
            e.preventDefault();
            uploadFile(e.dataTransfer.files[0]);
        });

        document.getElementById('fileElem').addEventListener('change', function() {
            uploadFile(this.files[0]);
        });

        function uploadFile(file) {
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                $('#upload-preview').attr('src', e.target.result);
                $('#preview-container').removeClass('d-none');
                $('#drop-message').addClass('d-none');
                $('#loading-overlay').removeClass('d-none');
            };
            reader.readAsDataURL(file);

            let formData = new FormData();
            formData.append('file', file);

            $.ajax({
                url: "{{ route('images.upload') }}",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                processData: false,
                contentType: false,
                success: function() {
                    alert('Upload successful');
                    $('#loading-overlay').addClass('d-none');
                    loadImages($('#sort-select').val());
                    $('button[data-bs-target="#library"]').tab('show');
                },
                error: function() {
                    alert('Upload failed');
                    $('#loading-overlay').addClass('d-none');
                }
            });
        }
    </script>

</body>

</html>