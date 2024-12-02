<?php
session_start();
include 'form.html';
function generate_calendar($month, $year, $tasks = []) {
    $month_names = ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь",
                    "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"];
    $days_in_month = date('t', strtotime("$year-$month-01"));
    $first_day_of_week = date('w', strtotime("$year-$month-01"));
    if ($first_day_of_week == 0) {
        $first_day_of_week = 7;
    }
    echo '<style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 20px;
            }
            h2 {
                color: #333;
            }
            table {
                border-collapse: collapse;
                width: 100%;
                margin-top: 20px;
            }
            th, td {
                border: 1px solid #ddd;
                padding: 10px;
                text-align: center;
            }
            th {
                background-color: #4CAF50;
                color: white;
            }
            td {
                background-color: #fff;
            }
            td.othermonth {
                color: lightgray;
            }
            td.weekend {
                background-color: #f9c2c2; /* Цвет для выходных */
            }
            td.task {
                background-color: #c2f9c2; /* Цвет для задач */
            }
           .delete-button {
                color: white;
                background-color: #000;
                border: none;
                padding: 5px 10px;
                border-radius: 5px;
                cursor: pointer;
            }
            .delete-form {
                text-align: right; /* Выравнивание формы вправо */
                margin-top: 10px; /* Отступ сверху */
            }
          </style>';

    echo "<table>";
    echo "<tr><th colspan='7'>{$month_names[$month - 1]} $year</th></tr>";
    echo "<tr><th>Пн</th><th>Вт</th><th>Ср</th><th>Чт</th><th>Пт</th><th class='weekend'>Сб</th><th class='weekend'>Вс</th></tr>";
    echo "<tr>";
    for ($i = 1; $i < $first_day_of_week; $i++) {
        $ii = $i+24;
        echo "<td class='othermonth'>$ii</td>";
    }
    
    for ($day = 1; $day <= $days_in_month; $day++) {
        if (($day + $first_day_of_week) % 7 == 0 || ($day + $first_day_of_week - 1) % 7 == 0) {
            echo "<td class='weekend'>$day</td>"; 
        } else {
            $date_key = "$year-$month-$day";
            if (isset($tasks[$date_key])) {
                echo "<td class='task'><b>$day</b><br>{$tasks[$date_key]}<br>
                      <div class='delete-form'>
                          <form action='index.php' method='post' style='display:inline;'>
                              <input type='hidden' name='delete_date' value='$date_key'>
                              <input type='submit' class='delete-button' value='X'>
                          </form>
                      </div>
                      </td>";
            } else {
                echo "<td>$day</td>";
            }
        }
        if (($day + $first_day_of_week - 1) % 7 == 0) {
            echo "</tr><tr>";
        }
    }   
    for ($i = 1; $i < $first_day_of_week-1; $i++) {
        echo "<td class='othermonth'>$i</td>";
    }
    echo "</tr></table>";
}
$current_month = date('n');
$current_year = date('Y');
$tasks = isset($_SESSION['tasks']) ? $_SESSION['tasks'] : [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_date'])) {
    $delete_date = $_POST['delete_date'];
    unset($_SESSION['tasks'][$delete_date]);
}
generate_calendar($current_month, $current_year, $tasks);
?>
