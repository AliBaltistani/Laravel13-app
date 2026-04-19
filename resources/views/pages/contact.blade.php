@extends('layouts.app')

@section('meta_title', 'Contact Us - ' . Setting::get('general.site_name', 'Porto Shop'))

@section('content')
    @include('partials.breadcrumb', ['title' => 'Contact Us'])

    <div class="container contact-us-container">
        <div class="row">
            <div class="col-lg-6">
                <h2 class="mt-1 mb-2">Contact Information</h2>
                <p class="mb-2">{{ Setting::get('general.footer_about', '') }}</p>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="contact-info">
                            <h3>The Office</h3>
                            <ul class="contact-list">
                                <li><i class="icon-map-marker"></i> {{ Setting::get('contact.address', '123 Street Name, City') }}</li>
                                <li><i class="icon-phone"></i> <a href="tel:{{ Setting::get('contact.phone') }}">{{ Setting::get('contact.phone') }}</a></li>
                                <li><i class="icon-envelope"></i> <a href="mailto:{{ Setting::get('contact.email') }}">{{ Setting::get('contact.email') }}</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="contact-info">
                            <h3>Working Hours</h3>
                            <ul class="contact-list">
                                <li><i class="icon-clock"></i> {{ Setting::get('contact.working_hours', 'Mon - Fri: 9AM - 5PM') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <h2 class="mt-1 mb-2">Send Us a Message</h2>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ url('/contact') }}" method="POST" class="mb-0 contact-form">
                    @csrf
                    <div class="form-group">
                        <label for="contact-name">Your Name <span class="required">*</span></label>
                        <input type="text" class="form-control" id="contact-name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="contact-email">Your Email <span class="required">*</span></label>
                        <input type="email" class="form-control" id="contact-email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="contact-phone">Your Phone</label>
                        <input type="tel" class="form-control" id="contact-phone" name="phone">
                    </div>
                    <div class="form-group">
                        <label for="contact-subject">Subject</label>
                        <input type="text" class="form-control" id="contact-subject" name="subject">
                    </div>
                    <div class="form-group">
                        <label for="contact-message">Your Message <span class="required">*</span></label>
                        <textarea class="form-control" id="contact-message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
        </div>
    </div>
@endsection
