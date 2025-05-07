export const urlGetAppointments = "/production/planning/get-appointments";
export const urlSaveAppointments = "/production/planning/save-appointment";
export const urlDeleteAppointments = "/production/planning/delete-appointment";
export const urlGetOrders = "/production/production/get-commandes";
export const urlSyncSchedule = "/production/orders/sync-schedule";

// Optionally expose CSRF token
window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


