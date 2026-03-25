@extends('frontend.default.layouts.master')
@section('contents')
    <section class="section-404 ptb-120 position-relative overflow-hidden z-1">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6">
                    <div class="content-404 text-center">
                        <img src="{{ staticAsset('frontend/default/assets/img/404.png') }}" alt="not found"
                            class="img-fluid">
                        <h2 class="mt-4">Sorry, Something Went Wrong</h2>
                        <p class="mb-6">The page you are looking for might have been removed had its name changed or is
                            temporarily unavailable.</p>
                        <a href="{{ env('APP_URL') }}" class="btn btn-secondary btn-md rounded-1">Back to Home Page</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
