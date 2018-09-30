<?php

class pgfModel extends Model 
{
    public function __construct(){
        parent::__construct();
    }
    public function nina(Type $var = null)
    {
        $stmt = $this->_db->query("SELECT * FROM ninas;");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function nuevapgf(
       $actividades,
        $area,
        $objetivo,
        $diagnostico,
        $datos
        )
    {
            try{

                $this->_db->beginTransaction();
                /**
                 * INSERTO ACTIVIDAD DE AREA
                 */
                $sql = "INSERT INTO actividades_area VALUES (
                    NULL,
                    :descripcion,
                    :tiempo,
                    :responsable,
                    :evaluacion,
                    :observaciones
                    );";
                $stmt = $this->_db->prepare($sql);
                $stmt->execute($actividades);
                $lastActiarea = $this->_db->lastInsertId();
                /**
                 * Inserto Datos Area
                 */
                $sql = "INSERT INTO datos_area VALUES (
                    NULL,
                    :diagnostico_area,
                    :objetivo_area,
                    :evaluacion_global,
                    $lastActiarea
                    
                    );";
                $stmt = $this->_db->prepare($sql);
                $stmt->execute($area);
                $lastAre = $this->_db->lastInsertId();
                /**
                 * INSERTO OBJETIVO GENERAL
                 */
                $sql = "INSERT INTO objetivo_general VALUES (
                    NULL,
                    :consensuado_familia,
                    :consensuado_equipo
                    );";
                $stmt = $this->_db->prepare($sql);
                $stmt->execute($objetivo);
                $lastObj = $this->_db->lastInsertId();
                /**
                 * INSERTO DIAGNOSTICO SITUACION
                 */     
                $sql = "INSERT INTO diagnostico_situacion VALUES (
                    NULL,
                    :diag_familia,
                    :diag_equipo
                    );";
                $stmt = $this->_db->prepare($sql);
                $stmt->execute($diagnostico);    
                $lastDiag = $this->_db->lastInsertId(); 
                /**
                 * Inserto Datos identificacion
                 */  
                $sql= "INSERT INTO datos_pgf VALUES (
                    NULL,
                    :familia, 
                    :etnia, 
                    :acogimiento, 
                    :fecha_elaboracion,
                    :proxima_evaluacion, 
                    :profesional, 
                    :cedula,
                    :nina,
                    $lastDiag,
                    $lastObj,
                    $lastAre 
                );";
                $stmt = $this->_db->prepare($sql);
                $stmt->execute($datos);
               $lastId = $this->_db->lastInsertId(); 
               $this->_db->commit();
               return $lastId;
            }catch(PDOException $e){
                $this->_db->rollBack();
                return $e->getMessage();
            }      
    }
}