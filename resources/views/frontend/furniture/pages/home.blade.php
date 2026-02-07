@extends('frontend.default.layouts.master')

@section('title')
    {{ localize('Home') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('contents')
    <!--hero section start-->
    @include('frontend.' . getTheme() . '.pages.partials.home.hero')
    <!--hero section end-->

    <!--category section start-->
    @include('frontend.' . getTheme() . '.pages.partials.home.category')
    <!--category section end-->

    
    <!--banner section start for banner one and two -->
    @include('frontend.' . getTheme() . '.pages.partials.home.banners')

    {{-- Featured collection section with banner image Three and Four  --}}
    @include('frontend.' . getTheme() . '.pages.partials.home.featuredCollection')
    

    {{--featuredBrandProducts section with banner Five and Six --}}
    @include('frontend.' . getTheme() . '.pages.partials.home.featuredBrandProducts')


   <!--blog section start -->
    @include('frontend.' . getTheme() . '.pages.partials.home.blogs', ['blogs' => $blogs])
     <!--blog section end-->
@endsection

@section('scripts')
    <script>
        "use strict";

        // runs when the document is ready
        $(document).ready(function() {
            @if (\App\Models\Location::where('is_published', 1)->count() <=1)
                notifyMe('info', '{{ localize('Select your location if not selected') }}');
            @endif
        });


        
        $(document).ready(function()
        {          
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
                    $('.featured-tab-content').html(data); 
                    console.log(data);                 
                }
            });    
        }

    </script>
@endsection
