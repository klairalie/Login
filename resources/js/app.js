import './bootstrap';

  document.addEventListener('DOMContentLoaded', function () {
        const birthdateInput = document.getElementById('birthdate');
        const ageInput = document.getElementById('age');

        birthdateInput.addEventListener('change', function () {
            const birthdate = new Date(this.value);
            const today = new Date();

            let age = today.getFullYear() - birthdate.getFullYear();
            const m = today.getMonth() - birthdate.getMonth();

            if (m < 0 || (m === 0 && today.getDate() < birthdate.getDate())) {
                age--;
            }

            ageInput.value = age > 0 ? age : 0;
        });
    });
