window.onload = function() {
    // Handle remove teacher forms
    const removeTeacherForms = document.querySelectorAll('.remove-teacher');
    removeTeacherForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const confirmed = confirm('Are you sure you want to remove yourself as a teacher from this course?');
            if (!confirmed) {
                e.preventDefault();
            }
        });
    });

    // Handle add teacher forms
    const addTeacherForms = document.querySelectorAll('.add-teacher');
    addTeacherForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const confirmed = confirm('Are you sure you want to add yourself as a teacher to this course?');
            if (!confirmed) {
                e.preventDefault();
            }
        });
    });

    // Handle enroll student forms
    const enrollStudentForms = document.querySelectorAll('.enroll-student');
    enrollStudentForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const confirmed = confirm('Are you sure you want to enroll in this course?');
            if (!confirmed) {
                e.preventDefault();
            }
        });
    });

    // Handle unenroll student forms
    const unenrollStudentForms = document.querySelectorAll('.unenroll-student');
    unenrollStudentForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const confirmed = confirm('Are you sure you want to unenroll from this course?');
            if (!confirmed) {
                e.preventDefault();
            }
        });
    });
};
window.onload = function() {
    // Handle remove teacher forms
    const removeTeacherForms = document.querySelectorAll('.remove-teacher');
    removeTeacherForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const confirmed = confirm('Are you sure you want to remove yourself as a teacher from this course?');
            if (!confirmed) {
                e.preventDefault();
            }
        });
    });

    // Handle add teacher forms
    const addTeacherForms = document.querySelectorAll('.add-teacher');
    addTeacherForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const confirmed = confirm('Are you sure you want to add yourself as a teacher to this course?');
            if (!confirmed) {
                e.preventDefault();
            }
        });
    });

    // Handle enroll student forms
    const enrollStudentForms = document.querySelectorAll('.enroll-student');
    enrollStudentForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const confirmed = confirm('Are you sure you want to enroll in this course?');
            if (!confirmed) {
                e.preventDefault();
            }
        });
    });

    // Handle unenroll student forms
    const unenrollStudentForms = document.querySelectorAll('.unenroll-student');
    unenrollStudentForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const confirmed = confirm('Are you sure you want to unenroll from this course?');
            if (!confirmed) {
                e.preventDefault();
            }
        });
    });

    setTimeout(function() {
        var successMessage = document.getElementById('success-message');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 2000);

    // Automatically clear the error message after 2 seconds
    setTimeout(function() {
        var errorMessage = document.getElementById('error-message');
        if (errorMessage) {
            errorMessage.style.display = 'none';
        }
    }, 2000);

    // Scroll to student submissions if 'page' parameter exists
    if (document.getElementById('assessment-show-page')) {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('page')) {
            // Scroll to the 'student-submissions' section if 'page' exists
            document.getElementById('student-submissions').scrollIntoView();
        }
    }
};
