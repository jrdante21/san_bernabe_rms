@extends('layouts.app')
@section('content')
    <div id="app">
        <div class="card bg-gradient-to-tl from-green-400 to-green-600 sticky top-0 z-50">
            <div class="container mx-auto">
                <div class="flex items-center">
                    <div class="flex-1">
                        <div class="flex gap-4 items-center">
                            <div class="flex-initial"><img src="/images/logo.png" class="w-14"></div>
                            <div class="flex-initial text-center text-white">
                                <div class="header mb-0">Record Management System</div>
                                <div class="header mb-0 text-base">Sab Bernabe, Maddela, Quirino</div>
                            </div>
                            <div class="flex-initial"><img src="/images/logo1.png" class="w-14"></div>
                        </div>
                    </div>
                    <div class="flex-initial">
                        <admins-menu :admin="{{ $admin }}"></admins-menu>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mx-auto relative">
            <div class="fixed h-full top-auto overflow-auto w-72 pt-5 pb-20 pr-10">
                <div class="mb-4">
                    <print-report :transaction-types="{{ json_encode($transactions) }}"></print-report>
                </div>

                <div class="menu-col font-semibold text-lg">
                    <router-link class="item rounded" to="/admin/home">Home</router-link>
                    <router-link class="item rounded" to="/admin/residents">Residents</router-link>
                </div>

                <div class="pl-2 mt-1">
                    <div class="header text-lg mb-0">Transactions</div>
                    <div class="menu-col">
                        @foreach ($transactions as $t)
                            <router-link class="item rounded" to="/admin/transactions/{{ $t['name'] }}">{{ $t['title'] }}</router-link>
                        @endforeach
                    </div>
                </div>

                <div class="menu-col font-semibold text-lg">
                    @if ($admin->level <= 2)
                        <router-link class="item rounded" to="/admin/officials">Barangay Officials</router-link>
                    @endif

                    @if ($admin->level <= 1)
                        <router-link class="item rounded" to="/admin/administrators">Administrators</router-link>
                    @endif
                </div>
            </div>
            <div class="ml-72 pt-5">
                <router-view :admin-level="{{ $admin->level }}" :api-server-name="'{{ url('/api') }}/'" :transaction-types="{{ json_encode($transactions) }}"></router-view>
            </div>
        </div>
    </div>
    <alert-component v-model="alertSuccess" :message="alertMsg" status="success"></alert-component>
    <script src="{{ mix('/js/app.js') }}" ></script>
@endsection