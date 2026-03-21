@extends('layouts.store')
@section('content')
<main class="main shop-page">
    <div class="page-content mb-10">
        <div class="container shop-page-container">
            <div class="shop-default-banner shop-hero banner d-flex align-items-center mb-5 br-xs" style="background-image: url({{ asset('wolmart/assets/images/demos/demo11/banner/shop-banner.jpg') }}); background-color: #e9c2ab;">
                <div class="banner-content"><h4 class="banner-subtitle font-weight-bolder">Catálogo</h4><h3 class="banner-title text-white font-weight-bolder ls-25">Portal de venta</h3></div>
            </div>
            <div class="row gutter-lg main-content-wrap shop-layout">
                <aside class="sidebar shop-sidebar sticky-sidebar-wrapper sidebar-fixed">
                    <div class="sidebar-overlay"></div>
                    <a class="sidebar-close" href="#"><i class="close-icon"></i></a>
                    <div class="sidebar-content scrollable">
                        <div class="sticky-sidebar">
                            <div class="filter-actions"><label>Filtros</label></div>
                            <div class="widget widget-collapsible"><h3 class="widget-title"><span>Categorías</span></h3><ul class="widget-body filter-items search-ul shop-category-list">
                                @foreach($categories as $category)
                                    <li><a href="{{ route('store.shop', ['category' => $category->slug]) }}">{{ $category->name }}</a></li>
                                @endforeach
                            </ul></div>
                        </div>
                    </div>
                </aside>
                <div class="main-content shop-results">
                    <nav class="toolbox sticky-toolbox sticky-content fix-top shop-toolbar"><div class="toolbox-left"><div class="toolbox-item toolbox-sort select-box text-dark"><span>{{ $products->total() }} productos</span></div></div></nav>
                    <div class="row cols-2 cols-sm-3 cols-md-3 cols-lg-4 product-wrapper shop-grid">
                        @foreach($products as $product)
                            @include('store.partials.product-card', ['product' => $product])
                        @endforeach
                    </div>
                    <div class="toolbox toolbox-pagination justify-content-between shop-pagination">{{ $products->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
