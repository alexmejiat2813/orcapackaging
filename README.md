# orcapackaging

# Pre-requis

- Installer Laragon : https://laragon.org/download/
- Definir un .env avec les valeurs correspondants a votre base de donnees (voir exemple)
- Installation des drivers ODBC pour SQL Server (voir plus bas)

# Setup de Laragon

Placer ce repertoire a l'interieur du repertoire : C:/laragon/www/   
Ensuite, il faut redefinir le document root de Laragon en allant dans : Menu > Preferences > Document Root : C:\laragon\www\orcapackaging\public    
Verifier que le DocumentRoot a bien ete modifie en se rendant dans Menu > Apache > httpd.conf et en se rendant a la ligne commencant par DocumentRoot

# Installation des drivers ODBC

Il faut que vous vous rendiez sur https://github.com/microsoft/msphpsql/releases puis que vous installiez la version de driver correspondant a votre version de PHP.  
Une fois le ZIP telecharge, il faut extraire seulement deux fichier :   
- php_sqlsrv_{votre_version_PHP}_ts.dll   
- php_pdo_sqlsrv_{votre_version_PHP}_ts.dll  

Renommez ces deux fichiers en juste :  

- php_sqlsrv.dll   
- php_pdo_sqlsrv.dll  

Deplacez ces fichiers ici : C:\laragon\bin\php{votre_version_PHP}\ext   
Ensuite, il faut inserer ces lignes dans C:\laragon\bin\php{votre_version_PHP}\php.ini
- extension=sqlsrv
- extension=pdo_sqlsrv 

# Installation du certificat

AU CAS OU QUAND LE CERTIFICAT AURA EXPIRE

Entrer dans le powershell de la machine de Thomas et ecrire ces lignes :
```
- $cert = New-SelfSignedCertificate `
  -Subject "CN=$(hostname)" `
  -KeyExportPolicy Exportable `
  -KeySpec KeyExchange `
  -Provider "Microsoft RSA SChannel Cryptographic Provider" `
  -CertStoreLocation "cert:\LocalMachine\My" `
  -FriendlyName "SQLServerSSL" `
  -NotAfter (Get-Date).AddYears(5) `
  -TextExtension @("2.5.29.37={text}1.3.6.1.5.5.7.3.1")
```
OPTIONNEL (pour verifier que le certificat est bon)
```
$cert | Select-Object Subject, Thumbprint, HasPrivateKey, PrivateKey
```

Puis Win + R > `certlm.msc` > Clic droit sur le certificat (appele normalement ORCA-SERVER) > Gerer les cles privees.

Ajouter les comptes :

- `NT Service\MSSQL$SQLEXPRESS`
- `NT Service\MSSQL$THOMAS`

Maintenant, ouvrir C:\Windows\SysWOW64\SQLServerManager{NB}.msc   
Clics-droits sur Protocols for THOMAS et Protocols for SQLEXPRESS > Se rendre dans Certificat > Ajouter le certificat > APPLIQUER
Redemarrer les 2 services dans SQL Server Services  

Ensuite, il faut aller chercher le certificat dans :  
- Win + R > certlm.msc > Personnel > Certificats > Clic-droit sur le certificat > Toutes les taches > Exporter > Ne pas exporter la cle privee et le sortir en .cer

Une fois cela fait, il faut acceder a ce fichier .cer sur la machine sur laquelle vous desirez vous connecter a la base de donnees de Thomas.  
Double-clic sur le fichier et installer le certificat.

Le projet ne devrait plus donner d'erreur de certificat.