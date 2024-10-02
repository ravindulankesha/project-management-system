<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Projects</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
</head>
<body>

    <h1>Project List</h1>

    <a href="{{ route('projects.create') }}">Create New Project</a>

    <ul id="project-list">
        @forelse ($projects as $project)
            <li id="project-{{ $project->id }}">
                <strong>{{ $project->name }}</strong>: <italic>{{ $project->description }}</italic>

                <button class="edit-btn" data-id="{{ $project->id }}">Edit</button>
                <button class="delete-btn" data-id="{{ $project->id }}">Delete</button>
                <br>
                <u>Linked Customers:</u>
                <ul>
                    @forelse ($project->customers as $customer)
                        <li>{{ $customer->name }} ({{ $customer->email }})</li>
                    @empty
                        <li>No customers linked to this project.</li>
                    @endforelse
                </ul>
            </li>
        @empty
            <li>No projects available.</li>
        @endforelse
    </ul>

    <!-- Modal for editing project -->
    <div id="edit-modal" style="display: none;">
        <h2>Edit Project</h2>
        <form id="edit-form">
            @csrf
            <input type="hidden" id="edit-project-id">
            <div>
                <label for="edit-name">Project Name:</label>
                <input type="text" id="edit-name" name="name" required>
            </div>
            <div>
                <label for="edit-description">Project Description:</label>
                <textarea id="edit-description" name="description" required></textarea>
            </div>
            <button type="submit">Update Project</button>
        </form>
    </div>

    <script>
        // CSRF token setup for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Delete project via AJAX
        $('.delete-btn').click(function() {
            const projectId = $(this).data('id');
            const url = `/projects/${projectId}`;

            if(confirm('Are you sure you want to delete this project?')) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    success: function(response) {
                        // Remove the project from the list
                        $('#project-' + projectId).remove();
                        alert(response.message);
                    },
                    error: function(error) {
                        alert('Error deleting the project');
                    }
                });
            }
        });

        // Open the edit modal and populate it with project data
        $('.edit-btn').click(function() {
            const projectId = $(this).data('id');
            const projectName = $(this).parent().find('strong').text();
            const projectDescription = $(this).parent().find('italic').text();

            $('#edit-project-id').val(projectId);
            $('#edit-name').val(projectName);
            $('#edit-description').val(projectDescription);

            $('#edit-modal').show();
        });

        // Submit the edit form via AJAX
        $('#edit-form').submit(function(e) {
            e.preventDefault();

            const projectId = $('#edit-project-id').val();
            const url = `/projects/${projectId}`;
            const data = {
                name: $('#edit-name').val(),
                description: $('#edit-description').val()
            };

            $.ajax({
                url: url,
                type: 'PUT',
                data: data,
                success: function(response) {
                    // Update the project details in the list
                    $('#project-' + projectId).find('strong').text(response.project.name);
                    $('#project-' + projectId).find('italic').text(response.project.description);

                    $('#edit-modal').hide();
                    alert('Project updated successfully!');
                },
                error: function(error) {
                    alert('Error updating the project');
                }
            });
        });
    </script>

</body>
</html>

