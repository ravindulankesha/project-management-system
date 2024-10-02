<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Customer</title>
</head>
<body>
    <a href="{{route('customers.index')}}">Back</a>
    <h1>Create Customer</h1>

    <form action="{{ route('customers.store') }}" method="POST">
        @csrf

        <div>
            <label for="name">Customer Name:</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required>
            @error('name')
                <div>{{ $message }}</div>
            @enderror
        </div>
        
        <div>
            <label for="country">Country:</label>
            <input type="text" name="country" id="country" value="{{ old('country') }}" required>
            @error('country')
                <div>{{ $message }}</div>
            @enderror
        </div>
        
        <div>
            <label for="company">Customer Company:</label>
            <input type="text" name="company" id="company" value="{{ old('company') }}" required>
            @error('company')
                <div>{{ $message }}</div>
            @enderror
        </div>
        
        <div>
            <label for="phone">Customer Phone:</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required>
            @error('phone')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="email">Customer Email:</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
            @error('email')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <h2>Addresses</h2>

        <div id="addresses">
            <div class="address" id="address-0">
                <label for="number">Number:</label>
                <input type="text" name="addresses[0][number]" required>
                @error('addresses.0.number')
                    <div>{{ $message }}</div>
                @enderror

                <label for="street">Street:</label>
                <input type="text" name="addresses[0][street]" required>
                @error('addresses.0.street')
                    <div>{{ $message }}</div>
                @enderror

                <label for="city_state">City, State:</label>
                <input type="text" name="addresses[0][city_state]" required>
                @error('addresses.0.city_state')
                    <div>{{ $message }}</div>
                @enderror
            </div>
        </div>

        <button type="button" id="add-address">Add Another Address</button>

        <div>
            <button type="submit">Create Customer</button>
        </div>
    </form>

    <script>
        let addressCount = 1;

        document.getElementById('add-address').addEventListener('click', function() {
            const addressesDiv = document.getElementById('addresses');
            const newAddressDiv = document.createElement('div');
            newAddressDiv.classList.add('address');
            newAddressDiv.id = `address-${addressCount}`;

            newAddressDiv.innerHTML = `  
                <label for="number">Number:</label>
                <input type="text" name="addresses[${addressCount}][number]" required>
                <label for="street">Street:</label>
                <input type="text" name="addresses[${addressCount}][street]" required>
                <label for="city_state">City, State:</label>
                <input type="text" name="addresses[${addressCount}][city_state]" required>
                <button type="button" class="remove-address" data-id="${addressCount}">Remove Address</button>
            `;
            
            addressesDiv.appendChild(newAddressDiv);
            addressCount++;
        });

        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-address')) {
                const addressId = event.target.getAttribute('data-id');
                const addressDiv = document.getElementById(`address-${addressId}`);
                addressDiv.remove();
            }
        });
    </script>

</body>
</html>
