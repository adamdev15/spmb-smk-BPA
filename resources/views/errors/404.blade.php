@extends('errors.layout')
@section('title', 'Halaman Tidak Ditemukan')
@section('code', '404')
@section('message', 'Oops! Halaman Tidak Ditemukan')
@section('description', 'Maaf, halaman yang Anda cari tidak ada atau mungkin telah dipindahkan ke URL lain.')
@section('image')
    <img src="{{ asset('images/error_404.jpg') }}" alt="404 Illustration" class="w-64 md:w-80 h-auto rounded-2xl mix-blend-multiply">
@endsection
