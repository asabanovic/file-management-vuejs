@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="col-md-12">
                        <file-management :settings="{{ json_encode($props) }}"></file-management>
                    </div>
                    <div class="col-md-12">
                        <attachment-list :settings="{{ json_encode($props) }}"></attachment-list>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
