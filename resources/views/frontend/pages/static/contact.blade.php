@extends('frontend.layout.app')

@section('page-title')
Contact Us
@endsection

@section('page-css')

@endsection

@section('body-content')

<section class="s-page-title">
    <div class="container">
        <div class="content">
            <h1 class="title-page">Contact Us</h1>
            <ul class="breadcrumbs-page">
                <li><a href="{{ route('homepage') }}" class="h6 link">Home</a></li>
                <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                <li>
                    <h6 class="current-page fw-normal">Contact Us</h6>
                </li>
            </ul>
        </div>
    </div>
</section>

<section class="s-contact-information flat-spacing">
    <div class="container">
        <div class="row d-flex align-items-center">
            <div class="col-lg-7">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d26181392.1208345!2d-99.46914709389182!3d32.57055369824682!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88c1766591562abf%3A0xf72e13d35bc74ed0!2sFlorida%2C%20USA!5e1!3m2!1sen!2sbd!4v1768192262434!5m2!1sen!2sbd"
                    width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="col-lg-5">
                <div class="infor-content">
                    <p class="title h1 fw-medium text-black">Contact Infomation</p>
                    <ul class="infor-store">
                        <li>
                            <h5 class="caption fw-semibold">Address</h5>
                            <p class="h6 mb-12">{{ config('app.address') }}</p>
                            <a href="https://www.google.com/maps?q={{ config('app.address') }}" target="_blank"
                                class="tf-btn-line">
                                <span class="h6 text-capitalize fw-semibold">
                                    Get Direction
                                </span>
                                <i class="icon icon-arrow-top-right fs-20"></i>
                            </a>
                        </li>
                        <li>
                            <h5 class="caption fw-semibold">Contact Us</h5>
                            <ul class="store-contact list-ver">
                                <li>
                                    <i class="icon icon-phone"></i>
                                    <span class="br-line type-vertical"></span>
                                    <a href="tel:{{ config('app.phone') }}" class="h6 link">{{ config('app.phone')
                                        }}</a>
                                </li>
                                <li>
                                    <i class="icon icon-envelope-simple"></i>
                                    <span class="br-line type-vertical"></span>
                                    <a href="mailto:{{ config('app.email') }}" class="h6 link">{{ config('app.email')
                                        }}</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <h5 class="caption fw-semibold">Social Media</h5>
                            <ul class="tf-social-icon">
                                @if (!empty(config('app.facebook')))
                                <li>
                                    <a href="{{ config('app.facebook') }}" target="_blank" class="social-facebook">
                                        <span class="icon"><i class="icon-fb"></i></span>
                                    </a>
                                </li>
                                @endif

                                @if (!empty(config('app.instra')))
                                <li>
                                    <a href="{{ config('app.instra') }}" target="_blank" class="social-instagram">
                                        <span class="icon"><i class="icon-instagram-logo"></i></span>
                                    </a>
                                </li>
                                @endif

                                @if (!empty(config('app.twitter')))
                                <li>
                                    <a href="{{ config('app.twitter') }}" target="_blank" class="social-x">
                                        <span class="icon"><i class="icon-x"></i></span>
                                    </a>
                                </li>
                                @endif

                                @if (!empty(config('app.tiktok')))
                                <li>
                                    <a href="{{ config('app.tiktok') }}" target="_blank" class="social-tiktok">
                                        <span class="icon"><i class="icon-tiktok"></i></span>
                                    </a>
                                </li>
                                @endif
                                @if (!empty(config('app.whatsapp')))
                                <li>
                                    <a href="{{ config('app.whatsapp') }}" target="_blank" class="social-whatsapp">
                                        <span class="icon"><i class="bi bi-whatsapp"></i></span>
                                    </a>
                                </li>
                                @endif

                                @if (!empty(config('app.youtube')))
                                <li>
                                    <a href="{{ config('app.youtube') }}" target="_blank" class="social-youtube">
                                        <span class="icon"><i class="bi bi-youtube"></i></span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

@livewire('frontend.user.contact-message')

@endsection

@section('page-script')
@endsection