@extends('layouts.app')

@section('title','Dashboard')
@section('dashboard','active')
@section('content')
    <div class="row justify-content-center">
        <div class="col-3">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                <i class="fas fa-store"></i>
                </div>
                <div class="card-wrap">
                <div class="card-header">
                    <h4>Total Stores</h4>
                </div>
                <div class="card-body">
                    10
                </div>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                <i class="fas fa-boxes"></i>
                </div>
                <div class="card-wrap">
                <div class="card-header">
                    <h4>Total Products</h4>
                </div>
                <div class="card-body">
                    10
                </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                <i class="fas fa-list-alt"></i>
                </div>
                <div class="card-wrap">
                <div class="card-header">
                    <h4>Total Order</h4>
                </div>
                <div class="card-body">
                    10
                </div>
                </div>
            </div>
        </div>
    </div>    
@endsection