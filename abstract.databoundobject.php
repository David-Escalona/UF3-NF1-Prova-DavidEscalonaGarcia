<?php

class Logger {
    public static function log($userAppId, $isActive, $code, $comment) {
        $host = 'localhost';
        $dbname = 'usuaris';
        $user = 'postgres';
        $password = 'root';

        try {
            $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Convertir el valor booleano a un tipo de dato compatible con PostgreSQL
            $isActive = $isActive ? 'TRUE' : 'FALSE';

            $statement = $pdo->prepare("INSERT INTO logUserApp (idUserApp, isActive, codi, regtime, comentari) VALUES (?, $isActive, ?, ?, ?)");
            $statement->execute([$userAppId, $code, date('Y-m-d H:i:s'), $comment]);

            echo "Registro guardado en la base de datos.\n";
        } catch (PDOException $e) {
            echo "Error al conectar a la base de datos: " . $e->getMessage() . "\n";
        }
    }
}

abstract class DataBoundObject {
    abstract protected function save();
}

class UserApp extends DataBoundObject {
    private $id;
    private $nom;
    private $group;
    private $created;
    private $lastUpdated;
    private $isActive;

    public function __construct($id, $nom, $group, $created, $lastUpdated, $isActive) {
        $this->id = $id;
        $this->nom = $nom;
        $this->group = $group;
        $this->created = $created;
        $this->lastUpdated = $lastUpdated;
        $this->isActive = $isActive;
    }

    public function save() {
        try {
            // Simulando una validación que impide que isActive sea false
            if ($this->isActive === false) {
                throw new Exception("El campo isActive no puede ser false.");
            }

            // Tu código para guardar el objeto UserApp en la base de datos
            
            echo "UserApp guardado en la base de datos.\n";
            Logger::log($this->id, $this->isActive, 200, "Guardado exitoso");
        } catch (Exception $e) {
            echo "Error al guardar UserApp: " . $e->getMessage() . "\n";
            Logger::log($this->id, $this->isActive, $e->getCode(), $e->getMessage());
        }
    }
}

// Modificar la instancia de UserApp con isActive como false
$user = new UserApp(
    1,
    'Usuario1',
    'Grupo1',
    date('Y-m-d H:i:s'),
    date('Y-m-d H:i:s'), 
    false // Cambiado a false para simular un valor incorrecto
);

$user->save();

?>





 
