/* =============================================
   Eventify - JavaScript Validation
   SEN4001 Web Design & Databases
   ============================================= */

/* Master validation function for the Add Event form.
   Called on form submit. Returns false to stop submission if errors found. */
function validateEventForm(form) {

    // Collect all error messages into one string
    var fail = "";

    // Run each individual validation function
    fail += validateEventName(form.event_name.value);
    fail += validateEventDescription(form.event_description.value);
    fail += validateEventCategory(form.event_category.value);
    fail += validateKeywords(form.keywords.value);
    fail += validateStartDate(form.start_date.value);
    fail += validateEndDate(form.start_date.value, form.end_date.value);

    // If no errors, allow form to submit
    if (fail == "") {
        return true;
    } else {
        // Show all error messages in an alert box
        alert(fail);
        return false;
    }
}

/* Check event name is not empty */
function validateEventName(field) {
    if (field == "") {
        return "Event name is required.\n";
    }
    return "";
}

/* Check event description is not empty */
function validateEventDescription(field) {
    if (field == "") {
        return "Event description is required.\n";
    }
    return "";
}

/* Check a category has been selected */
function validateEventCategory(field) {
    if (field == "") {
        return "Please select an event category.\n";
    }
    return "";
}

/* Check keywords field is not empty */
function validateKeywords(field) {
    if (field == "") {
        return "Please enter at least one keyword.\n";
    }
    return "";
}

/* Check start date is not empty */
function validateStartDate(field) {
    if (field == "") {
        return "Start date is required.\n";
    }
    return "";
}

/* Check end date is not empty and is not before start date */
function validateEndDate(startField, endField) {
    if (endField == "") {
        return "End date is required.\n";
    }
    // Compare dates - end date must not be before start date
    if (startField != "" && endField < startField) {
        return "End date cannot be before the start date.\n";
    }
    return "";
}
