@extends('layouts.main')

@section('title', 'Contact Us - D\'RANiK Techs')
@section('description', 'Get in touch with D\'RANiK Techs. We\'re here to help with any questions about our service marketplace platform.')

@section('content')
<div class="bg-white">
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-blue-50 to-indigo-100 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                    Contact Us
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Have a question or need support? We're here to help! Reach out to us and we'll get back to you as soon as possible.
                </p>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Form -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Send us a Message</h2>
                    
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <i data-feather="check-circle" class="w-5 h-5 text-green-600 mr-3"></i>
                                <p class="text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact.submit') }}" x-data="contactForm()">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Full Name *
                                </label>
                                <input type="text" id="name" name="name" required
                                       value="{{ old('name') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition-colors duration-200"
                                       placeholder="Your full name">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Address *
                                </label>
                                <input type="email" id="email" name="email" required
                                       value="{{ old('email') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition-colors duration-200"
                                       placeholder="your@email.com">
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                                Subject *
                            </label>
                            <select id="subject" name="subject" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition-colors duration-200">
                                <option value="">Select a subject</option>
                                <option value="General Inquiry" {{ old('subject') == 'General Inquiry' ? 'selected' : '' }}>General Inquiry</option>
                                <option value="Technical Support" {{ old('subject') == 'Technical Support' ? 'selected' : '' }}>Technical Support</option>
                                <option value="Billing Question" {{ old('subject') == 'Billing Question' ? 'selected' : '' }}>Billing Question</option>
                                <option value="Provider Application" {{ old('subject') == 'Provider Application' ? 'selected' : '' }}>Provider Application</option>
                                <option value="Partnership" {{ old('subject') == 'Partnership' ? 'selected' : '' }}>Partnership Opportunity</option>
                                <option value="Report Issue" {{ old('subject') == 'Report Issue' ? 'selected' : '' }}>Report an Issue</option>
                                <option value="Other" {{ old('subject') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('subject')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-6">
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                Message *
                            </label>
                            <textarea id="message" name="message" rows="6" required
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition-colors duration-200"
                                      placeholder="Tell us how we can help you..."
                                      maxlength="2000" x-ref="message">{{ old('message') }}</textarea>
                            <div class="mt-1 text-sm text-gray-500">
                                <span x-text="$refs.message ? $refs.message.value.length : 0">{{ strlen(old('message', '')) }}</span>/2000 characters
                            </div>
                            @error('message')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <button type="submit" 
                                :disabled="isSubmitting"
                                :class="{
                                    'bg-gray-400 cursor-not-allowed': isSubmitting,
                                    'bg-black hover:bg-gray-800': !isSubmitting
                                }"
                                class="w-full text-white py-3 px-6 rounded-lg font-semibold transition-colors duration-200 flex items-center justify-center">
                            <span x-show="!isSubmitting">Send Message</span>
                            <span x-show="isSubmitting" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Sending...
                            </span>
                        </button>
                    </form>
                </div>

                <!-- Contact Information -->
                <div class="space-y-8">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Get in Touch</h2>
                        <p class="text-gray-600 mb-8">
                            We're committed to providing excellent customer service. Choose the best way to reach us based on your needs.
                        </p>
                    </div>

                    <!-- Contact Methods -->
                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i data-feather="mail" class="w-6 h-6 text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Email Support</h3>
                                <p class="text-gray-600 mb-2">For general inquiries and support</p>
                                <a href="mailto:support@draniktech.com" class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                    support@draniktech.com
                                </a>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i data-feather="phone" class="w-6 h-6 text-green-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Phone Support</h3>
                                <p class="text-gray-600 mb-2">Monday - Friday, 9AM - 6PM WAT</p>
                                <a href="tel:+2348012345678" class="text-green-600 hover:text-green-800 transition-colors duration-200">
                                    +234 801 234 5678
                                </a>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i data-feather="message-circle" class="w-6 h-6 text-purple-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Live Chat</h3>
                                <p class="text-gray-600 mb-2">Available 24/7 for urgent issues</p>
                                <button onclick="openChat()" class="text-purple-600 hover:text-purple-800 transition-colors duration-200">
                                    Start Live Chat
                                </button>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i data-feather="map-pin" class="w-6 h-6 text-yellow-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Office Address</h3>
                                <p class="text-gray-600">
                                    123 Innovation Drive<br>
                                    Victoria Island, Lagos<br>
                                    Nigeria
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Response Times -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Response Times</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">General Inquiries</span>
                                <span class="text-sm font-medium text-gray-900">Within 24 hours</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Technical Support</span>
                                <span class="text-sm font-medium text-gray-900">Within 4 hours</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Urgent Issues</span>
                                <span class="text-sm font-medium text-gray-900">Within 1 hour</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Quick answers to common questions. Can't find what you're looking for? Contact us directly.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">How do I create an account?</h3>
                    <p class="text-gray-600">
                        Click "Sign Up" and choose whether you want to book services or provide them. Complete the registration form and verify your email.
                    </p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Is my payment information secure?</h3>
                    <p class="text-gray-600">
                        Yes, we use industry-standard encryption and work with trusted payment partners like Paystack and Flutterwave.
                    </p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">How are service providers verified?</h3>
                    <p class="text-gray-600">
                        All providers go through identity verification, background checks, and skill assessments before being approved.
                    </p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">What if I'm not satisfied with a service?</h3>
                    <p class="text-gray-600">
                        We offer a satisfaction guarantee. Contact us within 24 hours and we'll work to resolve any issues.
                    </p>
                </div>
            </div>

            <div class="text-center mt-8">
                <a href="{{ route('help') }}" 
                   class="inline-flex items-center px-6 py-3 bg-black text-white font-semibold rounded-lg hover:bg-gray-800 transition-colors duration-200">
                    <i data-feather="help-circle" class="w-5 h-5 mr-2"></i>
                    View All FAQs
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('contactForm', () => ({
            isSubmitting: false,
            
            init() {
                // Character counter for message
                this.$refs.message.addEventListener('input', () => {
                    // Alpine will handle the character count display
                });
            }
        }))
    });

    function openChat() {
        // In a real application, this would open a chat widget
        alert('Live chat feature coming soon! Please use the contact form or email us directly.');
    }
</script>
@endpush
@endsection

