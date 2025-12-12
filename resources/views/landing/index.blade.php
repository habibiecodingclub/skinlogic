@extends('layouts.landing')

@section('content')
    @include('landing.sections.header')
    @include('landing.sections.hero')
    @include('landing.sections.why-skinlogic')
    @include('landing.sections.apa-yang-kami-tawarkan')
    @include('landing.sections.produk')
    @include('landing.sections.promo-banner')
    @include('landing.sections.testimonials')
    @include('landing.sections.visit-clinic')
    @include('landing.sections.articles')
    @include('landing.sections.footer')
@endsection
