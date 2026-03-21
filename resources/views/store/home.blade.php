@extends('layouts.store')
@section('content')
@php
    $fallbackCategoryImages = [
        'electronica' => 'wolmart/assets/images/demos/demo1/categories/1-1.jpg',
        'moda' => 'wolmart/assets/images/demos/demo1/categories/1-2.jpg',
        'hogar' => 'wolmart/assets/images/demos/demo1/categories/2-1.jpg',
        'deportes' => 'wolmart/assets/images/demos/demo1/categories/2-2.jpg',
        'belleza' => 'wolmart/assets/images/demos/demo1/categories/2-3.jpg',
        'accesorios' => 'wolmart/assets/images/demos/demo1/categories/2-4.jpg',
        'default' => 'wolmart/assets/images/demos/demo1/categories/2-5.jpg',
    ];
@endphp
<main class="main">
    <section class="intro-section">
        <div class="swiper-container swiper-theme nav-inner swiper-nav-lg animation-slider mb-2" data-swiper-options='{"slidesPerView":1}'>
            <div class="swiper-wrapper row cols-1 gutter-no">
                <div class="swiper-slide banner banner-fixed intro-slide intro-slide1" style="background-image: url({{ asset('wolmart/assets/images/demos/demo1/sliders/slide-1.jpg') }}); background-color: #f3ddd0;">
                    <div class="container"><div class="banner-content y-50"><h5 class="banner-subtitle hero-eyebrow font-weight-normal text-default">Compra con cercanía y confianza</h5><h3 class="banner-title font-weight-bolder ls-25">La Tienda de Mi Abue <br> ya está en línea</h3><p class="hero-description">Una experiencia de compra cálida y ordenada para catálogo, carrito y backoffice.</p><a href="{{ route('store.shop') }}" class="btn btn-dark btn-rounded hero-cta">Comprar ahora</a></div></div>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="row category-banner-wrapper appear-animate pt-6 pb-8">
            @foreach($categories as $category)
                @php
                    $categorySlug = $category->slug ?: \Illuminate\Support\Str::slug($category->name);
                    $categoryImagePath = $category->image;

                    if (! $categoryImagePath || ! file_exists(public_path($categoryImagePath))) {
                        $categoryImagePath = $fallbackCategoryImages[$categorySlug] ?? $fallbackCategoryImages['default'];
                    }
                @endphp
                <div class="col-md-4 mb-4">
                    <div class="cat-banner banner banner-fixed br-xs">
                        <figure><img src="{{ asset($categoryImagePath) }}" alt="{{ $category->name }}" style="height:320px; width:100%; object-fit:cover;"></figure>
                        <div class="banner-content"><h4 class="banner-subtitle text-uppercase font-weight-bold">{{ $category->products_count }} productos</h4><h3 class="banner-title text-white">{{ $category->name }}</h3><a href="{{ route('store.shop', ['category' => $category->slug]) }}" class="btn btn-white btn-link btn-underline">Ver categoría</a></div>
                    </div>
                </div>
            @endforeach
        </div>

        <h2 class="title title-underline mb-4">Productos destacados</h2>
        <div class="row cols-xl-4 cols-md-3 cols-2">
            @foreach($featuredProducts as $product)
                @include('store.partials.product-card', ['product' => $product])
            @endforeach
        </div>

        <h2 class="title title-underline mb-4 mt-10">Últimos productos</h2>
        <div class="row cols-xl-4 cols-md-3 cols-2">
            @foreach($latestProducts as $product)
                @include('store.partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</main>
@endsection
