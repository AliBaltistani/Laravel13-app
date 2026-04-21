@extends('layouts.app')

@section('meta_title', 'Contact Us - ' . Setting::get('general.site_name', 'Porto Shop'))

@section('content')
    <div class="page-header page-header-custom">
        <div class="container d-flex flex-column align-items-center">
            <nav aria-label="breadcrumb" class="breadcrumb-nav">
                <div class="container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
                    </ol>
                </div>
            </nav>
            <span class="page-header-badge"><i class="fas fa-headset"></i> We'd Love to Hear From You</span>
            <h1>Get in Touch With Us</h1>
            <p class="page-header-desc">Have a question, feedback, or need assistance? Our team is here to help. Reach out and we'll get back to you as soon as possible.</p>
        </div>
    </div>

    {{-- Contact Info Cards --}}
    <section class="contact-cards-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="contact-info-card">
                        <div class="contact-info-card-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h4 class="contact-info-card-title">Our Office</h4>
                        <p class="contact-info-card-text">{{ Setting::get('contact.address', '123 Street Name, City') }}</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="contact-info-card">
                        <div class="contact-info-card-icon icon-phone">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <h4 class="contact-info-card-title">Call Us</h4>
                        <p class="contact-info-card-text">
                            <a href="tel:{{ Setting::get('contact.phone') }}">{{ Setting::get('contact.phone') }}</a>
                        </p>
                        <span class="contact-info-card-badge">{{ Setting::get('contact.working_hours', 'Mon - Fri: 9AM - 5PM') }}</span>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 mb-4">
                    <div class="contact-info-card">
                        <div class="contact-info-card-icon icon-email">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h4 class="contact-info-card-title">Email Us</h4>
                        <p class="contact-info-card-text">
                            <a href="mailto:{{ Setting::get('contact.email') }}">{{ Setting::get('contact.email') }}</a>
                        </p>
                        <span class="contact-info-card-badge">We reply within 24 hours</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Contact Form Section --}}
    <section class="contact-form-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="contact-form-wrapper">
                        <div class="contact-form-header">
                            <h3 class="contact-form-title">Send Us a Message</h3>
                            <p class="contact-form-subtitle">Fill out the form below and we'll get back to you shortly.</p>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success contact-form-alert">
                                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ url('/contact') }}" method="POST" class="contact-modern-form" id="contact-form">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="modern-form-group">
                                        <div class="modern-input-wrapper">
                                            <i class="fas fa-user"></i>
                                            <input type="text" class="modern-form-control" id="contact-name" name="name" placeholder="Your Name" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="modern-form-group">
                                        <div class="modern-input-wrapper">
                                            <i class="fas fa-envelope"></i>
                                            <input type="email" class="modern-form-control" id="contact-email" name="email" placeholder="Your Email" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="modern-form-group">
                                        <div class="modern-input-wrapper">
                                            <i class="fas fa-phone-alt"></i>
                                            <input type="tel" class="modern-form-control" id="contact-phone" name="phone" placeholder="Your Phone (optional)">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="modern-form-group">
                                        <div class="modern-input-wrapper">
                                            <i class="fas fa-tag"></i>
                                            <input type="text" class="modern-form-control" id="contact-subject" name="subject" placeholder="Subject">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modern-form-group">
                                <div class="modern-input-wrapper textarea-wrapper">
                                    <i class="fas fa-comment-dots"></i>
                                    <textarea class="modern-form-control" id="contact-message" name="message" rows="5" placeholder="Write your message here..." required></textarea>
                                </div>
                            </div>
                            <button type="submit" class="contact-submit-btn" id="contact-submit-btn">
                                <span>Send Message</span>
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    /* ============================================
       CONTACT PAGE — MODERN REDESIGN
       ============================================ */



    /* Info Cards */
    .contact-cards-section { padding: 40px 0 20px; }
    .contact-info-card {
        background: #fff; border-radius: 16px;
        padding: 35px 28px; text-align: center;
        border: 1px solid #eef1f5;
        box-shadow: 0 4px 25px rgba(0,0,0,0.04);
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        position: relative;
        overflow: hidden;
    }
    .contact-info-card::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 3px;
        background: var(--porto-primary);
        transform: scaleX(0); transition: transform 0.35s ease;
    }
    .contact-info-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.1);
    }
    .contact-info-card:hover::before { transform: scaleX(1); }
    .contact-info-card-icon {
        width: 64px; height: 64px; border-radius: 16px;
        background: linear-gradient(135deg, var(--porto-primary), color-mix(in srgb, var(--porto-primary) 80%, #000));
        color: #fff; display: inline-flex; align-items: center; justify-content: center;
        font-size: 24px; margin-bottom: 20px;
        transition: transform 0.3s ease;
    }
    .contact-info-card:hover .contact-info-card-icon { transform: scale(1.1) rotate(-5deg); }
    .contact-info-card-icon.icon-phone { background: linear-gradient(135deg, #2ecc71, #27ae60); }
    .contact-info-card-icon.icon-email { background: linear-gradient(135deg, #e74c3c, #c0392b); }
    .contact-info-card-title {
        font-size: 18px; font-weight: 700; color: var(--porto-heading);
        margin-bottom: 8px; font-family: 'Poppins', sans-serif;
    }
    .contact-info-card-text {
        font-size: 14px; color: #666; margin-bottom: 6px; line-height: 1.6;
    }
    .contact-info-card-text a { color: var(--porto-primary); text-decoration: none; font-weight: 500; }
    .contact-info-card-text a:hover { text-decoration: underline; }
    .contact-info-card-badge {
        display: inline-block; font-size: 12px; color: var(--porto-primary);
        background: rgba(var(--porto-primary-rgb, 0,136,204), 0.08);
        padding: 4px 12px; border-radius: 50px; font-weight: 600;
    }

    /* Form Section */
    .contact-form-section { padding: 20px 0 60px; }
    .contact-form-wrapper {
        background: #fff; border-radius: 20px;
        padding: 45px 40px; border: 1px solid #eef1f5;
        box-shadow: 0 8px 40px rgba(0,0,0,0.06);
    }
    .contact-form-header { text-align: center; margin-bottom: 30px; }
    .contact-form-title {
        font-size: 26px; font-weight: 700; color: var(--porto-heading);
        margin-bottom: 8px; font-family: 'Poppins', sans-serif;
    }
    .contact-form-subtitle { font-size: 14px; color: #888; }
    .contact-form-alert {
        border-radius: 12px; border: none;
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724; font-weight: 500;
    }

    /* Modern Form Inputs */
    .modern-form-group { margin-bottom: 20px; }
    .modern-input-wrapper {
        position: relative; display: flex; align-items: center;
        background: #f8f9fb; border: 2px solid #eef1f5;
        border-radius: 12px; transition: all 0.3s ease;
        overflow: hidden;
    }
    .modern-input-wrapper:focus-within {
        border-color: var(--porto-primary);
        background: #fff;
        box-shadow: 0 0 0 4px rgba(var(--porto-primary-rgb, 0,136,204), 0.08);
    }
    .modern-input-wrapper i {
        position: absolute; left: 16px; top: 50%;
        transform: translateY(-50%);
        color: #b0b8c4; font-size: 15px; z-index: 1;
        transition: color 0.3s ease;
    }
    .modern-input-wrapper:focus-within i { color: var(--porto-primary); }
    .modern-input-wrapper.textarea-wrapper i { top: 20px; transform: none; }
    .modern-form-control {
        width: 100%; border: none; background: none;
        padding: 14px 16px 14px 46px;
        font-size: 14px; color: #333;
        font-family: 'Open Sans', sans-serif;
        outline: none;
    }
    .modern-form-control::placeholder { color: #aaa; }
    textarea.modern-form-control { resize: vertical; min-height: 120px; }

    /* Submit Button */
    .contact-submit-btn {
        display: inline-flex; align-items: center; gap: 10px;
        background: linear-gradient(135deg, var(--porto-primary), color-mix(in srgb, var(--porto-primary) 80%, #000));
        color: #fff; border: none; padding: 14px 36px;
        border-radius: 12px; font-size: 15px; font-weight: 600;
        cursor: pointer; transition: all 0.35s ease;
        font-family: 'Poppins', sans-serif;
        box-shadow: 0 4px 15px rgba(var(--porto-primary-rgb, 0,136,204), 0.3);
    }
    .contact-submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(var(--porto-primary-rgb, 0,136,204), 0.4);
    }
    .contact-submit-btn i { transition: transform 0.3s ease; }
    .contact-submit-btn:hover i { transform: translateX(4px); }

    /* Responsive */
    @media (max-width: 768px) {
        .contact-hero-title { font-size: 28px; }
        .contact-form-wrapper { padding: 30px 20px; }
        .contact-hero-section { padding: 35px 0 25px; }
    }
</style>
@endpush
