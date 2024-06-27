@extends('front.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                    <li class="breadcrumb-item active">Shop</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-6 pt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3 sidebar">
                    <div class="sub-title">
                        <h2>Categories</h3>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="accordion accordion-flush" id="accordionExample">
                                @foreach ($categories as $category)
                                    <div class="accordion-item">
                                        @if ($category->subCategories->isNotEmpty())
                                            <h2 class="accordion-header" id="heading{{ $category->id }}">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapse{{ $category->id }}"
                                                    aria-expanded="false" aria-controls="collapse{{ $category->id }}">
                                                    {{ $category->name }}
                                                </button>
                                            </h2>
                                            <div id="collapse{{ $category->id }}"
                                                class="accordion-collapse collapse {{ $categorySelected == $category->id ? 'show' : '' }}"
                                                aria-labelledby="heading{{ $category->id }}"
                                                data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="navbar-nav">
                                                        @foreach ($category->subCategories as $subCategory)
                                                            <a href="{{ route('front.shop', [$category->slug, $subCategory->slug]) }}"
                                                                class="nav-item nav-link {{ $subCategorySelected == $subCategory->id ? 'text-primary' : '' }}">{{ $subCategory->name }}</a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <a href="{{ route('front.shop', $category->slug) }}"
                                                class="nav-item nav-link ">{{ $category->name }}</a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="sub-title mt-5">


                        <h2>Brand</h3>
                    </div>

                    <div class="card">
                        @if ($brands->isNotEmpty())
                            @foreach ($brands as $brand)
                                <div class="card-body">
                                    <div class="form-check mb-2">
                                        <input {{ in_array($brand->id, $brandsArray) ? 'checked' : '' }}
                                            class="form-check-input brand-label" name="brand[]" type="checkbox"
                                            value="{{ $brand->id }}" id="brand-{{ $brand->id }}">
                                        <label class="form-check-label" for="brand-{{ $brand->id }}">
                                            {{ $brand->name }}
                                        </label>
                                    </div>

                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="sub-title mt-5">
                        <h2>Price</h3>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="form-check mb-2">
                                <input type="text" class="js-range-slider" name="my_range" value="" />

                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row pb-3">
                        <div class="col-12 pb-1">
                            <div class="d-flex align-items-center justify-content-end mb-4">
                                <div class="ml-2">
                                    {{-- <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-light dropdown-toggle"
                                            data-bs-toggle="dropdown">Sorting</button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#">Latest</a>
                                            <a class="dropdown-item" href="#">Price High</a>
                                            <a class="dropdown-item" href="#">Price Low</a>
                                        </div>
                                    </div> --}}
                                    <select name="sort" id="sort" class="form-control">
                                        <option value="latest" {{ $sort == 'latest' ? 'selected' : '' }}>latest</option>
                                        <option value="price_desc" {{ $sort == 'price_desc' ? 'selected' : '' }}>Price High
                                        </option>
                                        <option value="price_asc" {{ $sort == 'price_asc' ? 'selected' : '' }}>Price Low
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        @if ($products->isNotEmpty())
                            @foreach ($products as $product)
                                <div class="col-md-4">

                                    <div class="card product-card">
                                        <div class="product-image position-relative">
                                            <a href="{{ route('front.product', $product->slug) }}" class="product-img">
                                                @if ($product->getMedia('images')->isNotEmpty())
                                                    <img style="height: 200px; width: 100%"
                                                        src="{{ $product->getFirstMediaUrl('images') }}"
                                                        alt="{{ $product->name }}">
                                                @else
                                                    <img src="{{ asset('images/default-product-image.jpg') }}"
                                                        alt="Default Image">
                                                @endif
                                            </a>
                                            <a class="whishlist" href="222"><i class="far fa-heart"></i></a>

                                            <div class="product-action">
                                                <a class="btn btn-dark" href="#">
                                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-body text-center mt-3">
                                            <a class="h6 link" href="product.php">{{ $product->name }}</a>
                                            <div class="price mt-2">
                                                <span class="h5"><strong>{{ $product->price }}</strong></span>
                                                @if ($product->compare_price > 0)
                                                    <span
                                                        class="h6 text-underline"><del>{{ $product->compare_price }}</del></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        <div class="col-md-12 pt-5">
                            {{ $products->WithQueryString()->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@section('customJs')
    <script>
        $(document).ready(function() {
            // Initialize range slider
            var rangeSlider = $(".js-range-slider").ionRangeSlider({
                type: "double",
                min: 0,
                max: 1000,
                from: {{ $price_min }},
                step: 10,
                to: {{ $price_max }},
                skin: "round",
                max_postfix: "+",
                prefix: "$",
                onFinish: function(data) {
                    apply_filter();
                }
            }).data("ionRangeSlider");

            $(".brand-label").change(function() {
                apply_filter();
            });

            $("#sort").change(function() {
                apply_filter();
            });

            function apply_filter() {
                var brands = [];

                $(".brand-label").each(function() {
                    if ($(this).is(":checked")) {
                        brands.push($(this).val());
                    }
                });

                // Get the slider values
                var price_min = rangeSlider.result.from;
                var price_max = rangeSlider.result.to;

                // Example: Log brands array to console
                console.log(brands.toString());

                var url = '{{ url()->current() }}?';
                // price range filter
                url += 'price_min=' + price_min + '&price_max=' + price_max;
                //brand filter
                if (brands.length > 0) {
                    url += '&brand=' + brands.toString();
                }

                //sorting filter
                url += '&sort=' + $("#sort").val(); // Fixed the typo here

                window.location.href = url;
            }
        });
    </script>
@endsection
