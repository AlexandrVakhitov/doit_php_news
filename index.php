<!DOCTYPE html>
<html lang="en">
<head>
    <title>Новости</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap-reboot.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<? require_once('connect.php');

$perpage = 3;
//Всего записей
$cnt = 0;
if ($query = mysqli_query($conn, "SELECT COUNT(id) AS cnt2 FROM `news` ORDER BY UNIX_TIMESTAMP(data) DESC")) ;{

    mysqli_data_seek($query, 0);

    $row = mysqli_fetch_assoc($query);

    $cnt = $row['cnt2'];

    }

//Количество страниц
    //Деление на количество показов и округление результата в большую сторону
$pages = ceil($cnt / $perpage);

//Текущая страница
$cur_page = 1;
if (isset($_GET['page']) and is_numeric($_GET['page'])) {
    $cur_page = $_GET['page'];

    //Проверяем ограничения
    // стр. не больше числа страниц
    if ($cur_page > $pages) {
        $cur_page = $pages;
    }
    }

//С какой записи выводить
    //Ограничение на вывод С какой и сколько
$from = ($cur_page - 1) * $perpage;

//Выводим список новостей
if ($query = mysqli_query($conn, "SELECT * FROM `news` ORDER BY UNIX_TIMESTAMP(data) DESC LIMIT " . $from . "," . $perpage) and mysqli_fetch_assoc($query) != '') {

    mysqli_data_seek($query, 0);

    $out = ' 
 <div class="container"> 
    <table class="table" cellpadding="5" cellspacing="0">
        <tr scope="row">
            <td scope="row"><b>Название</b></td>
            <td scope="row"><b>Текст</b></td>
            <td scope="row"> <b>Дата</b></td>
    </tr>';

    while ($row = mysqli_fetch_assoc($query)) {
        $out .=
            '<tr>
        <td scope="col">' . $row['name'] . '</td>
        <td scope="col">' . $row['text'] . '</td>
        <td scope="col">' . $row['data'] . '</td>
    </tr>';
    }

    $out .= '
</table>
</div>';


}
//Вывод погинации
for ($i = 1; $i <= $pages; $i++) {

        $out .='<nav aria-label="Page navigation example">';
    $out .='<ul class="pagination justify-content-center">';
    if ($i == $cur_page) {

        $out .= '<li class="page-item"><a class="text-danger page-link mx-2" href="?page='.$i.'">' . $i . '</a></li>';


    } else {
        $out .= '<li class="page-item"><a class="page-link mx-2" href="?page='.$i.'">' . $i . '</a></li>';

    }

}
$out .='</ul></nav>';
echo $out;
?>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>