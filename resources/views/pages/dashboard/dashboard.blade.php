@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-4">
        <div class="col-span-12 space-y-6 xl:col-span-7">
            <x-ecommerce.dashboard />
        </div>
    </div>
@endsection
