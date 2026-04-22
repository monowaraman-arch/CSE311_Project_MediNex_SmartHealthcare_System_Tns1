<?php

session_start();

if (
    !isset($_SESSION['user']) ||
    $_SESSION['user'] === '' ||
    !isset($_SESSION['usertype']) ||
    $_SESSION['usertype'] !== 'p'
) {
    header('location: ../login.php');
    exit;
}

$useremail = $_SESSION['user'];

require_once __DIR__ . '/../../../connection.php';
require_once __DIR__ . '/../../../includes/auth-helper.php';

date_default_timezone_set('Asia/Dhaka');

$pageTitle = 'Appointments';
$today = date('Y-m-d');
$filterDate = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedDate = trim($_POST['sheduledate'] ?? '');
    if ($submittedDate !== '') {
        $filterDate = $submittedDate;
    }
}

$patient = fetchPatientAppointmentPageUser($database, $useremail);

if ($patient === null) {
    header('location: ../logout.php');
    exit;
}

$userid = (int) $patient['pid'];
$username = $patient['pname'];
$appointments = fetchPatientAppointments($database, $userid, $filterDate);
$popup = buildPatientAppointmentPopup($database, $userid);

function fetchPatientAppointmentPageUser(mysqli $database, string $email): ?array
{
    $statement = $database->prepare('SELECT * FROM patient WHERE pemail = ?');
    $statement->bind_param('s', $email);
    $statement->execute();
    $result = $statement->get_result();
    $row = $result->fetch_assoc();
    $statement->close();

    return $row ?: null;
}

function fetchPatientAppointments(mysqli $database, int $userId, ?string $scheduledDate): array
{
    $sql = "SELECT
                appointment.appoid,
                schedule.scheduleid,
                schedule.title,
                doctor.docname,
                patient.pname,
                schedule.scheduledate,
                schedule.scheduletime,
                appointment.apponum,
                appointment.appodate
            FROM schedule
            INNER JOIN appointment ON schedule.scheduleid = appointment.scheduleid
            INNER JOIN patient ON patient.pid = appointment.pid
            INNER JOIN doctor ON schedule.docid = doctor.docid
            WHERE patient.pid = ?";

    $types = 'i';
    $params = [$userId];

    if ($scheduledDate !== null) {
        $sql .= ' AND schedule.scheduledate = ?';
        $types .= 's';
        $params[] = $scheduledDate;
    }

    $sql .= ' ORDER BY appointment.appodate ASC';

    $statement = $database->prepare($sql);
    $statement->bind_param($types, ...$params);
    $statement->execute();
    $result = $statement->get_result();

    $appointments = [];
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }

    $statement->close();

    return $appointments;
}

function buildPatientAppointmentPopup(mysqli $database, int $userId): ?array
{
    $action = $_GET['action'] ?? null;

    if ($action === null) {
        return null;
    }

    switch ($action) {
        case 'booking-added':
            return [
                'type' => 'booking-added',
                'bookingId' => (int) ($_GET['id'] ?? 0),
            ];

        case 'drop':
            return [
                'type' => 'drop',
                'appointmentId' => (int) ($_GET['id'] ?? 0),
                'title' => $_GET['title'] ?? '',
                'doctorName' => $_GET['doc'] ?? '',
            ];

        case 'view':
            $doctorId = (int) ($_GET['id'] ?? 0);
            if ($doctorId <= 0) {
                return null;
            }

            $doctor = fetchDoctorPopupDetails($database, $doctorId);
            if ($doctor === null) {
                return null;
            }

            return [
                'type' => 'view',
                'doctor' => $doctor,
            ];

        case 'reschedule':
            $appointmentId = (int) ($_GET['id'] ?? 0);
            $currentScheduleId = (int) ($_GET['scheduleid'] ?? 0);

            if ($appointmentId <= 0 || $currentScheduleId <= 0) {
                return null;
            }

            $rescheduleData = fetchReschedulePopupDetails($database, $userId, $appointmentId, $currentScheduleId);
            if ($rescheduleData === null) {
                return null;
            }

            return [
                'type' => 'reschedule',
                'data' => $rescheduleData,
            ];

        case 'rescheduled':
            return [
                'type' => 'rescheduled',
            ];

        default:
            return null;
    }
}

function fetchDoctorPopupDetails(mysqli $database, int $doctorId): ?array
{
    $statement = $database->prepare(
        'SELECT doctor.docname, doctor.docemail, doctor.docnic, doctor.doctel, specialties.sname
         FROM doctor
         LEFT JOIN specialties ON doctor.specialties = specialties.id
         WHERE doctor.docid = ?'
    );
    $statement->bind_param('i', $doctorId);
    $statement->execute();
    $result = $statement->get_result();
    $row = $result->fetch_assoc();
    $statement->close();

    return $row ?: null;
}

function fetchReschedulePopupDetails(
    mysqli $database,
    int $userId,
    int $appointmentId,
    int $currentScheduleId
): ?array {
    $appointmentStatement = $database->prepare(
        "SELECT
            appointment.appoid,
            schedule.scheduleid,
            schedule.docid,
            schedule.title,
            schedule.scheduledate,
            schedule.scheduletime,
            doctor.docname
         FROM appointment
         INNER JOIN schedule ON appointment.scheduleid = schedule.scheduleid
         INNER JOIN doctor ON schedule.docid = doctor.docid
         WHERE appointment.appoid = ? AND appointment.pid = ?"
    );
    $appointmentStatement->bind_param('ii', $appointmentId, $userId);
    $appointmentStatement->execute();
    $appointmentResult = $appointmentStatement->get_result();
    $appointment = $appointmentResult->fetch_assoc();
    $appointmentStatement->close();

    if (!$appointment) {
        return null;
    }

    $scheduleStatement = $database->prepare(
        'SELECT * FROM schedule WHERE docid = ? AND scheduledate >= CURDATE() ORDER BY scheduledate, scheduletime'
    );
    $scheduleStatement->bind_param('i', $appointment['docid']);
    $scheduleStatement->execute();
    $scheduleResult = $scheduleStatement->get_result();

    $scheduleOptions = [];
    while ($schedule = $scheduleResult->fetch_assoc()) {
        if ((int) $schedule['scheduleid'] === $currentScheduleId) {
            continue;
        }

        $booked = getScheduleBookedCount($database, (int) $schedule['scheduleid']);
        $maxPatients = getScheduleMaxPatients($database, (int) $schedule['scheduleid']);
        $available = $maxPatients - $booked;

        if ($available <= 0) {
            continue;
        }

        $scheduleOptions[] = [
            'scheduleid' => (int) $schedule['scheduleid'],
            'label' => $schedule['scheduledate'] . ' @ ' . substr($schedule['scheduletime'], 0, 5)
                . ' (' . $schedule['title'] . ' - ' . $available . ' slots available)',
        ];
    }

    $scheduleStatement->close();

    $error = $_GET['error'] ?? '';
    $errorMessage = '';

    if ($error === 'overlap') {
        $errorMessage = 'This time slot is already booked!';
    } elseif ($error === 'full') {
        $errorMessage = 'This session is full!';
    }

    return [
        'appointmentId' => $appointmentId,
        'title' => $appointment['title'],
        'doctorName' => $appointment['docname'],
        'currentDate' => $appointment['scheduledate'],
        'currentTime' => $appointment['scheduletime'],
        'scheduleOptions' => $scheduleOptions,
        'minDate' => date('Y-m-d'),
        'errorMessage' => $errorMessage,
    ];
}
