<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8');
	require_once("class/connection.php");
	if(isset($_SESSION['usuario_on']))
	{
		$user        = $_SESSION['usuario_on'];
		$sql_user    = "SELECT * FROM usuarios WHERE login ='" . $user ."'";
		$query_user  = mysqli_query($conn,$sql_user);
		$linhas_user = mysqli_num_rows($query_user); 
		if($linhas_user == 0)
		{
			header("Location:login.php");
		}
		$dados_user  = mysqli_fetch_assoc($query_user);
		$responsa    = $dados_user['id'];
		$user_status = $dados_user['status'];
		$departament = $dados_user['departamentos_id'];
		//RECUOPERAR USUÁRIOS DO MESMO DEPARTAMENTO;
		$sql_parcas    = "SELECT * FROM  usuarios WHERE departamentos_id = '" . $departament . "'";
		$query_parcas  = mysqli_query($conn, $sql_parcas);
		if(isset($_POST['add']))
		{
			$data 		 = $_POST['data'];
			$nome 		 = $_POST['nome'];
			$desc 		 = $_POST['descricao'];
			$prioridade  = $_POST['prioridade'];
			$status  	 = "FF0000";//background-color value
			$responsable = $responsa;
			if(isset($_POST['responsavel']) && $_POST['responsavel'] != "")
			{
				$responsable =  $_POST['responsavel'];
			}
			$sql_inserir   = "INSERT INTO tasks(id, descricao, status, prazo, prioridade, usuarios_id, departamentos_id) VALUES(NULL, '" . $desc . "', '" . $status . "', '" . $data . "', '" . $prioridade . "', '" . $responsable . "', '" . $departament . "' )";
			$query_inserir = mysqli_query($conn, $sql_inserir);
			if(! $query_inserir)
			{
				echo("<script>alert('Erro ao inserir Tarefa, tente novamente!');</script>");
			}
		}
		
		if(isset($_GET['s']))
		{
			$new_status   = $_GET['s'];
			$task_id      = $_GET['id'];
			
			$sql_select_status   = "SELECT * FROM tasks WHERE id = '" . $task_id . "'";
			$query_select_status = mysqli_query($conn, $sql_select_status);
			$dados_select_status = mysqli_fetch_assoc($query_select_status);
			if ($dados_select_status['usuarios_id'] == $responsa)
			{
				$sql_update   = "UPDATE tasks SET status = '". $new_status . "' WHERE id = '". $task_id . "'";
				$query_update = mysqli_query($conn, $sql_update);
				if(! $query_update)
				{
					echo ("<script>alert('Erro ao alterar Status da Tarefa, favor tentar novamente!');</script>");
				}
				
			}
			else
			{
				echo ("<script>alert('Você não pode alterar a tarefa de outro usuário!');</script>");
			}
		}
	}
	else
	{
		header("Location:login.php");	
	}
	
?>
<!DOCTYPE html>
<html>
<head>
	<title> ASKA - Tarefas </title>
	<meta charset="utf-8" />
	<link rel="stylesheet" type="text/css" href="css/index.css" >
	<link href="https://fonts.googleapis.com/css?family=Roboto+Mono" rel="stylesheet">
</head>
<body>
	<header class='cabecalho'>
		<img id='logo' src='images/logo.png' >
		<h1>Gerenciar Tarefas</h1>
		<a href="javascript: openAdicionar()" class='nova'>Nova Tarefa</a>
		<a style="background-color: #333; color:#aaa; margin-left: 30px; font-size: 10px;" href="logout.php">sair</a>
	</header>
	<div class='interface'>
	<center>
		<div class='tarefas'>
				<div class="tasks">
					<center>
						<ul>
							<li class='tarefa' style="background-color: #10647E; border-left: #333 solid 15px; border-bottom: #333 solid 0.019px;"><a class="anchors" style="color:white;">Tarefa</a></li>
							<li class='responsavel' style="background-color: #10647E;border-bottom: #333 solid 0.019px; " ><a class="anchors" style="color:white;">Responsável</a></li>
							<li class='prazo' style="background-color: #10647E; border-bottom: #333 solid 0.019px;"><a class="anchors" style="color:white;">Prazo</a></li>
							<li class='prazo' style="background-color: #10647E; border-bottom: #333 solid 0.019px;"><a class="anchors" style="color:white;">Prioridade</a></li> 
							<li class='status' style="background-color: #10647E; border-bottom: #333 solid 0.019px;"><a class="anchors" style="color:white;">Status</a></li>
							<br>
							<?php 
								$select_id   = 0;
								if($user_status  == 'master')
								{
									$sql_tasks 	 = "SELECT * FROM tasks";	
								}
								else
								{
									$sql_tasks 	 = "SELECT * FROM tasks WHERE departamentos_id = '" . $departament . "'";
								}
								$query_tasks = mysqli_query($conn, $sql_tasks);
								while( $tasks= mysqli_fetch_array($query_tasks,MYSQLI_ASSOC) )
								{
									$sql_task_responsavel   = "SELECT nome FROM usuarios WHERE id = '" . $tasks['usuarios_id'] . "'";
									$query_task_responsavel = mysqli_query($conn, $sql_task_responsavel);
									$task_responsavel       = mysqli_fetch_assoc($query_task_responsavel);		
									$data     = explode('-', $tasks['prazo']);
									$data_str = $data[2] . "/" . $data[1] . "/" . $data[0];
									echo ( "<li class='tarefa' style='border-left: #87FF9A solid 15px; border-radius: 4px 0px 0px 10px; border-bottom: #333 solid 0.019px;'> <a class='anchors2' style='color: #333;'>" . $tasks['descricao'] . "</a>  </li>" );
									echo ( "<li class='responsavel' style='border-bottom: #333 solid 0.019px;'> <a class='responsa' style='color: #333;'>" . $task_responsavel['nome'] . "</a> </li>" );
									echo ( "<li class='prazo' > <a style='color: #333;'>" . $data_str  . "</a> </li>" );
									
									if( $tasks['prioridade'] == 'baixa' )
									{
										echo ( "<li class='prazo' style='background-color:#22ab4e;  border-bottom: #333 solid 0.019px;'> <a  style='color:white; font-size: 20px; font-weight:bold; margin-top: 2px;' >" . $tasks['prioridade']  . "</a> </li>" );
									}
									elseif( $tasks['prioridade'] == 'medio' )
									{
										echo ( "<li class='prazo' style='background-color:#201D41; border-bottom: #333 solid 0.019px; ' > <a style='color:white; font-size: 20px; font-weight:bold; margin-top: 2px;'>" . $tasks['prioridade']  . "</a> </li>" );										
									}
									else
									{
										echo ( "<li class='prazo' style='background-color:#bd1414; border-bottom: #333 solid 0.019px;'> <a  style='color:white; font-size: 20px; font-weight:bold; margin-top: 2px;'>" . $tasks['prioridade']  . "</a> </li>" );
									}
									
									
									if( $tasks['status'] == 'FF0000' )
									{
										echo ( "<li class='status'> <select class='drop' name='drop' id='" . $tasks['id'] . "' style='background-color:#" . $tasks['status'] . ";' onchange='change_status(this.id)'> <option id='nope' value='FF0000' selected>Não Realizado</option> <option value='fdcd07' id='realizando'>Realizando</option> <option id='feito' value='08fb00'>Feito</option> </select></li>" );
									}
									elseif( $tasks['status'] == 'fdcd07' )
									{
										echo ( "<li class='status'> <select class='drop' name='drop' id='" . $tasks['id'] . "' style='background-color:#" . $tasks['status'] . ";' onchange='change_status(this.id)'> <option id='nope' value='FF0000'>Não Realizado</option> <option value='fdcd07' id='realizando' selected>Realizando</option> <option id='feito' value='08fb00'>Feito</option> </select></li>" );		
									}
									else
									{
										echo ( "<li class='status'> <select class='drop' name='drop' id='" . $tasks['id'] . "' style='background-color:#" . $tasks['status'] . ";' onchange='change_status(this.id)'> <option id='nope' value='FF0000'>Não Realizado</option> <option value='fdcd07' id='realizando'>Realizando</option> <option id='feito' value='08fb00' selected>Feito</option> </select></li>" );		
									}
									echo ( "<br>" );
									echo("<script>setColor('". $tasks['id'] ."');</script><BR>");
								}
								  
							?>
						</ul>
					</center>
				</div>
		</div>	
	</center>
		<center>
			<div id='adicionar'>
			<a style="color:#aaa; font-size: 35px; margin-left: 80%;" href="javascript: closeAdicionar()">X</a>
				<h1> Adicione uma Tarefa </h1>
				<form method='POST' action="index.php">
					<label>Descrição da atividade: </label><br><input type="text" placeholder="Ex: Enviar os lançamentos ASKA" name='descricao' class="inputs" maxlength='150' required><br>
					<label>Prazo: </label><br><input type="date"  name='data' class="inputs" required maxlength='8'><br>
					<?php
						if($user_status == 'admin' || $user_status == 'master')
						{
							$sql_departamento 	= "SELECT nome FROM departamentos WHERE id= '" . $departament . "'";
							$query_departamento = mysqli_query($conn, $sql_departamento);
							$dp_nome            = mysqli_fetch_assoc($query_departamento);
							echo("<label>Responsável: </label><br> <select name='responsavel' class='inputs'>");
							while($users_parcas  = mysqli_fetch_array($query_parcas, MYSQLI_ASSOC) )
							{
								echo("<option value='" . $users_parcas['id'] . "' style='color:#333' >". $users_parcas['nome'] . " </option>");
							}		
							echo("</select><br>");
						}
					?>
					<label>Prioridade: </label><br>
					<select name='prioridade'>
						<option value='baixa'> Baixa</option>
						<option value='medio'>Médio</option>
						<option value='urgente'>Urgente</option>
					</select>
					<input type="submit" name="add" value="ADICIONAR" class='btn'>
				</form>
				<br>
			</div>
		</center>
	</div>
</body>
<script>	
	function setColor(id)
	{
		document.getElementById(id).style.backgroundColor = "#" + document.getElementById(id).value;
	}

	function openAdicionar()
	{
		document.getElementById("adicionar").style.display ="block";
		
		document.getElementById("adicionar").style.position ="relative";
	}
	
	function closeAdicionar()
	{
		document.getElementById("adicionar").style.display ="none";
	}
	function change_status(id) 
	{
		status_value = document.getElementById(id).value
		window.location = "index.php?s=" + status_value + "&id=" + id;
	}
</script>
</html>