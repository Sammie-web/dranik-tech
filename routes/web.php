<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// CMS Pages
Route::get('/about', [\App\Http\Controllers\CmsController::class, 'about'])->name('about');
Route::get('/contact', [\App\Http\Controllers\CmsController::class, 'contact'])->name('contact');
Route::post('/contact', [\App\Http\Controllers\CmsController::class, 'submitContact'])->name('contact.submit');
Route::get('/terms', [\App\Http\Controllers\CmsController::class, 'terms'])->name('terms');
Route::get('/privacy', [\App\Http\Controllers\CmsController::class, 'privacy'])->name('privacy');
Route::get('/help', [\App\Http\Controllers\CmsController::class, 'help'])->name('help');
Route::get('/faq', [\App\Http\Controllers\CmsController::class, 'help'])->name('faq');
Route::get('/support', [\App\Http\Controllers\CmsController::class, 'contact'])->name('support');
Route::get('/blog', [\App\Http\Controllers\CmsController::class, 'blog'])->name('blog.index');
Route::get('/blog/{id}', [\App\Http\Controllers\CmsController::class, 'blogPost'])->name('blog.post');

// Provider Registration (redirect to regular register with provider role)
Route::get('/provider/register', function () {
    return redirect()->route('register', ['role' => 'provider']);
})->name('provider.register');

// Payment callback and webhook routes (public)
Route::get('/payments/callback/{gateway}', [\App\Http\Controllers\PaymentController::class, 'callback'])->name('payments.callback');
Route::post('/payments/webhook/{gateway}', [\App\Http\Controllers\PaymentController::class, 'webhook'])->name('payments.webhook');

// Authenticated Routes
Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->isProvider()) {
        return redirect()->route('provider.dashboard');
    } else {
        return redirect()->route('customer.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Role-specific dashboard routes
Route::get('/customer/dashboard', function () {
    return view('customer.dashboard');
})->middleware(['auth', 'verified'])->name('customer.dashboard');

Route::get('/provider/dashboard', function () {
    return view('provider.dashboard');
})->middleware(['auth', 'verified'])->name('provider.dashboard');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('admin.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Routes requiring verified email
    Route::middleware('verified')->group(function () {
        // Booking routes
        Route::get('/services/{service}/book', [\App\Http\Controllers\BookingController::class, 'create'])->name('bookings.create');
        Route::post('/services/{service}/book', [\App\Http\Controllers\BookingController::class, 'store'])->name('bookings.store');
        Route::get('/bookings/{booking}', [\App\Http\Controllers\BookingController::class, 'show'])->name('bookings.show');
        Route::get('/bookings/{booking}/payment', [\App\Http\Controllers\BookingController::class, 'payment'])->name('bookings.payment');
        Route::patch('/bookings/{booking}/cancel', [\App\Http\Controllers\BookingController::class, 'cancel'])->name('bookings.cancel');
        Route::patch('/bookings/{booking}/confirm', [\App\Http\Controllers\BookingController::class, 'confirm'])->name('bookings.confirm');
        Route::patch('/bookings/{booking}/complete', [\App\Http\Controllers\BookingController::class, 'complete'])->name('bookings.complete');

        // Customer booking history and receipt
        Route::get('/my/bookings', [\App\Http\Controllers\BookingController::class, 'history'])->name('bookings.history');
        Route::get('/bookings/{booking}/receipt', [\App\Http\Controllers\BookingController::class, 'receipt'])->name('bookings.receipt');
        
        // Payment routes
        Route::post('/payments/{booking}/initialize', [\App\Http\Controllers\PaymentController::class, 'initialize'])->name('payments.initialize');
        Route::post('/payments/{payment}/refund', [\App\Http\Controllers\PaymentController::class, 'refund'])->name('payments.refund');
        
        // Review routes
        Route::get('/services/{service}/reviews', [\App\Http\Controllers\ReviewController::class, 'index'])->name('reviews.index');
        Route::get('/bookings/{booking}/review/create', [\App\Http\Controllers\ReviewController::class, 'create'])->name('reviews.create');
        Route::post('/bookings/{booking}/review', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
        Route::get('/reviews/{review}', [\App\Http\Controllers\ReviewController::class, 'show'])->name('reviews.show');
        Route::get('/reviews/{review}/edit', [\App\Http\Controllers\ReviewController::class, 'edit'])->name('reviews.edit');
        Route::patch('/reviews/{review}', [\App\Http\Controllers\ReviewController::class, 'update'])->name('reviews.update');
        Route::delete('/reviews/{review}', [\App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');
        Route::post('/reviews/{review}/report', [\App\Http\Controllers\ReviewController::class, 'report'])->name('reviews.report');
        Route::get('/providers/{provider}/reviews', [\App\Http\Controllers\ReviewController::class, 'providerReviews'])->name('reviews.provider');
        Route::get('/my-reviews', [\App\Http\Controllers\ReviewController::class, 'customerReviews'])->name('reviews.customer');
        
        // Admin review routes
        Route::patch('/reviews/{review}/toggle-featured', [\App\Http\Controllers\ReviewController::class, 'toggleFeatured'])->name('reviews.toggle-featured')->middleware('can:admin');
        
        // Notification routes
        Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
        Route::patch('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::patch('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::delete('/notifications/{notification}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
        
        // API routes for notifications (AJAX)
        Route::get('/api/notifications/count', [\App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('api.notifications.count');
        Route::get('/api/notifications/recent', [\App\Http\Controllers\NotificationController::class, 'getRecent'])->name('api.notifications.recent');

        // Provider routes (still protected by verified middleware)
        Route::middleware('verified')->group(function () {
            Route::prefix('provider')->group(function () {
                Route::get('/services/active', [\App\Http\Controllers\ProviderController::class, 'activeServices'])->name('provider.services.active');
                Route::get('/bookings', [\App\Http\Controllers\ProviderController::class, 'allBookings'])->name('provider.bookings');
                Route::get('/services/pending', [\App\Http\Controllers\ProviderController::class, 'pendingServices'])->name('provider.services.pending');
                Route::get('/withdrawals', [\App\Http\Controllers\ProviderController::class, 'withdrawals'])->name('provider.withdrawals');
                Route::get('/biodata', [\App\Http\Controllers\ProviderController::class, 'biodata'])->name('provider.biodata');
                Route::post('/biodata', [\App\Http\Controllers\ProviderController::class, 'updateBiodata'])->name('provider.biodata.update');
                Route::get('/payments', [\App\Http\Controllers\ProviderController::class, 'payments'])->name('provider.payments');
                Route::get('/chats', [\App\Http\Controllers\ProviderController::class, 'chatList'])->name('provider.chats');
                Route::get('/manage/services', [\App\Http\Controllers\ProviderController::class, 'manageServices'])->name('provider.manage.services');
                Route::get('/manage/categories', [\App\Http\Controllers\ProviderController::class, 'manageCategories'])->name('provider.manage.categories');
            });
        });

        // Chat routes per booking
        Route::get('/bookings/{booking}/chat', [\App\Http\Controllers\MessageController::class, 'thread'])->name('chat.thread');
        Route::post('/bookings/{booking}/chat', [\App\Http\Controllers\MessageController::class, 'send'])->name('chat.send');
    });

    // Admin routes
    Route::prefix('admin')->group(function () {
        Route::get('/users', [\App\Http\Controllers\AdminController::class, 'manageUsers'])->name('admin.users');
        Route::delete('/users/{user}', [\App\Http\Controllers\AdminController::class, 'destroyUser'])->name('admin.users.destroy');
        Route::get('/users/{user}/edit', [\App\Http\Controllers\AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::patch('/users/{user}', [\App\Http\Controllers\AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::get('/settings', [\App\Http\Controllers\AdminController::class, 'platformSettings'])->name('admin.settings');
        Route::get('/services', [\App\Http\Controllers\AdminController::class, 'manageServices'])->name('admin.services');
        Route::get('/audit', [\App\Http\Controllers\AdminController::class, 'audit'])->name('admin.audit');
        Route::get('/payments', [\App\Http\Controllers\AdminController::class, 'customerPayments'])->name('admin.payments');
        Route::get('/withdrawals', [\App\Http\Controllers\AdminController::class, 'vendorWithdrawals'])->name('admin.withdrawals');
        Route::post('/login-as/{user}', [\App\Http\Controllers\AdminController::class, 'loginAs'])->name('admin.login-as');
        Route::post('/stop-impersonation', [\App\Http\Controllers\AdminController::class, 'stopImpersonation'])->name('admin.stop-impersonation');
        Route::get('/vendors/pending', [\App\Http\Controllers\AdminController::class, 'pendingVendors'])->name('admin.vendors.pending');
        Route::post('/vendors/{vendor}/verify', [\App\Http\Controllers\AdminController::class, 'verifyVendor'])->name('admin.vendors.verify');
        Route::get('/categories', [\App\Http\Controllers\CategoryController::class, 'adminIndex'])->name('admin.categories');
        Route::post('/categories', [\App\Http\Controllers\CategoryController::class, 'store'])->name('admin.categories.store');
        Route::patch('/categories/{category}', [\App\Http\Controllers\CategoryController::class, 'update'])->name('admin.categories.update');
        Route::delete('/categories/{category}', [\App\Http\Controllers\CategoryController::class, 'destroy'])->name('admin.categories.destroy');
        Route::patch('/services/{service}/category', [\App\Http\Controllers\CategoryController::class, 'assignServiceCategory'])->name('admin.services.assign-category');
        Route::get('/commissions', [\App\Http\Controllers\AdminController::class, 'commissions'])->name('admin.commissions');
        Route::get('/gateways', [\App\Http\Controllers\AdminController::class, 'gateways'])->name('admin.gateways');
        Route::get('/broadcasts', [\App\Http\Controllers\AdminController::class, 'broadcasts'])->name('admin.broadcasts');
    });

    // Chat routes per booking
    Route::get('/bookings/{booking}/chat', [\App\Http\Controllers\MessageController::class, 'thread'])->name('chat.thread');
    Route::post('/bookings/{booking}/chat', [\App\Http\Controllers\MessageController::class, 'send'])->name('chat.send');
});

// Admin vendor management routes
Route::prefix('admin/vendors')->middleware(['auth'])->group(function () {
    Route::get('pending', [\App\Http\Controllers\AdminController::class, 'pendingVendors'])->name('admin.vendors.pending');
    Route::get('{vendor}/review', [\App\Http\Controllers\AdminController::class, 'reviewVendor'])->name('admin.vendors.review');
    Route::post('{vendor}/approve', [\App\Http\Controllers\AdminController::class, 'approveVendor'])->name('admin.vendors.approve');
    Route::post('{vendor}/reject', [\App\Http\Controllers\AdminController::class, 'rejectVendor'])->name('admin.vendors.reject');
});

require __DIR__.'/auth.php';
