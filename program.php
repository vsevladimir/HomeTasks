<html>
<head>
<title>Навчальна програма</title>
 <link rel="stylesheet" href="http://program.ua/style.css">
</head>
<body>
	<div class="mainn">
	<div class="container-fluid name_zaklad">
		<div class="text">
						Відокремлений структурний підрозділ "Новокаховський політехнічний фаховий коледж Державного університету "Одеська політехніка"
		</div>
	</div>
<?php
	$dbh = new PDO('mysql:host=localhost; dbname=rektor', 'root', '');
	$query = 'SELECT `навантаження`.`id`, `навантаження`.`id_group`, `предмети`.`Назва`, CONCAT(`викладачі`.`Прізвище`," ",SUBSTRING(`викладачі`.`Імя`, 1,1), ".", SUBSTRING(`викладачі`.`По-батькові`,1,1)) AS `викладач` FROM (`навантаження` INNER JOIN `предмети` ON `навантаження`.`id_предм`=`предмети`.`id_пред`) INNER JOIN `викладачі` ON `навантаження`.`id_препода`=`викладачі`.`id` WHERE `навантаження`.`id`='.$_GET['id'];
echo '<br>';
foreach ($dbh->query($query) as $row){
	echo '<f>'.$row['id_group'].' '.$row['Назва'].' '.$row['викладач'].'</f><br>';
echo '<br>';
	$query1='SELECT `мета`.`Мета` FROM `мета` WHERE `мета`.`Предмет`='.$_GET['id'];
	echo '<w>Мета – ';
	foreach ($dbh->query(	$query1) as $row1){
		echo $row1['Мета'].'<br>';
	}echo '</w>'; echo '<br>';

	$query1='SELECT `завдання`.`Завдання` FROM `завдання` WHERE `завдання`.`Предмет`='.$_GET['id'];
	echo '<B>Завдання:</B><br>';
	foreach ($dbh->query(	$query1) as $row1){
		echo '– '.$row1['Завдання'].'<br>';
	}
echo '<br>';
	$query1='SELECT `знати`.`Знати` FROM `знати` WHERE `знати`.`Предмет`='.$_GET['id'];
	echo '<B>На заняттях з дисципліни Алгоритми та структури даних студенти повинні знати:</B><br>';
	foreach ($dbh->query(	$query1) as $row1){
		echo '– '.$row1['Знати'].'<br>';
	}
	echo '<br>';
	$query1='SELECT `вміти`.`Вміти` FROM `вміти` WHERE `вміти`.`Предмет`='.$_GET['id'];
	echo '<B>На заняттях з дисципліни Алгоритми та структури даних студенти повинні вміти:</B><br>';
	foreach ($dbh->query(	$query1) as $row1){
		echo '– '.$row1['Вміти'].'<br>';
	}
	echo '<br>';
	$query1='SELECT `семестр`.`id`, `семестр`.`Назва` FROM `семестр` WHERE `Предмет`='.$_GET['id'];
	echo '<d><center>Структура навчальної дисципліни</center></d>';
	echo '<br>';
	echo '<table><tr><th rowspan="4">№ п/п</th><th rowspan="4">Назви змістовних модулів і тем</th><th colspan="4">Кількість годин</th></tr>';
	echo '<tr><th colspan="4">Денна форма</th></tr>';
	echo '<tr><th rowspan="2">Усього</th><th colspan="3">в тому числі</th></tr>';
	echo '<tr><th>лекції</th><th>ЛР</th><th>СР</th></tr>';

	foreach ($dbh->query(	$query1) as $row1){
		echo '<tr><td colspan="6"><center>Семестр '.$row1['Назва'].'</center></td></tr>';
		$query2='SELECT `модулі`.`id`, `модулі`.`Назва` FROM `модулі` WHERE (SELECT COUNT(`програми`.`id`) FROM `програми` WHERE (`програми`.`Модуль`=`модулі`.`id`) AND(`програми`.`Семестр`="'.$row1['id'].'"))>0';
		foreach ($dbh->query(	$query2) as $row2){
			echo '<tr><td colspan="6"><center>Модуль '.$row2['Назва'].'</center></td></tr>';
			$query3='SELECT DISTINCT `змістові модулі`.`id`,`змістові модулі`.`Назва` FROM `змістові модулі` INNER JOIN `теми` ON `теми`.`Змістовий модуль`=`змістові модулі`.`id`WHERE (SELECT COUNT(`теми`.`id_тм`) FROM `теми` INNER JOIN `програми` ON `програми`.`Тема`=`теми`.`id_тм` WHERE (`теми`.`Змістовий модуль`=`змістові модулі`.`id`) AND (`програми`.`Модуль`="'.$row2['id'].'"))>0';
			foreach ($dbh->query(	$query3) as $row3)
			{
				echo '<tr><td colspan="6"><center>Змістовий модуль '.$row3['Назва'].'</center></td></tr>';
				$query4='SELECT `теми`.`id_тм`, `теми`.`Назва`, (SELECT SUM(`програми`.`Кількість годин`) FROM `програми` WHERE `програми`.`Тема`=`теми`.`id_тм`) AS `усього` FROM `теми` WHERE `теми`.`Змістовий модуль`='.$row3['id'];
				foreach ($dbh->query(	$query4) as $row4)
				{
					echo '<tr><td colspan="2">Тема '.$row4['Назва'].'</td><td>'.$row4['усього'].'</td><td></td><td></td><td></td></tr>';
					$query5='SELECT `програми`.`Номер заняття`, `програми`.`Назва`,`програми`.`Кількість годин`, IF((`програми`.`Тип заняття`="Лекція")OR(`програми`.`Тип заняття`="Контрольна робота"),`програми`.`Кількість годин`,"")AS `Лекції`, IF(`програми`.`Тип заняття`="Лабораторна робота",`програми`.`Кількість годин`,"")AS `ЛР`, IF(`програми`.`Тип заняття`="Самостійна робота",`програми`.`Кількість годин`,"")AS `СР` FROM `програми` WHERE `програми`.`Тема`='.$row4['id_тм'];
					foreach ($dbh->query(	$query5) as $row5)
					{
						echo '<tr><td>'.$row5['Номер заняття'].'</td>';
						echo '<td>'.$row5['Назва'].'</td>';
						echo '<td>'.$row5['Кількість годин'].'</td>';
						echo '<td>'.$row5['Лекції'].'</td>';
						echo '<td>'.$row5['ЛР'].'</td>';
						echo '<td>'.$row5['СР'].'</td></tr>';
					}
				}
				echo '<tr><td colspan="2">Всього за змістовим модулем '.$row3['Назва'].'</td><td>1</td><td>2</td><td>3</td><td>4</td></tr>';
			}
			echo '<tr><td colspan="2">Всього за модулем '.$row2['Назва'].'</td><td>1</td><td>2</td><td>3</td><td>4</td></tr>';
		}
			echo '<tr><td colspan="2">Всього за семестр '.$row1['Назва'].'</td><td>1</td><td>2</td><td>3</td><td>4</td></tr>';
	}

	echo '</table><br>';
	$query1='SELECT `джерела інформації`.`Джерела` FROM `джерела інформації` WHERE `джерела інформації`.`Предмет`='.$_GET['id'];
	echo '<B>Джерела інформації:</B><br>';
	foreach ($dbh->query(	$query1) as $row1){
		echo '– '.$row1['Джерела'].'<br>';
	}
}
?>
</div>
</body>
</html>
