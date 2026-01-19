@extends('layouts.app')

@section('content')
    {{-- <x-common.page-breadcrumb pageTitle="From Elements" /> --}}
    <div class="space-y-6">
        <x-tables.basic-tables.lapangan-badminton :bookings="$bookings" :tanggal="$tanggal" />
    </div>
@endsection
