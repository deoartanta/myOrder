@extends('layouts.app')

@section('title','Stores')
@section('store','active')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
            <div class="card-header">
                <h4>List @yield('title')</h4>
            </div>
            <div class="card-body p-0">
                <div class="add-action m-3">
                    <form action="{{ route('store.store') }}" method="post">
                        @csrf
                        @method('Post')
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="inputPassword6" class="col-form-label">Store Name</label>
                            </div>
                            <div class="col-auto">
                                <input type="text" id="nm_store" name="nm_store" class="form-control" placeholder="Enter store name" required>
                            </div>
                            <div class="col-auto">
                                <label for="address" class="col-form-label">Store Address</label>
                            </div>
                            <div class="col-auto">
                                <input type="text" id="address" name="address" class="form-control" placeholder="Enter the address of this store" required>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-success">
                                    <b>ADD</b>
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Store Name</th>
                            <th>Store Address</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @isset($stores)
                                @foreach ($stores as $val)
                                    <tr>
                                        <form action="{{ route('store.update',$val->id) }}" id="form-{{ $val->id }}" method="post">
                                            @csrf
                                            @method('PATCH')
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="content-table-{{ $val->id }}">
                                                    {{ $val->nm_store }}
                                                    <div class="table-links">
                                                        Update at {{ date('d F Y, h:i A',strtotime($val->updated_at)) }}
                                                        <div class="bullet"></div>
                                                        <a href="#">{{ $val->products->count() }} Products</a>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-0 d-none" id="name{{ $val->id }}">
                                                    <input type="text" class="form-control" value="{{ $val->nm_store }}" name="nm_store" placeholder="Enter store name" required autofocus>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="content-table-{{ $val->id }}">
                                                    {{ $val->address }}
                                                </div>
                                                <div class="form-group mb-0 d-none" id="address{{ $val->id }}">
                                                    <input type="text" class="form-control" name="address" value="{{ $val->address }}" placeholder="" required autofocus>
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" class=" btn btn-primary btn-action mr-1 btn-edit" title="Edit" data-submit="false" data-id="{{ $val->id }}"><i class="fas fa-pencil-alt"></i></button>
                                                <button type="button" class=" btn btn-danger btn-action btn-hps" title="Delete" data-id="{{ $val->id }}" data-name="{{ $val->nm_store }}" data-href="{{ route('store.destroy',$val->id) }}"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </form>
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
                $(".content-table-"+id).addClass("d-none");
                $("div#name"+id).removeClass("d-none");
                $("div#address"+id).removeClass("d-none");
                button.html("<i class='fas fa-arrow-right m-0'></i>").attr("title","Submit");
                button.parent().find(".btn-hps").html("<b class='m-0'>X</b>").attr("title","Close");
                close[id] = true;
                submitForm(id,button);
            });
            function submitForm(id,_this){
                var form = $("#form-"+id);
                if (submit[id]) {
                    form.submit();
                    // $(".btn-hps").click();
                    _this.addClass("disabled");
                }else{
                    submit[id] = true;
                }
            };
            $(".btn-hps").click(function(e){
                var button = $(this);
                var id = button.data("id");
                var name = button.data("name");
                var href = button.data("href");
                var form = $("#form-delete");
                if (close[id]) {
                    $(".content-table-"+id).removeClass("d-none");
                    $("div#name"+id).addClass("d-none");
                    $("div#address"+id).addClass("d-none");
                    submit[id] = false;
                    close[id] = false;
                    button.html("<i class='fas fa-trash'></i>").attr("title","Delete");
                    button.attr("data-close","false");
                    button.parent().find(".btn-edit").html("<i class='fas fa-pencil-alt'></i>").attr("title","Edit");
                    button.parent().find(".btn-edit").attr("data-submit","false");
                }else{
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
                    })
                }
            })
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