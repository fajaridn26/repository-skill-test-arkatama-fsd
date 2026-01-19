@extends('layouts.app')

@section('content')
    {{-- <x-common.page-breadcrumb pageTitle="From Elements" /> --}}
    <div class="space-y-6">
        <x-tables.basic-tables.hewan :hewans="$hewans" />
    </div>
@endsection
