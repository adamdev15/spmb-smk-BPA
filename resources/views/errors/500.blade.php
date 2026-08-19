@extends('errors.layout')
@section('title', 'Kesalahan Server')
@section('code', '500')
@section('message', 'Oops! Terjadi Kesalahan Server')
@section('description', 'Maaf, server kami sedang mengalami sedikit gangguan. Silakan coba beberapa saat lagi.')
@section('image')
    <img src="{{ asset('images/error_500.jpg') }}" alt="500 Illustration" class="w-64 md:w-80 h-auto rounded-2xl mix-blend-multiply">
@endsection
