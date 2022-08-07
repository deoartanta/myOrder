@extends('layouts.app')

@section('title','Order')
@section('dropdown-order','active')
@section('create-order','active')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header border-bottom">
                    <h4>List @yield('title')</h4>
                </div>
                <form action="{{ route('order.store') }}" method="post">
                    @csrf
                    @method('POST')
                    <div class="card-body p-0">
                        <div class="row add-action m-3">
                            <div class="col-6">
                                <div class="row align-items-center my-3">
                                    <div class="col-3 align-self-start">
                                        <label for="order_cd" class="col-form-label">Order Code</label>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="order_cd" id="order_cd" class="form-control-plaintext" value="{{ $cd_order }}" aria-describedby="helpOrdercd" readonly>
                                    </div>
                                </div>
                                <div class="row align-items-center my-3">
                                    <div class="col-3 align-self-start">
                                        <label for="dateNow" class="col-form-label">Date</label>
                                    </div>
                                    <div class="col-6">
                                        <input type="date" name="dateNow" id="dateNow" class="form-control-plaintext" value="{{ date("Y-m-d") }}" aria-describedby="helpOrdercd" readonly>
                                    </div>
                                </div>
                                
                                <div class="row align-items-center my-3">
                                    <div class="col-3 align-self-start">
                                        <label for="nm_customer" class="col-form-label">Buyer's name<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="nm_customer" id="nm_customer" class="form-control" value="{{ old('nm_customer')?old('nm_customer'):Auth()->user()->name }}" aria-describedby="helpOrdernm" required>
                                        <small id="helpOrdernm" class="text-muted"><span class="text-danger">*</span> Anda dapat menggantinya dengan nama pembeli yang lain</small>
                                    </div>
                                </div>

                                <div class="row align-items-center my-3">
                                    <div class="col-3 align-self-start">
                                        <label for="nmStore" class="col-form-label">Store Name<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-6">
                                        <select class="form-select" id="nmStore" name="product_id">
                                            <option value="0" selected disabled >Choose stores</option>
                                            @foreach ($stores as $val)
                                                <option value="{{ $val->id }}">{{ $val->nm_store }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row align-items-center my-3">
                                    <div class="col-3 align-self-start">
                                        <label for="nmProduct" class="col-form-label">Product Name<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-6">
                                        <select class="form-select" id="nmProduct" name="product_id" disabled>
                                            <option selected disabled>Choose product</option>
                                            @foreach ($stores as $val)
                                                @foreach ($val->products as $item)
                                                    <option class="store-{{ $val->id }}" value="{{ $item->id }}">{{ "$item->nm_product @".sprintf('Rp. %s', number_format($item->hrg_product)) }}</option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row align-items-center my-3">
                                    <div class="col-3 align-self-start">
                                        <label for="qty" class="col-form-label">Qty<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-6">
                                        <input type="number" name="qty" id="qty" class="form-control text-end" value="0" min="1" required>
                                    </div>
                                </div>
                                <div class="row align-items-center my-1">
                                    <div class="col-3 align-self-start">
                                        <label for="subtotal" class="col-form-label">Sub Total<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-6">
                                        <input type="hidden" name="hrg_subtotal" id="hrg_subtotal" value="0">
                                        <input type="text"  id="subtotal" class="form-control text-end" value="Rp. 0" required disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <button type="reset" class="btn btn-secondary mx-1">Reset</button>
                        <button type="submit" class="btn btn-success mx-1">order</button>
                    </div>
                </form>
            </div>
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="d-flex align-items-center justify-content-end mb-3">
                        <h3 class="">Grand Total</h3>
                        <div class="bg-secondary ms-2 p-2" style="min-width: 15rem;">
                            <h3 class="text-end">Rp. 0</h3>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-light mb-0">
                            <thead class="bg-secondary">
                                <tr>
                                    <th>#</th>
                                    <th>Order Code</th>
                                    <th>Customer</th>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Sub Total</th>
                                    <th>action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($orders)
                                    @foreach ($orders as $val)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $val->order_cd }}</td>
                                            <td>{{ $val->orderdetails->first()->nm_customer }}</td>
                                            <td>{{ $val->orderdetails->first()->product->nm_product }} x<span class="qty-">{{ $val->orderdetails->first()->qty }}</span></td>
                                            <td>{{ $val->orderdetails->first()->product->hrg_product }}</td>
                                            <td>{{ sprintf('Rp. %s', number_format($val->orderdetails->first()->hrg_total)) }}</td>
                                            <td>
                                                
                                            </td>
                                        </tr>
                                        @foreach ($val->orderdetails as $item)
                                            @if ($val->orderdetails->first()->id!=$item->id)    
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td>{{ $item->nm_customer }}</td>
                                                    <td>{{ $item->product->nm_product }} x<span class="qty-">{{ $item->qty }}</span></td>
                                                    <td>{{ sprintf('Rp. %s', number_format($item->hrg_total)) }}</td>
                                                    <td>{{ sprintf('Rp. %s', number_format($item->hrg_total)) }}</td>
                                                    <td>

                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endforeach
                                @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <form action="{{ route('order.update', 1) }}" method="post">
                        @csrf
                        @method('PATCH')
                        <div class="row justify-content-start">
                            <div class="col-5">
                                <div class="row align-items-center my-1">
                                    <div class="col-4 align-self-start">
                                        <label for="discount" class="col-form-label">Discount format<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-6">
                                        <select class="form-select" id="discountFormat"  >
                                            <option selected disabled>Choose discount format</option>
                                            <option value="1">Presentase(%)</option>
                                            <option value="2">Absolute(Rp. 0)</option>
                                            <option value="0">No discount</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row align-items-center my-1">
                                    <div class="col-4 align-self-start">
                                        <label for="min" class="col-form-label">Discount<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-6">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text label-ab">Rp. </span>
                                            <input type="number" class="form-control text-end" name="discount" id="discount" required value="0">
                                            <span class="input-group-text label-per ">% </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="row align-items-center my-1">
                                    <div class="col-4 align-self-start">
                                        <label for="min" class="col-form-label">Min Purchase<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-6">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">Rp. </span>
                                            <input type="number" name="min" id="min" class="form-control text-end" value="0" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center my-1">
                                    <div class="col-4 align-self-start">
                                        <label for="max" class="col-form-label">Max Discount <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-6">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">Rp. </span>
                                            <input type="number" class="form-control text-end" id="max" required aria-describedby="helpmin" value="0">
                                        </div>
                                        <small id="helpmax" class="text-muted"><span class="text-danger">*</span>Jika kosong anda dapat mengisinya dengan 0</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <button type="reset" class="btn btn-secondary mx-1">Cancel</button>
                                <button type="submit" class="btn btn-primary mx-1">Submit</button>
                            </div>
                        </div>
                    </form>
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
            $("#qty").focus(function(){
                var qty = $(this).val();
                if(!qty||qty<1){
                    $(this).val(null);
                }
            })
            $("#qty").focusout(function(){
                var qty = $(this).val();
                if(!qty){
                    $(this).val(0);
                }else{
                    var totalhrg = $("#hrg_subtotal").val(); 
                    $("#subtotal").val("Rp. "+totalhrg.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }
            })
            $("#qty").keyup(function(){
                var val = $("#nmProduct option:selected").text();
                var val_arr = val.split("@")[1].split(" ")[1].split(",");
                var hrg = parseInt(val_arr[0]+val_arr[1]);
                $("#hrg_subtotal").val($(this).val()*hrg);
                $("#subtotal").val($(this).val()*hrg);
               
            })
            $(".btn-edit").click(function(e){
                var button = $(this);
                var id = button.data("id");
                
            });
            // $('div[class^="store-"]').not('.store-'+val);
            $("#nmProduct").change(function(e){
                var val = $(this).find("option:selected").text();
                var val_arr = val.split("@")[1].split(" ")[1].split(",");
                var hrg = parseInt(val_arr[0]+val_arr[1]);
                var total = $("#qty").val()*hrg;
                $("#hrg_subtotal").val(hrg);
                $("#subtotal").val("Rp. "+total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            });
            $("#nmStore").change(function(e){
                var val = $(this).val();
                var no = 1;
                $("option[class^='store-']").not('.store-'+val).hide();
                $("option.store-"+val).show();
                $("#nmProduct").attr("disabled",false);
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