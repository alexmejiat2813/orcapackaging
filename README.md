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