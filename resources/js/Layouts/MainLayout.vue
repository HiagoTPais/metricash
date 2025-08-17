<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <div :class="[
            'fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform',
            sidebarOpen ? 'translate-x-0' : '-translate-x-full',
            'md:translate-x-0 transition-transform duration-300 ease-in-out'
        ]">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-center h-16">
                    <img src="img/logo.png" class="w-20 h-20">
                    <span class="text-indigo-700 text-xl font-bold">Metricash</span>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
                    <Link v-for="item in navigation" :key="item.name" :href="item.href"
                        class="flex items-center px-4 py-2 text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 rounded-md group">
                    <svg v-if="item.icon !== 'text'" class="w-6 h-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon" />
                    </svg>
                    <span v-else class="w-6 h-6 mr-3 flex items-center justify-center text-2xl font-bold text-gray-500" style="font-family: 'Arial', 'sans-serif'">₿</span>
                    {{ item.name }}
                    </Link>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="md:pl-64 flex flex-col flex-1">
            <!-- Top Navigation -->
            <div class="sticky top-0 z-10 bg-white md:flex h-16 shadow-sm">
                <div class="flex items-center justify-between px-4 md:px-8" style="width: 100%;">
                    <div>
                        <!-- Mobile menu button -->
                        <button type="button"
                            class="md:hidden -ml-0.5 -mt-0.5 h-12 w-12 inline-flex items-center justify-center rounded-md text-gray-500 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                            @click="sidebarOpen = !sidebarOpen">
                            <span class="sr-only">Open sidebar</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <!-- Search Bar -->
                        <div class="flex-1 px-4 flex justify-center lg:justify-end">
                            <div class="w-full max-w-lg lg:max-w-xs">
                                <label for="search" class="sr-only">Search</label>
                                <div class="relative text-gray-400 focus-within:text-gray-600">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Right side buttons -->
                    <div class="ml-4 flex items-center md:ml-6 space-x-4">
                        <!-- Notifications -->
                        <button
                            class="p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span class="sr-only">View notifications</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </button>

                        <!-- Settings -->
                        <!-- <button
                            class="p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span class="sr-only">Settings</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button> -->


                        <!-- User Profile Section -->
                        <div class="flex items-center px-4 py-3 border-t border-gray-200">
                            <div class="flex-shrink-0">
                                <div
                                    class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-semibold">
                                    JD
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">John Doe</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-1">
                <div class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                        <slot />
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'

const sidebarOpen = ref(false)

const navigation = [
    { name: 'Dashboard', href: '/dashboard', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
    { name: 'Management', href: '/income-expenses', icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z' },
    { name: 'Indicators', href: '/indicators', icon: 'M3 12h3v8H3v-8zM9 8h3v12H9V8zM15 4h3v16h-3V4z' },
    // { name: 'Crypto', href: '/crypto', icon: 'text' },
    { name: 'Users', href: '/profile', icon: 'M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2 M9 7a4 4 0 1 1 0-8 4 4 0 0 1 0 8z M23 21v-2a4 4 0 0 0-3-3.87 M16 3.13a4 4 0 0 1 0 7.75' },
    // { name: 'Integrations', href: '/income-expenses', icon: 'M20 7h-5V3h-6v4H4v6h5v4h6v-4h5V7z' },
    // { name: 'Notifications', href: '/income-expenses', icon: 'M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9 M13.73 21a2 2 0 0 1-3.46 0' },
    // { name: 'Security', href: '/income-expenses', icon: 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z' },
    // { name: 'Settings', href: '/income-expenses', icon: 'M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6z M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 M13 21v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 M3 13h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 M16.82 4.91a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9c0 .69.41 1.3 1.01 1.58.23.1.49.15.75.15H21a2 2 0 0 1 0 4 M20.91 14c-.26 0-.52.05-.75.15-.6.28-1.01.89-1.01 1.58' },
]
</script>
