{{-- <div class="row justify-content-center g-4 mt-2">

    <div class="col-xl-6">
        @if(isset($blogs[0]))
              @php 
              $blog = $blogs[0];
             @endphp
            <article class="hm3-blog-card">
                <div class="thumbnail rounded overflow-hidden">
                    <a href="{{ route('home.blogs.show', $blog->slug) }}"><img src="{{ uploadedAsset($blog->collectLocalization('thumbnail_image')) ?? asset('assets/img/placeholder.png') }}" alt="blog grid" class="img-fluid"></a>
                </div>
                <div class="article-contents py-5 px-4 bg-white position-relative">
                    <div class="blog-meta d-flex align-items-center gap-3 flex-wrap mb-2">
                        <span class="fw-medium fs-xs"><i class="fa-solid fa-tags me-2"></i>{{ $blog->category->name ?? '' }}</span>
                        <span class="fw-medium fs-xs"><i class="fa-regular fa-clock me-2"></i>{{ $blog->created_at->format('M d, Y') }}</span>
                    </div>
                    <a href="{{ route('home.blogs.show', $blog->slug) }}"><h4 class="mb-2">{{ $blog->collectLocalization('title') ?? '' }}</h4></a>
                    <p class="mb-3">{{ $blog->collectLocalization('short_description') ?? '' }}</p>
                    <a href="{{ route('home.blogs.show', $blog->slug) }}" class="explore-btn fw-bold">Explore More<span class="ms-2"><i class="fas fa-arrow-right"></i></span></a>
                </div>
            </article>
        @endif
    </div>

    {{-- Right side: remaining blogs --}}
    {{-- <div class="col-xl-6">
        <div class="row g-4">
            @foreach ($blogs->skip(1) as $blog)
                <div class="col-xl-12 col-md-6">
                    <article class="article-horizontal d-flex align-items-center gap-4 p-4 rounded-3 bg-white border-0">
                        <div class="thumbnail flex-shrink-0 overflow-hidden rounded-3">
                            <a href="{{ route('home.blogs.show', $blog->slug) }}"><img src="{{ uploadedAsset($blog->collectLocalization('thumbnail_image')) ?? null }}" alt="not found" class="img-fluid"></a>
                        </div>
                        <div class="article-contents">
                            <div class="blog-meta d-flex align-items-center gap-3 flex-wrap mb-2">
                                <span class="fw-medium fs-xs"><i class="fa-solid fa-tags me-2"></i>{{ $blog->category->name ?? '' }}</span>
                                <span class="fw-medium fs-xs"><i class="fa-regular fa-clock me-2"></i>{{ $blog->created_at->format('M d, Y') }}</span>
                            </div>
                            <a href="{{ route('home.blogs.show', $blog->slug) }}"><h4 class="mb-2">{{ $blog->collectLocalization('title') ?? '' }}</h4></a>
                            <p class="mb-3">{{ $blog->collectLocalization('short_description') ?? '' }}</p>
                            <a href="{{ route('home.blogs.show', $blog->slug) }}" class="explore-btn fw-bold">Explore More<span class="ms-2"><i class="fas fa-arrow-right"></i></span></a>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </div>
</div>  --}}
  <!--blog section start-->
  {{-- @dd(getSetting('organic_blogs_title')); --}}
      <section class="blog-section pb-120 overflow-hidden">
          <div class="container">
              <div class="row g-5 justify-content-between">
                  <div class="col-xl-5 col-lg-7">
                      <div class="section-title text-center text-lg-start">
                          <h2 class="mb-3">
                            {{ localize(getSetting('organic_blogs_title') ?? '')}}
                          </h2>
                          <p class="mb-0">
                            {{ localize(getSetting('organic_blog_text') ?? '')}}
                          </p>
                      </div>
                  </div>
                  <div class="col-xl-4 align-self-end col-lg-5">
                      <div class="text-center text-lg-end d-none d-lg-block">
                          <a href=" {{route('home.blogs')}}" class="btn btn-dark">Show All<span class="ms-2"><i class="fas fa-arrow-right"></i></span></a>
                      </div>
                  </div>
              </div>
              <div class="row justify-content-center g-4 mt-2">
                    @if(isset($blogs[0]))
                    @php 
                       $blog = $blogs[0];
                    @endphp
                  <div class="col-xl-6">
                      <article class="hm3-blog-card">
                          <div class="thumbnail rounded overflow-hidden">
                              <a href="{{ route('home.blogs.show', $blog->slug) }}"><img src="{{ uploadedAsset($blog->collectLocalization('thumbnail_image')) ?? null }}" alt="blog grid" class="img-fluid"></a>
                          </div>
                          <div class="article-contents py-5 px-4 bg-white position-relative">
                              <div class="blog-meta d-flex align-items-center gap-3 gap-xl-2 gap-xxl-3 flex-wrap mb-2">
                                  <span class="fw-medium fs-xs"><i class="fa-solid fa-tags me-2"></i>{{ $blog->category->name ?? '' }}</span>
                                  <span class="fw-medium fs-xs"><i class="fa-regular fa-clock me-2"></i>{{ $blog->created_at->format('M d, Y') }}</span>
                              </div>
                              <a href="{{ route('home.blogs.show', $blog->slug) }}"><h4 class="mb-2">{{ $blog->collectLocalization('title') ?? '' }}</h4></a>
                              <p class="mb-3">{{ $blog->collectLocalization('short_description') ?? '' }}</p>
                              <a href="{{ route('home.blogs.show', $blog->slug) }}" class="explore-btn fw-bold">Explore More<span class="ms-2"><i class="fas fa-arrow-right"></i></span></a>
                          </div>
                      </article>
                  </div>
                  @endif
                  <div class="col-xl-6">
                      <div class="row g-4">
                          @foreach ($blogs->skip(1) as $blog)
                          <div class="col-xl-12 col-md-6">
                              <article class="article-horizontal d-flex align-items-center gap-4 p-4 rounded-3 bg-white border-0">
                                  <div class="thumbnail flex-shrink-0 overflow-hidden rounded-3">
                                      <a href="blog-details.html"><img src="{{ uploadedAsset($blog->collectLocalization('thumbnail_image')) ?? null }}" alt="not found" class="img-fluid"></a>
                                  </div>
                                  <div class="article-contents">
                                      <div class="blog-meta d-flex align-items-center gap-3 gap-xl-2 gap-xxl-3 flex-wrap mb-2">
                                          <span class="fw-medium fs-xs"><i class="fa-solid fa-tags me-2"></i>{{ $blog->category->name ?? '' }}</span>
                                          <span class="fw-medium fs-xs"><i class="fa-regular fa-clock me-2"></i>{{ $blog->created_at->format('M d, Y') }}</span>
                                      </div>
                                      <a href="blog-details.html"><h4 class="mb-2">{{ $blog->collectLocalization('title') ?? '' }}</h4></a>
                                      <p class="mb-3">{{ $blog->collectLocalization('short_description') ?? '' }}</p>
                                      <a href="{{ route('home.blogs.show', $blog->slug) }}" class="explore-btn fw-bold">Explore More<span class="ms-2"><i class="fas fa-arrow-right"></i></span></a>
                                  </div>
                              </article>
                          </div>
                            @endforeach
                      </div>
                  </div>
              </div>
              {{-- <div class="text-center d-lg-none mt-6">
                  <a href="{{route('home.blogs')}}" class="btn btn-dark">Show All dfff<span class="ms-2"><i class="fas fa-arrow-right"></i></span></a>
              </div> --}}
          </div>
      </section>  <!--blog section end-->