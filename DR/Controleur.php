<?php
include('includes/dbconnection.php');

class Controleur {

    private $dbh;

    public function __construct($dbh) {
        $this->dbh = $dbh;
    }

    public function charger_ste() {
        $res="";
        try {
            $query = $this->dbh->prepare("SELECT ids,raison_sociale FROM ste");
            $query->execute();
            while($result = $query->fetch()){
                $res.="<option value='{$result[0]}'>{$result[1]}</option>";
            }
            return $res;
        } catch (PDOException $e) {
            echo "Erreur: " . $e->getMessage();
            return false;
        }
    }
    
    public function charger_fornis() {
        $res="";
        try {
            $query = $this->dbh->prepare("SELECT * FROM fournisseur");
            $query->execute();
            while($result = $query->fetch()){
                $res.="<option  value='{$result[0]}'>{$result[1]}</option>";
            }
            return $res;
        } catch (PDOException $e) {
            echo "Erreur: " . $e->getMessage();
            return false;
        }
    }
    public function charger_etablisement() {
        $res="";
        try {
            $query = $this->dbh->prepare("SELECT * FROM etablissement");
            $query->execute();
            while($result = $query->fetch()){
                $res.="<option  value='{$result[0]}'>{$result[2]}</option>";
            }
            return $res;
        } catch (PDOException $e) {
            echo "Erreur: " . $e->getMessage();
            return false;
        }
    }
    
    // public function chercher_etablisement($id_dr) {
    //     try {
    //         $query = $this->dbh->prepare("SELECT * FROM etablissement where id_e={$id_dr}");
    //             $query->execute();
    //         $result = $query->fetch();
    //         return [$result[0],$result[2]];
    //     } catch (PDOException $e) {
    //         echo "Erreur: " . $e->getMessage();
    //         return false;
    //     }
    // }
    public function chercher_ste_ids($ids) {
        try {
            $query = $this->dbh->prepare("SELECT raison_sociale FROM ste where ids={$ids}");
                $query->execute();
            $result = $query->fetch();
        return $result[0];
        } catch (PDOException $e) {
            echo "Erreur: " . $e->getMessage();
            return false;
        }
    }

    public function cherchertt_ste_ids($ids) {
        try {
            $query = $this->dbh->prepare("SELECT * FROM ste where ids={$ids}");
            $query->execute();
            $result = $query->fetch();
            return $result;


        } catch (PDOException $e) {
        
            echo "Erreur: " . $e->getMessage();
            return false;
        }
    }
    public function chercer_demande_prix($id_d) {
        try {
            $query = $this->dbh->prepare("SELECT * FROM demande_prix where id_d={$id_d}");
            $query->execute();
            $result = $query->fetch();
            return $result;
        } catch (PDOException $e) {
            echo "Erreur: " . $e->getMessage();
            return false;
        }
    }
}

?>
