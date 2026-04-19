@extends('layouts.app')

@section('meta_title', 'Contact Us - ' . Setting::get('general.site_name', 'Porto Shop'))

@push('styles')
<style>
    .custom-page-header { background-color: #f6f6f6; padding: 45px 0 50px; margin-bottom: 50px; }
    .custom-breadcrumb { justify-content: center; background: transparent; padding: 0; margin-bottom: 12px; font-size: 11px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; }
    .custom-breadcrumb .breadcrumb-item a { color: #dc3545; text-decoration: none; }
    .custom-breadcrumb .breadcrumb-item a:hover { color: #c82333; }
    .custom-breadcrumb .breadcrumb-item.active { color: #333; }
    .custom-breadcrumb .breadcrumb-item + .breadcrumb-item::before { content: "›"; font-size: 15px; line-height: 1; vertical-align: middle; color: #999; margin: 0 8px; }
    
    .page-hero-title { font-size: 2.8rem; font-weight: 800; color: #222529; font-family: 'Poppins', sans-serif; line-height: 1.2; }

    .contact-heading { font-size: 24px; font-weight: 800; color: #222529; font-family: 'Poppins', sans-serif; margin-bottom: 15px; }
    .contact-subtext { font-size: 14px; color: #777; margin-bottom: 35px; line-height: 1.6; font-weight: 500; }
    
    .contact-info-block h3 { font-size: 18px; font-weight: 700; color: #222529; font-family: 'Poppins', sans-serif; margin-bottom: 15px; }
    .contact-info-list { list-style: none; padding: 0; margin: 0; font-size: 14px; color: #666; font-weight: 500; line-height: 1.8; }
    .contact-info-list li { margin-bottom: 10px; display: flex; align-items: flex-start; gap: 8px; }
    .contact-info-list i { margin-top: 4px; color: #999; }
    .contact-info-list a { color: #0d6efd; text-decoration: none; }
    .contact-info-list a:hover { text-decoration: underline; }

    .contact-form label { font-size: 13px; font-weight: 800; color: #222; margin-bottom: 8px; display: block; font-family: 'Poppins', sans-serif; }
    .contact-form .form-control { border: 1px solid #e5e5e5; padding: 12px 15px; box-shadow: none; border-radius: 3px; background: #fff; font-size: 14px; }
    .contact-form .form-control:focus { border-color: #0d6efd; }
    
    .btn-send-message { background-color: #0088cc; color: white; border: none; font-weight: 700; font-family: 'Poppins', sans-serif; text-transform: uppercase; font-size: 13px; letter-spacing: 0.5px; padding: 14px 28px; border-radius: 3px; transition: 0.3s; }
    .btn-send-message:hover { background-color: #0073ad; color: white; }
</style>
@endpush

@section('content')
    <div class="custom-page-header">
        <div class="container text-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb custom-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">HOME</a></li>
                    <li class="breadcrumb-item active" aria-current="page">CONTACT US</li>
                </ol>
            </nav>
            <h1 class="page-hero-title mx-auto">Contact Us</h1>
        </div>
    </div>

    <div class="container mb-5 pb-5">
        <div class="row">
            {{-- Left Column: Information --}}
            <div class="col-lg-6 pr-lg-5 mb-5 mb-lg-0">
                <h2 class="contact-heading">Contact Information</h2>
                <p class="contact-subtext">{{ Setting::get('general.footer_about', 'Porto is an optimized eCommerce theme built with quality and care.') }}</p>
                
                <div class="row mt-4 pt-2">
                    <div class="col-sm-6 mb-4 mb-sm-0">
                        <div class="contact-info-block">
                            <h3>The Office</h3>
                            <ul class="contact-info-list">
                                <li>{{ Setting::get('contact.address', '123 Street Name, City, Country') }}</li>
                                <li><i class="far fa-phone"></i><a href="tel:{{ Setting::get('contact.phone') }}">{{ Setting::get('contact.phone', '+1 234 567 890') }}</a></li>
                                <li><a href="mailto:{{ Setting::get('contact.email') }}">{{ Setting::get('contact.email', 'contact@porto.com') }}</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="contact-info-block">
                            <h3>Working Hours</h3>
                            <ul class="contact-info-list">
                                <li><i class="far fa-clock"></i> {{ Setting::get('contact.working_hours', 'Mon - Fri: 9AM - 5PM') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Form --}}
            <div class="col-lg-6">
                <h2 class="contact-heading mb-4">Send Us a Message</h2>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ url('/contact') }}" method="POST" class="contact-form">
                    @csrf
                    <div class="form-group mb-4">
                        <label for="contact-name">Your Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="contact-name" name="name" required>
                    </div>
                    <div class="form-group mb-4">
                        <label for="contact-email">Your Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="contact-email" name="email" required>
                    </div>
                    <div class="form-group mb-4">
                        <label for="contact-phone">Your Phone</label>
                        <input type="tel" class="form-control" id="contact-phone" name="phone">
                    </div>
                    <div class="form-group mb-4">
                        <label for="contact-subject">Subject</label>
                        <input type="text" class="form-control" id="contact-subject" name="subject">
                    </div>
                    <div class="form-group mb-4 pb-2">
                        <label for="contact-message">Your Message <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="contact-message" name="message" rows="7" required style="resize: vertical;"></textarea>
                    </div>
                    <button type="submit" class="btn-send-message">SEND MESSAGE</button>
                </form>
            </div>
        </div>
    </div>
@endsection
