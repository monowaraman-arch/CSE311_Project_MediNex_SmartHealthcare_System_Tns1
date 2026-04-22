<?php
//  AUTHENTICATION & SECURITY - HELPER FUNCTIONS SECTION START 
//  DATABASE RELATIONS USED: Schedule, Appointment 
// Schedule: Check schedule availability (getScheduleMaxPatients function)
// Appointment: Check appointment overlap and booking count (checkAppointmentOverlap, getScheduleBookedCount functions)

function hashPassword($password) {
    // Hash password using bcrypt
    return password_hash($password, PASSWORD_BCRYPT);
}

function verifyPassword($password, $hash) {
    // Verify password against hash
    return password_verify($password, $hash);
}

function checkAppointmentOverlap($database, $scheduleid, $appodate, $exclude_appoid = null) {
    // Check if appointment slot is already booked
    $sql = "SELECT COUNT(*) as count FROM appointment WHERE scheduleid = ? AND appodate = ? AND status != 'cancelled'";
    if ($exclude_appoid) {
        $sql .= " AND appoid != ?";
    }
    $stmt = $database->prepare($sql);
    if ($exclude_appoid) {
        $stmt->bind_param("isi", $scheduleid, $appodate, $exclude_appoid);
    } else {
        $stmt->bind_param("is", $scheduleid, $appodate);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'] > 0;
}

function getScheduleMaxPatients($database, $scheduleid) {
    // Get maximum patients for a schedule
    $sql = "SELECT nop FROM schedule WHERE scheduleid = ?";
    $stmt = $database->prepare($sql);
    $stmt->bind_param("i", $scheduleid);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['nop'] ?? 0;
}

function getScheduleBookedCount($database, $scheduleid) {
    // Get number of booked appointments for a schedule
    $sql = "SELECT COUNT(*) as count FROM appointment WHERE scheduleid = ? AND status != 'cancelled'";
    $stmt = $database->prepare($sql);
    $stmt->bind_param("i", $scheduleid);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'] ?? 0;
}
// ========== AUTHENTICATION & SECURITY - HELPER FUNCTIONS SECTION END ==========

?>

