@extends('layouts.app')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="container-custom">
        <div class="max-w-4xl mx-auto bg-white p-8 md:p-12 shadow-sm border border-gray-100 rounded-2xl">
            <div class="prose prose-sm md:prose-base max-w-none prose-p:text-center prose-p:my-2 prose-strong:text-gray-900">
                {!! $page->content !!}
            </div>
        </div>
    </div>
</div>
@endsection
