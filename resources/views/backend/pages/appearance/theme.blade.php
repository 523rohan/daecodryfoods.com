@extends('backend.layouts.master')

@section('title')
    {{ localize('Theme Settings') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('contents')
    <section class="tt-section pt-4">
        <div class="container">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card tt-page-header">
                        <div class="card-body d-lg-flex align-items-center justify-content-lg-between">
                            <div class="tt-page-title">
                                <h2 class="h5 mb-lg-0">{{ localize('Theme Settings') }}</h2>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!--left sidebar-->
                <div class="col-xl-9 order-2 order-md-2 order-lg-2 order-xl-1">
                    <form action="{{ route('admin.appearance.themeUpdate') }}" method="POST" enctype="multipart/form-data"
                        class="pb-650">
                        @csrf
                        <!--general settings-->
                        <div class="card mb-4" id="section-1">
                            <div class="card-body">
                                <div class="row">
                                    {{-- Only show Grocery theme (Theme 1) --}}
                                    <div class="mb-3 col-lg-6">
                                        <label for="default_theme_name"
                                            class="form-label">{{ localize('Theme Name') }}</label>
                                        <input type="text" id="default_theme_name" name="1" class="form-control"
                                            value="{{ isset($themes[0]) ? $themes[0]->name : null }}" required>
                                    </div>

                                    {{-- Hidden fields for other themes to maintain database structure --}}
                                    <input type="hidden" name="2"
                                        value="{{ isset($themes[1]) ? $themes[1]->name : 'Halal Food' }}">
                                    <input type="hidden" name="3"
                                        value="{{ isset($themes[2]) ? $themes[2]->name : 'Furniture' }}">
                                    <input type="hidden" name="4"
                                        value="{{ isset($themes[3]) ? $themes[3]->name : 'Organic' }}">

                                </div>
                                {{-- <div class="row">
                                    <div class="mb-3 col-lg-4">
                                        <p for="halal_theme_name" class="form-label">{{ localize('Select Active Theme') }}
                                        </p>

                                        @php
                                        $getActiveThemes = getSetting(appStatic()::ENTITY_ACTIVE_THEMES);

                                        $activeThemeId = null;

                                        if(!empty($getActiveThemes)){
                                        try{
                                        $decodedData = json_decode($getActiveThemes);
                                        $activeThemeId = $decodedData[0];
                                        }
                                        catch(\Throwable $e){
                                        throw $e;
                                        }
                                        }
                                        @endphp


                                        @forelse($themes as $key => $theme)
                                        <label for="activeTheme{{$theme->id}}" class="d-inline me-2">
                                            <input type="radio" @if(!is_null($activeThemeId) && $theme->id ==
                                            $activeThemeId) checked @endif
                                            name="active_theme_id"
                                            value="{{ $theme->id }}"
                                            id="activeTheme{{ $theme->id }}">
                                            {{ $theme->name }}
                                        </label>
                                        @empty
                                        @endforelse

                                    </div>

                                </div> --}}

                                <div class="row">
                                    <div class="mb-3 col-lg-12">
                                        <label class="form-label d-block mb-2">{{ localize('Active Theme') }}</label>

                                        @php
                                            $getActiveThemes = getSetting(appStatic()::ENTITY_ACTIVE_THEMES);

                                            $activeThemeId = null;

                                            if (!empty($getActiveThemes)) {
                                                try {
                                                    $decodedData = json_decode($getActiveThemes);
                                                    $activeThemeId = $decodedData[0];
                                                } catch (\Throwable $e) {
                                                    throw $e;
                                                }
                                            }
                                        @endphp

                                        {{-- Only show Grocery theme (first theme) --}}
                                        <div class="d-flex flex-wrap gap-3">
                                            @if(isset($themes[0]))
                                                <label for="activeTheme{{ $themes[0]->id }}"
                                                    class="d-inline-flex align-items-center me-3">
                                                    <input type="radio" checked name="active_theme_id"
                                                        value="{{ $themes[0]->id }}" id="activeTheme{{ $themes[0]->id }}"
                                                        class="me-1">
                                                    {{ $themes[0]->name }} (Active)
                                                </label>
                                            @else
                                                <span class="text-muted">Grocery theme is active</span>
                                            @endif
                                        </div>
                                        <small class="text-muted">Your store is using the Grocery theme</small>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <!--general settings-->


                        <div class="mb-3 col-lg-4">
                            <button class="btn btn-primary" type="submit">
                                <i data-feather="save" class="me-1"></i> {{ localize('Save Changes') }}
                            </button>
                        </div>
                    </form>
                </div>

                <!--right sidebar-->
                <div class="col-xl-3 order-1 order-md-1 order-lg-1 order-xl-2">
                    <div class="card tt-sticky-sidebar">
                        <div class="card-body">
                            <h5 class="mb-4">{{ localize('Configure General Settings') }}</h5>
                            <div class="tt-vertical-step">
                                <ul class="list-unstyled">
                                    <li>
                                        <a href="#section-1" class="active">{{ localize('General Information') }}</a>
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
        $(document).ready(function () {
            getChosenFilesCount();
            showSelectedFilePreviewOnLoad();
        });
    </script>
@endsection