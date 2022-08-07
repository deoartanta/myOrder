@extends('layouts.app')

@section('title','Products')
@section('Products','active')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
            <div class="card-header">
                <h4>List @yield('title')</h4>
            </div>
            <div class="card-body p-0">
                <div class="add-action m-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <label for="nmStore" class="col-form-label">Store Name</label>
                        </div>
                        <div class="col-auto">
                            <select class="form-select" id="nmStore">
                                <option selected disabled>Choose stores name</option>
                                @foreach ($stores as $val)
                                    <option value="{{ $val->id }}">{{ $val->nm_store }}</option>
                                @endforeach
                                <option value="0">All</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProduct">
                                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                ADD
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @isset($products)
                                @foreach ($products as $val)
                                    <tr class="store-{{ $val->store->id }}">
                                        <td class="no">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="content-table-{{ $val->id }} nm_product">
                                                {{ $val->nm_product }}
                                                <div class="table-links">
                                                    Update at {{ date('d F Y, h:i A',strtotime($val->updated_at)) }}
                                                    <div class="bullet"></div>
                                                    <a href="#">{{ $val->store->nm_store }}</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="content-table-{{ $val->id }} price">
                                                {{ sprintf('Rp. %s', number_format($val->hrg_product)) }}
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class=" btn btn-primary btn-action mr-1 btn-edit" title="Edit" data-href="{{ route('product.update', $val->id) }}" data-id="{{ $val->id }}" data-id_store="{{ $val->store->id }}" data-name="{{ $val->nm_product }}" data-hrg="{{ $val->hrg_product }}" data-bs-toggle="modal" data-bs-target="#editProduct"><i class="fas fa-pencil-alt"></i></button>

                                            <button type="button" class=" btn btn-danger btn-action btn-hps" title="Delete" data-id="{{ $val->id }}" data-name="{{ $val->nm_product }}" data-href="{{ route('product.destroy',$val->id) }}"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center"> Data not found</td>
                                    </tr>
                            @endisset
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
        </div>
    </div>
    <form action="" method="post" id="form-delete">
        @method("DELETE")
        @csrf
    </form>
    
@endsection
@section('modal')
    {{-- Modal --}}    
    <div class="modal fade" id="addProduct" tabindex="-1" aria-labelledby="addProductLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-light">
                <div class="modal-header ">
                    <h5 class="modal-title" id="addProductLabel">Add Product</h5>
                    <button type="button" class="btn-close btn-light" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('product.store') }}" method="post">
                    @method('POST')
                    @csrf
                    <div class="modal-body bg-light">
                        @include('product.form-product')
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editProduct" tabindex="-1" aria-labelledby="editProductLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-light">
                <div class="modal-header ">
                    <h5 class="modal-title" id="editProductLabel">Edit Product</h5>
                    <button type="button" class="btn-close btn-light" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post">
                    @method('PATCH')
                    @csrf
                    <div class="modal-body bg-light">
                        @include('product.form-product')
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function(){
            "@if(session('stsAction'))"
                "@if(session('btnAction'))"
                    showAlert(
                        "{{ session('stsAction') }}",
                        "{{ session('iconAction') }}",
                        "{{ session('titleAction') }}",
                        "{{ session('btnAction') }}"
                    );
                "@else"
                    showAlert(
                        "{{ session('stsAction') }}",
                        "{{ session('iconAction') }}",
                        "{{ session('titleAction') }}",false
                    );

                "@endif"
            "@endif"
            const submit = [];
            const close = [];
            $(".btn-edit").click(function(e){
                var button = $(this);
                var id = button.data("id");
                
            });
            // $('div[class^="store-"]').not('.store-'+val);
            $("#nmStore").change(function(e){
                var val = $(this).val();
                var no = 1;
                $("tr[class^='store-']").not('.store-'+val).hide();
                $(".store-"+val).show();
                $(".store-"+val).each(function(){
                    $(this).find(".no").text(no++);
                });
                if(val==0){
                    $("tr[class^='store-']").show();
                    $("tr[class^='store-']").each(function(){
                        $(this).find(".no").text(no++);
                    });
                }
            });
            $(".btn-hps").click(function(e){
                var button = $(this);
                var id = button.data("id");
                var name = button.data("name");
                var href = button.data("href");
                var form = $("#form-delete");
                Swal.fire({
                    title: 'Are you sure?',
                    text: name+" will be deleted soon, you may not be able to restore it!, want to continue?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, continue!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.attr("action",href);
                        form.submit();
                    }
                });
            });
            $("#editProduct").on("show.bs.modal",function(e){
                var button = $(e.relatedTarget);
                var href = button.data("href");
                var id = button.data("id");
                var id_store = button.data("id_store");
                var name = button.data("name");
                var hrg = button.data("hrg");

                $(this).find("#store_id").val(id_store).change();
                $(this).find("#nm_product").val(name);
                $(this).find("#hrg_product").val(hrg);
                $(this).find("form").attr("action",href);

            });
        });
        function showAlertNotBtn(msg, icon, title) {
            Swal.fire({
                icon: icon,
                width: 600,
                title: title,
                text: msg,
                timer:2000,
                showConfirmButton: false
            });
        };
        function showAlert(msg, icon, title,btnConfirm) {
            if (btnConfirm) {
                Swal.fire({
                    icon: icon,
                    width: 600,
                    title: title,
                    text: msg,
                    showConfirmButton: btnConfirm
                });
            }else{
                showAlertNotBtn(msg, icon, title);
            }
        };
    </script>
@endsection