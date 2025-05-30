<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import "../../../public/css/all.min.css"
import "../../../public/js/tailwind.js"

</script>

<template>
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <img src="img/logo.png" alt="logo" style="width: 85px; height: 85px;">
                        <span class="text-indigo-700 text-xl font-bold">Metricash</span>
                    </div>
                </div>
                <div class="hidden md:ml-6 md:flex md:items-center md:space-x-8">
                    <a href="#features"
                        class="text-gray-500 hover:text-primary px-3 py-2 text-sm font-medium">Features</a>
                    <a href="#how-it-works" class="text-gray-500 hover:text-primary px-3 py-2 text-sm font-medium">How
                        It Works</a>
                    <a href="#pricing"
                        class="text-gray-500 hover:text-primary px-3 py-2 text-sm font-medium">Pricing</a>
                 
                    <a href="login"
                        class="bg-blue-700 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded">
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="gradient-bg text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28">
            <div class="md:flex md:items-center md:justify-between">
                <div class="md:w-1/2 mb-10 md:mb-0">
                    <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-6">Take Control of Your Financial Future
                    </h1>
                    <p class="text-xl mb-8 opacity-90">
                        Metricash helps businesses and individuals manage their
                        finances with precision, automation, and real-time insights.
                    </p>
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="#signup"
                            class="bg-blue-700 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded">
                            Start Free Trial
                        </a>
                    </div>
                </div>
                <div class="md:w-1/2 flex justify-center">
                    <div class="relative w-full max-w-lg">
                        <div class="absolute top-0 left-0 w-full h-full bg-white opacity-10 rounded-xl"></div>
                        <div
                            class="relative bg-white bg-opacity-10 backdrop-filter backdrop-blur-lg rounded-xl p-6 shadow-2xl border border-white border-opacity-20">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-xl font-semibold">Dashboard Preview</h3>
                                <span class="text-sm bg-white bg-opacity-20 px-3 py-1 rounded-full">Live Data</span>
                            </div>
                            <div class="grid grid-cols-3 gap-4 mb-6">
                                <div class="bg-white bg-opacity-10 p-3 rounded-lg">
                                    <p class="text-sm opacity-80">Income</p>
                                    <p class="text-2xl font-bold">$12,450</p>
                                </div>
                                <div class="bg-white bg-opacity-10 p-3 rounded-lg">
                                    <p class="text-sm opacity-80">Expenses</p>
                                    <p class="text-2xl font-bold">$8,720</p>
                                </div>
                                <div class="bg-white bg-opacity-10 p-3 rounded-lg">
                                    <p class="text-sm opacity-80">Savings</p>
                                    <p class="text-2xl font-bold">$3,730</p>
                                </div>
                            </div>
                            <div class="h-48 bg-white bg-opacity-5 rounded-lg mb-4 flex items-center justify-center">
                                <i class="fas fa-chart-bar text-4xl opacity-50"></i>
                            </div>
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm opacity-80">Financial Health</p>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-green-400 h-2.5 rounded-full" style="width: 78%"></div>
                                    </div>
                                </div>
                                <span class="text-green-400 font-semibold">78%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-dark mb-4">Powerful Features for Financial Control</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">FinTrack Pro provides everything you need to take
                    complete control of your finances with confidence.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-8 rounded-xl shadow-md feature-card transition duration-300">
                    <div class="w-14 h-14 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-chart-pie text-primary text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Real-Time Analytics</h3>
                    <p class="text-gray-600">Get instant insights into your financial health with our powerful analytics
                        dashboard that updates in real-time.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white p-8 rounded-xl shadow-md feature-card transition duration-300">
                    <div class="w-14 h-14 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-robot text-primary text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">AI-Powered Forecasting</h3>
                    <p class="text-gray-600">Our advanced algorithms predict future cash flow and help you make informed
                        financial decisions.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white p-8 rounded-xl shadow-md feature-card transition duration-300">
                    <div class="w-14 h-14 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-shield-alt text-primary text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Bank-Level Security</h3>
                    <p class="text-gray-600">Your financial data is protected with 256-bit encryption and multi-factor
                        authentication.</p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-white p-8 rounded-xl shadow-md feature-card transition duration-300">
                    <div class="w-14 h-14 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-sync-alt text-primary text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Automated Reconciliation</h3>
                    <p class="text-gray-600">Save hours each month with automatic bank reconciliation that matches
                        transactions with ease.</p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-white p-8 rounded-xl shadow-md feature-card transition duration-300">
                    <div class="w-14 h-14 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-file-invoice-dollar text-primary text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Custom Reporting</h3>
                    <p class="text-gray-600">Create tailored financial reports with just a few clicks to meet your
                        specific business needs.</p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-white p-8 rounded-xl shadow-md feature-card transition duration-300">
                    <div class="w-14 h-14 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-mobile-alt text-primary text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Mobile Accessibility</h3>
                    <p class="text-gray-600">Manage your finances on the go with our fully responsive mobile app
                        available on all devices.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-dark mb-4">Simple Yet Powerful Financial Control</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">Get started in minutes and experience the difference
                    of professional financial management.</p>
            </div>

            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-10 md:mb-0">
                    <div class="relative">
                        <div class="absolute -left-8 -top-8 w-32 h-32 bg-primary bg-opacity-10 rounded-full z-0"></div>
                        <div class="relative z-10 bg-gray-50 p-8 rounded-xl shadow-lg">
                            <img src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80"
                                alt="FinTrack Pro interface" class="rounded-lg shadow-md">
                        </div>
                    </div>
                </div>
                <div class="md:w-1/2 md:pl-12">
                    <div class="space-y-8">
                        <!-- Step 1 -->
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div
                                    class="flex items-center justify-center h-12 w-12 rounded-md bg-primary text-white font-bold">
                                    1</div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-dark">Connect Your Accounts</h3>
                                <p class="mt-2 text-gray-600">Securely link your bank accounts, credit cards, and
                                    investment portfolios in just a few clicks.</p>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div
                                    class="flex items-center justify-center h-12 w-12 rounded-md bg-primary text-white font-bold">
                                    2</div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-dark">Set Your Financial Goals</h3>
                                <p class="mt-2 text-gray-600">Define your savings targets, debt reduction plans, and
                                    investment objectives.</p>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div
                                    class="flex items-center justify-center h-12 w-12 rounded-md bg-primary text-white font-bold">
                                    3</div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-dark">Monitor & Optimize</h3>
                                <p class="mt-2 text-gray-600">Receive personalized recommendations to improve your
                                    financial health and track progress.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12">
                        <a href="#signup"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary hover:bg-secondary transition duration-300">
                            Start Your Free Trial
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-dark mb-4">Simple, Transparent Pricing</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">Choose the plan that fits your financial management
                    needs.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <!-- Basic Plan -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden pricing-card transition duration-300">
                    <div class="px-6 py-8">
                        <h3 class="text-2xl font-medium text-gray-900 mb-2">Starter</h3>
                        <p class="text-gray-600 mb-6">Perfect for individuals and freelancers</p>
                        <div class="flex items-baseline mb-6">
                            <span class="text-4xl font-bold text-dark">$9</span>
                            <span class="ml-1 text-lg font-medium text-gray-500">/month</span>
                        </div>
                        <ul class="space-y-4 mb-8">
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span>Up to 5 accounts</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span>Basic budgeting tools</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span>Monthly reports</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span>Email support</span>
                            </li>
                        </ul>
                    </div>
                    <div class="px-6 py-4 bg-gray-50">
                        <a href="#signup"
                            class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-primary hover:bg-secondary transition duration-300">
                            Get Started
                        </a>
                    </div>
                </div>

                <!-- Professional Plan -->
                <div
                    class="bg-white rounded-xl shadow-lg overflow-hidden pricing-card transform scale-105 border-2 border-primary transition duration-300">
                    <div class="px-6 py-8">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-2xl font-medium text-gray-900 mb-2">Professional</h3>
                                <p class="text-gray-600 mb-6">Ideal for small businesses</p>
                            </div>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary bg-opacity-10 text-primary">Popular</span>
                        </div>
                        <div class="flex items-baseline mb-6">
                            <span class="text-4xl font-bold text-dark">$29</span>
                            <span class="ml-1 text-lg font-medium text-gray-500">/month</span>
                        </div>
                        <ul class="space-y-4 mb-8">
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span>Up to 15 accounts</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span>Advanced analytics</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span>Custom reports</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span>Priority support</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span>Team collaboration</span>
                            </li>
                        </ul>
                    </div>
                    <div class="px-6 py-4 bg-gray-50">
                        <a href="#signup"
                            class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-primary hover:bg-secondary transition duration-300">
                            Get Started
                        </a>
                    </div>
                </div>

            </div>

            <div class="mt-12 text-center">
                <p class="text-gray-600">Need something custom? <a href="#contact"
                        class="text-primary font-medium hover:underline">Contact our sales team</a></p>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-dark mb-4">Frequently Asked Questions</h2>
                <p class="text-lg text-gray-600">Everything you need to know about FinTrack Pro</p>
            </div>

            <div class="space-y-6">
                <!-- FAQ 1 -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <button class="flex justify-between items-center w-full text-left" onclick="toggleFAQ(1)">
                        <h3 class="text-lg font-medium text-dark">Is my financial data secure with FinTrack Pro?</h3>
                        <i class="fas fa-chevron-down text-primary transition-transform duration-300"
                            id="faq-icon-1"></i>
                    </button>
                    <div class="mt-4 text-gray-600 hidden" id="faq-content-1">
                        <p>Absolutely. We use bank-level 256-bit encryption to protect your data, and we never store
                            your banking credentials. Our security protocols are regularly audited by independent third
                            parties to ensure compliance with the highest industry standards.</p>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <button class="flex justify-between items-center w-full text-left" onclick="toggleFAQ(2)">
                        <h3 class="text-lg font-medium text-dark">How does the free trial work?</h3>
                        <i class="fas fa-chevron-down text-primary transition-transform duration-300"
                            id="faq-icon-2"></i>
                    </button>
                    <div class="mt-4 text-gray-600 hidden" id="faq-content-2">
                        <p>Our 14-day free trial gives you full access to all features of the Professional plan. No
                            credit card is required to start. At the end of the trial period, you can choose to continue
                            with any of our paid plans or cancel with no obligation.</p>
                    </div>
                </div>

                <!-- FAQ 3 -->
                <!-- <div class="bg-white p-6 rounded-lg shadow-md">
                    <button class="flex justify-between items-center w-full text-left" onclick="toggleFAQ(3)">
                        <h3 class="text-lg font-medium text-dark">Can I connect multiple bank accounts?</h3>
                        <i class="fas fa-chevron-down text-primary transition-transform duration-300" id="faq-icon-3"></i>
                    </button>
                    <div class="mt-4 text-gray-600 hidden" id="faq-content-3">
                        <p>Yes! Depending on your plan, you can connect between 5 (Starter) to unlimited (Enterprise) bank accounts. We support connections with over 10,000 financial institutions in North America, Europe, and Australia.</p>
                    </div>
                </div> -->

                <!-- FAQ 4 -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <button class="flex justify-between items-center w-full text-left" onclick="toggleFAQ(4)">
                        <h3 class="text-lg font-medium text-dark">What if I need help setting up my account?</h3>
                        <i class="fas fa-chevron-down text-primary transition-transform duration-300"
                            id="faq-icon-4"></i>
                    </button>
                    <div class="mt-4 text-gray-600 hidden" id="faq-content-4">
                        <p>We offer comprehensive onboarding assistance for all new customers. Professional and
                            Enterprise plan subscribers receive priority support, including one-on-one setup sessions
                            with our financial experts.</p>
                    </div>
                </div>

                <!-- FAQ 5 -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <button class="flex justify-between items-center w-full text-left" onclick="toggleFAQ(5)">
                        <h3 class="text-lg font-medium text-dark">Can I cancel my subscription anytime?</h3>
                        <i class="fas fa-chevron-down text-primary transition-transform duration-300"
                            id="faq-icon-5"></i>
                    </button>
                    <div class="mt-4 text-gray-600 hidden" id="faq-content-5">
                        <p>Yes, you can cancel your subscription at any time through your account settings. We don't
                            believe in locking customers into long-term contracts. If you cancel, you'll continue to
                            have access until the end of your current billing period.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">FinTrack Pro</h3>
                    <p class="text-gray-400">Advanced financial control for businesses and individuals. Take charge of
                        your financial future today.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Product</h3>
                    <ul class="space-y-2">
                        <li><a href="#features"
                                class="text-gray-400 hover:text-white transition duration-300">Features</a></li>
                        <li><a href="#pricing"
                                class="text-gray-400 hover:text-white transition duration-300">Pricing</a></li>
                        <li><a href="#how-it-works" class="text-gray-400 hover:text-white transition duration-300">How
                                It Works</a></li>
                        <li><a href="#signup" class="text-gray-400 hover:text-white transition duration-300">Free
                                Trial</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Company</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">About Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Careers</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Blog</a></li>
                        <li><a href="#contact"
                                class="text-gray-400 hover:text-white transition duration-300">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Legal</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Privacy
                                Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Terms of
                                Service</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Security</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">GDPR</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-gray-800 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 mb-4 md:mb-0">© 2023 FinTrack Pro. All rights reserved.</p>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-white">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

body {
    font-family: 'Inter', sans-serif;
}

.gradient-bg {
    background: linear-gradient(135deg, rgb(67 56 202) 0%, rgb(85, 75, 196) 100%);
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.pricing-card:hover {
    transform: scale(1.03);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.testimonial-card {
    background: linear-gradient(145deg, #ffffff 0%, #f9fafb 100%);
}
</style>