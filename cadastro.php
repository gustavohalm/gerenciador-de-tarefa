<?php
	session_start();
	require_once("class/connection.php");
	if(isset($_SESSION['usuario_on']))
	{
		$user        = $_SESSION['usuario_on'];
		$sql_user    = "SELECT * FROM usuarios WHERE login ='" . $user ."'";
		$query_user  = mysqli_query($conn,$sql_user);
		$linhas_user = mysqli_num_rows($query_user); 
		if($linhas_user > 0)
		{
			header("Location:index.php");
		}	
	}
	if(isset($_POST['user']) && isset($_POST['pass']))
	{
		$user_login = $_POST['user'];
		$pass_login = $_POST['pass'];
		$sql        = "SELECT * FROM usuarios WHERE login ='" . $user_login."'";
		$query      = mysqli_query($conn,$sql);
		$linhas     = mysqli_num_rows($query); 
		if($linhas > 0)
		{
			echo("<script>alert('Usuário já cadastrado!');</script>");
		}
		else
		{
			$user_cadastro  = $_POST['user'];
			$pass_cadastro  = $_POST['pass'];
			$name_cadastro  = $_POST['name'];
			$pass2_cadastro = $_POST['pass2'];
			$dp_cadastro    = $_POST['departamento'];
			if($pass_cadastro == $pass2_cadastro)
			{
				$sql_select   = "SELECT id FROM departamentos WHERE nome = '". $dp_cadastro ."'";
				$query_select = mysqli_query($conn, $sql_select);
				$dp_id = mysqli_fetch_assoc($query_select);	
				
				$sql_cadastrar  = "INSERT INTO usuarios(id, nome, login, senha, status, departamentos_id) VALUES(NULL, '" . $name_cadastro . "', '" . $user_cadastro . "', '" . $pass_cadastro. "','comum', '" . $dp_id['id'] . "'  )";
				$query_cadastro = mysqli_query($conn, $sql_cadastrar);
				if($query_cadastro)
				{
					echo("<script>alert('Cadastro realizado com Sucesso!!');</script>");
					header("Location:login.php?c=s");
				}
				else
				{
					echo("<script>alert('Cadastro não pode ser realizado, favor tentar novamente.');</script>");

				}
			}
			else
			{
				echo("<script>alert('As senhas não iguais!!!');</script>");
			}
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Cadastro - Gerenciador</title>
	<style>
	@import url('https://fonts.googleapis.com/css?family=Roboto+Mono');
	body{
		background-color: #333;
	}
		.login{
			width: 450px;
			height: 580px;
			background-color: white;
			margin-top: 5%;
		}
		input{
			height:50px;
			width:350px;
		}
		select{
			height:50px;
			width:350px;
		}
		#btn{
			background-color: #333;
			color:white;
			font-size: 25px;
			border: none;
			font-family: 'Roboto Mono', monospace;
		}
		.logins{
			border: none;
			border-bottom: #333 solid 2px;
			
			font-family: 'Roboto Mono', monospace;
		}
		#esqueci
		{
			color:#aaa;
			font-size: 11px; 
			font-family:'Roboto Mono', monospace;
		}
		#esqueci:hover
		{
			text-decoration: underline;
			color:#333
		}
	</style>
	<script>
		function esqueci_senha()
		{
			alert("Contate o T.I");
		}
	</script>
</head>
<body>
	<center>
	<h1 style="color:white; font-family: 'Roboto Mono', monospace;">Gerenciador de Tarefas - Área de Cadastrado </h1>
		<div class="login"><br>
			<form method="post" action="cadastro.php">
				<input type="text" name="name" placeholder="Nome" class="logins" required><br><br>
				<input type="text" name="user" placeholder="Usuário" class="logins" required><br><br>
				<input type="password" name="pass" placeholder="*******" class="logins" required><br><br>
				<input type="password" name="pass2" placeholder="******* (confirme)" class="logins" required><br><br>
				<select name="departamento" class="logins" required>
					<option value="contabil"> Departamento Contábil</option>
					<option value="fiscal"> Departamento Fiscal</option>
					<option value="pessoal"> Departamento Pessoal</option>
				<select><br><br>
				<input type="submit" name="acessar" id="btn" value="Cadastrar">
			</form>
		</div>
	</center>
</body>
</html>