@extends('layouts.main')

@section('title', 'Terms of Service - D\'RANiK Techs')
@section('description', 'Read our Terms of Service to understand the rules and guidelines for using D\'RANiK Techs service marketplace platform.')

@section('content')
<div class="bg-white">
    <!-- Hero Section -->
    <div class="bg-gray-50 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Terms of Service</h1>
                <p class="text-lg text-gray-600">
                    Last updated: {{ now()->format('F d, Y') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Terms Content -->
    <div class="py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="prose prose-lg max-w-none">
                
                <!-- Introduction -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Introduction</h2>
                    <p class="text-gray-700 mb-4">
                        Welcome to D'RANiK Techs ("we," "our," or "us"). These Terms of Service ("Terms") govern your use of our website and mobile application (collectively, the "Platform") that connects customers with service providers.
                    </p>
                    <p class="text-gray-700 mb-4">
                        By accessing or using our Platform, you agree to be bound by these Terms. If you disagree with any part of these terms, then you may not access the Platform.
                    </p>
                </div>

                <!-- Definitions -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">2. Definitions</h2>
                    <ul class="space-y-3 text-gray-700">
                        <li><strong>"Platform"</strong> refers to the D'RANiK Techs website, mobile application, and related services.</li>
                        <li><strong>"User"</strong> refers to any person who accesses or uses the Platform.</li>
                        <li><strong>"Customer"</strong> refers to a User who books services through the Platform.</li>
                        <li><strong>"Provider"</strong> refers to a User who offers services through the Platform.</li>
                        <li><strong>"Service"</strong> refers to any service offered by Providers through the Platform.</li>
                        <li><strong>"Booking"</strong> refers to a confirmed appointment between a Customer and Provider.</li>
                    </ul>
                </div>

                <!-- User Accounts -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">3. User Accounts</h2>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">3.1 Account Creation</h3>
                    <p class="text-gray-700 mb-4">
                        To use certain features of the Platform, you must create an account. You agree to provide accurate, current, and complete information during registration and to update such information to keep it accurate, current, and complete.
                    </p>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">3.2 Account Security</h3>
                    <p class="text-gray-700 mb-4">
                        You are responsible for safeguarding your account credentials and for all activities that occur under your account. You must immediately notify us of any unauthorized use of your account.
                    </p>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">3.3 Account Termination</h3>
                    <p class="text-gray-700 mb-4">
                        We may suspend or terminate your account at any time for violations of these Terms or for any other reason at our sole discretion.
                    </p>
                </div>

                <!-- Platform Use -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">4. Platform Use</h2>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">4.1 Permitted Use</h3>
                    <p class="text-gray-700 mb-4">
                        You may use the Platform only for lawful purposes and in accordance with these Terms. You agree not to use the Platform:
                    </p>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700 mb-4">
                        <li>In any way that violates applicable laws or regulations</li>
                        <li>To transmit or procure the sending of any advertising or promotional material</li>
                        <li>To impersonate or attempt to impersonate another person or entity</li>
                        <li>To engage in any other conduct that restricts or inhibits anyone's use of the Platform</li>
                    </ul>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">4.2 Content Standards</h3>
                    <p class="text-gray-700 mb-4">
                        All content you submit must be accurate, not misleading, and comply with applicable laws. You retain ownership of your content but grant us a license to use it in connection with the Platform.
                    </p>
                </div>

                <!-- Service Providers -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">5. Service Providers</h2>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">5.1 Provider Requirements</h3>
                    <p class="text-gray-700 mb-4">
                        To become a Provider, you must:
                    </p>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700 mb-4">
                        <li>Complete our verification process</li>
                        <li>Provide accurate information about your services</li>
                        <li>Maintain appropriate licenses and insurance as required by law</li>
                        <li>Deliver services professionally and as described</li>
                    </ul>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">5.2 Service Standards</h3>
                    <p class="text-gray-700 mb-4">
                        Providers must maintain high service standards, respond to bookings promptly, and treat customers with respect and professionalism.
                    </p>
                </div>

                <!-- Bookings and Payments -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">6. Bookings and Payments</h2>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">6.1 Booking Process</h3>
                    <p class="text-gray-700 mb-4">
                        Customers can book services through the Platform. All bookings are subject to Provider availability and confirmation.
                    </p>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">6.2 Payment Terms</h3>
                    <p class="text-gray-700 mb-4">
                        Payment is required at the time of booking. We charge a 5% commission on completed bookings. Refunds are subject to our refund policy.
                    </p>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">6.3 Cancellation Policy</h3>
                    <p class="text-gray-700 mb-4">
                        Bookings may be cancelled up to 24 hours before the scheduled time. Cancellation policies may vary by Provider and service type.
                    </p>
                </div>

                <!-- Intellectual Property -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">7. Intellectual Property</h2>
                    <p class="text-gray-700 mb-4">
                        The Platform and its original content, features, and functionality are owned by D'RANiK Techs and are protected by international copyright, trademark, patent, trade secret, and other intellectual property laws.
                    </p>
                </div>

                <!-- Privacy -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">8. Privacy</h2>
                    <p class="text-gray-700 mb-4">
                        Your privacy is important to us. Please review our Privacy Policy, which also governs your use of the Platform, to understand our practices.
                    </p>
                </div>

                <!-- Disclaimers -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">9. Disclaimers</h2>
                    <p class="text-gray-700 mb-4">
                        The Platform is provided on an "as is" and "as available" basis. We make no representations or warranties of any kind, express or implied, regarding the Platform or the services provided through it.
                    </p>
                    <p class="text-gray-700 mb-4">
                        We are not responsible for the quality, safety, or legality of services provided by Providers, or the truth or accuracy of Provider listings.
                    </p>
                </div>

                <!-- Limitation of Liability -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">10. Limitation of Liability</h2>
                    <p class="text-gray-700 mb-4">
                        In no event shall D'RANiK Techs be liable for any indirect, incidental, special, consequential, or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses.
                    </p>
                </div>

                <!-- Indemnification -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">11. Indemnification</h2>
                    <p class="text-gray-700 mb-4">
                        You agree to defend, indemnify, and hold harmless D'RANiK Techs from and against any claims, damages, obligations, losses, liabilities, costs, or debt, and expenses (including attorney's fees) arising from your use of the Platform.
                    </p>
                </div>

                <!-- Dispute Resolution -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">12. Dispute Resolution</h2>
                    <p class="text-gray-700 mb-4">
                        Any disputes arising out of or relating to these Terms or the Platform shall be resolved through binding arbitration in accordance with the laws of Nigeria.
                    </p>
                </div>

                <!-- Changes to Terms -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">13. Changes to Terms</h2>
                    <p class="text-gray-700 mb-4">
                        We reserve the right to modify these Terms at any time. We will notify users of any material changes via email or through the Platform. Continued use of the Platform after changes constitutes acceptance of the new Terms.
                    </p>
                </div>

                <!-- Contact Information -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">14. Contact Information</h2>
                    <p class="text-gray-700 mb-4">
                        If you have any questions about these Terms, please contact us at:
                    </p>
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <p class="text-gray-700">
                            <strong>D'RANiK Techs</strong><br>
                            Email: legal@draniktech.com<br>
                            Phone: +234 801 234 5678<br>
                            Address: 123 Innovation Drive, Victoria Island, Lagos, Nigeria
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Questions About Our Terms?</h2>
            <p class="text-lg text-gray-600 mb-8">
                Our legal team is here to help clarify any questions you may have.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('contact') }}" 
                   class="inline-flex items-center px-6 py-3 bg-black text-white font-semibold rounded-lg hover:bg-gray-800 transition-colors duration-200">
                    <i data-feather="mail" class="w-5 h-5 mr-2"></i>
                    Contact Legal Team
                </a>
                <a href="{{ route('help') }}" 
                   class="inline-flex items-center px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <i data-feather="help-circle" class="w-5 h-5 mr-2"></i>
                    View FAQs
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

