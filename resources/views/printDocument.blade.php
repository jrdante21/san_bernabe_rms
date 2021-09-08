@extends('layouts.print')
@section('content')
    @switch($type)
        @case(2) {{-- Barangay Certificate --}}
            <div class="font-bold mb-4">TO WHOM IT MAY CONCERN:</div>
            <p class="mb-4">
                <b>THIS IS TO CERTIFY that I officially know <u>{{ $resident['name'] }}</u></b>, 
                of legal age, {{ $resident['civil'] }} and a resident of Barangay San Bernabe, Maddela, Quirino. 
                This is to certify further that he/she is of good moral character and a law abiding citizen in this community.
            </p>
            <p class="mb-4">This certificate is issued upon the request of the above-mentioned name for whatever legal purpose it may deem serve.</p>
            @break

        @case(3) {{-- Barangay Clearance --}}
            <div class="font-bold mb-4">TO WHOM IT MAY CONCERN:</div>
            <p class="mb-4">
                <b>THIS IS TO CERTIFY that I officially know <u>{{ $resident['name'] }}</u></b>, 
                of legal age, {{ $resident['civil'] }} and a resident of Barangay San Bernabe, Maddela, Quirino. 
                This is to certify further that he/she is of good moral character and a law abiding citizen in this community.
            </p>
			<p class="mb-4">Records of this office show that as to this date he/she has not been accuse of any crime or any pending obligation.</p>
			<p class="mb-4">This clearance is issued upon the request of the above-mentioned name for whatever legal purpose it may deem serve.</p>
            @break
        
        @case(4) {{-- Barangay Idigency --}}
            <div class="font-bold mb-4">TO WHOM IT MAY CONCERN:</div>
            <p class="mb-4">
                <b>THIS IS TO CERTIFY that I officially know <u>{{ $resident['name'] }}</u></b>, 
                of legal age, {{ $resident['civil'] }} and a resident of Barangay San Bernabe, Maddela, Quirino. 
                This is to certify further that he/she is of good moral character and a law abiding citizen in this community.
            </p>
			<p class="mb-4">Further certify that his/her family belongs to the indigents in this community.</p>
			<p class="mb-4">This clearance is issued upon the request of the above-mentioned name for whatever legal purpose it may deem serve.</p>
            @break

        @case(5) {{-- Business Permit --}}
            <div class="font-bold mb-4">TO WHOM IT MAY CONCERN:</div>
            <p class="mb-4">
                <b>THIS IS TO CERTIFY that I officially know <u>{{ $resident['name'] }}</u></b>, 
                of legal age, {{ $resident['civil'] }} and owner of <b><u>{{ $business['name'] }}</u></b> at Barangay San Bernabe, Maddela, Quirino 
                is given permit to operate his/her establishment/business subject however to the provision of our existing laws and ordinance, 
                and rules & regulation governing the operation and maintenance of the same.
            </p>
            <p class="mb-4">This permit may be revoked at any time when necessary to protect the public interest or violation of any provision.</p>
            @break

        @case(6) {{-- Business Clearance --}}
            <div class="font-bold mb-4">TO WHOM IT MAY CONCERN:</div>
            <p class="mb-4">
                <b>THIS IS TO CERTIFY that I officially know <u>{{ $resident['name'] }}</u></b>, 
                resident of Barangay San Bernabe, Maddela, Quirino. Has no pending obligation in our Purok.
            </p>

            <div class="flex justify-center gap-10 mb-4">
                <div class="flex-initial">Owner of:</div>
                <div class="flex-initial text-center">
                    <div class="font-bold underline">{{ $business['name'] }}</div>
                    <div class="italic">Name type of Business</div>
                </div>
            </div>

            <div class="text-center mb-4">
                <div class="font-bold underline">{{ $business['address'] }}</div>
                <div class="italic">Place of Business</div>
            </div>

            <p class="mb-4">Provided however, that all rules, regulations, and ordinances of this Barangay are being observed and complied with accordingly</p>
            @break

        @case(7) {{-- Purok Clearance --}}
            <div class="font-bold mb-4">TO WHOM IT MAY CONCERN:</div>
            <p class="mb-4">
                <b>THIS IS TO CERTIFY that I officially know <u>{{ $resident['name'] }}</u></b>, 
                resident of Barangay San Bernabe, Maddela, Quirino. Has no pending obligation in our Purok.
            </p>
            <p class="mb-4">He/she can now secure Barangay Clearance.</p>
            @break

        @case(8) {{-- Certification --}}
            <div class="font-bold text-center mb-4">
                <div class="uppercase">OFFICE OF THE LUPONG TAGAPAMAYAPA</div>
                <div class="uppercase">Certification</div>
            </div>
            <div class="font-bold mb-4">TO WHOM IT MAY CONCERN:</div>
            <p class="mb-4">
                <b>THIS IS TO CERTIFY that I officially know <u>{{ $resident['name'] }}</u></b>, 
                of legal age, and a resident of Barangay San Bernabe, Maddela, Quirino. This is to certify further the he is a legal owner of a
                <u>{{ $assets['title'] }}</u> with the following description: .
            </p>

            <div class="mb-4">
                @switch($assets['assets']['type'])
                    @case('vehicle')
                        <p>MODEL: {{$assets['assets']['v_model']}}</p>
                        <p>PLATE #: {{$assets['assets']['v_plate']}}</p>
                        <p>COLOR: {{$assets['assets']['v_color']}}</p>
                        @break
    
                    @case('animal')
                        <p>SEX: {{$assets['assets']['a_sex']}}</p>
                        <p>AGE: {{$assets['assets']['a_age']}}</p>
                        <p>COLOR: {{$assets['assets']['a_color']}}</p>
                        <p>CERTIFICATE OF OWNERSHIP: {{$assets['assets']['a_cert']}}</p>
                        @break
                        
                    @case('land')
                        <p>HECTAR: {{$assets['assets']['l_hectar']}}</p>
                        <p>LOCATION: {{$assets['assets']['l_loc']}}</p>
                        @break
                    
                @endswitch
            </div>

            <p class="mb-4">{{ ucfirst($assets['purpose']) }}.</p>
            <p class="mb-4">This certification is issued upon the request of the above mentioned name for whatever legal purpose it rnay deemed serve.</p>
            @break

        @case(9) {{-- Summon --}}
            <div class="font-bold text-center mb-4 uppercase">OFFICE OF THE PUNONG BARANGAY</div>
            <div class="flex justify-between gap-10 mb-4">
                <div class="flex-initial text-center">
                    <div class="font-bold underline">{{ $resident['name'] }}</div>
                    <div class="uppercase">Complainant</div>
                    <div class="mx-2 font-bold">Against</div>
                    <div class="font-bold underline">{{ $summons['respondent'] }}</div>
                    <div class="uppercase">Respondent</div>
                </div>
                <div class="flex-initial">
                    <div>Barangay Case No.: <b><u>{{ $doc_num }}</u></b></div>
                    <div>For: <b><u>{{ $summons['reason'] }}</u></b></div>
                </div>
            </div>
            <div class="font-bold text-center mb-4 uppercase">SUMMONS</div>
            <div class="font-bold mb-4 uppercase">TO {{ $summons['respondent'] }}:</div>
            <p class="mb-4">
                You are hereby summoned to appear before me in person, together with your witness, 
                on the <b><u>{{ date('dS', strtotime($summons['schedule'])) }}</u></b> day of <b><u>{{ date('F Y', strtotime($summons['schedule'])) }}</u></b>, 
                at <u><b>{{ date('h:m A', strtotime($summons['schedule'])) }}</b></u> then and there to answer to a complaint made before me, 
                copy of which is attached hereto, for mediation / conciliation of your dispute with complainant. 
            </p>
            <p class="mb-4">You are hereby warned that if you refuse or willfully fail to appear in obedience to this simmons, you maybe bared from filling any counter arising to said complaints. Fail not or else face punishment as for contempt of court. </p>
            <p class="mb-4">This <u>{{ date('dS', strtotime($issued_at)) }}</u> day of <u>{{ date('F Y', strtotime($issued_at)) }}</u>.</p>

            <div class="border-t-2 border-dashed border-black mb-4"></div>

            <div class="flex gap-4 mb-4">
                <div class="flex-initial">__________1.</div>
                <div class="flex-1">Handling to him said summon in person.</div>
            </div>
            <div class="flex gap-4 mb-4">
                <div class="flex-initial">__________2.</div>
                <div class="flex-1">Handling to him said summon but he refuse to receive.</div>
            </div>
            <div class="flex gap-4 mb-4">
                <div class="flex-initial">__________3.</div>
                <div class="flex-1">Leaving said summon to his dwelling with ______________________________<br>A Person of suitable age and discretion residing there in.</div>
            </div>

            <div class="flex justify-between mb-4">
                <div class="flex-initial">______________________________</div>
                <div class="flex-initial">______________________________</div>
            </div>
            <div class="flex justify-between mb-4">
                <div class="flex-initial">______________________________</div>
                <div class="flex-initial">______________________________</div>
            </div>
            @break
        
        @default
            <div class="header">Document type unknown.</div>
            @break        

    @endswitch

    @if ($type <= 8)
        <p class="mb-4">
            Issued this <u>{{ date('dS', strtotime($issued_at)) }}</u> day of <u>{{ date('F Y', strtotime($issued_at)) }}</u>, at Barangay San Bernabe, Maddela, Quirino. 
        </p>
    @endif

    @if (!empty($assignatory))
        <div class="flex justify-end mt-10">
            <div class="flex-initial text-center">
                @foreach ($assignatory as $a)
                    <div class="mb-4">
                        <div class="underline font-bold">{{ $a['admin_officials']['name'] }}</div>
                        <div class="italic">{{ $a['admin_officials']['position'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if ($type <= 8)
        <div class="font-bold mt-4">
            <div>OR No.: <u>{{ $or_num }}</u></div>
            <div>CTC No.: <u>{{ $doc_num }}</u></div>
            <div>Date Issued: <u>{{ date('F d, Y', strtotime($issued_at)) }}</u></div>
        </div>
    @endif

@endsection
