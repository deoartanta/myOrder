<div class="form-group">
    <label class="" for="nmStore">Product Name</label>
    <select class="form-select" id="store_id" name="store_id" required>
        <option value="" selected disabled>Choose stores name</option>
        @foreach ($stores as $val)
            <option value="{{ $val->id }}">{{ $val->nm_store }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label class="" for="nm_product">Product Name</label>
    <input type="text" name="nm_product" id="nm_product" class="form-control" placeholder="Enter product name" required>
</div>
<div class="form-group">
    <label class="" for="hrg_product" class="col-form-label">Price</label>
    <input type="text" name="hrg_product" id="hrg_product" class="form-control" placeholder="enter product price" required>
</div>