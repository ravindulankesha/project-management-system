<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <title>Customers</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
</head>
<body>

    <h1>Customer List</h1>

    <a href="{{ route('customers.create') }}">Add New Customer</a>

    <ul id="customer-list">
        @forelse ($customers as $customer)
            <li id="customer-{{ $customer->id }}">
                <strong>{{ $customer->name }}</strong> | <strong>{{ $customer->phone }}</strong> | <strong>{{ $customer->email }}</strong> | <strong>{{ $customer->country }}</strong>
                <button class="edit-btn" data-id="{{ $customer->id }}">Edit</button>
                <button class="delete-btn" data-id="{{ $customer->id }}">Delete</button>
                
                <ul>
                    @forelse ($customer->addresses as $address)
                        <li>{{ $address->number }}, {{ $address->street }}, {{ $address->city_state }}</li>
                    @empty
                        <li>No addresses available.</li>
                    @endforelse
                </ul>
            </li>
        @empty
            <li>No customers available.</li>
        @endforelse
    </ul>

    <!-- Edit Modal -->
    <div id="edit-modal" style="display: none;">
        <h2>Edit Customer</h2>
        <form id="edit-form">
            @csrf
            <input type="hidden" id="edit-customer-id">
            <div>
                <label for="edit-name">Name:</label>
                <input type="text" id="edit-name" name="name" required>
            </div>
            <div>
                <label for="edit-phone">Phone:</label>
                <input type="text" id="edit-phone" name="phone" required>
            </div>
            <div>
                <label for="edit-email">Email:</label>
                <input type="email" id="edit-email" name="email" required>
            </div>
            <div>
                <label for="edit-country">Country:</label>
                <input type="text" id="edit-country" name="country" required>
            </div>
            <button type="submit">Update Customer</button>
        </form>
    </div>

    <script>
        // CSRF token setup for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Delete customer via AJAX
        $('.delete-btn').click(function() {
            const customerId = $(this).data('id');
            const url = `/customers/${customerId}`;

            if (confirm('Are you sure you want to delete this customer?')) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    success: function(response) {
                        $('#customer-' + customerId).remove(); // Remove customer from list
                        alert(response.message);
                    },
                    error: function(error) {
                        alert('Error deleting the customer');
                    }
                });
            }
        });

        // Open edit modal and populate with customer data
        $('.edit-btn').click(function() {
            const customerId = $(this).data('id');
            const customerElement = $('#customer-' + customerId);
            const customerName = customerElement.find('strong').eq(0).text();
            const customerPhone = customerElement.find('strong').eq(1).text();
            const customerEmail = customerElement.find('strong').eq(2).text();
            const customerCountry = customerElement.find('strong').eq(3).text();

            $('#edit-customer-id').val(customerId);
            $('#edit-name').val(customerName);
            $('#edit-phone').val(customerPhone);
            $('#edit-email').val(customerEmail);
            $('#edit-country').val(customerCountry);

            $('#edit-modal').show();
        });

        // Submit the edit form via AJAX
        $('#edit-form').submit(function(e) {
            e.preventDefault();

            const customerId = $('#edit-customer-id').val();
            const url = `/customers/${customerId}`;
            const data = {
                name: $('#edit-name').val(),
                phone: $('#edit-phone').val(),
                email: $('#edit-email').val(),
                country: $('#edit-country').val()
            };

            $.ajax({
                url: url,
                type: 'PUT',
                data: data,
                success: function(response) {
                    // Update customer details in the list
                    const updatedCustomer = `
                        <strong>${response.customer.name}</strong> | <strong>${response.customer.phone}</strong> | <strong>${response.customer.email}</strong> | <strong>${response.customer.country}</strong>
                        <button class="edit-btn" data-id="${response.customer.id}">Edit</button>
                        <button class="delete-btn" data-id="${response.customer.id}">Delete</button>
                    `;
                    $('#customer-' + customerId).html(updatedCustomer);

                    $('#edit-modal').hide();
                    alert('Customer updated successfully!');
                },
                error: function(error) {
                    alert('Error updating the customer');
                }
            });
        });
    </script>

</body>
</html>
