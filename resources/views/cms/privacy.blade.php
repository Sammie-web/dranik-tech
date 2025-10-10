@extends('layouts.main')

@section('title', 'Privacy Policy - D\'RANIK Techs')
@section('description', 'Learn how D\'RANIK Techs protects your privacy and handles your personal information on our service marketplace platform.')

@section('content')
<div class="bg-white">
    <!-- Hero Section -->
    <div class="bg-gray-50 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Privacy Policy</h1>
                <p class="text-lg text-gray-600">
                    Last updated: {{ now()->format('F d, Y') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Privacy Content -->
    <div class="py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="prose prose-lg max-w-none">
                
                <!-- Introduction -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Introduction</h2>
                    <p class="text-gray-700 mb-4">
                        At D'RANIK Techs, we are committed to protecting your privacy and ensuring the security of your personal information. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our Platform.
                    </p>
                    <p class="text-gray-700 mb-4">
                        By using our Platform, you consent to the data practices described in this policy.
                    </p>
                </div>

                <!-- Information We Collect -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">2. Information We Collect</h2>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">2.1 Personal Information</h3>
                    <p class="text-gray-700 mb-4">We collect information you provide directly to us, such as:</p>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700 mb-6">
                        <li>Name, email address, and phone number</li>
                        <li>Profile information and photos</li>
                        <li>Payment information and billing address</li>
                        <li>Service preferences and booking history</li>
                        <li>Reviews and ratings</li>
                        <li>Communications with us</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-900 mb-3">2.2 Automatically Collected Information</h3>
                    <p class="text-gray-700 mb-4">When you use our Platform, we automatically collect:</p>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700 mb-6">
                        <li>Device information (IP address, browser type, operating system)</li>
                        <li>Usage data (pages visited, time spent, features used)</li>
                        <li>Location information (with your permission)</li>
                        <li>Cookies and similar tracking technologies</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-900 mb-3">2.3 Information from Third Parties</h3>
                    <p class="text-gray-700 mb-4">We may receive information from:</p>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700 mb-6">
                        <li>Payment processors</li>
                        <li>Identity verification services</li>
                        <li>Social media platforms (if you connect your accounts)</li>
                        <li>Other users (reviews, referrals)</li>
                    </ul>
                </div>

                <!-- How We Use Information -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">3. How We Use Your Information</h2>
                    <p class="text-gray-700 mb-4">We use your information to:</p>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700 mb-6">
                        <li>Provide and maintain our Platform</li>
                        <li>Process bookings and payments</li>
                        <li>Verify user identity and prevent fraud</li>
                        <li>Send notifications and updates</li>
                        <li>Improve our services and user experience</li>
                        <li>Provide customer support</li>
                        <li>Comply with legal obligations</li>
                        <li>Send marketing communications (with your consent)</li>
                    </ul>
                </div>

                <!-- Information Sharing -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">4. How We Share Your Information</h2>
                    <p class="text-gray-700 mb-4">We may share your information in the following circumstances:</p>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">4.1 With Other Users</h3>
                    <p class="text-gray-700 mb-4">
                        We share necessary information between customers and service providers to facilitate bookings (name, contact information, service details).
                    </p>

                    <h3 class="text-xl font-semibold text-gray-900 mb-3">4.2 With Service Providers</h3>
                    <p class="text-gray-700 mb-4">
                        We work with third-party service providers for payment processing, identity verification, and other business functions.
                    </p>

                    <h3 class="text-xl font-semibold text-gray-900 mb-3">4.3 Legal Requirements</h3>
                    <p class="text-gray-700 mb-4">
                        We may disclose information when required by law or to protect our rights, property, or safety.
                    </p>

                    <h3 class="text-xl font-semibold text-gray-900 mb-3">4.4 Business Transfers</h3>
                    <p class="text-gray-700 mb-4">
                        In the event of a merger, acquisition, or sale of assets, user information may be transferred.
                    </p>
                </div>

                <!-- Data Security -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">5. Data Security</h2>
                    <p class="text-gray-700 mb-4">
                        We implement appropriate technical and organizational measures to protect your personal information:
                    </p>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700 mb-6">
                        <li>Encryption of sensitive data in transit and at rest</li>
                        <li>Regular security assessments and updates</li>
                        <li>Access controls and authentication measures</li>
                        <li>Employee training on data protection</li>
                        <li>Incident response procedures</li>
                    </ul>
                    <p class="text-gray-700 mb-4">
                        However, no method of transmission over the internet is 100% secure, and we cannot guarantee absolute security.
                    </p>
                </div>

                <!-- Your Rights -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">6. Your Rights and Choices</h2>
                    <p class="text-gray-700 mb-4">You have the following rights regarding your personal information:</p>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">6.1 Access and Portability</h3>
                    <p class="text-gray-700 mb-4">
                        You can request a copy of your personal information and receive it in a structured format.
                    </p>

                    <h3 class="text-xl font-semibold text-gray-900 mb-3">6.2 Correction and Updates</h3>
                    <p class="text-gray-700 mb-4">
                        You can update your profile information at any time through your account settings.
                    </p>

                    <h3 class="text-xl font-semibold text-gray-900 mb-3">6.3 Deletion</h3>
                    <p class="text-gray-700 mb-4">
                        You can request deletion of your account and personal information, subject to legal retention requirements.
                    </p>

                    <h3 class="text-xl font-semibold text-gray-900 mb-3">6.4 Marketing Communications</h3>
                    <p class="text-gray-700 mb-4">
                        You can opt out of marketing emails by clicking the unsubscribe link or updating your preferences.
                    </p>
                </div>

                <!-- Cookies -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">7. Cookies and Tracking</h2>
                    <p class="text-gray-700 mb-4">
                        We use cookies and similar technologies to enhance your experience:
                    </p>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700 mb-6">
                        <li><strong>Essential Cookies:</strong> Required for basic Platform functionality</li>
                        <li><strong>Analytics Cookies:</strong> Help us understand how you use the Platform</li>
                        <li><strong>Marketing Cookies:</strong> Used to deliver relevant advertisements</li>
                        <li><strong>Preference Cookies:</strong> Remember your settings and preferences</li>
                    </ul>
                    <p class="text-gray-700 mb-4">
                        You can control cookies through your browser settings, but some features may not work properly if cookies are disabled.
                    </p>
                </div>

                <!-- Data Retention -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">8. Data Retention</h2>
                    <p class="text-gray-700 mb-4">
                        We retain your personal information for as long as necessary to:
                    </p>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700 mb-6">
                        <li>Provide our services</li>
                        <li>Comply with legal obligations</li>
                        <li>Resolve disputes</li>
                        <li>Enforce our agreements</li>
                    </ul>
                    <p class="text-gray-700 mb-4">
                        When information is no longer needed, we securely delete or anonymize it.
                    </p>
                </div>

                <!-- International Transfers -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">9. International Data Transfers</h2>
                    <p class="text-gray-700 mb-4">
                        Your information may be transferred to and processed in countries other than your own. We ensure appropriate safeguards are in place to protect your information during such transfers.
                    </p>
                </div>

                <!-- Children's Privacy -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">10. Children's Privacy</h2>
                    <p class="text-gray-700 mb-4">
                        Our Platform is not intended for children under 18. We do not knowingly collect personal information from children. If you believe we have collected information from a child, please contact us immediately.
                    </p>
                </div>

                <!-- Changes to Policy -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">11. Changes to This Policy</h2>
                    <p class="text-gray-700 mb-4">
                        We may update this Privacy Policy from time to time. We will notify you of any material changes by email or through the Platform. Your continued use after changes constitutes acceptance of the updated policy.
                    </p>
                </div>

                <!-- Contact Information -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">12. Contact Us</h2>
                    <p class="text-gray-700 mb-4">
                        If you have questions about this Privacy Policy or our data practices, please contact us:
                    </p>
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <p class="text-gray-700">
                            <strong>Data Protection Officer</strong><br>
                            Email: privacy@draniktech.com<br>
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
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Questions About Your Privacy?</h2>
            <p class="text-lg text-gray-600 mb-8">
                Our privacy team is here to help you understand how we protect your information.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('contact') }}" 
                   class="inline-flex items-center px-6 py-3 bg-black text-white font-semibold rounded-lg hover:bg-gray-800 transition-colors duration-200">
                    <i data-feather="shield" class="w-5 h-5 mr-2"></i>
                    Contact Privacy Team
                </a>
                <a href="{{ route('help') }}" 
                   class="inline-flex items-center px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <i data-feather="help-circle" class="w-5 h-5 mr-2"></i>
                    Privacy FAQs
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

