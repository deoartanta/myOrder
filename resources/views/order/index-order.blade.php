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
                                        <input type="hidden" name="hrg_total" id="hrg_total_hide" value="0">
                                        <input type="text"  id="hrg_total" class="form-control text-end" value="Rp. 0" required disabled>
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
                            <h3 class="text-end hrg_grandtotal">Rp. 0</h3>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-light mb-0">
                            <thead class="bg-secondary">
                                <tr>
                                    <th>#</th>
                                    <th>Customer</th>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                    <th>action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($orders)
                                    @php
                                        $subtotalorder = 0;
                                    @endphp
                                    @if($orders->orderdetails->count()!=0)
                                        @foreach ($orders->orderdetails->sortBy('nm_customer') as $item)
                                            @php
                                                $subtotalorder = $subtotalorder+$item->hrg_total;
                                            @endphp  
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->nm_customer }}</td>
                                                <td>{{ $item->product->nm_product }} x<span class="qty-">{{ $item->qty }}</span></td>
                                                <td>{{ sprintf('Rp. %s', number_format($item->product->hrg_product)) }}</td>
                                                <td>{{ sprintf('Rp. %s', number_format($item->hrg_total)) }}</td>
                                                <td>
                                                    <button type="button" class=" btn btn-danger btn-action btn-hps" title="Delete" data-id="{{ $item->id }}" data-name="{{ $item->nm_customer }}" data-href="{{ route('order.destroy',$item->id) }}"><i class="fas fa-trash"></i> Delete</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <th class="text-center" colspan="6">
                                                Data not found
                                            </th>
                                        </tr>
                                    @endif
                                @else
                                        <tr>
                                            <th class="text-center" colspan="6">
                                                Data not found
                                            </th>
                                        </tr>
                                @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <form action="{{ route('order.update',isset($orders)?$orders->id:0) }}" method="post" id="orderFormUpdate">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="hrg_grandtotal" id="hrg_grandtotal" value="{{ isset($subtotalorder)?$subtotalorder:0 }}">
                        <div class="row justify-content-start">
                            <div class="col-3">
                                <div class="row align-items-center my-1">
                                    <div class="col-4 align-self-start">
                                        <label for="discount" class="col-form-label">Discount format<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-8">
                                        <select class="form-select mb-3" id="discountFormat" name="formatDiscount" >
                                            <option selected disabled>Choose</option>
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
                                    <div class="col-8">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text label-ab">Rp. </span>
                                            <input type="number" class="form-control text-end" name="discount" id="discount" required value="0" disabled>
                                            <span class="input-group-text label-per d-none">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="row align-items-center my-1">
                                    <div class="col-4 align-self-start">
                                        <label for="min" class="col-form-label">Min Purchase<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-8">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">Rp. </span>
                                            <input type="number" name="min" id="min" class="form-control text-end" value="0" required disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center my-1">
                                    <div class="col-4 align-self-start">
                                        <label for="max" class="col-form-label">Max Discount <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-8">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">Rp. </span>
                                            <input type="number" class="form-control text-end" name="max" id="max" required value="0" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="row align-items-center my-1">
                                    <div class="col-4 align-self-start">
                                        <label for="max" class="col-form-label">Sub Total</label>
                                    </div>
                                    <div class="col-6">
                                        <input type="hidden" name="hrg_subtotal" id="hrg_subtotal_hide" value="{{ isset($subtotalorder)?$subtotalorder:0 }}">
                                        <input type="text" class="form-control text-end" id="hrg_subtotal" required value="{{ sprintf('Rp. %s',number_format(isset($subtotalorder)?$subtotalorder:0)) }}" disabled>
                                    </div>
                                </div>
                                <div class="row align-items-center my-1">
                                    <div class="col-4 align-self-start">
                                        <label for="max" class="col-form-label">Ongkir<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-8">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">Rp. </span>
                                            <input type="number" class="form-control text-end" id="ongkir" name="ongkir" required value="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="row align-items-center my-1">
                                    <div class="col-4 align-self-start">
                                        <label for="dis_total" class="col-form-label">Discount Total</label>
                                    </div>
                                    <div class="col-8">
                                        <input type="text" name="dis_total" id="dis_total" class="form-control text-end" value="Rp. 0" disabled>
                                    </div>
                                </div>
                                <button type="reset" class="btn btn-secondary mx-1">Cancel</button>
                                <button type="button" id="btn-submit" class="btn btn-primary mx-1">Submit</button>
                            </div>
                            <div class="col-6">
                                <small id="helpmax" class="text-muted"><span class="text-danger">*</span>Jika kosong anda dapat mengisinya dengan angka 0</small>
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
        const FORMAT_PRESENTASE = 1;
        const FORMAT_ABSOLUTE = 2;
        const FORMAT_NOT_USED = 0;
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
            var grandtotal1 = $("#hrg_subtotal_hide").val();
            $(".hrg_grandtotal").html("Rp. "+grandtotal1.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
// -------------------------------------Focus--------------------------------------\\
            //input Focus
            $("input[type=number]").focus(function(){
                var val = $(this).val();
                if(!val||val<1){
                    $(this).val(null);
                }
            });
            //input Focus Out
            $("#qty").focusout(function(){
                var qty = $(this).val();
                if(!qty){
                    $(this).val(0);
                }
            });

            $("input[type=number]").focusout(function(){
                var dis = $(this).val();
                if(!dis){
                    $(this).val(0);
                }
            });
            // input Key Up
            $("#qty").keyup(function(){
                var hrg = getHrg($("#nmProduct"));
                var hrg_total = $(this).val()*hrg;
                $("#hrg_total_hide").val(hrg_total);
                $("#hrg_total").val("Rp. "+hrg_total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
               
            });

            $("#discount").keyup(function(){
                $("#min").keyup();
            })

            $("#min").keyup(function(){
                var max = parseInt($("#max").val());
                var min = parseInt($(this).val());

                var val_dis = parseInt($("#discount").val());
                var subtotal = $("#hrg_subtotal_hide").val();
                var format = $("#discountFormat").val();
                var ongkir = parseInt($("#ongkir").val());

                var discount = caldiscount($("#discount"),format);
                var grandtotal = parseInt(subtotal) - discount;
                // console.log();
                if(!min){
                    min = 0;
                }
                if (!max) {
                    max = 0;
                }
                if(ongkir){
                    grandtotal += ongkir;
                }else{
                    ongkir = 0;
                }
                // console.log("Min ="+min+" Max ="+max+" Ongkir"+ongkir);
                if (parseInt(format)==FORMAT_PRESENTASE) {
                    if (min==0||subtotal>=min) {
                        // console.log("Format presentase subtotal ="+discount+" Min ="+min+" subtotal ="+subtotal);
                        if (max==0||(max>=discount)) {
                            // console.log("min = "+min+" Grand Total ="+grandtotal);
                            $("#hrg_grandtotal").val(grandtotal);
                            $("#dis_total").val("Rp. "+discount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                            $(".hrg_grandtotal").text("Rp. "+grandtotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        }else{
                            discount = max;
                            grandtotal = parseInt(subtotal) - discount;
                            // console.log("max = "+max+" Grand Total ="+grandtotal);
                            $("#hrg_grandtotal").val(grandtotal);
                            $("#dis_total").val("Rp. "+discount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                            $(".hrg_grandtotal").text("Rp. "+grandtotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        }
                    }else{
                        $("#hrg_grandtotal").val(grandtotal);
                        $("#dis_total").val("Rp. 0");
                        $(".hrg_grandtotal").text("Rp. "+subtotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    }
                }else if(parseInt(format)==FORMAT_ABSOLUTE){
                    if (subtotal>=min) {
                        console.log("Format Absolute subtotal ="+discount+" Min ="+min+" subtotal ="+subtotal);
                        $("#hrg_grandtotal").val(grandtotal);
                        $("#dis_total").val("Rp. "+discount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $(".hrg_grandtotal").text("Rp. "+grandtotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    }else{
                        $("#hrg_grandtotal").val(subtotal);
                        $("#dis_total").val("Rp. 0");
                        $(".hrg_grandtotal").text("Rp. "+subtotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    }
                }
                
            });

            $("#max").keyup(function(){
                $("#min").keyup();
            });
            $("#ongkir").keyup(function(){
                var min = parseInt($("#min").val());
                var ongkir = parseInt($(this).val());
                var format_disc = $("#discountFormat").val();
                var subtotal = parseInt($("#hrg_subtotal_hide").val());
                if (format_disc&&format_disc!=FORMAT_NOT_USED) {
                    if (min<=subtotal) {
                        $("#min").keyup();
                    }else{
                        if(ongkir){
                            subtotal += ongkir;
                        }
                        $("#hrg_grandtotal").val(subtotal);
                        $(".hrg_grandtotal").text("Rp. "+subtotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    }
                }else{
                    if(ongkir){
                        subtotal += ongkir;
                    }
                    $("#hrg_grandtotal").val(subtotal);
                    $(".hrg_grandtotal").text("Rp. "+subtotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }
            });

            $("#min").change(function(){
                $("#min").keyup();
            });

            $("#max").change(function(){
                $("#min").keyup();
            });
            
            $("#ongkir").change(function(){
                $(this).keyup();
            });

            // $('div[class^="store-"]').not('.store-'+val);
            //Selected Change
            $("#nmStore").change(function(e){
                var val = $(this).val();
                var no = 1;
                $("option[class^='store-']").not('.store-'+val).hide();
                $("option.store-"+val).show();
                $("#nmProduct").attr("disabled",false);
            });

            $("#nmProduct").change(function(e){
                var hrg = getHrg($(this));
                var total = $("#qty").val()*hrg;
                $("#hrg_total_hide").val(hrg);
                $("#hrg_total").val("Rp. "+total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            });

            $("#discount").change(function(){
                $("#min").keyup();
            });

            $("#discountFormat").change(function(e){
                var val = $(this).val();
                $("#discount").val(0);
                if(parseInt(val)==FORMAT_PRESENTASE){
                    $(".label-per").removeClass("d-none");
                    $(".label-ab").addClass("d-none");
                    $("#discount,#max,#min").attr("disabled",false);
                    $("#discount").attr("max",100);
                }else if(parseInt(val)==FORMAT_ABSOLUTE){
                    $(".label-per").addClass("d-none");
                    $(".label-ab").removeClass("d-none");
                    $("#discount,#min").attr("disabled",false);
                    $("#max").attr("disabled",true);
                    $("#discount").removeattr("max");
                }else{
                    $("#discount,#max,#min").attr("disabled",true);
                };
                // $("#subtotal").val("Rp. "+total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            });

            //Btn Click
            $(".btn-edit").click(function(e){
                var button = $(this);
                var id = button.data("id");
                
            });

            $("#btn-submit").click(function(e){
                var form = $("#orderFormUpdate");
                Swal.fire({
                    title: 'Confirm',
                    text: "Data will be saved soon, want to continue?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, continue!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
            $(".btn-hps").click(function(e){
                var button = $(this);
                var id = button.data("id");
                var name = button.data("name");
                var href = button.data("href");
                var form = $("#form-delete");
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Orders from "+name+" will be deleted soon, you may not be able to restore it!, want to continue?",
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
// -------------------------------------Modal--------------------------------------\\
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
        
// ------------------------------------Function------------------------------------\\
        function getHrg(_thisOption){
            var val = _thisOption.find("option:selected").text();
            var val_arr = val.split("@")[1].split(" ")[1].split(",");
            var hrg = parseInt(val_arr[0]+val_arr[1]);
            return hrg;
        }

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

        //Calculate Discount
        function caldiscount(_this,formatDiscount){
            var val_dis = _this.val();
            var subtotal = $("#hrg_subtotal_hide").val();
            if (val_dis) {
                if(parseInt(formatDiscount)==FORMAT_PRESENTASE){
                    if(val_dis>100){
                        val_dis = 100;
                        _this.val(val_dis);
                    }
                    discount = (parseInt(val_dis)/100)*subtotal;
                    return discount;
                }else if (parseInt(formatDiscount)==FORMAT_ABSOLUTE) {
                    discount = parseInt(val_dis);
                    return discount;
                }
            }else{
                return 0;
            }
        }
    </script>
@endsection