@extends('layouts')

@section('content')
    @if(count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="?" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="photos" class="col-form-label">Title</label>
            <input type="file" id="photos" class="form-control{{ $error->has('title') ? 'is-invalid' : ''}}"
            name="photos[] multiple required">
        </div>

        <div class="form-group">
            <button class="btn btn-primary" type="submit">Upload</button>
        </div>
    </form>
@endsection
