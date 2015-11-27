<?php

// Classe métier générique à accès BD automatique
// ToDo : non duplication des instances de classes liées
// ToDo : modèle hiérarchique

class Model {

	// Un appel au constructeur sans id créées une instance et une ligne dans la db
	public function __construct($id=null) {
		$class = get_class($this);
		$table = strtolower($class);
        $idtable = substr($table,-3)."_id";
		if ($id == null) {
			$st = db()->prepare("insert into $table default values returning $idtable");
			$st->execute();
			$row = $st->fetch();
			$field = $idtable;
			$this->$field = $row[$field];
		} else {
			$st = db()->prepare("select * from $table where $idtable=:id");
			$st->bindValue(":id", $id);
			$st->execute();
			if ($st->rowCount() != 1) {
				throw new Exception("Not in table: ".$table." id: ".$id );
			} else {
				$row = $st->fetch(PDO::FETCH_ASSOC);
				foreach($row as $field=>$value) {
					if (substr($field, -2) == "id") {
                        $tables = [
                            'adr' => 'T_E_ADRESSE_ADR',
                            'avi' => 'T_E_AVIS_AVI',
                            'cli' => 'T_E_CLIENT_CLI',
                            'com' => 'T_E_COMMANDE_COM',
                            'jeu' => 'T_E_JEUVIDEO_JEU',
                            'mot' => 'T_E_MOTCLE_MOT',
                            'pho' => 'T_E_PHOTO_PHO',
                            'rel' => 'T_E_RELAIS_REL',
                            'vid' => 'T_E_VIDEO_VID',
                            'ale' => 'T_J_ALERTE_ALE',
                            'ava' => 'T_J_AVISABUSIF_AVA',
                            'avd' => 'T_J_AVISDECONSEILLE_AVD',
                            'avr' => 'T_J_AVISRECOMMANDE_AVR',
                            'fav' => 'T_J_FAVORI_FAV',
                            'gej' => 'T_J_GENREJEU_GEJ',
                            'jer' => 'T_J_JEURAYON_JER',
                            'lec' => 'T_J_LIGNECOMMANDE_LEC',
                            'rec' => 'T_J_RELAISCLIENT_REC',
                            'con' => 'T_R_CONSOLE_CON',
                            'edi' => 'T_R_EDITEUR_EDI',
                            'gen' => 'T_R_GENRE_GEN',
                            'pay' => 'T_R_PAYS_PAY',
                            'ray' => 'T_R_RAYON_RAY'
                        ];
						$linkedField = $tables[substr($field, 0,3)];
						$linkedClass = $linkedField;
						if ($linkedClass != get_class($this))
							$this->$linkedField = new $linkedClass($value);
						else
							$this->$field = $value;
					} else
						$this->$field = $value;
				}
			}
		}

	}

	public static function findAll() {
		$class = get_called_class();
		$table = strtolower($class);
        $idtable = substr($table,-3)."_id";
		$st = db()->prepare("select $idtable from $table");
		$st->execute();
		$list = array();
		while($row = $st->fetch(PDO::FETCH_ASSOC)) {
			$list[] = new $class($row[$idtable]);
		}
		return $list;
	}


	public function __get($fieldName) {
		$varName = "_".$fieldName;

		if (property_exists(get_class($this), $varName)) {
			return $this->$varName;
		} else {
            throw new Exception("Unknown variable: ".$fieldName);
        }
	}


	public function __set($fieldName, $value) {
		$varName = "_".$fieldName;
		if ($value != null) {
			if (property_exists(get_class($this), $varName)) {
				$this->$varName = $value;
				$class = get_class($this);
				$table = strtolower($class);
                $idtable = substr($table,-3)."_id";
				$id = $idtable;
				if (isset($value->$id)) {
					$fieldName = $fieldName.'_id';
					$st = db()->prepare("update $table set $fieldName=:val where $idtable=:id");

					echo "update $table set $fieldName=:val where $idtable=:id";
					$st->bindValue(":val", $value->$id);
				} else {
					$st = db()->prepare("update $table set $fieldName=:val where $idtable=:id");
					$st->bindValue(":val", $value);
				}
				$id = $idtable;
				$st->bindValue(":id", $this->$id);
				$st->execute();
			} else
				throw new Exception("Unknown variable: ".$fieldName);
		}
	}

	// à surcharger
	public function __toString() {
		return get_class($this).": ";
	}

}



