<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use App\Models\Review;

class CmsController extends Controller
{
    /**
     * About Us page
     */
    public function about()
    {
        $stats = [
            'total_services' => Service::active()->count(),
            'total_providers' => User::where('role', 'provider')->where('is_active', true)->count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_reviews' => Review::verified()->count(),
            'average_rating' => Review::verified()->avg('rating') ?? 0,
        ];

        return view('cms.about', compact('stats'));
    }

    /**
     * Contact Us page
     */
    public function contact()
    {
        return view('cms.contact');
    }

    /**
     * Submit contact form
     */
    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // In a real application, you would send an email or store in database
        // For now, we'll just log it and show a success message
        \Log::info('Contact form submission', [
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Thank you for your message! We\'ll get back to you within 24 hours.');
    }

    /**
     * Terms of Service page
     */
    public function terms()
    {
        return view('cms.terms');
    }

    /**
     * Privacy Policy page
     */
    public function privacy()
    {
        return view('cms.privacy');
    }

    /**
     * Help/FAQ page
     */
    public function help()
    {
        $faqs = [
            [
                'category' => 'Getting Started',
                'questions' => [
                    [
                        'question' => 'How do I create an account?',
                        'answer' => 'Click on "Sign Up" in the top right corner and choose whether you want to book services (Customer) or provide services (Service Provider). Fill in your details and verify your email address.'
                    ],
                    [
                        'question' => 'Is it free to join D\'RANiK Techs?',
                        'answer' => 'Yes! Creating an account is completely free for both customers and service providers. We only charge a small commission on completed bookings.'
                    ],
                    [
                        'question' => 'How do I find services in my area?',
                        'answer' => 'Use our search function on the homepage or browse by categories. You can filter by location, price range, ratings, and availability to find the perfect service provider.'
                    ]
                ]
            ],
            [
                'category' => 'Booking Services',
                'questions' => [
                    [
                        'question' => 'How do I book a service?',
                        'answer' => 'Find the service you want, click "Book Now", select your preferred date and time, provide any special instructions, and proceed to payment. You\'ll receive a confirmation once your booking is complete.'
                    ],
                    [
                        'question' => 'Can I cancel or reschedule my booking?',
                        'answer' => 'Yes, you can cancel or request to reschedule your booking up to 24 hours before the scheduled time. Cancellation policies may vary by service provider.'
                    ],
                    [
                        'question' => 'What payment methods do you accept?',
                        'answer' => 'We accept all major credit/debit cards, bank transfers, and mobile money through our secure payment partners Paystack and Flutterwave.'
                    ]
                ]
            ],
            [
                'category' => 'For Service Providers',
                'questions' => [
                    [
                        'question' => 'How do I become a service provider?',
                        'answer' => 'Sign up as a service provider, complete your profile with photos and descriptions of your services, set your availability and pricing, and wait for approval. Once verified, you can start receiving bookings.'
                    ],
                    [
                        'question' => 'How much commission do you charge?',
                        'answer' => 'We charge a 5% commission on completed bookings. This covers payment processing, platform maintenance, and customer support.'
                    ],
                    [
                        'question' => 'When do I get paid?',
                        'answer' => 'Payments are released to your account within 24-48 hours after service completion and customer confirmation. You can track your earnings in your provider dashboard.'
                    ]
                ]
            ],
            [
                'category' => 'Safety & Trust',
                'questions' => [
                    [
                        'question' => 'How do you verify service providers?',
                        'answer' => 'All service providers go through a verification process including identity verification, background checks where applicable, and review of their qualifications and experience.'
                    ],
                    [
                        'question' => 'What if I\'m not satisfied with a service?',
                        'answer' => 'We have a customer satisfaction guarantee. If you\'re not happy with a service, contact our support team within 24 hours and we\'ll work to resolve the issue, including potential refunds.'
                    ],
                    [
                        'question' => 'Is my personal information safe?',
                        'answer' => 'Yes, we use industry-standard security measures to protect your personal and payment information. We never share your data with third parties without your consent.'
                    ]
                ]
            ]
        ];

        return view('cms.help', compact('faqs'));
    }

    /**
     * Blog index page
     */
    public function blog()
    {
        // Sample blog posts - in a real app, these would come from a database
        $posts = [
            [
                'id' => 1,
                'title' => '10 Tips for Choosing the Right Home Service Provider',
                'excerpt' => 'Finding reliable home service providers can be challenging. Here are our top tips to help you make the right choice for your needs.',
                'image' => 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'category' => 'Home Services',
                'author' => 'D\'RANiK Team',
                'published_at' => now()->subDays(3),
                'read_time' => '5 min read'
            ],
            [
                'id' => 2,
                'title' => 'The Future of Service Marketplaces in Africa',
                'excerpt' => 'Exploring how digital platforms are transforming the service industry across African markets and what it means for consumers and providers.',
                'image' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'category' => 'Industry Insights',
                'author' => 'Sarah Johnson',
                'published_at' => now()->subDays(7),
                'read_time' => '8 min read'
            ],
            [
                'id' => 3,
                'title' => 'Building Trust in the Digital Service Economy',
                'excerpt' => 'How reviews, ratings, and verification systems are creating safer and more reliable service marketplaces for everyone.',
                'image' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'category' => 'Trust & Safety',
                'author' => 'Michael Brown',
                'published_at' => now()->subDays(10),
                'read_time' => '6 min read'
            ],
            [
                'id' => 4,
                'title' => 'Maximizing Your Earnings as a Service Provider',
                'excerpt' => 'Practical strategies for service providers to increase their bookings, improve customer satisfaction, and grow their business on our platform.',
                'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'category' => 'Provider Tips',
                'author' => 'D\'RANiK Team',
                'published_at' => now()->subDays(14),
                'read_time' => '7 min read'
            ]
        ];

        return view('cms.blog', compact('posts'));
    }

    /**
     * Individual blog post page
     */
    public function blogPost($id)
    {
        // Sample blog post content - in a real app, this would come from a database
        $posts = [
            1 => [
                'title' => '10 Tips for Choosing the Right Home Service Provider',
                'content' => 'When it comes to finding reliable home service providers, making the right choice can save you time, money, and stress...',
                'image' => 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'category' => 'Home Services',
                'author' => 'D\'RANiK Team',
                'published_at' => now()->subDays(3),
                'read_time' => '5 min read'
            ]
        ];

        $post = $posts[$id] ?? abort(404);

        return view('cms.blog-post', compact('post'));
    }
}
