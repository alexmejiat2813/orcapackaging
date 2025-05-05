export const urlGetAppointments = "/production/planning/get-appointments";
export const urlSaveAppointments = "/production/planning/save-appointment";
export const urlDeleteAppointments = "/production/planning/delete-appointment";

// Optionally expose CSRF token
window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
