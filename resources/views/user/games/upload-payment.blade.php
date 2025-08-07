@extends('layouts.user-dashboard')

@section('title', 'Upload Payment Receipt')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fa fa-upload"></i> Upload Payment Receipt</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('user.games.payment.store', $game) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="receipt">Payment Receipt (image)</label>
                            <input type="file" class="form-control @error('receipt') is-invalid @enderror" name="receipt" required>
                            @error('receipt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-check"></i> Submit Receipt
                        </button>
                        <a href="{{ route('user.games.show', $game) }}" class="btn btn-secondary">Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
