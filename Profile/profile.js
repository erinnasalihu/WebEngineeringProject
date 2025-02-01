document.addEventListener('DOMContentLoaded', function () {
    // Handle profile photo upload
    const photoUpload = document.getElementById('photoUpload');
    const profilePhoto = document.getElementById('profilePhoto');

    photoUpload.addEventListener('change', async function (e) {
        const file = e.target.files[0];
        if (file) {
            if (!file.type.startsWith('image/')) {
                alert('Please upload an image file');
                return;
            }

            const formData = new FormData();
            formData.append('photo', file);

            try {
                console.log('Uploading file:', file.name, 'Type:', file.type);
                const response = await fetch('Profile/update_photo.php', {
                    method: 'POST',
                    body: formData
                });

                // Log the raw response for debugging
                const responseText = await response.text();
                console.log('Raw response:', responseText);

                // Try to parse the response as JSON
                let data;
                try {
                    data = JSON.parse(responseText);
                } catch (e) {
                    console.error('Failed to parse JSON response:', e);
                    alert('Server returned invalid response');
                    return;
                }

                if (data.success) {
                    console.log('Photo updated successfully, new URL:', data.photo_url);
                    profilePhoto.src = data.photo_url + '?t=' + new Date().getTime();
                    alert('Profile photo updated successfully!');
                } else {
                    console.error('Upload failed:', data.message);
                    alert(data.message || 'Failed to update profile photo');
                }
            } catch (error) {
                console.error('Upload error:', error);
                alert('An error occurred while updating the profile photo');
            }
        }
    });

    // Handle profile update form
    const updateProfileForm = document.getElementById('updateProfileForm');

    updateProfileForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        const formData = {
            username: document.getElementById('username').value,
            email: document.getElementById('email').value,
            newPassword: document.getElementById('newPassword').value
        };

        try {
            const response = await fetch('Profile/update_profile.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();
            if (data.success) {
                alert('Profile updated successfully!');
                if (data.username) {
                    document.querySelector('.profile-info h1').textContent = data.username;
                }
                if (data.email) {
                    document.querySelector('.profile-info .email').textContent = data.email;
                }
                document.getElementById('newPassword').value = '';
            } else {
                alert(data.message || 'Failed to update profile');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while updating the profile');
        }
    });

    // Handle logout
    window.logout = async function () {
        try {
            const response = await fetch('Profile/logout.php');
            const data = await response.json();
            if (data.success) {
                window.location.href = '/LogIn/index.php';
            } else {
                alert('Failed to logout. Please try again.');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred during logout');
        }
    };

    // Global variable to store the currently edited recipe
    let currentEditId = null;

    // Make editRecipe function globally available
    window.editRecipe = async function (recipeId) {
        try {
            const row = document.querySelector(`tr[data-recipe-id="${recipeId}"]`);
            const recipe = {
                id: recipeId,
                title: row.querySelector('[data-field="title"]').textContent,
                category: row.querySelector('[data-field="category"]').textContent.toLowerCase(),
                cooking_time: parseInt(row.querySelector('[data-field="cooking_time"]').textContent),
                ingredients: row.querySelector('[data-field="ingredients"]').textContent
            };

            // Show edit modal
            const modal = document.getElementById('editRecipeModal');
            modal.style.display = 'block';

            // Fill form with current values
            document.getElementById('editTitle').value = recipe.title;
            document.getElementById('editIngredients').value = recipe.ingredients;
            document.getElementById('editCookingTime').value = recipe.cooking_time;
            document.getElementById('editCategory').value = recipe.category;

            currentEditId = recipeId;
        } catch (error) {
            console.error('Error preparing edit:', error);
            alert('Failed to prepare recipe for editing');
        }
    }

    // Make saveRecipeEdit function globally available
    window.saveRecipeEdit = async function () {
        try {
            const recipeData = {
                id: currentEditId,
                title: document.getElementById('editTitle').value,
                ingredients: document.getElementById('editIngredients').value,
                cooking_time: parseInt(document.getElementById('editCookingTime').value),
                category: document.getElementById('editCategory').value
            };

            const response = await fetch('Profile/update_recipe.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(recipeData)
            });

            const data = await response.json();

            if (data.success) {
                // Close modal
                document.getElementById('editRecipeModal').style.display = 'none';
                // Reload recipes table
                loadUserRecipes();
            } else {
                alert(data.message || 'Failed to update recipe');
            }
        } catch (error) {
            console.error('Error saving recipe:', error);
            alert('Failed to save recipe changes');
        }
    }

    // Update the loadUserRecipes function to include data attributes
    async function loadUserRecipes() {
        try {
            const response = await fetch('Profile/get_user_recipes.php');
            const data = await response.json();

            const recipesTableBody = document.querySelector('#userRecipes tbody');
            recipesTableBody.innerHTML = '';

            if (data.success && data.recipes.length > 0) {
                data.recipes.forEach(recipe => {
                    const row = document.createElement('tr');
                    row.setAttribute('data-recipe-id', recipe.id);
                    row.innerHTML = `
                        <td data-field="title">${recipe.title}</td>
                        <td data-field="category">${recipe.category}</td>
                        <td data-field="cooking_time">${recipe.cooking_time}</td>
                        <td data-field="ingredients">${recipe.ingredients}</td>
                        <td>${new Date(recipe.created_at).toLocaleDateString()}</td>
                        <td>
                            <button onclick="editRecipe(${recipe.id})" class="btn btn-small">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteRecipe(${recipe.id})" class="btn btn-small btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    `;
                    recipesTableBody.appendChild(row);
                });
            } else {
                recipesTableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="no-recipes">No recipes created yet</td>
                    </tr>
                `;
            }
        } catch (error) {
            console.error('Error:', error);
            document.querySelector('#userRecipes tbody').innerHTML = `
                <tr>
                    <td colspan="6" class="error">Failed to load recipes</td>
                </tr>
            `;
        }
    }

    // Load user recipes when page loads
    loadUserRecipes();

    // Modal close functionality
    const modal = document.getElementById('editRecipeModal');
    const closeBtn = document.querySelector('.close-modal');

    // Close modal when clicking the close button
    closeBtn.onclick = function () {
        modal.style.display = "none";
    }

    // Close modal when clicking outside
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Make deleteRecipe function globally available
    window.deleteRecipe = async function (recipeId) {
        // Show confirmation dialog
        if (!confirm('Are you sure you want to delete this recipe?')) {
            return;
        }

        try {
            const response = await fetch('Profile/delete_recipe.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: recipeId })
            });

            const data = await response.json();

            if (data.success) {
                // Remove the row from the table
                const row = document.querySelector(`tr[data-recipe-id="${recipeId}"]`);
                if (row) {
                    row.remove();
                }
                // Optionally reload the entire table
                loadUserRecipes();
            } else {
                alert(data.message || 'Failed to delete recipe');
            }
        } catch (error) {
            console.error('Error deleting recipe:', error);
            alert('Failed to delete recipe');
        }
    }

    // Add Recipe Modal functionality
    const addRecipeBtn = document.getElementById('addRecipeBtn');
    const addRecipeModal = document.getElementById('addRecipeModal');
    const addModalClose = addRecipeModal.querySelector('.close-modal');

    addRecipeBtn.onclick = function () {
        addRecipeModal.style.display = 'block';
    }

    addModalClose.onclick = function () {
        addRecipeModal.style.display = 'none';
    }

    // Update window click handler to close both modals
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
        if (event.target == addRecipeModal) {
            addRecipeModal.style.display = "none";
        }
    }

    // Make saveNewRecipe function globally available
    window.saveNewRecipe = async function () {
        try {
            const recipeData = {
                title: document.getElementById('newTitle').value,
                ingredients: document.getElementById('newIngredients').value,
                cooking_time: parseInt(document.getElementById('newCookingTime').value),
                category: document.getElementById('newCategory').value
            };

            const response = await fetch('Profile/add_recipe.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(recipeData)
            });

            const data = await response.json();

            if (data.success) {
                // Close modal and reset form
                addRecipeModal.style.display = 'none';
                document.getElementById('addRecipeForm').reset();
                // Reload recipes table
                loadUserRecipes();
            } else {
                alert(data.message || 'Failed to create recipe');
            }
        } catch (error) {
            console.error('Error creating recipe:', error);
            alert('Failed to create new recipe');
        }
    }
});