<?php
$db = mysqli_connect('localhost', 'root', '', 'todo');

	// initialize variables
	$name = "";
	$address = "";
	$status = "";
	$id = 0;
	$update = false;
	// array for JSON response
	$response = array();

	if (isset($_POST['save'])) {
		$name = $_POST['task'];
		$address = $_POST['description'];
		$status = $_POST['status'];
		$create_date = date("d-m-y G:i:s");
		$query = "INSERT INTO tasks (task, description, status, create_date) VALUES 
										('$name','$address', '$status', '$create_date')";
		mysqli_query($db, $query); 
		$_SESSION['message'] = "Address saved"; 
		header('location: index.php');
	}
	if (isset($_GET['edit'])) {
		$id = $_GET['edit'];
		$update = true;
		$record = mysqli_query($db, "SELECT * FROM tasks WHERE id=$id");

		if (count($record) == 1 ) {
			$n = mysqli_fetch_array($record);
			$name = $n['task'];
			$address = $n['description'];
		}
	}
	if (isset($_POST['update'])) {
	$id = $_POST['id'];
	$name = $_POST['task'];
	$address = $_POST['description'];
	$status = $_POST['status'];
	mysqli_query($db, "UPDATE tasks SET task='$name', description='$address', status='$status' WHERE id=$id");
	$_SESSION['message'] = "Address updated!"; 
	header('location: index.php');
}
if (isset($_GET['del'])) {
	$id = $_GET['del'];
	mysqli_query($db, "DELETE FROM tasks WHERE id=$id");
	$_SESSION['message'] = "Address deleted!"; 
	header('location: index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" type="text/css" href="style1.css">
	<link rel="stylesheet" type="text/css" href="select.css">
	<script type='text/javascript' src='js/select.js'></script>
	<title>CRUD: CReate, Update, Delete PHP MySQL</title>
</head>
<body>
	<?php if (isset($_SESSION['message'])): ?>
	<div class="msg">
		<?php 
			echo $_SESSION['message']; 
			unset($_SESSION['message']);
		?>
	</div>
<?php endif ?>
<?php
	$tasks = mysqli_query($db, "SELECT * FROM tasks");
	$todo = mysqli_query($db, "SELECT * FROM tasks WHERE status = 'TODO' ORDER BY create_date");
	$doing = mysqli_query($db, "SELECT * FROM tasks WHERE status = 'DOING'ORDER BY create_date");
	$done = mysqli_query($db, "SELECT * FROM tasks WHERE status = 'DONE'ORDER BY create_date");
?>
	<form method="_GET" action="index.php">
		<?php $i = 1;
		if(isset($_GET["json"]))
		{
		$result = $_GET["json"];
		while ($task = mysqli_fetch_array($result))
		{
		echo "JSON<br>Task $i" . json_encode($task['task']) . "<br>";
		echo "Description " . json_encode($task['description']) . "<br>";
		echo "Status " . json_encode($task['status']) . "<br>";
		echo "Create date " . json_encode($task['create_date']) . "<br><hr>";
		$i++;}
		}?>
		<button type="submit" name="json">JSON</button>
	</form>
<form method="post" action="index.php" >
		<div class="input-group">
			<label>Title</label>
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<input type="text" placeholder="Заголовок" name="task" value="<?php echo $name; ?>">
		</div>
		<div class="input-group">
			<label>Description</label>
			<input type="textarea" cols="21" rows="10" name="description" value="<?php echo $address; ?>" placeholder="Описание">
		</div>
			<label class="label">Статус</label><br>
			<select class="cs-select cs-skin-rotate" name="status">
			<option>TODO</option>
			<option>DOING</option>
			<option>DONE</option>
		</select>
		<div class="input-group">
			<?php if ($update == true): ?>
	<button class="btn" type="submit" name="update" style="background: #556B2F;" >update</button>
<?php else: ?>
	<button class="add_btn" type="submit" name="save">Save</button>
<?php endif ?>
		</div>
	</form>
<table>
		<thead>
			<tr>
				<th>TODO</th>
				<th>DOING</th>
				<th>DONE</th>
			</tr>
		</thead>

		<tbody>
			<tr>
				<td>
					<table>
						<tbody>
							<tr>
							<td>
								<?php 
									while($row = mysqli_fetch_array($todo))
									{?>
  									<div class="divdate"><?php echo $row['create_date'];?>
  									<span class="delete">  										
										<a href="index.php?edit=<?php echo $row['id'] ?>">Edit</a>
										<a href="index.php?del=<?php echo $row['id'] ?>">Delete</a>
  									</span> 
									</div>
									<div class="divtask">
									<?php echo $row['task'];?>
									</div>
									<div align="center"> <?php echo $row['description']; ?> </div>
									<?php echo "<hr>";
									};?>
							</td>
							</tr>
						</tbody>
					</table>
				</td>
				<td>
					<table>
						<tbody>
							<tr>
							<td>
								<?php 
									while($row = mysqli_fetch_array($doing))
									{?>
  									<div class="divdate"><?php echo $row['create_date'];?> 
									<span class="delete">  										
										<a href="index.php?edit=<?php echo $row['id'] ?>">Edit</a>
										<a href="index.php?del=<?php echo $row['id'] ?>">Delete</a>
  									</span> 
									</div>
									<div class="divtask">
									<?php echo $row['task'];?>
									</div> 
									<div align="center"> <?php echo $row['description']; ?> </div>
									<?php echo "<hr>";
									};?>
							</td>
							</tr>
						</tbody>
					</table>
				</td>
				<td>
					<table>
						<tbody>
							<tr>
							<td>
								<?php 
									while($row = mysqli_fetch_array($done))
									{?>
  									<div class="divdate"><?php echo $row['create_date'];?> 
									<span class="delete">  										
										<a href="index.php?edit=<?php echo $row['id'] ?>">Edit</a>
										<a href="index.php?del=<?php echo $row['id'] ?>">Delete</a>
  									</span> 
									</div>
									<div class="divtask">
									<?php echo $row['task'];?>
									</div> 
									<div align="center"> <?php echo $row['description']; ?> </div>
									<?php echo "<hr>";
									};?>
							</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
	
</body>
</html>