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
                                <h2 class="h5 mb-lg-0">{{ localize('Update Banner') }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4 g-4">
                <!--left sidebar-->
                <div class="col-xl-9 order-2 order-md-2 order-lg-2 order-xl-1">
                     <form action="{{ route('admin.appearance.organic.homepage.updateBannerSix') }}" method="POST"
                            enctype="multipart/form-data" id="section-1">
                            @csrf
                            <input type="hidden" name="id" value="{{ $id }}">
                            @php
                                $banner = null;
                                if (!empty($banners)) {
                                    foreach ($banners as $key => $thisBanner) {
                                        if ($thisBanner->id == $id) {
                                            $banner = $thisBanner;
                                        }
                                    }
                                }
                        @endphp

                            <!-- slider info start -->
                            <div class="card mb-4">
                                <div class="card-body">
                                    <!-- Title -->
                                    <div class="mb-4">
                                        <label for="title" class="form-label">{{ localize('Title') }}</label>
                                        <input type="text" name="title" id="title"
                                            placeholder="{{ localize('Type title') }}" class="form-control"
                                            value="{{ $banner->title ?? '' }}">
                                    </div>

                                    <!-- Subtitle -->
                                    <div class="mb-4">
                                        <label for="subtitle" class="form-label">{{ localize('Subtitle') }}</label>
                                        <input type="text" name="subtitle" id="subtitle"
                                            placeholder="{{ localize('Type subtitle') }}" class="form-control"
                                            value="{{ $banner->subtitle ?? '' }}">
                                    </div>

                                    {{-- First Card Details --}}
                                    <div class="mb-4">
                                        <label class="form-label">{{ localize('First Card Image') }}</label>
                                        <div class="tt-image-drop rounded">
                                            <span class="fw-semibold">{{ localize('First Card Image') }}</span>
                                            <!-- choose media -->
                                            <div class="tt-product-thumb show-selected-files mt-3">
                                                <div class="avatar avatar-xl cursor-pointer choose-media"
                                                    data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                                                    onclick="showMediaManager(this)" data-selection="single">
                                                    <input type="hidden" name="firstCartImage" value="{{ $banner->firstCartImage ?? '' }}">
                                                    <div class="no-avatar rounded-circle">
                                                        <span><i data-feather="plus"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="firstCardClientCount" class="form-label">{{ localize('First Card Client Count') }}</label>
                                        <input type="number" name="firstCardClientCount" id="firstCardClientCount"
                                            placeholder="{{ localize('Type First card client count') }}" class="form-control"
                                            value="{{ $banner->firstCardClientCount ?? '' }}">
                                    </div>

                                    <div class="mb-4">
                                        <label for="firstCardText" class="form-label">{{ localize('First Card Text') }}</label>
                                        <input type="text" name="firstCardText" id="firstCardText"
                                            placeholder="{{ localize('Type First card text') }}" class="form-control"
                                            value="{{ $banner->firstCardText ?? '' }}">
                                    </div>

                                    {{-- Second Card Details --}}
                                    <div class="mb-4">
                                        <label class="form-label">{{ localize('Second Card Image') }}</label>
                                        <div class="tt-image-drop rounded">
                                            <span class="fw-semibold">{{ localize('Second Card Image') }}</span>
                                            <!-- choose media -->
                                            <div class="tt-product-thumb show-selected-files mt-3">
                                                <div class="avatar avatar-xl cursor-pointer choose-media"
                                                    data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                                                    onclick="showMediaManager(this)" data-selection="single">
                                                    <input type="hidden" name="secondCartImage" value="{{ $banner->secondCartImage ?? '' }}">
                                                    <div class="no-avatar rounded-circle">
                                                        <span><i data-feather="plus"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- @dd($banner); --}}
                                    <div class="mb-4">
                                        <label for="secondCardClientCount" class="form-label">{{ localize('Second Card Client Count') }}</label>
                                        <input type="number" name="secondCardClientCount" id="secondCardClientCount"
                                            placeholder="{{ localize('Type Second card client count') }}" class="form-control"
                                            value="{{ $banner->secondCardClientCount ?? '' }}">
                                    </div>

                                    <div class="mb-4">
                                        <label for="secondCardText" class="form-label">{{ localize('Second Card Text') }}</label>
                                        <input type="text" name="secondCardText" id="secondCardText"
                                            placeholder="{{ localize('Type Second card text') }}" class="form-control"
                                            value="{{ $banner->secondCardText ?? '' }}">
                                    </div>

                                    {{-- Third Card Details --}}
                                    <div class="mb-4">
                                        <label class="form-label">{{ localize('Third Card Image') }}</label>
                                        <div class="tt-image-drop rounded">
                                            <span class="fw-semibold">{{ localize('Third Card Image') }}</span>
                                            <!-- choose media -->
                                            <div class="tt-product-thumb show-selected-files mt-3">
                                                <div class="avatar avatar-xl cursor-pointer choose-media"
                                                    data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                                                    onclick="showMediaManager(this)" data-selection="single">
                                                    <input type="hidden" name="thirdCartImage" value="{{ $banner->thirdCartImage ?? '' }}">
                                                    <div class="no-avatar rounded-circle">
                                                        <span><i data-feather="plus"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="thirdCardClientCount" class="form-label">{{ localize('Third Card Client Count') }}</label>
                                        <input type="number" name="thirdCardClientCount" id="thirdCardClientCount"
                                            placeholder="{{ localize('Type Third card client count') }}" class="form-control"
                                            value="{{ $banner->thirdCardClientCount ?? '' }}">
                                    </div>

                                    <div class="mb-4">
                                        <label for="thirdCardText" class="form-label">{{ localize('Third Card Text') }}</label>
                                        <input type="text" name="thirdCardText" id="thirdCardText"
                                            placeholder="{{ localize('Type Third card text') }}" class="form-control"
                                            value="{{ $banner->thirdCardText ?? '' }}">
                                    </div>

                                </div><!-- /.card-body -->
                            </div><!-- /.card -->
                            <!-- slider info end -->

                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-4">
                                            <button class="btn btn-primary" type="submit">
                                                <i data-feather="save" class="me-1"></i> {{ localize('Save Changes') }}
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
                                    <h5 class="mb-4">{{ localize('Banner Section Six') }}</h5>
                                    <div class="tt-vertical-step">
                                        <ul class="list-unstyled">
                                            <li>
                                                <a href="#section-1" class="active">{{ localize('Update Banner') }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

        </div>
    </section>
@endsection

@section('scripts')
    <script>
        "use strict";

        // runs when the document is ready --> for media files
        $(document).ready(function() {
            getChosenFilesCount();
            showSelectedFilePreviewOnLoad();
        });
    </script>
@endsection
