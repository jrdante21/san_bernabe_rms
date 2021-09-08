@extends('layouts.print')
@section('content')
    <div class="font-bold mb-4 text-lg text-center">Resident Information</div>

    <div class="font-bold mb-2">General Information</div>
    <div class="flex items-center gap-4">
        <div class="flex-initial w-28">
            @if (!empty($image))
                <img src="/images/profile_pic/{{$image}}" class="w-full border border-solid border-gray-300" alt="Resident Image">
            @else
                <div class="flex w-full h-28 border border-solid border-gray-300 items-center">
                    <div class="flex-1 text-center">No photo</div>
                </div>
            @endif
        </div>
        <div class="flex-1">
            <div class="flex flex-col gap-2">
                <div class="flex-1">
                    <div class="flex gap-2 items-center">
                        <div class="flex-initial">Name:</div>
                        <div class="flex-1 font-semibold border-b border-solid border-gray-300">{{ $name }}</div>

                        <div class="flex-initial">Gender:</div>
                        <div class="flex-initial font-semibold border-b border-solid border-gray-300">{{ ucwords($gender) }}</div>

                        <div class="flex-initial">Civil Status:</div>
                        <div class="flex-initial font-semibold border-b border-solid border-gray-300">{{ ucwords($civil) }}</div>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex gap-2 items-center">
                        <div class="flex-initial">Birthdate:</div>
                        <div class="flex-1 font-semibold border-b border-solid border-gray-300">{{ date('M d, Y', strtotime($bday)) }}</div>

                        <div class="flex-initial">Age:</div>
                        <div class="flex-initial font-semibold border-b border-solid border-gray-300">{{ $age }}</div>

                        <div class="flex-initial">Years Resided:</div>
                        <div class="flex-initial font-semibold border-b border-solid border-gray-300">{{ $years_res }}</div>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex gap-2 items-center">
                        <div class="flex-initial">Address:</div>
                        <div class="flex-1 font-semibold border-b border-solid border-gray-300">{{ $address }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="font-bold mt-4 mb-2">Educational Background</div>
    @if (count($education) <= 0)
        <div class="border border-solid border-gray-300 p-2 italic">Not applicable</div>
    @else
        <table class="celled w-full table-auto text-left border-collapse mb-4">
            <thead>
                <tr class="border-b border-gray-300 border-solid bg-gray-200">
                    <th>Level</th>
                    <th>School Name</th>
                    <th>Address</th>
                    <th>Graduated</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($education as $e)
                    <tr>
                        <td>{{ $e['level_word'] }}</td>
                        <td>{{ $e['name'] }}</td>
                        <td>{{ $e['address'] }}</td>
                        <td>{{ date('M Y' ,strtotime($e['year'])) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="font-bold mt-4 mb-2">Family Background</div>
    <div class="flex flex-col gap-2">
        <div class="flex-1">
            <div class="flex gap-2 items-center">
                <div class="flex-initial">Father:</div>
                @if (!empty($father))
                    <div class="flex-1 font-semibold border-b border-solid border-gray-300">{{ $father }}</div>
                @else
                    <div class="flex-1 italic border-b border-solid border-gray-300">Not applicable.</div>
                @endif
            </div>
        </div>
        <div class="flex-1">
            <div class="flex gap-2 items-center">
                <div class="flex-initial">Mother:</div>
                @if (!empty($mother))
                    <div class="flex-1 font-semibold border-b border-solid border-gray-300">{{ $mother }}</div>
                @else
                    <div class="flex-1 italic border-b border-solid border-gray-300">Not applicable.</div>
                @endif
            </div>
        </div>
        <div class="flex-1">
            <div class="flex gap-2 items-center">
                <div class="flex-initial">Spouse:</div>
                @if (!empty($spouse))
                    <div class="flex-1 font-semibold border-b border-solid border-gray-300">{{ $spouse }}</div>
                @else
                    <div class="flex-1 italic border-b border-solid border-gray-300">Not applicable.</div>
                @endif
            </div>
        </div>
    </div>
    @if (count($children) >= 1)
        <div class="font-bold my-2">Children</div>
        <table class="celled w-full table-auto text-left border-collapse mb-4">
            <thead>
                <tr class="border-b border-gray-300 border-solid bg-gray-200">
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Civil Status</th>
                    <th>Birthdate</th>
                    <th>School Level / Name</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($children as $c)
                    <tr>
                        <td>{{ $c['name'] }}</td>
                        <td>{{ ucwords($c['gender']) }}</td>
                        <td>{{ ucwords($c['civil']) }}</td>
                        <td>{{ date('M d, Y', strtotime($c['bday'])) }}</td>
                        <td>
                            @if ($c['has_school'] >= 1)
                                {{ ucwords($c['school_level']) }} / {{ ucwords($c['school_name']) }}
                            @else
                                <span class="italic">Not Applicable</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if (count($business) >= 1)
        <div class="font-bold mt-4 mb-2">Business</div>
        <table class="celled w-full table-auto text-left border-collapse mb-4">
            <thead>
                <tr class="border-b border-gray-300 border-solid bg-gray-200">
                    <th>Name</th>
                    <th>Address</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($business as $b)
                    <tr>
                        <td>{{ $b['name'] }}</td>
                        <td>{{ $b['address'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if (count($work) >= 1)
        <div class="font-bold mt-4 mb-2">Job / Work History</div>
        <table class="celled w-full table-auto text-left border-collapse mb-4">
            <thead>
                <tr class="border-b border-gray-300 border-solid bg-gray-200">
                    <th>Title / Position</th>
                    <th>Company</th>
                    <th>Date Started</th>
                    <th>Date Ended</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($work as $w)
                    <tr>
                        <td>{{ $w['title'] }}</td>
                        <td>{{ $w['company'] }}</td>
                        <td>{{ date('M Y', strtotime($w['start'])) }}</td>
                        <td>
                            @if ($w['upto_present'] >= 1)
                                Present
                            @else
                                {{ date('M Y', strtotime($w['end'])) }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if (count($assets) >= 1)
        <div class="font-bold mt-4 mb-2">Assets</div>
        <table class="celled w-full table-auto text-left border-collapse mb-4">
            <thead>
                <tr class="border-b border-gray-300 border-solid bg-gray-200">
                    <th>Type</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($assets as $a)
                    <tr>
                        <td>{{ ucwords($a['type']) }}</td>
                        <td>
                            <div class="flex gap-4 items-center">
                                @foreach ($a['description'] as $d)
                                    <div class="flex-initial">
                                        <span class="mr-px">{{ $d['name'] }}:</span>
                                        <span class="font-bold">{{ $d['value'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

@endsection
