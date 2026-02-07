@extends('frontend.default.layouts.master')

@section('title')
    {{ localize('Home') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('contents')
    <!--hero section start-->
    @include('frontend.' . getTheme() . '.pages.partials.home.hero')
    <!--hero section end-->

    <!--category section , one and two banner start-->
    @include('frontend.' . getTheme() . '.pages.partials.home.category')
    <!--category section end-->


      {{-- Featured Product  collection section with sidebar  --}}
    @include('frontend.' . getTheme() . '.pages.partials.home.featureCategories')

    {{-- Featured collection section with banner image Three and Four  --}}
    @include('frontend.' . getTheme() . '.pages.partials.home.offerBanner')
     
       <!--featured feature prodcution collection-->
    @include('frontend.' . getTheme() . '.pages.partials.home.featuredCollection')


    <!--featured products section start-->
    {{-- @include('frontend.' . getTheme() . '.pages.partials.home.featuredProducts') --}}
    <!--featured products section end-->

    <!--Best Deals Products-->
    @include('frontend.' . getTheme() . '.pages.partials.home.bestDeals')

    <!--Why Choose Us-->
    @include('frontend.' . getTheme() . '.pages.partials.home.whyChooseUs')

    <!--Client Feedback-->
    @include('frontend.' . getTheme() . '.pages.partials.home.feedback')

    <!--banner section start for banner Seven -->
    @include('frontend.' . getTheme() . '.pages.partials.home.bannersSeven')

    {{--featuredBrandProducts section with banner Five and Six --}}
    {{-- @include('frontend.' . getTheme() . '.pages.partials.home.featuredBrandProducts') --}}

   <!--blog section start -->
    @include('frontend.' . getTheme() . '.pages.partials.home.blogs', ['blogs' => $blogs]) 
     <!--blog section end-->
@endsection

@section('scripts')

    <script>       
        
        $(document).ready(function(){          
            // filterFeaturedCategoryProduct after click
            $(document).on('click', '#filterFeaturedCategoryProduct', function() {
                var category_id = $(this).attr('data-category_id');
                var theme_id = $(this).attr('data-theme_id');
                filterFeaturedCategoryProduct(category_id, theme_id);    
            });       
        });

        function filterFeaturedCategoryProduct(category_id, theme_id) {
            var url = '{{ route('products.index') }}?category_id=' + category_id + '&theme_id=' + theme_id + '&loadFeaturedProductByCategoryAndThemeId=true';
            $('.featured-tab-content').html('<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');           
            
            $.ajax({
                type: "GET",
                url: url,
                dataType: 'html',
                success: function(data) {
                    // $('.featured-tab-content').html(''); 
                    console.log(data);                 
                }
            });    
        }


    </script>

@endsection
