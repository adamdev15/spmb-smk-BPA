@extends('errors.layout')
@section('title', 'Akses Ditolak')
@section('code', '403')
@section('message', 'Akses Ditolak')
@section('description', 'Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.')
@section('image')
    <img src="{{ asset('images/error_404.jpg') }}" alt="403 Illustration" class="w-64 md:w-80 h-auto rounded-2xl mix-blend-multiply" style="filter: hue-rotate(150deg);">
@endsection
