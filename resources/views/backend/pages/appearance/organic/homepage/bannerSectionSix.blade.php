@extends('backend.layouts.master')

@section('title')
    {{ localize('Website Homepage Configuration') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('contents')
    <section class="tt-section pt-4">
        <div class="container">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card tt-page-header">
                        <div class="card-body d-lg-flex align-items-center justify-content-lg-between">
                            <div class="tt-page-title">
                                <h2 class="h5 mb-lg-0">{{ localize('Banner Section Six') }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4 g-4">
                <!--left sidebar-->
                <div class="col-xl-9 order-2 order-md-2 order-lg-2 order-xl-1">
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-4" id="section-1">
                                <table class="table tt-footable" data-use-parent-width="true">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="7%">{{ localize('S/L') }}</th>
                                            {{-- <th>{{ localize('Image') }}</th> --}}
                                            <th>{{ localize('Ttitle') }}</th>
                                            <th>{{ localize('Subtitle') }}</th>
                                            <th data-breakpoints="xs sm" class="text-end">
                                                {{ localize('Action') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($banners as $key => $banner)
                                            <tr>
                                                <td class="text-center align-middle">
                                                    {{ $key + 1 }}
                                                </td>
                                                {{-- <td class="align-middle">
                                                    <div class="avatar avatar-lg">
                                                        <img class="rounded" src="{{ uploadedAsset($banner->image) }}"
                                                            alt="" />
                                                    </div>
                                                </td> --}}
                                                <td class="align-middle">
                                                    <h6 class="mb-0">{{ $banner->title }}</h6>
                                                </td>
                                                <td class="align-middle">
                                                    <h6 class="mb-0">{{ $banner->subtitle }}</h6>
                                                </td>

                                                <td class="text-end align-middle">
                                                    <div class="dropdown tt-tb-dropdown">
                                                        <button type="button" class="btn p-0" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <i data-feather="more-vertical"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end shadow">

                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.appearance.organic.homepage.editBannerSix', ['id' => $banner->id, 'lang_key' => env('DEFAULT_LANGUAGE')]) }}&localize">
                                                                <i data-feather="edit-3"
                                                                    class="me-2"></i>{{ localize('Edit') }}
                                                            </a>

                                                            <a href="#" class="dropdown-item confirm-delete"
                                                                data-href="{{ route('admin.appearance.organic.homepage.deleteBannerSix', $banner->id) }}"
                                                                title="{{ localize('Delete') }}">
                                                                <i data-feather="trash-2" class="me-2"></i>
                                                                {{ localize('Delete') }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.appearance.organic.homepage.storeBannerSix') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <!--slider info start-->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Add New Banner') }}</h5>

                                {{-- title --}}
                                <div class="mb-4">
                                    <label for="title" class="form-label">{{ localize('Title') }}</label>
                                    <input type="text" name="title" id="title"
                                        placeholder="{{ localize('Type title') }}" class="form-control">
                                    {{ localize('Add your text in {_text here_} to make it colorful') }}
                                </div>
                                
                             
                                 
                                                                        
                                {{-- subtitle --}}
                                <div class="mb-4">
                                    <label for="subtitle" class="form-label">{{ localize('Subtitle') }}</label>
                                    <input type="text" name="subtitle" id="subtitle"
                                        placeholder="{{ localize('Type subtitle') }}" class="form-control">
                                  </div>
                              {{-- First Card Details --}}
                              
                                    <!-- First Card Image -->
                                    <div class="mb-4">
                                        <label class="form-label">{{ localize('First Card Image') }}</label>
                                        <div class="tt-image-drop rounded">
                                            <span class="fw-semibold">{{ localize('First Card Image') }}</span>
                                            <!-- choose media -->
                                            <div class="tt-product-thumb show-selected-files mt-3">
                                                <div class="avatar avatar-xl cursor-pointer choose-media"
                                                    data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                                                    onclick="showMediaManager(this)" data-selection="single">
                                                    <input type="hidden" name="firstCartImage">
                                                    <div class="no-avatar rounded-circle">
                                                        <span><i data-feather="plus"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- choose media -->
                                        </div>
                                    </div>

                                    <!-- First Card Client Count -->
                                    <div class="mb-4">
                                        <label for="firstCardClientCount" class="form-label">{{ localize('First Card Client Count') }}</label>
                                        <input type="number" name="firstCardClientCount" id="firstCardClientCount"
                                            placeholder="{{ localize('Type first card client count') }}" class="form-control">
                                    </div>

                                    <!-- First Card Text -->
                                    <div class="mb-4">
                                        <label for="firstCardText" class="form-label">{{ localize('First Card Text') }}</label>
                                        <input type="text" name="firstCardText" id="firstCardText"
                                            placeholder="{{ localize('Type first card text') }}" class="form-control">
                                    </div>
                                
                                 {{-- Second Card --}}
                                    <!-- Second Card Image -->
                                    <div class="mb-4">
                                        <label class="form-label">{{ localize('Second Card Image') }}</label>
                                        <div class="tt-image-drop rounded">
                                            <span class="fw-semibold">{{ localize('Second Card Image') }}</span>
                                            <!-- choose media -->
                                            <div class="tt-product-thumb show-selected-files mt-3">
                                                <div class="avatar avatar-xl cursor-pointer choose-media"
                                                    data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                                                    onclick="showMediaManager(this)" data-selection="single">
                                                    <input type="hidden" name="secondCartImage">
                                                    <div class="no-avatar rounded-circle">
                                                        <span><i data-feather="plus"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- choose media -->
                                        </div>
                                    </div>
                                     <!-- second Card Client Count -->
                                    <div class="mb-4">
                                        <label for="secondCardClientCount" class="form-label">{{ localize('Second Card Client Count') }}</label>
                                        <input type="number" name="secondCardClientCount" id="secondCardClientCount"
                                            placeholder="{{ localize('Type Second card client count') }}" class="form-control">
                                    </div>

                                    <!-- Second Card Text -->
                                    <div class="mb-4">
                                        <label for="secondCardText" class="form-label">{{ localize('Second Card Text') }}</label>
                                        <input type="text" name="secondCardText" id="secondCardText"
                                            placeholder="{{ localize('Type Second card text') }}" class="form-control">
                                    </div>

                                 {{-- Third Card --}}
                                    <!-- Third Card Image -->
                                    <div class="mb-4">
                                        <label class="form-label">{{ localize('Third Card Image') }}</label>
                                        <div class="tt-image-drop rounded">
                                            <span class="fw-semibold">{{ localize('Third Card Image') }}</span>
                                            <!-- choose media -->
                                            <div class="tt-product-thumb show-selected-files mt-3">
                                                <div class="avatar avatar-xl cursor-pointer choose-media"
                                                    data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                                                    onclick="showMediaManager(this)" data-selection="single">
                                                    <input type="hidden" name="thirdCartImage">
                                                    <div class="no-avatar rounded-circle">
                                                        <span><i data-feather="plus"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- choose media -->
                                        </div>
                                    </div>

                                    <!-- Third Card Client Count -->
                                    <div class="mb-4">
                                        <label for="thirdCardClientCount" class="form-label">{{ localize('Third Card Client Count') }}</label>
                                        <input type="number" name="thirdCardClientCount" id="thirdCardClientCount"
                                            placeholder="{{ localize('Type Third card client count') }}" class="form-control">
                                    </div>

                                    <!-- Third Text -->
                                    <div class="mb-4">
                                        <label for="thirdCardText" class="form-label">{{ localize('Third Card Text') }}</label>
                                        <input type="text" name="thirdCardText" id="thirdCardText"
                                            placeholder="{{ localize('Type Third card text') }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                        <!--slider info end-->


                        <div class="row">
                            <div class="col-12">
                                <div class="mb-4">
                                    <button class="btn btn-primary" type="submit">
                                        <i data-feather="save" class="me-1"></i> {{ localize('Save Banner') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!--right sidebar-->
                <div class="col-xl-3 order-1 order-md-1 order-lg-1 order-xl-2">
                    <div class="card tt-sticky-sidebar">
                        <div class="card-body">
                            <h5 class="mb-4">{{ localize('Homepage Configuration') }}</h5>
                            <div class="tt-vertical-step-link">
                                <ul class="list-unstyled">
                                    @include('backend.pages.appearance.organic.homepage.inc.rightSidebar')
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
