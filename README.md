Pour build le Dockerfile:
docker-compose build

Pour lancer les différents conteneurs: 
docker-compose up -d

Pour arrêter docker :
docker-compose stop

Pour supprimer les containers :
docker-compose down


Pour supprimer les containers avec les volumes :
docker-compose down --volumes

Pour accéder en local:
Symfony : http://127.0.0.1:8082
PhpMyAdmin : http://127.0.0.1:8083

Pour éxec les commandes symfony:
docker exec -it php2 bash