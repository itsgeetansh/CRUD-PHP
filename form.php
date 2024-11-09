<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">

    <style>
        .valid {
            color: green;
        }

        .invalid {
            color: red;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-lg-12 mt-5">
                <h2>Registration Form</h2>
                <form id="myForm" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo isset($editId) ? $editId : ''; ?>">

                    <div class="form-group">
                        <label for="username" class="mt-4">Username:</label>
                        <input type="text" class="form-control" name="username" id="username" value="<?php echo isset($editUsername) ? $editUsername : ''; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="gmail" class="mt-4">Gmail:</label>
                        <input type="email" class="form-control" name="gmail" id="gmail" value="<?php echo isset($editGmail) ? $editGmail : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="mobile_no" class="mt-4">Mobile No:</label>
                        <input type="text" class="form-control" name="mobile_no" id="mobile_no" value="<?php echo isset($editMobileNo) ? $editMobileNo : ''; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="gender" class="mt-4">Gender:</label><br>
                        <input type="radio" name="gender" value="male" id="gender_male" <?php echo (isset($editGender) && $editGender == 'male') ? 'checked' : ''; ?>> Male
                        <input type="radio" name="gender" value="female" id="gender_female" <?php echo (isset($editGender) && $editGender == 'female') ? 'checked' : ''; ?>> Female
                        <input type="radio" name="gender" value="other" id="gender_other" <?php echo (isset($editGender) && $editGender == 'other') ? 'checked' : ''; ?>> Other
                    </div>
                    <div class="form-group">
                        <label for="password" class="mt-4">Password:</label>
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>

                    <div>
                        <ul id="password-criteria">
                            <li id="capital" class="invalid">Password must contain at least one capital letter</li>
                            <li id="special" class="invalid">Password must contain at least one special character</li>
                            <li id="length" class="invalid">Password must contain at least 8 characters</li>
                            <li id="numeric" class="invalid">Password must contain at least one numeric value</li>
                        </ul>
                    </div>
                    <div class="form-group">
                        <label for="imageUpload" class="mt-4">Upload an image (max 2MB):</label>
                        <input type="file" id="imageUpload" name="image" accept="image/*">

                        <div id="currentImageContainer" class="mt-3" style="display: none;">
                            <img id="currentImage" src="" alt="image" style="max-width: 150px;">
                            <button type="button" id="removeImageBtn" class="btn btn-danger mt-2">Remove Image</button>
                        </div>
                    </div>

                    <button type="submit" id="submitBtn" class="btn btn-primary mt-3"><?php echo isset($editId) ? 'Update' : 'Submit'; ?></button>
                </form>

            </div>
        </div>

        <div class="col-lg-12">
            <div class="table-container col-lg-8 col-md-10 col-sm-12 mx-auto">
                <h3 class="text-center">Submitted Data</h3>
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Username</th>
                            <th>Gmail</th>
                            <th>Mobile No.</th>
                            <th>Password</th>
                            <th>Profile</th>
                            <th>Delete</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody id="dataTable">
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['gmail']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['mobile_no']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['password']) . "</td>";
                                echo "<td>
                <img src='" . (!empty($row['image']) ? $row['image'] : 'default/defaultImg.png') . "' alt='Profile Image' class='profile-img' style='width: 50px; height: 50px;'>
            </td>";
                                echo "<td>
                <button class='btn btn-danger delete-btn' data-id='" . $row['id'] . "'>Delete</button>
            </td>";
                                echo "<td>
                <button class='btn btn-primary edit-btn' data-id='" . $row['id'] . "' data-username='" . $row['username'] . "' data-gmail='" . $row['gmail'] . "' data-mobile='" . $row['mobile_no'] . "' data-password='" . $row['password'] . "'>Edit</button>
            </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center'>No records found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

<script>
    
    document.getElementById('password').addEventListener('input', function() {
        let password = this.value;

        let capital = /[A-Z]/.test(password);
        document.getElementById('capital').className = capital ? 'valid' : 'invalid';

        let special = /[!@#$%^&*(),.?":{}|<>]/.test(password);
        document.getElementById('special').className = special ? 'valid' : 'invalid';

        let length = password.length >= 8;
        document.getElementById('length').className = length ? 'valid' : 'invalid';

        let numeric = /\d/.test(password);
        document.getElementById('numeric').className = numeric ? 'valid' : 'invalid';
    });

    $(document).ready(function() {
        $('#myForm').on('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(this);

            // Check if an image was uploaded
            // const imageInput = document.getElementById('imageUpload');
            // if (!imageInput.files.length) {
            //     formData.append('defaultImage', true);
            // }
    document.getElementById('myForm').addEventListener('submit', function(event) {
        let isValid = true;

        // Check if all criteria are satisfied
        if (document.getElementById('capital').className !== 'valid') {
            isValid = false;
        }
        if (document.getElementById('special').className !== 'valid') {
            isValid = false;
        }
        if (document.getElementById('length').className !== 'valid') {
            isValid = false;
        }
        if (document.getElementById('numeric').className !== 'valid') {
            isValid = false;
        }

        // If any criteria are not satisfied, prevent form submission
        if (!isValid) {
            event.preventDefault(); // Prevent form submission
            alert("Please ensure that your password meets all the criteria.");
        }
        $.ajax({
                url: 'function.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    alert('Form submitted successfully!');
                    fetchData();
                },
                error: function() {
                    alert('Error submitting form.');
                }
            });
    })
        });
        // Function to fetch data from the server
        function fetchData() {
            $.ajax({
                url: 'function.php?fetch=true',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    const dataTable = $('#dataTable');
                    console.log(data);

                    dataTable.empty();

                    if (data.length > 0) {
                        data.forEach(row => {
                            const tableRow = `
                                <tr>
                                    <td>${row.username}</td>
                                    <td>${row.gmail}</td>
                                    <td>${row.mobile_no}</td>
                                    <td>${row.password}</td>
                                    <td>
                                        <img src="${row.image}" alt="Profile Image" class="profile-img" style="width: 50px; height: 50px;">
                                    </td>
                                    <td>
                                        <button class="btn btn-danger delete-btn" data-id="${row.id}">Delete</button>
                                    </td>
                                    <td>
                                        <button class="btn btn-primary edit-btn" data-id="${row.id}" data-username="${row.username}" data-gmail="${row.gmail}" data-mobile="${row.mobile_no}" data-password="${row.password}">Edit</button>
                                    </td>
                                </tr>
                            `;
                            dataTable.append(tableRow);
                        });
                    } else {
                        dataTable.append('<tr><td colspan="7" class="text-center">No records found</td></tr>');
                    }
                },
                error: function() {
                    alert('Error fetching data.');
                }
            });
        }

        fetchData();

        // Delete functionality
        $(document).on('click', '.delete-btn', function() {
            const userId = $(this).data('id');
            console.log(userId, "<<<<<<");

            if (confirm('Are you sure you want to delete this record?')) {
                $.ajax({
                    url: 'function.php?del=' + userId,
                    type: 'DELETE',
                    success: function(response) {
                        alert('Record deleted successfully!');
                        fetchData();
                    },

                });
            }
        });

        // Edit functionality
        $(document).on('click', '.edit-btn', function() {
            const userId = $(this).data('id');
            console.log(userId, "userId");


            $.ajax({
                url: 'function.php?editId=' + userId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.status !== 'error') {
                        // Pre-fill the form with the fetched data
                        $('input[name="id"]').val(userId);
                        $('#username').val(data.username);
                        $('#gmail').val(data.gmail);
                        $('#mobile_no').val(data.mobile_no);
                        $('#password').val(data.password);
                        $('input[name="gender"][value="' + data.gender + '"]').prop('checked', true);

                        // Handle image display
                        if (data.image) {
                            $('#currentImage').attr('src', data.image);
                            $('#currentImageContainer').show();
                        } else {
                            $('#currentImageContainer').hide();
                        }
                    } else {
                        alert(data.message);
                    }
                },
                error: function() {
                    alert('Error fetching user data.');
                }
            });
        });
        $('#removeImageBtn').on('click', function() {
            const userId = $('input[name="id"]').val();
            const defaultImage = 'default/defaultImg.png';

            console.log(defaultImage, "defaultImage");

            // Send an AJAX request to update the image in the database
            $.ajax({
                url: 'function.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'removeImage',
                    userId: userId,
                    defaultImage: defaultImage
                },
                success: function(response) {
                    if (response.status === 'success') {
                        // Update the UI to show the default image
                        $('#currentImage').attr('src', defaultImage);
                        $('#currentImageContainer').hide();
                        alert('Image removed successfully.');
                    } else {
                        alert('Error removing image: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error removing image.');
                }
            });
        });


    });
</script>

</html>