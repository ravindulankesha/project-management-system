<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Project</title>
</head>
<body>

    <h1>Create Project</h1>

    <form action="{{ route('projects.store') }}" method="POST">
        @csrf

        <div>
            <label for="name">Project Name:</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required>
            @error('name')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="description">Project Description:</label>
            <textarea name="description" id="description" required>{{ old('description') }}</textarea>
            @error('description')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <h2>Link Customers to Project</h2>

        <div>
            @foreach ($customers as $customer)
                <div>
                    <label for="customer_{{ $customer->id }}">
                        <input type="checkbox" name="customers[]" value="{{ $customer->id }}" id="customer_{{ $customer->id }}">
                        {{ $customer->name }} ({{ $customer->email }})
                    </label>
                </div>
            @endforeach
            @error('customers')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <button type="submit">Create Project</button>
        </div>
    </form>

</body>
</html>
