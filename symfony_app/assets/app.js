import './stimulus_bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

document.addEventListener('turbo:load', function () {
    const editModal = document.getElementById('editUserModal');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const url = button.getAttribute('data-url');
            const firstName = button.getAttribute('data-first-name');
            const lastName = button.getAttribute('data-last-name');
            const gender = button.getAttribute('data-gender');
            const birthdate = button.getAttribute('data-birthdate');

            const form = editModal.querySelector('form');
            form.action = url;

            const firstNameInput = document.getElementById('edit_first_name');
            const lastNameInput = document.getElementById('edit_last_name');
            const genderInput = document.getElementById('edit_gender');
            const birthdateInput = document.getElementById('edit_birthdate');

            if (firstNameInput) firstNameInput.value = firstName;
            if (lastNameInput) lastNameInput.value = lastName;
            if (genderInput) genderInput.value = gender;
            if (birthdateInput) birthdateInput.value = birthdate;
        });
    }

    const deleteModal = document.getElementById('deleteUserModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const url = button.getAttribute('data-url');
            const firstName = button.getAttribute('data-first-name');
            const lastName = button.getAttribute('data-last-name');
            const gender = button.getAttribute('data-gender');

            const form = deleteModal.querySelector('form');
            form.action = url;

            document.getElementById('delete_user_name').textContent = `${firstName} ${lastName}`;
            document.getElementById('delete_first_name').value = firstName;
            document.getElementById('delete_last_name').value = lastName;
            document.getElementById('delete_gender').value = gender;
        });
    }
});
