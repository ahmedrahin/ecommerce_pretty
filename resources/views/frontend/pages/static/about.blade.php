@extends('frontend.layout.app')

@section('page-title')
    About Us
@endsection

@section('page-css')
    <style>
        .content  {
            font-size: 18px !important;
            margin-top: 30px !important;
            line-height: 28px;
        }
       .content ul{
            padding: 20px 0;
        }
    </style>

@endsection

@section('body-content')

    <div class="container" style="padding: 50px 20px;">
        <div class="row">
            <h2>About Us</h2>

            <div class="content">
                Inky is a brand shaped by tattoo culture, bold lines, and real self-expression. We design clothing for people who treat style as an extension of identity. Every piece is created with intention, rooted in the belief that art belongs everywhere, including what you wear.
Our approach is simple. Clean structure, strong visual details, and designs that feel personal without trying too hard. Inky speaks to people who express themselves through ink, creativity, and individuality. We aim to build a brand where clothing feels connected to the person wearing it.
Each drop starts with inspiration from tattoo artistry, street culture, and the freedom of turning ideas into something tangible. Inky supports artists, creators, and anyone who values authenticity in their everyday style.
Wear your art. Live your story.
 That is the foundation of Inky.

            </div>
        </div>
    </div>

@endsection
