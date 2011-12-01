function Personn (){ 
	
	this.prenom;
	this.nom;
	
	if ( typeof Personn.initialized == "undefined" ) {

		Personn.prototype.setPrenom = function (name){ 
			this.prenom = name;	
		}

		Personn.prototype.setNom = function (name){ 
			this.nom = name;	
		}

		Personn.prototype.getPrenom = function (){ 
			return this.prenom;	
		}

		Personn.prototype.getNom = function (){ 
			return this.nom;	
		}

		Personn.initialized = true; 
	}
}