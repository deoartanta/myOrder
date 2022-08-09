@extends('layouts.app')

@section('title','Bills')
@section('dropdown-order','active')
@section('split-bill','active')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
            <div class="card-header">
                <h4>List @yield('title')</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th class="text-uppercase">ID ORDER</th>
                            <th class="text-uppercase">Total Item</th>
                            <th class="text-uppercase">Sub Total</th>
                            <th class="text-uppercase">used discount</th>
                            <th class="text-uppercase">grand total</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            @isset($orders)
                                @if ($orders->count()!=0)
                                    @foreach ($orders as $val)
                                        <tr>
                                            <td class="no">{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="content-table-{{ $val->id }}">
                                                    {{ $val->order_cd }}
                                                </div>
                                            </td>
                                            <td>
                                                {{ $val->item_total }} Item
                                            </td>
                                            <td>
                                                {{ sprintf('Rp. %s', number_format($val->hrg_subtotal)) }}
                                            </td>
                                            <td>
                                                {{ $val->terms_discount	 }}
                                            </td>
                                            <td>
                                                {{ sprintf('Rp. %s', number_format($val->hrg_grandtotal)) }}
                                            </td>
                                            <td style="min-width: 150px">
                                                <a href="{{ route('split-bill.show',$val->order_cd) }}" class=" btn btn-primary mr-1" title="Calculate">
                                                    Split Bill <i class="fas fa-arrow-right"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center"> Data not found</td>
                                    </tr>
                                @endif
                            @else
                                    <tr>
                                        <td colspan="7" class="text-center"> Data not found</td>
                                    </tr>
                            @endisset
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
        </div>
        @isset($bills)
            <div class="col-12">
                <div class="card">
                <div class="card-header">
                    <h4>Split Bill</h4>
                </div>
                <div class="card-body p-0">
                    <div class="row add-action m-3">
                        <div class="col-6">
                            <div class="row align-items-center my-3">
                                <div class="col-3 align-self-start">
                                    <label for="order_cd" class="col-form-label">Order Code</label>
                                </div>
                                <div class="col-6">
                                    <input type="text" name="order_cd" id="order_cd" class="form-control" value="{{ $order->order_cd }}" aria-describedby="helpOrdercd" readonly>
                                </div>
                            </div>
                            <div class="row align-items-center my-3">
                                <div class="col-3 align-self-start">
                                    <label for="dateNow" class="col-form-label">Date</label>
                                </div>
                                <div class="col-6">
                                    <input type="date" id="dateNow" class="form-control" value="{{ $updated_at }}" aria-describedby="helpOrdercd" readonly>
                                </div>
                            </div>
                            <div class="row align-items-center my-3">
                                <div class="col-3 align-self-start">
                                    <label for="dateNow" class="col-form-label">Used Discount</label>
                                </div>
                                <div class="col-6">
                                    <p id="my-textarea" class="form-control-plaintext">{{ $order->terms_discount }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row align-items-center my-3">
                                <div class="col-3 align-self-start">
                                    <label for="order_cd" class="col-form-label">Total Item</label>
                                </div>
                                <div class="col-6">
                                    <input type="text" name="order_cd" id="order_cd" class="form-control" value="{{ $order->item_total }} Item" aria-describedby="helpOrdercd" readonly>
                                </div>
                            </div>
                            <div class="row align-items-center my-3">
                                <div class="col-3 align-self-start">
                                    <label for="subtotalshow" class="col-form-label">Sub Total</label>
                                </div>
                                <div class="col-6">
                                    <input type="text" id="subtotalshow" class="form-control" value="{{ sprintf('Rp. %s', number_format($val->hrg_subtotal)) }}" aria-describedby="helpOrdercd" readonly>
                                </div>
                            </div>
                            <div class="row align-items-center my-3">
                                <div class="col-3 align-self-start">
                                    <label for="gradtotalshow" class="col-form-label">Grand Total</label>
                                </div>
                                <div class="col-6">
                                    <input type="text" id="gradtotalshow" class="form-control" value="{{ sprintf('Rp. %s', number_format($val->hrg_grandtotal)) }}" aria-describedby="helpOrdercd" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-uppercase">Name customer</th>
                                <th class="text-uppercase">Total Item</th>
                                <th class="text-uppercase">Split bill</th>
                            </tr>
                            </thead>
                            <tbody>
                                @isset($order)
                                    @php
                                        $nm_customer = "";
                                        $key_i = -1;
                                        $qty = 0;
                                    @endphp     
                                    @foreach ($bills as $val)
                                        <tr>
                                            <td class="no">{{ $loop->iteration }}</td>
                                            <td>
                                                {{ $val->orderdetail->nm_customer }}
                                            </td>
                                            <td>
                                                {{-- {{ $val->qty }} Item --}}
                                            </td>
                                            <td>
                                                {{ sprintf('Rp. %s', number_format($val->bill_total)) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                        <tr>
                                            <td colspan="7" class="text-center"> Data not found</td>
                                        </tr>
                                @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
            </div>
        @endisset
    </div>
    <form action="" method="post" id="form-delete">
        @method("DELETE")
        @csrf
    </form>
    
@endsection
@section('modal')
    {{-- Modal --}}    
    
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