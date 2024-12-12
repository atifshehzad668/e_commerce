@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Product</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="products.html" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <form action="" method="put" enctype="multipart/form-data" name="ProductForm" id="ProductForm">
            @csrf
            @method('PUT')
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" value="{{ $product->id }}" name="product_id">
                                        <div class="mb-3">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" value="{{ $product->name }}" id="title"
                                                class="form-control" placeholder="Title">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="slug">Slug</label>
                                            <input type="text" value="{{ $product->slug }}" readonly name="slug"
                                                id="slug" class="form-control" placeholder="Slug">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description">Short Description</label>
                                            <textarea name="short_description" value="{{ $product->short_description }}" id="description" cols="30"
                                                rows="10" class="summernote" placeholder="Short Description"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description">Description</label>
                                            <textarea name="description" value="{{ $product->description }}" id="description" cols="30" rows="10"
                                                class="summernote" placeholder="Description"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description">Shipping And Returns</label>
                                            <textarea name="shipping_returns" value="{{ $product->shipping_returns }}" id="description" cols="30"
                                                rows="10" class="summernote" placeholder="Shipping And Returns"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="image">Product Image</label>
                                            <div id="app">

                                                <input type="file" name="images[]" id="images" accept="image/*"
                                                    class="form-control" multiple>
                                                {{-- <file-uploader :unlimited="true"
                                                    :media="{{ $product->getMedia('images') }}" collection="images"
                                                    label="CNIC FRONT IMAGE"
                                                    accept="image/jpeg,image/png,image/jpg,image/gif"
                                                    notes="Supported types: jpeg, png,jpg">
                                                </file-uploader> --}}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Pricing</h2>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="price">Price</label>
                                            <input type="text" value="{{ $product->price }}" name="price"
                                                id="price" class="form-control" placeholder="Price">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="compare_price">Compare at Price</label>
                                            <input type="text" value="{{ $product->compate_price }}"
                                                name="compare_price" id="compare_price" class="form-control"
                                                placeholder="Compare Price">
                                            <p class="text-muted mt-3">
                                                To show a reduced price, move the product’s original price into Compare at
                                                price. Enter a lower value into Price.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Inventory</h2>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sku">SKU (Stock Keeping Unit)</label>
                                            <input type="text" value="{{ $product->sku }}" name="sku"
                                                id="sku" class="form-control" placeholder="sku">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="barcode">Barcode</label>
                                            <input type="text" value="{{ $product->barcode }}" name="barcode"
                                                id="barcode" class="form-control" placeholder="Barcode">
                                            <p class="error"></p>
                                        </div>
                                    </div>




                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="related_products">Related product</label>
                                            <div class="mb-3">
                                                <select multiple name="related_products[]" id="related_products"
                                                    class="form-control related_products w-100">
                                                    @if (!empty($related_products))
                                                        @foreach ($related_products as $relproduct)
                                                            <option value="{{ $relproduct->id }}" selected>
                                                                {{ $relproduct->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="hidden" value="No" name="track_qty">
                                                <input class="custom-control-input" type="checkbox" value="Yes"
                                                    id="track_qty"
                                                    {{ isset($product) && $product->track_qty == 'YES' ? 'checked' : '' }}
                                                    name="track_qty" checked>
                                                <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                                <p class="error"></p>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <input type="number" min="0" value="{{ $product->qty }}"
                                                name="qty" id="qty" class="form-control" placeholder="Qty">
                                            <p class="error"></p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Product status</h2>
                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">


                                        <option {{ $product->status == 1 ? 'selected' : '' }} value="1">Active
                                        </option>
                                        <option {{ $product->status == 0 ? 'selected' : '' }} value="0">Block
                                        </option>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h2 class="h4  mb-3">Product category</h2>
                                <div class="mb-3">
                                    <label for="category">Category</label>
                                    <select name="category" id="category" class="form-control">

                                        @if ($categories->isNotEmpty())
                                            {
                                            @foreach ($categories as $category)
                                                <option {{ $product->category_id == $category->id ? 'selected' : '' }}
                                                    value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                            }
                                        @endif
                                    </select>
                                    <p class="error"></p>
                                </div>
                                <div class="mb-3">
                                    <label for="sub_category">Sub category</label>
                                    <select name="sub_category" id="sub_category" class="form-control">
                                        @if ($sub_categories->isNotEmpty())
                                            {
                                            @foreach ($sub_categories as $subcategory)
                                                <option
                                                    {{ $product->sub_category_id == $subcategory->id ? 'selected' : '' }}
                                                    value="{{ $subcategory->id }}">
                                                    {{ $subcategory->name }}</option>
                                            @endforeach
                                            }
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Product brand</h2>
                                <div class="mb-3">
                                    <select name="brand" id="brand" class="form-control">
                                        @foreach ($brands as $brand)
                                            <option {{ $product->brand_id == $brand->id ? 'selected' : '' }}
                                                value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                {{-- <pre>{{ var_dump($product->is_featured) }}</pre> --}}
                                <h2 class="h4 mb-3">Featured product</h2>
                                <div class="mb-3">
                                    <select name="is_featured" id="is_featured" class="form-control">
                                        <option value="No"
                                            {{ isset($product) && strtoupper($product->is_featured) == 'NO' ? 'selected' : '' }}>
                                            No</option>
                                        <option value="Yes"
                                            {{ isset($product) && strtoupper($product->is_featured) == 'YES' ? 'selected' : '' }}>
                                            Yes</option>
                                    </select>


                                    <p class="error"></p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </div>
        </form>
        <!-- /.card -->
    </section>
@endsection

@section('customjs')
    <script>
        $('.related_products').select2({
            ajax: {
                url: '{{ route('products.getProducts') }}',
                dataType: 'json',
                tags: true,
                multiple: true,
                minimumInputLength: 3,
                processResults: function(data) {
                    return {
                        results: data.tags
                    };
                }
            }
        });
        // $("#ProductForm").submit(function(event) {
        //     event.preventDefault();
        //     var formArray = $(this).serializeArray();
        //     $("button[type='submit']").prop('disabled', true);
        //     $.ajax({
        //         url: '{{ route('products.update', $product->id) }}',
        //         type: 'put',
        //         data: formArray,
        //         dataType: 'json',
        //         success: function(response) {
        //             $("button[type='submit']").prop('disabled', false);
        //             if (response['status'] == true) {
        //                 $(".error").removeClass('invalid-feedback').html('');
        //                 $("input[type='text'],select,input[type='number']").removeClass('is-invalid');
        //                 window.location.href = "{{ route('products.index') }}";

        //             } else {
        //                 var errors = response['errors'];

        //                 $(".error").removeClass('invalid-feedback').html('');
        //                 $("input[type='text'],select,input[type='number']").removeClass('is-invalid');
        //                 $.each(errors, function(key, value) {
        //                     $('#' + key).addClass('is-invalid')
        //                         .siblings('p')
        //                         .html(value);
        //                 });


        //             }
        //         },
        //         error: function() {
        //             console.log("Something Went Wrong");
        //         }
        //     });
        // });
        $("#ProductForm").submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            $("button[type='submit']").prop('disabled', true);
            $.ajax({
                url: '{{ route('products.update', $product->id) }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    $("button[type='submit']").prop('disabled', false);
                    if (response['status'] == true) {
                        $(".error").removeClass('invalid-feedback').html('');
                        $("input[type='text'],select,input[type='number']").removeClass('is-invalid');
                        window.location.href = "{{ route('products.index') }}";
                    } else {
                        var errors = response['errors'];
                        $(".error").removeClass('invalid-feedback').html('');
                        $("input[type='text'],select,input[type='number']").removeClass('is-invalid');
                        $.each(errors, function(key, value) {
                            $('#' + key).addClass('is-invalid').siblings('p').html(value);
                        });
                    }
                },
                error: function() {
                    console.log("Something Went Wrong");
                }
            });
        });



        $("#title").change(function() {
            let element = $(this);
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: '{{ route('getSlug') }}',
                type: 'GET',
                data: {
                    title: element.val()
                },
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);
                    if (response.status) {
                        $("#slug").val(response.slug);
                    }
                }
            });
        });

        $("#category").change(function() {
            var category_id = $(this).val();
            $.ajax({
                url: '{{ route('product-subcategories.index') }}',
                type: 'get',
                data: {
                    category_id: category_id
                },
                dataType: 'json',
                success: function(response) {
                    $("#sub_category").find("option").not(":first").remove();
                    $.each(response['subCategories'], function(key, item) {
                        $("#sub_category").append(
                            `<option value='${item.id}'>${item.name}</option>`)
                    });
                },
                error: function() {
                    console.log("Something Went Wrong");
                }
            });
        });
    </script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-file-uploader"></script>
    <script>
        new Vue({
            el: '#app'
        })
    </script> --}}
@endsection
