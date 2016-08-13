<?php
	//DataBase by Joan Alba Maldonado


    //Permite una demora de carga "infinita":
    if (!ini_get("safe_mode")) { @set_time_limit(0); }

	//Objecto de la base de datos:
	class DataBase
	{
		//Variable de conexion:
		//public $conexion;
        var $conexion;
		//Nombre de la base de dades:
		//public $bd_nombre;
        var $bd_nombre = "";

		//Conectar:
		function connect($host, $user, $pass)
		{
			$this->conexion = @mysqli_connect($host, $user, $pass) or die("No se ha podido realizar la conexion a la base de datos<br>Respuesta de mySQL: " . mysqli_error($this->conexion));
		}

		//Selecciona la base de datos:
		function select($bd_nombre)
		{
			if (trim($bd_nombre) === "") { return FALSE; }
			@mysqli_select_db($this->conexion, $bd_nombre) or die("No se ha podido seleccionar la base de datos $bd_nombre<br>Respuesta de mySQL: " . mysqli_error($this->conexion));
			$this->bd_nombre = $bd_nombre;
		}

		//Desconectar:
		function disconnect()
		{
			@mysqli_close($this->conexion);
		}

		//Realiza una consulta:
		function query($consulta)
		{
			$datos_obtenidos = FALSE;
            if (trim($consulta) != "")
			{
				if (function_exists('mysqli_set_charset'))
				{
					@mysqli_set_charset($this->conexion, 'utf8');
					//@mysql_set_charset('iso-8859-1', $this->conexion);
				}
				else
				{
					@mysqli_query($this->conexion, "SET NAMES 'utf8'");
					//@mysql_query("SET NAMES 'iso-8859-1'", $this->conexion);
				}
				$datos_obtenidos = @mysqli_query($this->conexion, $consulta) or die("No se ha podido hacer en la base de datos la consulta: <b>$consulta</b><br>Respuesta de mySQL: " . mysqli_error($this->conexion));
			} //else { $datos_obtenidos = FALSE; }
			return $datos_obtenidos;
		}
	}