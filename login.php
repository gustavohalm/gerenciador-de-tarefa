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
			$sql_senha    = "SELECT * FROM usuarios WHERE login ='" . $user_login."' AND senha= '". $pass_login ."'";
			$query_senha  = mysqli_query($conn, $sql_senha);
			$linhas_senha = mysqli_num_rows($query_senha);
			if($linhas_senha > 0)
			{
				$_SESSION['usuario_on'] = $user_login;
				header("Location:index.php");
			}
			else
			{
				echo("<script>alert('Senha Incorreta!');</script>");
			}
		}
		else
		{
			echo("<script>alert('Usuário Inválido!');</script>");
		}
	}
	if(isset($_GET['c']))
	{
		if($_GET['c'] == 's')
		{
			echo("<script>alert('Cadastro realizado com Sucesso!');</script>");
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login - Gerenciador</title>
	<style>
	@import url('https://fonts.googleapis.com/css?family=Roboto+Mono');
	body{
		background-color: #333;
	}
		.login{
			width: 400px;
			height: 400px;
			background-color: white;
			margin-top: 5%;
		}
		input{
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
		#btn:hover{
			border-radius: 4px;
			width: 340px;
		}
		#btn:active{
			border-radius: 4px;
			width: 340px;
		}
		.logins{
			border: none;
			border-bottom: #333 solid 2px;
			padding-left: 5px;
			font-family: 'Roboto Mono', monospace;
		}
		.logins:hover{
			border: #333 solid 1px;
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
	<h1 style="color:white; font-family: 'Roboto Mono', monospace;">Gerenciador de Tarefas - Área de Login</h1>
		<div class="login"><br><br><br><br>
			<form method="post" action="login.php">
				<input type="text" name="user" placeholder="Usuário" class="logins" required><br><br>
				<input type="password" name="pass" placeholder="*******" class="logins" required><br><br><br>
				<input type="submit" name="acessar" id="btn" value="Acessar">
			</form>
			<a onclick="esqueci_senha()" id="esqueci">Esqueceu a Senha?</a>
		</div>
	</center>
</body>
</html>