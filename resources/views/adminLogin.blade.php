@extends('layouts.app')
@section('content')
    <div>
        <div class="flex justify-center items-center mt-32">
            <div class="card p-6">
                <div class="flex gap-4 justify-center items-center mb-10">
                    <div class="flex-initial"><img src="/images/logo.png" class="w-14"></div>
                    <div class="flex-initial text-center">
                        <div class="header mb-0">Record Management System</div>
                        <div class="header mb-0 text-base">Sab Bernabe, Maddela, Quirino</div>
                    </div>
                    <div class="flex-initial"><img src="/images/logo1.png" class="w-14"></div>
                </div>

                <form action="/loginAuth" method="POST">
                    <div class="flex flex-col gap-6">
                        <div class="flex-1"><input class="w-full input" value="{{ old('username') }}" type="text" name="username" placeholder="Username" autocomplete="off" required></div>
                        <div class="flex-1"><input class="w-full input" type="password" name="password" placeholder="Password" autocomplete="off" required></div>
                        <div class="flex-1">
                            <label>Login As</label>
                            <select class="w-full input" name="login_as">
                                @php
                                    $loginAs = collect([
                                        ['value'=>'admin', 'text'=>'Administrator', 'selected'=>''],
                                        ['value'=>'official', 'text'=>'Barangay Official', 'selected'=>''],
                                    ])->map(function($o){
                                        if (old('login_as') == $o['value']) $o['selected'] = 'selected="selected"';
                                        return $o;
                                    });
                                @endphp
                                @foreach ($loginAs as $l)
                                    <option value="{{ $l['value'] }}" {{ $l['selected'] }}>{{ $l['text'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        @csrf
                        <div class="flex-1">
                            <button class="button w-full bg-blue-500 text-white">Log In</button>
                        </div>
                    </div>
                </form>
                
                @php
                    $errors = (is_array($errors)) ? $errors : $errors->all();
                @endphp
                @if (!empty($errors))
                    <div class="mt-4 font-semibold text-red-700">
                        <ul class="pl-4 list-disc">
                            @foreach ($errors as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <script>0</script>
@endsection