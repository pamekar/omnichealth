@extends('layouts.app')

@section('title', 'Privacy Policy - OMNIC Medical Store')

@section('content')
<div class="privacy-page">
    <section class="section">
        <div class="container" data-aos="fade-up">
            <h1 class="section-title">Privacy Policy</h1>
            <div class="privacy-content">
                <p><strong>Last Updated:</strong> {{ date('F j, Y') }}</p>

                <h2>1. Introduction</h2>
                <p>Welcome to OMNIC Medical Store. We are committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website. Please read this privacy policy carefully. If you do not agree with the terms of this privacy policy, please do not access the site.</p>

                <h2>2. Collection of Your Information</h2>
                <p>We may collect information about you in a variety of ways. The information we may collect on the Site includes:</p>
                <ul>
                    <li><strong>Personal Data:</strong> Personally identifiable information, such as your name, shipping address, email address, and telephone number, and demographic information, such as your age, gender, hometown, and interests, that you voluntarily give to us when you register with the Site or when you choose to participate in various activities related to the Site, such as online chat and message boards.</li>
                    <li><strong>Derivative Data:</strong> Information our servers automatically collect when you access the Site, such as your IP address, your browser type, your operating system, your access times, and the pages you have viewed directly before and after accessing the Site.</li>
                </ul>

                <h2>3. Use of Your Information</h2>
                <p>Having accurate information about you permits us to provide you with a smooth, efficient, and customized experience. Specifically, we may use information collected about you via the Site to:</p>
                <ul>
                    <li>Create and manage your account.</li>
                    <li>Process your transactions and send you related information, including purchase confirmations and invoices.</li>
                    <li>Email you regarding your account or order.</li>
                    <li>Improve our website and offerings.</li>
                    <li>Monitor and analyze usage and trends to improve your experience with the Site.</li>
                </ul>

                <h2>4. Disclosure of Your Information</h2>
                <p>We may share information we have collected about you in certain situations. Your information may be disclosed as follows:</p>
                <ul>
                    <li><strong>By Law or to Protect Rights:</strong> If we believe the release of information about you is necessary to respond to legal process, to investigate or remedy potential violations of our policies, or to protect the rights, property, and safety of others, we may share your information as permitted or required by any applicable law, rule, or regulation.</li>
                    <li><strong>Third-Party Service Providers:</strong> We may share your information with third parties that perform services for us or on our behalf, including payment processing, data analysis, email delivery, hosting services, customer service, and marketing assistance.</li>
                </ul>

                <h2>5. Security of Your Information</h2>
                <p>We use administrative, technical, and physical security measures to help protect your personal information. While we have taken reasonable steps to secure the personal information you provide to us, please be aware that despite our efforts, no security measures are perfect or impenetrable, and no method of data transmission can be guaranteed against any interception or other type of misuse.</p>

                <h2>6. Contact Us</h2>
                <p>If you have questions or comments about this Privacy Policy, please contact us at support@omnic.com.</p>
            </div>
        </div>
    </section>
</div>

<style>
.privacy-content {
    max-width: 800px;
    margin: 0 auto;
    background: var(--surface-color);
    padding: 2.5rem;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    line-height: 1.8;
}
.privacy-content h2 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--secondary-color);
    margin-top: 2rem;
    margin-bottom: 1rem;
}
.privacy-content ul {
    list-style: disc;
    padding-left: 20px;
}
.privacy-content li {
    margin-bottom: 0.5rem;
}
</style>
@endsection
