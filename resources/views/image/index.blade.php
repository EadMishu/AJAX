<!DOCTYPE html>
<html>
<head>
    <title>Image List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

   <div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Image Records</h2>
        <a href="{{ route('imageses.create') }}" class="btn btn-primary">+ Add New</a>
    </div>

    {{-- Success message --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Table --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Number</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($images as $index => $image)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $image->name }}</td>
                        <td>{{ $image->phone }}</td>
                        <td>{{ $image->number }}</td>
                        <td>
                            @if($image->image)
                                <img src="{{ asset('storage/uploads/' . $image->image) }}" alt="Image" style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <span class="text-muted">No image</span>
                            @endif
                        </td>
                        <td>
                            <a href="#" class="btn btn-sm btn-warning">Edit</a>

                            <form action="{{ route('imageses.destroy', $image->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure to delete?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

</body>
</html>