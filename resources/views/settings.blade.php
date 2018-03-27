<?php
/**
 * Created by PhpStorm.
 * User: wit543
 * Date: 28-Mar-18
 * Time: 02:01 AM
 */
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <passport-authorized-clients></passport-authorized-clients>
                <passport-clients></passport-clients>
                <passport-personal-access-tokens></passport-personal-access-tokens>
            </div>
        </div>
    </div>
@endsection