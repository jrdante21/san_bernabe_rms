@extends('layouts.print')
@section('content')
    <div class="font-bold mb-4 text-lg text-center">Resident Lists</div>

    @foreach ($residents as $rk => $res)
        @if (!empty($rk))
            <div class="font-bold mt-4 mb-2">Purok {{ $rk }}</div>
        @endif

        <table class="celled w-full table-auto text-left border-collapse mb-4">
            <thead class="border-b border-gray-300 border-solid bg-gray-200">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Address</th>
                    @if (!empty($with_assets))
                        <th class="w-1/3">Assets</th>
                    @endif
                    <th>Date Registered</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $num = 0;
                @endphp
                @foreach ($res as $r)
                    <tr>
                        <td>{{ $num+=1 }}</td>
                        <td>{{ $r['name'] }}</td>
                        <td>{{ $r['address'] }}</td>
                        @if (!empty($with_assets))
                            <td>
                                @if (count($r['assets']) >= 1)
                                    <ul class="list-disc pl-4">
                                        @foreach ($r['assets'] as $a)
                                            <li>
                                                <b>{{ ucwords($a['type']) }}</b>
                                                <div class="text-sm">
                                                    @foreach ($a['description'] as $d)
                                                        <div>{{ $d['name'] }}: <span class="font-semibold">{{ ucwords($d['value']) }}</span></div>
                                                    @endforeach
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="italic">No assets found.</span>
                                @endif
                            </td>
                        @endif
                        <td>{{ date('M d, Y', strtotime($r['created_at'])) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
@endsection