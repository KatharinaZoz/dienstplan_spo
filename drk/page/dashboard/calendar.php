<?php
session_start();
$is_admin = isset($_SESSION["is_admin"]) && (int) $_SESSION["is_admin"] === 1 ? true : false;
$admin_calendar_view = $is_admin && $_SESSION["admin_panel"] == 1 ? true : false;
/* 
- get current month
- calculate the amount of table rows needed
- generate the table with each cell as a name and date with the complete date in it
- then get the appointments for that day and apply them with the right colour
*/
?>
<div class="calendar-wrapper">
    <div class="cal-nav-wrapper">
        <button>
            < </button>
                <p>February</p>
                <button>></button>
    </div>
    <table class="calendar-table">
        <tr class="calendar-header">
            <th>Monday</th>
            <th>Tuesday</th>
            <th>Wednesday</th>
            <th>Thursday</th>
            <th>Friday</th>
            <th>Satturday</th>
            <th>Sunday</th>
        </tr>
        <tr>
            <td>01</td>
            <td>02</td>
            <td>03</td>
            <td>04</td>
            <td>05</td>
            <td>06</td>
            <td>07</td>
        </tr>
    </table>

</div>