@extends('frontend.layout.app')

@section('page-title')
    Terms & Condition | {{ config('app.name') }}
@endsection

@section('page-css')

    <style>
        .content  {
            font-size: 18px !important;
            margin-top: 30px !important;
        }
       .content ul{
            padding: 20px 0;
        }
    </style>

@endsection

@section('body-content')

    <div class="container" style="padding: 50px 20px;">
        <div class="row">
            <h2>Terms & Condition</h2>

            <div class="content">
                @php
                    echo App\Models\PagesContent::first()->terms ?? '';
                @endphp
            </div>
        </div>
    </div>

@endsection
