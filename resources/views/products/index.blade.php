<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Laravel 12 - Ajax</title>
    {{-- fontawesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- datatable --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Laravel 12 Ajax</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Pricing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container my-5">
        <div class="card">
            <h5 class="card-header">Products</h5>
            <div class="card-body">
                <a href="#" class="btn btn-primary mb-2" onclick="addModal()"> <i class="fa-solid fa-plus"></i>
                    Add New Product</a>
                <table id="tableProduct" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('products.modal')

    {{-- Jquery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    {{-- Bootstrap5 --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    {{-- sweetalert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- datatable --}}
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
    <!-- Laravel Javascript Validation -->
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\ProductRequest', '#productForm') !!}

    <script>
        let save_method;

        $(document).ready(function() {
            productsTable();
        });

        function productsTable() {
            $('#tableProduct').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                // filters = true,
                // order: [
                //     [0, 'desc']
                // ],
                ajax: 'products/datatable',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                    }, {
                        data: 'name',
                        name: 'name',
                    }, {
                        data: 'slug',
                        name: 'slug',
                    }, {
                        data: 'description',
                        name: 'description',
                    }, {
                        data: 'price',
                        name: 'price',
                    },
                    {
                        data: 'action',
                        name: 'action',
                    },
                ]
            });
        }

        function resetValidation() {
            $('.is-invalid').removeClass('is-invalid');
            $('.is-valid').removeClass('is-valid');
            $('span.invalid-feedback').remove();
        }

        // addModal
        function addModal() {
            // reset form
            $('#productForm')[0].reset();

            resetValidation();

            $('#productModal').modal('show');

            save_method = 'add';

            $('.modal-title').text('Add New Product');
            $('.btnSubmit').text('Create');
        }

        // FORM - store/ update
        $('#productForm').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this)

            var url, method;

            // default post
            url = "products";
            method = "POST";

            // if method add
            if (save_method == 'edit') {
                url = 'products/' + $('#id').val();
                formData.append('_method', 'PUT')
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: method,
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#productModal').modal('hide');
                    $('#tableProduct').DataTable().ajax.reload();

                    Swal.fire({
                        title: response.title,
                        text: response.text,
                        icon: response.icon,
                        showConfirmButton: false,
                        timer: 1500
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR.responseText);
                    // alert("Error: " + jqXHR.responseText);
                }
            });
        });

        // editModal
        function editModal(e) {
            let id = e.getAttribute('data-id');

            save_method = 'edit';

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: "products/" + id,
                success: function(response) {
                    let result = response.data;

                    $('#name').val(result.name)
                    $('#description').val(result.description)
                    $('#price').val(result.price)
                    $('#id').val(result.uuid)
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR.responseText);
                    alert("Error displaying data: " + jqXHR.responseText);
                }
            });

            resetValidation();
            $('#productModal').modal('show');

            $('.modal-title').text('Update Product');
            $('.btnSubmit').text('Update');
        }

        // deleteModal
        function deleteModal(e) {
            let id = e.getAttribute('data-id');

            Swal.fire({
                title: "Delete?",
                text: "Are you sure you want to delete this product?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                confirmButtonColor: "#3085d6",
                cancelButtonText: "No, cancel!",
                cancelButtonColor: "#d33",
            }).then((result) => {

                if (!result.isConfirmed) return;

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "DELETE",
                    url: "products/" + id,
                    dataType: "json",
                    success: function(response) {
                        $('#productModal').modal('hide');

                        $('#tableProduct').DataTable().ajax.reload();

                        Swal.fire({
                            title: response.title,
                            text: response.text,
                            icon: response.icon,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR.responseText);
                        alert(jqXHR.responseText);
                    }
                });
            });
        }
    </script>
</body>

</html>
